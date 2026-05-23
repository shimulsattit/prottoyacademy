<style>
  nav {
    position: fixed; top: 0; left: 0; right: 0; z-index: 100;
    display: flex; align-items: center; justify-content: space-between;
    padding: 16px 52px;
    background: rgba(7,9,30,0.88);
    backdrop-filter: blur(18px);
    border-bottom: 1px solid rgba(255,255,255,0.07);
  }
  .logo-box { display: flex; align-items: center; gap: 12px; text-decoration: none; color: #fff; }
  .logo-icon {
    width: 44px; height: 44px;
    background: linear-gradient(135deg, var(--accent-gold), var(--accent-orange));
    border-radius: 11px;
    display: flex; align-items: center; justify-content: center;
    font-family: 'Noto Serif Bengali', serif;
    font-size: 22px; font-weight: 900;
    color: #07091e;
    box-shadow: 0 4px 18px rgba(245,197,24,0.35);
  }
  .logo-text { font-family: 'Noto Serif Bengali', serif; font-size: 19px; font-weight: 700; line-height: 1.25; }
  .logo-text span { color: var(--accent-gold); }
  .logo-sub { font-size: 10px; font-weight: 400; color: #6b7db3; letter-spacing: 2px; }

  .nav-links { display: flex; align-items: center; gap: 28px; list-style: none; margin-bottom: 0; }
  .nav-links a { color: var(--text-light); text-decoration: none; font-size: 15px; font-weight: 500; transition: color .2s; }
  .nav-links a:hover { color: var(--accent-gold); }
  .nav-cta {
    background: linear-gradient(135deg, var(--accent-gold), var(--accent-orange));
    color: #07091e !important; font-weight: 700 !important;
    padding: 10px 22px; border-radius: 9px;
    box-shadow: 0 4px 14px rgba(245,197,24,0.3);
    transition: transform .2s, box-shadow .2s !important;
  }
  .nav-cta:hover { transform: translateY(-2px); box-shadow: 0 7px 22px rgba(245,197,24,0.45) !important; }

  .hamburger {
    display: none;
    flex-direction: column; justify-content: center; align-items: center;
    width: 40px; height: 40px; gap: 5px;
    background: rgba(255,255,255,0.06);
    border: 1px solid rgba(255,255,255,0.1);
    border-radius: 9px; cursor: pointer;
    transition: background .2s;
  }
  .hamburger span { display: block; width: 20px; height: 2px; background: #fff; border-radius: 2px; transition: transform .3s, opacity .3s; }
  .hamburger.open span:nth-child(1) { transform: translateY(7px) rotate(45deg); }
  .hamburger.open span:nth-child(2) { opacity: 0; }
  .hamburger.open span:nth-child(3) { transform: translateY(-7px) rotate(-45deg); }

  .mobile-menu {
    display: none;
    position: fixed; top: 68px; left: 0; right: 0;
    background: rgba(7,9,30,0.97);
    backdrop-filter: blur(20px);
    border-bottom: 1px solid rgba(255,255,255,0.08);
    z-index: 99;
    padding: 20px 24px 28px;
    flex-direction: column; gap: 4px;
    transform: translateY(-10px); opacity: 0;
    transition: transform .25s ease, opacity .25s ease;
    max-height: calc(100vh - 68px);
    overflow-y: auto;
  }
  .mobile-menu.open { display: flex; transform: translateY(0); opacity: 1; }
  .mobile-menu a { color: var(--text-light); text-decoration: none; font-size: 16px; font-weight: 500; padding: 13px 16px; border-radius: 10px; display: block; transition: background .2s, color .2s; }
  .mobile-menu a:hover { background: rgba(255,255,255,0.07); color: #fff; }
  .mobile-menu .mob-cta { margin-top: 8px; background: linear-gradient(135deg, var(--accent-gold), var(--accent-orange)); color: #07091e !important; font-weight: 700 !important; text-align: center; border-radius: 10px; }

  @media (max-width: 768px) {
    nav { padding: 12px 18px; }
    .nav-links { display: none; }
    .hamburger { display: flex; }
  }
  @media (max-width: 480px) {
    .logo-text { font-size: 16px; }
    .logo-icon { width: 38px; height: 38px; font-size: 19px; }
    .logo-sub { font-size: 8px; letter-spacing: 1px; }
  }
</style>

<nav>
  <a href="{{ route('home') }}" class="logo-box">
    <div class="logo-icon">প্র</div>
    <div class="logo-text">
      প্রত্যয় <span>একাডেমি</span>
      <div class="logo-sub">PROTTOY ACADEMY</div>
    </div>
  </a>
  <ul class="nav-links">
    <li><a href="{{ route('home') }}">হোম</a></li>
    @if (get_settings('header_menu_labels') != null)
        @foreach ( json_decode(get_settings('header_menu_labels')) as $key => $value)
            <li>
                <a href="{{ json_decode(App\Models\Setting::where('name', 'header_menu_links')->first()->value, true)[$key] }}">
                    {{ $value }}
                </a>
            </li>
        @endforeach
    @endif
    @if(auth()->guard('student')->check())
        <li><a href="{{ route('student.dashboard') }}" class="nav-cta">📊 ড্যাশবোর্ড</a></li>
    @else
        <li><a href="{{ route('login') }}" class="nav-cta">🔑 লগইন</a></li>
    @endif
  </ul>
  <button class="hamburger" id="hamburger" aria-label="মেনু">
    <span></span><span></span><span></span>
  </button>
</nav>

<div class="mobile-menu" id="mobileMenu">
  <a href="{{ route('home') }}">🏠 হোম</a>
  @if (get_settings('header_menu_labels') != null)
      @foreach ( json_decode(get_settings('header_menu_labels')) as $key => $value)
          <a href="{{ json_decode(App\Models\Setting::where('name', 'header_menu_links')->first()->value, true)[$key] }}">
              {{ $value }}
          </a>
      @endforeach
  @endif
  @if(auth()->guard('student')->check())
      <a href="{{ route('student.dashboard') }}" class="mob-cta">📊 ড্যাশবোর্ড</a>
  @else
      <a href="{{ route('login') }}" class="mob-cta">🔑 লগইন করুন</a>
  @endif
</div>

<script>
  const btn = document.getElementById('hamburger');
  const menu = document.getElementById('mobileMenu');
  btn.addEventListener('click', () => {
    btn.classList.toggle('open');
    menu.classList.toggle('open');
  });
  menu.querySelectorAll('a').forEach(a => {
    a.addEventListener('click', () => {
      btn.classList.remove('open');
      menu.classList.remove('open');
    });
  });
</script>
