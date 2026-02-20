@extends('layouts.portal')

@section('title', 'AI Advisor')

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
            <div class="card-body p-0">
                <!-- Chatfuel AI Agent Integration -->
                <div id="chatfuel-container" class="chatfuel-wrapper">
                    @if($chatfuelBotId)
                        <!-- Chatfuel Widget will be embedded here -->
                        <div id="chatfuel-widget" style="min-height: 600px; width: 100%;">
                            <!-- The Chatfuel script will inject the chat interface here -->
                            <div class="text-center py-5">
                                <div class="spinner-border text-success mb-3" role="status">
                                    <span class="visually-hidden">Loading...</span>
                                </div>
                                <p class="text-muted">Loading AI Advisor...</p>
                            </div>
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="bi bi-chat-dots display-1 text-muted"></i>
                            <h5 class="mt-3 text-muted">AI Advisor Coming Soon</h5>
                            <p class="text-muted">The AI Advisor is being configured. Please check back later.</p>
                        </div>
                    @endif
                </div>
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

@push('styles')
<style>
    .chatfuel-wrapper {
        min-height: 600px;
        border: 1px solid #e9ecef;
        border-radius: 0 0 0.375rem 0.375rem;
    }

    .chatfuel-wrapper iframe {
        width: 100%;
        height: 600px;
        border: none;
    }
</style>
@endpush

@push('scripts')
@if($chatfuelBotId)
<script>
    // Chatfuel Integration
    // Pass user context to Chatfuel for grade-aware responses
    window.chatfuelUserAttributes = {
        user_id: '{{ $user->id }}',
        first_name: '{{ $user->first_name }}',
        last_name: '{{ $user->last_name }}',
        email: '{{ $user->email }}',
        grade: '{{ $user->grade }}',
        grade_display: '{{ $user->grade_display }}'
    };

    // Chatfuel widget initialization
    // Replace this with your actual Chatfuel embed code
    (function() {
        var chatfuelBotId = '{{ $chatfuelBotId }}';

        // Example Chatfuel web widget embed
        // You'll need to replace this with the actual embed code from Chatfuel
        var script = document.createElement('script');
        script.src = 'https://widget.chatfuel.com/widget.js';
        script.setAttribute('data-bot-id', chatfuelBotId);
        script.setAttribute('data-container', 'chatfuel-widget');

        // Pass user attributes for context
        script.setAttribute('data-user-id', window.chatfuelUserAttributes.user_id);
        script.setAttribute('data-user-first-name', window.chatfuelUserAttributes.first_name);
        script.setAttribute('data-user-grade', window.chatfuelUserAttributes.grade);

        document.getElementById('chatfuel-widget').innerHTML = '';
        document.getElementById('chatfuel-widget').appendChild(script);
    })();
</script>
@endif
@endpush
