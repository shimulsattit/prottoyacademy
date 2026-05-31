<!DOCTYPE html>
<html lang="bn">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ isset($title) ? $title . ' - Prottoy Academy' : 'Prottoy Academy Portal' }}</title>
    
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="shortcut icon" href="{{ asset(get_settings('system_favicon') ?? 'portal-resource/images/favicon.ico') }}" />
    
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Hind+Siliguri:wght@300;400;500;600;700&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Standard CSS Bundles from Metronic / Project -->
    <link rel="stylesheet" type="text/css" href="{{ asset('portal-resource/css/plugins.bundle.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ asset('portal-resource/css/style.bundle.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ asset('portal-resource/css/select2.min.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ asset('portal-resource/css/sweetalert.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ asset('portal-resource/css/parsley.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ asset('portal-resource/css/toastr.min.css') }}" />
    
    <!-- Premium Style Overrides -->
    <style>
        :root {
            --primary: #1B4F72;
            --primary-light: #2E86C1;
            --primary-lighter: #AED6F1;
            --secondary: #1E8449;
            --secondary-light: #27AE60;
            --accent1: #E67E22;
            --accent2: #8E44AD;
            --accent3: #E74C3C;
            --accent4: #16A085;
            --bg: #F0F4F8;
            --sidebar-bg: linear-gradient(180deg, #0D2137 0%, #1B4F72 40%, #1E8449 100%);
            --card-bg: #fff;
            --text-main: #1a202c;
            --text-muted: #718096;
            --border: #e2e8f0;
            --shadow: 0 4px 20px rgba(0,0,0,0.08);
            --shadow-hover: 0 8px 30px rgba(0,0,0,0.15);
            --radius: 16px;
        }

        body {
            font-family: 'Hind Siliguri', 'Inter', sans-serif !important;
            background: var(--bg) !important;
            color: var(--text-main) !important;
            display: flex;
            min-height: 100vh;
            overflow-x: hidden;
        }

        /* SIDEBAR STYLE */
        .sidebar {
            width: 260px;
            min-height: 100vh;
            background: var(--sidebar-bg);
            display: flex;
            flex-direction: column;
            position: fixed;
            left: 0;
            top: 0;
            bottom: 0;
            z-index: 100;
            transition: width 0.3s;
            box-shadow: 4px 0 15px rgba(0, 0, 0, 0.1);
        }
        
        .sidebar.collapsed {
            width: 72px;
        }

        .sidebar-logo {
            padding: 24px 20px 16px;
            display: flex;
            align-items: center;
            gap: 12px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.12);
        }

        .logo-icon {
            width: 44px;
            height: 44px;
            background: linear-gradient(135deg, #F39C12, #E74C3C);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 22px;
            font-weight: 700;
            color: #fff;
            flex-shrink: 0;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);
        }

        .logo-text {
            color: #fff;
        }

        .logo-text h2 {
            font-size: 15px;
            font-weight: 700;
            line-height: 1.2;
            margin: 0;
            color: #fff !important;
        }

        .logo-text span {
            font-size: 11px;
            color: rgba(255, 255, 255, 0.6);
        }

        .sidebar.collapsed .logo-text, 
        .sidebar.collapsed .nav-label, 
        .sidebar.collapsed .section-title,
        .sidebar.collapsed .chevron-icon {
            display: none;
        }

        .sidebar-nav {
            flex: 1;
            padding: 16px 12px;
            overflow-y: auto;
        }

        .sidebar-nav::-webkit-scrollbar {
            width: 4px;
        }

        .sidebar-nav::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, 0.15);
            border-radius: 10px;
        }

        .section-title {
            font-size: 10px;
            color: rgba(255, 255, 255, 0.45);
            text-transform: uppercase;
            letter-spacing: 1.5px;
            padding: 16px 8px 6px;
            font-weight: 600;
        }

        .nav-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 11px 12px;
            border-radius: 10px;
            cursor: pointer;
            margin-bottom: 2px;
            color: rgba(255, 255, 255, 0.75) !important;
            transition: all 0.2s;
            position: relative;
            text-decoration: none !important;
            font-weight: 500;
        }

        .nav-item:hover {
            background: rgba(255, 255, 255, 0.12);
            color: #fff !important;
        }

        .nav-item.active {
            background: linear-gradient(135deg, #F39C12, #E67E22);
            color: #fff !important;
            box-shadow: 0 4px 12px rgba(243, 156, 18, 0.4);
            font-weight: 600;
        }

        .nav-item i {
            width: 20px;
            text-align: center;
            font-size: 16px;
            flex-shrink: 0;
        }

        .nav-label {
            font-size: 14px;
        }

        /* DROPDOWNS */
        .nav-dropdown {
            position: relative;
        }

        .dropdown-content {
            display: none;
            padding-left: 12px;
            background: rgba(0, 0, 0, 0.18);
            border-radius: 8px;
            margin: 4px 6px;
        }

        .dropdown-content .nav-item {
            font-size: 13px;
            padding: 8px 12px;
            color: rgba(255, 255, 255, 0.7) !important;
        }

        .dropdown-content .nav-item:hover, 
        .dropdown-content .nav-item.active {
            color: #fff !important;
            background: rgba(255, 255, 255, 0.08);
            box-shadow: none;
        }

        .has-dropdown {
            justify-content: space-between;
        }

        .has-dropdown .chevron-icon {
            font-size: 10px;
            transition: transform 0.2s;
            margin-left: auto;
        }

        .has-dropdown.open .chevron-icon {
            transform: rotate(180deg);
        }

        /* FOOTER / USER CARD */
        .sidebar-footer {
            padding: 16px 12px;
            border-top: 1px solid rgba(255, 255, 255, 0.12);
        }

        .user-card {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 10px 12px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 10px;
            cursor: pointer;
            transition: background 0.2s;
        }

        .user-card:hover {
            background: rgba(255, 255, 255, 0.18);
        }

        .user-avatar {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background: linear-gradient(135deg, #9B59B6, #3498DB);
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            font-weight: 700;
            font-size: 14px;
            flex-shrink: 0;
        }

        .user-info {
            overflow: hidden;
        }

        .user-info strong {
            display: block;
            color: #fff;
            font-size: 13px;
            font-weight: 600;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .user-info span {
            color: rgba(255, 255, 255, 0.55);
            font-size: 11px;
        }

        .sidebar.collapsed .user-info,
        .sidebar.collapsed .sidebar-footer span,
        .sidebar.collapsed .sidebar-footer strong {
            display: none;
        }

        /* MAIN SECTION */
        .main {
            margin-left: 260px;
            flex: 1;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            transition: margin-left 0.3s;
            background: var(--bg);
        }

        .main.expanded {
            margin-left: 72px;
        }

        /* TOPBAR STYLE */
        .topbar {
            background: #fff;
            padding: 0 28px;
            height: 68px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            border-bottom: 1px solid var(--border);
            position: sticky;
            top: 0;
            z-index: 50;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
        }

        .topbar-left {
            display: flex;
            align-items: center;
            gap: 16px;
        }

        .collapse-btn {
            width: 36px;
            height: 36px;
            border: 1px solid var(--border);
            border-radius: 8px;
            background: transparent;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--text-muted);
            transition: all 0.2s;
            font-size: 16px;
        }

        .collapse-btn:hover {
            background: var(--bg);
            border-color: var(--primary-light);
            color: var(--primary);
        }

        .topbar-left .breadcrumb {
            display: flex;
            align-items: center;
            gap: 6px;
            font-size: 14px;
            color: var(--text-muted);
            margin: 0;
            background: transparent;
            padding: 0;
        }

        .topbar-left .breadcrumb strong {
            color: var(--text-main);
            font-weight: 600;
        }

        .topbar-right {
            display: flex;
            align-items: center;
            gap: 16px;
        }

        .date-chip {
            background: linear-gradient(135deg, #1B4F72, #2E86C1);
            color: #fff;
            border-radius: 10px;
            padding: 7px 14px;
            font-size: 13px;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .topbar-user-dropdown {
            position: relative;
            cursor: pointer;
        }

        .topbar-avatar {
            width: 38px;
            height: 38px;
            border-radius: 10px;
            border: 2px solid #e2e8f0;
            object-fit: cover;
            transition: border-color 0.2s;
        }

        .topbar-user-dropdown:hover .topbar-avatar {
            border-color: var(--primary-light);
        }

        .dropdown-menu-custom {
            position: absolute;
            right: 0;
            top: 48px;
            background: #fff;
            border: 1px solid var(--border);
            border-radius: 12px;
            width: 250px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
            display: none;
            z-index: 1000;
            padding: 8px 0;
        }

        .dropdown-menu-custom.show {
            display: block;
        }

        .dropdown-header-custom {
            padding: 12px 18px;
            border-bottom: 1px solid var(--border);
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .dropdown-header-custom img {
            width: 44px;
            height: 44px;
            border-radius: 50%;
            object-fit: cover;
        }

        .dropdown-header-custom .user-details h4 {
            font-size: 13px;
            font-weight: 700;
            margin: 0;
            color: var(--text-main);
        }

        .dropdown-header-custom .user-details span {
            font-size: 11px;
            color: var(--text-muted);
        }

        .dropdown-item-custom {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 10px 18px;
            color: var(--text-main);
            text-decoration: none !important;
            font-size: 13px;
            font-weight: 500;
            transition: background 0.2s;
        }

        .dropdown-item-custom:hover {
            background: var(--bg);
            color: var(--primary);
        }

        .dropdown-item-custom i {
            font-size: 14px;
            color: var(--text-muted);
            width: 18px;
            text-align: center;
        }

        /* CONTENT STYLE */
        .content {
            padding: 28px;
            flex: 1;
        }

        /* Scroll Top override */
        #scrolltop {
            background-color: var(--primary);
            border-radius: 8px;
        }

        #scrolltop:hover {
            background-color: var(--primary-light);
        }

        /* RESPONSIVE LAYOUT adjustments */
        @media(max-width: 991px) {
            .sidebar {
                width: 72px;
            }
            .sidebar .logo-text, 
            .sidebar .nav-label, 
            .sidebar .section-title,
            .sidebar .chevron-icon,
            .sidebar .user-info {
                display: none;
            }
            .main {
                margin-left: 72px;
            }
        }

        @media(max-width: 576px) {
            .topbar {
                padding: 0 16px;
            }
            .topbar-left .breadcrumb {
                display: none;
            }
            .content {
                padding: 16px;
            }
        }
    </style>
    @stack('style')
</head>

<body>
    <!-- Premium Collapsible Sidebar -->
    @include('layouts.partials.admin.sidebar')

    <!-- Main Wrapper -->
    <div class="main" id="main">
        <!-- Premium Topbar Header -->
        <header class="topbar">
            <div class="topbar-left">
                <button class="collapse-btn" id="sidebarToggle">
                    <i class="fas fa-bars"></i>
                </button>
                <div class="breadcrumb">
                    <span>পোর্টাল</span>
                    <i class="fas fa-chevron-right" style="font-size:10px"></i>
                    <strong>{{ $title ?? 'ড্যাশবোর্ড' }}</strong>
                </div>
            </div>
            
            <div class="topbar-right">
                <!-- Bangla Date chip -->
                <div class="date-chip">
                    <i class="fas fa-calendar-alt"></i>
                    <span id="today-date"></span>
                </div>
                
                <!-- Profile Avatar Dropdown -->
                <div class="topbar-user-dropdown" id="userDropdownTrigger">
                    <img src="{{ asset(Auth::guard('admin')->user()->avatar ? Auth::guard('admin')->user()->avatar : 'portal-resource/images/300-1.jpg') }}" class="topbar-avatar" alt="user" />
                    
                    <div class="dropdown-menu-custom" id="userDropdownMenu">
                        <div class="dropdown-header-custom">
                            <img src="{{ asset(Auth::guard('admin')->user()->avatar ? Auth::guard('admin')->user()->avatar : 'portal-resource/images/300-1.jpg') }}" alt="user" />
                            <div class="user-details">
                                <h4>{{ Auth::guard('admin')->user()->first_name . ' ' . Auth::guard('admin')->user()->last_name }}</h4>
                                <span>{{ Auth::guard('admin')->user()->email }}</span>
                            </div>
                        </div>
                        
                        <a href="{{ route('portal.profile') }}" class="dropdown-item-custom">
                            <i class="fas fa-user-edit"></i>
                            <span>My Profile</span>
                        </a>
                        
                        <a href="{{ route('portal.password') }}" class="dropdown-item-custom">
                            <i class="fas fa-key"></i>
                            <span>Update Password</span>
                        </a>
                        
                        <div class="dropdown-divider m-0" style="border-top: 1px solid var(--border);"></div>
                        
                        <a href="javascript:;" data-url="{{ route('portal.logout') }}" id="logout" class="dropdown-item-custom" style="color: var(--accent3) !important;">
                            <i class="fas fa-sign-out-alt" style="color: var(--accent3) !important;"></i>
                            <span>Sign Out</span>
                        </a>
                    </div>
                </div>
            </div>
        </header>

        <!-- Main Yield Content -->
        <div class="content">
            @yield('content')
        </div>
    </div>

    <!-- Metronic Remote Modal Container (Important for AJAX Popups/Forms) -->
    @if(isset($modal))
        <div id="modal_remote" class="modal fade border-top-success rounded-top-0" data-backdrop="static" role="dialog">
            <div class="modal-dialog modal-{{ $modal }} modal-dialog-centered">
                <div class="modal-content">
                    <!-- Loaded dynamically via AJAX -->
                </div>
            </div>
        </div>
    @endif

    <!-- Scroll to Top button -->
    <div id="scrolltop" class="scrolltop" data-scrolltop="true">
        <i class="ki-duotone ki-arrow-up">
            <span class="path1"></span>
            <span class="path2"></span>
        </i>
    </div>

    <!-- Core Javascript Scripts -->
    <script>var hostUrl = "assets/";</script>
    <script src="{{ asset('portal-resource/js/plugins.bundle.js') }}"></script>
    <script src="{{ asset('portal-resource/js/scripts.bundle.js') }}"></script>
    <script src="{{ asset('portal-resource/js/select2.min.js') }}"></script>
    <script src="{{ asset('portal-resource/js/parsley.min.js') }}"></script>
    <script src="{{ asset('portal-resource/js/toastr.min.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="{{ asset('portal-resource/js/main.js') }}"></script>

    <!-- Custom Collapsing and Dropdown scripts -->
    <script>
        $(document).ready(function() {
            // Sidebar collapse trigger
            $('#sidebarToggle').on('click', function() {
                $('#sidebar').toggleClass('collapsed');
                $('#main').toggleClass('expanded');
            });

            // Sidebar dropdown menus toggle
            $('.has-dropdown').on('click', function() {
                $(this).toggleClass('open');
                $(this).next('.dropdown-content').slideToggle(200);
            });

            // Auto-expand active dropdowns on page load
            $('.dropdown-content').each(function() {
                if ($(this).find('.nav-item.active').length > 0) {
                    $(this).show();
                    $(this).prev('.has-dropdown').addClass('open');
                }
            });

            // Topbar profile dropdown toggle
            $('#userDropdownTrigger').on('click', function(e) {
                e.stopPropagation();
                $('#userDropdownMenu').toggleClass('show');
            });

            $(document).on('click', function() {
                $('#userDropdownMenu').removeClass('show');
            });

            // Live today date in Bangla
            const d = new Date();
            const months = ['জানুয়ারি','ফেব্রুয়ারি','মার্চ','এপ্রিল','মে','জুন','জুলাই','আগস্ট','সেপ্টেম্বর','অক্টোবর','নভেম্বর','ডিসেম্বর'];
            const days = ['রবিবার','সোমবার','মঙ্গলবার','বুধবার','বৃহস্পতিবার','শুক্রবার','শনিবার'];
            $('#today-date').text(`${days[d.getDay()]}, ${d.getDate()} ${months[d.getMonth()]} ${d.getFullYear()}`);
        });
    </script>
    
    @stack('scripts')
</body>
</html>
