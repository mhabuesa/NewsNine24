@extends('backend.layouts.app')
@section('title', 'Advertise List')
@push('style')
    <link rel="stylesheet" href="{{ asset('assets') }}/js/plugins/datatables-bs5/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="{{ asset('assets') }}/js/plugins/datatables-buttons-bs5/css/buttons.bootstrap5.min.css">
    <link rel="stylesheet"
        href="{{ asset('assets') }}/js/plugins/datatables-responsive-bs5/css/responsive.bootstrap5.min.css">
@endpush
@section('content')

    <!-- Main content -->
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12 m-auto mt-4">
                <div class="block block-rounded">
                    <div class="block-header block-header-default">
                        <h3 class="block-title">Add New Advertise</h3>
                        <div class="block-options">
                            <button type="button" class="btn-block-option" data-bs-dismiss="modal">
                                <i class="fa fa-fw fa-times"></i>
                            </button>
                        </div>
                    </div>
                    <div class="block-content block-content-full overflow-x-auto">
                        <form action="{{ route('advertise.store') }}" method="POST" enctype="multipart/form-data">
                            <div class="row">
                                <div class="col-lg-6">
                                    @csrf
                                    <div class="block block-rounded block-transparent mb-0">
                                        <!-- Header -->
                                        <!-- Body -->
                                        <div class="block-content fs-sm">
                                            <!-- Title -->
                                            <div class="mb-3">
                                                <label class="form-label">Placement</label>
                                                <select name="place" class="js-select2 form-select" id=""
                                                    required>
                                                    <option value="">Select Placement</option>
                                                    <option value="home_top">Home Top</option>
                                                    <option value="home_down">Home Down</option>
                                                    <option value="news_top">News Top</option>
                                                    <option value="news_top">News Middle</option>
                                                    <option value="news_down">News Down</option>
                                                    <option value="category_top">Category Top</option>
                                                    <option value="category_down">Category Down</option>
                                                    <option value="common">Common</option>
                                                </select>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Title <small
                                                        class="text-mute">(Optional)</small></label>
                                                <input type="text" name="title" class="form-control"
                                                    placeholder="Enter advertise title" required>
                                            </div>

                                            <!-- Link -->
                                            <div class="mb-3">
                                                <label class="form-label">Link</label>
                                                <input type="url" name="link" class="form-control"
                                                    placeholder="https://example.com" required>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <!-- Image Holder -->
                                    <div class="mb-3 text-center">
                                        <div class="preview-box border rounded position-relative d-flex align-items-center justify-content-center"
                                            style="background: #f0f2f5; overflow: hidden;">
                                            <img id="imagePreview"
                                                src="https://placehold.co/800x300/d8f2e7/000?text=News+Nine+24"
                                                class="img-fluid"
                                                style="max-height: 100%; max-width: 100%; object-fit: contain; display: block;">

                                            <label for="imageInput" class="btn btn-primary rounded-circle position-absolute"
                                                style="bottom: 10px; right: 10px; width: 40px; height: 40px; padding: 8px; cursor: pointer; z-index: 10;">
                                                <i class="fas fa-cloud-upload-alt"></i>
                                            </label>
                                            <input type="file" id="imageInput" accept=".png, .jpg, .jpeg" hidden
                                                name="image">
                                        </div>
                                        <small class="text-muted mt-2 d-block">
                                            Supported: .png, .jpg, .jpeg | Recommended: 180×180px
                                        </small>

                                    </div>
                                </div>
                                <div class="col-lg-12 text-center">
                                    <button type="submit" class="btn btn-sm btn-primary w-25 mt-4">
                                        Save Advertise
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <!-- /.col -->
        </div>
        <div class="row">
            <div class="col-lg-12 m-auto mt-4">
                <div class="block block-rounded">
                    <div class="block-header block-header-default">
                        <h3 class="block-title">
                            Trash News List
                        </h3>
                        <a href="javascript:void(0)" class="badge bg-primary float-end p-2" data-bs-toggle="modal"
                            data-bs-target="#addAdvertise"> <i class="fa fa-plus"></i> New Advertise</a>
                    </div>
                    <div class="block-content block-content-full overflow-x-auto">
                        <table class="table table-bordered table-striped table-vcenter" id="advertiseTable">
                            <thead>
                                <tr>
                                    <th class="text-center">SL</th>
                                    <th>Image</th>
                                    <th>Placement</th>
                                    <th>Title</th>
                                    <th>Link</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>


                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <!-- /.col -->
        </div>
        <!-- /.row -->
    </div>
    <!-- /.container-fluid -->
    <!-- /.content -->

    <div class="modal fade" id="addAdvertise" tabindex="-1" data-bs-backdrop="static">
        <div class="modal-dialog modal-dialog-slideright">
            <div class="modal-content">

                <form action="{{ route('advertise.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="block block-rounded block-transparent mb-0">
                        <!-- Header -->
                        <div class="block-header block-header-default">
                            <h3 class="block-title">Add New Advertise</h3>
                            <div class="block-options">
                                <button type="button" class="btn-block-option" data-bs-dismiss="modal">
                                    <i class="fa fa-fw fa-times"></i>
                                </button>
                            </div>
                        </div>

                        <!-- Body -->
                        <div class="block-content fs-sm">

                            <!-- Image Holder -->
                            <div class="mb-3 text-center">
                                <div class="preview-box border rounded position-relative d-flex align-items-center justify-content-center"
                                    style="background: #f0f2f5; overflow: hidden;">
                                    <img id="imagePreview" src="https://placehold.co/900x500/d8f2e7/000?text=News+Nine+24"
                                        class="img-fluid"
                                        style="max-height: 100%; max-width: 100%; object-fit: contain; display: block;">

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
                            </div>

                            <!-- Title -->
                            <div class="mb-3">
                                <label class="form-label">Placement</label>
                                <select name="place" class="js-select2 form-select" id="">
                                    <option value="">Select Placement</option>
                                    <option value="home_top">Home Top</option>
                                    <option value="home_down">Home Down</option>
                                    <option value="news_top">News Top</option>
                                    <option value="news_top">News Middle</option>
                                    <option value="news_down">News Down</option>
                                    <option value="category_top">Category Top</option>
                                    <option value="category_down">Category Down</option>
                                    <option value="common">Common</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Title <small class="text-mute">(Optional)</small></label>
                                <input type="text" name="title" class="form-control"
                                    placeholder="Enter advertise title" required>
                            </div>

                            <!-- Link -->
                            <div class="mb-3">
                                <label class="form-label">Link</label>
                                <input type="url" name="link" class="form-control"
                                    placeholder="https://example.com" required>
                            </div>

                        </div>

                        <!-- Footer -->
                        <div class="block-content block-content-full text-end bg-body">
                            <button type="button" class="btn btn-sm btn-alt-secondary me-1" data-bs-dismiss="modal">
                                Close
                            </button>
                            <button type="submit" class="btn btn-sm btn-primary">
                                Save Advertise
                            </button>
                        </div>
                    </div>
                </form>

            </div>
        </div>
    </div>


@endsection

@push('footer_scripts')
    <!-- DataTables  & Plugins -->
    <script src="{{ asset('assets') }}/js/plugins/datatables/dataTables.min.js"></script>
    <script src="{{ asset('assets') }}/js/plugins/datatables-bs5/js/dataTables.bootstrap5.min.js"></script>
    <script src="{{ asset('assets') }}/js/plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
    <script src="{{ asset('assets') }}/js/plugins/datatables-responsive-bs5/js/responsive.bootstrap5.min.js"></script>
    <script src="{{ asset('assets') }}/js/plugins/datatables-buttons/dataTables.buttons.min.js"></script>
    <script src="{{ asset('assets') }}/js/plugins/datatables-buttons-bs5/js/buttons.bootstrap5.min.js"></script>
    <script src="{{ asset('assets') }}/js/plugins/datatables-buttons-jszip/jszip.min.js"></script>
    <script src="{{ asset('assets') }}/js/plugins/datatables-buttons-pdfmake/pdfmake.min.js"></script>
    <script src="{{ asset('assets') }}/js/plugins/datatables-buttons-pdfmake/vfs_fonts.js"></script>
    <script src="{{ asset('assets') }}/js/plugins/datatables-buttons/buttons.print.min.js"></script>
    <script src="{{ asset('assets') }}/js/plugins/datatables-buttons/buttons.html5.min.js"></script>
    <script src="{{ asset('assets') }}/js/pages/be_tables_datatables.min.js"></script>

    <script>
        $('#advertiseTable').DataTable();
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
    </script>

    <!-- Page specific script -->
    <script>
        function restorenews(button) {
            const id = $(button).data('id');
            Swal.fire({
                title: "Are you sure?",
                text: "Restore this news?",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#16a34a",
                cancelButtonColor: "#d33",
                confirmButtonText: "Yes, Restore it!"
            }).then((result) => {
                if (result.isConfirmed) {
                    let url = "{{ route('news.restore', ':id') }}";
                    url = url.replace(':id', id);
                    let token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

                    $.ajax({
                        url: url,
                        type: 'get',
                        dataType: 'json',
                        headers: {
                            'X-CSRF-TOKEN': token
                        },
                        success: function(data) {
                            if (data.success) {
                                showToast(data.message, "success");
                                $(button).closest('tr').remove();
                            } else {
                                showToast(data.message, "error");
                            }
                        },
                        error: function(xhr) {
                            showToast("An error occurred: " + xhr.responseJSON.message, "error");
                        }
                    });
                }
            });
        }

        function deletenews(button) {
            const id = $(button).data('id');
            Swal.fire({
                title: "Are you sure?",
                text: "You won't be able to revert this!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Yes, delete it!"
            }).then((result) => {
                if (result.isConfirmed) {
                    let url = "{{ route('news.permanentlydelete', ':id') }}";
                    url = url.replace(':id', id);
                    let token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

                    $.ajax({
                        url: url,
                        type: 'get',
                        dataType: 'json',
                        headers: {
                            'X-CSRF-TOKEN': token
                        },
                        success: function(data) {
                            if (data.success) {
                                showToast(data.message, "success");
                                $(button).closest('tr').remove();
                            } else {
                                showToast(data.message, "error");
                            }
                        },
                        error: function(xhr) {
                            showToast("An error occurred: " + xhr.responseJSON.message, "error");
                        }
                    });
                }
            });
        }
    </script>
@endpush
