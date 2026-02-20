<?php

namespace App\Http\Controllers;

use App\Models\Page;
use Illuminate\Http\Request;

class PageController extends Controller
{
    /**
     * Display the About page.
     */
    public function about()
    {
        $page = Page::getBySlug('about');

        if (!$page) {
            abort(404);
        }

        return view('public.page', compact('page'));
    }

    /**
     * Display the Privacy Policy page.
     */
    public function privacy()
    {
        $page = Page::getBySlug('privacy');

        if (!$page) {
            abort(404);
        }

        return view('public.page', compact('page'));
    }

    /**
     * Display the Terms of Service page.
     */
    public function terms()
    {
        $page = Page::getBySlug('terms');

        if (!$page) {
            abort(404);
        }

        return view('public.page', compact('page'));
    }
}
