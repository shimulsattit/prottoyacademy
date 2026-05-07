<div class="student-sidebar">
    <div class="sidebar-header px-4 py-4 d-flex align-items-center">
        <div class="logo-area">
            <h4 class="fw-bold mb-0" style="background: linear-gradient(135deg, #f5c518, #ff8a00); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">Prottoy</h4>
        </div>
    </div>

    <div class="sidebar-menu mt-2">
        <ul class="nav flex-column px-3">
            <li class="nav-item mb-2">
                <a href="{{ route('student.dashboard') }}" class="nav-link {{ Request::is('student/dashboard') ? 'active' : '' }}">
                    <i class="ri-dashboard-3-line me-3"></i> ড্যাশবোর্ড
                </a>
            </li>
            <li class="nav-item mb-2">
                @php
                    $isQuestionBankActive = Request::is('category/*') || Request::is('job-solution*') || Request::is('bank*') || Request::is('admission*');
                @endphp
                <a href="#questionBankSubmenu" class="nav-link d-flex justify-content-between align-items-center {{ $isQuestionBankActive ? 'active' : '' }}" data-bs-toggle="collapse" role="button" aria-expanded="{{ $isQuestionBankActive ? 'true' : 'false' }}">
                    <span><i class="ri-book-3-line me-3"></i> প্রশ্ন ব্যাংক</span>
                    <i class="ri-arrow-down-s-line"></i>
                </a>
                <div class="collapse {{ $isQuestionBankActive ? 'show' : '' }} ps-4" id="questionBankSubmenu">
                    <ul class="nav flex-column sub-menu">
                        <li class="nav-item">
                            <a href="/job-solution" class="nav-link py-2 small {{ Request::is('job-solution*') ? 'active' : '' }}">Job Solution</a>
                        </li>
                        <li class="nav-item">
                            <a href="/bank" class="nav-link py-2 small {{ Request::is('bank*') ? 'active' : '' }}">Bank Job</a>
                        </li>
                        <li class="nav-item">
                            <a href="/admission" class="nav-link py-2 small {{ Request::is('admission*') ? 'active' : '' }}">Admission</a>
                        </li>
                    </ul>
                </div>
            </li>
            <li class="nav-item mb-2">
                <a href="/admission" class="nav-link {{ Request::is('admission*') ? 'active' : '' }}">
                    <i class="ri-flashlight-line me-3"></i> দ্রুত প্র্যাকটিস
                </a>
            </li>
            <li class="nav-item mb-2">
                <a href="{{ route('student.exams') }}" class="nav-link {{ Request::is('student/exams') ? 'active' : '' }}">
                    <i class="ri-article-line me-3"></i> মডেল টেস্ট
                </a>
            </li>
            <li class="nav-item mb-2">
                <a href="{{ route('student.profile') }}" class="nav-link {{ Request::is('student/profile') ? 'active' : '' }}">
                    <i class="ri-user-6-line me-3"></i> প্রোফাইল-রিপোর্ট
                </a>
            </li>
        </ul>
    </div>

    @if(auth()->guard('student')->check())
    <div class="sidebar-footer px-3 py-4 mt-auto">
        <div class="user-card-glass p-3 d-flex align-items-center">
            <div class="user-avatar-mini me-3">
                @if(auth()->guard('student')->user()->profile_photo_path)
                    <img src="{{ asset(auth()->guard('student')->user()->profile_photo_path) }}" alt="Avatar" class="rounded-circle" style="width: 40px; height: 40px; object-fit: cover; border: 2px solid rgba(245, 197, 24, 0.5);">
                @else
                    <div class="avatar-circle-sm">{{ strtoupper(substr(auth()->guard('student')->user()->name, 0, 1)) }}</div>
                @endif
            </div>
            <div class="user-info-mini overflow-hidden">
                <h6 class="text-white mb-0 small text-truncate fw-bold">{{ auth()->guard('student')->user()->name }}</h6>
                <form action="{{ route('student.logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="text-danger-custom small border-0 bg-transparent p-0" style="font-size: 11px;">লগআউট</button>
                </form>
            </div>
        </div>
    </div>
    @else
    <div class="sidebar-footer px-4 py-4 mt-auto">
        <a href="{{ route('login') }}" class="btn-premium-small w-100 py-2 rounded-3 fw-bold text-center">লগইন করুন</a>
    </div>
    @endif
</div>

<style>
    .student-sidebar {
        background: rgba(255, 255, 255, 0.02);
        backdrop-filter: blur(20px);
        -webkit-backdrop-filter: blur(20px);
        height: 100vh;
        position: fixed;
        left: 0;
        top: 0;
        display: flex;
        flex-direction: column;
        border-right: 1px solid rgba(255, 255, 255, 0.08);
        width: 260px;
        z-index: 1001;
    }

    @media (max-width: 991px) {
        .student-sidebar {
            left: -280px;
            width: 280px;
            background: #07091e;
        }
        .student-sidebar.active {
            left: 0;
        }
    }

    .nav-link {
        color: rgba(255, 255, 255, 0.6);
        padding: 12px 18px;
        border-radius: 14px;
        font-weight: 500;
        font-size: 16px;
        transition: all 0.3s ease;
        margin: 0 5px;
    }
    .nav-link:hover {
        color: #fff;
        background: rgba(255, 255, 255, 0.05);
        transform: translateX(5px);
    }
    .nav-link.active {
        background: linear-gradient(135deg, rgba(245, 197, 24, 0.1), rgba(245, 197, 24, 0.2));
        color: #f5c518;
        border-left: 3px solid #f5c518;
    }
    
    .sub-menu .nav-link {
        font-size: 14px;
        padding: 8px 15px;
        margin-left: 10px;
    }
    
    .user-card-glass {
        background: rgba(255, 255, 255, 0.04);
        border: 1px solid rgba(255, 255, 255, 0.08);
        border-radius: 18px;
    }
    
    .avatar-circle-sm {
        width: 40px;
        height: 40px;
        background: linear-gradient(135deg, #f5c518, #ff8a00);
        color: #07091e;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 800;
        font-size: 16px;
    }
    
    .text-danger-custom {
        color: #ef4444;
        transition: opacity 0.2s;
    }
    .text-danger-custom:hover {
        opacity: 0.8;
    }
</style>
