<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Univa Student Portal">
    <meta name="author" content="Univa">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Portal') - Univa</title>

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
    <link href="{{ asset('css/portal.css') }}" rel="stylesheet">

    <!-- Turbo for SPA-like navigation -->
    <script type="module">
        import hotwiredTurbo from 'https://cdn.jsdelivr.net/npm/@hotwired/turbo@8.0.12/+esm';
    </script>

    @stack('styles')
</head>
<body>
    <div class="d-flex" id="wrapper">
        <!-- Sidebar -->
        <div class="border-end bg-white" id="sidebar-wrapper">
            <div class="sidebar-heading border-bottom bg-dark py-3 px-3">
                @php
                    $logo = \App\Models\SiteSetting::get('site_logo');
                @endphp
                <a href="{{ route('portal.dashboard') }}" class="text-decoration-none">
                    @if($logo)
                        <img src="{{ Storage::url($logo) }}" alt="Univa" height="36">
                    @else
                        <span class="fs-4 fw-bold text-primary">Univa</span>
                    @endif
                </a>
            </div>
            <div class="list-group list-group-flush">
                <a class="list-group-item list-group-item-action list-group-item-light p-3 {{ request()->routeIs('portal.dashboard') ? 'active' : '' }}" href="{{ route('portal.dashboard') }}">
                    <i class="bi bi-speedometer2 me-2"></i> Dashboard
                </a>
                <a class="list-group-item list-group-item-action list-group-item-light p-3 {{ request()->routeIs('portal.guidelines') ? 'active' : '' }}" href="{{ route('portal.guidelines') }}">
                    <i class="bi bi-book me-2"></i> Guidelines
                </a>
                <a class="list-group-item list-group-item-action list-group-item-light p-3 {{ request()->routeIs('portal.advisor') ? 'active' : '' }}" href="{{ route('portal.advisor') }}">
                    <i class="bi bi-robot me-2"></i> AI Advisor
                </a>
                <a class="list-group-item list-group-item-action list-group-item-light p-3 {{ request()->routeIs('portal.profile') ? 'active' : '' }}" href="{{ route('portal.profile') }}">
                    <i class="bi bi-person me-2"></i> Profile
                </a>
                <a class="list-group-item list-group-item-action list-group-item-light p-3 {{ request()->routeIs('contact') ? 'active' : '' }}" href="{{ route('contact') }}">
                    <i class="bi bi-envelope me-2"></i> Contact
                </a>
                <form action="{{ route('portal.signout') }}" method="POST" class="m-0">
                    @csrf
                    <button type="submit" class="list-group-item list-group-item-action list-group-item-light p-3 text-danger border-0 w-100 text-start">
                        <i class="bi bi-box-arrow-right me-2"></i> Sign Out
                    </button>
                </form>
            </div>
        </div>

        <!-- Page content wrapper -->
        <div id="page-content-wrapper">
            <!-- Top navigation -->
            <nav class="navbar navbar-expand-lg navbar-dark bg-dark border-bottom">
                <div class="container-fluid">
                    <!-- Mobile sidebar toggle (hidden on desktop) -->
                    <button class="btn btn-sm btn-outline-secondary d-lg-none me-2" id="sidebarToggle">
                        <i class="bi bi-list"></i>
                    </button>

                    <div class="ms-auto d-flex align-items-center">
                        <!-- Grade Badge -->
                        <span class="badge bg-primary me-3">{{ auth()->user()->grade_display }}</span>

                        <!-- User Name -->
                        <span class="navbar-text">
                            Welcome, <strong>{{ auth()->user()->full_name }}</strong>
                        </span>
                    </div>
                </div>
            </nav>

            <!-- Main Content Area -->
            <div class="container-fluid p-4">
                <!-- Flash Messages -->
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @yield('content')
            </div>

            <!-- Footer -->
            <footer class="bg-dark text-light py-3 mt-auto">
                <div class="container-fluid px-4">
                    <div class="d-flex flex-wrap justify-content-between align-items-center">
                        <ul class="nav">
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
                                    <a class="nav-link text-light" href="{{ $link['url'] }}">{{ $link['label'] }}</a>
                                </li>
                            @endforeach
                        </ul>
                        <span class="text-light">
                            @php
                                $copyrightText = \App\Models\SiteSetting::get('copyright_text', 'Univa. All rights reserved.');
                            @endphp
                            &copy; <span id="copyright-year"></span> {{ $copyrightText }}
                        </span>
                    </div>
                </div>
            </footer>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Copyright year auto-update -->
    <script>
        document.getElementById('copyright-year').textContent = new Date().getFullYear();
    </script>

    <!-- Sidebar Toggle Script -->
    <script>
        function initSidebarToggle() {
            const sidebarToggle = document.getElementById('sidebarToggle');
            if (sidebarToggle) {
                sidebarToggle.addEventListener('click', function(e) {
                    e.preventDefault();
                    document.getElementById('wrapper').classList.toggle('toggled');
                });
            }
        }
        document.addEventListener('DOMContentLoaded', initSidebarToggle);
        document.addEventListener('turbo:load', initSidebarToggle);
    </script>

    @stack('scripts')
</body>
</html>
