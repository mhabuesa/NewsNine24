@extends('backend.layouts.app')
@section('title', 'News Details')
@push('style')
    <link rel="stylesheet" href="{{ asset('assets') }}/js/plugins/datatables-bs5/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="{{ asset('assets') }}/js/plugins/datatables-buttons-bs5/css/buttons.bootstrap5.min.css">
    <link rel="stylesheet"
        href="{{ asset('assets') }}/js/plugins/datatables-responsive-bs5/css/responsive.bootstrap5.min.css">
@endpush
@section('content')

    <div class="bg-image" style="min-height: 600px; background-image: url('{{ media($news->image) }}');"></div>
    <div class="bg-body-extra-light">
        <div class="content content-boxed">
            <div class="text-center fs-sm push">
                <h2 class="mt-3">{{ $news->title }}</h2>
                <span class="d-inline-block py-2 px-4 bg-body-light rounded">
                    Published on {{ $news->created_at->format('M d, Y') }}
                    @if ($news->created_at->isToday())
                        &bull; <span>{{ $news->created_at->diffForHumans() }}</span>
                    @endif
                </span>
            </div>
            <div class="row justify-content-center">
                <div class="col-sm-10">
                    <article class="story">
                        <p>{{ $news->short_description }}</p>

                        {!! $news->description !!}

                    </article>
                    <article class="story">
                        <h4>Meta Information</h4>
                        <span class="d-block fw-bold mb-1">Title</span>
                        <p>{{ $news->meta->title }}</p>

                        <span class="d-block fw-bold mb-1">Description</span>
                        {{ $news->meta->description }}
                        <span class="d-block fw-bold mb-1 mt-4">Tags</span>
                        {{ $news->meta->tags }}

                    </article>
                </div>
            </div>
        </div>
    </div>


@endsection
