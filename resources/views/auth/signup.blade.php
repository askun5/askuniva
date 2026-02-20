@extends('layouts.public')

@section('title', 'Get Started')

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

    .form-floating > label {
        color: #6c757d;
    }

    .password-requirements {
        font-size: 0.85rem;
        margin-top: 0.5rem;
    }

    .password-requirements .requirement {
        display: flex;
        align-items: center;
        margin-bottom: 0.25rem;
        color: #6c757d;
    }

    .password-requirements .requirement i {
        margin-right: 0.5rem;
    }

    .password-requirements .requirement.valid {
        color: #198754;
    }

    .password-requirements .requirement.invalid {
        color: #dc3545;
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
                        <h2>Get Started</h2>
                        <p class="text-muted">Create your Univa account</p>
                    </div>

                    <form method="POST" action="{{ route('signup.submit') }}" id="signup-form">
                        @csrf
                        <input type="hidden" name="recaptcha_token" id="recaptcha_token">
                        @error('recaptcha_token')
                            <div class="alert alert-danger mb-3">{{ $message }}</div>
                        @enderror

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <div class="form-floating">
                                    <input type="text"
                                           class="form-control @error('first_name') is-invalid @enderror"
                                           id="first_name"
                                           name="first_name"
                                           value="{{ old('first_name') }}"
                                           placeholder="First Name"
                                           required>
                                    <label for="first_name">First Name</label>
                                    @error('first_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="form-floating">
                                    <input type="text"
                                           class="form-control @error('last_name') is-invalid @enderror"
                                           id="last_name"
                                           name="last_name"
                                           value="{{ old('last_name') }}"
                                           placeholder="Last Name"
                                           required>
                                    <label for="last_name">Last Name</label>
                                    @error('last_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <div class="form-floating">
                                <input type="email"
                                       class="form-control @error('email') is-invalid @enderror"
                                       id="email"
                                       name="email"
                                       value="{{ old('email') }}"
                                       placeholder="Email"
                                       required>
                                <label for="email">Email Address</label>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <div class="form-floating">
                                <select class="form-select @error('grade') is-invalid @enderror"
                                        id="grade"
                                        name="grade"
                                        required>
                                    <option value="">Select your grade...</option>
                                    <option value="grade_9_10" {{ old('grade') == 'grade_9_10' ? 'selected' : '' }}>Grade 9 & 10</option>
                                    <option value="grade_11" {{ old('grade') == 'grade_11' ? 'selected' : '' }}>Grade 11</option>
                                    <option value="grade_12" {{ old('grade') == 'grade_12' ? 'selected' : '' }}>Grade 12</option>
                                </select>
                                <label for="grade">Current Grade</label>
                                @error('grade')
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
                            <div class="password-requirements" id="password-requirements">
                                <div class="requirement" id="req-length">
                                    <i class="bi bi-question-circle"></i>
                                    <span>At least 8 characters</span>
                                </div>
                                <div class="requirement" id="req-uppercase">
                                    <i class="bi bi-question-circle"></i>
                                    <span>At least 1 uppercase letter</span>
                                </div>
                                <div class="requirement" id="req-lowercase">
                                    <i class="bi bi-question-circle"></i>
                                    <span>At least 1 lowercase letter</span>
                                </div>
                                <div class="requirement" id="req-number">
                                    <i class="bi bi-question-circle"></i>
                                    <span>At least 1 number</span>
                                </div>
                                <div class="requirement" id="req-special">
                                    <i class="bi bi-question-circle"></i>
                                    <span>At least 1 special character</span>
                                </div>
                            </div>
                        </div>

                        <div class="mb-4">
                            <div class="form-floating">
                                <input type="password"
                                       class="form-control"
                                       id="password_confirmation"
                                       name="password_confirmation"
                                       placeholder="Confirm Password"
                                       required>
                                <label for="password_confirmation">Confirm Password</label>
                            </div>
                            <div class="password-requirements">
                                <div class="requirement" id="req-match">
                                    <i class="bi bi-question-circle"></i>
                                    <span>Passwords match</span>
                                </div>
                            </div>
                        </div>

                        <div class="mb-4">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="newsletter" name="newsletter" value="1" {{ old('newsletter') ? 'checked' : '' }}>
                                <label class="form-check-label" for="newsletter">
                                    Sign up for our newsletter to receive updates and tips
                                </label>
                            </div>
                        </div>

                        <div class="d-grid mb-3">
                            <button type="submit" class="btn btn-primary btn-lg">
                                Create Account
                            </button>
                        </div>

                        <p class="text-center text-muted mb-0">
                            Already have an account?
                            <a href="{{ route('signin') }}" class="text-decoration-none">Sign In</a>
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
    document.getElementById('signup-form').addEventListener('submit', function(e) {
        e.preventDefault();
        grecaptcha.ready(function() {
            grecaptcha.execute('{{ config('services.recaptcha.site_key') }}', {action: 'signup'}).then(function(token) {
                document.getElementById('recaptcha_token').value = token;
                document.getElementById('signup-form').submit();
            });
        });
    });

    // Password validation
    const password = document.getElementById('password');
    const passwordConfirm = document.getElementById('password_confirmation');

    const requirements = {
        length: { element: document.getElementById('req-length'), regex: /.{8,}/ },
        uppercase: { element: document.getElementById('req-uppercase'), regex: /[A-Z]/ },
        lowercase: { element: document.getElementById('req-lowercase'), regex: /[a-z]/ },
        number: { element: document.getElementById('req-number'), regex: /[0-9]/ },
        special: { element: document.getElementById('req-special'), regex: /[!@#$%^&*(),.?":{}|<>\-_=+\[\]\\\/`~;']/ }
    };

    function setRequirementState(element, state) {
        const icon = element.querySelector('i');
        element.classList.remove('valid', 'invalid');
        icon.classList.remove('bi-check-circle-fill', 'bi-x-circle-fill', 'bi-question-circle');

        if (state === 'valid') {
            element.classList.add('valid');
            icon.classList.add('bi-check-circle-fill');
        } else if (state === 'invalid') {
            element.classList.add('invalid');
            icon.classList.add('bi-x-circle-fill');
        } else {
            icon.classList.add('bi-question-circle');
        }
    }

    function validatePassword() {
        const value = password.value;

        for (const [key, req] of Object.entries(requirements)) {
            if (req.regex.test(value)) {
                setRequirementState(req.element, 'valid');
            } else {
                setRequirementState(req.element, 'invalid');
            }
        }

        validateMatch();
    }

    function validateMatch() {
        const matchElement = document.getElementById('req-match');
        if (passwordConfirm.value && password.value === passwordConfirm.value) {
            setRequirementState(matchElement, 'valid');
        } else if (passwordConfirm.value) {
            setRequirementState(matchElement, 'invalid');
        } else {
            setRequirementState(matchElement, 'neutral');
        }
    }

    password.addEventListener('input', validatePassword);
    passwordConfirm.addEventListener('input', validateMatch);

    // Initialize on page load
    if (password.value) {
        validatePassword();
    }
</script>
@endpush
