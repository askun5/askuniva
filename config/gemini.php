<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Gemini API Configuration
    |--------------------------------------------------------------------------
    | Get your API key from https://aistudio.google.com/
    | Set GEMINI_API_KEY in your .env file.
    |
    | To switch from free tier to paid tier: just add billing in Google AI Studio.
    | No code changes needed — the same API key and endpoint works for both.
    */

    'api_key' => env('GEMINI_API_KEY', ''),

    'model' => env('GEMINI_MODEL', 'gemini-2.0-flash'),

    'endpoint' => 'https://generativelanguage.googleapis.com/v1beta/models',

    /*
    |--------------------------------------------------------------------------
    | Pricing (per 1 million tokens)
    |--------------------------------------------------------------------------
    | Update these when switching to paid tier if pricing changes.
    | Current rates for gemini-2.0-flash (as of March 2026):
    |   Free tier:  $0 (rate-limited)
    |   Paid tier:  Input $0.075 / Output $0.30 per 1M tokens
    */

    'pricing' => [
        'input_per_million'  => env('GEMINI_INPUT_PRICE', 0.075),
        'output_per_million' => env('GEMINI_OUTPUT_PRICE', 0.30),
    ],

    /*
    |--------------------------------------------------------------------------
    | Usage Caps (set to 0 for unlimited)
    |--------------------------------------------------------------------------
    | daily_tokens          - Max total tokens (input+output) per day across all users
    | monthly_budget_usd    - Max estimated spend per calendar month in USD
    | per_user_daily_tokens - Max tokens a single user can consume per day
    */

    'caps' => [
        'daily_tokens'          => env('GEMINI_DAILY_TOKEN_CAP', 500000),
        'monthly_budget_usd'    => env('GEMINI_MONTHLY_BUDGET_USD', 0),
        'per_user_daily_tokens' => env('GEMINI_USER_DAILY_TOKEN_CAP', 50000),
    ],

    /*
    |--------------------------------------------------------------------------
    | Context Window
    |--------------------------------------------------------------------------
    | Number of recent messages to include as conversation history.
    | Higher = better context but more tokens per request.
    */

    'context_messages' => env('GEMINI_CONTEXT_MESSAGES', 20),

];
