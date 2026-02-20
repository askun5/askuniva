<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
use App\Models\SiteSetting;
use Illuminate\Http\Request;

class AdvisorController extends Controller
{
    /**
     * Display the AI Advisor page with Chatfuel integration.
     */
    public function index()
    {
        $user = auth()->user();

        // Get Chatfuel configuration from settings
        $chatfuelBotId = SiteSetting::get('chatfuel_bot_id', '');
        $chatfuelToken = SiteSetting::get('chatfuel_token', '');

        return view('portal.advisor', compact('user', 'chatfuelBotId', 'chatfuelToken'));
    }
}
