<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Univa Admin Panel">
    <meta name="author" content="Univa">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Admin') - Univa Admin</title>

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
    <link href="{{ asset('css/admin.css') }}" rel="stylesheet">

    @stack('styles')
</head>
<body>
    <div class="d-flex" id="wrapper">
        <!-- Sidebar -->
        <div class="border-end bg-dark" id="sidebar-wrapper">
            <div class="sidebar-heading border-bottom py-3 px-3 bg-dark">
                @php
                    $logo = \App\Models\SiteSetting::get('site_logo');
                @endphp
                <a href="{{ route('admin.dashboard') }}" class="text-decoration-none">
                    @if($logo)
                        <img src="{{ Storage::url($logo) }}" alt="Univa Admin" height="40">
                    @else
                        <span class="fs-4 fw-bold text-light">Univa Admin</span>
                    @endif
                </a>
            </div>
            <div class="list-group list-group-flush">
                <a class="list-group-item list-group-item-action bg-dark text-light border-0 p-3 {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}">
                    <i class="bi bi-speedometer2 me-2"></i> Dashboard
                </a>
                <a class="list-group-item list-group-item-action bg-dark text-light border-0 p-3 {{ request()->routeIs('admin.branding') ? 'active' : '' }}" href="{{ route('admin.branding') }}">
                    <i class="bi bi-palette me-2"></i> Branding
                </a>
                <a class="list-group-item list-group-item-action bg-dark text-light border-0 p-3 {{ request()->routeIs('admin.content.homepage') ? 'active' : '' }}" href="{{ route('admin.content.homepage') }}">
                    <i class="bi bi-house me-2"></i> Homepage
                </a>
                <a class="list-group-item list-group-item-action bg-dark text-light border-0 p-3 {{ request()->routeIs('admin.content.pages*') ? 'active' : '' }}" href="{{ route('admin.content.pages') }}">
                    <i class="bi bi-file-earmark-text me-2"></i> Pages
                </a>
                <a class="list-group-item list-group-item-action bg-dark text-light border-0 p-3 {{ request()->routeIs('admin.content.footer') ? 'active' : '' }}" href="{{ route('admin.content.footer') }}">
                    <i class="bi bi-layout-text-window-reverse me-2"></i> Footer
                </a>
                <a class="list-group-item list-group-item-action bg-dark text-light border-0 p-3 {{ request()->routeIs('admin.guidelines*') ? 'active' : '' }}" href="{{ route('admin.guidelines') }}">
                    <i class="bi bi-book me-2"></i> Guidelines
                </a>
                <a class="list-group-item list-group-item-action bg-dark text-light border-0 p-3 {{ request()->routeIs('admin.contacts*') ? 'active' : '' }}" href="{{ route('admin.contacts') }}">
                    <i class="bi bi-envelope me-2"></i> Contact Messages
                    @php
                        $unreadCount = \App\Models\ContactSubmission::unread()->count();
                    @endphp
                    @if($unreadCount > 0)
                        <span class="badge bg-danger ms-2">{{ $unreadCount }}</span>
                    @endif
                </a>
                <hr class="text-light my-2">
                <a class="list-group-item list-group-item-action bg-dark text-light border-0 p-3" href="{{ route('home') }}" target="_blank">
                    <i class="bi bi-box-arrow-up-right me-2"></i> View Site
                </a>
                <form action="{{ route('portal.signout') }}" method="POST" class="m-0">
                    @csrf
                    <button type="submit" class="list-group-item list-group-item-action bg-dark text-danger border-0 p-3 w-100 text-start">
                        <i class="bi bi-box-arrow-right me-2"></i> Sign Out
                    </button>
                </form>
            </div>
        </div>

        <!-- Page content wrapper -->
        <div id="page-content-wrapper" class="bg-light">
            <!-- Top navigation -->
            <nav class="navbar navbar-expand-lg navbar-light bg-white border-bottom shadow-sm">
                <div class="container-fluid">
                    <button class="btn btn-sm btn-outline-secondary d-lg-none me-2" id="sidebarToggle">
                        <i class="bi bi-list"></i>
                    </button>

                    <span class="navbar-text ms-auto">
                        Logged in as <strong>{{ auth()->user()->full_name }}</strong>
                    </span>
                </div>
            </nav>

            <!-- Main Content -->
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
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Sidebar Toggle Script -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const sidebarToggle = document.getElementById('sidebarToggle');
            if (sidebarToggle) {
                sidebarToggle.addEventListener('click', function(e) {
                    e.preventDefault();
                    document.getElementById('wrapper').classList.toggle('toggled');
                });
            }
        });
    </script>

    @stack('scripts')
</body>
</html>
