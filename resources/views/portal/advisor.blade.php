@extends('layouts.portal')

@section('title', 'AI Advisor')

@push('styles')
<style>
    #chatfuel-card-body {
        min-height: 480px;
        display: flex;
        align-items: center;
        justify-content: center;
    }
</style>
@endpush

@section('content')
<div class="row">
    <div class="col-12">
        <nav aria-label="breadcrumb" class="mb-4">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('portal.dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item active" aria-current="page">AI Advisor</li>
            </ol>
        </nav>

        <div class="card">
            <div class="card-header bg-success text-white">
                <h4 class="mb-0">
                    <i class="bi bi-robot me-2"></i>AI College Advisor
                </h4>
            </div>

            <div class="card-body" id="chatfuel-card-body">
                @if($chatfuelBotId)
                    <div class="text-center" id="cf-launcher">
                        <i class="bi bi-robot display-1 text-success mb-3 d-block"></i>
                        <h5 class="mb-2">Your AI College Advisor is Ready</h5>
                        <p class="text-muted mb-4">
                            Get personalized college advice, essay tips, and<br>application guidance — just ask.
                        </p>
                        <button id="cf-start-btn" class="btn btn-success btn-lg px-5" disabled>
                            <span class="spinner-border spinner-border-sm me-2" id="cf-spinner" role="status"></span>
                            <span id="cf-btn-label">Loading…</span>
                        </button>
                    </div>
                @else
                    <div class="text-center">
                        <i class="bi bi-chat-dots display-1 text-muted"></i>
                        <h5 class="mt-3 text-muted">AI Advisor Coming Soon</h5>
                        <p class="text-muted">The AI Advisor is being configured. Please check back later.</p>
                    </div>
                @endif
            </div>
        </div>

        <div class="card mt-4 border-warning">
            <div class="card-body">
                <h6 class="text-warning mb-1"><i class="bi bi-exclamation-triangle-fill me-2"></i>Disclaimer</h6>
                <p class="text-muted small mb-0">
                    This advisor is intended for use with universities located within the United States only.
                    All information provided is for general guidance purposes and may not reflect the most current
                    institutional policies, requirements, or deadlines. University data is subject to change;
                    please verify all details directly with the respective institution before making any decisions.
                </p>
            </div>
        </div>

        <div class="card mt-4">
            <div class="card-body">
                <h5><i class="bi bi-lightbulb me-2 text-warning"></i>Tips for Using the AI Advisor</h5>
                <ul class="mb-0">
                    <li>Be specific with your questions for better answers</li>
                    <li>Ask about college requirements, test prep, extracurriculars, and application tips</li>
                    <li>The advisor knows you're in <strong>{{ $user->grade_display }}</strong> and will tailor advice accordingly</li>
                    <li>You can ask follow-up questions to get more detailed information</li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
@if($chatfuelBotId)
<script>
(function () {
    var startBtn  = document.getElementById('cf-start-btn');
    var spinner   = document.getElementById('cf-spinner');
    var btnLabel  = document.getElementById('cf-btn-label');
    var cfElement = null;

    // Load Chatfuel widget with full z-index so it renders properly
    var s = document.createElement('script');
    s.dataset.bot            = '{{ $chatfuelBotId }}';
    s.dataset.zindex         = '999999';
    s.dataset.userAttributes = JSON.stringify({
        academic_level: '{{ addslashes($user->grade_display) }}',
        zip_code:       '{{ addslashes($user->zip_code) }}'
    });
    s.src            = 'https://panel.chatfuel.com/widgets/chat-widget/chat-widget.js';
    s.async          = true;
    s.defer          = true;
    document.head.appendChild(s);

    // Poll for Chatfuel's widget root (any <div> added directly to <body>
    // that is NOT our portal #wrapper)
    var attempts = 0;
    var poll = setInterval(function () {
        attempts++;

        document.querySelectorAll('body > div').forEach(function (div) {
            if (div.id !== 'wrapper' && !cfElement) {
                cfElement = div;
            }
        });

        if (cfElement) {
            clearInterval(poll);
            enableButton();
            return;
        }

        // Give up after 15 seconds
        if (attempts > 30) {
            clearInterval(poll);
            enableButton();
        }
    }, 500);

    function enableButton() {
        spinner.style.display = 'none';
        startBtn.disabled     = false;
        btnLabel.textContent  = 'Start AI Chat';
        startBtn.innerHTML    = '<i class="bi bi-chat-dots-fill me-2"></i>Start AI Chat';

        startBtn.addEventListener('click', function () {
            // Find the toggle button inside Chatfuel's widget and click it
            var btn = cfElement
                ? cfElement.querySelector('button')
                : document.querySelector('body > div:not(#wrapper) button');

            if (btn) {
                btn.click();
            }
        });
    }
})();
</script>
@endif
@endpush
