<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
use App\Models\AiChatSession;
use App\Models\AiChatMessage;
use App\Models\SiteSetting;
use App\Services\GeminiService;
use Illuminate\Http\Request;

class AdvisorController extends Controller
{
    const SESSION_HOURS = 24;

    private static function questionLimit(): int
    {
        return max(1, (int) SiteSetting::get('advisor_question_limit', 15));
    }

    private static function sessionLimit(): int
    {
        return max(1, (int) SiteSetting::get('advisor_session_limit', 1));
    }

    /**
     * Display the AI Advisor page.
     */
    public function index()
    {
        $user          = auth()->user();
        $lastSession   = AiChatSession::where('user_id', $user->id)->latest()->first();
        $questionLimit = self::questionLimit();

        $defaultTips = json_encode([
            'Be specific with your questions for better answers',
            'Ask about college requirements, test prep, extracurriculars, and application tips',
            'The advisor knows you\'re a {grade} student and will tailor advice accordingly',
            'You can ask follow-up questions to get more detailed information',
            'Your chat history is saved — use Load Last Chat to continue where you left off',
        ]);

        $defaultDisclaimer = 'This advisor is intended for use with universities located within the United States only. All information provided is for general guidance purposes and may not reflect the most current institutional policies, requirements, or deadlines. Please verify all details directly with the respective institution before making any decisions.';

        $tips       = json_decode(SiteSetting::get('advisor_tips', $defaultTips), true);
        $disclaimer = SiteSetting::get('advisor_disclaimer', $defaultDisclaimer);

        return view('portal.advisor', compact('user', 'lastSession', 'tips', 'disclaimer', 'questionLimit'));
    }

    /**
     * Create a new chat session, or return the most recent one if the session limit is reached.
     */
    public function newSession()
    {
        $user         = auth()->user();
        $sessionLimit = self::sessionLimit();

        $recentCount = AiChatSession::where('user_id', $user->id)
            ->where('created_at', '>=', now()->subHours(self::SESSION_HOURS))
            ->count();

        if ($recentCount >= $sessionLimit) {
            $recentSession = AiChatSession::where('user_id', $user->id)
                ->where('created_at', '>=', now()->subHours(self::SESSION_HOURS))
                ->latest()
                ->first();

            $messages      = AiChatMessage::where('session_id', $recentSession->id)
                ->orderBy('id')
                ->get(['role', 'content', 'created_at']);
            $questionCount = $messages->where('role', 'user')->count();

            return response()->json([
                'session_id'     => $recentSession->id,
                'already_exists' => true,
                'messages'       => $messages,
                'question_count' => $questionCount,
                'submitted_at'   => $recentSession->submitted_at,
            ]);
        }

        $session = AiChatSession::create(['user_id' => $user->id]);

        // Save the opening greeting as the first model message
        $greeting = $this->buildGreeting($user);
        if ($greeting) {
            AiChatMessage::create([
                'session_id' => $session->id,
                'role'       => 'model',
                'content'    => $greeting,
            ]);
        }

        $messages = AiChatMessage::where('session_id', $session->id)
            ->orderBy('id')
            ->get(['role', 'content', 'created_at']);

        return response()->json([
            'session_id'     => $session->id,
            'already_exists' => false,
            'messages'       => $messages,
            'question_count' => 0,
            'submitted_at'   => null,
        ]);
    }

    /**
     * Load the most recent chat session with its messages.
     */
    public function loadLastSession()
    {
        $user    = auth()->user();
        $session = AiChatSession::where('user_id', $user->id)->latest()->first();

        if (!$session) {
            return response()->json(['session_id' => null, 'messages' => [], 'question_count' => 0, 'submitted_at' => null]);
        }

        $messages      = AiChatMessage::where('session_id', $session->id)
            ->orderBy('id')
            ->get(['role', 'content', 'created_at']);
        $questionCount = $messages->where('role', 'user')->count();

        return response()->json([
            'session_id'     => $session->id,
            'messages'       => $messages,
            'question_count' => $questionCount,
            'submitted_at'   => $session->submitted_at,
        ]);
    }

    /**
     * Send a message to Gemini and return the AI response.
     */
    public function chat(Request $request, GeminiService $gemini)
    {
        $request->validate([
            'message'    => 'required|string|max:2000',
            'session_id' => 'required|integer',
        ]);

        $user    = auth()->user();
        $session = AiChatSession::where('id', $request->session_id)
            ->where('user_id', $user->id)
            ->firstOrFail();

        // Block if session was already submitted
        if ($session->submitted_at) {
            return response()->json([
                'success'                => false,
                'question_limit_reached' => true,
                'message'                => 'This session has already been submitted for review.',
            ], 429);
        }

        // Enforce question limit per session
        $questionLimit = self::questionLimit();
        $questionCount = AiChatMessage::where('session_id', $session->id)
            ->where('role', 'user')
            ->count();

        if ($questionCount >= $questionLimit) {
            return response()->json([
                'success'                => false,
                'question_limit_reached' => true,
                'message'                => "You have reached the {$questionLimit}-question limit for this session. Submit your questions to receive detailed answers.",
            ], 429);
        }

        $result = $gemini->chat($user, $session, $request->message);

        if (!$result['success']) {
            if ($result['cap_reached'] ?? false) {
                $messages = [
                    'daily_user_limit'    => 'You have reached your daily message limit. Please try again tomorrow.',
                    'daily_global_limit'  => 'The AI Advisor is temporarily unavailable due to high demand. Please try again later.',
                    'monthly_budget_limit'=> 'The AI Advisor is temporarily unavailable this month. Please try again next month.',
                ];
                return response()->json([
                    'success'     => false,
                    'cap_reached' => true,
                    'message'     => $messages[$result['reason']] ?? 'Usage limit reached.',
                ], 429);
            }

            if (($result['error'] ?? '') === 'rate_limited') {
                return response()->json([
                    'success' => false,
                    'message' => 'The AI is receiving too many requests right now. Please wait a moment and try again.',
                ], 503);
            }

            return response()->json([
                'success' => false,
                'message' => 'Something went wrong. Please try again.',
            ], 500);
        }

        return response()->json([
            'success'        => true,
            'message'        => $result['message'],
            'question_count' => $questionCount + 1,
        ]);
    }

    /**
     * Mark a session as submitted for advisor review.
     */
    public function submitSession(Request $request)
    {
        $request->validate(['session_id' => 'required|integer']);

        $user    = auth()->user();
        $session = AiChatSession::where('id', $request->session_id)
            ->where('user_id', $user->id)
            ->firstOrFail();

        if (!$session->submitted_at) {
            $session->update(['submitted_at' => now()]);
        }

        return response()->json(['success' => true]);
    }

    /**
     * Build the personalized opening greeting from the site setting.
     */
    private function buildGreeting($user): string
    {
        $default = "Hello, {name}! I'm your AI College Advisor here at Univa. As a {grade} student, I can help you navigate the college application process, explore schools, prepare for standardized tests, and more. What would you like to talk about today?";

        $template = SiteSetting::get('advisor_greeting', $default);

        return str_replace(['{name}', '{grade}'], [$user->first_name, $user->grade_display], $template);
    }
}
