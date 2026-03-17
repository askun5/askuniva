@extends('layouts.portal')

@section('title', 'Dashboard')

@section('content')
<div class="row">
    <div class="col-12">
        <h1 class="mb-4">Welcome, {{ $user->first_name }}!</h1>

        <div class="row">
            <!-- Quick Access Cards -->
            <div class="col-md-4 mb-4">
                <div class="card h-100">
                    <div class="card-body text-center">
                        <h5 class="card-title">Guidelines</h5>
                        <p class="card-text text-muted">View college preparation guidelines tailored for {{ $user->grade_display }}.</p>
                        <a href="{{ route('portal.guidelines.show', $user->grade) }}" class="btn btn-primary btn-lg">
                            View Guidelines
                        </a>
                    </div>
                </div>
            </div>

            <div class="col-md-4 mb-4">
                <div class="card h-100">
                    <div class="card-body text-center">
                        <h5 class="card-title">AI Advisor</h5>
                        <p class="card-text text-muted">Chat with your AI college counselor for personalized advice.</p>
                        <a href="{{ route('portal.advisor') }}" class="btn btn-primary btn-lg">
                            Start Chat
                        </a>
                    </div>
                </div>
            </div>

            <div class="col-md-4 mb-4">
                <div class="card h-100">
                    <div class="card-body text-center">
                        <h5 class="card-title">Your Profile</h5>
                        <p class="card-text text-muted">Update your personal information and account settings.</p>
                        <a href="{{ route('portal.profile') }}" class="btn btn-primary btn-lg">
                            Edit Profile
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Info Card -->
        <div class="card mt-4">
            <div class="card-header bg-light">
                <h5 class="mb-0"><i class="bi bi-info-circle me-2"></i>About Your Journey</h5>
            </div>
            <div class="card-body">
                <p>As a <strong>{{ $user->grade_display }}</strong> student, you're at an important stage of your college preparation journey.</p>
                <p class="mb-0">Use the <strong>Guidelines</strong> section to learn about what you should focus on now, and chat with the <strong>AI Advisor</strong> for personalized guidance on your specific questions about college admissions, test preparation, extracurriculars, and more.</p>
            </div>
        </div>
    </div>
</div>
@endsection
