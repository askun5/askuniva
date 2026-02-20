<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\Http;

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

        $response = $http->post('https://www.google.com/recaptcha/api/siteverify', [
            'secret' => config('services.recaptcha.secret_key'),
            'response' => $value,
            'remoteip' => request()->ip(),
        ]);

        $result = $response->json();

        if (!$result['success'] || ($result['score'] ?? 0) < $this->minScore) {
            $fail('The reCAPTCHA verification failed. Please try again.');
        }
    }
}
