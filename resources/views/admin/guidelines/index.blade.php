@extends('layouts.admin')

@section('title', 'Grade Guidelines')

@section('content')
<h1 class="mb-4">Grade-Specific Guidelines</h1>

<div class="card">
    <div class="card-body">
        <p class="text-muted mb-4">
            Manage the college preparation guidelines shown to students based on their grade level.
        </p>

        <div class="row">
            @php
                $grades = [
                    'grade_9_10' => ['name' => 'Grades 9 & 10', 'icon' => 'bi-journal-bookmark', 'color' => 'success'],
                    'grade_11' => ['name' => 'Grade 11', 'icon' => 'bi-journal-text', 'color' => 'info'],
                    'grade_12' => ['name' => 'Grade 12', 'icon' => 'bi-journal-check', 'color' => 'warning'],
                ];
            @endphp

            @foreach($grades as $gradeKey => $gradeInfo)
                @php
                    $guideline = $guidelines->where('grade', $gradeKey)->first();
                @endphp
                <div class="col-md-4 mb-4">
                    <div class="card h-100 border-{{ $gradeInfo['color'] }}">
                        <div class="card-header bg-{{ $gradeInfo['color'] }} {{ $gradeInfo['color'] == 'warning' ? 'text-dark' : 'text-white' }}">
                            <h5 class="mb-0">
                                <i class="bi {{ $gradeInfo['icon'] }} me-2"></i>{{ $gradeInfo['name'] }}
                            </h5>
                        </div>
                        <div class="card-body">
                            @if($guideline)
                                <h6>{{ $guideline->title }}</h6>
                                <p class="text-muted small">
                                    Last updated: {{ $guideline->updated_at->format('M j, Y') }}
                                </p>
                            @else
                                <p class="text-muted">No guidelines set yet.</p>
                            @endif
                        </div>
                        <div class="card-footer bg-transparent">
                            <a href="{{ route('admin.guidelines.edit', $gradeKey) }}" class="btn btn-{{ $gradeInfo['color'] }} {{ $gradeInfo['color'] == 'warning' ? 'text-dark' : '' }} w-100">
                                <i class="bi bi-pencil me-2"></i>Edit Guidelines
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>
@endsection
