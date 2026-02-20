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
            <h2 class="fw-bold mb-3">University Application Guidelines</h2>
            <p class="text-muted lead">Select your grade level to view specific guidelines and recommendations</p>
        </div>

        <div class="row justify-content-center g-4">
            <!-- Grade 9 & 10 -->
            <div class="col-md-4">
                <a href="{{ route('portal.guidelines.show', 'grade_9_10') }}" class="text-decoration-none">
                    <div class="card h-100 grade-card {{ $user->grade === 'grade_9_10' ? 'border-primary' : '' }}">
                        <div class="card-body text-center p-4">
                            <div class="grade-icon mb-3">
                                <i class="bi bi-mortarboard fs-1 text-primary"></i>
                            </div>
                            <h4 class="card-title text-dark">Grade 9 & 10</h4>
                            <p class="card-text text-muted">Foundation building and early preparation for your university journey</p>
                            @if($user->grade === 'grade_9_10')
                                <span class="badge bg-primary">Your Grade</span>
                            @endif
                        </div>
                        <div class="card-footer bg-transparent border-0 text-center pb-4">
                            <span class="btn btn-outline-primary">
                                View Guidelines <i class="bi bi-arrow-right"></i>
                            </span>
                        </div>
                    </div>
                </a>
            </div>

            <!-- Grade 11 -->
            <div class="col-md-4">
                <a href="{{ route('portal.guidelines.show', 'grade_11') }}" class="text-decoration-none">
                    <div class="card h-100 grade-card {{ $user->grade === 'grade_11' ? 'border-primary' : '' }}">
                        <div class="card-body text-center p-4">
                            <div class="grade-icon mb-3">
                                <i class="bi bi-journal-bookmark fs-1 text-success"></i>
                            </div>
                            <h4 class="card-title text-dark">Grade 11</h4>
                            <p class="card-text text-muted">Strategic planning and test preparation for competitive applications</p>
                            @if($user->grade === 'grade_11')
                                <span class="badge bg-primary">Your Grade</span>
                            @endif
                        </div>
                        <div class="card-footer bg-transparent border-0 text-center pb-4">
                            <span class="btn btn-outline-success">
                                View Guidelines <i class="bi bi-arrow-right"></i>
                            </span>
                        </div>
                    </div>
                </a>
            </div>

            <!-- Grade 12 -->
            <div class="col-md-4">
                <a href="{{ route('portal.guidelines.show', 'grade_12') }}" class="text-decoration-none">
                    <div class="card h-100 grade-card {{ $user->grade === 'grade_12' ? 'border-primary' : '' }}">
                        <div class="card-body text-center p-4">
                            <div class="grade-icon mb-3">
                                <i class="bi bi-award fs-1 text-warning"></i>
                            </div>
                            <h4 class="card-title text-dark">Grade 12</h4>
                            <p class="card-text text-muted">Final application steps and deadline management for success</p>
                            @if($user->grade === 'grade_12')
                                <span class="badge bg-primary">Your Grade</span>
                            @endif
                        </div>
                        <div class="card-footer bg-transparent border-0 text-center pb-4">
                            <span class="btn btn-outline-warning">
                                View Guidelines <i class="bi bi-arrow-right"></i>
                            </span>
                        </div>
                    </div>
                </a>
            </div>
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
