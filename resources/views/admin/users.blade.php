@extends('layouts.admin')

@section('title', $title)

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="mb-0">{{ $title }}</h1>
    <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left me-1"></i>Back to Dashboard
    </a>
</div>

<!-- Filter Tabs -->
<ul class="nav nav-pills mb-4 flex-wrap">
    <li class="nav-item">
        <a class="nav-link {{ $filter === null ? 'active' : '' }}" href="{{ route('admin.users') }}">
            All
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link {{ $filter === 'grade_9_10' ? 'active' : '' }}" href="{{ route('admin.users', 'grade_9_10') }}">
            HS 9 & 10
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link {{ $filter === 'grade_11' ? 'active' : '' }}" href="{{ route('admin.users', 'grade_11') }}">
            HS 11
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link {{ $filter === 'grade_12' ? 'active' : '' }}" href="{{ route('admin.users', 'grade_12') }}">
            HS 12
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link {{ $filter === 'community_college' ? 'active' : '' }}" href="{{ route('admin.users', 'community_college') }}">
            Comm. College
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link {{ $filter === 'undergraduate' ? 'active' : '' }}" href="{{ route('admin.users', 'undergraduate') }}">
            Undergraduate
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link {{ $filter === 'graduate' ? 'active' : '' }}" href="{{ route('admin.users', 'graduate') }}">
            Graduate
        </a>
    </li>
</ul>

<div class="card">
    <div class="card-body">
        @if($users->count() > 0)
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>First Name</th>
                        <th>Last Name</th>
                        <th>Email Address</th>
                        <th>Academic Level</th>
                        <th>Location</th>
                        <th>Newsletter</th>
                        <th>Last Login</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($users as $user)
                        <tr style="cursor: pointer;" onclick="window.location='{{ route('admin.user.show', $user) }}'">
                            <td>{{ $user->first_name }}</td>
                            <td>{{ $user->last_name }}</td>
                            <td>{{ $user->email }}</td>
                            <td>
                                @switch($user->grade)
                                    @case('grade_9_10')
                                        <span class="badge bg-primary">HS 9 & 10</span>
                                        @break
                                    @case('grade_11')
                                        <span class="badge bg-success">HS 11</span>
                                        @break
                                    @case('grade_12')
                                        <span class="badge bg-warning text-dark">HS 12</span>
                                        @break
                                    @case('community_college')
                                        <span class="badge bg-info">Comm. College</span>
                                        @break
                                    @case('undergraduate')
                                        <span class="badge bg-secondary">Undergrad</span>
                                        @break
                                    @case('graduate')
                                        <span class="badge bg-dark">Graduate</span>
                                        @break
                                    @default
                                        <span class="badge bg-secondary">Not Set</span>
                                @endswitch
                            </td>
                            <td>
                                @if($user->city && $user->state)
                                    {{ $user->city }}, {{ $user->state }}
                                    @if($user->zip_code)
                                        <span class="text-muted small">{{ $user->zip_code }}</span>
                                    @endif
                                @else
                                    <span class="text-muted">—</span>
                                @endif
                            </td>
                            <td>
                                @if($user->newsletter)
                                    <span class="badge bg-success">Yes</span>
                                @else
                                    <span class="badge bg-secondary">No</span>
                                @endif
                            </td>
                            <td>
                                @if($user->last_login_at)
                                    {{ $user->last_login_at->format('M j, Y g:i A') }}
                                @else
                                    <span class="text-muted">Never</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <div class="d-flex justify-content-center mt-4">
                {{ $users->links() }}
            </div>
        @else
            <div class="text-center py-5 text-muted">
                <i class="bi bi-people display-1"></i>
                <p class="mt-3">No students found.</p>
            </div>
        @endif
    </div>
</div>
@endsection
