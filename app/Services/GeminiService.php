<?php

namespace App\Services;

use App\Models\AiChatSession;
use App\Models\AiChatMessage;
use App\Models\AiUsageLog;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class GeminiService
{
    private string $apiKey;
    private string $model;
    private string $endpoint;

    public function __construct()
    {
        $this->apiKey   = config('gemini.api_key');
        $this->model    = config('gemini.model');
        $this->endpoint = config('gemini.endpoint');
    }

    /**
     * Check whether usage caps allow sending a request.
     *
     * @return array{allowed: bool, reason?: string}
     */
    public function checkCaps(User $user): array
    {
        $caps = config('gemini.caps');

        // Per-user daily token cap
        if ($caps['per_user_daily_tokens'] > 0) {
            $userDailyTokens = AiUsageLog::where('user_id', $user->id)
                ->whereDate('created_at', today())
                ->sum(DB::raw('input_tokens + output_tokens'));

            if ($userDailyTokens >= $caps['per_user_daily_tokens']) {
                return ['allowed' => false, 'reason' => 'daily_user_limit'];
            }
        }

        // Global daily token cap
        if ($caps['daily_tokens'] > 0) {
            $globalDailyTokens = AiUsageLog::whereDate('created_at', today())
                ->sum(DB::raw('input_tokens + output_tokens'));

            if ($globalDailyTokens >= $caps['daily_tokens']) {
                return ['allowed' => false, 'reason' => 'daily_global_limit'];
            }
        }

        // Monthly budget cap
        if ($caps['monthly_budget_usd'] > 0) {
            $monthlySpend = AiUsageLog::whereYear('created_at', now()->year)
                ->whereMonth('created_at', now()->month)
                ->sum('estimated_cost_usd');

            if ($monthlySpend >= $caps['monthly_budget_usd']) {
                return ['allowed' => false, 'reason' => 'monthly_budget_limit'];
            }
        }

        return ['allowed' => true];
    }

    /**
     * Send a user message to Gemini and return the AI response.
     *
     * @return array{success: bool, message?: string, cap_reached?: bool, reason?: string, error?: string}
     */
    public function chat(User $user, AiChatSession $session, string $userMessage): array
    {
        // Check caps before doing anything
        $capCheck = $this->checkCaps($user);
        if (!$capCheck['allowed']) {
            return ['success' => false, 'cap_reached' => true, 'reason' => $capCheck['reason']];
        }

        // Persist the user's message first
        AiChatMessage::create([
            'session_id' => $session->id,
            'role'       => 'user',
            'content'    => $userMessage,
        ]);

        // Build the contents array from recent history (oldest → newest)
        $limit    = config('gemini.context_messages', 20);
        $history  = AiChatMessage::where('session_id', $session->id)
            ->orderBy('id', 'desc')
            ->limit($limit)
            ->get()
            ->reverse()
            ->values();

        $contents = $history->map(fn ($msg) => [
            'role'  => $msg->role,
            'parts' => [['text' => $msg->content]],
        ])->toArray();

        // System instruction that includes the student's profile
        $systemInstruction = $this->buildSystemPrompt($user);

        $url = "{$this->endpoint}/{$this->model}:generateContent?key={$this->apiKey}";

        $payload = [
            'system_instruction' => [
                'parts' => [['text' => $systemInstruction]],
            ],
            'contents' => $contents,
        ];

        try {
            $response = Http::timeout(30)
                ->withOptions(['verify' => app()->isProduction()])
                ->post($url, $payload);

            if (!$response->successful()) {
                Log::error('Gemini API error', [
                    'status' => $response->status(),
                    'body'   => $response->body(),
                ]);
                // Roll back the user message so the conversation stays consistent
                AiChatMessage::where('session_id', $session->id)
                    ->where('role', 'user')
                    ->latest('id')
                    ->first()
                    ?->delete();

                if ($response->status() === 429) {
                    return ['success' => false, 'error' => 'rate_limited'];
                }

                return ['success' => false, 'error' => 'api_error'];
            }

            $data = $response->json();

            $aiText       = $data['candidates'][0]['content']['parts'][0]['text']
                            ?? 'I could not generate a response. Please try again.';
            $inputTokens  = $data['usageMetadata']['promptTokenCount']     ?? 0;
            $outputTokens = $data['usageMetadata']['candidatesTokenCount'] ?? 0;

            // Estimate cost
            $pricing = config('gemini.pricing');
            $cost    = ($inputTokens  * $pricing['input_per_million']  / 1_000_000)
                     + ($outputTokens * $pricing['output_per_million'] / 1_000_000);

            // Persist AI response
            AiChatMessage::create([
                'session_id' => $session->id,
                'role'       => 'model',
                'content'    => $aiText,
            ]);

            // Log token usage
            AiUsageLog::create([
                'user_id'           => $user->id,
                'session_id'        => $session->id,
                'input_tokens'      => $inputTokens,
                'output_tokens'     => $outputTokens,
                'estimated_cost_usd'=> $cost,
                'model'             => $this->model,
            ]);

            // Auto-title the session from the first message (max 80 chars)
            if (!$session->title) {
                $session->update(['title' => mb_substr($userMessage, 0, 80)]);
            }

            return [
                'success' => true,
                'message' => $aiText,
                'tokens'  => ['input' => $inputTokens, 'output' => $outputTokens],
            ];

        } catch (\Exception $e) {
            Log::error('Gemini API exception', ['message' => $e->getMessage()]);

            // Roll back the user message
            AiChatMessage::where('session_id', $session->id)
                ->where('role', 'user')
                ->latest('id')
                ->first()
                ?->delete();

            return ['success' => false, 'error' => 'exception'];
        }
    }

    /**
     * Build the system prompt injecting the student's profile data.
     */
    private function buildSystemPrompt(User $user): string
    {
        $locationParts = array_filter([$user->city, $user->state, $user->zip_code]);
        $location      = $locationParts ? implode(', ', $locationParts) : 'not provided';

        return <<<PROMPT
You are an AI College Advisor for Univa, a college guidance platform for students in the United States.

Student Profile:
- Name: {$user->full_name}
- First Name: {$user->first_name}
- Academic Level: {$user->grade_display}
- Location: {$location}
- Email: {$user->email}

Instructions:
- Always address the student by their first name.
- Tailor every response to their current academic level ({$user->grade_display}).
- Focus exclusively on US colleges, universities, and the US college application process.
- Be encouraging, clear, and concise. Use plain language.
- If the student asks about something outside of college guidance, politely redirect them back to college-related topics.
- All information is for general guidance only. Always remind the student to verify specific details directly with institutions.
PROMPT;
    }
}
