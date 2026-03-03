<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;

class VerificationController extends Controller
{
    /**
     * Show the "check your email" notice page.
     */
    public function notice()
    {
        if (auth()->user()->hasVerifiedEmail()) {
            return redirect()->route('portal.dashboard');
        }

        return view('auth.verify-email');
    }

    /**
     * Handle the signed verification link click.
     */
    public function verify(EmailVerificationRequest $request)
    {
        if ($request->user()->hasVerifiedEmail()) {
            return redirect()->route('portal.dashboard')
                ->with('success', 'Your email is already verified.');
        }

        $request->fulfill();

        return redirect()->route('portal.dashboard')
            ->with('success', 'Your email has been verified. Welcome to Univa!');
    }

    /**
     * Resend the verification email.
     */
    public function resend(Request $request)
    {
        if ($request->user()->hasVerifiedEmail()) {
            return redirect()->route('portal.dashboard');
        }

        $request->user()->sendEmailVerificationNotification();

        return back()->with('status', 'A new verification link has been sent to your email address.');
    }
}
