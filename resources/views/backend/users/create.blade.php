@extends('backend.layouts.app')
@section('title', 'Create New User')

@section('content')
    <div class="container-fluid">
        <div class="row push">
            <div class="col-lg-12 col-xl-10  m-auto">
                <form action="{{ route('users.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="block block-rounded">
                        <div class="block-header block-header-default">
                            <h3 class="block-title">Create User</h3>
                        </div>
                        <div class="block-content block-content-full">
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="mb-4">
                                        <label class="form-label" for="name">Name<span
                                                class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="name" name="name"
                                            placeholder="Name" value="{{ old('name') }}" required>
                                        @error('name')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>

                                    <div class="mb-4">
                                        <label class="form-label" for="email">Email<span
                                                class="text-danger">*</span></label>
                                        <input type="email" class="form-control" id="email" name="email"
                                            placeholder="Email" value="{{ old('email') }}" required>
                                        @error('email')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>

                                    <div class="mb-4">
                                        <label class="form-label" for="password">Password<span
                                                class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <input type="password" class="form-control" id="password" name="password"
                                                placeholder="Password" value="{{ old('password') }}" required>

                                            <!-- Show / Hide Button -->
                                            <span type="button" class="input-group-text" id="togglePassword">
                                                <i class="fas fa-eye"></i>
                                            </span>
                                        </div>

                                        @error('password')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>

                                    <div class="mb-4">
                                        <label class="form-label" for="password_confirmation">Confirm Password<span
                                                class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <input type="password" class="form-control" id="password_confirmation"
                                                name="password_confirmation" placeholder="Confirm Password"
                                                value="{{ old('password_confirmation') }}" required>

                                            <!-- Show / Hide Button -->
                                            <span type="button" class="input-group-text" id="toggleConfirmPassword">
                                                <i class="fas fa-eye"></i>
                                            </span>
                                        </div>
                                        @error('password_confirmation')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="mb-4 text-center">
                                        <div class="image-upload-wrapper text-center mb-3">
                                            <div class="preview-box border rounded position-relative d-flex align-items-center justify-content-center mx-auto"
                                                style="height: 300px; width: 300px; background: #f0f2f5; overflow: hidden;">
                                                <img id="imagePreview"
                                                    src="https://placehold.co/300x300/d8f2e7/000?text=Profile+Image"
                                                    class="img-fluid"
                                                    style="max-height: 100%; max-width: 100%; object-fit: contain; display: block;">

                                                <label for="imageInput"
                                                    class="btn btn-primary rounded-circle position-absolute"
                                                    style="bottom: 10px; right: 10px; width: 30px; height: 30px; padding: 0px; cursor: pointer; z-index: 10;">
                                                    <i class="fas fa-cloud-upload-alt fs-xs"></i>
                                                </label>
                                                <input type="file" id="imageInput" accept=".png, .jpg, .jpeg" hidden
                                                    name="image">
                                            </div>

                                            <small class="text-muted">
                                                <span class="d-block mt-2">Supported Files: <b>.png, .jpg, .jpeg</b>.</span>
                                                <span class="d-block">Image will be resized into <b>400x400px</b></span>
                                            </small>
                                            @error('image')
                                                <small class="text-danger mt-2 d-block">
                                                    {{ $message }}
                                                </small>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="text-center">
                                <button type="submit" class="btn btn-primary  w-50 mb-2 mt-4">Submit</button>
                            </div>
                        </div>
                    </div>


            </div>
        </div>
        </form>
    </div>
@endsection
@push('footer_scripts')
    <script>
        $(document).ready(function() {
            $('#imageInput').change(function(e) {
                var file = this.files[0];
                if (file) {
                    var reader = new FileReader();
                    reader.onload = function(e) {
                        $('#imagePreview').attr('src', e.target.result).fadeIn();
                    }
                    reader.readAsDataURL(file);
                }
            });
        });
    </script>

    <script>
        $(document).ready(function() {
            $('#togglePassword').on('click', function() {
                let passwordInput = $('#password');
                let icon = $(this).find('i');

                if (passwordInput.attr('type') === 'password') {
                    passwordInput.attr('type', 'text');
                    icon.removeClass('fa-eye').addClass('fa-eye-slash');
                } else {
                    passwordInput.attr('type', 'password');
                    icon.removeClass('fa-eye-slash').addClass('fa-eye');
                }
            });
        });
        $(document).ready(function() {
            $('#toggleConfirmPassword').on('click', function() {
                let passwordInput = $('#password_confirmation');
                let icon = $(this).find('i');

                if (passwordInput.attr('type') === 'password') {
                    passwordInput.attr('type', 'text');
                    icon.removeClass('fa-eye').addClass('fa-eye-slash');
                } else {
                    passwordInput.attr('type', 'password');
                    icon.removeClass('fa-eye-slash').addClass('fa-eye');
                }
            });
        });
    </script>
@endpush
