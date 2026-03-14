@extends('layouts.admin')

@section('title', 'AI Usage')

@section('content')
<div class="d-flex align-items-center justify-content-between mb-4">
    <h2 class="mb-0"><i class="bi bi-bar-chart-line me-2 text-success"></i>AI Advisor Usage</h2>
    <span class="text-muted small">{{ now()->format('F Y') }}</span>
</div>

{{-- ── Summary Cards ──────────────────────────────────────────────────── --}}
<div class="row g-3 mb-4">
    <div class="col-sm-6 col-xl-3">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <div class="text-muted small mb-1">Tokens Today</div>
                <div class="fs-3 fw-bold text-success">{{ number_format($todayTokens) }}</div>
                @if($caps['daily_tokens'] > 0)
                    <div class="text-muted small">Cap: {{ number_format($caps['daily_tokens']) }}</div>
                    <div class="progress mt-1" style="height:4px;">
                        <div class="progress-bar bg-success"
                             style="width: {{ min(100, round($todayTokens / $caps['daily_tokens'] * 100)) }}%"></div>
                    </div>
                @endif
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <div class="text-muted small mb-1">Tokens This Month</div>
                <div class="fs-3 fw-bold text-primary">{{ number_format($monthTokens) }}</div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <div class="text-muted small mb-1">Est. Cost This Month</div>
                <div class="fs-3 fw-bold text-warning">${{ number_format($monthCost, 4) }}</div>
                @if($caps['monthly_budget_usd'] > 0)
                    <div class="text-muted small">Budget: ${{ number_format($caps['monthly_budget_usd'], 2) }}</div>
                    <div class="progress mt-1" style="height:4px;">
                        <div class="progress-bar bg-warning"
                             style="width: {{ min(100, round($monthCost / $caps['monthly_budget_usd'] * 100)) }}%"></div>
                    </div>
                @endif
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <div class="text-muted small mb-1">Total Est. Cost (All Time)</div>
                <div class="fs-3 fw-bold text-danger">${{ number_format($totalCost, 4) }}</div>
            </div>
        </div>
    </div>
</div>

{{-- ── Active Caps ─────────────────────────────────────────────────────── --}}
<div class="card shadow-sm mb-4">
    <div class="card-header bg-light">
        <h5 class="mb-0"><i class="bi bi-sliders me-2"></i>Active Caps <span class="text-muted fw-normal small">(set in .env)</span></h5>
    </div>
    <div class="card-body">
        <div class="row g-3">
            <div class="col-md-4">
                <div class="border rounded p-3 text-center">
                    <div class="text-muted small mb-1">Daily Token Cap (all users)</div>
                    <div class="fw-bold">
                        {{ $caps['daily_tokens'] > 0 ? number_format($caps['daily_tokens']) . ' tokens' : 'Unlimited' }}
                    </div>
                    <div class="text-muted small">GEMINI_DAILY_TOKEN_CAP</div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="border rounded p-3 text-center">
                    <div class="text-muted small mb-1">Per-User Daily Cap</div>
                    <div class="fw-bold">
                        {{ $caps['per_user_daily_tokens'] > 0 ? number_format($caps['per_user_daily_tokens']) . ' tokens' : 'Unlimited' }}
                    </div>
                    <div class="text-muted small">GEMINI_USER_DAILY_TOKEN_CAP</div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="border rounded p-3 text-center">
                    <div class="text-muted small mb-1">Monthly Budget Cap</div>
                    <div class="fw-bold">
                        {{ $caps['monthly_budget_usd'] > 0 ? '$' . number_format($caps['monthly_budget_usd'], 2) : 'Unlimited' }}
                    </div>
                    <div class="text-muted small">GEMINI_MONTHLY_BUDGET_USD</div>
                </div>
            </div>
        </div>
        <p class="text-muted small mb-0 mt-3">
            <i class="bi bi-info-circle me-1"></i>
            To adjust caps, edit <code>.env</code> on the server and run <code>php artisan config:cache</code>.
        </p>
    </div>
</div>

{{-- ── Last 14 Days ─────────────────────────────────────────────────────── --}}
<div class="card shadow-sm mb-4">
    <div class="card-header bg-light">
        <h5 class="mb-0"><i class="bi bi-calendar3 me-2"></i>Daily Breakdown — Last 14 Days</h5>
    </div>
    <div class="card-body p-0">
        @if($dailyStats->isEmpty())
            <div class="text-muted text-center p-4">No usage in the last 14 days.</div>
        @else
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Date</th>
                        <th class="text-end">Total Tokens</th>
                        <th class="text-end">Active Users</th>
                        <th class="text-end">Est. Cost</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($dailyStats as $day)
                    <tr>
                        <td>{{ \Carbon\Carbon::parse($day->date)->format('D, M j Y') }}</td>
                        <td class="text-end">{{ number_format($day->total_tokens) }}</td>
                        <td class="text-end">{{ $day->active_users }}</td>
                        <td class="text-end">${{ number_format($day->total_cost, 5) }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif
    </div>
</div>

{{-- ── Top Users This Month ─────────────────────────────────────────────── --}}
<div class="card shadow-sm mb-4">
    <div class="card-header bg-light">
        <h5 class="mb-0"><i class="bi bi-people me-2"></i>Top Users This Month</h5>
    </div>
    <div class="card-body p-0">
        @if($userStats->isEmpty())
            <div class="text-muted text-center p-4">No usage this month.</div>
        @else
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Student</th>
                        <th class="text-end">Input Tokens</th>
                        <th class="text-end">Output Tokens</th>
                        <th class="text-end">Total Tokens</th>
                        <th class="text-end">Requests</th>
                        <th class="text-end">Est. Cost</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($userStats as $stat)
                    <tr>
                        <td>
                            @if($stat->user)
                                <a href="{{ route('admin.user.show', $stat->user) }}">
                                    {{ $stat->user->full_name }}
                                </a>
                                <div class="text-muted small">{{ $stat->user->email }}</div>
                            @else
                                <span class="text-muted">Deleted user</span>
                            @endif
                        </td>
                        <td class="text-end">{{ number_format($stat->input_tokens) }}</td>
                        <td class="text-end">{{ number_format($stat->output_tokens) }}</td>
                        <td class="text-end fw-semibold">{{ number_format($stat->total_tokens) }}</td>
                        <td class="text-end">{{ number_format($stat->requests) }}</td>
                        <td class="text-end">${{ number_format($stat->total_cost, 5) }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif
    </div>
</div>
@endsection
