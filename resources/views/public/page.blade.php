@extends(auth()->check() ? 'layouts.portal' : 'layouts.public')

@section('title', $page->title)

@section('body-class', 'content-page')

@push('styles')
<style>
    body.content-page {
        background-color: #f8f9fa;
        padding-bottom: 100px; /* Account for fixed footer */
    }

    .content-section {
        min-height: calc(100vh - 70px);
        padding: 3rem 0;
    }

    @if(auth()->check())
    body.content-page {
        padding-bottom: 0;
    }

    .content-section {
        min-height: auto;
    }
    @endif

    .content-card {
        background: white;
        border-radius: 0.5rem;
        box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
    }

    .page-content {
        line-height: 1.8;
    }

    .page-content h2, .page-content h3 {
        margin-top: 2rem;
        margin-bottom: 1rem;
    }

    .page-content ul, .page-content ol {
        margin-bottom: 1rem;
    }
</style>
@endpush

@section('content')
<section class="content-section">
    <div class="container px-4 px-lg-5">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="content-card p-5">
                    <h1 class="mb-4">{{ $page->title }}</h1>
                    <hr class="mb-4">
                    <div class="page-content">
                        {!! $page->content !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
