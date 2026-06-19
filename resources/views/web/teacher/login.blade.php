<!DOCTYPE html>
<html lang="bn">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>শিক্ষক লগইন — প্রত্যয় একাডেমি</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Noto+Serif+Bengali:wght@400;600;700;900&family=Hind+Siliguri:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: 'Noto Serif Bengali', 'Hind Siliguri', sans-serif;
            min-height: 100vh; background: #07091e;
            display: flex; align-items: center; justify-content: center;
            padding: 20px; position: relative; overflow: hidden;
        }
        body::before {
            content: ''; position: fixed; inset: 0; pointer-events: none;
            background: radial-gradient(circle at 20% 20%, rgba(0,119,182,.28) 0%, transparent 50%),
                        radial-gradient(circle at 80% 80%, rgba(0,180,216,.12) 0%, transparent 50%);
        }
        .box {
            background: rgba(255,255,255,.04); border: 1px solid rgba(255,255,255,.09);
            border-radius: 26px; padding: 48px 42px; width: 100%; max-width: 440px;
            backdrop-filter: blur(20px); position: relative; z-index: 1;
            box-shadow: 0 32px 80px rgba(0,0,0,.4);
        }
        .brand {
            width: 60px; height: 60px; border-radius: 15px;
            background: linear-gradient(135deg, #00b4d8, #0077b6);
            display: flex; align-items: center; justify-content: center;
            font-size: 22px; font-weight: 900; color: #07091e;
            margin: 0 auto 20px; box-shadow: 0 8px 24px rgba(0,180,216,.35);
        }
        h1 { font-size: 25px; font-weight: 900; color: #fff; text-align: center; margin-bottom: 6px; }
        .sub { font-size: 13.5px; color: rgba(255,255,255,.45); text-align: center; margin-bottom: 30px; }
        .fg { margin-bottom: 17px; }
        label { display: block; font-size: 12.5px; font-weight: 600; color: rgba(255,255,255,.65); margin-bottom: 7px; }
        input[type="email"], input[type="password"] {
            width: 100%; padding: 12px 15px;
            background: rgba(255,255,255,.06); border: 1px solid rgba(255,255,255,.1);
            border-radius: 11px; color: #fff; font-size: 14.5px; font-family: inherit;
            outline: none; transition: border-color .2s, background .2s;
        }
        input:focus { border-color: rgba(0,180,216,.55); background: rgba(255,255,255,.09); }
        input::placeholder { color: rgba(255,255,255,.28); }
        .btn {
            width: 100%; padding: 13px;
            background: linear-gradient(135deg, #00b4d8, #0077b6);
            border: none; border-radius: 11px; color: #07091e;
            font-family: inherit; font-size: 15.5px; font-weight: 700; cursor: pointer;
            box-shadow: 0 6px 20px rgba(0,180,216,.35);
            transition: transform .2s, box-shadow .2s; margin-top: 8px;
        }
        .btn:hover { transform: translateY(-2px); box-shadow: 0 10px 28px rgba(0,180,216,.45); }
        .lnks { text-align: center; margin-top: 22px; font-size: 13px; color: rgba(255,255,255,.45); }
        .lnks a { color: #00b4d8; text-decoration: none; font-weight: 600; }
        .lnks a:hover { text-decoration: underline; }
        .lnks p { margin-top: 8px; }
        .alert-ok { background: rgba(34,197,94,.1); border: 1px solid rgba(34,197,94,.25); color: #22c55e; border-radius: 11px; padding: 11px 15px; margin-bottom: 18px; font-size: 13.5px; }
        .alert-err { background: rgba(239,68,68,.1); border: 1px solid rgba(239,68,68,.25); color: #ef4444; border-radius: 11px; padding: 11px 15px; margin-bottom: 18px; font-size: 13.5px; }
    </style>
</head>
<body>
<div class="box">
    <div class="brand">শি</div>
    <h1>শিক্ষক লগইন</h1>
    <p class="sub">প্রত্যয় একাডেমি — Teacher Panel</p>

    @if(session('success'))
        <div class="alert-ok">✅ {{ session('success') }}</div>
    @endif
    @if($errors->any())
        <div class="alert-err">❌ {{ $errors->first() }}</div>
    @endif

    <form method="POST" action="{{ route('teacher.login.post') }}">
        @csrf
        <div class="fg">
            <label>ইমেইল ঠিকানা</label>
            <input type="email" name="email" value="{{ old('email') }}" placeholder="teacher@example.com" required>
        </div>
        <div class="fg">
            <label>পাসওয়ার্ড</label>
            <input type="password" name="password" placeholder="••••••••" required>
        </div>
        <button type="submit" class="btn">🔑 লগইন করুন</button>
    </form>

    <div class="lnks">
        <p>নতুন শিক্ষক? <a href="{{ route('teacher.register') }}">নিবন্ধন করুন</a></p>
        <p><a href="{{ route('home') }}">← ওয়েবসাইটে ফিরুন</a></p>
    </div>
</div>
</body>
</html>
