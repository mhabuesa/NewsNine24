@extends('backend.layouts.app')
@section('title', 'News List')
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
                        <h3 class="block-title">
                            Users List
                        </h3>
                        <div class="block-options space-x-1">

                            <a href="{{ route('users.create') }}" class="badge bg-primary p-2"> <i class="fa fa-plus"></i>
                                Add
                                New User </a>
                        </div>
                    </div>
                    <div class="block-content block-content-full overflow-x-auto">
                        <table class="table table-bordered table-striped table-vcenter" id="newsTable">
                            <thead>
                                <tr class="text-center">
                                    <th class="">SL</th>
                                    <th class="">Image</th>
                                    <th class="">Name</th>
                                    <th class="">Email</th>
                                    <th class="">Date</th>
                                    <th class="">Action</th>
                                </tr>
                            </thead>
                            <tbody>

                                @forelse ($users as $key => $user)
                                    <tr>
                                        <td class="text-center fs-sm">{{ $key + 1 }}</td>
                                        <td class="fw-semibold fs-sm">
                                            @if ($user->image)
                                                <img src="{{ asset($user->image) }}" alt="User Image"
                                                    class="img-fluid rounded" style="width: 50px; height: 50px;">
                                            @else
                                                <img src="https://placehold.co/50x50/d8f2e7/000?text=No+Image"
                                                    alt="No Image" class="img-fluid rounded">
                                            @endif
                                        </td>
                                        <td class="fw-semibold fs-sm">{{ $user->name }}</td>
                                        <td class="fw-semibold fs-sm">{{ $user->email }}</td>
                                        <td class="fw-semibold fs-sm">{{ $user->created_at->format('d-m-Y') }}</td>
                                        <td class="text-center">
                                            <div class="d-block">
                                                @if ($user->id == auth()->user()->id)
                                                <span class="badge bg-warning p-2"> Not Allowed </span>
                                                @else
                                                    <a href="{{ route('users.edit', $user->id) }}"
                                                        class="border-0 btn btn-sm">
                                                        <i class="fa fa-pencil text-secondary fa-xl"></i>
                                                    </a>
                                                    <button type="button" class="border-0 btn btn-sm"
                                                        onclick="deleteUser(this)" data-id="{{ $user->id }}"><i
                                                            class="fa fa-trash text-danger fa-xl"></i></button>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center">No Contribution Found!</td>
                                    </tr>
                                @endforelse

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


    <!-- Page specific script -->
    <script>
        function deleteUser(button) {
            const id = $(button).data('id');
            Swal.fire({
                title: "Are you sure?",
                text: "This User will be Terminated Permanently.",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#f97316",
                cancelButtonColor: "#d33",
                confirmButtonText: "Yes, Terminate!"
            }).then((result) => {
                if (result.isConfirmed) {
                    let url = "{{ route('users.destroy', ':id') }}";
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
    </script>
@endpush
