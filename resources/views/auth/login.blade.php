<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1.0">
    <title>Sign In | {{ config('app.name') }}</title>

    <link rel="shortcut icon" href="{{ asset('assets') }}/media/favicons/favicon.png">
    <link rel="icon" type="image/png" sizes="192x192" href="{{ asset('assets') }}/media/favicons/favicon-192x192.png">
    <link rel="apple-touch-icon" sizes="180x180"
        href="{{ asset('assets') }}/media/favicons/apple-touch-icon-180x180.png">
    <link rel="stylesheet" id="css-main" href="{{ asset('assets') }}/css/oneui.min-5.9.css">
        <!-- Extra CSS Libraries -->
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">

</head>

<body>
    <div id="page-container">
        <main id="main-container">
            <div class="hero-static d-flex align-items-center">
                <div class="content">
                    <div class="row justify-content-center push">
                        <div class="col-md-8 col-lg-6 col-xl-4">
                            <div class="block block-rounded mb-0">
                                <div class="block-header block-header-default">
                                    <h3 class="block-title">Sign In</h3>
                                </div>
                                <div class="block-content">
                                    <div class="p-sm-3 px-lg-4 px-xxl-5 py-lg-5">
                                        <h1 class="h2 mb-1">{{ config('app.name') }}</h1>
                                        <p class="fw-medium text-muted">
                                            Welcome, please login.
                                        </p>
                                        <form class="js-validation-signin" action="{{ route('login') }}" method="POST">
                                            @csrf
                                            <div class="py-3">
                                                <div class="mb-4">
                                                    <input type="email"
                                                        class="form-control form-control-alt form-control-lg"
                                                        id="email" name="email" placeholder="Enter Email"
                                                        value="{{ old('email') ?? Cookie::get('remember_email') }}">
                                                    @error('email')
                                                        <small class="text-danger">{{ $message }}</small>
                                                    @enderror
                                                </div>
                                                <div class="mb-4">
                                                    <input type="password"
                                                        class="form-control form-control-alt form-control-lg"
                                                        id="login-password" name="password" placeholder="Password"
                                                        value="{{ Cookie::get('remember_password') ? decrypt(Cookie::get('remember_password')) : '' }}">
                                                    @error('password')
                                                        <small class="text-danger">{{ $message }}</small>
                                                    @enderror
                                                </div>
                                                <div class="mb-4">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox" id="remember"
                                                            name="remember"
                                                            {{ Cookie::get('remember_me') ? 'checked' : '' }}>
                                                        <label class="form-check-label" for="remember">Remember
                                                            Me</label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row mb-4">
                                                <div class="col-md-6 col-xl-5">
                                                    <button type="submit" class="btn w-100 btn-alt-primary">
                                                        <i class="fa fa-fw fa-sign-in-alt me-1 opacity-50"></i> Sign In
                                                    </button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="fs-sm text-muted text-center">
                        <strong>{{ config('app.name') }}</strong> &copy; <span data-toggle="year-copy"></span>
                    </div>
                </div>
            </div>
        </main>
    </div>
    <script src="{{ asset('assets') }}/js/oneui.app.min-5.9.js"></script>
    <script src="{{ asset('assets') }}/js/lib/jquery.min.js"></script>
    <script src="{{ asset('assets') }}/js/plugins/jquery-validation/jquery.validate.min.js"></script>
    <script src="{{ asset('assets') }}/js/pages/op_auth_signin.min.js"></script>

    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/toastify-js"></script>
    <script>
        // Ajax setup
        const csrf = $('meta[name="csrf-token"]').attr('content');
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': csrf
            }
        });


        // Toast
        function showToast(text, type = 'success') {
            let bg;
            switch (type) {
                case 'error':
                    from = '#ff5b5c';
                    to = '#ff5b5c';
                    break;
                case 'success':
                    from = '#00b09b';
                    to = '#96c93d';
                    break;
                default:
                    from = '#00b09b';
                    to = '#96c93d';
                    break;
            }
            console.log(type, bg);

            Toastify({
                text,
                duration: 3000,
                gravity: "top",
                position: "right",
                close: true,
                stopOnFocus: true,
                style: {
                    background: `linear-gradient(to right, ${from}, ${to})`
                },
                onClick: function() {}
            }).showToast();
        }
    </script>

    @if (session('error'))
        <script>
            showToast('{{ session('error') }}', 'error');
        </script>
    @endif
</body>

</html>
