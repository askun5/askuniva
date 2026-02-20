<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Univa - Your AI-powered virtual college counselor">
    <meta name="author" content="Univa">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Univa') - AI College Counselor</title>

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

    <!-- reCAPTCHA badge positioning -->
    <style>
        .grecaptcha-badge {
            bottom: 70px !important;
        }
    </style>
</head>
<body class="@yield('body-class')">
    @yield('content')

    <!-- Footer Navigation (Big Picture Style) -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-bottom" id="main-footer" data-turbo-permanent>
        <div class="container px-4 px-lg-5">
            <!-- Logo/Brand -->
            @php
                $logo = \App\Models\SiteSetting::get('site_logo');
            @endphp
            <a class="navbar-brand" href="{{ route('home') }}">
                @if($logo)
                    <img src="{{ Storage::url($logo) }}" alt="Univa" height="24">
                @else
                    <img src="{{ asset('images/univa-logo.png') }}" alt="Univa" height="24">
                @endif
            </a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarResponsive">
                <!-- Footer Links (Left) -->
                <ul class="navbar-nav me-auto">
                    @php
                        $footerLinks = json_decode(\App\Models\SiteSetting::get('footer_links', '[]'), true) ?? [
                            ['label' => 'Home', 'url' => '/'],
                            ['label' => 'About', 'url' => '/about'],
                            ['label' => 'Privacy', 'url' => '/privacy'],
                            ['label' => 'Terms', 'url' => '/terms'],
                            ['label' => 'Contact', 'url' => '/contact'],
                        ];
                    @endphp
                    @foreach($footerLinks as $link)
                        <li class="nav-item">
                            <a class="nav-link" href="{{ $link['url'] }}">{{ $link['label'] }}</a>
                        </li>
                    @endforeach
                </ul>

                <!-- Copyright (Right) -->
                <span class="navbar-text text-light">
                    @php
                        $copyrightText = \App\Models\SiteSetting::get('copyright_text', 'Univa. All rights reserved.');
                    @endphp
                    &copy; <span id="copyright-year"></span> {{ $copyrightText }}
                </span>
            </div>
        </div>
    </nav>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Auto-update copyright year -->
    <script>
        // Set copyright year on initial load
        document.getElementById('copyright-year').textContent = new Date().getFullYear();

        // Handle Turbo page transitions for body class
        document.addEventListener('turbo:before-render', (event) => {
            // Get the new body class from the incoming page
            const newBodyClass = event.detail.newBody.className;
            document.body.className = newBodyClass;
        });
    </script>

    <!-- Custom JS -->
    <script src="{{ asset('js/app.js') }}"></script>

    @stack('scripts')
</body>
</html>
