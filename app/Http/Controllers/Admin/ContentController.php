<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SiteSetting;
use App\Models\Page;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ContentController extends Controller
{
    /**
     * Display homepage content editor.
     */
    public function homepage()
    {
        $heroTitle = SiteSetting::get('hero_title', 'Welcome to Univa');
        $heroSubtext = SiteSetting::get('hero_subtext', 'Your AI-powered virtual college counselor.');
        $heroImage = SiteSetting::get('hero_image', null);

        return view('admin.content.homepage', compact('heroTitle', 'heroSubtext', 'heroImage'));
    }

    /**
     * Update homepage content.
     */
    public function updateHomepage(Request $request)
    {
        $request->validate([
            'hero_title' => ['required', 'string', 'max:255'],
            'hero_subtext' => ['required', 'string', 'max:1000'],
            'hero_image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
        ]);

        SiteSetting::set('hero_title', $request->hero_title, 'text', 'hero');
        SiteSetting::set('hero_subtext', $request->hero_subtext, 'textarea', 'hero');

        if ($request->hasFile('hero_image')) {
            // Delete old image if exists
            $oldImage = SiteSetting::get('hero_image');
            if ($oldImage) {
                Storage::disk('public')->delete($oldImage);
            }

            $imagePath = $request->file('hero_image')->store('hero', 'public');
            SiteSetting::set('hero_image', $imagePath, 'image', 'hero');
        }

        return back()->with('success', 'Homepage content updated successfully.');
    }

    /**
     * Display pages list.
     */
    public function pages()
    {
        $pages = Page::all();

        return view('admin.content.pages', compact('pages'));
    }

    /**
     * Edit a specific page.
     */
    public function editPage(Page $page)
    {
        return view('admin.content.page-edit', compact('page'));
    }

    /**
     * Update a page.
     */
    public function updatePage(Request $request, Page $page)
    {
        $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'content' => ['required', 'string'],
        ]);

        $page->update([
            'title' => $request->title,
            'content' => $request->content,
        ]);

        return redirect()->route('admin.content.pages')
            ->with('success', 'Page updated successfully.');
    }

    /**
     * Display AI Advisor content editor.
     */
    public function advisor()
    {
        $defaultTips = json_encode([
            'Be specific with your questions for better answers',
            'Ask about college requirements, test prep, extracurriculars, and application tips',
            'The advisor knows you\'re a {grade} student and will tailor advice accordingly',
            'You can ask follow-up questions to get more detailed information',
            'Your chat history is saved — use Load Last Chat to continue where you left off',
        ]);

        $defaultDisclaimer = 'This advisor is intended for use with universities located within the United States only. All information provided is for general guidance purposes and may not reflect the most current institutional policies, requirements, or deadlines. Please verify all details directly with the respective institution before making any decisions.';

        $defaultGreeting = "Hello, {name}! I'm your AI College Advisor here at Univa. As a {grade} student, I can help you navigate the college application process, explore schools, prepare for standardized tests, and more. What would you like to talk about today?";

        $tips             = json_decode(SiteSetting::get('advisor_tips', $defaultTips), true);
        $disclaimer       = SiteSetting::get('advisor_disclaimer', $defaultDisclaimer);
        $greeting         = SiteSetting::get('advisor_greeting', $defaultGreeting);
        $sessionLimit     = (int) SiteSetting::get('advisor_session_limit', 1);
        $questionLimit    = (int) SiteSetting::get('advisor_question_limit', 15);
        $warningThreshold = (int) SiteSetting::get('advisor_warning_threshold', 3);

        return view('admin.content.advisor', compact('tips', 'disclaimer', 'greeting', 'sessionLimit', 'questionLimit', 'warningThreshold'));
    }

    /**
     * Update AI Advisor content.
     */
    public function updateAdvisor(Request $request)
    {
        $request->validate([
            'tips'              => ['required', 'array', 'min:1'],
            'tips.*'            => ['required', 'string', 'max:500'],
            'disclaimer'        => ['required', 'string', 'max:2000'],
            'greeting'          => ['required', 'string', 'max:1000'],
            'session_limit'     => ['required', 'integer', 'min:1', 'max:10'],
            'question_limit'    => ['required', 'integer', 'min:1', 'max:100'],
            'warning_threshold' => ['required', 'integer', 'min:1', 'max:10'],
        ]);

        SiteSetting::set('advisor_tips',              json_encode(array_values($request->tips)), 'json',    'advisor');
        SiteSetting::set('advisor_disclaimer',        $request->disclaimer,                      'textarea', 'advisor');
        SiteSetting::set('advisor_greeting',          $request->greeting,                        'textarea', 'advisor');
        SiteSetting::set('advisor_session_limit',     $request->session_limit,                   'number',   'advisor');
        SiteSetting::set('advisor_question_limit',    $request->question_limit,                  'number',   'advisor');
        SiteSetting::set('advisor_warning_threshold', $request->warning_threshold,               'number',   'advisor');

        return back()->with('success', 'AI Advisor settings updated successfully.');
    }

    /**
     * Display footer settings.
     */
    public function footer()
    {
        $footerLinks = SiteSetting::get('footer_links', json_encode([
            ['label' => 'Home', 'url' => '/'],
            ['label' => 'About', 'url' => '/about'],
            ['label' => 'Privacy', 'url' => '/privacy'],
            ['label' => 'Contact', 'url' => '/contact'],
        ]));

        $footerLinks = json_decode($footerLinks, true);
        $copyrightText = SiteSetting::get('copyright_text', 'Univa. All rights reserved.');

        return view('admin.content.footer', compact('footerLinks', 'copyrightText'));
    }

    /**
     * Update footer settings.
     */
    public function updateFooter(Request $request)
    {
        $request->validate([
            'links' => ['required', 'array'],
            'links.*.label' => ['required', 'string', 'max:50'],
            'links.*.url' => ['required', 'string', 'max:255'],
            'copyright_text' => ['required', 'string', 'max:255'],
        ]);

        SiteSetting::set('footer_links', json_encode($request->links), 'json', 'footer');
        SiteSetting::set('copyright_text', $request->copyright_text, 'text', 'footer');

        return back()->with('success', 'Footer updated successfully.');
    }
}
