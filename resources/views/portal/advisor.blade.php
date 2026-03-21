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
                    <li>Be specific with your questions for better answers</li>
                    <li>Ask about college requirements, test prep, extracurriculars, and application tips</li>
                    <li>The advisor knows you're a <strong>{{ $user->grade_display }}</strong> student and will tailor advice accordingly</li>
                    <li>You can ask follow-up questions to get more detailed information</li>
                    <li>Your chat history is saved — use <strong>Load Last Chat</strong> to continue where you left off</li>
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
                    <div class="text-muted small mt-1" style="padding-left:0.25rem;">
                        Press <kbd>Enter</kbd> to send &nbsp;·&nbsp; <kbd>Shift+Enter</kbd> for new line
                    </div>
                </div>
            </div>
        </div>

        {{-- ── Disclaimer ────────────────────────────────────────────── --}}
        <div class="card mt-4 border-danger">
            <div class="card-body">
                <h6 class="text-danger mb-1">Disclaimer</h6>
                <p class="text-muted small mb-0">
                    This advisor is intended for use with universities located within the United States only.
                    All information provided is for general guidance purposes and may not reflect the most current
                    institutional policies, requirements, or deadlines. Please verify all details directly with
                    the respective institution before making any decisions.
                </p>
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

    const csrfToken     = document.querySelector('meta[name="csrf-token"]').content;
    const messagesEl    = document.getElementById('chat-messages');
    const welcomeEl     = document.getElementById('chat-welcome');
    const inputAreaEl   = document.getElementById('chat-input-area');
    const chatInputEl   = document.getElementById('chat-input');
    const btnSend       = document.getElementById('btn-send');
    const btnNewChat    = document.getElementById('btn-new-chat');
    const btnLoadLast   = document.getElementById('btn-load-last');
    const btnWelcomeStart = document.getElementById('btn-welcome-start');
    const btnWelcomeLoad  = document.getElementById('btn-welcome-load');

    let currentSessionId = null;
    let isSending        = false;

    // ── Helpers ────────────────────────────────────────────────────────────

    function scrollToBottom() {
        messagesEl.scrollTop = messagesEl.scrollHeight;
    }

    function showChat() {
        welcomeEl.style.display    = 'none';
        inputAreaEl.style.display  = 'block';
        chatInputEl.focus();
    }

    function showWelcome() {
        welcomeEl.style.display   = 'block';
        inputAreaEl.style.display = 'none';
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

    function setSending(sending) {
        isSending         = sending;
        btnSend.disabled  = sending;
        chatInputEl.disabled = sending;
        if (sending) {
            btnSend.innerHTML = '<span class="spinner-border spinner-border-sm"></span>';
        } else {
            btnSend.innerHTML = '<i class="bi bi-send-fill"></i>';
            chatInputEl.focus();
        }
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
        const data = await res.json();
        if (!data.session_id) {
            throw new Error('No session_id in response');
        }
        return data.session_id;
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

    // ── Actions ────────────────────────────────────────────────────────────

    function setBtnLoading(btn, loading, originalHtml) {
        btn.disabled = loading;
        btn.innerHTML = loading
            ? '<span class="spinner-border spinner-border-sm me-1"></span>Starting…'
            : originalHtml;
    }

    async function handleNewChat() {
        const originalHtml = btnNewChat ? btnNewChat.innerHTML : '';
        if (btnNewChat) setBtnLoading(btnNewChat, true, originalHtml);
        try {
            currentSessionId = await startNewSession();
            // Clear old messages
            Array.from(messagesEl.children).forEach(el => {
                if (el.id !== 'chat-welcome') el.remove();
            });
            showChat();
            appendSystemMessage('New chat started. Say hello!');
        } catch (err) {
            console.error('handleNewChat error:', err);
            alert('Could not start a new chat. Please refresh the page and try again.\n\nError: ' + err.message);
        } finally {
            if (btnNewChat) setBtnLoading(btnNewChat, false, originalHtml);
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
            // Clear stale messages
            Array.from(messagesEl.children).forEach(el => {
                if (el.id !== 'chat-welcome') el.remove();
            });
            showChat();
            if (data.messages.length === 0) {
                appendSystemMessage('Previous chat loaded — no messages yet. Say hello!');
            } else {
                data.messages.forEach(msg => {
                    if (msg.role === 'model') {
                        appendBubble('model', marked.parse(msg.content), true);
                    } else {
                        appendBubble('user', msg.content);
                    }
                });
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
        } else if (status === 429 && data.cap_reached) {
            appendSystemMessage('⚠ ' + data.message);
        } else if (status === 503) {
            appendSystemMessage('⚠ ' + (data.message ?? 'The AI is busy. Please wait a moment and try again.'));
        } else {
            appendSystemMessage('⚠ Something went wrong. Please try again.');
        }
    }

    // ── Event listeners ────────────────────────────────────────────────────

    if (btnNewChat)      btnNewChat.addEventListener('click', handleNewChat);
    if (btnLoadLast)     btnLoadLast.addEventListener('click', handleLoadLast);
    if (btnWelcomeStart) btnWelcomeStart.addEventListener('click', handleNewChat);
    if (btnWelcomeLoad)  btnWelcomeLoad.addEventListener('click', handleLoadLast);
    if (btnSend)         btnSend.addEventListener('click', handleSend);

    chatInputEl.addEventListener('keydown', function (e) {
        if (e.key === 'Enter' && !e.shiftKey) {
            e.preventDefault();
            handleSend();
        }
    });

})();
</script>
@endpush
