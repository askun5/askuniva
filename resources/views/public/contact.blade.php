@extends(auth()->check() ? 'layouts.portal' : 'layouts.public')

@section('title', 'Contact Us')

@section('body-class', 'contact-page')

@push('styles')
<style>
    body.contact-page {
        background-color: #f8f9fa;
        padding-bottom: 100px; /* Account for fixed footer */
    }

    .contact-section {
        min-height: calc(100vh - 70px);
        display: flex;
        align-items: center;
        padding: 3rem 0;
    }

    @if(auth()->check())
    body.contact-page {
        padding-bottom: 0;
    }

    .contact-section {
        min-height: auto;
    }
    @endif

    .contact-card {
        max-width: 600px;
        margin: 0 auto;
    }
</style>
@endpush

@section('content')
<section class="contact-section">
    <div class="container px-4 px-lg-5">
        <div class="contact-card">
            <div class="card shadow">
                <div class="card-body p-5">
                    <h2 class="card-title text-center mb-4">Contact Us</h2>
                    <p class="text-muted text-center mb-4">Have questions? We'd love to hear from you.</p>

                    @if(session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('contact.submit') }}" id="contact-form">
                        @csrf
                        <input type="hidden" name="recaptcha_token" id="recaptcha_token">
                        @error('recaptcha_token')
                            <div class="alert alert-danger mb-3">{{ $message }}</div>
                        @enderror

                        <div class="mb-3">
                            <label for="email" class="form-label">Email Address</label>
                            <input type="email"
                                   class="form-control @error('email') is-invalid @enderror"
                                   id="email"
                                   name="email"
                                   value="{{ old('email') }}"
                                   required>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="comments" class="form-label">Your Message</label>
                            <textarea class="form-control @error('comments') is-invalid @enderror"
                                      id="comments"
                                      name="comments"
                                      rows="5"
                                      required>{{ old('comments') }}</textarea>
                            @error('comments')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="bi bi-send me-2"></i>Send Message
                            </button>
                        </div>
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
    document.getElementById('contact-form').addEventListener('submit', function(e) {
        e.preventDefault();
        grecaptcha.ready(function() {
            grecaptcha.execute('{{ config('services.recaptcha.site_key') }}', {action: 'contact'}).then(function(token) {
                document.getElementById('recaptcha_token').value = token;
                document.getElementById('contact-form').submit();
            });
        });
    });
</script>
@endpush
