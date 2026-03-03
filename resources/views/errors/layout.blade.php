<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Error') - Univa</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #f8f9fa; }
        .error-wrapper {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .error-code {
            font-size: 6rem;
            font-weight: 700;
            color: #dee2e6;
            line-height: 1;
        }
    </style>
</head>
<body>
    <div class="error-wrapper">
        <div class="text-center px-4">
            <div class="error-code">@yield('code')</div>
            <h1 class="h3 fw-semibold mt-2 mb-3">@yield('title')</h1>
            <p class="text-muted mb-4">@yield('message')</p>
            <a href="{{ url('/') }}" class="btn btn-dark px-4">Go Home</a>
        </div>
    </div>
</body>
</html>
