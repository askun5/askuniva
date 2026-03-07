<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\ContactSubmission;
use Illuminate\Http\Request;

class AdminDashboardController extends Controller
{
    /**
     * Display the admin dashboard.
     */
    public function index()
    {
        $stats = [
            'total_users' => User::where('role', 'student')->count(),
            'grade_9_10' => User::where('role', 'student')->where('grade', 'grade_9_10')->count(),
            'grade_11' => User::where('role', 'student')->where('grade', 'grade_11')->count(),
            'grade_12' => User::where('role', 'student')->where('grade', 'grade_12')->count(),
            'unread_contacts' => ContactSubmission::unread()->count(),
        ];

        return view('admin.dashboard', compact('stats'));
    }

    /**
     * Display a single user's detail page.
     */
    public function showUser(User $user)
    {
        $user->load(['loginHistories', 'contactSubmissions']);

        return view('admin.user-detail', compact('user'));
    }

    /**
     * Display a list of users filtered by grade.
     */
    public function users(Request $request, ?string $filter = null)
    {
        $query = User::where('role', 'student');

        $title = 'All Students';

        $validFilters = [
            'grade_9_10'        => 'HS Grades 9 & 10 Students',
            'grade_11'          => 'HS Grade 11 Students',
            'grade_12'          => 'HS Grade 12 Students',
            'community_college' => 'Community College Students',
            'undergraduate'     => 'Undergraduate Students',
            'graduate'          => 'Graduate Students',
        ];

        if ($filter && isset($validFilters[$filter])) {
            $query->where('grade', $filter);
            $title = $validFilters[$filter];
        }

        $users = $query->orderBy('last_name')->orderBy('first_name')->paginate(20);

        return view('admin.users', compact('users', 'title', 'filter'));
    }
}
