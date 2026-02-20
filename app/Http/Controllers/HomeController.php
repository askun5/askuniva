<?php

namespace App\Http\Controllers;

use App\Models\SiteSetting;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Display the front page.
     * Redirects authenticated users to the portal.
     */
    public function index()
    {
        // Redirect authenticated users to portal
        if (auth()->check()) {
            return redirect()->route('portal.dashboard');
        }

        // Get hero content from settings
        $heroTitle = SiteSetting::get('hero_title', 'Welcome to Univa');
        $heroSubtext = SiteSetting::get('hero_subtext', 'Your AI-powered virtual college counselor helping you prepare for university.');
        $heroImage = SiteSetting::get('hero_image', null);

        return view('public.home', compact('heroTitle', 'heroSubtext', 'heroImage'));
    }
}
