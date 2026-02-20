<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SiteSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BrandingController extends Controller
{
    /**
     * Display branding settings.
     */
    public function index()
    {
        $logo = SiteSetting::get('site_logo', null);
        $favicon = SiteSetting::get('site_favicon', null);

        return view('admin.branding', compact('logo', 'favicon'));
    }

    /**
     * Update branding settings.
     */
    public function update(Request $request)
    {
        $request->validate([
            'logo' => ['nullable', 'image', 'mimes:png,jpg,jpeg,svg', 'max:2048'],
            'favicon' => ['nullable', 'image', 'mimes:ico,png', 'max:512'],
        ]);

        if ($request->hasFile('logo')) {
            // Delete old logo if exists
            $oldLogo = SiteSetting::get('site_logo');
            if ($oldLogo) {
                Storage::disk('public')->delete($oldLogo);
            }

            $logoPath = $request->file('logo')->store('branding', 'public');
            SiteSetting::set('site_logo', $logoPath, 'image', 'branding');
        }

        if ($request->hasFile('favicon')) {
            // Delete old favicon if exists
            $oldFavicon = SiteSetting::get('site_favicon');
            if ($oldFavicon) {
                Storage::disk('public')->delete($oldFavicon);
            }

            $faviconPath = $request->file('favicon')->store('branding', 'public');
            SiteSetting::set('site_favicon', $faviconPath, 'image', 'branding');
        }

        return back()->with('success', 'Branding updated successfully.');
    }
}
