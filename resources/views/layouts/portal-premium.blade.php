<!DOCTYPE html>
<html lang="bn">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Prottoy Academy' }}</title>
    <link href="https://fonts.googleapis.com/css2?family=Hind+Siliguri:wght@300;400;500;600;700&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
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
        * { margin:0; padding:0; box-sizing:border-box; }
        body { font-family: 'Hind Siliguri', 'Inter', sans-serif; background: var(--bg); color: var(--text-main); display:flex; min-height:100vh; overflow-x:hidden; }

        /* SIDEBAR */
        .sidebar {
            width: 260px; min-height: 100vh; background: var(--sidebar-bg);
            display: flex; flex-direction: column; position: fixed; left:0; top:0; bottom:0; z-index:100;
            transition: width 0.3s;
        }
        .sidebar.collapsed { width: 72px; }
        .sidebar-logo {
            padding: 24px 20px 16px; display:flex; align-items:center; gap:12px;
            border-bottom: 1px solid rgba(255,255,255,0.12);
        }
        .logo-icon {
            width:44px; height:44px; background: linear-gradient(135deg,#F39C12,#E74C3C);
            border-radius:12px; display:flex; align-items:center; justify-content:center;
            font-size:22px; flex-shrink:0; box-shadow: 0 4px 12px rgba(0,0,0,0.3);
        }
        .logo-text { color:#fff; }
        .logo-text h2 { font-size:15px; font-weight:700; line-height:1.2; }
        .logo-text span { font-size:11px; color:rgba(255,255,255,0.6); }
        .sidebar.collapsed .logo-text, .sidebar.collapsed .nav-label, .sidebar.collapsed .section-title { display:none; }

        .sidebar-nav { flex:1; padding: 16px 12px; overflow-y:auto; }
        .section-title { font-size:10px; color:rgba(255,255,255,0.45); text-transform:uppercase; letter-spacing:1.5px; padding: 16px 8px 6px; font-weight:600; }

        .nav-item {
            display:flex; align-items:center; gap:12px; padding:11px 12px;
            border-radius:10px; cursor:pointer; margin-bottom:2px;
            color:rgba(255,255,255,0.75); transition:all 0.2s; position:relative;
            text-decoration: none;
        }
        .nav-item:hover { background:rgba(255,255,255,0.12); color:#fff; }
        .nav-item.active { background: linear-gradient(135deg,#F39C12,#E67E22); color:#fff; box-shadow: 0 4px 12px rgba(243,156,18,0.4); }
        .nav-item i { width:20px; text-align:center; font-size:16px; flex-shrink:0; }
        .nav-label { font-size:14px; font-weight:500; }
        .nav-badge { margin-left:auto; background:#E74C3C; color:#fff; font-size:10px; font-weight:700; padding:2px 7px; border-radius:20px; }

        .sidebar-footer { padding:16px 12px; border-top:1px solid rgba(255,255,255,0.12); }
        .user-card {
            display:flex; align-items:center; gap:10px; padding:10px 12px;
            background:rgba(255,255,255,0.1); border-radius:10px; cursor:pointer;
            transition:background 0.2s;
        }
        .user-card:hover { background:rgba(255,255,255,0.18); }
        .user-avatar {
            width:36px; height:36px; border-radius:50%;
            background: linear-gradient(135deg,#9B59B6,#3498DB);
            display:flex; align-items:center; justify-content:center;
            color:#fff; font-weight:700; font-size:14px; flex-shrink:0;
        }
        .user-info { overflow:hidden; }
        .user-info strong { display:block; color:#fff; font-size:13px; font-weight:600; white-space:nowrap; overflow:hidden; text-overflow:ellipsis; }
        .user-info span { color:rgba(255,255,255,0.55); font-size:11px; }
        .sidebar.collapsed .user-info { display:none; }

        /* MAIN */
        .main { margin-left:260px; flex:1; display:flex; flex-direction:column; min-height:100vh; transition:margin-left 0.3s; }
        .main.expanded { margin-left:72px; }

        /* TOPBAR */
        .topbar {
            background:#fff; padding:0 28px; height:68px; display:flex; align-items:center;
            justify-content:space-between; border-bottom:1px solid var(--border);
            position:sticky; top:0; z-index:50; box-shadow: 0 2px 8px rgba(0,0,0,0.05);
        }
        .topbar-left { display:flex; align-items:center; gap:16px; }
        .collapse-btn {
            width:36px; height:36px; border:1px solid var(--border); border-radius:8px;
            background:transparent; cursor:pointer; display:flex; align-items:center; justify-content:center;
            color:var(--text-muted); transition:all 0.2s; font-size:16px;
        }
        .collapse-btn:hover { background:var(--bg); border-color:var(--primary-light); color:var(--primary); }
        .breadcrumb { display:flex; align-items:center; gap:6px; font-size:14px; color:var(--text-muted); }
        .breadcrumb strong { color:var(--text-main); font-weight:600; }

        .topbar-right { display:flex; align-items:center; gap:12px; }
        .date-chip {
            background: linear-gradient(135deg,#1B4F72,#2E86C1);
            color:#fff; border-radius:10px; padding:7px 14px; font-size:13px; font-weight:500;
        }

        /* CONTENT */
        .content { padding:28px; flex:1; }
        
        @media(max-width:768px) {
            .sidebar { width:72px; }
            .sidebar .logo-text, .sidebar .nav-label, .sidebar .section-title, .sidebar .user-info { display:none; }
            .main { margin-left:72px; }
        }
    </style>
    @stack('style')
</head>
<body>
    <!-- SIDEBAR -->
    <aside class="sidebar" id="sidebar">
        <div class="sidebar-logo">
            <div class="logo-icon">প্র</div>
            <div class="logo-text">
                <h2>প্রত্যয় একাডেমি</h2>
                <span>অ্যাডমিন পোর্টাল</span>
            </div>
        </div>
        <nav class="sidebar-nav">
            <div class="section-title">প্রধান মেনু</div>
            <a href="{{ route('portal.dashboard') }}" class="nav-item {{ Request::is('portal/dashboard') ? 'active' : '' }}">
                <i class="fas fa-tachometer-alt"></i>
                <span class="nav-label">ড্যাশবোর্ড</span>
            </a>
            
            <a href="{{ route('portal.category.index') }}" class="nav-item {{ Request::is('portal/category*') ? 'active' : '' }}">
                <i class="fas fa-bars"></i>
                <span class="nav-label">ক্যাটাগরি</span>
            </a>

            <a href="{{ route('portal.job-category.index') }}" class="nav-item {{ Request::is('portal/job-category*') ? 'active' : '' }}">
                <i class="fas fa-stream"></i>
                <span class="nav-label">জব ক্যাটাগরি</span>
            </a>

            <a href="{{ route('portal.year.index') }}" class="nav-item {{ Request::is('portal/year*') ? 'active' : '' }}">
                <i class="fas fa-calendar"></i>
                <span class="nav-label">বছর</span>
            </a>

            <a href="{{ route('portal.exam.index') }}" class="nav-item {{ Request::is('portal/exam*') ? 'active' : '' }}">
                <i class="fas fa-file-alt"></i>
                <span class="nav-label">পরীক্ষা</span>
            </a>

            <a href="{{ route('portal.question.index') }}" class="nav-item {{ Request::is('portal/question*') ? 'active' : '' }}">
                <i class="fas fa-question-circle"></i>
                <span class="nav-label">প্রশ্নব্যাংক</span>
            </a>

            @php
                $jobSolutionCats = App\Models\Category::select('id', 'name')->where('parent_id', 9)->get();
                $admissionCats = App\Models\Category::select('id', 'name')->where('parent_id', 64)->get();
            @endphp

            @if($jobSolutionCats->count() > 0)
            <div class="section-title">ক্যাটাগরি ভিত্তিক</div>
            <div class="nav-dropdown">
                <div class="nav-item has-dropdown" onclick="$(this).next('.dropdown-content').slideToggle();">
                    <i class="fas fa-briefcase"></i>
                    <span class="nav-label">Job Solution</span>
                    <i class="fas fa-chevron-down ms-auto" style="font-size: 10px;"></i>
                </div>
                <div class="dropdown-content" style="display: none; padding-left: 20px; background: rgba(0,0,0,0.1);">
                    @foreach($jobSolutionCats as $cat)
                        <a href="{{ route('portal.category-wise-question', $cat->id) }}" class="nav-item" style="font-size: 13px;">{{ $cat->name }}</a>
                    @endforeach
                </div>
            </div>
            @endif

            <div class="section-title">প্রশাসন</div>
            <a href="{{ route('portal.stuff.index') }}" class="nav-item {{ Request::is('portal/stuff*') ? 'active' : '' }}">
                <i class="fas fa-users-cog"></i>
                <span class="nav-label">স্টাফগণ</span>
            </a>
            <a href="{{ route('portal.roles.index') }}" class="nav-item {{ Request::is('portal/roles*') ? 'active' : '' }}">
                <i class="fas fa-user-shield"></i>
                <span class="nav-label">রোল ও পারমিশন</span>
            </a>
            <a href="{{ route('portal.settings') }}" class="nav-item {{ Request::is('portal/settings*') ? 'active' : '' }}">
                <i class="fas fa-cog"></i>
                <span class="nav-label">সেটিংস</span>
            </a>
            
            <a href="{{ route('portal.bin.categories') }}" class="nav-item {{ Request::is('portal/bin*') ? 'active' : '' }}">
                <i class="fas fa-trash"></i>
                <span class="nav-label">রিসাইকেল বিন</span>
            </a>
        </nav>
        <div class="sidebar-footer">
            <div class="user-card" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                <div class="user-avatar">{{ substr(Auth::guard('admin')->user()->first_name, 0, 1) }}</div>
                <div class="user-info">
                    <strong>{{ Auth::guard('admin')->user()->first_name }}</strong>
                    <span>লগআউট করুন</span>
                </div>
            </div>
            <form id="logout-form" action="{{ route('portal.logout') }}" method="POST" style="display: none;">
                @csrf
            </form>
        </div>
    </aside>

    <!-- MAIN -->
    <div class="main" id="main">
        <!-- TOPBAR -->
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
                <div class="date-chip">
                    <i class="fas fa-calendar-alt" style="margin-right:6px"></i>
                    <span id="today-date"></span>
                </div>
            </div>
        </header>

        <!-- CONTENT -->
        <div class="content">
            @yield('content')
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#sidebarToggle').on('click', function() {
                $('#sidebar').toggleClass('collapsed');
                $('#main').toggleClass('expanded');
            });

            // Today date in Bangla
            const d = new Date();
            const months = ['জানুয়ারি','ফেব্রুয়ারি','মার্চ','এপ্রিল','মে','জুন','জুলাই','আগস্ট','সেপ্টেম্বর','অক্টোবর','নভেম্বর','ডিসেম্বর'];
            const days = ['রবিবার','সোমবার','মঙ্গলবার','বুধবার','বৃহস্পতিবার','শুক্রবার','শনিবার'];
            $('#today-date').text(`${days[d.getDay()]}, ${d.getDate()} ${months[d.getMonth()]} ${d.getFullYear()}`);
        });
    </script>
    @stack('scripts')
</body>
</html>
