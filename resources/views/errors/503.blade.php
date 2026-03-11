<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Under Maintenance - Univa</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
            background-color: #212529;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 2rem 1rem;
        }

        .site-logo {
            height: 28px;
            width: auto;
            margin-bottom: 2rem;
        }

        .maintenance-card {
            background: white;
            border-radius: 1.25rem;
            padding: 3rem 2.5rem;
            max-width: 600px;
            width: 100%;
            text-align: center;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.4);
        }

        .icon-circle {
            width: 72px;
            height: 72px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1.5rem;
        }

        .icon-circle i {
            font-size: 2rem;
            color: white;
        }

        h1 {
            font-size: 1.6rem;
            font-weight: 700;
            color: #1a1a2e;
            margin-bottom: 0.75rem;
        }

        p {
            color: #6c757d;
            line-height: 1.6;
            margin-bottom: 0;
        }

        .divider {
            border: none;
            border-top: 1px solid #f0f0f0;
            margin: 2rem 0;
        }

        .back-link {
            color: #667eea;
            text-decoration: none;
            font-size: 0.875rem;
            font-weight: 500;
        }

        .back-link:hover {
            color: #5a67d8;
        }
    </style>
</head>
<body>

    <!-- Logo above the card -->
    @php
        try {
            $logo = \App\Models\SiteSetting::get('site_logo');
            $logoUrl = $logo ? \Illuminate\Support\Facades\Storage::url($logo) : asset('images/univa-logo.png');
        } catch (\Exception $e) {
            $logoUrl = asset('images/univa-logo.png');
        }
    @endphp
    <img src="{{ $logoUrl }}" alt="Univa" class="site-logo">

    <div class="maintenance-card">

        <!-- Icon -->
        <div class="icon-circle">
            <i class="bi bi-tools"></i>
        </div>

        <!-- Message -->
        <h1>We'll be right back</h1>
        <p>
            We're performing scheduled maintenance to improve your experience.
            This won't take long — please check back shortly.
        </p>

        <hr class="divider">

        <p class="mb-0" style="font-size: 0.85rem; color: #adb5bd;">
            Thank you for your patience &mdash;
            <a href="mailto:support@askuniva.com" class="back-link">support@askuniva.com</a>
        </p>
    </div>

</body>
</html>
