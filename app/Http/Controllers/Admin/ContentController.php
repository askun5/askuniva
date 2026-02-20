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
     * Display footer settings.
     */
    public function footer()
    {
        $footerLinks = SiteSetting::get('footer_links', json_encode([
            ['label' => 'Home', 'url' => '/'],
            ['label' => 'About', 'url' => '/about'],
            ['label' => 'Privacy', 'url' => '/privacy'],
            ['label' => 'Terms', 'url' => '/terms'],
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
