<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
use App\Models\AiChatSession;
use App\Models\AiChatMessage;
use App\Services\GeminiService;
use Illuminate\Http\Request;

class AdvisorController extends Controller
{
    /**
     * Display the AI Advisor page.
     */
    public function index()
    {
        $user        = auth()->user();
        $lastSession = AiChatSession::where('user_id', $user->id)->latest()->first();

        return view('portal.advisor', compact('user', 'lastSession'));
    }

    /**
     * Create a new chat session and return its ID.
     */
    public function newSession()
    {
        $session = AiChatSession::create(['user_id' => auth()->id()]);

        return response()->json(['session_id' => $session->id]);
    }

    /**
     * Load the most recent chat session with its messages.
     */
    public function loadLastSession()
    {
        $user    = auth()->user();
        $session = AiChatSession::where('user_id', $user->id)->latest()->first();

        if (!$session) {
            return response()->json(['session_id' => null, 'messages' => []]);
        }

        $messages = AiChatMessage::where('session_id', $session->id)
            ->orderBy('id')
            ->get(['role', 'content', 'created_at']);

        return response()->json([
            'session_id' => $session->id,
            'messages'   => $messages,
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

        $result = $gemini->chat($user, $session, $request->message);

        if (!$result['success']) {
            if ($result['cap_reached'] ?? false) {
                $messages = [
                    'daily_user_limit'    => 'You have reached your daily message limit. Please try again tomorrow.',
                    'daily_global_limit'  => 'The AI Advisor is temporarily unavailable due to high demand. Please try again later.',
                    'monthly_budget_limit'=> 'The AI Advisor is temporarily unavailable this month. Please try again next month.',
                ];
                return response()->json([
                    'success'    => false,
                    'cap_reached'=> true,
                    'message'    => $messages[$result['reason']] ?? 'Usage limit reached.',
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
            'success' => true,
            'message' => $result['message'],
        ]);
    }
}
