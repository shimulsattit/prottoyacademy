<div class="t-sidebar" id="teacherSidebar">
    <div class="sb-logo">
        <a href="{{ route('teacher.dashboard') }}">
            <div class="sb-icon">শি</div>
            <div>
                <div class="sb-name">প্রত্যয় <span>একাডেমি</span></div>
                <div class="sb-sub">TEACHER PANEL</div>
            </div>
        </a>
    </div>

    <nav class="sb-nav">
        <div class="sb-item">
            <a href="{{ route('teacher.dashboard') }}" class="sb-link {{ request()->routeIs('teacher.dashboard') ? 'active' : '' }}">
                <i class="ri-dashboard-3-line"></i> ড্যাশবোর্ড
            </a>
        </div>
        <div class="sb-item">
            <a href="{{ route('teacher.questions') }}" class="sb-link {{ request()->routeIs('teacher.questions') ? 'active' : '' }}">
                <i class="ri-question-line"></i> আমার প্রশ্নসমূহ
            </a>
        </div>
        <div class="sb-item">
            <a href="{{ route('teacher.questions.create') }}" class="sb-link {{ request()->routeIs('teacher.questions.create') ? 'active' : '' }}">
                <i class="ri-add-circle-line"></i> নতুন প্রশ্ন যোগ
            </a>
        </div>
        <div class="sb-item">
            <a href="{{ route('teacher.profile') }}" class="sb-link {{ request()->routeIs('teacher.profile') ? 'active' : '' }}">
                <i class="ri-user-line"></i> প্রোফাইল
            </a>
        </div>
        <div class="sb-divider"></div>
        <div class="sb-item">
            <a href="{{ route('home') }}" class="sb-link" target="_blank">
                <i class="ri-global-line"></i> ওয়েবসাইট দেখুন
            </a>
        </div>
    </nav>

    @auth('teacher')
    <div class="sb-foot">
        <div class="u-card">
            @if(auth()->guard('teacher')->user()->avatar)
                <img src="{{ asset(auth()->guard('teacher')->user()->avatar) }}" alt="Avatar"
                     class="u-av" style="object-fit:cover; font-size:0;">
            @else
                <div class="u-av">{{ strtoupper(substr(auth()->guard('teacher')->user()->name, 0, 1)) }}</div>
            @endif
            <div style="flex:1; overflow:hidden;">
                <div style="font-size:13px; font-weight:700; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">
                    {{ auth()->guard('teacher')->user()->name }}
                </div>
                <div style="font-size:11px; color:rgba(255,255,255,.45);">
                    {{ auth()->guard('teacher')->user()->subject ?? 'শিক্ষক' }}
                </div>
            </div>
            <form action="{{ route('teacher.logout') }}" method="POST" style="margin:0;">
                @csrf
                <button type="submit" style="background:none;border:none;cursor:pointer;color:#ef4444;font-size:18px;" title="লগআউট">
                    <i class="ri-logout-box-r-line"></i>
                </button>
            </form>
        </div>
    </div>
    @endauth
</div>
