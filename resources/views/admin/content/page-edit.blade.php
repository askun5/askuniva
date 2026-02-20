@extends('layouts.admin')

@section('title', 'Edit Page')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1>Edit Page: {{ $page->title }}</h1>
    <a href="{{ route('admin.content.pages') }}" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left me-2"></i>Back to Pages
    </a>
</div>

<div class="card">
    <div class="card-body">
        <form method="POST" action="{{ route('admin.content.pages.update', $page) }}">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label for="title" class="form-label">Page Title</label>
                <input type="text"
                       class="form-control @error('title') is-invalid @enderror"
                       id="title"
                       name="title"
                       value="{{ old('title', $page->title) }}"
                       required>
                @error('title')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label class="form-label">Page Slug</label>
                <input type="text" class="form-control" value="/{{ $page->slug }}" disabled>
                <div class="form-text">The URL slug cannot be changed.</div>
            </div>

            <div class="mb-4">
                <label for="content" class="form-label">Page Content</label>
                <textarea class="form-control @error('content') is-invalid @enderror"
                          id="content"
                          name="content"
                          rows="15">{{ old('content', $page->content) }}</textarea>
                <div class="form-text">You can use HTML for formatting.</div>
                @error('content')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-check-lg me-2"></i>Save Changes
                </button>
                <a href="/{{ $page->slug }}" target="_blank" class="btn btn-outline-secondary">
                    <i class="bi bi-eye me-2"></i>Preview
                </a>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<!-- Optional: Include a rich text editor like TinyMCE or CKEditor -->
<script src="https://cdn.tiny.cloud/1/no-api-key/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>
<script>
    tinymce.init({
        selector: '#content',
        plugins: 'lists link code',
        toolbar: 'undo redo | blocks | bold italic | alignleft aligncenter alignright | bullist numlist | link | code',
        height: 400,
        menubar: false,
        branding: false
    });
</script>
@endpush
