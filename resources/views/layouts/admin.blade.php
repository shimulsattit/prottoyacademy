<!DOCTYPE html>
<html lang="en">
	<head>
		<title>{{ isset($title) ? $title : 'Laravel Admin Template' }}</title>
		
        <meta charset="utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1" />
		
		<meta name="csrf-token" content="{{ csrf_token() }}">
		<link rel="shortcut icon" href="{{ asset(get_settings('system_favicon') ?? 'portal-resource/images/favicon.ico') }}" />
		<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Inter:300,400,500,600,700" />
		
        <link rel="stylesheet" type="text/css" href="{{ asset('portal-resource/css/plugins.bundle.css') }}" />
		<link rel="stylesheet" type="text/css" href="{{ asset('portal-resource/css/style.bundle.css') }}" />
		<link rel="stylesheet" type="text/css" href="{{ asset('portal-resource/css/select2.min.css') }}" />
		<link rel="stylesheet" type="text/css" href="{{ asset('portal-resource/css/sweetalert.css') }}" />
		<link rel="stylesheet" type="text/css" href="{{ asset('portal-resource/css/parsley.css') }}" />
		<link rel="stylesheet" type="text/css" href="{{ asset('portal-resource/css/toastr.min.css') }}" />
        @stack('style')
	</head>

	<body id="app_body" data-app-layout="light-sidebar" data-app-header-fixed="true" data-app-sidebar-enabled="true" data-app-sidebar-fixed="true" data-app-sidebar-hoverable="true" data-app-sidebar-push-header="true" data-app-sidebar-push-toolbar="true" data-app-sidebar-push-footer="true" data-app-toolbar-enabled="true" class="app-default">
		<section class="d-flex flex-column flex-root app-root" id="app_root">
			<div class="app-page flex-column flex-column-fluid" id="app_page">
                @include('layouts.partials.admin.header')

                <div class="app-wrapper flex-column flex-row-fluid" id="app_wrapper">
                    @include('layouts.partials.admin.sidebar')

                    <div class="app-main flex-column flex-row-fluid" id="kt_app_main">
						<main class="d-flex flex-column flex-column-fluid">
							@yield('content')
						</main>

                        @include('layouts.partials.admin.footer')
					</div>
				</div>
			</div>
		</section>

		<div id="scrolltop" class="scrolltop" data-scrolltop="true">
			<i class="ki-duotone ki-arrow-up">
				<span class="path1"></span>
				<span class="path2"></span>
			</i>
		</div>

		@if(isset($modal))
			<div id="modal_remote" class="modal fade border-top-success rounded-top-0" data-backdrop="static" role="dialog">
				<div class="modal-dialog modal-{{ $modal }} modal-dialog-centered">
					<div class="modal-content">

					</div>
				</div>
			</div>
		@endif

		<script>var hostUrl = "assets/";</script>
		<script src="{{ asset('portal-resource/js/plugins.bundle.js') }}"></script>
		<script src="{{ asset('portal-resource/js/scripts.bundle.js') }}"></script>
		<script src="{{ asset('portal-resource/js/select2.min.js') }}"></script>
		<script src="{{ asset('portal-resource/js/parsley.min.js') }}"></script>
		<script src="{{ asset('portal-resource/js/toastr.min.js') }}"></script>
		<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

		<script src="{{ asset('portal-resource/js/main.js') }}"></script>
        @stack('scripts')
	</body>
</html>
