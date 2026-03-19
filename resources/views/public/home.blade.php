@extends('layouts.public')

@section('title', 'Welcome')

@section('body-class', 'home-page')

@push('styles')
<style>
    /* Big Picture Style Hero */
    body.home-page {
        background: linear-gradient(rgba(0, 0, 0, 0.3), rgba(0, 0, 0, 0.3)),
                    url('{{ $heroImage ? Storage::url($heroImage) : asset("images/hero-default.jpg") }}');
        background-size: cover;
        background-position: center;
        background-attachment: fixed;
        background-color: #1a3d8c;
        min-height: 100vh;
    }

    .hero-section {
        min-height: calc(100vh - 56px); /* Account for top nav */
        display: flex;
        align-items: center;
    }

    .hero-content {
        color: white;
        max-width: 920px;
    }

    .hero-content h1 {
        font-size: 3.5rem;
        font-weight: bold;
        margin-bottom: 1.5rem;
        text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.5);
    }

    .hero-content p {
        font-size: 1.25rem;
        margin-bottom: 2rem;
        text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.5);
    }

    .cta-buttons .btn {
        padding: 0.75rem 2rem;
        font-size: 1.1rem;
        margin-right: 1rem;
        margin-bottom: 0.5rem;
    }

    .btn-get-started {
        background-color: #0d6efd;
        border-color: #0d6efd;
        color: white;
    }

    .btn-get-started:hover {
        background-color: #0b5ed7;
        border-color: #0a58ca;
        color: white;
    }

    .btn-signin {
        background-color: transparent;
        border: 2px solid white;
        color: white;
    }

    .btn-signin:hover {
        background-color: white;
        color: #212529;
    }

    @media (max-width: 768px) {
        .hero-content h1 {
            font-size: 2.5rem;
        }

        .hero-content p {
            font-size: 1rem;
        }

        .cta-buttons .btn {
            display: block;
            width: 100%;
            margin-right: 0;
        }
    }
</style>
@endpush

@section('content')
<section class="hero-section">
    <div class="container-fluid px-3">
        <div class="row">
            <div class="col-lg-8">
                <div class="hero-content">
                    <h1>{{ $heroTitle }}</h1>
                    <p>{{ $heroSubtext }}</p>
                    <div class="cta-buttons">
                        <a href="#" class="btn btn-get-started btn-lg" data-bs-toggle="modal" data-bs-target="#signupModal">
                            GET STARTED
                        </a>
                        <a href="#" class="btn btn-signin btn-lg" data-bs-toggle="modal" data-bs-target="#signinModal">
                            SIGN IN
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
