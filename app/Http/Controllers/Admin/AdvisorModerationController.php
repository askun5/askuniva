<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AiChatWarning;
use App\Models\User;

class AdvisorModerationController extends Controller
{
    /**
     * List all users with warnings or suspensions.
     */
    public function index()
    {
        $flaggedUsers = User::where('advisor_warnings', '>', 0)
            ->orWhereNotNull('advisor_suspended_at')
            ->orderByDesc('advisor_suspended_at')
            ->orderByDesc('advisor_warnings')
            ->get();

        return view('admin.advisor-moderation', compact('flaggedUsers'));
    }

    /**
     * Reset a user's warning count.
     */
    public function resetWarnings(User $user)
    {
        $user->update(['advisor_warnings' => 0]);

        return back()->with('success', "Warnings reset for {$user->full_name}.");
    }

    /**
     * Unsuspend a user's AI Advisor access.
     */
    public function unsuspend(User $user)
    {
        $user->update(['advisor_suspended_at' => null]);

        return back()->with('success', "{$user->full_name}'s AI Advisor access has been restored.");
    }
}
