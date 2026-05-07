<!DOCTYPE html>
<html class="h-full" data-theme="true" data-theme-mode="light" dir="ltr" lang="en">
    <head>
        <title>{{ isset($title) ? $title : 'Portal Panel' }} - {{ get_settings('system_name') ?? 'Prottoy Academy' }}</title>
        <meta charset="utf-8"/>
        <meta content="width=device-width, initial-scale=1, shrink-to-fit=no" name="viewport"/>
		<link rel="shortcut icon" href="{{ asset(get_settings('system_favicon') ?? 'portal-resource/images/favicon.ico') }}" />

        @stack('meta')

        <!-- Fav Icons -->
        <link href="{{ asset('portal-resource/images/apple-touch-icon.png') }}" rel="apple-touch-icon" sizes="180x180"/>
        <link href="{{ asset('portal-resource/images/favicon-32x32.png') }}" rel="icon" sizes="32x32" type="image/png"/>
        <link href="{{ asset('portal-resource/images/favicon.ico') }}" rel="shortcut icon"/>

        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet"/>
        <link href="{{ asset('portal-resource/css/styles.bundle.css') }}" rel="stylesheet"/>
        <link href="{{ asset('portal-resource/css/styles.css') }}" rel="stylesheet"/>
        <style>
            .branded-bg {
		    	background-image:url('{{ asset("portal-resource/images/1.png") }}');
		    }
		
            .dark .branded-bg {
		    	background-image: url('{{ asset("portal-resource/images/1-dark.png") }}');
		    }
        </style>
        @stack('style')

    </head>

    <body class="antialiased flex h-full text-base text-gray-700 dark:bg-coal-500">
      
    <script>
        const defaultThemeMode = 'light';
        let themeMode;

        if ( document.documentElement ) {
            if ( localStorage.getItem('theme')) {
                themeMode = localStorage.getItem('theme');
            } else if ( document.documentElement.hasAttribute('data-theme-mode')) {
                themeMode = document.documentElement.getAttribute('data-theme-mode');
            } else {
                themeMode = defaultThemeMode;
            }

            if (themeMode === 'system') {
                themeMode = window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light';
            }

            document.documentElement.classList.add(themeMode);
        }
    </script>

    @yield('content')
    
    <script>var hostUrl = "assets/";</script>
    <script src="{{ asset('portal-resource/js/plugins.bundle.js') }}"></script>
    <script src="{{ asset('portal-resource/js/scripts.bundle.js') }}"></script>
        
@stack('scripts')

    </body>
</html>
