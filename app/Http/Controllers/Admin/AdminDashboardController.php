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
            'grade_9_10' => User::where('grade', 'grade_9_10')->count(),
            'grade_11' => User::where('grade', 'grade_11')->count(),
            'grade_12' => User::where('grade', 'grade_12')->count(),
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

        if ($filter) {
            switch ($filter) {
                case 'grade_9_10':
                    $query->where('grade', 'grade_9_10');
                    $title = 'Grade 9 & 10 Students';
                    break;
                case 'grade_11':
                    $query->where('grade', 'grade_11');
                    $title = 'Grade 11 Students';
                    break;
                case 'grade_12':
                    $query->where('grade', 'grade_12');
                    $title = 'Grade 12 Students';
                    break;
            }
        }

        $users = $query->orderBy('last_name')->orderBy('first_name')->paginate(20);

        return view('admin.users', compact('users', 'title', 'filter'));
    }
}
