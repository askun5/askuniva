<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>@yield('code', 'Error') - Univa</title>
    <link href="{{ asset('css/sb-admin.css') }}" rel="stylesheet">
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
</head>
<body>
    <div id="layoutError">
        <div id="layoutError_content">
            <main>
                <div class="container">
                    <div class="row justify-content-center">
                        <div class="col-lg-6">
                            <div class="text-center mt-4">
                                <h1 class="display-1">@yield('code')</h1>
                                <p class="lead">@yield('title')</p>
                                <p>@yield('message')</p>
                                <a href="{{ url('/') }}">
                                    <i class="fas fa-arrow-left me-1"></i>
                                    Return to Home
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
        <div id="layoutError_footer">
            <footer class="py-4 bg-light mt-auto">
                <div class="container-fluid px-4">
                    <div class="d-flex align-items-center justify-content-between small">
                        <div class="text-muted">
                            &copy; {{ date('Y') }}
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
                            @foreach($footerLinks as $i => $link)
                                @if($i > 0) &middot; @endif
                                <a href="{{ $link['url'] }}">{{ $link['label'] }}</a>
                            @endforeach
                        </div>
                    </div>
                </div>
            </footer>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
</body>
</html>
