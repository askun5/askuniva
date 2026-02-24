<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\LoginHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rules;
use App\Rules\Recaptcha;

class AuthController extends Controller
{
    /**
     * Show the sign up form.
     */
    public function showSignUp()
    {
        return view('auth.signup');
    }

    /**
     * Handle sign up submission.
     */
    public function signUp(Request $request)
    {
        Log::error('Signup attempt - recaptcha_token: ' . ($request->input('recaptcha_token') ?: 'EMPTY'));

        $request->validate([
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'grade' => ['required', 'in:grade_9_10,grade_11,grade_12'],
            'recaptcha_token' => ['required', new Recaptcha()],
        ]);

        $user = User::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'grade' => $request->grade,
            'newsletter' => $request->boolean('newsletter'),
            'last_login_at' => now(),
        ]);

        Auth::login($user);

        LoginHistory::create([
            'user_id' => $user->id,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'logged_in_at' => now(),
        ]);

        return redirect()->route('portal.dashboard')
            ->with('success', 'Welcome to Univa! Your account has been created.');
    }

    /**
     * Show the sign in form.
     */
    public function showSignIn()
    {
        return view('auth.signin');
    }

    /**
     * Handle sign in submission.
     */
    public function signIn(Request $request)
    {
        $request->validate([
            'recaptcha_token' => ['required', new Recaptcha()],
        ]);

        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();

            // Update last login time
            auth()->user()->update(['last_login_at' => now()]);

            LoginHistory::create([
                'user_id' => auth()->id(),
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'logged_in_at' => now(),
            ]);

            // Redirect admin users to admin dashboard
            if (auth()->user()->isAdmin()) {
                return redirect()->intended(route('admin.dashboard'));
            }

            return redirect()->intended(route('portal.dashboard'));
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    /**
     * Handle sign out.
     */
    public function signOut(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home')
            ->with('success', 'You have been signed out.');
    }

    /**
     * Show the forgot password form.
     */
    public function showForgotPassword()
    {
        return view('auth.forgot-password');
    }

    /**
     * Send password reset link.
     */
    public function sendResetLink(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email'],
        ]);

        $status = Password::sendResetLink(
            $request->only('email')
        );

        return $status === Password::RESET_LINK_SENT
            ? back()->with('status', __($status))
            : back()->withErrors(['email' => __($status)]);
    }

    /**
     * Show the password reset form.
     */
    public function showResetPassword(Request $request, string $token)
    {
        return view('auth.reset-password', [
            'token' => $token,
            'email' => $request->email,
        ]);
    }

    /**
     * Handle password reset.
     */
    public function resetPassword(Request $request)
    {
        $request->validate([
            'token' => ['required'],
            'email' => ['required', 'email'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function (User $user, string $password) {
                $user->forceFill([
                    'password' => Hash::make($password),
                ])->setRememberToken(Str::random(60));

                $user->save();

                event(new PasswordReset($user));
            }
        );

        return $status === Password::PASSWORD_RESET
            ? redirect()->route('signin')->with('status', __($status))
            : back()->withErrors(['email' => [__($status)]]);
    }
}
