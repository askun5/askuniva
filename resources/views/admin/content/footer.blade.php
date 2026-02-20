@extends('layouts.admin')

@section('title', 'Footer Settings')

@section('content')
<h1 class="mb-4">Footer Settings</h1>

<div class="card">
    <div class="card-body">
        <form method="POST" action="{{ route('admin.content.footer.update') }}">
            @csrf
            @method('PUT')

            <div class="mb-4">
                <label class="form-label">Footer Links</label>
                <div id="footer-links">
                    @foreach($footerLinks as $index => $link)
                        <div class="row mb-2 footer-link-row">
                            <div class="col-md-5">
                                <input type="text"
                                       class="form-control"
                                       name="links[{{ $index }}][label]"
                                       value="{{ $link['label'] }}"
                                       placeholder="Label"
                                       required>
                            </div>
                            <div class="col-md-5">
                                <input type="text"
                                       class="form-control"
                                       name="links[{{ $index }}][url]"
                                       value="{{ $link['url'] }}"
                                       placeholder="URL (e.g., /about)"
                                       required>
                            </div>
                            <div class="col-md-2">
                                <button type="button" class="btn btn-outline-danger w-100 remove-link">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </div>
                        </div>
                    @endforeach
                </div>
                <button type="button" class="btn btn-outline-primary btn-sm mt-2" id="add-link">
                    <i class="bi bi-plus-lg me-2"></i>Add Link
                </button>
            </div>

            <div class="mb-4">
                <label for="copyright_text" class="form-label">Copyright Text</label>
                <input type="text"
                       class="form-control @error('copyright_text') is-invalid @enderror"
                       id="copyright_text"
                       name="copyright_text"
                       value="{{ old('copyright_text', $copyrightText) }}"
                       required>
                <div class="form-text">The year will be automatically prepended (e.g., "&copy; 2024 Your Text")</div>
                @error('copyright_text')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <button type="submit" class="btn btn-primary">
                <i class="bi bi-check-lg me-2"></i>Save Footer Settings
            </button>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    let linkIndex = {{ count($footerLinks) }};

    document.getElementById('add-link').addEventListener('click', function() {
        const container = document.getElementById('footer-links');
        const row = document.createElement('div');
        row.className = 'row mb-2 footer-link-row';
        row.innerHTML = `
            <div class="col-md-5">
                <input type="text" class="form-control" name="links[${linkIndex}][label]" placeholder="Label" required>
            </div>
            <div class="col-md-5">
                <input type="text" class="form-control" name="links[${linkIndex}][url]" placeholder="URL (e.g., /about)" required>
            </div>
            <div class="col-md-2">
                <button type="button" class="btn btn-outline-danger w-100 remove-link">
                    <i class="bi bi-trash"></i>
                </button>
            </div>
        `;
        container.appendChild(row);
        linkIndex++;
    });

    document.getElementById('footer-links').addEventListener('click', function(e) {
        if (e.target.classList.contains('remove-link') || e.target.closest('.remove-link')) {
            const row = e.target.closest('.footer-link-row');
            if (document.querySelectorAll('.footer-link-row').length > 1) {
                row.remove();
            } else {
                alert('You must have at least one footer link.');
            }
        }
    });
</script>
@endpush
