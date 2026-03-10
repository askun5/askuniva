<?php

namespace App\Http\Controllers;

use App\Mail\ContactSubmissionReceived;
use App\Models\ContactSubmission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Rules\Recaptcha;

class ContactController extends Controller
{
    /**
     * Show the contact form.
     */
    public function show()
    {
        return view('public.contact');
    }

    /**
     * Handle contact form submission.
     */
    public function submit(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email', 'max:255'],
            'comments' => ['required', 'string', 'max:5000'],
            'recaptcha_token' => ['required', new Recaptcha()],
        ]);

        $submission = ContactSubmission::create([
            'email' => $request->email,
            'comments' => $request->comments,
        ]);

        Mail::to('support@askuniva.com')->send(new ContactSubmissionReceived($submission));

        return back()->with('success', 'Thank you for your message! We will get back to you soon.');
    }
}
