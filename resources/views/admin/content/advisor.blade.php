@extends('layouts.admin')

@section('title', 'AI Advisor Content')

@section('content')
<h1 class="mb-4">AI Advisor Content</h1>

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

<form method="POST" action="{{ route('admin.content.advisor.update') }}">
    @csrf
    @method('PUT')

    {{-- Conversation Rules --}}
    <div class="card mb-4">
        <div class="card-header fw-semibold">
            <i class="bi bi-sliders text-primary me-2"></i>Conversation Rules
        </div>
        <div class="card-body">
            <p class="text-muted small mb-3">
                Controls how many sessions a student can start and how many questions they can ask per session within a 24-hour window.
            </p>
            <div class="row g-3">
                <div class="col-md-4">
                    <label for="session_limit" class="form-label fw-medium">Sessions per 24 hours</label>
                    <input type="number"
                           class="form-control @error('session_limit') is-invalid @enderror"
                           id="session_limit"
                           name="session_limit"
                           value="{{ old('session_limit', $sessionLimit) }}"
                           min="1" max="10" required>
                    @error('session_limit')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <div class="form-text">How many new chat sessions a student can start per day (1–10).</div>
                </div>
                <div class="col-md-4">
                    <label for="question_limit" class="form-label fw-medium">Questions per session</label>
                    <input type="number"
                           class="form-control @error('question_limit') is-invalid @enderror"
                           id="question_limit"
                           name="question_limit"
                           value="{{ old('question_limit', $questionLimit) }}"
                           min="1" max="100" required>
                    @error('question_limit')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <div class="form-text">Maximum questions a student can ask in a single session (1–100).</div>
                </div>
            </div>
        </div>
    </div>

    {{-- Opening Greeting --}}
    <div class="card mb-4">
        <div class="card-header fw-semibold">
            <i class="bi bi-chat-quote text-success me-2"></i>Opening Greeting
        </div>
        <div class="card-body">
            <p class="text-muted small mb-1">
                This message is automatically sent by the AI Advisor when a student starts a new chat session.
                Use the placeholders below to personalise it:
            </p>
            <ul class="text-muted small mb-3">
                <li><code>{name}</code> — student's first name</li>
                <li><code>{grade}</code> — student's grade level (e.g. <em>11th Grader</em>)</li>
            </ul>
            <textarea class="form-control @error('greeting') is-invalid @enderror"
                      name="greeting"
                      id="greeting"
                      rows="4"
                      maxlength="1000"
                      required>{{ old('greeting', $greeting) }}</textarea>
            @error('greeting')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
            <div class="form-text">Shown as the first message in every new chat. Max 1,000 characters.</div>
        </div>
    </div>

    {{-- Tips --}}
    <div class="card mb-4">
        <div class="card-header fw-semibold">
            <i class="bi bi-lightbulb text-warning me-2"></i>Tips for Using the AI Advisor
        </div>
        <div class="card-body">
            <p class="text-muted small mb-3">
                These bullet points appear above the chat window. Use <code>{grade}</code> anywhere to insert the student's grade level.
            </p>

            <div id="tips-list">
                @foreach($tips as $index => $tip)
                    <div class="input-group mb-2 tip-row">
                        <span class="input-group-text text-muted"><i class="bi bi-grip-vertical"></i></span>
                        <input type="text"
                               class="form-control @error("tips.$index") is-invalid @enderror"
                               name="tips[{{ $index }}]"
                               value="{{ old("tips.$index", $tip) }}"
                               placeholder="Enter a tip…"
                               required>
                        <button type="button" class="btn btn-outline-danger remove-tip">
                            <i class="bi bi-trash"></i>
                        </button>
                        @error("tips.$index")
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                @endforeach
            </div>

            <button type="button" class="btn btn-outline-primary btn-sm mt-1" id="add-tip">
                <i class="bi bi-plus-lg me-1"></i>Add Tip
            </button>
        </div>
    </div>

    {{-- Disclaimer --}}
    <div class="card mb-4">
        <div class="card-header fw-semibold">
            <i class="bi bi-exclamation-triangle text-danger me-2"></i>Disclaimer
        </div>
        <div class="card-body">
            <textarea class="form-control @error('disclaimer') is-invalid @enderror"
                      name="disclaimer"
                      id="disclaimer"
                      rows="4"
                      maxlength="2000"
                      required>{{ old('disclaimer', $disclaimer) }}</textarea>
            @error('disclaimer')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
            <div class="form-text">Shown below the chat window in a red-bordered card.</div>
        </div>
    </div>

    <button type="submit" class="btn btn-primary">
        <i class="bi bi-check-lg me-2"></i>Save Changes
    </button>
</form>
@endsection

@push('scripts')
<script>
    let tipIndex = {{ count($tips) }};

    document.getElementById('add-tip').addEventListener('click', function () {
        const list = document.getElementById('tips-list');
        const row  = document.createElement('div');
        row.className = 'input-group mb-2 tip-row';
        row.innerHTML = `
            <span class="input-group-text text-muted"><i class="bi bi-grip-vertical"></i></span>
            <input type="text" class="form-control" name="tips[${tipIndex}]" placeholder="Enter a tip…" required>
            <button type="button" class="btn btn-outline-danger remove-tip">
                <i class="bi bi-trash"></i>
            </button>
        `;
        list.appendChild(row);
        tipIndex++;
    });

    document.getElementById('tips-list').addEventListener('click', function (e) {
        const btn = e.target.closest('.remove-tip');
        if (!btn) return;
        const rows = document.querySelectorAll('.tip-row');
        if (rows.length > 1) {
            btn.closest('.tip-row').remove();
        } else {
            alert('You must keep at least one tip.');
        }
    });
</script>
@endpush
