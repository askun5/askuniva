@extends('layouts.admin')

@section('title', 'Branding')

@section('content')
<h1 class="mb-4">Global Branding</h1>

<div class="card">
    <div class="card-body">
        <form method="POST" action="{{ route('admin.branding.update') }}" enctype="multipart/form-data">
            @csrf

            <div class="row">
                <div class="col-md-6 mb-4">
                    <label class="form-label">Site Logo</label>
                    <div class="border rounded p-3 mb-3 bg-light text-center">
                        @if($logo)
                            <img src="{{ Storage::url($logo) }}" alt="Current Logo" class="img-fluid" style="max-height: 80px;">
                        @else
                            <span class="text-muted">No logo uploaded</span>
                        @endif
                    </div>
                    <input type="file"
                           class="form-control @error('logo') is-invalid @enderror"
                           id="logo"
                           name="logo"
                           accept=".png,.jpg,.jpeg,.svg">
                    <div class="form-text">Recommended: PNG or SVG, max 2MB</div>
                    @error('logo')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6 mb-4">
                    <label class="form-label">Favicon</label>
                    <div class="border rounded p-3 mb-3 bg-light text-center">
                        @if($favicon)
                            <img src="{{ Storage::url($favicon) }}" alt="Current Favicon" class="img-fluid" style="max-height: 32px;">
                        @else
                            <span class="text-muted">No favicon uploaded</span>
                        @endif
                    </div>
                    <input type="file"
                           class="form-control @error('favicon') is-invalid @enderror"
                           id="favicon"
                           name="favicon"
                           accept=".ico,.png">
                    <div class="form-text">Recommended: ICO or PNG, 32x32 pixels</div>
                    @error('favicon')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <button type="submit" class="btn btn-primary">
                <i class="bi bi-check-lg me-2"></i>Save Branding
            </button>
        </form>
    </div>
</div>
@endsection
