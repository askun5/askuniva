@extends('layouts.public')

@section('title', 'Sign In')

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
        max-width: 600px;
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
                        <h2>Welcome Back</h2>
                        <p class="text-muted">Sign in to your account</p>
                    </div>

                    @if(session('status'))
                        <div class="alert alert-success mb-4">
                            {{ session('status') }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('signin.submit') }}" id="signin-form">
                        @csrf
                        <input type="hidden" name="recaptcha_token" id="recaptcha_token">
                        @error('recaptcha_token')
                            <div class="alert alert-danger mb-3">{{ $message }}</div>
                        @enderror

                        <div class="mb-3">
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

                        <div class="mb-3">
                            <div class="form-floating">
                                <input type="password"
                                       class="form-control @error('password') is-invalid @enderror"
                                       id="password"
                                       name="password"
                                       placeholder="Password"
                                       required>
                                <label for="password">Password</label>
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-4 d-flex justify-content-between align-items-center">
                            <div class="form-check">
                                <input class="form-check-input"
                                       type="checkbox"
                                       name="remember"
                                       id="remember"
                                       {{ old('remember') ? 'checked' : '' }}>
                                <label class="form-check-label" for="remember">
                                    Remember me
                                </label>
                            </div>
                            <a href="{{ route('password.request') }}" class="text-decoration-none">
                                Forgot Password?
                            </a>
                        </div>

                        <div class="d-grid mb-3">
                            <button type="submit" class="btn btn-primary btn-lg">
                                Sign In
                            </button>
                        </div>

                        <p class="text-center text-muted mb-0">
                            Don't have an account?
                            <a href="{{ route('signup') }}" class="text-decoration-none">Get Started</a>
                        </p>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@push('scripts')
<script src="https://www.google.com/recaptcha/api.js?render={{ config('services.recaptcha.site_key') }}"></script>
<script>
    document.getElementById('signin-form').addEventListener('submit', function(e) {
        e.preventDefault();
        grecaptcha.ready(function() {
            grecaptcha.execute('{{ config('services.recaptcha.site_key') }}', {action: 'signin'}).then(function(token) {
                document.getElementById('recaptcha_token').value = token;
                document.getElementById('signin-form').submit();
            });
        });
    });
</script>
@endpush
