@extends('layouts.admin')

@section('title', $user->full_name)

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="mb-0">{{ $user->full_name }}</h1>
    <a href="{{ url()->previous() }}" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left me-1"></i>Back to Users
    </a>
</div>

<div class="row">
    <!-- User Info -->
    <div class="col-md-4 mb-4">
        <div class="card h-100">
            <div class="card-header">
                <h5 class="mb-0"><i class="bi bi-person me-2"></i>User Information</h5>
            </div>
            <div class="card-body">
                <table class="table table-borderless mb-0">
                    <tr>
                        <th class="text-muted ps-0" style="width: 40%;">First Name</th>
                        <td>{{ $user->first_name }}</td>
                    </tr>
                    <tr>
                        <th class="text-muted ps-0">Last Name</th>
                        <td>{{ $user->last_name }}</td>
                    </tr>
                    <tr>
                        <th class="text-muted ps-0">Email</th>
                        <td>{{ $user->email }}</td>
                    </tr>
                    <tr>
                        <th class="text-muted ps-0">Current Grade</th>
                        <td>
                            @switch($user->grade)
                                @case('grade_9_10')
                                    <span class="badge bg-success">Grade 9 & 10</span>
                                    @break
                                @case('grade_11')
                                    <span class="badge bg-info">Grade 11</span>
                                    @break
                                @case('grade_12')
                                    <span class="badge bg-warning text-dark">Grade 12</span>
                                    @break
                                @default
                                    <span class="badge bg-secondary">Not Set</span>
                            @endswitch
                        </td>
                    </tr>
                    <tr>
                        <th class="text-muted ps-0">Newsletter</th>
                        <td>
                            @if($user->newsletter)
                                <span class="badge bg-success">Yes</span>
                            @else
                                <span class="badge bg-secondary">No</span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th class="text-muted ps-0">Registered</th>
                        <td>{{ $user->created_at->format('M j, Y g:i A') }}</td>
                    </tr>
                    <tr>
                        <th class="text-muted ps-0">Last Login</th>
                        <td>
                            @if($user->last_login_at)
                                {{ $user->last_login_at->format('M j, Y g:i A') }}
                            @else
                                <span class="text-muted">Never</span>
                            @endif
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </div>

    <!-- Contact Messages -->
    <div class="col-md-8 mb-4">
        <div class="card h-100">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0"><i class="bi bi-envelope me-2"></i>Contact Messages</h5>
                @if($user->contactSubmissions->count() > 0)
                    <span class="badge bg-primary">{{ $user->contactSubmissions->count() }}</span>
                @endif
            </div>
            <div class="card-body">
                @if($user->contactSubmissions->count() > 0)
                    @foreach($user->contactSubmissions as $submission)
                        <div class="border rounded p-3 mb-3">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <span class="text-muted small">{{ $submission->created_at->format('M j, Y g:i A') }}</span>
                                @if(!$submission->is_read)
                                    <span class="badge bg-warning text-dark">Unread</span>
                                @else
                                    <span class="badge bg-secondary">Read</span>
                                @endif
                            </div>
                            <p class="mb-0">{{ $submission->comments }}</p>
                        </div>
                    @endforeach
                @else
                    <div class="text-center py-4 text-muted">
                        <i class="bi bi-inbox display-4"></i>
                        <p class="mt-2 mb-0">No contact messages from this user.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Login History -->
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0"><i class="bi bi-clock-history me-2"></i>Login History</h5>
        @if($user->loginHistories->count() > 0)
            <span class="badge bg-primary">{{ $user->loginHistories->count() }}</span>
        @endif
    </div>
    <div class="card-body">
        @if($user->loginHistories->count() > 0)
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Date & Time</th>
                        <th>IP Address</th>
                        <th>Browser / Device</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($user->loginHistories as $login)
                        <tr>
                            <td>{{ $login->logged_in_at->format('M j, Y g:i A') }}</td>
                            <td><code>{{ $login->ip_address }}</code></td>
                            <td class="text-truncate" style="max-width: 400px;">{{ $login->user_agent }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <div class="text-center py-4 text-muted">
                <i class="bi bi-clock display-4"></i>
                <p class="mt-2 mb-0">No login history recorded yet.</p>
            </div>
        @endif
    </div>
</div>
@endsection
