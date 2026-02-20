@extends('layouts.admin')

@section('title', 'Homepage Content')

@section('content')
<h1 class="mb-4">Homepage Content</h1>

<div class="card">
    <div class="card-header">
        <h5 class="mb-0"><i class="bi bi-card-image me-2"></i>Hero Section</h5>
    </div>
    <div class="card-body">
        <form method="POST" action="{{ route('admin.content.homepage.update') }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label for="hero_title" class="form-label">Hero Title</label>
                <input type="text"
                       class="form-control @error('hero_title') is-invalid @enderror"
                       id="hero_title"
                       name="hero_title"
                       value="{{ old('hero_title', $heroTitle) }}"
                       required>
                @error('hero_title')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="hero_subtext" class="form-label">Hero Subtext</label>
                <textarea class="form-control @error('hero_subtext') is-invalid @enderror"
                          id="hero_subtext"
                          name="hero_subtext"
                          rows="3"
                          required>{{ old('hero_subtext', $heroSubtext) }}</textarea>
                @error('hero_subtext')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-4">
                <label class="form-label">Background Image</label>
                <div class="border rounded p-3 mb-3 bg-light">
                    @if($heroImage)
                        <img src="{{ Storage::url($heroImage) }}" alt="Current Hero Image" class="img-fluid rounded" style="max-height: 200px;">
                    @else
                        <span class="text-muted">No background image uploaded (using default)</span>
                    @endif
                </div>
                <input type="file"
                       class="form-control @error('hero_image') is-invalid @enderror"
                       id="hero_image"
                       name="hero_image"
                       accept=".jpg,.jpeg,.png,.webp">
                <div class="form-text">Recommended: 1920x1080 pixels, JPG/PNG/WebP, max 5MB</div>
                @error('hero_image')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <button type="submit" class="btn btn-primary">
                <i class="bi bi-check-lg me-2"></i>Save Changes
            </button>
        </form>
    </div>
</div>
@endsection
