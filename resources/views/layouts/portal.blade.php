<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Univa Student Portal">
    <meta name="author" content="Univa">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Portal') - Univa</title>

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

    <!-- SB Admin CSS -->
    <link href="{{ asset('css/sb-admin.css') }}" rel="stylesheet">

    <!-- Font Awesome -->
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>

    <!-- Turbo for SPA-like navigation -->
    <script type="module">
        import hotwiredTurbo from 'https://cdn.jsdelivr.net/npm/@hotwired/turbo@8.0.12/+esm';
    </script>

    @stack('styles')
</head>
<body class="sb-nav-fixed">

    <!-- Top Navigation -->
    <nav id="topnav" class="sb-topnav navbar navbar-expand navbar-dark bg-dark" data-turbo-permanent>
        <!-- Brand -->
        @php $logo = \App\Models\SiteSetting::get('site_logo'); @endphp
        <a class="navbar-brand ps-3" href="{{ route('portal.dashboard') }}">
            @if($logo)
                <img src="{{ Storage::url($logo) }}" alt="Univa" height="30">
            @else
                Univa
            @endif
        </a>

        <!-- Sidebar Toggle -->
        <button class="btn btn-link btn-sm order-1 order-lg-0 me-4 me-lg-0" id="sidebarToggle" href="#!">
            <i class="fas fa-bars"></i>
        </button>

        <!-- Right Nav -->
        <ul class="navbar-nav ms-auto me-3 me-lg-4">
            <!-- Grade Badge -->
            <li class="nav-item d-none d-md-flex align-items-center me-3">
                <span class="badge bg-primary">{{ auth()->user()->grade_display }}</span>
            </li>
            <!-- User Dropdown -->
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" id="navbarDropdown" href="#" role="button"
                   data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="fas fa-user fa-fw"></i>
                    <span class="d-none d-md-inline ms-1">{{ auth()->user()->full_name }}</span>
                </a>
                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                    <li><a class="dropdown-item" href="{{ route('portal.profile') }}">
                        <i class="fas fa-user-circle me-2"></i>Profile
                    </a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li>
                        <form action="{{ route('portal.signout') }}" method="POST" class="m-0">
                            @csrf
                            <button type="submit" class="dropdown-item text-danger">
                                <i class="fas fa-sign-out-alt me-2"></i>Sign Out
                            </button>
                        </form>
                    </li>
                </ul>
            </li>
        </ul>
    </nav>

    <div id="layoutSidenav">
        <!-- Sidebar -->
        <div id="layoutSidenav_nav" data-turbo-permanent>
            <nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
                <div class="sb-sidenav-menu">
                    <div class="nav">
                        <div class="sb-sidenav-menu-heading">Main</div>

                        <a class="nav-link {{ request()->routeIs('portal.dashboard') ? 'active' : '' }}"
                           href="{{ route('portal.dashboard') }}">
                            <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                            Dashboard
                        </a>

                        @php
                            $guidelineLevels = [
                                'grade_9_10'        => 'HS Grades 9 & 10',
                                'grade_11'          => 'HS Grade 11',
                                'grade_12'          => 'HS Grade 12',
                                'community_college' => 'Community College',
                                'undergraduate'     => 'Undergraduate',
                                'graduate'          => 'Graduate',
                            ];
                            $guidelinesOpen = request()->routeIs('portal.guidelines*');
                        @endphp
                        <a class="nav-link {{ $guidelinesOpen ? '' : 'collapsed' }}"
                           href="#" data-bs-toggle="collapse" data-bs-target="#collapseGuidelines"
                           aria-expanded="{{ $guidelinesOpen ? 'true' : 'false' }}" aria-controls="collapseGuidelines">
                            <div class="sb-nav-link-icon"><i class="fas fa-book"></i></div>
                            Guidelines
                            <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                        </a>
                        <div class="collapse {{ $guidelinesOpen ? 'show' : '' }}" id="collapseGuidelines"
                             data-bs-parent="#sidenavAccordion">
                            <nav class="sb-sidenav-menu-nested nav">
                                @foreach($guidelineLevels as $key => $label)
                                    <a class="nav-link {{ request()->route('grade') === $key ? 'active' : '' }}"
                                       href="{{ route('portal.guidelines.show', $key) }}">
                                        {{ $label }}
                                    </a>
                                @endforeach
                            </nav>
                        </div>

                        <a class="nav-link {{ request()->routeIs('portal.advisor') ? 'active' : '' }}"
                           href="{{ route('portal.advisor') }}">
                            <div class="sb-nav-link-icon"><i class="fas fa-robot"></i></div>
                            AI Advisor
                        </a>

                        <div class="sb-sidenav-menu-heading">Account</div>

                        <a class="nav-link {{ request()->routeIs('portal.profile') ? 'active' : '' }}"
                           href="{{ route('portal.profile') }}">
                            <div class="sb-nav-link-icon"><i class="fas fa-user"></i></div>
                            Profile
                        </a>

                        <a class="nav-link {{ request()->routeIs('contact') ? 'active' : '' }}"
                           href="{{ route('contact') }}">
                            <div class="sb-nav-link-icon"><i class="fas fa-envelope"></i></div>
                            Contact
                        </a>
                    </div>
                </div>
                <div class="sb-sidenav-footer">
                    <div class="small">Logged in as:</div>
                    {{ auth()->user()->full_name }}
                </div>
            </nav>
        </div>

        <!-- Page Content -->
        <div id="layoutSidenav_content">
            <main>
                <div class="container-fluid px-4 pt-4 pb-4">
                    <!-- Flash Messages -->
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show mt-4" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show mt-4" role="alert">
                            {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    @yield('content')
                </div>
            </main>

            <!-- Footer -->
            <footer class="py-4 bg-light mt-auto">
                <div class="container-fluid px-4">
                    <div class="d-flex align-items-center justify-content-between small">
                        <div class="text-muted">
                            &copy; <span id="copyright-year"></span>
                            @php
                                $copyrightText = \App\Models\SiteSetting::get('copyright_text', 'Univa. All rights reserved.');
                            @endphp
                            {{ $copyrightText }}
                        </div>
                        <div>
                            @php
                                $footerLinks = json_decode(\App\Models\SiteSetting::get('footer_links', '[]'), true) ?? [
                                    ['label' => 'Home', 'url' => '/'],
                                    ['label' => 'Privacy', 'url' => '/privacy'],
                                    ['label' => 'Contact', 'url' => '/contact'],
                                ];
                            @endphp
                            @foreach($footerLinks as $link)
                                <a href="{{ $link['url'] }}" class="me-3">{{ $link['label'] }}</a>
                            @endforeach
                        </div>
                    </div>
                </div>
            </footer>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous" data-turbo-eval="false"></script>

    <!-- SB Admin Scripts -->
    <script src="{{ asset('js/sb-scripts.js') }}" data-turbo-eval="false"></script>

    <!-- Copyright year -->
    <script>
        document.getElementById('copyright-year').textContent = new Date().getFullYear();
    </script>

    <!-- Update sidebar active link and Guidelines collapse on Turbo navigation -->
    <script data-turbo-eval="false">
        function updateSidebarActive() {
            const current = window.location.pathname;

            // Update active links
            document.querySelectorAll('#layoutSidenav_nav a.nav-link[href]').forEach(link => {
                const path = new URL(link.href, window.location.origin).pathname;
                link.classList.toggle('active', current === path);
            });

            // Open/close Guidelines collapse based on current page
            const onGuidelines = current.includes('/guidelines');
            const collapseEl   = document.getElementById('collapseGuidelines');
            const toggleLink   = document.querySelector('[data-bs-target="#collapseGuidelines"]');

            if (collapseEl) {
                const bsCollapse = bootstrap.Collapse.getOrCreateInstance(collapseEl, { toggle: false });
                if (onGuidelines) {
                    bsCollapse.show();
                    if (toggleLink) toggleLink.classList.remove('collapsed');
                } else {
                    bsCollapse.hide();
                    if (toggleLink) toggleLink.classList.add('collapsed');
                }
            }
        }
        document.addEventListener('turbo:load', updateSidebarActive);
    </script>

    @stack('scripts')
</body>
</html>
