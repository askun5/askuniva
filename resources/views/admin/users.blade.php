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
<ul class="nav nav-pills mb-4">
    <li class="nav-item">
        <a class="nav-link {{ $filter === null ? 'active' : '' }}" href="{{ route('admin.users') }}">
            All Students
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link {{ $filter === 'grade_9_10' ? 'active' : '' }}" href="{{ route('admin.users', 'grade_9_10') }}">
            Grade 9 & 10
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link {{ $filter === 'grade_11' ? 'active' : '' }}" href="{{ route('admin.users', 'grade_11') }}">
            Grade 11
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link {{ $filter === 'grade_12' ? 'active' : '' }}" href="{{ route('admin.users', 'grade_12') }}">
            Grade 12
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
                        <th>Current Grade</th>
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
