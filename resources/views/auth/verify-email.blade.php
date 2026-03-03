@extends('layouts.public')

@section('title', 'Verify Your Email')

@section('body-class', 'auth-page')

@push('styles')
<style>
    body.auth-page {
        background-color: #f8f9fa;
        min-height: 100vh;
        padding-bottom: 100px;
    }

    .auth-section {
        min-height: calc(100vh - 70px);
        display: flex;
        align-items: center;
        padding: 3rem 0;
    }

    .auth-card {
        max-width: 540px;
        margin: 0 auto;
    }

    .auth-header {
        text-align: center;
        margin-bottom: 2rem;
    }

    .auth-header h2 {
        font-weight: bold;
        color: #333;
    }
</style>
@endpush

@section('content')
<section class="auth-section">
    <div class="container px-4 px-lg-5">
        <div class="auth-card">
            <div class="card shadow">
                <div class="card-body p-5">
                    <div class="auth-header">
                        <i class="bi bi-envelope-check display-4 text-primary mb-3 d-block"></i>
                        <h2>Check Your Email</h2>
                        <p class="text-muted">
                            We sent a verification link to<br>
                            <strong>{{ auth()->user()->email }}</strong>
                        </p>
                    </div>

                    @if(session('success'))
                        <div class="alert alert-success mb-4">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if(session('status'))
                        <div class="alert alert-success mb-4">
                            {{ session('status') }}
                        </div>
                    @endif

                    <p class="text-center text-muted mb-4">
                        Click the link in the email to activate your account and access the student portal.
                        If you don't see it, please check your spam or junk folder.
                    </p>

                    <form method="POST" action="{{ route('verification.send') }}">
                        @csrf
                        <div class="d-grid mb-3">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="bi bi-arrow-repeat me-2"></i>Resend Verification Email
                            </button>
                        </div>
                    </form>

                    <form method="POST" action="{{ route('portal.signout') }}">
                        @csrf
                        <div class="d-grid">
                            <button type="submit" class="btn btn-outline-secondary">
                                Sign Out
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
