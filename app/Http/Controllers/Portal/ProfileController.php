<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{
    /**
     * Display the user's profile.
     */
    public function show()
    {
        $user = auth()->user();

        return view('portal.profile', compact('user'));
    }

    /**
     * Update the user's profile.
     */
    public function update(Request $request)
    {
        $user = auth()->user();

        $request->validate([
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'grade' => ['required', 'string', Rule::in(['grade_9_10', 'grade_11', 'grade_12', 'community_college', 'undergraduate', 'graduate', 'gap_year'])],
            'zip_code' => ['nullable', 'string', 'max:10'],
            'city' => ['nullable', 'string', 'max:255'],
            'state' => ['nullable', 'string', 'max:2'],
        ]);

        $user->update([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'grade' => $request->grade,
            'zip_code' => $request->zip_code,
            'city' => $request->city,
            'state' => $request->state,
        ]);

        return back()->with('success', 'Profile updated successfully.');
    }
}
