@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')
<h1 class="mb-4">Admin Dashboard</h1>

<div class="row">
    <!-- Stats Cards -->
    <div class="col-md-3 mb-4">
        <a href="{{ route('admin.users') }}" class="text-decoration-none">
            <div class="card bg-primary text-white h-100" style="cursor: pointer; transition: transform 0.15s;">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-white-50">Total Students</h6>
                            <h2 class="mb-0">{{ $stats['total_users'] }}</h2>
                        </div>
                        <i class="bi bi-people display-4 opacity-50"></i>
                    </div>
                </div>
            </div>
        </a>
    </div>

    <div class="col-md-3 mb-4">
        <a href="{{ route('admin.users', 'grade_9_10') }}" class="text-decoration-none">
            <div class="card bg-success text-white h-100" style="cursor: pointer; transition: transform 0.15s;">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-white-50">Grade 9 & 10</h6>
                            <h2 class="mb-0">{{ $stats['grade_9_10'] }}</h2>
                        </div>
                        <i class="bi bi-mortarboard display-4 opacity-50"></i>
                    </div>
                </div>
            </div>
        </a>
    </div>

    <div class="col-md-3 mb-4">
        <a href="{{ route('admin.users', 'grade_11') }}" class="text-decoration-none">
            <div class="card bg-info text-white h-100" style="cursor: pointer; transition: transform 0.15s;">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-white-50">Grade 11</h6>
                            <h2 class="mb-0">{{ $stats['grade_11'] }}</h2>
                        </div>
                        <i class="bi bi-mortarboard display-4 opacity-50"></i>
                    </div>
                </div>
            </div>
        </a>
    </div>

    <div class="col-md-3 mb-4">
        <a href="{{ route('admin.users', 'grade_12') }}" class="text-decoration-none">
            <div class="card bg-warning text-dark h-100" style="cursor: pointer; transition: transform 0.15s;">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-dark opacity-75">Grade 12</h6>
                            <h2 class="mb-0">{{ $stats['grade_12'] }}</h2>
                        </div>
                        <i class="bi bi-mortarboard display-4 opacity-50"></i>
                    </div>
                </div>
            </div>
        </a>
    </div>
</div>

<!-- Quick Actions -->
<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="bi bi-lightning me-2"></i>Quick Actions</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <a href="{{ route('admin.branding') }}" class="btn btn-outline-primary w-100 py-3">
                            <i class="bi bi-palette d-block mb-2 fs-3"></i>
                            Update Branding
                        </a>
                    </div>
                    <div class="col-md-4 mb-3">
                        <a href="{{ route('admin.content.homepage') }}" class="btn btn-outline-success w-100 py-3">
                            <i class="bi bi-house d-block mb-2 fs-3"></i>
                            Edit Homepage
                        </a>
                    </div>
                    <div class="col-md-4 mb-3">
                        <a href="{{ route('admin.guidelines') }}" class="btn btn-outline-info w-100 py-3">
                            <i class="bi bi-book d-block mb-2 fs-3"></i>
                            Edit Guidelines
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0"><i class="bi bi-envelope me-2"></i>Contact Messages</h5>
                @if($stats['unread_contacts'] > 0)
                    <span class="badge bg-danger">{{ $stats['unread_contacts'] }} new</span>
                @endif
            </div>
            <div class="card-body">
                @if($stats['unread_contacts'] > 0)
                    <p class="text-muted">You have {{ $stats['unread_contacts'] }} unread message(s).</p>
                    <a href="{{ route('admin.contacts') }}" class="btn btn-primary">
                        View Messages
                    </a>
                @else
                    <p class="text-muted mb-0">No new messages.</p>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
