@extends('layouts.web', ['title' => $category->site_title ?? 'Academy - Prottoy Academy'])

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
        border-radius: 12px; padding: 12px 24px; margin-bottom: 24px; display: flex; align-items: center; gap: 10px;
        flex-wrap: wrap;
    }
    .premium-breadcrumb a { color: var(--text-light); text-decoration: none; font-size: 14px; transition: color .2s; }
    .premium-breadcrumb a:hover { color: var(--accent-gold); }
    .premium-breadcrumb span { color: rgba(255,255,255,0.3); font-size: 14px; }
    .premium-breadcrumb .active { color: #fff; font-weight: 600; }

    /* STATS GRID */
    .stats-grid-academy {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 20px;
        margin-bottom: 32px;
    }
    .stat-card-academy {
        background: rgba(255, 255, 255, 0.03);
        border: 1px solid rgba(255, 255, 255, 0.08);
        border-radius: 18px;
        padding: 24px;
        position: relative;
        overflow: hidden;
        transition: transform 0.3s, border-color 0.3s;
    }
    .stat-card-academy:hover {
        transform: translateY(-4px);
        border-color: rgba(245, 197, 24, 0.25);
    }
    .stat-card-academy::before {
        content: '';
        position: absolute; top: -50%; left: -50%; width: 200%; height: 200%;
        background: radial-gradient(circle, rgba(245, 197, 24, 0.03) 0%, transparent 70%);
        pointer-events: none;
    }
    .stat-label-academy {
        font-size: 13px;
        color: var(--text-light);
        opacity: 0.8;
        margin-bottom: 8px;
        font-weight: 500;
    }
    .stat-value-academy {
        font-family: 'Noto Serif Bengali', serif;
        font-size: 32px;
        font-weight: 800;
        color: #fff;
        line-height: 1.2;
    }
    .stat-growth-academy {
        font-size: 11px;
        color: #22c55e;
        margin-top: 6px;
        display: flex;
        align-items: center;
        gap: 4px;
    }

    /* CONTAINER LAYOUT */
    .academy-layout {
        display: grid;
        grid-template-columns: 280px 1fr;
        gap: 32px;
        align-items: start;
    }

    /* SIDEBAR FILTERS */
    .academy-sidebar {
        background: rgba(255, 255, 255, 0.02);
        border: 1px solid rgba(255, 255, 255, 0.07);
        border-radius: 20px;
        padding: 24px;
        backdrop-filter: blur(12px);
    }
    .filter-group-academy {
        margin-bottom: 28px;
    }
    .filter-group-academy:last-child {
        margin-bottom: 0;
    }
    .filter-title-academy {
        font-size: 15px;
        font-weight: 700;
        color: #fff;
        margin-bottom: 16px;
        padding-bottom: 8px;
        border-bottom: 1px solid rgba(255, 255, 255, 0.06);
    }
    .filter-list-academy {
        display: flex;
        flex-direction: column;
        gap: 12px;
    }
    .filter-item-academy {
        display: flex;
        align-items: center;
        justify-content: space-between;
        cursor: pointer;
        user-select: none;
    }
    .filter-item-academy input {
        display: none;
    }
    .filter-label-academy {
        display: flex;
        align-items: center;
        gap: 10px;
        font-size: 14px;
        color: var(--text-light);
        transition: color 0.2s;
    }
    .filter-checkbox-academy {
        width: 18px;
        height: 18px;
        border-radius: 5px;
        border: 1.5px solid rgba(255, 255, 255, 0.2);
        display: inline-flex;
        align-items: center;
        justify-content: center;
        transition: all 0.2s;
        flex-shrink: 0;
    }
    .filter-checkbox-academy i {
        font-size: 11px;
        color: #07091e;
        opacity: 0;
        transition: opacity 0.15s;
    }
    .filter-item-academy input:checked + .filter-label-academy .filter-checkbox-academy {
        background: var(--accent-gold);
        border-color: var(--accent-gold);
    }
    .filter-item-academy input:checked + .filter-label-academy .filter-checkbox-academy i {
        opacity: 1;
    }
    .filter-item-academy input:checked + .filter-label-academy {
        color: #fff;
        font-weight: 600;
    }
    .filter-count-academy {
        font-size: 11px;
        background: rgba(255, 255, 255, 0.05);
        color: rgba(255, 255, 255, 0.4);
        padding: 2px 8px;
        border-radius: 100px;
        font-weight: 600;
        transition: all 0.2s;
    }
    .filter-item-academy input:checked ~ .filter-count-academy {
        background: rgba(245, 197, 24, 0.12);
        color: var(--accent-gold);
    }

    /* SEARCH & CONTROLS */
    .controls-wrapper-academy {
        display: flex;
        gap: 16px;
        margin-bottom: 24px;
        align-items: center;
    }
    .search-input-wrapper {
        flex: 1;
        position: relative;
    }
    .search-input-wrapper input {
        width: 100%;
        background: rgba(255, 255, 255, 0.03);
        border: 1px solid rgba(255, 255, 255, 0.08);
        border-radius: 14px;
        padding: 14px 16px 14px 48px;
        color: #fff;
        outline: none;
        font-size: 15px;
        transition: border-color 0.3s, background-color 0.3s;
    }
    .search-input-wrapper input:focus {
        border-color: var(--accent-gold);
        background: rgba(255, 255, 255, 0.05);
    }
    .search-input-wrapper i {
        position: absolute;
        left: 18px; top: 50%;
        transform: translateY(-50%);
        color: rgba(255, 255, 255, 0.4);
        font-size: 18px;
    }
    .btn-control-academy {
        background: rgba(255, 255, 255, 0.03);
        border: 1px solid rgba(255, 255, 255, 0.08);
        border-radius: 14px;
        padding: 14px 20px;
        color: #fff;
        font-weight: 600;
        font-size: 14px;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        cursor: pointer;
        transition: all 0.3s;
    }
    .btn-control-academy:hover {
        background: rgba(255, 255, 255, 0.06);
        border-color: rgba(255, 255, 255, 0.15);
    }

    /* ACTIVE BADGES */
    .active-badges-wrapper {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
        margin-bottom: 24px;
        align-items: center;
    }
    .badge-label-title {
        font-size: 13px;
        color: rgba(255, 255, 255, 0.4);
        margin-right: 8px;
    }
    .filter-badge-item {
        background: rgba(245, 197, 24, 0.08);
        border: 1px solid rgba(245, 197, 24, 0.2);
        color: var(--accent-gold);
        border-radius: 100px;
        padding: 4px 14px;
        font-size: 12px;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        cursor: pointer;
        transition: all 0.2s;
    }
    .filter-badge-item:hover {
        background: rgba(245, 197, 24, 0.15);
        transform: scale(0.97);
    }
    .filter-badge-item i {
        font-size: 14px;
    }
    .badge-clear-all {
        background: rgba(239, 68, 68, 0.08);
        border: 1px solid rgba(239, 68, 68, 0.2);
        color: #ef4444;
        border-radius: 100px;
        padding: 4px 14px;
        font-size: 12px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s;
        text-decoration: none;
    }
    .badge-clear-all:hover {
        background: rgba(239, 68, 68, 0.15);
    }

    /* QUESTION CARDS LIST */
    .questions-list-academy {
        display: flex;
        flex-direction: column;
        gap: 20px;
    }
    .question-card-academy {
        background: rgba(255, 255, 255, 0.03);
        border: 1px solid rgba(255, 255, 255, 0.07);
        border-radius: 18px;
        padding: 24px 28px;
        transition: border-color 0.3s, transform 0.3s;
        position: relative;
    }
    .question-card-academy:hover {
        border-color: rgba(245, 197, 24, 0.2);
        transform: translateY(-2px);
    }
    .q-header-academy {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        gap: 20px;
        margin-bottom: 16px;
    }
    .q-text-academy {
        font-size: 16px;
        font-weight: 600;
        line-height: 1.6;
        color: #fff;
    }
    .q-type-badge-academy {
        font-size: 11px;
        font-weight: 700;
        padding: 4px 12px;
        border-radius: 100px;
        white-space: nowrap;
        background: rgba(59, 130, 246, 0.1);
        color: #3b82f6;
        border: 1px solid rgba(59, 130, 246, 0.2);
    }
    .q-type-badge-academy.cq {
        background: rgba(168, 85, 247, 0.1);
        color: #a855f7;
        border: 1px solid rgba(168, 85, 247, 0.2);
    }
    .q-actions-academy {
        display: flex;
        gap: 8px;
        margin-left: auto;
    }
    .q-btn-action {
        width: 36px;
        height: 36px;
        border-radius: 8px;
        background: rgba(255, 255, 255, 0.03);
        border: 1px solid rgba(255, 255, 255, 0.07);
        color: rgba(255, 255, 255, 0.6);
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 16px;
        cursor: pointer;
        transition: all 0.2s;
        text-decoration: none;
    }
    .q-btn-action:hover {
        background: rgba(255, 255, 255, 0.08);
        border-color: rgba(255, 255, 255, 0.15);
        color: #fff;
    }
    .q-btn-action.edit:hover {
        border-color: rgba(245, 197, 24, 0.3);
        color: var(--accent-gold);
    }

    /* MCQ OPTIONS */
    .q-options-academy {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 12px;
        margin-bottom: 20px;
    }
    .q-option-item-academy {
        background: rgba(255, 255, 255, 0.02);
        border: 1px solid rgba(255, 255, 255, 0.05);
        border-radius: 10px;
        padding: 10px 16px;
        font-size: 14px;
        color: #ccc;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .q-option-prefix {
        font-weight: 700;
        color: var(--accent-gold);
    }
    .q-option-item-academy.correct {
        border-color: rgba(34, 197, 94, 0.3);
        background: rgba(34, 197, 94, 0.06);
        color: #22c55e;
    }

    /* TAGS ROW */
    .q-tags-academy {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
        align-items: center;
    }
    .q-tag-badge {
        font-size: 12px;
        font-weight: 600;
        padding: 4px 12px;
        border-radius: 6px;
        background: rgba(255, 255, 255, 0.04);
        color: rgba(255, 255, 255, 0.7);
        border: 1px solid rgba(255, 255, 255, 0.06);
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }

    /* LOADING SPINNER */
    .loader-academy {
        text-align: center;
        padding: 60px 0;
        display: none;
    }
    .spinner-academy {
        width: 48px;
        height: 48px;
        border: 4px solid rgba(245, 197, 24, 0.1);
        border-top-color: var(--accent-gold);
        border-radius: 50%;
        animation: spin 1s linear infinite;
        margin: 0 auto 16px;
    }
    @keyframes spin {
        to { transform: rotate(360deg); }
    }
    .loader-text {
        font-size: 15px;
        color: var(--text-light);
        opacity: 0.7;
    }

    /* PRINT LAYOUT */
    @media print {
        body { background: #fff !important; color: #000 !important; }
        .main-wrapper, .premium-breadcrumb, .stats-grid-academy, .academy-sidebar, .controls-wrapper-academy, .active-badges-wrapper, .q-actions-academy, .q-btn-action, .q-tags-academy, footer {
            display: none !important;
        }
        .academy-layout {
            display: block !important;
        }
        .question-card-academy {
            border: none !important;
            border-bottom: 1px solid #ccc !important;
            padding: 16px 0 !important;
            background: transparent !important;
            page-break-inside: avoid;
        }
        .q-text-academy { color: #000 !important; }
        .q-option-item-academy { border: none !important; background: transparent !important; color: #000 !important; }
        .q-option-item-academy.correct { font-weight: 700; }
    }
</style>
@endpush

@section('content')
<div class="section-premium">
    <div class="container">
        
        <!-- BREADCRUMB -->
        <div class="premium-breadcrumb">
            <a href="{{ route('home') }}">হোম</a>
            <span>/</span>
            <span class="active">একাডেমিক কোশ্চেন ব্যাংক</span>
        </div>

        <!-- HEADER -->
        <div class="page-header-premium mb-4">
            <h1 class="page-title-premium">একাডেমিক কোশ্চেন ব্যাংক</h1>
            <p class="page-subtitle-premium">সহজে শ্রেণি, বিষয় এবং অধ্যায় ভিত্তিক প্রশ্ন অনুসন্ধান করুন</p>
        </div>

        <!-- STATS GRID -->
        <div class="stats-grid-academy">
            <div class="stat-card-academy">
                <div class="stat-label-academy">মোট প্রশ্ন</div>
                <div class="stat-value-academy" id="stat-total">{{ number_format($stats['total_questions']) }}</div>
                <div class="stat-growth-academy"><i class="ri-arrow-right-up-line"></i> এই মাসে +১২৮</div>
            </div>
            <div class="stat-card-academy">
                <div class="stat-label-academy">বহুনির্বাচনী (MCQ)</div>
                <div class="stat-value-academy" id="stat-mcq">{{ number_format($stats['mcq_count']) }}</div>
                <div class="stat-growth-academy"><i class="ri-book-read-line"></i> ৯টি বিষয়</div>
            </div>
            <div class="stat-card-academy">
                <div class="stat-label-academy">সৃজনশীল (CQ)</div>
                <div class="stat-value-academy" id="stat-cq">{{ number_format($stats['cq_count']) }}</div>
                <div class="stat-growth-academy"><i class="ri-file-edit-line"></i> ৯টি বিষয়</div>
            </div>
            <div class="stat-card-academy">
                <div class="stat-label-academy">অধ্যায় কাভার</div>
                <div class="stat-value-academy">{{ $stats['chapters_count'] }}টি</div>
                <div class="stat-growth-academy"><i class="ri-layout-grid-line"></i> {{ $stats['classes_count'] }}টি শ্রেণি</div>
            </div>
        </div>

        <!-- MAIN LAYOUT -->
        <div class="academy-layout">
            
            <!-- SIDEBAR FILTERS -->
            <div class="academy-sidebar">
                
                <!-- Class Filters -->
                <div class="filter-group-academy">
                    <h4 class="filter-title-academy">শ্রেণি</h4>
                    <div class="filter-list-academy">
                        @foreach ($stats['classes'] as $cls)
                            <label class="filter-item-academy">
                                <input type="checkbox" name="class_ids[]" value="{{ $cls['id'] }}" data-name="{{ $cls['name'] }}">
                                <span class="filter-label-academy">
                                    <span class="filter-checkbox-academy"><i class="ri-check-line"></i></span>
                                    {{ $cls['name'] }}
                                </span>
                                <span class="filter-count-academy">{{ $cls['count'] }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>

                <!-- Subject Filters -->
                <div class="filter-group-academy">
                    <h4 class="filter-title-academy">বিষয়</h4>
                    <div class="filter-list-academy">
                        @foreach ($stats['subjects'] as $sub)
                            <label class="filter-item-academy">
                                <input type="checkbox" name="subject_ids[]" value="{{ $sub['id'] }}" data-name="{{ $sub['name'] }}">
                                <span class="filter-label-academy">
                                    <span class="filter-checkbox-academy"><i class="ri-check-line"></i></span>
                                    {{ $sub['name'] }}
                                </span>
                                <span class="filter-count-academy">{{ $sub['count'] }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>

                <!-- Question Types Filters -->
                <div class="filter-group-academy">
                    <h4 class="filter-title-academy">প্রশ্নের ধরন</h4>
                    <div class="filter-list-academy">
                        @foreach ($stats['types'] as $type)
                            <label class="filter-item-academy">
                                <input type="checkbox" name="types[]" value="{{ $type['key'] }}" data-name="{{ $type['name'] }}">
                                <span class="filter-label-academy">
                                    <span class="filter-checkbox-academy"><i class="ri-check-line"></i></span>
                                    {{ $type['name'] }}
                                </span>
                                <span class="filter-count-academy">{{ $type['count'] }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>

            </div>

            <!-- CONTENT EXPLORER -->
            <div class="academy-explorer">
                
                <!-- Search & Actions -->
                <div class="controls-wrapper-academy">
                    <div class="search-input-wrapper">
                        <i class="ri-search-line"></i>
                        <input type="text" id="search-query" placeholder="প্রশ্ন, অধ্যায় বা বিষয় খুঁজুন..." oninput="debounceSearch()">
                    </div>
                    <button class="btn-control-academy" onclick="triggerFilterModal()"><i class="ri-filter-3-line"></i> ফিল্টার</button>
                    <button class="btn-control-academy" onclick="window.print()"><i class="ri-download-2-line"></i> এক্সপোর্ট</button>
                </div>

                <!-- Active badges row -->
                <div class="active-badges-wrapper" id="active-badges-container" style="display: none;">
                    <span class="badge-label-title">ফিল্টার:</span>
                    <div id="badges-list" style="display: inline-flex; flex-wrap: wrap; gap: 8px;"></div>
                    <a href="javascript:void(0)" class="badge-clear-all" onclick="clearAllFilters()">× সব মুছুন</a>
                </div>

                <!-- Loader -->
                <div class="loader-academy" id="academy-loader">
                    <div class="spinner-academy"></div>
                    <p class="loader-text">প্রশ্ন লোড হচ্ছে, দয়া করে অপেক্ষা করুন...</p>
                </div>

                <!-- Questions list container -->
                <div class="questions-list-academy" id="questions-container">
                    <!-- Loaded dynamically via JS -->
                </div>

                <!-- Pagination container -->
                <div id="pagination-container" class="mt-5 d-flex justify-content-center"></div>

            </div>

        </div>

    </div>
</div>
@endsection

@push('scripts')
<script>
    let searchTimeout = null;

    document.addEventListener("DOMContentLoaded", function() {
        // Enforce single-select (mutual exclusion) for Class checkboxes
        const classCheckboxes = document.querySelectorAll("input[name='class_ids[]']");
        classCheckboxes.forEach(cb => {
            cb.addEventListener('change', function() {
                if (this.checked) {
                    classCheckboxes.forEach(otherCb => {
                        if (otherCb !== this) {
                            otherCb.checked = false;
                        }
                    });
                }
                applyFilters(1);
            });
        });

        // Bind change events to subject and types checkboxes
        document.querySelectorAll("input[name='subject_ids[]'], input[name='types[]']").forEach(cb => {
            cb.addEventListener('change', function() {
                applyFilters(1);
            });
        });

        // Load initial questions
        applyFilters(1);
    });

    function debounceSearch() {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(function() {
            applyFilters(1);
        }, 300);
    }

    function applyFilters(page = 1) {
        // Gather Class IDs
        const classIds = [];
        document.querySelectorAll("input[name='class_ids[]']:checked").forEach(checkbox => {
            classIds.push(checkbox.value);
        });

        // Gather Subject IDs
        const subjectIds = [];
        document.querySelectorAll("input[name='subject_ids[]']:checked").forEach(checkbox => {
            subjectIds.push(checkbox.value);
        });

        // Gather Types
        const types = [];
        document.querySelectorAll("input[name='types[]']:checked").forEach(checkbox => {
            types.push(checkbox.value);
        });

        const searchQuery = document.getElementById('search-query').value;

        // Render Active Badges
        renderActiveBadges();

        // If selection is incomplete (must check at least one Class, one Subject, and one Type)
        if (classIds.length === 0 || subjectIds.length === 0 || types.length === 0) {
            document.getElementById('academy-loader').style.display = 'none';
            document.getElementById('questions-container').style.display = 'flex';
            
            let missing = [];
            if (classIds.length === 0) missing.push('শ্রেণি');
            if (subjectIds.length === 0) missing.push('বিষয়');
            if (types.length === 0) missing.push('প্রশ্নের ধরন');
            
            document.getElementById('questions-container').innerHTML = `
                <div class="question-card-academy text-center py-5" style="border-style: dashed; width: 100%;">
                    <p class="fs-5 text-muted mb-0">প্রশ্ন দেখতে অনুগ্রহ করে সাইডবার থেকে <strong>${missing.join(', ')}</strong> সিলেক্ট করুন।</p>
                </div>
            `;
            document.getElementById('pagination-container').innerHTML = '';
            document.getElementById('stat-total').innerText = formatBanglaNumber(0);
            return;
        }

        // Show loader, hide list
        document.getElementById('questions-container').style.display = 'none';
        document.getElementById('academy-loader').style.display = 'block';

        // AJAX Query
        const params = new URLSearchParams();
        classIds.forEach(id => params.append('class_ids[]', id));
        subjectIds.forEach(id => params.append('subject_ids[]', id));
        types.forEach(type => params.append('types[]', type));
        if (searchQuery) params.append('search', searchQuery);
        params.append('page', page);

        fetch("{{ route('academy.filter') }}?" + params.toString())
            .then(res => res.json())
            .then(data => {
                document.getElementById('academy-loader').style.display = 'none';
                document.getElementById('questions-container').style.display = 'flex';
                
                // Set counts
                if (data.pagination) {
                    document.getElementById('stat-total').innerText = formatBanglaNumber(data.pagination.total);
                }

                renderQuestionsList(data.questions, data.pagination);
                renderPagination(data.pagination);
            })
            .catch(err => {
                console.error("Error fetching filtered questions", err);
                document.getElementById('academy-loader').style.display = 'none';
            });
    }

    function renderQuestionsList(questions, pagination) {
        const container = document.getElementById('questions-container');
        container.innerHTML = '';

        if (!questions || questions.length === 0) {
            container.innerHTML = `
                <div class="question-card-academy text-center py-5">
                    <p class="fs-5 text-muted mb-0">কোনো প্রশ্ন খুঁজে পাওয়া যায়নি।</p>
                </div>
            `;
            return;
        }

        const currentPage = pagination ? pagination.current_page : 1;
        const perPage = pagination ? pagination.per_page : 20;

        questions.forEach((q, idx) => {
            const serialNum = (currentPage - 1) * perPage + idx + 1;
            const serialNumBn = formatBanglaNumber(serialNum);
            
            // Prepend serial number to question text
            const displayQuestion = `<span style="font-weight: 700; color: var(--accent-gold); margin-right: 8px;">${serialNumBn}.</span>${q.question}`;

            let optionsHtml = '';
            if (q.question_type === 'mcq' && q.options) {
                optionsHtml = `
                    <div class="q-options-academy">
                        <div class="q-option-item-academy ${q.correct_answer === '1' ? 'correct' : ''}">
                            <span class="q-option-prefix">ক.</span> ${q.options.option_a || ''}
                        </div>
                        <div class="q-option-item-academy ${q.correct_answer === '2' ? 'correct' : ''}">
                            <span class="q-option-prefix">খ.</span> ${q.options.option_b || ''}
                        </div>
                        <div class="q-option-item-academy ${q.correct_answer === '3' ? 'correct' : ''}">
                            <span class="q-option-prefix">গ.</span> ${q.options.option_c || ''}
                        </div>
                        <div class="q-option-item-academy ${q.correct_answer === '4' ? 'correct' : ''}">
                            <span class="q-option-prefix">ঘ.</span> ${q.options.option_d || ''}
                        </div>
                    </div>
                `;
            }

            const card = document.createElement('div');
            card.className = 'question-card-academy';
            card.innerHTML = `
                <div class="q-header-academy">
                    <div class="q-text-academy">${displayQuestion}</div>
                    <div class="q-actions-academy">
                        <a href="${q.edit_url}" target="_blank" class="q-btn-action edit" title="এডিট করুন"><i class="ri-edit-line"></i></a>
                        <button class="q-btn-action" onclick="copyToClipboard(this, ${q.id})" title="কপি করুন"><i class="ri-file-copy-line"></i></button>
                        <button class="q-btn-action" onclick="printQuestion(${q.id})" title="প্রিন্ট করুন"><i class="ri-printer-line"></i></button>
                    </div>
                </div>
                ${optionsHtml}
                <div class="q-tags-academy">
                    ${q.job_category_name ? `<span class="q-tag-badge"><i class="ri-book-3-line"></i> ${q.job_category_name}</span>` : ''}
                    ${q.category_name ? `<span class="q-tag-badge"><i class="ri-folder-open-line"></i> ${q.category_name}</span>` : ''}
                    <span class="q-tag-badge"><i class="ri-speed-line"></i> ${q.hard_level_name}</span>
                    ${q.year_name ? `<span class="q-tag-badge"><i class="ri-calendar-line"></i> ${q.year_name}</span>` : ''}
                </div>
            `;
            container.appendChild(card);
        });
    }

    function renderActiveBadges() {
        const container = document.getElementById('active-badges-container');
        const list = document.getElementById('badges-list');
        list.innerHTML = '';

        let badgeCount = 0;

        // Collect Checked Checkboxes
        document.querySelectorAll(".academy-sidebar input[type='checkbox']:checked").forEach(checkbox => {
            badgeCount++;
            const badge = document.createElement('span');
            badge.className = 'filter-badge-item';
            badge.innerHTML = `${checkbox.getAttribute('data-name')} <i class="ri-close-line" onclick="removeSingleFilter('${checkbox.name}', '${checkbox.value}')"></i>`;
            list.appendChild(badge);
        });

        // Search badge
        const searchVal = document.getElementById('search-query').value;
        if (searchVal) {
            badgeCount++;
            const badge = document.createElement('span');
            badge.className = 'filter-badge-item';
            badge.innerHTML = `সার্চ: "${searchVal}" <i class="ri-close-line" onclick="clearSearch()"></i>`;
            list.appendChild(badge);
        }

        if (badgeCount > 0) {
            container.style.display = 'flex';
        } else {
            container.style.display = 'none';
        }
    }

    function removeSingleFilter(name, value) {
        document.querySelectorAll(`input[name='${name}']`).forEach(checkbox => {
            if (checkbox.value === value) {
                checkbox.checked = false;
            }
        });
        applyFilters(1);
    }

    function clearSearch() {
        document.getElementById('search-query').value = '';
        applyFilters(1);
    }

    function clearAllFilters() {
        document.querySelectorAll(".academy-sidebar input[type='checkbox']").forEach(checkbox => {
            checkbox.checked = false;
        });
        document.getElementById('search-query').value = '';
        applyFilters(1);
    }

    function copyToClipboard(button, qId) {
        const card = button.closest('.question-card-academy');
        const qText = card.querySelector('.q-text-academy').innerText;
        
        let copyText = qText + "\n";
        
        const options = card.querySelectorAll('.q-option-item-academy');
        options.forEach(opt => {
            copyText += opt.innerText + "\n";
        });

        navigator.clipboard.writeText(copyText).then(() => {
            toastr.success("প্রশ্নটি ক্লিপবোর্ডে কপি করা হয়েছে!");
            const icon = button.querySelector('i');
            icon.className = 'ri-check-line';
            setTimeout(() => {
                icon.className = 'ri-file-copy-line';
            }, 2000);
        });
    }

    function printQuestion(qId) {
        // Trigger specific window print targeting only this card or standard browser print
        window.print();
    }

    function triggerFilterModal() {
        // Can be linked to extra filtering options modal if needed
        toastr.info("অতিরিক্ত ফিল্টারিং অপশনসমূহ সাইডবারে পেয়ে যাবেন।");
    }

    function renderPagination(pagination) {
        const container = document.getElementById('pagination-container');
        container.innerHTML = '';

        if (!pagination || pagination.last_page <= 1) return;

        const nav = document.createElement('nav');
        const ul = document.createElement('ul');
        ul.className = 'pagination';

        // Prev Page
        const prevLi = document.createElement('li');
        prevLi.className = `page-item ${pagination.current_page === 1 ? 'disabled' : ''}`;
        prevLi.innerHTML = `<a class="page-link" href="javascript:void(0)" onclick="applyFilters(${pagination.current_page - 1})">পূর্ববর্তী</a>`;
        ul.appendChild(prevLi);

        // Page Numbers
        for (let i = 1; i <= pagination.last_page; i++) {
            const pageLi = document.createElement('li');
            pageLi.className = `page-item ${pagination.current_page === i ? 'active' : ''}`;
            pageLi.innerHTML = `<a class="page-link" href="javascript:void(0)" onclick="applyFilters(${i})">${formatBanglaNumber(i)}</a>`;
            ul.appendChild(pageLi);
        }

        // Next Page
        const nextLi = document.createElement('li');
        nextLi.className = `page-item ${pagination.current_page === pagination.last_page ? 'disabled' : ''}`;
        nextLi.innerHTML = `<a class="page-link" href="javascript:void(0)" onclick="applyFilters(${pagination.current_page + 1})">পরবর্তী</a>`;
        ul.appendChild(nextLi);

        nav.appendChild(ul);
        container.appendChild(nav);
    }

    function formatBanglaNumber(num) {
        const banglaDigits = {'0':'০','1':'১','2':'২','3':'৩','4':'৪','5':'৫','6':'৬','7':'৭','8':'৮','9':'৯'};
        return num.toString().replace(/[0-9]/g, digit => banglaDigits[digit]);
    }
</script>
@endpush
