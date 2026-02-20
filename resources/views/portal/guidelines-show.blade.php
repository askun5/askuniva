@extends('layouts.portal')

@section('title', 'Guidelines - ' . ($guideline ? $guideline->grade_display : 'Not Found'))

@section('content')
<div class="row">
    <div class="col-12">
        <nav aria-label="breadcrumb" class="mb-4">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('portal.dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('portal.guidelines') }}">Guidelines</a></li>
                <li class="breadcrumb-item active" aria-current="page">
                    @switch($grade)
                        @case('grade_9_10')
                            Grade 9 & 10
                            @break
                        @case('grade_11')
                            Grade 11
                            @break
                        @case('grade_12')
                            Grade 12
                            @break
                    @endswitch
                </li>
            </ol>
        </nav>

        <!-- Grade Navigation Tabs -->
        <ul class="nav nav-pills mb-4 justify-content-center">
            <li class="nav-item">
                <a class="nav-link {{ $grade === 'grade_9_10' ? 'active' : '' }}"
                   href="{{ route('portal.guidelines.show', 'grade_9_10') }}">
                    <i class="bi bi-mortarboard me-1"></i> Grade 9 & 10
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ $grade === 'grade_11' ? 'active' : '' }}"
                   href="{{ route('portal.guidelines.show', 'grade_11') }}">
                    <i class="bi bi-journal-bookmark me-1"></i> Grade 11
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ $grade === 'grade_12' ? 'active' : '' }}"
                   href="{{ route('portal.guidelines.show', 'grade_12') }}">
                    <i class="bi bi-award me-1"></i> Grade 12
                </a>
            </li>
        </ul>

        @if($guideline)
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">
                        <i class="bi bi-book me-2"></i>{{ $guideline->title }}
                    </h4>
                </div>
                <div class="card-body">
                    <div class="guideline-content">
                        {!! $guideline->content !!}
                    </div>
                </div>
            </div>
        @else
            <div class="alert alert-info">
                <i class="bi bi-info-circle me-2"></i>
                Guidelines for this grade level are being prepared. Please check back soon!
            </div>
        @endif

        <div class="mt-4 d-flex justify-content-between align-items-center">
            <a href="{{ route('portal.guidelines') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left me-2"></i>Back to All Guidelines
            </a>
            <a href="{{ route('portal.advisor') }}" class="btn btn-success">
                <i class="bi bi-robot me-2"></i>Have Questions? Chat with AI Advisor
            </a>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .nav-pills .nav-link {
        border-radius: 20px;
        padding: 0.5rem 1.25rem;
        margin: 0 0.25rem;
        color: #495057;
        background-color: #f8f9fa;
    }

    .nav-pills .nav-link:hover {
        background-color: #e9ecef;
    }

    .nav-pills .nav-link.active {
        background-color: #0d6efd;
        color: white;
    }

    .guideline-content {
        line-height: 1.8;
    }

    .guideline-content h2, .guideline-content h3 {
        margin-top: 1.5rem;
        margin-bottom: 1rem;
        color: #333;
    }

    .guideline-content ul, .guideline-content ol {
        margin-bottom: 1rem;
    }

    .guideline-content li {
        margin-bottom: 0.5rem;
    }

    .guideline-content p {
        margin-bottom: 1rem;
    }
</style>
@endpush
