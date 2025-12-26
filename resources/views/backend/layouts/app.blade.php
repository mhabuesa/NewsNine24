<!doctype html>
<html lang="en">

<head>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta charset="utf-8" />
    <meta name="viewport"
        content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />
    <title>@yield('title', 'App') | {{ config('app.name', 'Digonto') }}</title>



    <meta name="owner" content="{{ config('app.name', 'Digonto') }}" />
    <meta name="type" content="website" />
    <meta name="url" content="{{ url()->full() }}" />
    <meta name="site_name" content="{{ config('app.name', 'Digonto') }}" />

    <meta property="og:type" content="website" />
    <meta property="og:url" content="{{ url()->full() }}" />
    <meta property="og:site_name" content="{{ config('app.name', 'Digonto') }}" />

    <!-- Favicons -->
    <link rel="icon" type="image/png" href="{{ asset('assets') }}/icon/favicon.png">
    <link rel="apple-touch-icon" href="{{ asset('assets') }}/icon/favicon.png">

    <meta name="title"
        content="{{ config('app.name', 'Digonto') }} | Your trusted partner in investment and financial growth." />
    <meta name="description"
        content="{{ config('app.name', 'Digonto') }} | Your trusted partner in investment and financial growth. We are committed to providing our valued members with timely profit distributions, transparent updates, and continuous support to help everyone achieve sustainable financial success. Stay connected and grow with us!" />
    <meta name="keywords" content=" Digonto, Digonto Foundation" />
    <meta property="og:title"
        content="{{ config('app.name', 'Digonto') }} | Your trusted partner in investment and financial growth." />
    <meta property="og:description"
        content="{{ config('app.name', 'Digonto') }} | Your trusted partner in investment and financial growth. We are committed to providing our valued members with timely profit distributions, transparent updates, and continuous support to help everyone achieve sustainable financial success. Stay connected and grow with us!" />
    <meta property="og:image" content="{{ asset('assets') }}/icon/favicon.png" />


    <link rel="stylesheet" id="css-main" href="{{ asset('assets') }}/css/oneui.min-5.9.css">
    <link rel="stylesheet" id="css-main" href="{{ asset('assets') }}/css/custom.css">

    <!-- Extra CSS Libraries -->
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">

    <!-- Main CSS -->
    <link rel="stylesheet" id="css-main" href="{{ asset('assets') }}/css/oneui.min-5.9.css">
    @stack('style')
</head>

<body>
    <div id="page-container"
        class="sidebar-o sidebar-dark enable-page-overlay side-scroll page-header-fixed main-content-narrow">

        @include('backend.layouts.partials.sidebar')
        @include('backend.layouts.partials.header')

        <main id="main-container">

            <div class="content">
                @yield('content')
            </div>
        </main>

        <footer id="page-footer" class="bg-body-light">
            <div class="content py-3">
                <div class="row fs-sm">
                    <div class="col-sm-6 order-sm-2 py-1 text-center text-sm-end">
                        Crafted with <i class="fa fa-heart text-danger"></i> by <a class="fw-semibold"
                            href="https://devhunter.dev" target="_blank">DevHunter</a>
                    </div>
                    <div class="col-sm-6 order-sm-1 py-1 text-center text-sm-start">
                        <a class="fw-semibold" href="{{ url('/') }}">{{ config('app.name') }}</a> &copy; <span
                            data-toggle="year-copy"></span>
                    </div>
                </div>
            </div>
        </footer>
    </div>

    <!-- Extra JS -->
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/toastify-js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Main JS -->
    <script src="{{ asset('assets') }}/js/oneui.app.min-5.9.js"></script>
    <!-- jQuery JS -->
    <script src="{{ asset('assets') }}/js/lib/jquery.min.js"></script>


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

    @session('success')
    <script>
        showToast('{{ session('success') }}', 'success');
    </script>
    @endif

    @if (session('error'))
        <script>
            showToast('{{ session('error') }}', 'error');
        </script>
    @endif

    @stack('footer_scripts')


</body>

</html>
