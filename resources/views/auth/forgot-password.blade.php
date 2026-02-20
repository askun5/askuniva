@extends('layouts.public')

@section('title', 'Forgot Password')

@section('body-class', 'auth-page')

@push('styles')
<style>
    body.auth-page {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
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
        max-width: 450px;
        margin: 0 auto;
    }

    .auth-card .card {
        border: none;
        border-radius: 1rem;
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
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
            <div class="card">
                <div class="card-body p-5">
                    <div class="auth-header">
                        <h2>Forgot Password?</h2>
                        <p class="text-muted">Enter your email to receive a reset link</p>
                    </div>

                    @if(session('status'))
                        <div class="alert alert-success mb-4">
                            {{ session('status') }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('password.email') }}">
                        @csrf

                        <div class="mb-4">
                            <div class="form-floating">
                                <input type="email"
                                       class="form-control @error('email') is-invalid @enderror"
                                       id="email"
                                       name="email"
                                       value="{{ old('email') }}"
                                       placeholder="Email"
                                       required
                                       autofocus>
                                <label for="email">Email Address</label>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="d-grid mb-3">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="bi bi-envelope me-2"></i>Send Reset Link
                            </button>
                        </div>

                        <p class="text-center text-muted mb-0">
                            Remember your password?
                            <a href="{{ route('signin') }}" class="text-decoration-none">Sign In</a>
                        </p>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
