@extends('backend.layouts.app')
@section('title', 'Subcategories')
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
            <div class="col-lg-8 m-auto mt-4">
                <div class="block block-rounded">
                    <div class="block-header block-header-default">
                        <h3 class="block-title">
                            Subcategory List
                        </h3>
                    </div>
                    <div class="block-content block-content-full overflow-x-auto">
                        <table class="table table-bordered  table-vcenter" id="categoryTable">
                            <thead>
                                <tr>
                                    <th class="text-center">SL</th>
                                    <th>Subcategory</th>
                                    <th>Slug</th>
                                    <th>Category</th>
                                    <th>Priority</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($subcategories as $key => $subcategory)
                                    <tr>
                                        <td class="text-center fs-sm">{{ $key + 1 }}</td>
                                        <td class="fw-semibold fs-sm">{{ $subcategory->name }}</td>
                                        <td class="fw-semibold fs-sm">{{ $subcategory->slug }}</td>
                                        <td class="fs-sm fw-bold text-success text-center">{{ $subcategory->categories->name }}</td>
                                        <td class="fw-semibold fs-sm">{{ $subcategory->priority }}</td>
                                        <td class="fw-semibold fs-sm">
                                            <div class="form-check form-switch text-center">
                                                <input class="form-check-input" id="status" type="checkbox"
                                                    {{ $subcategory->status == '1' ? 'checked' : '' }} name="status"
                                                    data-id="{{ $subcategory->id }}"
                                                    data-status="{{ $subcategory->status }}"
                                                    onchange="updateSubcategoryStatus(this)">
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <button type="button" class="border-0 btn btn-sm editSubCategoryBtn"
                                                data-id="{{ $subcategory->id }}" data-name="{{ $subcategory->name }}"
                                                data-slug="{{ $subcategory->slug }}"
                                                data-priority="{{ $subcategory->priority }}">
                                                <i class="fa fa-pencil text-secondary fa-xl"></i>
                                            </button>
                                            <button type="button" class="border-0 btn btn-sm"
                                                onclick="deleteSubcategory(this)" data-id="{{ $subcategory->id }}"><i
                                                    class="fa fa-trash text-danger fa-xl"></i></button>
                                        </td>
                                    </tr>
                                @endforeach

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 m-auto mt-4">
                <div class="block block-rounded">
                    <div class="block-header block-header-default">
                        <h3 class="block-title">
                            Add New Sub Category
                        </h3>
                    </div>
                    <div class="block-content block-content-full overflow-x-auto">
                        <form action="{{ route('subcategory.store') }}" method="POST">
                            @csrf
                            <div class="mb-4">
                                <label class="form-label" for="category">Select Category </label>
                                <select class="form-control" id="category" name="category" required>
                                    <option value="" disabled selected>Select Category</option>
                                    @foreach ($categories as $category)
                                        <option value="{{ $category->id }}"
                                            {{ old('category') == $category->id ? 'selected' : '' }}>{{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('category')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                            <div class="mb-4">
                                <label class="form-label" for="subcategory_name">Sub Category Name</label>
                                <input type="text" class="form-control" id="subcategory_name" name="subcategory_name"
                                    placeholder="Enter subcategory name.." value="{{ old('subcategory_name') }}" required>
                                @error('subcategory_name')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                            <div class="mb-0 text-center">
                                <button type="submit" class="btn btn-primary w-50 mt-4">Submit</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <!-- /.col -->
        </div>
        <!-- /.row -->
    </div>
    <!-- /.container-fluid -->
    <!-- /.content -->

    <div class="modal fade" id="editSubCategoryModal" tabindex="-1" data-bs-backdrop="static">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title">Edit Sub Category</h5>
                </div>

                <div class="modal-body">
                    <input type="hidden" id="edit_subcategory_id">

                    <div class="mb-3">
                        <label>Sub Category Name</label>
                        <input type="text" id="edit_name" class="form-control">
                        <small class="text-danger d-none" id="nameError"></small>
                    </div>

                    <div class="mb-3">
                        <label>Slug</label>
                        <input type="text" id="edit_slug" class="form-control">
                        <small class="text-danger d-none" id="slugError"></small>
                    </div>

                    <div class="mb-3">
                        <label>Priority</label>
                        <input type="number" id="edit_priority" class="form-control">
                    </div>
                </div>

                <div class="modal-footer">
                    <button class="btn btn-secondary" id="closeEditModal">Cancel</button>
                    <button class="btn btn-primary" id="updateSubCategoryBtn">Update</button>
                </div>

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
        $('#categoryTable').DataTable();
    </script>

    <!-- Page specific script -->
    <script>
        function deleteSubcategory(button) {
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
                    let url = "{{ route('subcategory.destroy', ':id') }}";
                    url = url.replace(':id', id);
                    let token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

                    $.ajax({
                        url: url,
                        type: 'DELETE',
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

        function updateSubcategoryStatus(element) {

            Swal.fire({
                title: "Are you sure?",
                text: "Will update subcategory status",
                icon: "warning",
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                showCancelButton: true,
                confirmButtonText: "Yes, Update it!"
            }).then((result) => {

                if (!result.isConfirmed) {
                    element.checked = !element.checked;
                    return;
                }

                let id = $(element).data('id');
                let url = "{{ route('subcategory.status.update', ':id') }}".replace(':id', id);

                $.ajax({
                    url: url,
                    type: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(res) {
                        showToast(res.message, res.success ? 'success' : 'error');
                    }
                });
            });
        }
    </script>

    <script>
        // 🔹 Edit Sub Category button click
        $(document).on('click', '.editSubCategoryBtn', function() {

            $('#edit_subcategory_id').val($(this).data('id'));
            $('#edit_name').val($(this).data('name'));
            $('#edit_slug').val($(this).data('slug'));
            $('#edit_priority').val($(this).data('priority'));

            resetErrors();
            $('#editSubCategoryModal').modal('show');
        });

        // 🔹 Cancel
        $('#closeEditModal').on('click', function() {
            $('#editSubCategoryModal').modal('hide');
        });

        // 🔹 Reset errors
        function resetErrors() {
            $('#nameError, #slugError').addClass('d-none').text('');
            $('#edit_name, #edit_slug').removeClass('is-invalid');
        }

        // 🔹 Input change hide errors
        $('#edit_name, #edit_slug').on('input', function() {
            resetErrors();
        });

        // 🔹 Update Sub Category
        $('#updateSubCategoryBtn').on('click', function() {

            let id = $('#edit_subcategory_id').val();

            resetErrors();

            $.ajax({
                url: "{{ route('subcategory.update') }}",
                type: "POST",
                data: {
                    _token: "{{ csrf_token() }}",
                    id: id,
                    name: $('#edit_name').val().trim(),
                    slug: $('#edit_slug').val().trim(),
                    priority: $('#edit_priority').val()
                },
                beforeSend: function() {
                    $('#updateSubCategoryBtn')
                        .prop('disabled', true)
                        .text('Updating...');
                },
                success: function(res) {

                    if (res.errors) {
                        if (res.errors.name) {
                            $('#nameError').removeClass('d-none').text(res.errors.name[0]);
                            $('#edit_name').addClass('is-invalid');
                        }
                        if (res.errors.slug) {
                            $('#slugError').removeClass('d-none').text(res.errors.slug[0]);
                            $('#edit_slug').addClass('is-invalid');
                        }
                        return;
                    }

                    if (res.success) {
                        location.reload(); // session toast will show
                    }
                },
                complete: function() {
                    $('#updateSubCategoryBtn')
                        .prop('disabled', false)
                        .text('Update');
                }
            });
        });
    </script>
@endpush
