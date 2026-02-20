<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContactSubmission;
use Illuminate\Http\Request;

class ContactSubmissionsController extends Controller
{
    /**
     * Display all contact submissions.
     */
    public function index()
    {
        $submissions = ContactSubmission::orderBy('created_at', 'desc')
            ->paginate(20);

        return view('admin.contacts.index', compact('submissions'));
    }

    /**
     * View a single submission.
     */
    public function show(ContactSubmission $submission)
    {
        // Mark as read when viewed
        $submission->markAsRead();

        return view('admin.contacts.show', compact('submission'));
    }

    /**
     * Delete a submission.
     */
    public function destroy(ContactSubmission $submission)
    {
        $submission->delete();

        return redirect()->route('admin.contacts')
            ->with('success', 'Submission deleted successfully.');
    }
}
