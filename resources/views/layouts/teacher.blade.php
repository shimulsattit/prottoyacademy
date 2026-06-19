<!DOCTYPE html>
<html lang="bn">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ isset($title) ? $title . ' — প্রত্যয় একাডেমি' : 'শিক্ষক প্যানেল — প্রত্যয় একাডেমি' }}</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Hind+Siliguri:wght@300;400;500;600;700&family=Noto+Serif+Bengali:wght@400;600;700;900&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/remixicon@3.5.0/fonts/remixicon.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('portal-resource/css/toastr.min.css') }}">
    <style>
        :root {
            --tp: #00b4d8;
            --tp-dark: #0077b6;
            --tp-light: rgba(0,180,216,0.15);
            --sb: rgba(7,9,30,0.98);
            --card: rgba(255,255,255,0.04);
            --bdr: rgba(255,255,255,0.08);
            --navy: #07091e;
        }
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: 'Noto Serif Bengali', 'Hind Siliguri', sans-serif;
            background: var(--navy); color: #fff;
            min-height: 100vh; overflow-x: hidden;
        }
        body::before {
            content: ''; position: fixed; inset: 0; pointer-events: none;
            background: radial-gradient(circle at 0% 0%, rgba(0,119,182,.22) 0%, transparent 50%),
                        radial-gradient(circle at 100% 100%, rgba(0,180,216,.07) 0%, transparent 50%);
        }
        .t-wrap { display: flex; min-height: 100vh; position: relative; z-index: 1; }
        /* SIDEBAR */
        .t-sidebar {
            width: 260px; min-height: 100vh; background: var(--sb);
            border-right: 1px solid var(--bdr); backdrop-filter: blur(20px);
            position: fixed; top: 0; left: 0; display: flex; flex-direction: column;
            z-index: 100; transition: left .3s;
        }
        .t-content { margin-left: 260px; flex: 1; padding: 32px; min-height: 100vh; }
        .sb-logo { padding: 22px 18px; border-bottom: 1px solid var(--bdr); }
        .sb-logo a { display: flex; align-items: center; gap: 12px; text-decoration: none; }
        .sb-icon { width: 42px; height: 42px; border-radius: 11px;
            background: linear-gradient(135deg, var(--tp), var(--tp-dark));
            display: flex; align-items: center; justify-content: center;
            font-size: 17px; font-weight: 900; color: var(--navy);
            box-shadow: 0 4px 14px rgba(0,180,216,.35); }
        .sb-name { font-size: 15px; font-weight: 700; color: #fff; line-height: 1.3; }
        .sb-name span { color: var(--tp); }
        .sb-sub { font-size: 10px; color: rgba(255,255,255,.45); letter-spacing: 2px; }
        .sb-nav { flex: 1; padding: 14px 10px; overflow-y: auto; }
        .sb-item { margin-bottom: 3px; }
        .sb-link {
            display: flex; align-items: center; gap: 11px; padding: 11px 15px;
            border-radius: 11px; color: rgba(255,255,255,.55); text-decoration: none;
            font-size: 14.5px; font-weight: 500; transition: all .22s;
        }
        .sb-link:hover { color: #fff; background: rgba(255,255,255,.05); transform: translateX(4px); }
        .sb-link.active { background: var(--tp-light); color: var(--tp); border-left: 3px solid var(--tp); }
        .sb-link i { font-size: 18px; }
        .sb-divider { border-top: 1px solid var(--bdr); margin: 12px 0; }
        .sb-foot { padding: 14px; border-top: 1px solid var(--bdr); }
        .u-card { background: var(--card); border: 1px solid var(--bdr); border-radius: 13px;
            padding: 11px 13px; display: flex; align-items: center; gap: 10px; }
        .u-av { width: 36px; height: 36px; border-radius: 50%; flex-shrink: 0;
            background: linear-gradient(135deg, var(--tp), var(--tp-dark));
            display: flex; align-items: center; justify-content: center;
            font-size: 14px; font-weight: 800; color: var(--navy); }
        /* MOBILE */
        .mob-bar { display: none; background: rgba(7,9,30,.95); backdrop-filter: blur(15px);
            padding: 13px 18px; border-bottom: 1px solid var(--bdr);
            justify-content: space-between; align-items: center; position: sticky; top: 0; z-index: 99; }
        .mob-btn { background: none; border: none; color: #fff; font-size: 24px; cursor: pointer; }
        @media (max-width: 991px) {
            .t-sidebar { left: -280px; width: 280px; }
            .t-sidebar.open { left: 0; }
            .t-content { margin-left: 0; padding: 20px 15px; }
            .mob-bar { display: flex; }
        }
        /* CARDS & ALERTS */
        .t-card { background: var(--card); border: 1px solid var(--bdr); border-radius: 18px; backdrop-filter: blur(12px); }
        .t-alert-ok { background: rgba(34,197,94,.1); border: 1px solid rgba(34,197,94,.25); color: #22c55e; border-radius: 11px; padding: 11px 16px; margin-bottom: 18px; }
        .t-alert-err { background: rgba(239,68,68,.1); border: 1px solid rgba(239,68,68,.25); color: #ef4444; border-radius: 11px; padding: 11px 16px; margin-bottom: 18px; }
        /* PAGE HEADER */
        .pg-head { margin-bottom: 28px; }
        .pg-head h2 { font-size: 1.7rem; font-weight: 800; background: linear-gradient(135deg,#fff,#a5a5a5); -webkit-background-clip: text; -webkit-text-fill-color: transparent; }
        .pg-head p { font-size: 13.5px; color: rgba(255,255,255,.5); margin-top: 4px; }
        /* STAT CARDS */
        .stat-row { display: grid; grid-template-columns: repeat(4,1fr); gap: 16px; margin-bottom: 24px; }
        .stat-c { padding: 22px 20px; border-radius: 18px; border: 1px solid var(--bdr); background: var(--card); position: relative; overflow: hidden; }
        .stat-c .s-num { font-size: 30px; font-weight: 900; }
        .stat-c .s-lbl { font-size: 13px; color: rgba(255,255,255,.5); margin-top: 4px; }
        .stat-c .s-ico { position: absolute; bottom: -8px; right: 8px; font-size: 56px; opacity: .06; }
        @media (max-width: 768px) { .stat-row { grid-template-columns: repeat(2,1fr); } }
    </style>
    @stack('style')
</head>
<body>
<div class="mob-bar" id="mobBar">
    <span style="font-weight:700; color:var(--tp);">🎓 শিক্ষক প্যানেল</span>
    <button class="mob-btn" id="sbToggle"><i class="ri-menu-line"></i></button>
</div>
<div class="t-wrap">
    @include('web.teacher.partials.sidebar')
    <div class="t-content">
        @if(session('success'))
            <div class="t-alert-ok">✅ {{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="t-alert-err">❌ {{ session('error') }}</div>
        @endif
        @if($errors->any())
            <div class="t-alert-err">❌ {{ $errors->first() }}</div>
        @endif
        @yield('content')
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="{{ asset('portal-resource/js/toastr.min.js') }}"></script>
<script>
    const sbToggle = document.getElementById('sbToggle');
    const sidebar  = document.querySelector('.t-sidebar');
    if (sbToggle) sbToggle.addEventListener('click', () => sidebar.classList.toggle('open'));
    document.addEventListener('click', e => {
        if (window.innerWidth <= 991 && sidebar && !sidebar.contains(e.target) && e.target !== sbToggle)
            sidebar.classList.remove('open');
    });
</script>
@stack('scripts')
</body>
</html>
