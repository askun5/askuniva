<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Delete the terms page
        DB::table('pages')->where('slug', 'terms')->delete();

        // Remove Terms from footer_links site setting
        $setting = DB::table('site_settings')->where('key', 'footer_links')->first();
        if ($setting) {
            $links = json_decode($setting->value, true);
            if (is_array($links)) {
                $links = array_values(array_filter($links, fn($link) => ($link['url'] ?? '') !== '/terms'));
                DB::table('site_settings')
                    ->where('key', 'footer_links')
                    ->update(['value' => json_encode($links)]);
                Cache::forget('setting.footer_links');
            }
        }
    }

    public function down(): void
    {
        // Restore the terms page
        DB::table('pages')->insert([
            'slug'       => 'terms',
            'title'      => 'Terms of Service',
            'content'    => '<p>Terms of Service content.</p>',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Re-add Terms to footer_links
        $setting = DB::table('site_settings')->where('key', 'footer_links')->first();
        if ($setting) {
            $links = json_decode($setting->value, true) ?? [];
            $links[] = ['label' => 'Terms', 'url' => '/terms'];
            DB::table('site_settings')
                ->where('key', 'footer_links')
                ->update(['value' => json_encode($links)]);
        }
    }
};
