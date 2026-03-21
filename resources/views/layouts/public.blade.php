<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Univa - Your AI-powered virtual college counselor">
    <meta name="author" content="Univa">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Univa') - AI College Counselor</title>

    <!-- Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-6WVMERGRQK"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());
        gtag('config', 'G-6WVMERGRQK');
    </script>

    <!-- Favicon -->
    @php
        $favicon = \App\Models\SiteSetting::get('site_favicon');
    @endphp
    <link rel="icon" type="image/x-icon" href="{{ $favicon ? Storage::url($favicon) : asset('assets/favicon.ico') }}">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="{{ asset('css/public.css') }}" rel="stylesheet">

    <!-- Turbo for SPA-like navigation -->
    <script type="module">
        import hotwiredTurbo from 'https://cdn.jsdelivr.net/npm/@hotwired/turbo@8.0.12/+esm';
    </script>

    @stack('styles')

    <style>
        .grecaptcha-badge {
            bottom: 20px !important;
        }
        body {
            padding-top: 56px;
        }
        #public-topnav .navbar-brand img {
            height: 30px;
            width: auto;
        }
        /* Password requirement indicators (used in signup modal) */
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
        .password-requirements .requirement i { margin-right: 0.5rem; }
        .password-requirements .requirement.valid { color: #198754; }
        .password-requirements .requirement.invalid { color: #dc3545; }
        /* Zip suggestions in modal */
        #modal-zip-suggestions {
            top: 100%;
            left: 0;
            border: 1px solid #dee2e6;
            border-radius: 0.375rem;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
            max-height: 200px;
            overflow-y: auto;
        }
        #modal-zip-suggestions .list-group-item {
            cursor: pointer;
            border-left: none;
            border-right: none;
            font-size: 0.95rem;
        }
        #modal-city, #modal-state { background-color: #f8f9fa; }
    </style>
</head>
<body class="@yield('body-class')">

    <!-- Top Navigation -->
    <nav id="public-topnav" class="navbar navbar-dark bg-dark fixed-top px-3" data-turbo-permanent>
        @php $topLogo = \App\Models\SiteSetting::get('site_logo'); @endphp
        <a class="navbar-brand" href="{{ route('home') }}">
            @if($topLogo)
                <img src="{{ Storage::url($topLogo) }}" alt="Univa">
            @else
                <img src="{{ asset('images/univa-logo.png') }}" alt="Univa">
            @endif
        </a>
        <div class="ms-auto">
            @auth
                <a href="{{ route('portal.dashboard') }}" class="btn btn-primary btn-sm">Portal</a>
            @else
                <a href="#" class="btn btn-outline-light btn-sm px-4" data-bs-toggle="modal" data-bs-target="#signinModal">Sign In</a>
            @endauth
        </div>
    </nav>

    @yield('content')

    <!-- Footer -->
    <footer class="py-4 bg-dark mt-auto" data-turbo-permanent>
        <div class="container-fluid px-4">
            <div class="d-flex align-items-center justify-content-between small">
                <div>
                    @php
                        $footerLinks = json_decode(\App\Models\SiteSetting::get('footer_links', '[]'), true) ?? [
                            ['label' => 'Home', 'url' => '/'],
                            ['label' => 'About', 'url' => '/about'],
                            ['label' => 'Privacy', 'url' => '/privacy'],
                            ['label' => 'Contact', 'url' => '/contact'],
                        ];
                    @endphp
                    @foreach($footerLinks as $link)
                        @if($link['label'] === 'Contact')
                            <a href="#" class="text-light me-3" data-bs-toggle="modal" data-bs-target="#contactModal">{{ $link['label'] }}</a>
                        @else
                            <a href="{{ $link['url'] }}" class="text-light me-3">{{ $link['label'] }}</a>
                        @endif
                    @endforeach
                </div>
                <div class="text-light opacity-75">
                    @php
                        $copyrightText = \App\Models\SiteSetting::get('copyright_text', 'Univa. All rights reserved.');
                    @endphp
                    &copy; <span id="copyright-year"></span> {{ $copyrightText }}
                </div>
            </div>
        </div>
    </footer>

    <!-- ===================== Contact Modal ===================== -->
    <div class="modal fade" id="contactModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header border-0 pb-0">
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body px-3 px-sm-5 pb-5 pt-0">
                    <div class="text-center mb-4">
                        <h1 class="mb-2">Contact Us</h1>
                        <h2 class="h5 text-muted mb-0">Have questions?<br>We'd love to hear from you.</h2>
                    </div>

                    @if(session('contact_success'))
                        <div class="alert alert-success">{{ session('contact_success') }}</div>
                    @endif

                    <form method="POST" action="{{ route('contact.submit') }}" id="modal-contact-form">
                        @csrf
                        <input type="hidden" name="recaptcha_token" id="modal-contact-recaptcha">
                        <input type="hidden" name="form_type" value="contact">

                        @error('recaptcha_token')
                            <div class="alert alert-danger mb-3">{{ $message }}</div>
                        @enderror

                        <div class="mb-3">
                            <label for="modal-contact-email" class="form-label">Email Address</label>
                            <input type="email"
                                   class="form-control @error('email') is-invalid @enderror"
                                   id="modal-contact-email"
                                   name="email"
                                   value="{{ old('email') }}"
                                   required>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="modal-contact-comments" class="form-label">Your Message</label>
                            <textarea class="form-control @error('comments') is-invalid @enderror"
                                      id="modal-contact-comments"
                                      name="comments"
                                      rows="5"
                                      required>{{ old('comments') }}</textarea>
                            @error('comments')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary btn-lg">Send Message</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- ===================== Sign In Modal ===================== -->
    <div class="modal fade" id="signinModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header border-0 pb-0">
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body px-3 px-sm-5 pb-5 pt-0">
                    <div class="text-center mb-4">
                        <h1 class="mb-2">Welcome Back</h1>
                        <h2 class="h5 text-muted mb-0">Sign in to your account</h2>
                    </div>

                    @if(session('status'))
                        <div class="alert alert-success mb-4">{{ session('status') }}</div>
                    @endif

                    <form method="POST" action="{{ route('signin.submit') }}" id="modal-signin-form">
                        @csrf
                        <input type="hidden" name="recaptcha_token" id="modal-signin-recaptcha">
                        <input type="hidden" name="form_type" value="signin">

                        @error('recaptcha_token')
                            <div class="alert alert-danger mb-3">{{ $message }}</div>
                        @enderror

                        <div class="mb-3">
                            <div class="form-floating">
                                <input type="email"
                                       class="form-control @error('email') is-invalid @enderror"
                                       id="modal-signin-email"
                                       name="email"
                                       value="{{ old('email') }}"
                                       placeholder="Email"
                                       required>
                                <label for="modal-signin-email">Email Address</label>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <div class="form-floating">
                                <input type="password"
                                       class="form-control @error('password') is-invalid @enderror"
                                       id="modal-signin-password"
                                       name="password"
                                       placeholder="Password"
                                       required>
                                <label for="modal-signin-password">Password</label>
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-4 d-flex justify-content-between align-items-center">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="remember" id="modal-signin-remember" {{ old('remember') ? 'checked' : '' }}>
                                <label class="form-check-label" for="modal-signin-remember">Remember me</label>
                            </div>
                            <a href="{{ route('password.request') }}" class="text-decoration-none">Forgot Password?</a>
                        </div>

                        <div class="d-grid mb-3">
                            <button type="submit" class="btn btn-primary btn-lg">Sign In</button>
                        </div>

                        <p class="text-center text-muted mb-0">
                            Don't have an account?
                            <a href="#" class="text-decoration-none modal-switch-to-signup">Get Started</a>
                        </p>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- ===================== Sign Up Modal ===================== -->
    <div class="modal fade" id="signupModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header border-0 pb-0">
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body px-3 px-sm-5 pb-5 pt-0">
                    <div class="text-center mb-4">
                        <h1 class="mb-2">Get Started</h1>
                        <h2 class="h5 text-muted mb-0">Create your Univa account</h2>
                    </div>

                    <form method="POST" action="{{ route('signup.submit') }}" id="modal-signup-form">
                        @csrf
                        <input type="hidden" name="recaptcha_token" id="modal-signup-recaptcha">
                        <input type="hidden" name="form_type" value="signup">

                        @error('recaptcha_token')
                            <div class="alert alert-danger mb-3">{{ $message }}</div>
                        @enderror

                        <div class="mb-3">
                            <div class="form-floating">
                                <input type="text" class="form-control @error('first_name') is-invalid @enderror" id="modal-first-name" name="first_name" value="{{ old('first_name') }}" placeholder="First Name" required>
                                <label for="modal-first-name">First Name</label>
                                @error('first_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>
                        <div class="mb-3">
                            <div class="form-floating">
                                <input type="text" class="form-control @error('last_name') is-invalid @enderror" id="modal-last-name" name="last_name" value="{{ old('last_name') }}" placeholder="Last Name" required>
                                <label for="modal-last-name">Last Name</label>
                                @error('last_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <div class="form-floating">
                                <input type="email" class="form-control @error('email') is-invalid @enderror" id="modal-email" name="email" value="{{ old('email') }}" placeholder="Email" required>
                                <label for="modal-email">Email Address</label>
                                @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <div class="form-floating">
                                <select class="form-select @error('grade') is-invalid @enderror" id="modal-grade" name="grade" required>
                                    <option value="">Select your academic level...</option>
                                    <option value="grade_9_10" {{ old('grade') == 'grade_9_10' ? 'selected' : '' }}>High School (Grades 9 & 10)</option>
                                    <option value="grade_11" {{ old('grade') == 'grade_11' ? 'selected' : '' }}>High School (Grade 11)</option>
                                    <option value="grade_12" {{ old('grade') == 'grade_12' ? 'selected' : '' }}>High School (Grade 12)</option>
                                    <option value="community_college" {{ old('grade') == 'community_college' ? 'selected' : '' }}>Community College</option>
                                    <option value="undergraduate" {{ old('grade') == 'undergraduate' ? 'selected' : '' }}>Undergraduate (University)</option>
                                    <option value="graduate" {{ old('grade') == 'graduate' ? 'selected' : '' }}>Graduate (Master's/PhD)</option>
                                    <option value="gap_year" {{ old('grade') == 'gap_year' ? 'selected' : '' }}>Gap Year</option>
                                </select>
                                <label for="modal-grade">Academic Level</label>
                                @error('grade')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <div class="position-relative">
                                <div class="form-floating">
                                    <input type="text" class="form-control @error('zip_code') is-invalid @enderror" id="modal-zip" name="zip_code" value="{{ old('zip_code') }}" placeholder="Zip Code" maxlength="10" autocomplete="off" required>
                                    <label for="modal-zip">Zip Code</label>
                                    @error('zip_code')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                                <div id="modal-zip-suggestions" class="list-group position-absolute w-100" style="display:none; z-index:1060;"></div>
                            </div>
                        </div>

                        <div class="row" id="modal-city-state-row" style="{{ old('city') ? '' : 'display:none;' }}">
                            <div class="col-md-8 mb-3">
                                <div class="form-floating">
                                    <input type="text" class="form-control @error('city') is-invalid @enderror" id="modal-city" name="city" value="{{ old('city') }}" placeholder="City" readonly>
                                    <label for="modal-city">City</label>
                                    @error('city')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                            </div>
                            <div class="col-md-4 mb-3">
                                <div class="form-floating">
                                    <input type="text" class="form-control @error('state') is-invalid @enderror" id="modal-state" name="state" value="{{ old('state') }}" placeholder="State" readonly>
                                    <label for="modal-state">State</label>
                                    @error('state')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <div class="form-floating">
                                <input type="password" class="form-control @error('password') is-invalid @enderror" id="modal-password" name="password" placeholder="Password" required>
                                <label for="modal-password">Password</label>
                                @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="password-requirements">
                                <div class="requirement" id="modal-req-length"><i class="bi bi-question-circle"></i><span>At least 8 characters</span></div>
                                <div class="requirement" id="modal-req-uppercase"><i class="bi bi-question-circle"></i><span>At least 1 uppercase letter</span></div>
                                <div class="requirement" id="modal-req-lowercase"><i class="bi bi-question-circle"></i><span>At least 1 lowercase letter</span></div>
                                <div class="requirement" id="modal-req-number"><i class="bi bi-question-circle"></i><span>At least 1 number</span></div>
                                <div class="requirement" id="modal-req-special"><i class="bi bi-question-circle"></i><span>At least 1 special character</span></div>
                            </div>
                        </div>

                        <div class="mb-4">
                            <div class="form-floating">
                                <input type="password" class="form-control" id="modal-password-confirm" name="password_confirmation" placeholder="Confirm Password" required>
                                <label for="modal-password-confirm">Confirm Password</label>
                            </div>
                            <div class="password-requirements">
                                <div class="requirement" id="modal-req-match"><i class="bi bi-question-circle"></i><span>Passwords match</span></div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <div class="form-check">
                                <input class="form-check-input @error('agree_privacy') is-invalid @enderror" type="checkbox" id="modal-agree-privacy" name="agree_privacy" value="1" {{ old('agree_privacy') ? 'checked' : '' }} required>
                                <label class="form-check-label" for="modal-agree-privacy">
                                    By creating a Univa account, you agree to our <a href="{{ route('privacy') }}" target="_blank">Privacy &amp; Disclaimers</a>
                                </label>
                                @error('agree_privacy')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>

                        <div class="mb-4">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="modal-newsletter" name="newsletter" value="1" {{ old('newsletter') ? 'checked' : '' }}>
                                <label class="form-check-label" for="modal-newsletter">Sign up for our newsletter to receive updates and tips</label>
                            </div>
                        </div>

                        <div class="d-grid mb-3">
                            <button type="submit" class="btn btn-primary btn-lg">Create Account</button>
                        </div>

                        <p class="text-center text-muted mb-0">
                            Already have an account?
                            <a href="#" class="text-decoration-none modal-switch-to-signin">Sign In</a>
                        </p>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>

    <!-- reCAPTCHA -->
    <script src="https://www.google.com/recaptcha/api.js?render={{ config('services.recaptcha.site_key') }}"></script>

    <script>
        // Copyright year
        document.getElementById('copyright-year').textContent = new Date().getFullYear();

        // Turbo body class handling
        document.addEventListener('turbo:before-render', (event) => {
            document.body.className = event.detail.newBody.className;
        });

        // --- Modal switching ---
        document.querySelectorAll('.modal-switch-to-signup').forEach(el => {
            el.addEventListener('click', function(e) {
                e.preventDefault();
                bootstrap.Modal.getInstance(document.getElementById('signinModal'))?.hide();
                new bootstrap.Modal(document.getElementById('signupModal')).show();
            });
        });
        document.querySelectorAll('.modal-switch-to-signin').forEach(el => {
            el.addEventListener('click', function(e) {
                e.preventDefault();
                bootstrap.Modal.getInstance(document.getElementById('signupModal'))?.hide();
                new bootstrap.Modal(document.getElementById('signinModal')).show();
            });
        });

        // --- Auto-open modal on validation errors or contact success ---
        @if(session('contact_success') && !request()->is('contact'))
            document.addEventListener('DOMContentLoaded', function() {
                new bootstrap.Modal(document.getElementById('contactModal')).show();
            });
        @elseif($errors->any() && !request()->is('signin') && !request()->is('get-started') && !request()->is('contact'))
            document.addEventListener('DOMContentLoaded', function() {
                @if(old('form_type') === 'signup')
                    new bootstrap.Modal(document.getElementById('signupModal')).show();
                @elseif(old('form_type') === 'contact')
                    new bootstrap.Modal(document.getElementById('contactModal')).show();
                @else
                    new bootstrap.Modal(document.getElementById('signinModal')).show();
                @endif
            });
        @endif

        // --- Contact modal: reCAPTCHA on submit ---
        document.getElementById('modal-contact-form').addEventListener('submit', function(e) {
            e.preventDefault();
            const form = this;
            grecaptcha.ready(function() {
                grecaptcha.execute('{{ config('services.recaptcha.site_key') }}', {action: 'contact'}).then(function(token) {
                    document.getElementById('modal-contact-recaptcha').value = token;
                    form.submit();
                });
            });
        });

        // --- Sign In modal: reCAPTCHA on submit ---
        document.getElementById('modal-signin-form').addEventListener('submit', function(e) {
            e.preventDefault();
            const form = this;
            grecaptcha.ready(function() {
                grecaptcha.execute('{{ config('services.recaptcha.site_key') }}', {action: 'signin'}).then(function(token) {
                    document.getElementById('modal-signin-recaptcha').value = token;
                    form.submit();
                });
            });
        });

        // --- Sign Up modal: reCAPTCHA on submit ---
        document.getElementById('modal-signup-form').addEventListener('submit', function(e) {
            e.preventDefault();
            const form = this;
            if (typeof grecaptcha === 'undefined') {
                alert('reCAPTCHA failed to load. Please disable any ad blockers and try again.');
                return;
            }
            grecaptcha.ready(function() {
                grecaptcha.execute('{{ config('services.recaptcha.site_key') }}', {action: 'signup'}).then(function(token) {
                    document.getElementById('modal-signup-recaptcha').value = token;
                    form.submit();
                }).catch(function() {
                    alert('reCAPTCHA verification failed. Please refresh the page and try again.');
                });
            });
        });

        // --- Sign Up modal: Password validation ---
        (function() {
            const password = document.getElementById('modal-password');
            const passwordConfirm = document.getElementById('modal-password-confirm');
            const requirements = {
                length:    { element: document.getElementById('modal-req-length'),    regex: /.{8,}/ },
                uppercase: { element: document.getElementById('modal-req-uppercase'), regex: /[A-Z]/ },
                lowercase: { element: document.getElementById('modal-req-lowercase'), regex: /[a-z]/ },
                number:    { element: document.getElementById('modal-req-number'),    regex: /[0-9]/ },
                special:   { element: document.getElementById('modal-req-special'),   regex: /[!@#$%^&*(),.?":{}|<>\-_=+\[\]\\\/`~;']/ }
            };

            function setReqState(el, state) {
                const icon = el.querySelector('i');
                el.classList.remove('valid', 'invalid');
                icon.classList.remove('bi-check-circle-fill', 'bi-x-circle-fill', 'bi-question-circle');
                if (state === 'valid')   { el.classList.add('valid');   icon.classList.add('bi-check-circle-fill'); }
                else if (state === 'invalid') { el.classList.add('invalid'); icon.classList.add('bi-x-circle-fill'); }
                else { icon.classList.add('bi-question-circle'); }
            }

            function validateMatch() {
                const matchEl = document.getElementById('modal-req-match');
                if (passwordConfirm.value && password.value === passwordConfirm.value) setReqState(matchEl, 'valid');
                else if (passwordConfirm.value) setReqState(matchEl, 'invalid');
                else setReqState(matchEl, 'neutral');
            }

            password.addEventListener('input', function() {
                for (const [, req] of Object.entries(requirements)) {
                    setReqState(req.element, req.regex.test(this.value) ? 'valid' : 'invalid');
                }
                validateMatch();
            });
            passwordConfirm.addEventListener('input', validateMatch);
        })();

        // --- Sign Up modal: Zip code autocomplete ---
        (function() {
            const zipInput       = document.getElementById('modal-zip');
            const zipSuggestions = document.getElementById('modal-zip-suggestions');
            const cityInput      = document.getElementById('modal-city');
            const stateInput     = document.getElementById('modal-state');
            const cityStateRow   = document.getElementById('modal-city-state-row');
            let zipTimer;

            zipInput.addEventListener('input', function() {
                const zip = this.value.replace(/\D/g, '').slice(0, 5);
                this.value = zip;
                clearTimeout(zipTimer);
                zipSuggestions.style.display = 'none';
                zipSuggestions.innerHTML = '';
                if (zip.length === 5) {
                    zipTimer = setTimeout(() => lookupZip(zip), 350);
                } else {
                    cityInput.value = '';
                    stateInput.value = '';
                    cityStateRow.style.display = 'none';
                }
            });

            async function lookupZip(zip) {
                try {
                    const res = await fetch(`https://api.zippopotam.us/us/${zip}`);
                    if (!res.ok) { showZipMsg('No location found for this zip code.'); return; }
                    const data = await res.json();
                    zipSuggestions.innerHTML = '';
                    data.places.forEach(place => {
                        const btn = document.createElement('button');
                        btn.type = 'button';
                        btn.className = 'list-group-item list-group-item-action';
                        btn.innerHTML = `<i class="bi bi-geo-alt me-2 text-muted"></i>${place['place name']}, ${place['state abbreviation']} <span class="text-muted small ms-1">${zip}</span>`;
                        btn.addEventListener('click', () => {
                            cityInput.value  = place['place name'];
                            stateInput.value = place['state abbreviation'];
                            cityStateRow.style.display = '';
                            zipSuggestions.style.display = 'none';
                        });
                        zipSuggestions.appendChild(btn);
                    });
                    zipSuggestions.style.display = 'block';
                } catch (e) {
                    showZipMsg('Could not look up zip code. Please try again.');
                }
            }

            function showZipMsg(msg) {
                zipSuggestions.innerHTML = `<div class="list-group-item text-muted small py-2">${msg}</div>`;
                zipSuggestions.style.display = 'block';
            }

            document.addEventListener('click', function(e) {
                if (!zipInput.contains(e.target) && !zipSuggestions.contains(e.target)) {
                    zipSuggestions.style.display = 'none';
                }
            });
        })();
    </script>

    <!-- Custom JS -->
    <script src="{{ asset('js/app.js') }}"></script>

    @stack('scripts')

</body>
</html>
