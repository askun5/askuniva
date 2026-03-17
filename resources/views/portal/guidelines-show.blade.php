@extends('layouts.portal')

@section('title', 'Guidelines - ' . ($guideline ? $guideline->grade_display : 'Not Found'))

@section('content')
<div class="row">
    <div class="col-12">
        <h1 class="mt-0 mb-4">Guidelines</h1>

        @php
            $gradeLabels = [
                'grade_9_10'        => 'High School (Grades 9 & 10)',
                'grade_11'          => 'High School (Grade 11)',
                'grade_12'          => 'High School (Grade 12)',
                'community_college' => 'Community College',
                'undergraduate'     => 'Undergraduate',
                'graduate'          => 'Graduate (Master\'s/PhD)',
            ];
        @endphp
        @php
            $gradeLabel = $guideline ? $guideline->grade_display : ($gradeLabels[$grade] ?? ucwords(str_replace('_', ' ', $grade)));
        @endphp

        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">{{ $gradeLabel }}</h5>
            </div>
            <div class="card-body">
                @if($guideline)
                    <div class="guideline-content">
                        {!! $guideline->content !!}
                    </div>
                @else
                    <p class="mb-0">Guidelines for this grade level are being prepared. Please check back soon!</p>
                @endif
            </div>
        </div>

        <div class="mt-4 d-flex justify-content-end">
            <a href="{{ route('portal.advisor') }}" class="btn btn-primary btn-lg">
                Chat with AI Advisor
            </a>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
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
