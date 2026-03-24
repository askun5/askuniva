@extends('layouts.portal')

@section('title', 'AI Advisor')

@push('styles')
<style>
    /* ── Chat panel ────────────────────────────────────────────── */
    #chat-panel {
        display: flex;
        flex-direction: column;
        height: 560px;
    }

    /* ── Messages area ─────────────────────────────────────────── */
    #chat-messages {
        flex: 1;
        overflow-y: auto;
        padding: 1.25rem;
        background: #f8f9fa;
        scroll-behavior: smooth;
    }

    /* ── Bubble base ───────────────────────────────────────────── */
    .chat-bubble-wrap {
        display: flex;
        margin-bottom: 1rem;
        align-items: flex-end;
        gap: 0.5rem;
    }
    .chat-bubble-wrap.user  { flex-direction: row-reverse; }
    .chat-bubble-wrap.model { flex-direction: row; }

    .bubble-avatar {
        width: 32px;
        height: 32px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        font-size: 0.85rem;
    }
    .bubble-avatar.ai-avatar   { background: #198754; color: #fff; }
    .bubble-avatar.user-avatar { background: #0d6efd; color: #fff; }

    .chat-bubble {
        max-width: 75%;
        padding: 0.65rem 1rem;
        border-radius: 1rem;
        font-size: 0.92rem;
        line-height: 1.55;
        word-wrap: break-word;
    }
    .chat-bubble.user  {
        background: #0d6efd;
        color: #fff;
        border-bottom-right-radius: 0.25rem;
    }
    .chat-bubble.model {
        background: #fff;
        color: #212529;
        border: 1px solid #dee2e6;
        border-bottom-left-radius: 0.25rem;
    }

    /* Markdown-rendered content inside AI bubbles */
    .chat-bubble.model p  { margin-bottom: 0.4rem; }
    .chat-bubble.model p:last-child { margin-bottom: 0; }
    .chat-bubble.model ul,
    .chat-bubble.model ol { padding-left: 1.25rem; margin-bottom: 0.4rem; }
    .chat-bubble.model code {
        background: #f1f3f5;
        padding: 0.1em 0.35em;
        border-radius: 3px;
        font-size: 0.87em;
    }

    /* ── Typing indicator ──────────────────────────────────────── */
    .typing-indicator span {
        display: inline-block;
        width: 7px;
        height: 7px;
        background: #adb5bd;
        border-radius: 50%;
        margin: 0 1px;
        animation: bounce 1.2s infinite;
    }
    .typing-indicator span:nth-child(2) { animation-delay: 0.2s; }
    .typing-indicator span:nth-child(3) { animation-delay: 0.4s; }
    @keyframes bounce {
        0%, 60%, 100% { transform: translateY(0); }
        30%            { transform: translateY(-6px); }
    }

    /* ── Input area ────────────────────────────────────────────── */
    #chat-input-area {
        border-top: 1px solid #dee2e6;
        padding: 0.85rem 1.25rem;
        background: #fff;
    }
    #chat-input {
        resize: none;
        border-radius: 1.5rem;
        padding: 0.6rem 1rem;
        font-size: 0.92rem;
    }
    #btn-send {
        border-radius: 50%;
        width: 42px;
        height: 42px;
        padding: 0;
        flex-shrink: 0;
    }

    /* ── Limit / submitted areas ───────────────────────────────── */
    #session-limit-area,
    #session-submitted-area {
        border-top: 1px solid #dee2e6;
        background: #fff;
    }
    #session-limit-area .alert,
    #session-submitted-area .alert {
        border-radius: 0;
        margin: 0;
    }

    /* ── Question counter ──────────────────────────────────────── */
    #question-counter {
        font-size: 0.78rem;
        font-weight: 500;
    }
    #question-counter.near-limit { color: #fd7e14; }
    #question-counter.at-limit   { color: #dc3545; }
</style>
@endpush

@section('content')
<div class="row">
    <div class="col-12">
        <h1 class="mt-0 mb-4">AI Advisor</h1>

        {{-- ── Tips ──────────────────────────────────────────────────── --}}
        <div class="card mb-4">
            <div class="card-body">
                <h5 class="d-flex align-items-start gap-2"><i class="bi bi-lightbulb text-warning flex-shrink-0 mt-1"></i>Tips for Using the AI Advisor</h5>
                <ul class="mb-0">
                    @foreach($tips as $tip)
                        <li>{!! nl2br(e(str_replace('{grade}', '<strong>' . e($user->grade_display) . '</strong>', $tip))) !!}</li>
                    @endforeach
                </ul>
            </div>
        </div>

        {{-- ── Main Chat Card ────────────────────────────────────────── --}}
        <div class="card shadow-sm">
            <div id="chat-panel">
                {{-- Messages --}}
                <div id="chat-messages">
                    {{-- Welcome screen (shown before any session is active) --}}
                    <div id="chat-welcome" class="text-center py-5">
                        <i class="bi bi-robot display-1 text-success mb-3 d-block"></i>
                        <h5 class="mb-1">Hi, {{ $user->first_name }}! I'm your AI College Advisor.</h5>
                        <p class="text-muted mb-4">
                            Get personalised college advice tailored to your profile as a
                            <strong>{{ $user->grade_display }}</strong> student.
                        </p>
                        <div class="d-grid d-md-block">
                        <button class="btn btn-primary btn-lg px-5" id="btn-welcome-start">
                            New Chat
                        </button>
                        </div>
                        @if($lastSession)
                        <br><a href="#" class="d-inline-block mt-1 small" id="btn-welcome-load">
                            <i class="bi bi-clock-history me-1"></i>Load Last Chat
                        </a>
                        @endif
                    </div>
                </div>

                {{-- Input area (hidden until a session starts) --}}
                <div id="chat-input-area" style="display:none;">
                    <div class="d-flex gap-2 align-items-end">
                        <textarea
                            id="chat-input"
                            class="form-control"
                            rows="2"
                            placeholder="Ask about colleges, applications, essays…"
                        ></textarea>
                        <button id="btn-send" class="btn btn-success" title="Send">
                            <i class="bi bi-send-fill"></i>
                        </button>
                    </div>
                    <div class="text-muted small mt-1 d-flex justify-content-between align-items-center" style="padding-left:0.25rem;">
                        <span>Press <kbd>Enter</kbd> to send &nbsp;·&nbsp; <kbd>Shift+Enter</kbd> for new line</span>
                        <span id="question-counter">0 / {{ $questionLimit }} questions</span>
                    </div>
                </div>

                {{-- Shown when the 15-question limit is reached --}}
                <div id="session-limit-area" style="display:none;">
                    <div class="alert alert-warning mb-0 d-flex align-items-start gap-2">
                        <i class="bi bi-exclamation-triangle-fill mt-1 flex-shrink-0"></i>
                        <div>
                            <strong>Question limit reached.</strong>
                            <p class="mb-2 small mt-1">You've used all {{ $questionLimit }} questions for this session. Submit your questions and our advisors will send you detailed answers.</p>
                            <button id="btn-submit-session" class="btn btn-primary btn-sm">
                                <i class="bi bi-send me-1"></i>Submit Questions for Review
                            </button>
                        </div>
                    </div>
                </div>

                {{-- Shown after submission --}}
                <div id="session-submitted-area" style="display:none;">
                    <div class="alert alert-success mb-0">
                        <i class="bi bi-check-circle-fill me-1"></i>
                        <strong>Questions submitted!</strong> Our advisors will review your questions and send you detailed answers soon.
                    </div>
                </div>

                {{-- Shown when the user's account is suspended --}}
                <div id="account-suspended-area" style="display:none;">
                    <div class="alert alert-danger mb-0">
                        <i class="bi bi-slash-circle-fill me-1"></i>
                        <strong>Access Suspended.</strong> Your AI Advisor access has been suspended due to repeated policy violations. Please contact us to appeal.
                    </div>
                </div>
            </div>
        </div>

        {{-- ── Disclaimer ────────────────────────────────────────────── --}}
        <div class="card mt-4 border-danger">
            <div class="card-body">
                <h6 class="text-danger mb-1">Disclaimer</h6>
                <p class="text-muted small mb-0">{{ $disclaimer }}</p>
            </div>
        </div>

    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/marked@12/marked.min.js"></script>
<script>
(function () {
    'use strict';

    // Configure marked (v9+ API)
    marked.use({ breaks: true, gfm: true });

    const QUESTION_LIMIT    = {{ $questionLimit }};

    const csrfToken         = document.querySelector('meta[name="csrf-token"]').content;
    const messagesEl        = document.getElementById('chat-messages');
    const welcomeEl         = document.getElementById('chat-welcome');
    const inputAreaEl       = document.getElementById('chat-input-area');
    const sessionLimitEl      = document.getElementById('session-limit-area');
    const sessionSubmittedEl  = document.getElementById('session-submitted-area');
    const accountSuspendedEl  = document.getElementById('account-suspended-area');
    const chatInputEl       = document.getElementById('chat-input');
    const btnSend           = document.getElementById('btn-send');
    const btnSubmitSession  = document.getElementById('btn-submit-session');
    const btnNewChat        = document.getElementById('btn-new-chat');
    const btnLoadLast       = document.getElementById('btn-load-last');
    const btnWelcomeStart   = document.getElementById('btn-welcome-start');
    const btnWelcomeLoad    = document.getElementById('btn-welcome-load');
    const questionCounterEl = document.getElementById('question-counter');

    let currentSessionId = null;
    let isSending        = false;
    let questionCount    = 0;

    // ── Helpers ────────────────────────────────────────────────────────────

    function scrollToBottom() {
        messagesEl.scrollTop = messagesEl.scrollHeight;
    }

    function updateQuestionCounter(count, isSubmitted) {
        questionCount = count;

        // Update counter badge
        questionCounterEl.textContent = count + ' / ' + QUESTION_LIMIT + ' questions';
        questionCounterEl.classList.remove('near-limit', 'at-limit');
        if (count >= QUESTION_LIMIT) {
            questionCounterEl.classList.add('at-limit');
        } else if (count >= QUESTION_LIMIT - 3) {
            questionCounterEl.classList.add('near-limit');
        }

        // Show the right bottom area
        inputAreaEl.style.display        = 'none';
        sessionLimitEl.style.display     = 'none';
        sessionSubmittedEl.style.display = 'none';
        accountSuspendedEl.style.display = 'none';

        if (isSubmitted) {
            sessionSubmittedEl.style.display = 'block';
        } else if (count >= QUESTION_LIMIT) {
            sessionLimitEl.style.display = 'block';
        } else {
            inputAreaEl.style.display = 'block';
            chatInputEl.focus();
        }
    }

    function showChat(count, isSubmitted) {
        updateQuestionCounter(count, isSubmitted);
        welcomeEl.style.display = 'none';
    }

    function showWelcome() {
        welcomeEl.style.display          = 'block';
        inputAreaEl.style.display        = 'none';
        sessionLimitEl.style.display     = 'none';
        sessionSubmittedEl.style.display = 'none';
        // Clear messages except welcome
        Array.from(messagesEl.children).forEach(el => {
            if (el.id !== 'chat-welcome') el.remove();
        });
    }

    function appendBubble(role, html, isHtml = false) {
        const wrap = document.createElement('div');
        wrap.className = `chat-bubble-wrap ${role}`;

        const avatar = document.createElement('div');
        avatar.className = `bubble-avatar ${role === 'model' ? 'ai-avatar' : 'user-avatar'}`;
        avatar.innerHTML = role === 'model'
            ? '<i class="bi bi-robot"></i>'
            : '<i class="bi bi-person-fill"></i>';

        const bubble = document.createElement('div');
        bubble.className = `chat-bubble ${role}`;
        if (isHtml) {
            bubble.innerHTML = html;
        } else {
            bubble.textContent = html;
        }

        wrap.appendChild(avatar);
        wrap.appendChild(bubble);
        messagesEl.appendChild(wrap);
        scrollToBottom();
        return bubble;
    }

    function appendTypingIndicator() {
        const wrap = document.createElement('div');
        wrap.className = 'chat-bubble-wrap model';
        wrap.id = 'typing-indicator-wrap';

        const avatar = document.createElement('div');
        avatar.className = 'bubble-avatar ai-avatar';
        avatar.innerHTML = '<i class="bi bi-robot"></i>';

        const bubble = document.createElement('div');
        bubble.className = 'chat-bubble model';
        bubble.innerHTML = '<span class="typing-indicator"><span></span><span></span><span></span></span>';

        wrap.appendChild(avatar);
        wrap.appendChild(bubble);
        messagesEl.appendChild(wrap);
        scrollToBottom();
    }

    function removeTypingIndicator() {
        const el = document.getElementById('typing-indicator-wrap');
        if (el) el.remove();
    }

    function appendSystemMessage(text) {
        const el = document.createElement('div');
        el.className = 'text-center text-muted small my-3';
        el.textContent = text;
        messagesEl.appendChild(el);
        scrollToBottom();
    }

    function appendWarningAlert(text) {
        const el = document.createElement('div');
        el.className = 'alert alert-warning mx-3 my-2 py-2 small mb-0';
        el.innerHTML = '<i class="bi bi-exclamation-triangle-fill me-1"></i>' + text;
        messagesEl.appendChild(el);
        scrollToBottom();
    }

    function showSuspended() {
        inputAreaEl.style.display        = 'none';
        sessionLimitEl.style.display     = 'none';
        sessionSubmittedEl.style.display = 'none';
        accountSuspendedEl.style.display = 'block';
    }

    function setSending(sending) {
        isSending            = sending;
        btnSend.disabled     = sending;
        chatInputEl.disabled = sending;
        if (sending) {
            btnSend.innerHTML = '<span class="spinner-border spinner-border-sm"></span>';
        } else {
            btnSend.innerHTML = '<i class="bi bi-send-fill"></i>';
            chatInputEl.focus();
        }
    }

    function clearMessages() {
        Array.from(messagesEl.children).forEach(el => {
            if (el.id !== 'chat-welcome') el.remove();
        });
    }

    // ── API calls ──────────────────────────────────────────────────────────

    async function startNewSession() {
        const res = await fetch('{{ route("portal.advisor.session.new") }}', {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json' },
        });
        if (!res.ok) {
            throw new Error('Session creation failed (' + res.status + ')');
        }
        return await res.json();
    }

    async function loadLastSession() {
        const res = await fetch('{{ route("portal.advisor.session.last") }}', {
            headers: { 'Accept': 'application/json' },
        });
        if (!res.ok) {
            throw new Error('Failed to load session (' + res.status + ')');
        }
        return await res.json();
    }

    async function sendMessage(message) {
        const res  = await fetch('{{ route("portal.advisor.chat") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json',
            },
            body: JSON.stringify({ message, session_id: currentSessionId }),
        });
        return { ok: res.ok, status: res.status, data: await res.json() };
    }

    async function submitSession() {
        const res = await fetch('{{ route("portal.advisor.session.submit") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json',
            },
            body: JSON.stringify({ session_id: currentSessionId }),
        });
        return res.ok;
    }

    // ── Actions ────────────────────────────────────────────────────────────

    function renderMessages(messages) {
        clearMessages();
        if (messages && messages.length > 0) {
            messages.forEach(msg => {
                if (msg.role === 'model') {
                    appendBubble('model', marked.parse(msg.content), true);
                } else {
                    appendBubble('user', msg.content);
                }
            });
        }
    }

    async function handleNewChat() {
        try {
            const data = await startNewSession();
            currentSessionId = data.session_id;

            renderMessages(data.messages || []);
            showChat(data.question_count || 0, !!data.submitted_at);

            if (data.already_exists) {
                appendSystemMessage('Continuing your session from today (' + (data.question_count || 0) + '/' + QUESTION_LIMIT + ' questions used).');
            } else if (!data.messages || data.messages.length === 0) {
                appendSystemMessage('New chat started. Say hello!');
            }
        } catch (err) {
            console.error('handleNewChat error:', err);
            alert('Could not start a new chat. Please refresh the page and try again.\n\nError: ' + err.message);
        }
    }

    async function handleLoadLast() {
        try {
            const data = await loadLastSession();
            if (!data.session_id) {
                await handleNewChat();
                return;
            }
            currentSessionId = data.session_id;

            renderMessages(data.messages || []);
            showChat(data.question_count || 0, !!data.submitted_at);

            if (!data.messages || data.messages.length === 0) {
                appendSystemMessage('Previous chat loaded — no messages yet. Say hello!');
            }
        } catch (err) {
            console.error('handleLoadLast error:', err);
            alert('Could not load chat. Please refresh the page and try again.\n\nError: ' + err.message);
        }
    }

    async function handleSend() {
        if (isSending || !currentSessionId) return;
        const message = chatInputEl.value.trim();
        if (!message) return;

        chatInputEl.value = '';
        appendBubble('user', message);
        setSending(true);
        appendTypingIndicator();

        const { ok, status, data } = await sendMessage(message);
        removeTypingIndicator();
        setSending(false);

        if (ok && data.success) {
            appendBubble('model', marked.parse(data.message), true);
            updateQuestionCounter(data.question_count ?? (questionCount + 1), false);
        } else if (data.suspended) {
            showSuspended();
        } else if (data.flagged) {
            appendWarningAlert(data.message);
        } else if (status === 429 && data.question_limit_reached) {
            updateQuestionCounter(QUESTION_LIMIT, !!data.submitted_at);
        } else if (status === 429 && data.cap_reached) {
            appendSystemMessage('⚠ ' + data.message);
        } else if (status === 503) {
            appendSystemMessage('⚠ ' + (data.message ?? 'The AI is busy. Please wait a moment and try again.'));
        } else {
            appendSystemMessage('⚠ Something went wrong. Please try again.');
        }
    }

    async function handleSubmit() {
        btnSubmitSession.disabled  = true;
        btnSubmitSession.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span>Submitting…';

        const ok = await submitSession();
        if (ok) {
            sessionLimitEl.style.display     = 'none';
            sessionSubmittedEl.style.display = 'block';
        } else {
            btnSubmitSession.disabled  = false;
            btnSubmitSession.innerHTML = '<i class="bi bi-send me-1"></i>Submit Questions for Review';
            appendSystemMessage('⚠ Could not submit. Please try again.');
        }
    }

    // ── Event listeners ────────────────────────────────────────────────────

    if (btnNewChat)         btnNewChat.addEventListener('click', handleNewChat);
    if (btnLoadLast)        btnLoadLast.addEventListener('click', handleLoadLast);
    if (btnWelcomeStart)    btnWelcomeStart.addEventListener('click', handleNewChat);
    if (btnWelcomeLoad)     btnWelcomeLoad.addEventListener('click', handleLoadLast);
    if (btnSend)            btnSend.addEventListener('click', handleSend);
    if (btnSubmitSession)   btnSubmitSession.addEventListener('click', handleSubmit);

    chatInputEl.addEventListener('keydown', function (e) {
        if (e.key === 'Enter' && !e.shiftKey) {
            e.preventDefault();
            handleSend();
        }
    });

})();
</script>
@endpush
