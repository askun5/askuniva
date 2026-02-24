<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class Recaptcha implements ValidationRule
{
    /**
     * The minimum score threshold for reCAPTCHA v3.
     */
    protected float $minScore;

    /**
     * Create a new rule instance.
     */
    public function __construct(float $minScore = 0.5)
    {
        $this->minScore = $minScore;
    }

    /**
     * Run the validation rule.
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (empty($value)) {
            $fail('The reCAPTCHA verification failed. Please try again.');
            return;
        }

        $http = Http::asForm();

        // Disable SSL verification for local development
        if (app()->environment('local')) {
            $http = $http->withoutVerifying();
        }

        try {
            $response = $http->post('https://www.google.com/recaptcha/api/siteverify', [
                'secret' => config('services.recaptcha.secret_key'),
                'response' => $value,
                'remoteip' => request()->ip(),
            ]);

            $result = $response->json();

            Log::info('reCAPTCHA response', ['result' => $result]);

            if (!$result['success'] || ($result['score'] ?? 0) < $this->minScore) {
                $fail('The reCAPTCHA verification failed. Please try again.');
            }
        } catch (\Exception $e) {
            Log::error('reCAPTCHA request failed', ['error' => $e->getMessage()]);
            $fail('The reCAPTCHA verification failed. Please try again.');
        }
    }
}
