@extends('layouts.admin')

@section('title', 'View Message')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1>Contact Message</h1>
    <a href="{{ route('admin.contacts') }}" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left me-2"></i>Back to Messages
    </a>
</div>

<div class="card">
    <div class="card-body">
        <div class="row mb-4">
            <div class="col-md-6">
                <label class="form-label text-muted">From</label>
                <p class="mb-0"><strong>{{ $submission->email }}</strong></p>
            </div>
            <div class="col-md-6">
                <label class="form-label text-muted">Received</label>
                <p class="mb-0">{{ $submission->created_at->format('F j, Y \a\t g:i A') }}</p>
            </div>
        </div>

        <div class="mb-4">
            <label class="form-label text-muted">Message</label>
            <div class="border rounded p-3 bg-light">
                {{ $submission->comments }}
            </div>
        </div>

        <div class="d-flex gap-2">
            <a href="mailto:{{ $submission->email }}" class="btn btn-primary">
                <i class="bi bi-reply me-2"></i>Reply via Email
            </a>
            <form action="{{ route('admin.contacts.destroy', $submission) }}" method="POST" class="d-inline">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-outline-danger" onclick="return confirm('Are you sure you want to delete this submission?')">
                    <i class="bi bi-trash me-2"></i>Delete
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
