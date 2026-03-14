<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AiUsageLog;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class AiUsageController extends Controller
{
    public function index()
    {
        // Summary stats
        $todayTokens = AiUsageLog::whereDate('created_at', today())
            ->sum(DB::raw('input_tokens + output_tokens'));

        $monthTokens = AiUsageLog::whereYear('created_at', now()->year)
            ->whereMonth('created_at', now()->month)
            ->sum(DB::raw('input_tokens + output_tokens'));

        $monthCost = AiUsageLog::whereYear('created_at', now()->year)
            ->whereMonth('created_at', now()->month)
            ->sum('estimated_cost_usd');

        $totalCost = AiUsageLog::sum('estimated_cost_usd');

        // Last 14 days daily breakdown
        $dailyStats = AiUsageLog::select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('SUM(input_tokens + output_tokens) as total_tokens'),
                DB::raw('SUM(estimated_cost_usd) as total_cost'),
                DB::raw('COUNT(DISTINCT user_id) as active_users')
            )
            ->where('created_at', '>=', now()->subDays(13)->startOfDay())
            ->groupBy('date')
            ->orderBy('date', 'desc')
            ->get();

        // Per-user breakdown this month
        $userStats = AiUsageLog::select(
                'user_id',
                DB::raw('SUM(input_tokens) as input_tokens'),
                DB::raw('SUM(output_tokens) as output_tokens'),
                DB::raw('SUM(input_tokens + output_tokens) as total_tokens'),
                DB::raw('SUM(estimated_cost_usd) as total_cost'),
                DB::raw('COUNT(*) as requests')
            )
            ->whereYear('created_at', now()->year)
            ->whereMonth('created_at', now()->month)
            ->groupBy('user_id')
            ->orderBy('total_tokens', 'desc')
            ->with('user:id,first_name,last_name,email')
            ->limit(20)
            ->get();

        // Current caps from config
        $caps = config('gemini.caps');

        return view('admin.ai-usage', compact(
            'todayTokens', 'monthTokens', 'monthCost', 'totalCost',
            'dailyStats', 'userStats', 'caps'
        ));
    }
}
