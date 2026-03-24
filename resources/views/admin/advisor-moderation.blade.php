@extends('layouts.admin')

@section('title', 'Advisor Moderation')

@section('content')
<h1 class="mb-4">Advisor Moderation</h1>

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

<div class="card">
    <div class="card-header fw-semibold d-flex align-items-center justify-content-between">
        <span><i class="bi bi-shield-exclamation text-warning me-2"></i>Flagged Students</span>
        <span class="text-muted small fw-normal">{{ $flaggedUsers->count() }} student(s)</span>
    </div>

    @if($flaggedUsers->isEmpty())
        <div class="card-body text-muted text-center py-5">
            <i class="bi bi-shield-check display-4 text-success d-block mb-3"></i>
            No policy violations recorded.
        </div>
    @else
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Student</th>
                        <th>Email</th>
                        <th class="text-center">Warnings</th>
                        <th class="text-center">Status</th>
                        <th>Last Violation</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($flaggedUsers as $student)
                        @php
                            $lastWarning = \App\Models\AiChatWarning::where('user_id', $student->id)
                                ->latest()->first();
                        @endphp
                        <tr>
                            <td class="fw-medium">{{ $student->full_name }}</td>
                            <td class="text-muted small">{{ $student->email }}</td>
                            <td class="text-center">
                                <span class="badge {{ $student->advisor_warnings >= 3 ? 'bg-danger' : 'bg-warning text-dark' }}">
                                    {{ $student->advisor_warnings }}
                                </span>
                            </td>
                            <td class="text-center">
                                @if($student->advisor_suspended_at)
                                    <span class="badge bg-danger">Suspended</span>
                                @else
                                    <span class="badge bg-success">Active</span>
                                @endif
                            </td>
                            <td class="text-muted small">
                                @if($lastWarning)
                                    <span title="{{ $lastWarning->message_content }}">
                                        {{ ucfirst(str_replace('_', ' ', $lastWarning->reason)) }}
                                        &middot; {{ $lastWarning->created_at->diffForHumans() }}
                                    </span>
                                @else
                                    —
                                @endif
                            </td>
                            <td>
                                <div class="d-flex gap-2">
                                    @if($student->advisor_suspended_at)
                                        <form method="POST" action="{{ route('admin.advisor.moderation.unsuspend', $student) }}">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-outline-success">
                                                <i class="bi bi-unlock me-1"></i>Unsuspend
                                            </button>
                                        </form>
                                    @endif
                                    @if($student->advisor_warnings > 0)
                                        <form method="POST" action="{{ route('admin.advisor.moderation.reset', $student) }}">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-outline-secondary"
                                                    onclick="return confirm('Reset all warnings for {{ $student->first_name }}?')">
                                                <i class="bi bi-arrow-counterclockwise me-1"></i>Reset Warnings
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>

                        {{-- Warning detail rows --}}
                        @php
                            $allWarnings = \App\Models\AiChatWarning::where('user_id', $student->id)
                                ->latest()->take(5)->get();
                        @endphp
                        @if($allWarnings->isNotEmpty())
                            <tr class="table-light">
                                <td colspan="6" class="ps-4 py-2">
                                    <small class="text-muted fw-medium">Recent violations:</small>
                                    <div class="d-flex flex-wrap gap-2 mt-1">
                                        @foreach($allWarnings as $w)
                                            <span class="badge bg-light text-dark border small"
                                                  title="{{ $w->message_content }}">
                                                #{{ $w->warning_number }}
                                                {{ ucfirst(str_replace('_', ' ', $w->reason)) }}
                                                &middot; {{ $w->created_at->diffForHumans() }}
                                            </span>
                                        @endforeach
                                    </div>
                                </td>
                            </tr>
                        @endif
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>
@endsection
