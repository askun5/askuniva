@extends('layouts.portal')

@section('title', 'Guidelines')

@section('content')
<div class="row">
    <div class="col-12">
        <nav aria-label="breadcrumb" class="mb-4">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('portal.dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item active" aria-current="page">Guidelines</li>
            </ol>
        </nav>

        <div class="text-center mb-5">
            <h2 class="fw-bold mb-3">Academic Guidelines</h2>
            <p class="text-muted lead">Select your academic level to view specific guidelines and recommendations</p>
        </div>

        @php
            $levels = [
                'grade_9_10'       => ['label' => 'High School (Grades 9 & 10)', 'icon' => 'bi-mortarboard',     'color' => 'primary',  'desc' => 'Foundation building and early preparation for your academic journey'],
                'grade_11'         => ['label' => 'High School (Grade 11)',       'icon' => 'bi-journal-bookmark','color' => 'success',  'desc' => 'Strategic planning and test preparation for competitive applications'],
                'grade_12'         => ['label' => 'High School (Grade 12)',       'icon' => 'bi-award',           'color' => 'warning',  'desc' => 'Final application steps and deadline management for success'],
                'community_college'=> ['label' => 'Community College',            'icon' => 'bi-building',        'color' => 'info',     'desc' => 'Transfer planning, certifications, and career pathway guidance'],
                'undergraduate'    => ['label' => 'Undergraduate',                'icon' => 'bi-book',            'color' => 'secondary','desc' => 'Navigating university life, internships, and career preparation'],
                'graduate'         => ['label' => 'Graduate (Master\'s/PhD)',     'icon' => 'bi-mortarboard-fill','color' => 'dark',     'desc' => 'Research, applications, and advancing your academic career'],
            ];
        @endphp

        <div class="row justify-content-center g-4">
            @foreach($levels as $key => $level)
            <div class="col-md-4">
                <a href="{{ route('portal.guidelines.show', $key) }}" class="text-decoration-none">
                    <div class="card h-100 grade-card {{ $user->grade === $key ? 'border-primary' : '' }}">
                        <div class="card-body text-center p-4">
                            <div class="grade-icon mb-3">
                                <i class="bi {{ $level['icon'] }} fs-1 text-{{ $level['color'] }}"></i>
                            </div>
                            <h4 class="card-title text-dark">{{ $level['label'] }}</h4>
                            <p class="card-text text-muted">{{ $level['desc'] }}</p>
                            @if($user->grade === $key)
                                <span class="badge bg-primary">Your Level</span>
                            @endif
                        </div>
                        <div class="card-footer bg-transparent border-0 text-center pb-4">
                            <span class="btn btn-outline-{{ $level['color'] }}">
                                View Guidelines <i class="bi bi-arrow-right"></i>
                            </span>
                        </div>
                    </div>
                </a>
            </div>
            @endforeach
        </div>

        <div class="mt-5 text-center">
            <a href="{{ route('portal.advisor') }}" class="btn btn-success btn-lg">
                <i class="bi bi-robot me-2"></i>Have Questions? Chat with AI Advisor
            </a>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .grade-card {
        transition: all 0.3s ease;
        cursor: pointer;
        border-radius: 12px;
    }

    .grade-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 12px 40px rgba(0, 0, 0, 0.15);
    }

    .grade-card.border-primary {
        border-width: 2px !important;
    }

    .grade-icon {
        width: 80px;
        height: 80px;
        border-radius: 50%;
        background: #f8f9fa;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 1rem;
    }

    .grade-card:hover .grade-icon {
        background: #e9ecef;
    }
</style>
@endpush
