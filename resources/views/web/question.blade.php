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
    }
    .opt-box:hover { background: rgba(255,255,255,0.08); border-color: rgba(255,255,255,0.2); color: #fff; }
    .opt-correct {
        background: rgba(34,197,94,0.1) !important;
        border-color: rgba(34,197,94,0.3) !important;
        color: #22c55e !important; font-weight: 600;
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
            <button type="button" id="open-exam-modal-btn"
               onclick="{{ !$isStudent ? "toastr.error('পরীক্ষা দিতে লগইন করুন')" : "openExamSetupModal()" }}"
               class="btn-premium btn-gold">
                🖊 পরীক্ষা দাও <i class="ri-edit-line"></i>
            </button>
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
                        $sidebarExams = App\Models\JobCategory::where('category_id', $mainCategory->id)->where('status', 1)->latest()->take(15)->get();
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

{{-- ======================= EXAM SETUP MODAL ======================= --}}
<div class="modal fade" id="examSetupModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-md">
        <div class="modal-content" style="background: #0f1129; border: 1px solid rgba(255,255,255,0.1); border-radius: 24px;">
            <div class="modal-body p-4">
                <div class="text-center mb-4">
                    <div style="width:64px; height:64px; background:rgba(245,197,24,0.1); border:2px solid rgba(245,197,24,0.3); border-radius:50%; display:inline-flex; align-items:center; justify-content:center; margin-bottom:16px;">
                        <i class="ri-settings-4-line" style="font-size:28px; color:#f5c518;"></i>
                    </div>
                    <h4 class="fw-bold text-white mb-1">পরীক্ষার সেটআপ</h4>
                    <p class="text-white-50 small">{{ $model->name }}</p>
                </div>

                <div class="mb-3">
                    <label class="form-label text-white-50 small fw-bold mb-2">বিষয় নির্বাচন</label>
                    <select class="form-select rounded-3 p-3" id="setup-subject" style="background: rgba(15,17,41,0.95); border: 1px solid rgba(255,255,255,0.1); color: #fff;">
                        <option value="all">সকল বিষয় (মিশ্রিত)</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat['id'] }}">{{ $cat['name'] }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label text-white-50 small fw-bold mb-2">প্রশ্ন সংখ্যা / প্রশ্ন সীমা</label>
                    <select class="form-select rounded-3 p-3" id="setup-limit" style="background: rgba(15,17,41,0.95); border: 1px solid rgba(255,255,255,0.1); color: #fff;">
                        <option value="all">সম্পূর্ণ (সকল প্রশ্ন)</option>
                        <option value="10">১০ টি প্রশ্ন (১০ মিনিট)</option>
                        <option value="20">২০ টি প্রশ্ন (২০ মিনিট)</option>
                        <option value="30">৩০ টি প্রশ্ন (৩০ মিনিট)</option>
                        <option value="50">৫০ টি প্রশ্ন (৫০ মিনিট)</option>
                        <option value="100">১০০ টি প্রশ্ন (১০০ মিনিট)</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label text-white-50 small fw-bold mb-2">পরীক্ষার সময় (মিনিট) <span class="text-warning">— ম্যানুয়ালী দিন</span></label>
                    <input type="number" class="form-control rounded-3 p-3" id="setup-duration" value="30" min="1" max="300" placeholder="যেমন: ৩০" style="background: rgba(15,17,41,0.95); border: 1px solid rgba(255,255,255,0.1); color: #fff;">
                    <small class="text-white-50">প্রতিটি প্রশ্নের জন্য ১ মিনিট হিসেবে দেওয়া আছে, পরিবর্তন করুন</small>
                </div>

                <div class="mb-3">
                    <label class="form-label text-white-50 small fw-bold mb-2">নেগেটিভ মার্কিং</label>
                    <select class="form-select rounded-3 p-3" id="setup-negative-mark" style="background: rgba(15,17,41,0.95); border: 1px solid rgba(255,255,255,0.1); color: #fff;">
                        <option value="0.25">০.২৫ মার্কস (প্রতিটি ভুলে কাটা যাবে)</option>
                        <option value="0.50">০.৫০ মার্কস (প্রতিটি ভুলে কাটা যাবে)</option>
                        <option value="0.00">কোনো নেগেটিভ মার্ক নেই</option>
                    </select>
                </div>

                <div class="mb-4">
                    <label class="form-label text-white-50 small fw-bold mb-2">পাস মার্ক (শতকরা হিসেবে) <span class="text-warning">— ম্যানুয়ালী দিন</span></label>
                    <div class="input-group">
                        <input type="number" class="form-control rounded-start-3 p-3" id="setup-pass-mark" value="40" min="0" max="100" placeholder="যেমন: ৪০" style="background: rgba(15,17,41,0.95); border: 1px solid rgba(255,255,255,0.1); color: #fff;">
                        <span style="background: rgba(245,197,24,0.15); border: 1px solid rgba(255,255,255,0.1); border-left:none; padding: 0 16px; display:flex; align-items:center; color:#f5c518; font-weight:700; border-radius:0 12px 12px 0;">%</span>
                    </div>
                    <small class="text-white-50">প্রশ্নের মোট মার্কের কত শতাংশ পেলে পাস</small>
                </div>

                <div class="d-flex gap-3 justify-content-center">
                    <button type="button" class="btn btn-outline-light px-4 rounded-pill fw-bold" data-bs-dismiss="modal">বাতিল করুন</button>
                    <button type="button" class="btn fw-bold px-4 rounded-pill" id="confirm-start-exam-btn" style="background: linear-gradient(135deg, #f5c518, #ff8a00); color: #07091e; border: none;">পরীক্ষা শুরু করুন <i class="ri-arrow-right-line"></i></button>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- ======================= EXAM FULL-SCREEN OVERLAY ======================= --}}
<div id="exam-overlay" style="display:none; position:fixed; inset:0; background:#07091e; z-index:9999; overflow-y:auto; font-family:'Inter','Noto Sans Bengali',sans-serif;">

    {{-- TOP BAR --}}
    <div id="exam-topbar" style="position:sticky; top:0; z-index:100; background:rgba(7,9,30,0.95); backdrop-filter:blur(20px); border-bottom:1px solid rgba(255,255,255,0.08); padding:14px 24px; display:flex; align-items:center; justify-content:space-between;">
        <div style="display:flex; align-items:center; gap:16px;">
            <span style="font-weight:800; color:#f5c518; font-size:18px;">{{ $model->name }}</span>
            <span id="exam-subject-tag" style="background:rgba(255,255,255,0.06); color:rgba(255,255,255,0.5); padding:4px 14px; border-radius:50px; font-size:12px; border:1px solid rgba(255,255,255,0.08);"></span>
        </div>
        <div style="display:flex; align-items:center; gap:20px;">
            <div style="text-align:center;">
                <div style="font-size:10px; color:rgba(255,255,255,0.4); text-transform:uppercase; letter-spacing:0.5px;">অবশিষ্ট সময়</div>
                <div id="exam-timer" style="font-size:22px; font-weight:800; color:#f5c518; font-variant-numeric:tabular-nums;">00:00</div>
            </div>
        </div>
    </div>

    {{-- PROGRESS BAR --}}
    <div style="height:4px; background:rgba(255,255,255,0.05);">
        <div id="exam-progress-bar" style="height:100%; width:0%; background:linear-gradient(90deg,#f5c518,#22c55e); transition:width 0.4s ease;"></div>
    </div>

    {{-- CONTENT --}}
    <div style="max-width:860px; margin:0 auto; padding:32px 20px 160px;">
        <div id="exam-page-info" style="color:rgba(255,255,255,0.3); font-size:13px; margin-bottom:20px;"></div>
        <div id="exam-questions-area"></div>

        {{-- PAGINATION --}}
        <div style="display:flex; gap:12px; margin-top:24px;">
            <button id="exam-prev-btn" class="btn btn-outline-light rounded-pill px-4" style="display:none;"><i class="ri-arrow-left-line"></i> আগের পাতা</button>
            <button id="exam-next-btn" class="btn btn-outline-secondary rounded-pill px-4" style="display:none;">পরের পাতা <i class="ri-arrow-right-line"></i></button>
        </div>
    </div>

    {{-- BOTTOM SUBMIT BAR --}}
    <div id="exam-bottom-bar" style="position:fixed; bottom:20px; left:50%; transform:translateX(-50%); width:90%; max-width:800px; background:rgba(255,255,255,0.07); backdrop-filter:blur(20px); border:1px solid rgba(255,255,255,0.1); border-radius:100px; padding:0 28px; height:68px; display:flex; align-items:center; justify-content:space-between; z-index:200; box-shadow:0 10px 40px rgba(0,0,0,0.5);">
        <div style="display:flex; align-items:center; gap:20px; font-weight:700; font-size:16px; color:#fff;">
            <span><i class="ri-time-line" style="color:#f5c518;"></i> <span id="bar-timer">00:00</span></span>
            <span style="color:rgba(255,255,255,0.3);">|</span>
            <span><i class="ri-check-line" style="color:#22c55e;"></i> <span id="bar-progress">0/0</span></span>
        </div>
        <button id="exam-submit-btn" style="background:linear-gradient(135deg,#ef4444,#dc2626); color:#fff; border:none; padding:10px 32px; border-radius:50px; font-weight:800; font-size:15px; cursor:pointer; box-shadow:0 4px 15px rgba(239,68,68,0.35); transition:all 0.3s;" onmouseover="this.style.transform='translateY(-2px) scale(1.05)'" onmouseout="this.style.transform='none'">
            পরীক্ষা জমা দিন
        </button>
    </div>
</div>

{{-- ======================= RESULT REPORT CARD MODAL ======================= --}}
<div class="modal fade" id="examResultModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content" style="background:#0f1129; border:1px solid rgba(255,255,255,0.1); border-radius:28px;">
            <div class="modal-body p-5">

                {{-- PASS/FAIL BANNER --}}
                <div id="result-banner" class="text-center mb-4" style="padding:20px; border-radius:16px; border:2px solid rgba(255,255,255,0.1);">
                    <div id="result-icon" style="font-size:56px; margin-bottom:8px;"></div>
                    <h3 id="result-headline" class="fw-bold mb-1" style="font-size:26px;"></h3>
                    <p id="result-subtext" class="mb-0" style="color:rgba(255,255,255,0.6);"></p>
                </div>

                {{-- SCORE CIRCLE + STATS GRID --}}
                <div class="row g-3 align-items-center mb-4">
                    <div class="col-md-4 text-center">
                        <div id="result-score-circle" style="width:140px; height:140px; border-radius:50%; border:5px solid #22c55e; display:flex; flex-direction:column; align-items:center; justify-content:center; background:rgba(34,197,94,0.08); margin:0 auto;">
                            <small style="color:rgba(255,255,255,0.5); font-size:11px; text-transform:uppercase; letter-spacing:0.5px;">প্রাপ্ত নম্বর</small>
                            <strong id="res-marks" style="font-size:36px; color:#22c55e; line-height:1.1;">—</strong>
                            <small id="res-total-marks" style="color:rgba(255,255,255,0.4); font-size:12px;">/ 0</small>
                        </div>
                    </div>
                    <div class="col-md-8">
                        <div class="row g-2">
                            <div class="col-6">
                                <div style="background:rgba(34,197,94,0.1); border:1px solid rgba(34,197,94,0.25); border-radius:14px; padding:16px; text-align:center;">
                                    <small style="color:rgba(255,255,255,0.5); display:block; font-size:11px;">সঠিক উত্তর</small>
                                    <strong id="res-right" style="font-size:28px; color:#22c55e;">—</strong>
                                </div>
                            </div>
                            <div class="col-6">
                                <div style="background:rgba(239,68,68,0.1); border:1px solid rgba(239,68,68,0.25); border-radius:14px; padding:16px; text-align:center;">
                                    <small style="color:rgba(255,255,255,0.5); display:block; font-size:11px;">ভুল উত্তর</small>
                                    <strong id="res-wrong" style="font-size:28px; color:#ef4444;">—</strong>
                                </div>
                            </div>
                            <div class="col-6">
                                <div style="background:rgba(255,255,255,0.04); border:1px solid rgba(255,255,255,0.1); border-radius:14px; padding:16px; text-align:center;">
                                    <small style="color:rgba(255,255,255,0.5); display:block; font-size:11px;">উত্তর দেননি</small>
                                    <strong id="res-unanswered" style="font-size:28px; color:rgba(255,255,255,0.6);">—</strong>
                                </div>
                            </div>
                            <div class="col-6">
                                <div style="background:rgba(245,197,24,0.08); border:1px solid rgba(245,197,24,0.2); border-radius:14px; padding:16px; text-align:center;">
                                    <small style="color:rgba(255,255,255,0.5); display:block; font-size:11px;">নেগেটিভ মার্ক</small>
                                    <strong id="res-negative" style="font-size:28px; color:#f5c518;">—</strong>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- PERCENTAGE BAR --}}
                <div style="background:rgba(255,255,255,0.04); border-radius:12px; padding:16px 20px; margin-bottom:24px;">
                    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:10px;">
                        <span style="color:rgba(255,255,255,0.6); font-size:13px;">সাফল্যের হার</span>
                        <span id="res-percent-text" style="color:#f5c518; font-weight:700; font-size:15px;">0%</span>
                    </div>
                    <div style="height:10px; background:rgba(255,255,255,0.08); border-radius:100px; overflow:hidden;">
                        <div id="res-percent-bar" style="height:100%; width:0%; background:linear-gradient(90deg,#ef4444,#f5c518,#22c55e); border-radius:100px; transition:width 1s ease;"></div>
                    </div>
                    <div style="display:flex; justify-content:space-between; margin-top:8px;">
                        <span style="font-size:11px; color:rgba(255,255,255,0.3);">পাস মার্ক: <span id="res-pass-mark-label"></span></span>
                        <span style="font-size:11px; color:rgba(255,255,255,0.3);">মোট প্রশ্ন: <span id="res-total">—</span></span>
                    </div>
                </div>

                {{-- ACTIONS --}}
                <div class="d-flex gap-3 justify-content-center flex-wrap">
                    <a href="{{ route('student.exams') }}" class="btn btn-success px-5 rounded-pill fw-bold">আমার পরীক্ষাসমূহ</a>
                    <button onclick="location.reload()" class="btn btn-outline-light px-5 rounded-pill fw-bold">আবার পরীক্ষা দিন</button>
                </div>

            </div>
        </div>
    </div>
</div>



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
            title: '{{ $model->name }} - প্রত্যয় একাডেমী',
            text: 'প্রত্যয় একাডেমীতে এই পরীক্ষাটি দেখুন',
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

<script>
    // ======== EXAM ENGINE ========
    let examAllQuestions = [];
    let examCurrentPage = 0;
    const examQPerPage = 20;
    let examAnswers = {};
    let examTimerInterval;
    let examTimeLeft = 0;
    let examResultModal;
    let examSetupModal;
    let examSelectedNeg = 0.25;
    let examSelectedPassPct = 40; // percentage
    let examSelectedDuration = 30;
    let examTotalQ = 0;

    $(document).ready(function() {
        examSetupModal = new bootstrap.Modal(document.getElementById('examSetupModal'));
        examResultModal = new bootstrap.Modal(document.getElementById('examResultModal'));

        $.ajaxSetup({ headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' } });
    });

    function openExamSetupModal() {
        examSetupModal.show();
    }

    // When question limit changes, auto-suggest duration
    $('#setup-limit').on('change', function() {
        const val = $(this).val();
        if (val !== 'all') {
            $('#setup-duration').val(parseInt(val));
        }
    });

    $('#confirm-start-exam-btn').on('click', function() {
        const btn = $(this);
        btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm"></span> লোড হচ্ছে...');

        const subjectId = $('#setup-subject').val();
        const limit = $('#setup-limit').val();
        examSelectedNeg = parseFloat($('#setup-negative-mark').val()) || 0;
        examSelectedDuration = parseInt($('#setup-duration').val()) || 30;
        examSelectedPassPct = parseFloat($('#setup-pass-mark').val()) || 0;

        if (examSelectedDuration < 1) examSelectedDuration = 30;
        if (examSelectedPassPct < 0) examSelectedPassPct = 0;
        if (examSelectedPassPct > 100) examSelectedPassPct = 100;

        $.ajax({
            url: "{{ route('exam.start', $model->id) }}",
            method: 'POST',
            data: { subject_id: subjectId, limit: limit },
            dataType: 'json',
            success: function(data) {
                if (data && data.questions && data.questions.length > 0) {
                    examAllQuestions = data.questions;
                    examTotalQ = examAllQuestions.length;
                    examAnswers = {};
                    examCurrentPage = 0;
                    examSetupModal.hide();
                    startExamOverlay();
                    btn.prop('disabled', false).html('পরীক্ষা শুরু করুন <i class="ri-arrow-right-line"></i>');
                } else {
                    toastr.warning('যথেষ্ট প্রশ্ন পাওয়া যায়নি। বিষয় বা প্রশ্ন সীমা পরিবর্তন করুন।');
                    btn.prop('disabled', false).html('পরীক্ষা শুরু করুন <i class="ri-arrow-right-line"></i>');
                }
            },
            error: function(xhr) {
                if (xhr.status === 401 || (xhr.responseText && xhr.responseText.includes('login'))) {
                    examSetupModal.hide();
                    Swal.fire({
                        title: 'লগইন প্রয়োজন',
                        text: 'পরীক্ষা দিতে আপনার অ্যাকাউন্টে লগইন করুন।',
                        icon: 'info',
                        showCancelButton: true,
                        confirmButtonText: 'লগইন করুন',
                        cancelButtonText: 'পরে করব',
                        background: '#07091e', color: '#fff',
                        confirmButtonColor: '#22c55e', cancelButtonColor: '#ef4444',
                    }).then(r => { if (r.isConfirmed) window.location.href = "{{ route('login') }}"; });
                } else {
                    toastr.error('সার্ভার ত্রুটি। পৃষ্ঠা রিলোড করে আবার চেষ্টা করুন।');
                }
                btn.prop('disabled', false).html('পরীক্ষা শুরু করুন <i class="ri-arrow-right-line"></i>');
            }
        });
    });

    function startExamOverlay() {
        $('#exam-overlay').show();
        $('body').css('overflow', 'hidden');
        examTimeLeft = examSelectedDuration * 60;
        updateExamSubjectTag();
        examRenderPage(0);
        examStartTimer();
    }

    function updateExamSubjectTag() {
        const subjVal = $('#setup-subject').val();
        const subjText = subjVal === 'all' ? 'সকল বিষয়' : $('#setup-subject option:selected').text();
        $('#exam-subject-tag').text(subjText + ' — ' + examTotalQ + 'টি প্রশ্ন');
    }

    function examStartTimer() {
        examTimerInterval = setInterval(() => {
            if (examTimeLeft <= 0) {
                clearInterval(examTimerInterval);
                submitExam();
                return;
            }
            examTimeLeft--;
            const mins = Math.floor(examTimeLeft / 60).toString().padStart(2, '0');
            const secs = (examTimeLeft % 60).toString().padStart(2, '0');
            const display = `${mins}:${secs}`;
            $('#exam-timer').text(display);
            $('#bar-timer').text(display);

            // Flash red when < 1 min left
            if (examTimeLeft < 60) {
                $('#exam-timer, #bar-timer').css('color', '#ef4444');
            }
        }, 1000);
    }

    function examRenderPage(pageIdx) {
        const start = pageIdx * examQPerPage;
        const end = Math.min(start + examQPerPage, examAllQuestions.length);
        const pageQs = examAllQuestions.slice(start, end);
        const labels = ['ক', 'খ', 'গ', 'ঘ', 'ঙ'];
        const vals = ['a', 'b', 'c', 'd', 'e'];

        let html = '';
        pageQs.forEach((q, i) => {
            const num = start + i + 1;
            let opts = '';
            q.options.forEach((opt, j) => {
                if (!opt) return;
                const val = vals[j];
                const checked = examAnswers[q.id] === val;
                const disabled = examAnswers[q.id] !== undefined ? 'disabled' : '';
                const checkedStyle = checked ? 'background:rgba(245,197,24,0.15); border-color:rgba(245,197,24,0.5);' : '';
                const circleStyle = checked ? 'background:#f5c518; border-color:#f5c518; color:#07091e;' : '';
                opts += `
                <label class="exam-opt ${disabled}" style="display:flex; align-items:center; gap:14px; padding:14px 18px; border-radius:14px; border:1px solid rgba(255,255,255,0.08); background:rgba(255,255,255,0.03); margin-bottom:10px; cursor:pointer; transition:all 0.2s; ${checkedStyle}">
                    <input type="radio" name="eq_${q.id}" value="${val}" ${checked ? 'checked' : ''} style="display:none;" onchange="examSaveAnswer(${q.id}, '${val}', this)">
                    <div style="width:32px; height:32px; border-radius:50%; border:2px solid rgba(255,255,255,0.15); display:flex; align-items:center; justify-content:center; font-weight:700; font-size:14px; flex-shrink:0; transition:all 0.2s; ${circleStyle}">${labels[j]}</div>
                    <div style="color:#efefef; font-size:15px; font-weight:500;">${opt}</div>
                </label>`;
            });
            html += `
            <div style="background:rgba(255,255,255,0.03); border:1px solid rgba(255,255,255,0.08); border-radius:20px; padding:28px; margin-bottom:20px;">
                <div style="font-size:18px; font-weight:600; color:#fff; margin-bottom:22px; display:flex; gap:14px; line-height:1.6;">
                    <span style="color:#f5c518; min-width:32px;">${num}.</span>
                    <div>${q.question}</div>
                </div>
                ${opts}
            </div>`;
        });

        $('#exam-questions-area').hide().html(html).fadeIn(300);
        const totalPages = Math.ceil(examAllQuestions.length / examQPerPage);
        const answered = Object.keys(examAnswers).length;
        $('#exam-page-info').text(`পাতা ${pageIdx + 1} (মোট ${totalPages} পাতার মধ্যে)`);
        $('#bar-progress').text(`${answered}/${examAllQuestions.length}`);
        const pct = (answered / examAllQuestions.length) * 100;
        $('#exam-progress-bar').css('width', pct + '%');

        $('#exam-prev-btn').toggle(pageIdx > 0);
        $('#exam-next-btn').toggle(pageIdx < totalPages - 1);
        window.scrollTo({ top: 0, behavior: 'smooth' });
    }

    window.examSaveAnswer = function(qId, val, el) {
        if (examAnswers[qId] !== undefined) return;
        examAnswers[qId] = val;
        examRenderPage(examCurrentPage);
    };

    $('#exam-next-btn').on('click', () => {
        const total = Math.ceil(examAllQuestions.length / examQPerPage);
        if (examCurrentPage < total - 1) { examCurrentPage++; examRenderPage(examCurrentPage); }
    });
    $('#exam-prev-btn').on('click', () => {
        if (examCurrentPage > 0) { examCurrentPage--; examRenderPage(examCurrentPage); }
    });

    $('#exam-submit-btn').on('click', () => {
        Swal.fire({
            title: 'পরীক্ষা জমা দেবেন?',
            text: 'একবার জমা দিলে আর পরিবর্তন করা যাবে না।',
            icon: 'question',
            showCancelButton: true,
            background: '#07091e', color: '#fff',
            confirmButtonColor: '#22c55e',
            cancelButtonText: 'না',
            confirmButtonText: 'হ্যাঁ, জমা দিন'
        }).then(r => { if (r.isConfirmed) submitExam(); });
    });

    function submitExam() {
        clearInterval(examTimerInterval);
        const btn = document.getElementById('exam-submit-btn');
        if (btn) { btn.disabled = true; btn.innerHTML = '<span class="spinner-border spinner-border-sm"></span>'; }

        const questionIds = examAllQuestions.map(q => q.id);
        // Pass mark as absolute number: percentage of total questions
        const passMarkAbsolute = (examSelectedPassPct / 100) * examTotalQ;

        $.post("{{ route('exam.submit', $model->id) }}",
            { answers: examAnswers, question_ids: questionIds, negative_mark: examSelectedNeg, pass_mark: passMarkAbsolute },
            function(data) {
                showResultCard(data.attempt);
                $('#exam-overlay').hide();
                $('body').css('overflow', '');
            }).fail(function() {
            toastr.error('জমা দিতে সমস্যা হয়েছে। আবার চেষ্টা করুন।');
            if (btn) { btn.disabled = false; btn.innerHTML = 'পরীক্ষা জমা দিন'; }
        });
    }

    function showResultCard(attempt) {
        const totalQ   = attempt.total_questions || examTotalQ;
        const right    = attempt.right_answers || 0;
        const wrong    = attempt.wrong_answers || 0;
        const noAns    = attempt.no_answers   || (totalQ - right - wrong);
        const obtained = parseFloat(attempt.marks_obtained || 0).toFixed(2);
        const negMark  = parseFloat(attempt.negative_marks || 0).toFixed(2);
        const pct      = totalQ > 0 ? ((right / totalQ) * 100).toFixed(1) : 0;
        const passed   = attempt.passed;
        const passMarkAbs = ((examSelectedPassPct / 100) * totalQ).toFixed(1);

        $('#res-marks').text(obtained);
        $('#res-total-marks').text('/ ' + totalQ);
        $('#res-right').text(right);
        $('#res-wrong').text(wrong);
        $('#res-unanswered').text(noAns);
        $('#res-negative').text(negMark);
        $('#res-total').text(totalQ);
        $('#res-percent-text').text(pct + '%');
        $('#res-percent-bar').css('width', Math.min(pct, 100) + '%');
        $('#res-pass-mark-label').text(examSelectedPassPct + '% (' + passMarkAbs + ' নম্বর)');

        if (passed) {
            $('#result-banner').css({ background: 'rgba(34,197,94,0.08)', borderColor: 'rgba(34,197,94,0.3)' });
            $('#result-icon').html('🎉');
            $('#result-headline').text('অভিনন্দন! আপনি পাস করেছেন').css('color', '#22c55e');
            $('#result-subtext').text('চমৎকার পারফরম্যান্স! আপনি ' + pct + '% নম্বর পেয়েছেন।');
            $('#result-score-circle').css('borderColor', '#22c55e').css('background', 'rgba(34,197,94,0.08)');
            $('#res-marks').css('color', '#22c55e');
        } else {
            $('#result-banner').css({ background: 'rgba(239,68,68,0.08)', borderColor: 'rgba(239,68,68,0.3)' });
            $('#result-icon').html('😔');
            $('#result-headline').text('দুঃখিত! আপনি পাস করেননি').css('color', '#ef4444');
            $('#result-subtext').text('আপনি ' + pct + '% পেয়েছেন। পাস মার্ক ছিল ' + examSelectedPassPct + '%।');
            $('#result-score-circle').css('borderColor', '#ef4444').css('background', 'rgba(239,68,68,0.08)');
            $('#res-marks').css('color', '#ef4444');
        }

        examResultModal.show();
    }
</script>



@endpush
