@extends('layouts.web', ['title' => $category->site_title])

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
        border-radius: 12px; padding: 12px 24px; margin-bottom: 10px; display: flex; align-items: center; gap: 10px;
        flex-wrap: wrap;
    }
    .premium-breadcrumb a { color: var(--text-light); text-decoration: none; font-size: 14px; transition: color .2s; }
    .premium-breadcrumb a:hover { color: var(--accent-gold); }
    .premium-breadcrumb span { color: rgba(255,255,255,0.3); font-size: 14px; }
    .premium-breadcrumb .active { color: #fff; font-weight: 600; }

    /* TITLE */
    .page-header-premium { margin-bottom: 20px; }
    .page-title-premium { font-family: 'Noto Serif Bengali', serif; font-size: clamp(24px, 5vw, 36px); font-weight: 800; color: #fff; margin-bottom: 12px; }
    .page-subtitle-premium { color: var(--text-light); font-size: 16px; opacity: 0.8; }

    /* SEARCH BOX */
    .pz-search-container { max-width: 700px; margin: 40px auto 0; padding: 0 20px; }
    .pz-premium-search {
        position: relative; background: rgba(255, 255, 255, 0.04); backdrop-filter: blur(25px); -webkit-backdrop-filter: blur(25px);
        border: 1px solid rgba(255, 255, 255, 0.1); border-radius: 20px; display: flex; align-items: center; padding: 5px 10px;
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1); box-shadow: 0 10px 30px rgba(0,0,0,0.2);
    }
    .pz-premium-search:focus-within { background: rgba(255, 255, 255, 0.07); border-color: var(--accent-gold); transform: translateY(-2px); box-shadow: 0 15px 40px rgba(245, 197, 24, 0.15); }
    .search-icon { width: 50px; height: 50px; display: flex; align-items: center; justify-content: center; color: var(--accent-gold); font-size: 24px; }
    .pz-premium-search input { flex: 1; background: transparent; border: none; color: #fff; padding: 15px 10px; font-size: 18px; font-weight: 500; outline: none; }
    .pz-premium-search input::placeholder { color: rgba(255, 255, 255, 0.3); font-weight: 400; }
    .search-accent { position: absolute; bottom: -1px; left: 50%; transform: translateX(-50%); width: 0; height: 2px; background: linear-gradient(90deg, transparent, var(--accent-gold), transparent); transition: width 0.4s; }
    .pz-premium-search:focus-within .search-accent { width: 60%; }

    /* GRID & CARDS */
    .pz-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 24px; }
    .pz-card {
        position: relative; overflow: hidden; border-radius: 22px; padding: 32px;
        border: 1px solid rgba(255,255,255,0.08) !important; cursor: pointer;
        transition: transform .28s cubic-bezier(.22,.68,0,1.2), box-shadow .28s;
        background: rgba(255,255,255,0.055) !important; backdrop-filter: blur(12px);
        text-decoration: none !important; color: #fff !important; display: flex !important; flex-direction: column !important;
    }
    .pz-card:hover { transform: translateY(-8px) scale(1.01); border-color: rgba(245,197,24,0.3) !important; }
    .pz-glow { position: absolute; top: -40px; right: -40px; width: 140px; height: 140px; border-radius: 50%; opacity: 0.1; transition: opacity .3s, transform .3s; pointer-events: none; }
    .pz-card:hover .pz-glow { opacity: 0.2; transform: scale(1.2); }
    .pz-icon-box { width: 52px; height: 52px; border-radius: 14px; display: flex !important; align-items: center !important; justify-content: center !important; font-size: 24px !important; margin-bottom: 20px; background: rgba(245,197,24,0.12); color: var(--accent-gold); }
    .pz-title { font-family: 'Noto Serif Bengali', serif !important; font-size: 22px !important; font-weight: 700 !important; margin-bottom: 10px !important; line-height: 1.3 !important; display: block !important; color: #fff !important; }
    .pz-desc { font-size: 14px !important; color: var(--text-light) !important; opacity: 0.8; margin-bottom: 24px !important; }
    .pz-footer { display: flex !important; align-items: center !important; justify-content: space-between !important; margin-top: auto !important; padding-top: 18px; border-top: 1px solid rgba(255,255,255,0.08); }
    .pz-count-bn { font-size: 16px; font-weight: 700; color: var(--accent-gold); }
    .pz-arrow { font-size: 18px; color: var(--text-light); transition: transform .2s; }
    .pz-card:hover .pz-arrow { transform: translateX(4px); color: var(--accent-gold); }

    /* ACTION BUTTONS */
    .pz-actions { display: flex; gap: 10px; margin-top: 20px; }
    .pz-btn-mini { flex: 1; padding: 10px; border-radius: 10px; font-size: 13px; font-weight: 700; text-align: center; text-decoration: none !important; transition: all .2s; }
    .btn-gold-mini { background: var(--accent-gold); color: #07091e !important; }
    .btn-gold-mini:hover { filter: brightness(1.1); transform: translateY(-2px); }
    .btn-outline-mini { border: 1px solid rgba(255,255,255,0.15); color: #fff !important; background: rgba(255,255,255,0.05); }
    .btn-outline-mini:hover { background: rgba(255,255,255,0.1); border-color: #fff; }

    /* QUESTION ELEMENTS */
    .pz-btn-answer, .pz-btn-explain { background: rgba(255, 255, 255, 0.05); border: 1px solid rgba(255, 255, 255, 0.1); color: var(--accent-gold); padding: 8px 16px; border-radius: 8px; font-size: 14px; display: flex; align-items: center; gap: 8px; transition: all 0.3s; cursor: pointer; }
    .pz-btn-answer:hover, .pz-btn-explain:hover { background: rgba(245, 197, 24, 0.1); border-color: var(--accent-gold); }
    .pz-explain-box { margin-top: 20px; background: rgba(255, 255, 255, 0.02); border-left: 4px solid var(--accent-gold); border-radius: 0 10px 10px 0; padding: 15px 20px; }
    .pz-explain-content { color: #b8c4e8; font-size: 14px; line-height: 1.6; }
    .pz-btn-pdf { background: linear-gradient(135deg, #ff4d4d, #c40000); border: none; color: #fff; padding: 10px 22px; border-radius: 12px; font-size: 15px; font-weight: 600; display: flex; align-items: center; gap: 10px; transition: all 0.3s; cursor: pointer; box-shadow: 0 4px 15px rgba(196, 0, 0, 0.3); }
    .pz-btn-pdf:hover { transform: translateY(-2px); box-shadow: 0 6px 20px rgba(196, 0, 0, 0.4); filter: brightness(1.1); }

    /* PAGINATION */
    .pagination { gap: 8px; border: none !important; }
    .page-link { background: rgba(255,255,255,0.05) !important; border: 1px solid rgba(255,255,255,0.1) !important; color: rgba(255,255,255,0.7) !important; border-radius: 10px !important; padding: 10px 18px !important; font-weight: 600 !important; transition: all .2s !important; font-size: 14px !important; }
    .page-item.active .page-link { background: var(--accent-gold) !important; border-color: var(--accent-gold) !important; color: #07091e !important; box-shadow: 0 4px 12px rgba(245,197,24,0.3) !important; }
    .page-link:hover { background: rgba(255,255,255,0.1) !important; border-color: rgba(255,255,255,0.3) !important; color: #fff !important; transform: translateY(-2px); }

    /* PRINT STYLES - 2 COLUMN PDF */
    @media print {
        @page { margin: 15mm; size: A4; }
        body { background: #fff !important; color: #000 !important; }
        .website-header, .website-footer, .pz-search-container, .pz-btn-pdf, .pz-btn-answer, .pz-btn-explain, .rn-progress-parent, .breadcrumb-premium-wrapper, .page-subtitle-premium, .main-wrapper::before, .rn-back-circle { display: none !important; }
        .container { max-width: 100% !important; width: 100% !important; padding: 0 !important; margin: 0 !important; }
        .pz-questions-section { margin-top: 0 !important; }
        .page-title-premium { color: #000 !important; text-align: center; font-size: 24px !important; margin-bottom: 20px !important; border-bottom: 2px solid #000; padding-bottom: 10px; }
        .pz-questions-section > .row { display: block !important; column-count: 2; column-gap: 30px; }
        .pz-questions-section .col-12 { width: 100% !important; display: inline-block; page-break-inside: avoid; margin-bottom: 15px !important; }
        .pz-card { background: #fff !important; border: 1px solid #eee !important; color: #000 !important; padding: 15px !important; box-shadow: none !important; }
        .pz-card p, .pz-card span { color: #000 !important; }
        .pz-options-grid { display: block !important; }
        .pz-options-grid .col-md-6 { width: 100% !important; margin-bottom: 5px !important; }
        .pz-option-item { background: #f9f9f9 !important; border: 1px solid #ddd !important; color: #333 !important; padding: 6px 12px !important; font-size: 11px !important; }
        .pz-correct-ans { background: #e8f5e9 !important; border: 1px solid #2e7d32 !important; color: #2e7d32 !important; font-weight: bold !important; }
    }

    /* MOBILE RESPONSIVE */
    @media (max-width: 991px) { .pz-grid { grid-template-columns: repeat(2, 1fr); } }
    @media (max-width: 768px) { 
        .section-premium { padding: 90px 16px 40px; }
        .pz-grid { grid-template-columns: 1fr; } 
        .page-title-premium { font-size: 20px; line-height: 1.3; margin-bottom: 15px; padding: 0 10px; } 
        .pz-title { font-size: 16px !important; }
        .pz-search-container { margin-top: 25px; padding: 0 15px; }
        .pz-premium-search input { font-size: 15px; padding: 12px 10px; }
        .pz-actions { flex-direction: row; gap: 10px; }
        .pz-btn-mini { flex: 1; justify-content: center; padding: 10px 5px !important; font-size: 13px !important; }
        .premium-breadcrumb { padding: 10px 16px; margin-bottom: 20px; font-size: 13px; }
    }
</style>
@endpush

@section('content')
<div class="container section-premium">
    @php
        $breadcrumbs = $category->breadcrumb();
        $toBn = function($num) {
            $en = ['0','1','2','3','4','5','6','7','8','9'];
            $bn = ['০','১','২','৩','৪','৫','৬','৭','৮','৯'];
            return str_replace($en, $bn, (string)$num);
        };
    @endphp

    <!-- BREADCRUMB -->
    <div class="premium-breadcrumb">
        <a href="{{ route('home') }}">হোম</a>
        @foreach ($breadcrumbs as $index => $cat)
            <span>/</span>
            @if($index == count($breadcrumbs) - 1)
                <span class="active">{{ $cat->name }}</span>
            @else
                <a href="{{ route('slug.handle', $cat->slug) }}">{{ $cat->name }}</a>
            @endif
        @endforeach
    </div>

    <!-- HEADER -->
    <div class="page-header-premium">
        <h1 class="page-title-premium">{{ $category->name }}</h1>
        <p class="page-subtitle-premium">
            @if(count($jobCategories) > 0 || count($childCategories) > 0)
                আপনার পছন্দের টপিক বেছে নিন এবং প্রস্তুতি শুরু করুন।
            @else
                এই ক্যাটাগরিতে বর্তমানে কোনো প্রশ্ন নেই।
            @endif
        </p>
        
        <!-- PREMIUM SEARCH -->
        <div class="pz-search-container">
            <div class="pz-premium-search">
                <div class="search-icon"><i class="ri-search-eye-line"></i></div>
                <input type="text" id="globalPzSearch" placeholder="এখানে যা খুঁজছেন তা লিখুন..." onkeyup="runPremiumFilter()">
                <div class="search-accent"></div>
            </div>
        </div>
    </div>

    <!-- MAIN GRID -->
    @if (count($jobCategories) > 0)
        <div style="margin-bottom: 30px;">
            <h3 class="page-title-premium" style="font-size: 24px; margin-bottom: 20px;">পরীক্ষা ভিত্তিক</h3>
        </div>
    @endif
    
    <div class="pz-grid">
        @if (count($jobCategories) > 0)
            @foreach ($jobCategories as $job)
                <div class="pz-card" style="cursor: default;">
                    <div class="pz-glow" style="background:radial-gradient(circle, var(--accent-gold), transparent 70%)"></div>
                    <div class="pz-icon-box">📝</div>
                    <span class="pz-title">{{ $job->name }}</span>
                    <p class="pz-desc">বিগত বছরের সকল প্রশ্ন এবং মডেল টেস্ট সমাধান সহ।</p>
                    <div class="pz-footer" style="border-bottom: 1px solid rgba(255,255,255,0.08); padding-bottom: 15px; border-top:none; margin-top:0;">
                        <span class="pz-count-bn">{{ $toBn($job->questions->count()) }} টি প্রশ্নপত্র</span>
                    </div>
                    <div class="pz-actions">
                        <a href="{{ route('slug.handle', $job->slug) }}" class="pz-btn-mini btn-outline-mini">📖 প্র্যাকটিস</a>
                        <a href="{{ route('exam.show', $job->slug) }}" class="pz-btn-mini btn-gold-mini">🎯 পরীক্ষা</a>
                    </div>
                </div>
            @endforeach
        @endif
    </div>

    {{-- SUB-CATEGORIES (SUBJECTS) --}}
    @if (count($childCategories) > 0)
        <div style="margin-top: 60px;">
            <div style="margin-bottom: 30px;">
                <h3 class="page-title-premium" style="font-size: 24px;">
                    @php
                        $lowName = strtolower($category->name);
                        if (str_contains($lowName, 'admission') || str_contains($category->name, 'ভর্তি')) echo 'বিশ্ববিদ্যালয় সমূহ';
                        elseif (str_contains($lowName, 'bank') || str_contains($category->name, 'ব্যাংক')) echo 'ব্যাংক প্রশ্ন';
                        elseif (!$category->parent_id) echo 'ক্যাটাগরি ভিত্তিক';
                        else echo 'বিষয় ভিত্তিক';
                    @endphp
                </h3>
            </div>
            <div class="pz-grid">
                @php
                    $childCategories = $childCategories->values();
                    if($childCategories->count() > 3) {
                        $lastThree = $childCategories->splice(-3);
                        $childCategories = $lastThree->concat($childCategories);
                    }
                @endphp
                @foreach ($childCategories as $child)
                    <a href="{{ route('slug.handle', $child->slug) }}" class="pz-card">
                        <div class="pz-glow" style="background:radial-gradient(circle, var(--accent-gold), transparent 70%)"></div>
                        <div class="pz-icon-box">📂</div>
                        <span class="pz-title">{{ $child->name }}</span>
                        <p class="pz-desc">{{ Str::limit($child->meta_description ?? 'এই ক্যাটাগরির অধীনে সকল পরীক্ষার প্রশ্ন ও সমাধান এখানে পাবেন।', 80) }}</p>
                        <div class="pz-footer">
                            <span class="pz-count-bn">{{ $toBn($child->totalQuestionsCount()) }} টি প্রশ্নপত্র</span>
                            <span class="pz-arrow">→</span>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
    @endif

    {{-- SINGLE QUESTIONS LIST --}}
    @if (count($questions) > 0)
        <div class="mt-5 pz-questions-section">
            <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-3">
                <h3 class="page-title-premium mb-0" style="font-size: 24px;">প্রশ্নসমূহ</h3>
                <button class="pz-btn-pdf" onclick="window.print()"><i class="ri-file-pdf-2-line"></i> PDF ডাউনলোড করুন</button>
            </div>
            <div class="row g-4">
                @foreach ($questions as $index => $q)
                    <div class="col-12">
                        <div class="pz-card" style="cursor: default; padding: 24px;">
                            <p style="font-size: 17px; font-weight: 600; line-height: 1.6; margin-bottom: 18px; color: #fff;">
                                <span style="color: var(--accent-gold); margin-right: 8px;">{{ $toBn($index + 1) }}.</span> {!! $q['question'] !!}
                            </p>
                            <div class="row g-3 pz-options-grid" id="options-{{ $q['id'] }}">
                                @foreach ($q['options'] as $oi => $opt)
                                    @if($opt && $opt != 'প্রশ্ন নেই' && $opt != 'নিচে দেখুন')
                                        @php
                                            $isCorrect = false;
                                            $ans = strtolower($q['correct_answer']);
                                            if ($ans == ($oi+1) || $ans == strtolower($opt) || str_contains($ans, 'option_'.(['one','two','three','four','five'][$oi]))) $isCorrect = true;
                                        @endphp
                                        <div class="col-md-6">
                                            <div class="pz-option-item {{ $isCorrect ? 'pz-correct-ans' : '' }}" style="background: rgba(255,255,255,0.04); border: 1px solid rgba(255,255,255,0.08); border-radius: 10px; padding: 12px 18px; font-size: 14px; color: #ccc; transition: all 0.3s;">
                                                <span style="font-weight: 700; color: var(--accent-gold); margin-right: 10px;">{{ ['ক','খ','গ','ঘ','ঙ'][$oi] }}.</span> {!! $opt !!}
                                            </div>
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                            <div class="mt-4 d-flex align-items-center gap-3">
                                <button class="pz-btn-answer" onclick="toggleAnswer('{{ $q['id'] }}')"><i class="ri-eye-line"></i> উত্তর দেখুন</button>
                                @if($q['content'])
                                    <button class="pz-btn-explain" onclick="toggleExplain('{{ $q['id'] }}')"><i class="ri-lightbulb-line"></i> ব্যাখ্যা</button>
                                @endif
                            </div>
                            @if($q['content'])
                                <div id="explain-{{ $q['id'] }}" class="pz-explain-box" style="display: none;">
                                    <div class="pz-explain-content"><strong>ব্যাখ্যা:</strong><div class="mt-2">{!! $q['content'] !!}</div></div>
                                </div>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif
    
    @if(method_exists($jobCategories, 'links'))
        <div class="mt-5 d-flex justify-content-center">{{ $jobCategories->links('pagination::bootstrap-5') }}</div>
    @endif
</div>

@push('scripts')
<script>
    function toggleAnswer(qId) {
        const grid = document.getElementById('options-' + qId);
        const correctItem = grid.querySelector('.pz-correct-ans');
        if (correctItem) {
            const isRevealed = correctItem.classList.toggle('revealed');
            correctItem.style.background = isRevealed ? "rgba(40, 167, 69, 0.15)" : "rgba(255,255,255,0.04)";
            correctItem.style.borderColor = isRevealed ? "#28a745" : "rgba(255,255,255,0.08)";
            correctItem.style.color = isRevealed ? "#fff" : "#ccc";
        }
    }

    function toggleExplain(qId) {
        $('#explain-' + qId).slideToggle();
    }

    function runPremiumFilter() {
        const filter = document.getElementById('globalPzSearch').value.toLowerCase().trim();
        const cards = document.getElementsByClassName('pz-card');
        for (let card of cards) {
            const text = card.textContent || card.innerText;
            const isMatch = text.toLowerCase().includes(filter);
            card.style.display = isMatch ? "" : "none";
            const parentCol = card.closest('.col-12, .col-md-6, .col-lg-4');
            if (parentCol) parentCol.style.display = isMatch ? "" : "none";
        }
    }
</script>
@endpush
@endsection
