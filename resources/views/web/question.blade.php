@extends('layouts.web', ['title' => $model->name])

@push('style')
<style>
    /* PREMIUM THEME OVERRIDE */
    body { background-color: #07091e !important; color: #fff !important; }
    .main-wrapper { background: transparent !important; }
    
    .section-premium { position: relative; z-index: 1; padding: 140px 0 60px; }
    
    /* BREADCRUMB */
    .premium-breadcrumb {
        background: rgba(255,255,255,0.03);
        border: 1px solid rgba(255,255,255,0.08);
        border-radius: 12px; padding: 12px 24px;
        margin-bottom: 40px; display: flex; align-items: center; gap: 10px;
        flex-wrap: wrap;
    }
    .premium-breadcrumb a { color: var(--text-light); text-decoration: none; font-size: 14px; transition: color .2s; }
    .premium-breadcrumb a:hover { color: var(--accent-gold); }
    .premium-breadcrumb span { color: rgba(255,255,255,0.3); font-size: 14px; }
    .premium-breadcrumb .active { color: #fff; font-weight: 600; }

    /* TITLE SECTION */
    .exam-header-premium {
        background: rgba(255,255,255,0.04);
        border: 1px solid rgba(255,255,255,0.08);
        border-radius: 20px; padding: 40px;
        margin-bottom: 48px; position: relative; overflow: hidden;
    }
    .exam-title-premium {
        font-family: 'Noto Serif Bengali', serif;
        font-size: clamp(24px, 3.5vw, 36px); font-weight: 800; color: #fff;
        margin-bottom: 16px; line-height: 1.3;
    }
    .header-actions { display: flex; gap: 12px; flex-wrap: wrap; margin-top: 24px; }
    .btn-premium {
        display: inline-flex; align-items: center; gap: 8px;
        padding: 10px 24px; border-radius: 100px;
        font-weight: 700; font-size: 14px; text-decoration: none !important;
        transition: all .2s; cursor: pointer; border: none;
    }
    .btn-gold { background: var(--accent-gold); color: #07091e !important; box-shadow: 0 4px 15px rgba(245,197,24,0.3); }
    .btn-outline { background: rgba(255,255,255,0.06); border: 1px solid rgba(255,255,255,0.1); color: #fff !important; }
    .btn-premium:hover { transform: translateY(-2px); filter: brightness(1.1); }

    /* TABS */
    .nav-tabs-premium { border-bottom: 1px solid rgba(255,255,255,0.1); margin-bottom: 30px; gap: 10px; }
    .nav-tabs-premium .nav-link {
        color: rgba(255,255,255,0.6); border: none !important;
        padding: 12px 24px; font-weight: 600; font-size: 15px;
        background: transparent !important; transition: all .2s;
    }
    .nav-tabs-premium .nav-link:hover { color: #fff; }
    .nav-tabs-premium .nav-link.active {
        color: var(--accent-gold) !important;
        border-bottom: 2px solid var(--accent-gold) !important;
    }

    @media (max-width: 768px) {
        .nav-tabs-premium {
            flex-wrap: nowrap;
            overflow-x: auto;
            padding-bottom: 5px;
            -webkit-overflow-scrolling: touch;
        }
        .nav-tabs-premium::-webkit-scrollbar { height: 4px; }
        .nav-tabs-premium::-webkit-scrollbar-thumb { background: rgba(255,255,255,0.1); border-radius: 10px; }
        .nav-tabs-premium .nav-link { white-space: nowrap; padding: 10px 16px; font-size: 14px; }
    }

    /* QUESTION CARDS */
    .q-card-premium {
        background: rgba(255,255,255,0.05) !important;
        border: 1px solid rgba(255,255,255,0.08) !important;
        border-radius: 18px; padding: 28px; margin-bottom: 24px;
        transition: border-color .3s;
    }
    .q-card-premium:hover { border-color: rgba(245,197,24,0.2) !important; }
    
    .q-text-premium {
        font-size: 18px; font-weight: 700; color: #fff;
        line-height: 1.6; margin-bottom: 24px; display: flex; gap: 12px;
    }
    .q-num { color: var(--accent-gold); }
    
    .opt-grid { display: grid; grid-template-columns: repeat(2, 1fr); gap: 12px; }
    .opt-box {
        background: rgba(255,255,255,0.04);
        border: 1px solid rgba(255,255,255,0.07);
        border-radius: 10px; padding: 14px 18px;
        font-size: 15px; color: #cbd5e1;
        transition: all .2s; cursor: pointer;
        display: flex;
        align-items: center;
    }
    .opt-box p {
        margin: 0 !important;
        display: inline !important;
    }
    .opt-box:hover { background: rgba(255,255,255,0.08); border-color: rgba(255,255,255,0.2); color: #fff; }
    .opt-correct {
        background: rgba(34,197,94,0.1) !important;
        border-color: rgba(34,197,94,0.3) !important;
        color: #22c55e !important; font-weight: 600;
    }
    .opt-correct p, .opt-correct span:not(.opt-label) {
        color: #22c55e !important;
    }
    .opt-label { font-weight: 800; color: var(--accent-gold); margin-right: 10px; }

    /* SIDEBAR */
    .sidebar-widget-premium {
        background: rgba(255,255,255,0.04);
        border: 1px solid rgba(255,255,255,0.08);
        border-radius: 20px; padding: 24px;
    }
    .widget-title-premium {
        font-family: 'Noto Serif Bengali', serif;
        font-size: 20px; font-weight: 700; color: #fff;
        padding-bottom: 16px; border-bottom: 1px solid rgba(255,255,255,0.1);
        margin-bottom: 20px;
    }
    .widget-link {
        display: block; padding: 10px 0; color: var(--text-light);
        text-decoration: none; font-size: 15px; transition: all .2s;
        border-bottom: 1px solid rgba(255,255,255,0.05);
    }
    .widget-link:hover, .widget-link.active { color: var(--accent-gold); padding-left: 6px; }
    .widget-link.active { font-weight: 700; }

    /* PROGRESS OVERLAY */
    #pdf-progress-overlay {
        display: none; position: fixed; inset: 0;
        background: rgba(7,9,30,0.9); z-index: 9999;
        align-items: center; justify-content: center; backdrop-filter: blur(8px);
    }
    #pdf-progress-overlay.show { display: flex; }
    .progress-box {
        text-align: center; background: rgba(255,255,255,0.05);
        border: 1px solid rgba(255,255,255,0.1);
        padding: 40px; border-radius: 24px; width: 320px;
    }

    @media (max-width: 768px) {
        .section-premium { padding: 100px 16px 40px; }
        .opt-grid { grid-template-columns: 1fr; }
        .exam-header-premium { padding: 30px 20px; margin-bottom: 30px; }
        .exam-title-premium { font-size: 20px; }
        .header-actions { display: flex; flex-wrap: wrap; gap: 10px; margin-top: 20px; }
        .header-actions .btn-premium { width: 100%; justify-content: center; border-radius: 12px; }
        .header-actions .btn-gold { order: 1; padding: 14px; font-size: 16px; background: linear-gradient(135deg, #f5c518, #ff8a00); }
        .header-actions .btn-outline { width: calc(50% - 5px); order: 2; padding: 12px 5px; font-size: 11px; }
        .q-card-premium { padding: 20px; }
        .q-text-premium { font-size: 16px; margin-bottom: 18px; }
        .premium-breadcrumb { padding: 10px 16px; margin-bottom: 24px; font-size: 13px; }
    }

    /* PDF GENERATION STYLES */
    .pdf-page {
        background: #ffffff !important;
        color: #000000 !important;
        font-family: 'Hind Siliguri', 'Inter', sans-serif !important;
        padding: 20px !important;
        box-sizing: border-box !important;
    }
    .pdf-header {
        border-bottom: 2px solid #1B4F72 !important;
        padding-bottom: 12px !important;
        margin-bottom: 20px !important;
        text-align: center !important;
    }
    .pdf-header h1 {
        font-size: 24px !important;
        font-weight: 700 !important;
        color: #1B4F72 !important;
        margin-bottom: 6px !important;
    }
    .pdf-header p {
        font-size: 11px !important;
        color: #555555 !important;
        margin: 0 !important;
    }
    .pdf-columns {
        column-count: 2 !important;
        column-gap: 20px !important;
        column-rule: 1px dashed #cccccc !important;
    }
    .pdf-category-title {
        font-size: 14px !important;
        font-weight: 700 !important;
        color: #1B4F72 !important;
        border-bottom: 1px solid #1B4F72 !important;
        padding-bottom: 4px !important;
        margin-top: 15px !important;
        margin-bottom: 10px !important;
        break-inside: avoid !important;
    }
    .pdf-passage {
        background: #f8fafc !important;
        border: 1px solid #e2e8f0 !important;
        border-radius: 6px !important;
        padding: 8px 12px !important;
        margin-bottom: 12px !important;
        font-size: 10px !important;
        color: #334155 !important;
        break-inside: avoid !important;
    }
    .pdf-question {
        margin-bottom: 14px !important;
        break-inside: avoid !important;
        font-size: 11px !important;
        line-height: 1.5 !important;
    }
    .pdf-question-text {
        font-weight: 600 !important;
        color: #0f172a !important;
        margin-bottom: 6px !important;
    }
    .pdf-options {
        display: grid !important;
        grid-template-columns: repeat(2, 1fr) !important;
        gap: 4px !important;
        margin-bottom: 4px !important;
    }
    .pdf-option {
        display: flex !important;
        align-items: center !important;
        gap: 6px !important;
        font-size: 10px !important;
        color: #334155 !important;
        padding: 2px 4px !important;
    }
    .pdf-option-badge {
        display: inline-flex !important;
        align-items: center !important;
        justify-content: center !important;
        width: 16px !important;
        height: 16px !important;
        border: 1px solid #1B4F72 !important;
        border-radius: 50% !important;
        font-size: 9px !important;
        font-weight: 600 !important;
        color: #1B4F72 !important;
        line-height: 1 !important;
        flex-shrink: 0 !important;
    }
    .pdf-option-text {
        font-size: 10px !important;
    }
    .pdf-option.correct {
        text-decoration: none !important;
    }
    .pdf-option.correct .pdf-option-badge {
        background-color: #16a34a !important;
        border-color: #16a34a !important;
        color: #ffffff !important;
    }
    .pdf-option.correct .pdf-option-text {
        color: #16a34a !important;
        font-weight: 700 !important;
    }
</style>
@endpush

@section('content')
<div class="container section-premium">
    @php
        $toBn = function($num) {
            $en = ['0','1','2','3','4','5','6','7','8','9'];
            $bn = ['০','১','২','৩','৪','৫','৬','৭','৮','৯'];
            return str_replace($en, $bn, (string)$num);
        };
        $isStudent = auth()->guard('student')->check();
    @endphp

    <!-- BREADCRUMB -->
    <div class="premium-breadcrumb">
        <a href="{{ route('home') }}">হোম</a>
        @foreach ($breadcrumbs as $cat)
            <span>/</span>
            <a href="{{ route('slug.handle', $cat->slug) }}">{{ $cat->name }}</a>
        @endforeach
        <span>/</span>
        <span class="active">{{ $model->name }}</span>
    </div>

    <!-- HEADER -->
    <div class="exam-header-premium">
        <div style="position: absolute; top: -50px; right: -50px; font-size: 180px; opacity: 0.03; pointer-events: none;">📝</div>
        <h1 class="exam-title-premium">{{ $model->name }}</h1>
        <div class="header-actions">
            <a href="{{ $isStudent ? route('exam.show', $model->slug) : 'javascript:void(0)' }}" 
               onclick="{{ !$isStudent ? "toastr.error('পরীক্ষা দিতে প্রথমে লগইন করুন')" : "" }}"
               class="btn-premium btn-gold">
                🚀 পরীক্ষা দিন <i class="ri-edit-line"></i>
            </a>
            <button onclick="downloadPDF()" class="btn-premium btn-outline">
                📄 পিডিএফ ডাউনলোড <i class="ri-file-pdf-line"></i>
            </button>
            <button onclick="sharePage()" class="btn-premium btn-outline">
                🔗 শেয়ার করুন <i class="ri-share-line"></i>
            </button>
        </div>
    </div>

    <div class="row g-5">
        <div class="col-lg-8">
            <!-- TABS -->
            <ul class="nav nav-tabs nav-tabs-premium" id="examTabs" role="tablist">
                <li class="nav-item">
                    <button class="nav-link active" id="all-tab" data-bs-toggle="tab" data-bs-target="#all-pane" type="button">সব প্রশ্ন</button>
                </li>
                @foreach ($categories as $cat)
                    <li class="nav-item">
                        <button class="nav-link" data-bs-toggle="tab" data-bs-target="#cat-pane-{{ $cat['id'] }}" type="button">{{ $cat['name'] }}</button>
                    </li>
                @endforeach
            </ul>

            <div class="tab-content">
                <!-- ALL QUESTIONS PANE -->
                <div class="tab-pane fade show active" id="all-pane">
                    @php $counter = 0; @endphp
                    @foreach ($final as $categoryBlock)
                        <div style="margin: 40px 0 24px;">
                            <h3 style="font-family: 'Noto Serif Bengali', serif; font-size: 22px; color: var(--accent-gold); display: flex; align-items: center; gap: 12px;">
                                <span style="height: 2px; width: 30px; background: var(--accent-gold); opacity: 0.5;"></span>
                                {{ $categoryBlock['category_name'] }}
                            </h3>
                        </div>

                        @foreach ($categoryBlock['groups'] as $group)
                            @if (!empty($group['passage_text']))
                                <div style="background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.08); border-radius: 12px; padding: 20px; margin-bottom: 24px; font-size: 15px; color: #ccc; line-height: 1.7;">
                                    <strong style="color: #fff; margin-bottom: 8px; display: block;">📖 {{ $group['passage_name'] }}</strong>
                                    {!! $group['passage_text'] !!}
                                </div>
                            @endif

                            @foreach ($group['questions'] as $q)
                                @php
                                    $uniqueId = 'exp_' . $q['id'] . '_all';
                                    $correct = intval($q['correct_answer']);
                                    $labels = ['ক','খ','গ','ঘ','ঙ'];
                                @endphp
                                <div class="q-card-premium">
                                    <div class="q-text-premium">
                                        <span class="q-num">{{ $toBn(++$counter) }}.</span>
                                        <div>{!! $q['question'] !!}</div>
                                    </div>
                                    <div class="opt-grid">
                                        @foreach ($q['options'] as $i => $opt)
                                            @if($opt)
                                                <div class="opt-box {{ ($i+1)==$correct ? 'opt-correct' : '' }}">
                                                    <span class="opt-label">{{ $labels[$i] }}</span> {!! $opt !!}
                                                </div>
                                            @endif
                                        @endforeach
                                    </div>

                                    <!-- TAGS & ACTION -->
                                    <div style="margin-top: 24px; display: flex; flex-wrap: wrap; align-items: center; justify-content: space-between; gap: 16px; padding-top: 20px; border-top: 1px solid rgba(255,255,255,0.06);">
                                        <div style="display: flex; flex-wrap: wrap; gap: 8px;">
                                            @if(!empty($q['job_category_name']))
                                                <a href="{{ route('slug.handle', $q['job_category_slug']) }}" style="font-size: 11px; font-weight: 600; padding: 4px 12px; border-radius: 100px; background: rgba(34, 197, 94, 0.1); color: #22c55e; border: 1px solid rgba(34, 197, 94, 0.2); text-decoration: none;">{{ $q['job_category_name'] }}</a>
                                            @endif
                                            @if(!empty($q['exam_name']))
                                                <a href="{{ route('slug.handle', $q['exam_slug']) }}" style="font-size: 11px; font-weight: 600; padding: 4px 12px; border-radius: 100px; background: rgba(245, 158, 11, 0.1); color: #f59e0b; border: 1px solid rgba(245, 158, 11, 0.2); text-decoration: none;">{{ $q['exam_name'] }}</a>
                                            @endif
                                            @if(!empty($q['category_name']))
                                                <a href="{{ route('slug.handle', $q['category_slug']) }}" style="font-size: 11px; font-weight: 600; padding: 4px 12px; border-radius: 100px; background: rgba(59, 130, 246, 0.1); color: #3b82f6; border: 1px solid rgba(59, 130, 246, 0.2); text-decoration: none;">{{ $q['category_name'] }}</a>
                                            @endif
                                        </div>

                                        @if($q['content'])
                                            <button class="btn-premium btn-outline" style="font-size: 12px; padding: 6px 20px; border-radius: 8px;" type="button" data-bs-toggle="collapse" data-bs-target="#{{ $uniqueId }}">
                                                💡 ব্যাখ্যা দেখুন
                                            </button>
                                        @endif
                                    </div>

                                    @if($q['content'])
                                        <div class="collapse" id="{{ $uniqueId }}">
                                            <div style="background: rgba(255,255,255,0.03); border-left: 3px solid var(--accent-gold); padding: 20px; margin-top: 16px; color: #cbd5e1; font-size: 14px; line-height: 1.7; border-radius: 0 12px 12px 0;">
                                                <strong style="color: var(--accent-gold); display: block; margin-bottom: 8px;">ব্যাখ্যা:</strong>
                                                {!! $q['content'] !!}
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        @endforeach
                    @endforeach
                </div>

                <!-- CATEGORY PANES -->
                @foreach ($categories as $cat)
                    <div class="tab-pane fade" id="cat-pane-{{ $cat['id'] }}">
                        @php $counter = 0; @endphp
                        @foreach ($final as $categoryBlock)
                            @if($categoryBlock['category_id'] == $cat['id'])
                                @foreach ($categoryBlock['groups'] as $group)
                                    @foreach ($group['questions'] as $q)
                                        @php
                                            $uniqueId = 'exp_' . $q['id'] . '_' . $cat['id'];
                                            $correct = intval($q['correct_answer']);
                                            $labels = ['ক','খ','গ','ঘ','ঙ'];
                                        @endphp
                                        <div class="q-card-premium">
                                            <div class="q-text-premium">
                                                <span class="q-num">{{ $toBn(++$counter) }}.</span>
                                                <div>{!! $q['question'] !!}</div>
                                            </div>
                                            <div class="opt-grid">
                                                @foreach ($q['options'] as $i => $opt)
                                                    @if($opt)
                                                        <div class="opt-box {{ ($i+1)==$correct ? 'opt-correct' : '' }}">
                                                            <span class="opt-label">{{ $labels[$i] }}</span> {!! $opt !!}
                                                        </div>
                                                    @endif
                                                @endforeach
                                            </div>
                                        </div>
                                    @endforeach
                                @endforeach
                            @endif
                        @endforeach
                    </div>
                @endforeach
            </div>
        </div>

        <!-- SIDEBAR -->
        <div class="col-lg-4">
            <aside class="sidebar-widget-premium">
                <h5 class="widget-title-premium">{{ $mainCategory->name }}</h5>
                <div class="widget-content">
                    @php
                        $sidebarExams = App\Models\JobCategory::where('category_id', $mainCategory->id)->where('status', 1)->get();
                        $sidebarExams = $sidebarExams->sortByDesc(function ($sExam) {
                            if (preg_match('/\((\d{2}-\d{2}-\d{4})\)/', $sExam->name, $matches)) {
                                $parts = explode('-', $matches[1]);
                                if (count($parts) === 3) {
                                    return $parts[2] . '-' . $parts[1] . '-' . $parts[0];
                                }
                            }
                            return '0000-00-00';
                        })->take(15);
                    @endphp
                    @foreach ($sidebarExams as $sExam)
                        <a href="{{ route('slug.handle', $sExam->slug) }}" class="widget-link {{ $model->id == $sExam->id ? 'active' : '' }}">
                            {{ $sExam->name }}
                        </a>
                    @endforeach
                </div>
            </aside>
        </div>
    </div>
</div>

{{-- PDF Progress --}}
<div id="pdf-progress-overlay">
    <div class="progress-box">
        <div style="font-size: 48px; color: var(--accent-gold); margin-bottom: 20px; animation: pulse 1.5s infinite;">📄</div>
        <h4 style="font-family: 'Noto Serif Bengali', serif; color: #fff;">পিডিএফ তৈরি হচ্ছে...</h4>
        <p style="color: var(--text-light); font-size: 13px;">অনুগ্রহ করে কিছুক্ষণ অপেক্ষা করুন।</p>
    </div>
</div>

{{-- Hidden PDF Content (2-column A4 layout) --}}
<div id="pdf-content-all" style="display:none;">
    <div class="pdf-page">
        <div class="pdf-header">
            <h1>{{ $model->name }}</h1>
            <p>{{ implode(' > ', array_column($breadcrumbs->toArray(), 'name')) }} &nbsp;|&nbsp; প্রত্যয় একাডেমি &nbsp;|&nbsp; prottoyacademy.com</p>
        </div>
        <div class="pdf-columns">
            @php $pdfCounter = 0; @endphp
            @foreach ($final as $categoryBlock)
                <div class="pdf-category-title">{{ $categoryBlock['category_name'] }}</div>
                @foreach ($categoryBlock['groups'] as $group)
                    @php $hasPassage = !empty($group['passage_id']) && trim($group['passage_text']) !== ''; @endphp
                    @if ($hasPassage)
                        <div class="pdf-passage">
                            <strong>{{ $group['passage_name'] }}</strong><br>
                            {!! strip_tags($group['passage_text']) !!}
                        </div>
                    @endif
                    @foreach ($group['questions'] as $q)
                        @php
                            $correct = intval($q['correct_answer']);
                            $labels = ['ক','খ','গ','ঘ','ঙ'];
                        @endphp
                        <div class="pdf-question">
                            <div class="pdf-question-text">{{ ++$pdfCounter }}. {!! strip_tags($q['question']) !!}</div>
                            @if ($q['type'] === 'mcq')
                                <div class="pdf-options">
                                    @foreach ($q['options'] as $i => $opt)
                                        @if ($opt)
                                            <div class="pdf-option {{ ($i+1)==$correct ? 'correct' : '' }}">
                                                <span class="pdf-option-badge">{{ $labels[$i] }}</span>
                                                <span class="pdf-option-text">{!! strip_tags($opt) !!}</span>
                                            </div>
                                        @endif
                                    @endforeach
                                </div>
                            @else
                                <div style="color:#008000;font-size:9.5px;font-weight:600;">উত্তর: {!! strip_tags($q['correct_answer']) !!}</div>
                            @endif
                        </div>
                    @endforeach
                @endforeach
            @endforeach
        </div>
    </div>
</div>

@foreach ($categories as $cat)
<div id="pdf-content-{{ $cat['id'] }}" style="display:none;">
    <div class="pdf-page">
        <div class="pdf-header">
            <h1>{{ $model->name }} - {{ $cat['name'] }}</h1>
            <p>{{ implode(' > ', array_column($breadcrumbs->toArray(), 'name')) }} &nbsp;|&nbsp; প্রত্যয় একাডেমি &nbsp;|&nbsp; prottoyacademy.com</p>
        </div>
        <div class="pdf-columns">
            @php $pdfCounter = 0; @endphp
            @foreach ($final as $categoryBlock)
                @if ($categoryBlock['category_id'] == $cat['id'])
                    <div class="pdf-category-title">{{ $categoryBlock['category_name'] }}</div>
                    @foreach ($categoryBlock['groups'] as $group)
                        @php $hasPassage = !empty($group['passage_id']) && trim($group['passage_text']) !== ''; @endphp
                        @if ($hasPassage)
                            <div class="pdf-passage">
                                <strong>{{ $group['passage_name'] }}</strong><br>
                                {!! strip_tags($group['passage_text']) !!}
                            </div>
                        @endif
                        @foreach ($group['questions'] as $q)
                            @php
                                $correct = intval($q['correct_answer']);
                                $labels = ['ক','খ','গ','ঘ','ঙ'];
                            @endphp
                            <div class="pdf-question">
                                <div class="pdf-question-text">{{ ++$pdfCounter }}. {!! strip_tags($q['question']) !!}</div>
                                @if ($q['type'] === 'mcq')
                                    <div class="pdf-options">
                                        @foreach ($q['options'] as $i => $opt)
                                            @if ($opt)
                                                <div class="pdf-option {{ ($i+1)==$correct ? 'correct' : '' }}">
                                                    <span class="pdf-option-badge">{{ $labels[$i] }}</span>
                                                    <span class="pdf-option-text">{!! strip_tags($opt) !!}</span>
                                                </div>
                                            @endif
                                        @endforeach
                                    </div>
                                @else
                                    <div style="color:#008000;font-size:9.5px;font-weight:600;">উত্তর: {!! strip_tags($q['correct_answer']) !!}</div>
                                @endif
                            </div>
                        @endforeach
                    @endforeach
                @endif
            @endforeach
        </div>
    </div>
</div>
@endforeach

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/mathjax@3/es5/tex-mml-chtml.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
<script>
    function downloadPDF() {
        $('#pdf-progress-overlay').addClass('show');
        
        // Find which tab is active to print only its content or all content
        const activeTab = $('.nav-tabs-premium .nav-link.active');
        let elementId = 'pdf-content-all';
        
        if (activeTab.length > 0) {
            const activeTabTarget = activeTab.attr('data-bs-target');
            if (activeTabTarget && activeTabTarget !== '#all-pane') {
                const catId = activeTabTarget.replace('#cat-pane-', '');
                elementId = 'pdf-content-' + catId;
            }
        }
        
        const element = document.getElementById(elementId);
        if (!element) {
            $('#pdf-progress-overlay').removeClass('show');
            toastr.error('পিডিএফ কন্টেন্ট পাওয়া যায়নি!');
            return;
        }
        
        // Temporarily show the hidden element for html2pdf to render
        const originalStyle = element.style.display;
        element.style.display = 'block';
        
        const opt = {
            margin:       [10, 10, 15, 10], // top, left, bottom, right
            filename:     '{{ $model->name }}.pdf',
            image:        { type: 'jpeg', quality: 0.98 },
            html2canvas:  { scale: 2, useCORS: true, logging: false },
            jsPDF:        { unit: 'mm', format: 'a4', orientation: 'portrait' }
        };
        
        // Use html2pdf to generate and save the PDF
        html2pdf().set(opt).from(element).save().then(() => {
            element.style.display = originalStyle;
            $('#pdf-progress-overlay').removeClass('show');
            toastr.success('পিডিএফ ডাউনলোড সম্পন্ন হয়েছে!');
        }).catch(err => {
            console.error('PDF Generation Error:', err);
            element.style.display = originalStyle;
            $('#pdf-progress-overlay').removeClass('show');
            toastr.error('পিডিএফ ডাউনলোড করতে ব্যর্থ হয়েছে!');
        });
    }

    async function sharePage() {
        const shareData = {
            title: '{{ $model->name }} - প্রত্যয় একাডেমি',
            text: 'প্রত্যয় একাডেমিতে এই প্রশ্নপত্রটি দেখুন।',
            url: window.location.href
        };

        if (navigator.share) {
            try {
                await navigator.share(shareData);
            } catch (err) {
                console.log('Error sharing:', err);
            }
        } else {
            // Fallback: Copy to clipboard
            const el = document.createElement('textarea');
            el.value = window.location.href;
            document.body.appendChild(el);
            el.select();
            document.execCommand('copy');
            document.body.removeChild(el);
            toastr.info('লিঙ্কটি কপি করা হয়েছে!');
        }
    }
</script>
@endpush
