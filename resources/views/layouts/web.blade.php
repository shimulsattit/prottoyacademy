<!DOCTYPE html>
<html class="no-js" lang="en">
	<head>
        <meta charset="utf-8" />
        <meta http-equiv="x-ua-compatible" content="ie=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1" />

        <title>{{ isset($title) ? $title : 'Prottoy Academy' }}</title>

        <meta name="robots" content="index, follow" />

		<meta name="csrf-token" content="{{ csrf_token() }}">
		<link rel="shortcut icon" href="{{ asset(get_settings('system_favicon') ?? 'portal-resource/images/favicon.ico') }}" />

        @stack('meta')

        <!-- Google Search Console Verification -->
        @if(get_settings('google_search_console_id'))
            <meta name="google-site-verification" content="{{ get_settings('google_search_console_id') }}" />
        @endif

        <!-- Google Analytics (GA4) -->
        @if(get_settings('google_analytics_id'))
            <!-- Global site tag (gtag.js) - Google Analytics -->
            <script async src="https://www.googletagmanager.com/gtag/js?id={{ get_settings('google_analytics_id') }}"></script>
            <script>
                window.dataLayer = window.dataLayer || [];
                function gtag(){dataLayer.push(arguments);}
                gtag('js', new Date());

                gtag('config', '{{ get_settings('google_analytics_id') }}');
            </script>
        @endif

        <!-- Custom Header Scripts -->
        @if(get_settings('custom_header_scripts'))
            {!! get_settings('custom_header_scripts') !!}
        @endif

        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Hind+Siliguri:wght@300;400;500;600;700&family=Noto+Serif+Bengali:wght@400;600;700;900&display=swap" rel="stylesheet">

        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-4Q6Gf2aSP4eDXB8Miphtr37CMZZQ5oXLH2yaXMJ2w8e2ZtHTl7GptT4jmndRuHDT" crossorigin="anonymous">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js" integrity="sha384-j1CDi7MgGQ12Z7Qab0qlWQ/Qqz24Gc6BM0thvEMVjHnfYGF0rmFCozFSxQBxwHKO" crossorigin="anonymous"></script>

        <link rel="stylesheet" href="{{ asset('assets/css/vendor/bootstrap.min.css') }}">
        <link rel="stylesheet" href="{{ asset('assets/css/vendor/remixicon.css') }}">
        <link rel="stylesheet" href="{{ asset('assets/css/vendor/eduvibe-font.css') }}">
        <link rel="stylesheet" href="{{ asset('assets/css/vendor/magnifypopup.css') }}">
        <link rel="stylesheet" href="{{ asset('assets/css/vendor/slick.css') }}">
        <link rel="stylesheet" href="{{ asset('assets/css/vendor/odometer.css') }}">
        <link rel="stylesheet" href="{{ asset('assets/css/vendor/lightbox.css') }}">
        <link rel="stylesheet" href="{{ asset('assets/css/vendor/animation.css') }}">
        <link rel="stylesheet" href="{{ asset('assets/css/vendor/jqueru-ui-min.css') }}">
        <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
        <link rel="stylesheet" href="{{ asset('portal-resource/css/parsley.css') }}">
        <link rel="stylesheet" href="{{ asset('portal-resource/css/toastr.min.css') }}">

        <style>
            :root {
                --deep-navy: #07091e;
                --accent-gold: #f5c518;
                --accent-orange: #ff6b35;
                --text-light: #b8c4e8;
            }
            body {
                background-color: #050714 !important;
                color: #fff !important;
                font-family: 'Hind Siliguri', sans-serif;
                overflow-x: hidden;
            }
            body::before {
                content: '';
                position: fixed; top: 0; left: 0; width: 100%; height: 100%;
                background: 
                    radial-gradient(circle at 0% 0%, rgba(0, 119, 182, 0.22) 0%, transparent 50%),
                    radial-gradient(circle at 100% 0%, rgba(167, 139, 250, 0.06) 0%, transparent 40%),
                    radial-gradient(circle at 100% 100%, rgba(245, 197, 24, 0.05) 0%, transparent 45%),
                    radial-gradient(circle at 0% 100%, rgba(30, 58, 138, 0.35) 0%, transparent 50%);
                pointer-events: none; z-index: 0;
            }
            .main-wrapper {
                background: transparent;
                position: relative;
                z-index: 1;
                overflow-x: hidden;
            }
            .bg-color-white {
                background-color: var(--deep-navy) !important;
            }
            
            /* GLOBAL RESPONSIVE FIXES */
            @media (max-width: 768px) {
                .edu-breadcrumb-area { padding-top: 100px !important; padding-bottom: 40px !important; }
                .edu-breadcrumb-area .title { font-size: 24px !important; }
                .edu-section-gap { padding-top: 40px !important; padding-bottom: 40px !important; }
            }
        </style>
        @stack('style')
	</head>
	<body>
        <div class="main-wrapper">
            @include('layouts.partials.frontend.website-header')

            @yield('content')

            @include('layouts.partials.frontend.website-footer')
        </div>

        <div class="rn-progress-parent">
            <svg class="rn-back-circle svg-inner" width="100%" height="100%" viewBox="-1 -1 102 102">
                <path d="M50,1 a49,49 0 0,1 0,98 a49,49 0 0,1 0,-98" />
            </svg>
        </div>

        <script src="{{ asset('assets/js/vendor/modernizr.min.js') }}"></script>
        <script src="{{ asset('assets/js/vendor/jquery.js') }}"></script>
        <script src="{{ asset('assets/js/vendor/bootstrap.min.js') }}"></script>
        <script src="{{ asset('assets/js/vendor/sal.min.js') }}"></script>
        <script src="{{ asset('assets/js/vendor/backtotop.js') }}"></script>
        <script src="{{ asset('assets/js/vendor/magnifypopup.js') }}"></script>
        <script src="{{ asset('assets/js/vendor/slick.js') }}"></script>
        <script src="{{ asset('assets/js/vendor/countdown.js') }}"></script>
        <script src="{{ asset('assets/js/vendor/jquery-appear.js') }}"></script>
        <script src="{{ asset('assets/js/vendor/odometer.js') }}"></script>
        <script src="{{ asset('assets/js/vendor/isotop.js') }}"></script>
        <script src="{{ asset('assets/js/vendor/imageloaded.js') }}"></script>
        <script src="{{ asset('assets/js/vendor/lightbox.js') }}"></script>
        <script src="{{ asset('assets/js/vendor/wow.js') }}"></script>
        <script src="{{ asset('assets/js/vendor/paralax.min.js') }}"></script>
        <script src="{{ asset('assets/js/vendor/paralax-scroll.js') }}"></script>
        <script src="{{ asset('assets/js/vendor/jquery-ui.js') }}"></script>
        <script src="{{ asset('assets/js/vendor/tilt.jquery.min.js') }}"></script>
        <script src="{{ asset('portal-resource/js/parsley.min.js') }}"></script>
        <script src="{{ asset('portal-resource/js/toastr.min.js') }}"></script>
        <script src="{{ asset('portal-resource/js/toastr.min.js') }}"></script>
		<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script src="{{ asset('assets/js/main.js') }}"></script>
        <script>
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
        </script>
        <!-- Custom Footer/Body Scripts -->
        @if(get_settings('custom_footer_scripts'))
            {!! get_settings('custom_footer_scripts') !!}
        @endif

        @stack('scripts')
    </body>
</html>
