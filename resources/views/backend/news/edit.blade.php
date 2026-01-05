@extends('backend.layouts.app')
@section('title', 'Edit News')
@push('style')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.15.2/css/selectize.default.min.css"
        integrity="sha512-pTaEn+6gF1IeWv3W1+7X7eM60TFu/agjgoHmYhAfLEU8Phuf6JKiiE8YmsNC0aCgQv4192s4Vai8YZ6VNM6vyQ=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <style>
        .selectize-control.multi .selectize-input [data-value] {
            background-color: #0059ffffff !important;

            background-image: linear-gradient(to bottom, #0059ffffff, #0059ffffff);
        }

        .select2-container--default .select2-selection--single {
            height: 37px !important;
            border: 1px solid #e1e1e1 !important;
            border-radius: 6px !important;
        }

        .select2-container--default .select2-selection--single .select2-selection__rendered {
            line-height: 35px !important;
        }

        .select2-container--default .select2-selection--single .select2-selection__arrow {
            top: 6px !important;
        }
    </style>
    <link rel="stylesheet" href="{{ asset('assets') }}/js/plugins/select2/css/select2.min.css">
    <link rel="stylesheet" href="{{ asset('assets') }}/richtexteditor/rte_theme_default.css" />
    <script type="text/javascript" src="{{ asset('assets') }}/richtexteditor/rte.js"></script>
    <script type="text/javascript" src='{{ asset('assets') }}/richtexteditor/plugins/all_plugins.js'></script>
@endpush
@section('content')
    <div class="container-fluid">
        <form action="{{ route('news.update', $news->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="block block-rounded">
                <div class="block-header block-header-default">
                    <h3 class="block-title">Basic Information</h3>
                </div>
                <div class="block-content block-content-full">
                    <div class="row push">
                        <div class="col-lg-5 col-xl-5 m-auto">
                            <div class="mb-4">
                                <div class="image-upload-wrapper text-center mb-3">
                                    <div class="preview-box border rounded position-relative d-flex align-items-center justify-content-center"
                                        style="height: 307px; background: #f0f2f5; overflow: hidden;">
                                        @if ($news->image != null)
                                            <img id="imagePreview" src="{{ asset($news->image) }}" class="img-fluid"
                                                style="max-height: 100%; max-width: 100%; object-fit: contain; display: block;">
                                        @else
                                            <img id="imagePreview"
                                                src="https://placehold.co/900x500/d8f2e7/000?text=News+Nine+24"
                                                class="img-fluid"
                                                style="max-height: 100%; max-width: 100%; object-fit: contain; display: block;">
                                        @endif

                                        <label for="imageInput" class="btn btn-primary rounded-circle position-absolute"
                                            style="bottom: 10px; right: 10px; width: 40px; height: 40px; padding: 8px; cursor: pointer; z-index: 10;">
                                            <i class="fas fa-cloud-upload-alt"></i>
                                        </label>
                                        <input type="file" id="imageInput" accept=".png, .jpg, .jpeg" hidden
                                            name="image">
                                    </div>

                                    <small class="text-muted mt-2 d-block">
                                        Supported Files: <b>.png, .jpg, .jpeg</b>. Image will be resized into
                                        <b>900x500px</b>
                                    </small>
                                    @error('image')
                                        <small class="text-danger mt-2 d-block">
                                            {{ $message }}
                                        </small>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-10 col-xl-10 m-auto">
                            <div class="mb-4">
                                <label class="form-label" for="newsTitle">News Title <span
                                        class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="newsTitle" name="title"
                                    placeholder="News Title" value="{{ old('title') ?? $news->title }}" required>
                                @error('title')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                            <div class="mb-4">
                                <div class="row">
                                    <div class="col-6">
                                        <label class="form-label">Category <span class="text-danger">*</span></label>
                                        <select class="js-select2 form-select" id="category_id" name="category" required>
                                            <option value="">Select Category</option>
                                            @foreach ($categories as $category)
                                                <option {{ $news->category_id == $category->id ? 'selected' : '' }}
                                                    value="{{ $category->id }}">{{ $category->name }}</option>
                                            @endforeach
                                        </select>
                                        @error('category')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>

                                    <div class="col-6">
                                        <label class="form-label">Sub Category <span
                                                class="text-muted fs-xs">(Optional)</span></label>
                                        <select class="js-select2 form-select" id="sub_category_id" name="subcategory">
                                            <option value="">Select Subcategory</option>
                                            @foreach ($subcategories as $subcategory)
                                                <option {{ $news->subcategory_id == $subcategory->id ? 'selected' : '' }}
                                                    value="{{ $subcategory->id }}">{{ $subcategory->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                </div>
                            </div>
                            <div class="mb-4">
                                <label class="form-label" for="short_description">Short Description <span
                                        class="text-danger">*</span></label>
                                <textarea class="form-control" id="short_description" name="short_description" rows="4"
                                    placeholder="Short Description.." required>{{ old('short_description') ?? $news->short_description }}</textarea>
                                @error('short_description')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                            <div class="mb-4">
                                <label class="form-label" for="description">Description <span
                                        class="text-danger">*</span></label>
                                <textarea id="description" name="description" required>
                                    {{ old('description') ?? $news->description }}
                                </textarea>
                                @error('description')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                            <div class="mb-4">
                                <label class="form-label" for="video_url">Video Embed Url <span
                                        class="text-muted fs-xs">(Optional)</span></label>
                                <input type="text" class="form-control" id="video_url" name="video_url"
                                    placeholder="Video Embed Url" value="{{ old('video_url') ?? $news->video_url }}">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="block-header block-header-default mt-4">
                    <h3 class="block-title">Meta Information <span class="text-muted fs-xs text-normal"> (This Section is
                            Optional)</span></h3>
                </div>
                <div class="block-content block-content-full">
                    <div class="row push">
                        <div class="col-lg-10 col-xl-10 m-auto">
                            <div class="mb-4">
                                <label class="form-label" for="meta_title">Meta Title</label>
                                <input type="text" class="form-control" id="meta_title" name="meta_title"
                                    placeholder="Meta Title" value="{{ old('meta_title') ?? $news->meta?->title }}">
                            </div>
                            <div class="mb-4">
                                <label class="form-label" for="meta_description">Meta Description</label>
                                <textarea class="form-control" id="meta_description" name="meta_description" rows="4"
                                    placeholder="Meta Description.."> {{ old('meta_description') ?? $news->meta?->description }}</textarea>
                            </div>
                            <div class="mb-4">
                                <label class="form-label" for="description">Tags</label>
                                <input type="text" id="input-tags" name="tags" placeholder="Enter Tags"
                                    value="{{ old('tags') ?? $news->meta?->tags }}">
                            </div>

                        </div>
                    </div>
                </div>

                <div class="col-lg-8 col-xl-5 m-auto">
                    <div class="row items-push justify-content-center">
                        <div class="col-md-5">
                            <div class="form-check form-block m-2">
                                <input class="form-check-input" type="checkbox" id="featuredNews" name="featuredNews"
                                {{ $news->is_featured == 'featured' ? 'checked' : '' }}>
                                <label class="form-check-label" for="featuredNews">
                                    <span class="d-flex align-items-center justify-content-center">
                                        <i class="fa-solid fa-star text-warning"></i>
                                        <span class="ms-2">
                                            <span class="fw-bold">Featured News</span>
                                        </span>
                                    </span>
                                </label>
                            </div>
                        </div>
                        <div class="col-md-5">
                            <div class="form-check form-block m-2">
                                <input class="form-check-input" type="checkbox" id="hotNews" name="hotNews"
                                {{ $news->is_hot == 'hot' ? 'checked' : '' }}>
                                <label class="form-check-label" for="hotNews">
                                    <span class="d-flex align-items-center justify-content-center">
                                        <i class="fa-solid fa-fire text-danger"></i>
                                        <span class="ms-2">
                                            <span class="fw-bold">Hot News</span>
                                        </span>
                                    </span>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-8 col-xl-5 m-auto">
                    <div class="row items-push">
                        <div class="col-md-4">
                            <div class="form-check form-block">
                                <input class="form-check-input" type="radio" value="published" id="published"
                                    name="status" {{ $news->status == 'published' ? 'checked' : '' }}>
                                <label class="form-check-label" for="published">
                                    <span class="d-flex align-items-center">
                                        <i class="fa-solid fa-globe text-success"></i>
                                        <span class="ms-2">
                                            <span class="fw-bold">Published</span>
                                        </span>
                                    </span>
                                </label>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-check form-block">
                                <input class="form-check-input" type="radio" id="draft" name="status"
                                    value="draft" {{ $news->status == 'draft' ? 'checked' : '' }}>
                                <label class="form-check-label" for="draft">
                                    <span class="d-flex align-items-center">
                                        <i class="fa-solid fa-file-lines text-warning"></i>
                                        <span class="ms-2">
                                            <span class="fw-bold">Draft</span>
                                        </span>
                                    </span>
                                </label>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-check form-block">
                                <input class="form-check-input" type="radio" value="scheduled" id="scheduled"
                                    name="status" {{ $news->status == 'scheduled' ? 'checked' : '' }}>
                                <label class="form-check-label" for="scheduled">
                                    <span class="d-flex align-items-center">
                                        <i class="fa-solid fa-calendar-days text-danger"></i>
                                        <span class="ms-2">
                                            <span class="fw-bold">Scheduled</span>
                                        </span>
                                    </span>
                                </label>
                            </div>
                        </div>
                        <div class="col-md-6 m-auto d-none" id="scheduleBox">
                            <div class="form-block">
                                <label class="form-label d-block text-center" for="scheduled_at">Date Time</label>
                                <input type="datetime-local" class="form-control" id="scheduled_at" name="scheduled_at"
                                    value="{{ old('scheduled_at') ?? optional($news->scheduled_at)->format('Y-m-d\TH:i') }}">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mt-4 text-center">
                    <button type="submit" class="btn btn-primary  w-50 mt-4 mb-4">Submit</button>
                </div>
            </div>
        </form>
    </div>
@endsection
@push('footer_scripts')
    <script src="{{ asset('assets') }}/js/plugins/select2/js/select2.full.min.js"></script>
    <script>
        One.helpersOnLoad(['jq-select2']);
    </script>
    <script>
        var editor1 = new RichTextEditor("#description");
    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.15.2/js/selectize.min.js"
        integrity="sha512-IOebNkvA/HZjMM7MxL0NYeLYEalloZ8ckak+NDtOViP7oiYzG5vn6WVXyrJDiJPhl4yRdmNAG49iuLmhkUdVsQ=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script>
        $("#input-tags").selectize({
            delimiter: ",",
            persist: false,
            create: function(input) {
                return {
                    value: input,
                    text: input,
                };
            },
        });

        $(document).ready(function() {
            // ইমেজ প্রিভিউ লজিক
            $('#imageInput').change(function(e) {
                var file = this.files[0];
                if (file) {
                    var reader = new FileReader();
                    reader.onload = function(e) {
                        // প্রিভিউ ইমেজ আপডেট হবে
                        $('#imagePreview').attr('src', e.target.result).fadeIn();
                    }
                    reader.readAsDataURL(file);
                }
            });

        });
        $(document).ready(function() {
            // টাইটেল ইনপুটে কিছু লিখলেই এই ফাংশনটি কাজ করবে
            $('#newsTitle').on('input', function() {
                let title = $(this).val();

                // স্লাগ জেনারেট করার লজিক
                let slug = title.toLowerCase()
                    .trim() // শুরুর এবং শেষের বাড়তি স্পেস বাদ দেওয়া
                    .replace(/[^\w\s-]/g,
                        '') // স্পেশাল ক্যারেক্টার বাদ দেওয়া (বাংলা সাপোর্ট সহ চাইলে এটি পরিবর্তন করতে হবে)
                    .replace(/[\s_-]+/g,
                        '-') // স্পেস বা আন্ডারস্কোর থাকলে ড্যাশ (-) দিয়ে পরিবর্তন
                    .replace(/^-+|-+$/g, ''); // শুরুতে বা শেষে বাড়তি ড্যাশ থাকলে বাদ দেওয়া

                $('#slugInput').val(slug);
            });
        });
    </script>

    <script>
        $(document).ready(function() {

            $('#category_id').on('change', function() {
                let categoryId = $(this).val();
                let subCategorySelect = $('#sub_category_id');

                // reset
                subCategorySelect.empty();

                if (!categoryId) {
                    subCategorySelect.append(
                        `<option value="">No subcategory available</option>`
                    );
                    return;
                }

                $.ajax({
                    url: '/news/get-subcategories/' + categoryId,
                    type: 'GET',
                    success: function(response) {

                        if (response.length === 0) {
                            subCategorySelect.append(
                                `<option value="">No subcategory available</option>`
                            );
                        } else {
                            subCategorySelect.append(
                                `<option value="">Select Subcategory</option>`
                            );

                            $.each(response, function(index, subcategory) {
                                subCategorySelect.append(
                                    `<option value="${subcategory.id}">${subcategory.name}</option>`
                                );
                            });
                        }

                        // select2 refresh
                        subCategorySelect.trigger('change');
                    }
                });
            });

        });
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const statusRadios = document.querySelectorAll('input[name="status"]');
            const scheduleBox = document.getElementById('scheduleBox');

            function toggleSchedule() {
                const selected = document.querySelector('input[name="status"]:checked').value;

                if (selected === 'scheduled') {
                    scheduleBox.classList.remove('d-none');
                } else {
                    scheduleBox.classList.add('d-none');
                    document.getElementById('scheduled_at').value = '';
                }
            }

            // initial check
            toggleSchedule();

            // change event
            statusRadios.forEach(radio => {
                radio.addEventListener('change', toggleSchedule);
            });
        });
    </script>
@endpush
