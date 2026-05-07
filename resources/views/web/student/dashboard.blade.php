@extends('layouts.web', ['title' => 'ড্যাশবোর্ড | প্রত্যয় একাডেমি'])

@push('style')
<style>
    :root {
        --dark-bg: #07091e;
        --card-bg: rgba(255, 255, 255, 0.03);
        --glass-border: rgba(255, 255, 255, 0.08);
        --accent-gold: #f5c518;
        --text-white: #ffffff;
        --text-gray: rgba(255, 255, 255, 0.6);
    }

    body {
        background-color: var(--dark-bg) !important;
        color: var(--text-white) !important;
        font-family: 'Inter', 'Noto Sans Bengali', sans-serif;
    }
    
    .header-area, .footer-area {
        display: none !important;
    }
    
    .dashboard-wrapper {
        display: block;
        min-height: 100vh;
        background: var(--dark-bg);
    }
    
    .dashboard-content-area {
        margin-left: 260px; /* Account for fixed sidebar */
        padding: 40px;
        min-height: 100vh;
    }

    /* GLASS CARDS */
    .glass-card {
        background: var(--card-bg);
        backdrop-filter: blur(15px);
        -webkit-backdrop-filter: blur(15px);
        border: 1px solid var(--glass-border);
        border-radius: 24px;
        padding: 30px;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        text-decoration: none;
        display: block;
        margin-bottom: 20px;
    }
    
    .premium-nav-card {
        height: 100%;
    }
    
    .premium-nav-card:hover {
        background: rgba(255, 255, 255, 0.06);
        border-color: rgba(245, 197, 24, 0.4);
        transform: translateY(-8px);
    }

    .card-icon-box {
        width: 64px;
        height: 64px;
        border-radius: 20px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 20px;
        font-size: 30px;
    }

    .bg-orange { background: linear-gradient(135deg, rgba(249, 115, 22, 0.2), rgba(249, 115, 22, 0.4)); color: #f97316; }
    .bg-yellow { background: linear-gradient(135deg, rgba(245, 197, 24, 0.2), rgba(245, 197, 24, 0.4)); color: #f5c518; }
    .bg-red { background: linear-gradient(135deg, rgba(239, 68, 68, 0.2), rgba(239, 68, 68, 0.4)); color: #ef4444; }
    .bg-blue { background: linear-gradient(135deg, rgba(59, 130, 246, 0.2), rgba(59, 130, 246, 0.4)); color: #3b82f6; }
    .bg-green { background: linear-gradient(135deg, rgba(34, 197, 94, 0.2), rgba(34, 197, 94, 0.4)); color: #22c55e; }

    .card-title { color: #fff; font-weight: 700; font-size: 1.3rem; margin-bottom: 5px; }

    .progress-item { margin-bottom: 25px; }
    .progress-label { display: flex; justify-content: space-between; margin-bottom: 10px; font-size: 15px; color: var(--text-gray); font-weight: 600; }
    .custom-progress { height: 10px; background: rgba(255, 255, 255, 0.05); border-radius: 20px; overflow: hidden; border: 1px solid rgba(255, 255, 255, 0.05); }
    .progress-fill { height: 100%; background: linear-gradient(90deg, #22c55e, #4ade80); border-radius: 20px; }
    
    .section-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 35px; }
    .section-header h4 { font-weight: 800; font-size: 1.8rem; background: linear-gradient(135deg, #fff, #a5a5a5); -webkit-background-clip: text; -webkit-text-fill-color: transparent; }

    @media (max-width: 991px) {
        .dashboard-content-area { margin-left: 0; padding: 30px 20px; }
        .mobile-header {
            display: flex !important;
            background: rgba(7, 9, 30, 0.95);
            backdrop-filter: blur(15px);
            padding: 15px 25px;
            justify-content: space-between;
            align-items: center;
            border-bottom: 1px solid var(--glass-border);
            position: sticky;
            top: 0;
            z-index: 1002;
        }
    }

    .mobile-header { display: none; }
    .menu-toggle { background: none; border: none; color: #fff; font-size: 26px; cursor: pointer; }
</style>
@endpush

@section('content')
<div class="mobile-header">
    <h5 class="fw-bold mb-0" style="color: var(--accent-gold);">Prottoy</h5>
    <button class="menu-toggle" id="mobile-sidebar-toggle">
        <i class="ri-menu-5-line"></i>
    </button>
</div>

<div class="dashboard-wrapper">
    @include('web.student.partials.sidebar')

    <!-- Main Content -->
    <div class="dashboard-content-area">
        <div class="section-header">
            <h4>ড্যাশবোর্ড</h4>
            <div class="user-stats">
                <div class="badge bg-danger rounded-pill px-3 py-2" style="background: rgba(239,68,68,0.1) !important; color: #ef4444; border: 1px solid rgba(239,68,68,0.2);">
                    🔥 ০ দিন
                </div>
            </div>
        </div>

        <!-- Grid Links -->
        <div class="row g-4">
            <div class="col-md-4">
                <a href="/job-solution" class="glass-card premium-nav-card text-center">
                    <div class="card-icon-box bg-orange mx-auto">
                        <i class="ri-book-read-line"></i>
                    </div>
                    <h5 class="card-title">প্রশ্নব্যাংক</h5>
                </a>
            </div>
            <div class="col-md-4">
                <a href="/admission" class="glass-card premium-nav-card text-center">
                    <div class="card-icon-box bg-yellow mx-auto">
                        <i class="ri-flashlight-line"></i>
                    </div>
                    <h5 class="card-title">দ্রুত প্র্যাকটিস</h5>
                </a>
            </div>
            <div class="col-md-4">
                <a href="{{ route('student.exams') }}" class="glass-card premium-nav-card text-center">
                    <div class="card-icon-box bg-red mx-auto">
                        <i class="ri-article-line"></i>
                    </div>
                    <h5 class="card-title">মক পরীক্ষা</h5>
                </a>
            </div>
        </div>

        <!-- Progress and Banner -->
        <div class="row mt-4 g-4">
            <div class="col-lg-7">
                <div class="glass-card h-100 d-flex align-items-center justify-content-between" style="background: linear-gradient(135deg, rgba(34, 197, 94, 0.05), rgba(34, 197, 94, 0.15)); border-color: rgba(34, 197, 94, 0.2);">
                    <div>
                        <h4 class="mb-2 text-white fw-bold">লিডারবোর্ড আনলক করুন</h4>
                        <p class="text-white-50 mb-0">অংশগ্রহণ করুন এবং অন্যদের সাথে পাল্লা দিন।</p>
                    </div>
                    <i class="ri-medal-line fs-1 text-success"></i>
                </div>
            </div>
            <div class="col-lg-5">
                <div class="glass-card h-100">
                    <h5 class="mb-4 fw-bold">সাবজেক্ট ভিত্তিক রিপোর্ট</h5>
                    <div class="progress-item">
                        <div class="progress-label"><span>বাংলা</span><span>০%</span></div>
                        <div class="custom-progress"><div class="progress-fill" style="width: 0%"></div></div>
                    </div>
                    <div class="progress-item">
                        <div class="progress-label"><span>গণিত</span><span>০%</span></div>
                        <div class="custom-progress"><div class="progress-fill" style="width: 0%"></div></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    $(document).ready(function() {
        $('#mobile-sidebar-toggle').on('click', function() {
            $('.student-sidebar').toggleClass('active');
            $(this).find('i').toggleClass('ri-menu-5-line ri-close-line');
        });

        $(document).on('click', function(e) {
            if ($(window).width() <= 991) {
                if (!$(e.target).closest('.student-sidebar').length && !$(e.target).closest('#mobile-sidebar-toggle').length) {
                    $('.student-sidebar').removeClass('active');
                    $('#mobile-sidebar-toggle').find('i').removeClass('ri-close-line').addClass('ri-menu-5-line');
                }
            }
        });
    });
</script>
@endpush
@endsection
