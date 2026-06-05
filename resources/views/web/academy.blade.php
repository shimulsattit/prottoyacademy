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
    .academy-explorer {
        position: relative;
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
        transition: opacity 0.3s ease;
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
        min-width: 0;
        word-break: break-word;
        overflow-wrap: break-word;
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
    .q-btn-action.view {
        width: auto;
        padding: 0 10px;
        gap: 6px;
    }
    .q-view-count {
        font-size: 13px;
        font-weight: 600;
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
        gap: 10px;
    }
    .q-option-prefix {
        font-weight: 700;
        color: #fff;
    }
    .q-option-item-academy.correct {
        border-color: rgba(34, 197, 94, 0.3);
        background: rgba(34, 197, 94, 0.06);
        color: #22c55e;
    }
    .option-bubble {
        width: 14px;
        height: 14px;
        border: 1.5px solid rgba(255, 255, 255, 0.25);
        border-radius: 50%;
        display: inline-block;
        flex-shrink: 0;
        transition: all 0.2s ease;
        position: relative;
        box-sizing: border-box;
    }
    .option-bubble.filled {
        background-color: #22c55e;
        border-color: #22c55e;
    }
    .option-bubble.filled::after {
        content: "";
        display: block;
        width: 6px;
        height: 6px;
        margin: 2.5px;
        background-color: #fff;
        border-radius: 50%;
        box-sizing: border-box;
    }

    /* SUBTITLE DESCRIPTION */
    .page-subtitle-premium {
        color: var(--accent-gold) !important;
        font-size: 15px;
        font-weight: 500;
        margin-top: 10px;
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
        position: absolute;
        top: 250px;
        left: 50%;
        transform: translate(-50%, -50%);
        z-index: 100;
        text-align: center;
        padding: 30px 40px;
        background: rgba(15, 17, 38, 0.95);
        border: 1px solid rgba(245, 197, 24, 0.25);
        border-radius: 20px;
        box-shadow: 0 20px 50px rgba(0, 0, 0, 0.7);
        backdrop-filter: blur(12px);
        display: none;
        width: 320px;
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

    /* PREMIUM PAGINATION */
    .pagination {
        display: flex;
        flex-wrap: wrap;
        padding-left: 0;
        list-style: none;
        gap: 6px;
        margin: 30px 0;
    }
    .pagination .page-item {
        margin: 0;
    }
    .pagination .page-link {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 38px;
        height: 38px;
        padding: 0 12px;
        border-radius: 8px !important;
        background: rgba(255, 255, 255, 0.03);
        border: 1px solid rgba(255, 255, 255, 0.07);
        color: rgba(255, 255, 255, 0.6) !important;
        font-size: 14px;
        font-weight: 600;
        text-decoration: none;
        transition: all 0.2s;
    }
    .pagination .page-link:hover {
        background: rgba(255, 255, 255, 0.08);
        border-color: rgba(255, 255, 255, 0.15);
        color: #fff !important;
    }
    .pagination .page-item.active .page-link {
        background: var(--accent-gold) !important;
        border-color: var(--accent-gold) !important;
        color: #000 !important;
    }
    .pagination .page-item.disabled .page-link {
        opacity: 0.4;
        pointer-events: none;
        background: rgba(255, 255, 255, 0.01);
        border-color: rgba(255, 255, 255, 0.03);
        color: rgba(255, 255, 255, 0.3) !important;
    }

    .print-only-container {
        display: none !important;
    }

    /* PRINT LAYOUT */
    @media print {
        @page {
            size: A4 portrait;
            margin: 0;
        }
        body {
            background: #fff !important;
            color: #000 !important;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }
        header, footer, nav, 
        .edu-header, .edu-footer, 
        .main-wrapper > header, 
        .main-wrapper > footer, 
        .main-wrapper > .rn-progress-parent,
        .rn-progress-parent,
        .section-premium {
            display: none !important;
        }
        body, .main-wrapper {
            background: #fff !important;
            color: #000 !important;
            margin: 0 !important;
            padding: 0 !important;
            box-shadow: none !important;
            overflow: visible !important;
        }
        .print-only-container {
            display: block !important;
            width: 100% !important;
            background: #fff !important;
            color: #000 !important;
        }
        .print-page {
            width: 210mm;
            height: 297mm;
            box-sizing: border-box;
            padding: 20mm;
            position: relative;
            page-break-after: always;
            break-after: page;
            display: flex;
            flex-direction: column;
            background: #fff !important;
            overflow: hidden;
        }
        .print-watermark {
            position: absolute;
            top: 55%;
            left: 50%;
            transform: translate(-50%, -50%) rotate(-45deg);
            font-size: 80px;
            font-weight: 900;
            color: rgba(0, 0, 0, 0.04) !important;
            white-space: nowrap;
            pointer-events: none;
            z-index: 1;
            user-select: none;
            font-family: 'Hind Siliguri', 'Noto Sans Bengali', sans-serif;
        }
        .print-header {
            border-bottom: 1.5px solid #000;
            padding-bottom: 8px;
            margin-bottom: 15px;
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
            z-index: 2;
        }
        .print-header-title {
            font-size: 16px;
            font-weight: 700;
            color: #000;
            font-family: 'Hind Siliguri', 'Noto Sans Bengali', sans-serif;
        }
        .print-header-meta {
            font-size: 11px;
            color: #333;
            text-align: right;
            line-height: 1.4;
        }
        .print-columns {
            display: grid;
            grid-template-columns: 1fr 1fr;
            column-gap: 24px;
            row-gap: 12px;
            flex: 1;
            align-content: start;
            z-index: 2;
        }
        .print-q-card {
            page-break-inside: avoid;
            break-inside: avoid;
            font-size: 11px;
            line-height: 1.35;
            color: #000;
            border-bottom: 1px dashed #ddd;
            padding-bottom: 8px;
        }
        .print-q-text {
            font-weight: 600;
            margin-bottom: 4px;
        }
        .print-q-options {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 3px;
            padding-left: 8px;
        }
        .print-q-option {
            display: flex;
            align-items: center;
            gap: 6px;
        }
        .print-option-bubble {
            width: 10px;
            height: 10px;
            border: 1px solid #000;
            border-radius: 50%;
            display: inline-block;
            flex-shrink: 0;
            box-sizing: border-box;
            background: transparent !important;
        }
        .print-option-bubble.filled {
            background-color: #000 !important;
            border-color: #000 !important;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }
        .print-option-bubble.filled::after {
            content: "";
            display: block;
            width: 6px;
            height: 6px;
            margin: 1px;
            background-color: #000 !important;
            border-radius: 50%;
            }
    }

    /* RESPONSIVE DESIGN */
    @media (max-width: 992px) {
        .section-premium {
            padding: 100px 0 60px !important;
        }
        .stats-grid-academy {
            grid-template-columns: repeat(2, 1fr);
            gap: 16px;
        }
        .academy-layout {
            grid-template-columns: 1fr;
            gap: 24px;
        }
    }

    @media (max-width: 576px) {
        .academy-sidebar {
            padding: 16px;
        }
        .stats-grid-academy {
            grid-template-columns: 1fr;
            gap: 12px;
        }
        .stat-card-academy {
            padding: 16px 20px;
        }
        .stat-value-academy {
            font-size: 26px;
        }
        .controls-wrapper-academy {
            flex-direction: column;
            align-items: stretch;
            gap: 10px;
        }
        .search-input-wrapper input {
            padding: 12px 16px 12px 42px;
            font-size: 14px;
        }
        .search-input-wrapper i {
            left: 14px;
            font-size: 16px;
        }
        .btn-control-academy {
            width: 100%;
            justify-content: center;
            padding: 12px 16px;
            font-size: 13.5px;
        }
        .q-options-academy {
            grid-template-columns: 1fr;
            gap: 8px;
        }
        .q-option-item-academy {
            padding: 8px 12px;
            font-size: 13px;
        }
        .question-card-academy {
            padding: 16px 16px;
        }
        .q-text-academy {
            font-size: 14px;
            line-height: 1.5;
        }
        .q-header-academy {
            gap: 12px;
        }
    }

</style>
@endpush

@section('content')
<!-- Print-only template container -->
<div id="print-template-container" class="print-only-container"></div>

<div class="section-premium">
    <div class="container">
        
        <!-- BREADCRUMB -->
        <div class="premium-breadcrumb">
            <a href="{{ route('home') }}">হোম</a>
            <span>/</span>
            <span class="active">একাডেমিক কোশ্চেন ব্যাংক</span>
        </div>

        <!-- HEADER SUBTITLE -->
        <div class="page-header-premium mb-4">
            <p class="page-subtitle-premium">সহজে শ্রেণি, বিষয় এবং অধ্যায় ভিত্তিক প্রশ্ন অনুসন্ধান করুন</p>
        </div>

        <!-- STATS GRID -->
        <div class="stats-grid-academy">
            <div class="stat-card-academy">
                <div class="stat-label-academy">মোট প্রশ্ন</div>
                <div class="stat-value-academy" id="stat-total">{{ strtr(number_format($stats['total_questions']), ['0'=>'০','1'=>'১','2'=>'২','3'=>'৩','4'=>'৪','5'=>'৫','6'=>'৬','7'=>'৭','8'=>'৮','9'=>'৯']) }}</div>
                <div class="stat-growth-academy"><i class="ri-arrow-right-up-line"></i> এই মাসে +১২৮</div>
            </div>
            <div class="stat-card-academy">
                <div class="stat-label-academy">বহুনির্বাচনী (MCQ)</div>
                <div class="stat-value-academy" id="stat-mcq">{{ strtr(number_format($stats['mcq_count']), ['0'=>'০','1'=>'১','2'=>'২','3'=>'৩','4'=>'৪','5'=>'৫','6'=>'৬','7'=>'৭','8'=>'৮','9'=>'৯']) }}</div>
                <div class="stat-growth-academy"><i class="ri-book-read-line"></i> <span id="stat-mcq-subjects">{{ strtr(count($stats['subjects']), ['0'=>'০','1'=>'১','2'=>'২','3'=>'৩','4'=>'৪','5'=>'৫','6'=>'৬','7'=>'৭','8'=>'৮','9'=>'৯']) }}</span>টি বিষয়</div>
            </div>
            <div class="stat-card-academy">
                <div class="stat-label-academy">সৃজনশীল (CQ)</div>
                <div class="stat-value-academy" id="stat-cq">{{ strtr(number_format($stats['cq_count']), ['0'=>'০','1'=>'১','2'=>'২','3'=>'৩','4'=>'৪','5'=>'৫','6'=>'৬','7'=>'৭','8'=>'৮','9'=>'৯']) }}</div>
                <div class="stat-growth-academy"><i class="ri-file-edit-line"></i> <span id="stat-cq-subjects">{{ strtr(count($stats['subjects']), ['0'=>'০','1'=>'১','2'=>'২','3'=>'৩','4'=>'৪','5'=>'৫','6'=>'৬','7'=>'৭','8'=>'৮','9'=>'৯']) }}</span>টি বিষয়</div>
            </div>
            <div class="stat-card-academy">
                <div class="stat-label-academy">অধ্যায় কাভার</div>
                <div class="stat-value-academy" id="stat-chapters">{{ strtr($stats['chapters_count'], ['0'=>'০','1'=>'১','2'=>'২','3'=>'৩','4'=>'৪','5'=>'৫','6'=>'৬','7'=>'৭','8'=>'৮','9'=>'৯']) }}টি</div>
                <div class="stat-growth-academy"><i class="ri-layout-grid-line"></i> <span id="stat-classes">{{ strtr($stats['classes_count'], ['0'=>'০','1'=>'১','2'=>'২','3'=>'৩','4'=>'৪','5'=>'৫','6'=>'৬','7'=>'৭','8'=>'৮','9'=>'৯']) }}</span>টি শ্রেণি</div>
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
                                <input type="checkbox" name="class_ids[]" value="{{ $cls['id'] }}" data-name="{{ $cls['name'] }}" {{ (isset($selectedClassId) && $selectedClassId == $cls['id']) ? 'checked' : '' }}>
                                <span class="filter-label-academy">
                                    <span class="filter-checkbox-academy"><i class="ri-check-line"></i></span>
                                    {{ $cls['name'] }}
                                </span>
                                <span class="filter-count-academy">{{ strtr($cls['count'], ['0'=>'০','1'=>'১','2'=>'২','3'=>'৩','4'=>'৪','5'=>'৫','6'=>'৬','7'=>'৭','8'=>'৮','9'=>'৯']) }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>
                
                <!-- Subject Filters -->
                <div class="filter-group-academy" id="subject-filter-group" style="display: none;">
                    <h4 class="filter-title-academy">বিষয়</h4>
                    <div class="filter-list-academy">
                        @foreach ($stats['subjects'] as $sub)
                            <div class="subject-item-wrapper-academy" data-class-id="{{ $sub['class_id'] }}" style="display: none;">
                                <div class="subject-item-group-academy mb-2">
                                    <label class="filter-item-academy">
                                        <input type="checkbox" name="subject_ids[]" value="{{ $sub['id'] }}" data-name="{{ $sub['name'] }}" {{ (isset($selectedSubjectId) && $selectedSubjectId == $sub['id']) ? 'checked' : '' }}>
                                        <span class="filter-label-academy">
                                            <span class="filter-checkbox-academy"><i class="ri-check-line"></i></span>
                                            {{ $sub['name'] }}
                                        </span>
                                        <span class="filter-count-academy">{{ strtr($sub['count'], ['0'=>'০','1'=>'১','2'=>'২','3'=>'৩','4'=>'৪','5'=>'৫','6'=>'৬','7'=>'৭','8'=>'৮','9'=>'৯']) }}</span>
                                    </label>
                                    @if(!empty($sub['chapters']))
                                        <div class="chapter-list-academy" style="padding-left: 20px; display: none; flex-direction: column; gap: 8px; margin-top: 8px; margin-bottom: 8px;">
                                            @foreach($sub['chapters'] as $chap)
                                                <label class="filter-item-academy" style="opacity: 0.85;">
                                                    <input type="checkbox" name="chapter_ids[]" value="{{ $chap['id'] }}" data-name="{{ $chap['name'] }}" {{ (isset($selectedChapterId) && $selectedChapterId == $chap['id']) ? 'checked' : '' }}>
                                                    <span class="filter-label-academy" style="font-size: 13px;">
                                                        <span class="filter-checkbox-academy" style="width: 15px; height: 15px; border-radius: 3px;"><i class="ri-check-line" style="font-size: 9px;"></i></span>
                                                        {{ $chap['name'] }}
                                                    </span>
                                                    <span class="filter-count-academy" style="font-size: 10px; padding: 1px 6px;">{{ strtr($chap['count'], ['0'=>'০','1'=>'১','2'=>'২','3'=>'৩','4'=>'৪','5'=>'৫','6'=>'৬','7'=>'৭','8'=>'৮','9'=>'৯']) }}</span>
                                                </label>
                                            @endforeach
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Question Types Filters -->
                <div class="filter-group-academy">
                    <h4 class="filter-title-academy">প্রশ্নের ধরন</h4>
                    <div class="filter-list-academy">
                        @foreach ($stats['types'] as $type)
                            <label class="filter-item-academy">
                                <input type="checkbox" name="types[]" value="{{ $type['key'] }}" data-name="{{ $type['name'] }}" {{ ($type['key'] === 'mcq' || (isset($selectedClassId) && $type['count'] > 0)) ? 'checked' : '' }}>
                                <span class="filter-label-academy">
                                    <span class="filter-checkbox-academy"><i class="ri-check-line"></i></span>
                                    {{ $type['name'] }}
                                </span>
                                <span class="filter-count-academy type-count-badge" data-type-key="{{ $type['key'] }}">{{ strtr($type['count'], ['0'=>'০','1'=>'১','2'=>'২','3'=>'৩','4'=>'৪','5'=>'৫','6'=>'৬','7'=>'৭','8'=>'৮','9'=>'৯']) }}</span>
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
                    <button class="btn-control-academy" onclick="printAllQuestions()"><i class="ri-printer-line"></i> প্রিন্ট</button>
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

    // Function to update visibility of subjects based on selected classes
    function updateSubjectsVisibility() {
        const checkedClassIds = Array.from(document.querySelectorAll("input[name='class_ids[]']:checked")).map(cb => cb.value);
        const subjectFilterGroup = document.getElementById("subject-filter-group");
        let visibleSubjectsCount = 0;
        
        document.querySelectorAll(".subject-item-wrapper-academy").forEach(wrapper => {
            const classId = wrapper.getAttribute('data-class-id');
            if (checkedClassIds.includes(classId)) {
                wrapper.style.display = 'block';
                visibleSubjectsCount++;
            } else {
                wrapper.style.display = 'none';
                // Uncheck subject and its chapters if class is unchecked
                const subjectCb = wrapper.querySelector("input[name='subject_ids[]']");
                if (subjectCb && subjectCb.checked) {
                    subjectCb.checked = false;
                }
            }
        });

        if (subjectFilterGroup) {
            if (visibleSubjectsCount > 0) {
                subjectFilterGroup.style.display = 'block';
            } else {
                subjectFilterGroup.style.display = 'none';
            }
        }
    }

    // Function to update visibility of chapters based on selected subjects
    function updateChaptersVisibility() {
        document.querySelectorAll(".subject-item-wrapper-academy").forEach(wrapper => {
            const subjectCb = wrapper.querySelector("input[name='subject_ids[]']");
            const chapterList = wrapper.querySelector(".chapter-list-academy");
            
            if (chapterList) {
                if (subjectCb && subjectCb.checked && wrapper.style.display !== 'none') {
                    chapterList.style.display = 'flex';
                } else {
                    chapterList.style.display = 'none';
                    // Uncheck chapters if subject is unchecked
                    chapterList.querySelectorAll("input[name='chapter_ids[]']:checked").forEach(chapCb => {
                        chapCb.checked = false;
                    });
                }
            }
        });
    }

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
                updateSubjectsVisibility();
                updateChaptersVisibility();
                applyFilters(1);
            });
        });

        // Bind Subject change events
        const subjectCheckboxes = document.querySelectorAll("input[name='subject_ids[]']");
        subjectCheckboxes.forEach(cb => {
            cb.addEventListener('change', function() {
                updateChaptersVisibility();
                applyFilters(1);
            });
        });

        // Bind Chapter change events
        document.querySelectorAll("input[name='chapter_ids[]']").forEach(cb => {
            cb.addEventListener('change', function() {
                applyFilters(1);
            });
        });

        // Bind Type change events
        document.querySelectorAll("input[name='types[]']").forEach(cb => {
            cb.addEventListener('change', function() {
                applyFilters(1);
            });
        });

        // Initialize visibility on load
        updateSubjectsVisibility();
        updateChaptersVisibility();

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

        // Gather Chapter IDs
        const chapterIds = [];
        document.querySelectorAll("input[name='chapter_ids[]']:checked").forEach(checkbox => {
            chapterIds.push(checkbox.value);
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
            const qContainer = document.getElementById('questions-container');
            qContainer.style.display = 'flex';
            qContainer.style.opacity = '1';
            qContainer.style.pointerEvents = 'auto';
            
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

        // Show loader smoothly, fade list (avoiding display: none to prevent layout collapse)
        const qContainer = document.getElementById('questions-container');
        qContainer.style.opacity = '0.2';
        qContainer.style.pointerEvents = 'none';
        document.getElementById('academy-loader').style.display = 'block';

        // AJAX Query
        const params = new URLSearchParams();
        classIds.forEach(id => params.append('class_ids[]', id));
        subjectIds.forEach(id => params.append('subject_ids[]', id));
        chapterIds.forEach(id => params.append('chapter_ids[]', id));
        types.forEach(type => params.append('types[]', type));
        if (searchQuery) params.append('search', searchQuery);
        params.append('page', page);

        fetch("{{ route('academy.filter') }}?" + params.toString())
            .then(res => res.json())
            .then(data => {
                console.log("Academy filter AJAX response data:", data);
                document.getElementById('academy-loader').style.display = 'none';
                
                const qContainer = document.getElementById('questions-container');
                qContainer.style.opacity = '1';
                qContainer.style.pointerEvents = 'auto';
                
                window.lastFetchedQuestions = data.questions;
                
                // Set counts
                try {
                    if (data.stats) {
                        document.getElementById('stat-total').innerText = formatBanglaNumber(data.stats.total_questions);
                        document.getElementById('stat-mcq').innerText = formatBanglaNumber(data.stats.mcq_count);
                        document.getElementById('stat-cq').innerText = formatBanglaNumber(data.stats.cq_count);
                        
                        const chaptersVal = document.getElementById('stat-chapters');
                        if (chaptersVal) {
                            chaptersVal.innerText = formatBanglaNumber(data.stats.chapters_count) + 'টি';
                        }
                        
                        const mcqSub = document.getElementById('stat-mcq-subjects');
                        if (mcqSub) {
                            mcqSub.innerText = formatBanglaNumber(data.stats.subjects_count);
                        }
                        
                        const cqSub = document.getElementById('stat-cq-subjects');
                        if (cqSub) {
                            cqSub.innerText = formatBanglaNumber(data.stats.subjects_count);
                        }
                        
                        const classesVal = document.getElementById('stat-classes');
                        if (classesVal) {
                            classesVal.innerText = formatBanglaNumber(data.stats.classes_count);
                        }

                        // Update type counts in sidebar
                        document.querySelectorAll('.type-count-badge').forEach(badge => {
                            const key = badge.getAttribute('data-type-key');
                            if (key === 'mcq') {
                                badge.innerText = formatBanglaNumber(data.stats.mcq_count);
                            } else if (key === 'cq') {
                                badge.innerText = formatBanglaNumber(data.stats.cq_count);
                            } else if (key === 'short') {
                                badge.innerText = formatBanglaNumber(data.stats.short_count);
                            }
                        });
                    } else if (data.pagination) {
                        document.getElementById('stat-total').innerText = formatBanglaNumber(data.pagination.total);
                    }
                } catch (statsErr) {
                    console.error("Error updating statistics:", statsErr);
                }

                try {
                    renderQuestionsList(data.questions, data.pagination);
                } catch (renderErr) {
                    console.error("Error rendering questions list:", renderErr);
                }

                try {
                    renderPagination(data.pagination);
                } catch (paginateErr) {
                    console.error("Error rendering pagination:", paginateErr);
                }
            })
            .catch(err => {
                console.error("Error fetching filtered questions", err);
                document.getElementById('academy-loader').style.display = 'none';
                
                const qContainer = document.getElementById('questions-container');
                qContainer.style.opacity = '1';
                qContainer.style.pointerEvents = 'auto';
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

        const currentPage = parseInt(pagination ? pagination.current_page : 1) || 1;
        const perPage = parseInt(pagination ? pagination.per_page : 20) || 20;

        questions.forEach((q, idx) => {
            const serialNum = (currentPage - 1) * perPage + idx + 1;
            const serialNumBn = formatBanglaNumber(serialNum);
            
            // Prepend serial number to question text
            const displayQuestion = `<span style="font-weight: 700; color: #fff; margin-right: 8px;">${serialNumBn}.</span>${q.question}`;
 
            let optionsHtml = '';
            if (q.question_type === 'mcq' && q.options) {
                optionsHtml = `
                    <div class="q-options-academy">
                        <div class="q-option-item-academy ${q.correct_answer === '1' ? 'correct' : ''}">
                            <span class="option-bubble ${q.correct_answer === '1' ? 'filled' : ''}"></span>
                            <span class="q-option-prefix">ক.</span> ${q.options.option_a || ''}
                        </div>
                        <div class="q-option-item-academy ${q.correct_answer === '2' ? 'correct' : ''}">
                            <span class="option-bubble ${q.correct_answer === '2' ? 'filled' : ''}"></span>
                            <span class="q-option-prefix">খ.</span> ${q.options.option_b || ''}
                        </div>
                        <div class="q-option-item-academy ${q.correct_answer === '3' ? 'correct' : ''}">
                            <span class="option-bubble ${q.correct_answer === '3' ? 'filled' : ''}"></span>
                            <span class="q-option-prefix">গ.</span> ${q.options.option_c || ''}
                        </div>
                        <div class="q-option-item-academy ${q.correct_answer === '4' ? 'correct' : ''}">
                            <span class="option-bubble ${q.correct_answer === '4' ? 'filled' : ''}"></span>
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
                        <a href="${q.edit_url}" target="_blank" class="q-btn-action view" onclick="incrementViewCount(${q.id}, this); return true;" title="বিস্তারিত দেখুন">
                            <i class="ri-eye-line"></i>
                            <span class="q-view-count">${formatBanglaNumber(q.view || 0)}</span>
                        </a>
                    </div>
                </div>
                ${optionsHtml}
                <div class="q-tags-academy">
                    ${q.job_category_name ? `<span class="q-tag-badge"><i class="ri-book-3-line"></i> ${q.job_category_name}</span>` : ''}
                    ${q.category_name ? `<span class="q-tag-badge"><i class="ri-folder-open-line"></i> ${q.category_name}</span>` : ''}
                </div>
            `;
            container.appendChild(card);
        });

        // Keep print-only template container updated in background
        updatePrintTemplate(questions);
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
        updateSubjectsVisibility();
        updateChaptersVisibility();
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
        updateSubjectsVisibility();
        updateChaptersVisibility();
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
        console.log("renderPagination pagination data:", pagination);
        const container = document.getElementById('pagination-container');
        container.innerHTML = '';

        if (!pagination) {
            console.warn("renderPagination: pagination data is missing.");
            return;
        }

        const currentPage = parseInt(pagination.current_page) || 1;
        const lastPage = parseInt(pagination.last_page) || 1;

        if (lastPage < 1) {
            console.warn("renderPagination: lastPage is less than 1.");
            return;
        }

        const nav = document.createElement('div');
        const ul = document.createElement('ul');
        ul.className = 'pagination';

        // Prev Page
        const prevLi = document.createElement('li');
        prevLi.className = `page-item ${currentPage === 1 ? 'disabled' : ''}`;
        prevLi.innerHTML = `<a class="page-link" href="javascript:void(0)" onclick="applyFilters(${currentPage - 1})" aria-label="Previous"><i class="ri-arrow-left-s-line"></i></a>`;
        ul.appendChild(prevLi);

        // Page Numbers
        for (let i = 1; i <= lastPage; i++) {
            const pageLi = document.createElement('li');
            pageLi.className = `page-item ${currentPage === i ? 'active' : ''}`;
            pageLi.innerHTML = `<a class="page-link" href="javascript:void(0)" onclick="applyFilters(${i})">${formatBanglaNumber(i)}</a>`;
            ul.appendChild(pageLi);
        }

        // Next Page
        const nextLi = document.createElement('li');
        nextLi.className = `page-item ${currentPage === lastPage ? 'disabled' : ''}`;
        nextLi.innerHTML = `<a class="page-link" href="javascript:void(0)" onclick="applyFilters(${currentPage + 1})" aria-label="Next"><i class="ri-arrow-right-s-line"></i></a>`;
        ul.appendChild(nextLi);

        nav.appendChild(ul);
        container.appendChild(nav);
    }

    function formatBanglaNumber(num) {
        const banglaDigits = {'0':'০','1':'১','2':'২','3':'৩','4':'৪','5':'৫','6':'৬','7':'৭','8':'৮','9':'৯'};
        return num.toString().replace(/[0-9]/g, digit => banglaDigits[digit]);
    }

    function updatePrintTemplate(questions) {
        try {
            const printContainer = document.getElementById('print-template-container');
            if (!printContainer) return;

            printContainer.innerHTML = '';

            if (!questions || questions.length === 0) return;

            // Get active filter details
            const classNames = Array.from(document.querySelectorAll("input[name='class_ids[]']:checked")).map(cb => cb.getAttribute('data-name'));
            const subjectNames = Array.from(document.querySelectorAll("input[name='subject_ids[]']:checked")).map(cb => cb.getAttribute('data-name'));
            const chapterNames = Array.from(document.querySelectorAll("input[name='chapter_ids[]']:checked")).map(cb => cb.getAttribute('data-name'));

            const classDetail = classNames.length > 0 ? classNames.join(', ') : 'সব শ্রেণি';
            const subjectDetail = subjectNames.length > 0 ? subjectNames.join(', ') : 'সব বিষয়';
            const chapterDetail = chapterNames.length > 0 ? chapterNames.join(', ') : 'সব অধ্যায়';

            const questionsPerPage = 25;
            const totalPages = Math.ceil(questions.length / questionsPerPage);

            for (let pageIdx = 0; pageIdx < totalPages; pageIdx++) {
                const pageDiv = document.createElement('div');
                pageDiv.className = 'print-page';

                // Watermark
                const watermark = document.createElement('div');
                watermark.className = 'print-watermark';
                watermark.innerText = 'প্রত্যয় একাডেমি';
                pageDiv.appendChild(watermark);

                // Header
                const header = document.createElement('div');
                header.className = 'print-header';
                
                const headerTitle = document.createElement('div');
                headerTitle.className = 'print-header-title';
                headerTitle.innerText = 'একাডেমিক কোশ্চেন ব্যাংক';
                
                const headerMeta = document.createElement('div');
                headerMeta.className = 'print-header-meta';
                headerMeta.innerHTML = `
                    <div><strong>শ্রেণি:</strong> ${classDetail} | <strong>বিষয়:</strong> ${subjectDetail}</div>
                    <div><strong>অধ্যায়:</strong> ${chapterDetail} | <strong>পৃষ্ঠা:</strong> ${formatBanglaNumber(pageIdx + 1)}/ ${formatBanglaNumber(totalPages)}</div>
                `;
                
                header.appendChild(headerTitle);
                header.appendChild(headerMeta);
                pageDiv.appendChild(header);

                // Columns Container
                const columnsDiv = document.createElement('div');
                columnsDiv.className = 'print-columns';

                // Populate 25 questions
                const startIdx = pageIdx * questionsPerPage;
                const endIdx = Math.min(startIdx + questionsPerPage, questions.length);

                for (let qIdx = startIdx; qIdx < endIdx; qIdx++) {
                    const q = questions[qIdx];
                    if (!q) continue;
                    const serialNum = qIdx + 1;
                    const serialNumBn = formatBanglaNumber(serialNum);

                    const qCard = document.createElement('div');
                    qCard.className = 'print-q-card';

                    // Question Text
                    const qText = document.createElement('div');
                    qText.className = 'print-q-text';
                    qText.innerHTML = `<span style="font-weight: 700; margin-right: 5px;">${serialNumBn}.</span>${q.question || ''}`;
                    qCard.appendChild(qText);

                    // Options
                    if (q.question_type === 'mcq' && q.options) {
                        const qOptions = document.createElement('div');
                        qOptions.className = 'print-q-options';

                        const optionsData = [
                            { prefix: 'ক.', text: q.options.option_a, key: '1' },
                            { prefix: 'খ.', text: q.options.option_b, key: '2' },
                            { prefix: 'গ.', text: q.options.option_c, key: '3' },
                            { prefix: 'ঘ.', text: q.options.option_d, key: '4' }
                        ];

                        optionsData.forEach(opt => {
                            const optDiv = document.createElement('div');
                            optDiv.className = 'print-q-option';
                            
                            const bubble = document.createElement('span');
                            bubble.className = `print-option-bubble ${q.correct_answer === opt.key ? 'filled' : ''}`;
                            
                            const textSpan = document.createElement('span');
                            textSpan.innerHTML = `<strong>${opt.prefix}</strong> ${opt.text || ''}`;

                            optDiv.appendChild(bubble);
                            optDiv.appendChild(textSpan);
                            qOptions.appendChild(optDiv);
                        });

                        qCard.appendChild(qOptions);
                    }

                    columnsDiv.appendChild(qCard);
                }

                pageDiv.appendChild(columnsDiv);
                printContainer.appendChild(pageDiv);
            }
        } catch (printErr) {
            console.error("Error in updatePrintTemplate:", printErr);
        }
    }

    function incrementViewCount(qId, element) {
        fetch(`/academy/question/${qId}/view`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json'
            }
        })
        .then(res => res.json())
        .then(data => {
            if (data.status) {
                const countSpan = element.querySelector('.q-view-count');
                if (countSpan) {
                    countSpan.innerText = formatBanglaNumber(data.new_view);
                }
                // Update the memory cache as well
                if (window.lastFetchedQuestions) {
                    const qIdx = window.lastFetchedQuestions.findIndex(x => x.id === qId);
                    if (qIdx !== -1) {
                        window.lastFetchedQuestions[qIdx].view = data.new_view;
                    }
                }
            }
        })
        .catch(err => console.error("Error updating view count:", err));
    }

    function printAllQuestions() {
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

        // Gather Chapter IDs
        const chapterIds = [];
        document.querySelectorAll("input[name='chapter_ids[]']:checked").forEach(checkbox => {
            chapterIds.push(checkbox.value);
        });

        // Gather Types
        const types = [];
        document.querySelectorAll("input[name='types[]']:checked").forEach(checkbox => {
            types.push(checkbox.value);
        });

        const searchQuery = document.getElementById('search-query').value;

        // If selection is incomplete (must check at least one Class, one Subject, and one Type)
        if (classIds.length === 0 || subjectIds.length === 0 || types.length === 0) {
            toastr.warning("অনুগ্রহ করে কমপক্ষে একটি শ্রেণি, একটি বিষয় এবং একটি প্রশ্নের ধরন নির্বাচন করুন।");
            return;
        }

        // Show a loader/toast
        const toastId = toastr.info("প্রিন্ট ফাইল প্রস্তুত হচ্ছে, অনুগ্রহ করে অপেক্ষা করুন...", "", { timeOut: 0, extendedTimeOut: 0 });

        // AJAX Query for all questions (print_all=1)
        const params = new URLSearchParams();
        classIds.forEach(id => params.append('class_ids[]', id));
        subjectIds.forEach(id => params.append('subject_ids[]', id));
        chapterIds.forEach(id => params.append('chapter_ids[]', id));
        types.forEach(type => params.append('types[]', type));
        if (searchQuery) params.append('search', searchQuery);
        params.append('print_all', '1');

        fetch("{{ route('academy.filter') }}?" + params.toString())
            .then(res => res.json())
            .then(data => {
                toastr.clear(toastId);
                if (data.questions && data.questions.length > 0) {
                    updatePrintTemplate(data.questions);
                    // Slight delay to ensure DOM is updated before print modal opens
                    setTimeout(() => {
                        window.print();
                        // Restore original page content print template representation
                        if (window.lastFetchedQuestions) {
                            updatePrintTemplate(window.lastFetchedQuestions);
                        }
                    }, 500);
                } else {
                    toastr.warning("প্রিন্ট করার মতো কোনো প্রশ্ন পাওয়া যায়নি।");
                }
            })
            .catch(err => {
                console.error("Error preparing print questions", err);
                toastr.clear(toastId);
                toastr.error("প্রিন্ট ফাইল প্রস্তুত করতে সমস্যা হয়েছে।");
            });
    }
</script>
@endpush
