@extends('layouts.admin', ['title' => 'PDF বিস্তারিত ও AI জেনারেট'])

@push('style')
<style>
    /* CSS Variables for Premium Aesthetics */
    :root {
        --glass-bg: rgba(255, 255, 255, 0.7);
        --glass-border: rgba(226, 232, 240, 0.8);
        --primary-gradient: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);
        --accent-gradient: linear-gradient(135deg, #10b981 0%, #059669 100%);
        --dark-card: #0f172a;
    }

    /* Layout Styling */
    .dashboard-container {
        display: flex;
        gap: 20px;
        min-height: calc(100vh - 160px);
    }

    /* Left Sidebar: Page List */
    .sidebar-pane {
        width: 22%;
        background: var(--glass-bg);
        border: 1px solid var(--glass-border);
        border-radius: 16px;
        padding: 20px 12px;
        backdrop-filter: blur(12px);
        max-height: calc(100vh - 180px);
        overflow-y: auto;
    }

    .page-item-btn {
        width: 100%;
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 12px 16px;
        margin-bottom: 8px;
        background: transparent;
        border: 1px solid transparent;
        border-radius: 12px;
        text-align: left;
        color: #334155;
        font-weight: 600;
        transition: all 0.2s ease;
    }

    .page-item-btn:hover {
        background: rgba(79, 70, 229, 0.05);
        color: #4f46e5;
    }

    .page-item-btn.active {
        background: var(--primary-gradient);
        color: #ffffff !important;
        box-shadow: 0 4px 14px rgba(79, 70, 229, 0.35);
    }

    .page-badge-status {
        font-size: 10px;
        padding: 2px 8px;
        border-radius: 100px;
        font-weight: bold;
    }

    /* Middle Pane: Viewer & Previewer */
    .viewer-pane {
        flex: 1;
        background: #ffffff;
        border: 1px solid var(--glass-border);
        border-radius: 16px;
        display: flex;
        flex-direction: column;
        backdrop-filter: blur(12px);
        max-height: calc(100vh - 180px);
    }

    .viewer-header {
        padding: 16px 24px;
        border-bottom: 1px solid var(--glass-border);
        display: flex;
        align-items: center;
        justify-content: space-between;
        background: #f8fafc;
        border-radius: 16px 16px 0 0;
    }

    .viewer-tabs {
        display: flex;
        gap: 8px;
    }

    .viewer-tab-btn {
        background: transparent;
        border: none;
        padding: 8px 20px;
        font-weight: 700;
        color: #64748b;
        border-radius: 8px;
        transition: all 0.2s;
    }

    .viewer-tab-btn.active {
        background: rgba(79, 70, 229, 0.08);
        color: #4f46e5;
    }

    .viewer-content {
        flex: 1;
        padding: 24px;
        overflow-y: auto;
        font-size: 15px;
        line-height: 1.8;
        color: #334155;
    }

    .text-reader {
        white-space: pre-wrap;
        background: #f8fafc;
        padding: 24px;
        border-radius: 12px;
        border: 1px solid #e2e8f0;
        font-family: 'Inter', sans-serif;
    }

    /* Right Pane: AI Config */
    .config-pane {
        width: 28%;
        background: var(--glass-bg);
        border: 1px solid var(--glass-border);
        border-radius: 16px;
        padding: 24px;
        backdrop-filter: blur(12px);
        max-height: calc(100vh - 180px);
        overflow-y: auto;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
    }

    /* Question Card Styling */
    .editable-q-card {
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        padding: 18px;
        margin-bottom: 16px;
        background: #f8fafc;
        transition: border-color 0.2s;
    }

    .editable-q-card:hover {
        border-color: #cbd5e1;
    }

    .editable-q-card.selected {
        border-color: #10b981;
        background: rgba(16, 185, 129, 0.02);
    }

    /* Elegant Custom Scrollbars */
    .sidebar-pane::-webkit-scrollbar,
    .viewer-content::-webkit-scrollbar,
    .config-pane::-webkit-scrollbar {
        width: 6px;
    }
    .sidebar-pane::-webkit-scrollbar-thumb,
    .viewer-content::-webkit-scrollbar-thumb,
    .config-pane::-webkit-scrollbar-thumb {
        background: #cbd5e1;
        border-radius: 4px;
    }

    /* Animated CSS Spinner */
    .ai-spinner-container {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        padding: 60px 20px;
    }

    .ai-glowing-ring {
        width: 64px;
        height: 64px;
        border-radius: 50%;
        border: 4px solid rgba(124, 58, 237, 0.1);
        border-top-color: #7c3aed;
        animation: spin 1s linear infinite;
        margin-bottom: 20px;
        box-shadow: 0 0 15px rgba(124, 58, 237, 0.2);
    }

    @keyframes spin {
        to { transform: rotate(360deg); }
    }
</style>
@endpush

@section('content')
<div class="app-main flex-column flex-row-fluid" id="app_main">
    <div class="d-flex flex-column flex-column-fluid">

        {{-- Top Toolbar --}}
        <div id="app_toolbar" class="app-toolbar py-3 py-lg-6">
            <div id="app_toolbar_container" class="app-container container-xxl d-flex flex-stack">
                <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
                    <h1 class="page-heading d-flex text-gray-900 fw-bold fs-3 flex-column justify-content-center my-0">
                        📄 {{ $pdf->title }} — পেজ-ভিত্তিক AI প্রশ্ন জেনারেটর
                    </h1>
                    <div class="text-muted small mt-1">
                        ক্যাটাগরি: <strong class="text-dark">{{ $pdf->category->name ?? '—' }}</strong> · 
                        মোট সেভ করা প্রশ্ন: <span class="badge bg-success text-white" id="global-saved-count">{{ $pdf->questions_saved }}</span>
                    </div>
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('portal.pdf.index') }}" class="btn btn-sm btn-light-primary fw-bold">
                        <i class="bi bi-arrow-left me-1"></i> তালিকা
                    </a>
                </div>
            </div>
        </div>

        {{-- Main Dashboard Layout --}}
        <div id="app_content" class="app-content flex-column-fluid">
            <div id="app_content_container" class="app-container container-xxl">

                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show mb-4">
                        <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @if(!$pdf->extracted_text)
                    {{-- Text Extraction Pending View --}}
                    <div class="card shadow-sm border-0 py-10" style="background: radial-gradient(circle at top right, #eef2ff, #ffffff);">
                        <div class="card-body text-center p-10">
                            <i class="bi bi-file-earmark-pdf-fill text-danger mb-4" style="font-size: 80px; filter: drop-shadow(0 8px 16px rgba(239, 68, 68, 0.15));"></i>
                            <h3 class="fw-bolder text-gray-900 mb-2">PDF টেক্সট প্রসেসিং প্রয়োজন</h3>
                            <p class="text-muted fs-6 max-width-500 mx-auto mb-8">
                                AI দিয়ে প্রশ্ন জেনারেট করতে প্রথমে আমাদের হাই-স্পিড PDF টেক্সট এক্সট্রাক্টর দিয়ে পৃষ্ঠাগুলো স্ক্যান ও প্রসেস করে নেওয়া আবশ্যক।
                            </p>
                            <button class="btn btn-primary btn-lg px-8 fw-bold" id="main-extract-btn" style="box-shadow: 0 4px 14px rgba(79, 70, 229, 0.3);">
                                <i class="bi bi-cpu-fill me-2"></i> স্ক্যান ও প্রসেস শুরু করুন
                            </button>
                            <div id="extract-loader" class="mt-8 d-none">
                                <div class="ai-spinner-container">
                                    <div class="ai-glowing-ring"></div>
                                    <h5 class="fw-bold text-gray-800" id="extract-loader-text">পৃষ্ঠাগুলো স্ক্যান করে টেক্সট প্রসেস করা হচ্ছে...</h5>
                                    <p class="text-muted small mt-1">এটি আপনার ফাইলের সাইজের ওপর ভিত্তি করে কয়েক সেকেন্ড সময় নিতে পারে।</p>
                                </div>
                            </div>
                        </div>
                    </div>
                @else
                    {{-- SPA Interactive Dashboard --}}
                    <div class="dashboard-container">

                        {{-- 1. Left Sidebar Pane (Pages Selector) --}}
                        <div class="sidebar-pane">
                            <h5 class="fw-bold px-3 mb-4 text-gray-800 d-flex align-items-center justify-content-between">
                                <span>📖 পৃষ্ঠাসমূহ</span>
                                <span class="badge bg-light-primary text-primary" id="total-pages-badge">০</span>
                            </h5>
                            <div id="pages-list-container">
                                {{-- Dynamically populated via JS --}}
                            </div>
                        </div>

                        {{-- 2. Middle Pane (Page Content & AI Questions Preview) --}}
                        <div class="viewer-pane">
                            <div class="viewer-header">
                                <h5 class="fw-bold text-gray-800 mb-0 d-flex align-items-center gap-2">
                                    <span class="text-primary" id="active-page-label">পৃষ্ঠা ১</span>
                                </h5>
                                <div class="viewer-tabs">
                                    <button class="viewer-tab-btn active" id="tab-text-btn" onclick="switchTab('text')">
                                        <i class="bi bi-file-text me-1"></i> টেক্সট রিডার
                                    </button>
                                    <button class="viewer-tab-btn" id="tab-questions-btn" onclick="switchTab('questions')">
                                        <i class="bi bi-stars me-1"></i> AI প্রশ্নসমূহ
                                        <span class="badge bg-danger text-white ms-1 d-none" id="tab-q-count">০</span>
                                    </button>
                                </div>
                            </div>

                            <div class="viewer-content">
                                {{-- Tab A: Extracted Text --}}
                                <div id="tab-text-content">
                                    @php
                                        $isImage = in_array(strtolower(pathinfo($pdf->file_path, PATHINFO_EXTENSION)), ['jpg', 'jpeg', 'png', 'webp']);
                                    @endphp
                                    
                                    @if($isImage)
                                        <div class="text-center mb-6 bg-light p-4 rounded border">
                                            <img src="{{ asset('storage/' . $pdf->file_path) }}" class="img-fluid rounded shadow-sm" style="max-height: 400px; object-fit: contain; border: 1px solid #e2e8f0;">
                                            <div class="form-text mt-2"><i class="bi bi-image me-1"></i> আপলোড করা মূল ছবি</div>
                                        </div>
                                    @endif

                                    <div class="text-reader" id="active-page-text">
                                        টেক্সট লোড হচ্ছে...
                                    </div>
                                </div>

                                {{-- Tab B: AI Generated Questions View & Form --}}
                                <div id="tab-questions-content" class="d-none">
                                    <div id="no-questions-placeholder" class="text-center py-10">
                                        <i class="bi bi-stars fs-1 text-muted d-block mb-3"></i>
                                        <h6 class="text-muted">এই পৃষ্ঠার জন্য এখনো কোনো প্রশ্ন তৈরি করা হয়নি।</h6>
                                        <p class="text-muted small">ডান পাশের প্যানেল থেকে পছন্দমত প্রশ্ন টাইপ সিলেক্ট করে জেনারেট বাটনে ক্লিক করুন।</p>
                                    </div>

                                    <div id="questions-form-container" class="d-none">
                                        <div class="d-flex align-items-center justify-content-between mb-4 pb-3 border-bottom">
                                            <div class="d-flex align-items-center gap-3">
                                                <label class="d-flex align-items-center gap-2 cursor-pointer">
                                                    <input type="checkbox" id="select-all-q" class="form-check-input" checked onchange="toggleSelectAllQ(this)">
                                                    <span class="fw-bold text-gray-700">সব সিলেক্ট করুন</span>
                                                </label>
                                                <span class="text-muted">|</span>
                                                <span class="text-muted small"><strong class="text-primary" id="selected-q-count">0</strong>টি প্রশ্ন সিলেক্ট করা হয়েছে</span>
                                            </div>
                                        </div>

                                        <form action="{{ route('portal.pdf.save', $pdf->id) }}" method="POST" id="questions-save-form">
                                            @csrf
                                            <input type="hidden" name="page" id="form-active-page-val">
                                            <div id="questions-list-wrapper">
                                                {{-- Dynamically populated --}}
                                            </div>

                                            <div class="d-flex justify-content-end gap-3 mt-6">
                                                <button type="submit" class="btn btn-success fw-bold px-8">
                                                    <i class="bi bi-floppy-fill me-2"></i> নির্বাচিত প্রশ্ন সেভ করুন
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- 3. Right Pane (AI Operations Panel) --}}
                        <div class="config-pane">
                            <div>
                                <h5 class="fw-bold mb-5 text-gray-800 d-flex align-items-center gap-2">
                                    <i class="bi bi-cpu text-primary"></i>
                                    <span>AI প্রশ্ন প্যানেল</span>
                                </h5>

                                <div class="mb-5">
                                    <label class="form-label fw-bold text-gray-700">প্রশ্ন জেনারেটরের ভাষা</label>
                                    <select id="ai-language" class="form-select">
                                        <option value="bangla" selected>বাংলা (Bangla)</option>
                                        <option value="english">ইংরেজি (English)</option>
                                    </select>
                                </div>

                                <div class="mb-5">
                                    <label class="form-label fw-bold text-gray-700 d-flex justify-content-between">
                                        <span>MCQ প্রশ্ন সংখ্যা</span>
                                        <strong class="text-primary" id="mcq-val-label">১০টি</strong>
                                    </label>
                                    <input type="range" id="ai-mcq-count" class="form-range" min="0" max="30" value="10" oninput="updateRangeLabel('mcq', this.value)">
                                    <div class="d-flex justify-content-between text-muted small mt-1"><span>০</span><span>১৫</span><span>৩০</span></div>
                                </div>

                                <div class="mb-6">
                                    <label class="form-label fw-bold text-gray-700 d-flex justify-content-between">
                                        <span>Short Question সংখ্যা</span>
                                        <strong class="text-primary" id="short-val-label">৫টি</strong>
                                    </label>
                                    <input type="range" id="ai-short-count" class="form-range" min="0" max="20" value="5" oninput="updateRangeLabel('short', this.value)">
                                    <div class="d-flex justify-content-between text-muted small mt-1"><span>০</span><span>১০</span><span>২০</span></div>
                                </div>
                            </div>

                            <div>
                                <button type="button" class="btn btn-primary w-100 py-3 fw-bold fs-6" id="generate-btn" onclick="generateQuestionsForActivePage()" style="box-shadow: 0 4px 12px rgba(79, 70, 229, 0.25);">
                                    <i class="bi bi-stars me-2"></i> প্রশ্ন জেনারেট করুন
                                </button>
                                <p class="text-muted small text-center mt-2 mb-0"><i class="bi bi-lightning-fill text-warning me-1"></i> Gemini AI ২০-৪০ সেকেন্ড সময় নিতে পারে</p>
                            </div>
                        </div>

                    </div>
                @endif

            </div>
        </div>
    </div>
</div>

{{-- Dynamic Generating Loading Modal --}}
<div class="modal fade" id="generatingModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body text-center p-8">
                <div class="ai-spinner-container">
                    <div class="ai-glowing-ring"></div>
                    <h4 class="fw-bolder text-gray-800">Gemini AI প্রশ্ন তৈরি করছে...</h4>
                    <p class="text-muted small mt-1">সিলেক্ট করা পৃষ্ঠার টেক্সট থেকে সঠিক উত্তর ও ব্যাখ্যাসহ প্রশ্ন জেনারেট হচ্ছে।</p>
                    <div class="progress w-100 mt-4" style="height: 6px;">
                        <div class="progress-bar progress-bar-striped progress-bar-animated w-100 bg-primary"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Localized SPA Data State
    let pages = @json(json_decode($pdf->extracted_text, true) ?? []);
    let generatedQuestions = @json($pdf->generated_questions ?? new \stdClass());
    let activePage = 1;
    let activeTab = 'text';

    document.addEventListener('DOMContentLoaded', () => {
        if (pages.length > 0) {
            initDashboard();
        }

        // Text Extraction Trigger
        document.getElementById('main-extract-btn')?.addEventListener('click', function() {
            const btn = this;
            const loader = document.getElementById('extract-loader');
            btn.classList.add('d-none');
            loader.classList.remove('d-none');

            fetch(`/portal/pdf-questions/{{ $pdf->id }}/extract`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json'
                }
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    Swal.fire({ icon: 'success', title: 'সাফল্য!', text: data.message, showConfirmButton: false, timer: 2000 });
                    setTimeout(() => location.reload(), 1500);
                } else {
                    Swal.fire({ icon: 'error', title: 'ব্যর্থতা', text: data.message });
                    btn.classList.remove('d-none');
                    loader.classList.add('d-none');
                }
            })
            .catch(err => {
                Swal.fire({ icon: 'error', title: 'সার্ভার ত্রুটি', text: 'অনুগ্রহ করে আবার চেষ্টা করুন।' });
                btn.classList.remove('d-none');
                loader.classList.add('d-none');
            });
        });
    });

    // Initialize SPA Views
    function initDashboard() {
        document.getElementById('total-pages-badge').textContent = pages.length;
        renderPagesList();
        setActivePage(1);
    }

    // Render Page Selectors list on Left Panel
    function renderPagesList() {
        const container = document.getElementById('pages-list-container');
        container.innerHTML = '';

        pages.forEach(p => {
            const hasQuestions = generatedQuestions && generatedQuestions[p.page] && generatedQuestions[p.page].length > 0;
            const badgeHtml = hasQuestions 
                ? `<span class="page-badge-status bg-light-success text-success"><i class="bi bi-stars"></i> AI (${generatedQuestions[p.page].length})</span>`
                : `<span class="page-badge-status bg-light text-muted">ফাঁকা</span>`;

            const btn = document.createElement('button');
            btn.type = 'button';
            btn.className = `page-item-btn ${p.page === activePage ? 'active' : ''}`;
            btn.id = `page-btn-${p.page}`;
            btn.onclick = () => setActivePage(p.page);
            btn.innerHTML = `
                <span>📄 পৃষ্ঠা ${toBanglaNumber(p.page)}</span>
                ${badgeHtml}
            `;
            container.appendChild(btn);
        });
    }

    // Set Active Page
    function setActivePage(pageNumber) {
        // Remove active class from previous
        const prevBtn = document.getElementById(`page-btn-${activePage}`);
        if (prevBtn) prevBtn.classList.remove('active');

        activePage = pageNumber;

        // Set active class on new
        const activeBtn = document.getElementById(`page-btn-${activePage}`);
        if (activeBtn) activeBtn.classList.add('active');

        // Update active page label
        document.getElementById('active-page-label').textContent = `পৃষ্ঠা ${toBanglaNumber(activePage)}`;
        document.getElementById('generate-btn').innerHTML = `<i class="bi bi-stars me-2"></i> পৃষ্ঠা ${toBanglaNumber(activePage)} এর প্রশ্ন তৈরি করুন`;
        document.getElementById('form-active-page-val').value = activePage;

        // Load content
        const pageData = pages.find(p => p.page === activePage);
        const textContainer = document.getElementById('active-page-text');
        textContainer.textContent = pageData && pageData.text ? pageData.text : 'এই পৃষ্ঠায় কোনো টেক্সট পাওয়া যায়নি।';

        // Load Questions for Page
        loadQuestionsForActivePage();
        
        // Always reset tab to text on page change to avoid jarring switches
        switchTab('text');
    }

    // Load page questions inside preview Form
    function loadQuestionsForActivePage() {
        const questions = generatedQuestions && generatedQuestions[activePage] ? generatedQuestions[activePage] : [];
        const tabQCount = document.getElementById('tab-q-count');
        const placeholder = document.getElementById('no-questions-placeholder');
        const formContainer = document.getElementById('questions-form-container');
        const listWrapper = document.getElementById('questions-list-wrapper');

        if (questions.length > 0) {
            tabQCount.textContent = questions.length;
            tabQCount.classList.remove('d-none');
            placeholder.classList.add('d-none');
            formContainer.classList.remove('d-none');

            // Render Questions List
            listWrapper.innerHTML = '';
            questions.forEach((q, idx) => {
                const card = document.createElement('div');
                card.className = 'editable-q-card selected';
                card.id = `q-card-${idx}`;

                let optionsHtml = '';
                if (q.type === 'mcq') {
                    optionsHtml = `
                        <div class="row g-3 mb-4">
                            ${['a', 'b', 'c', 'd'].map(key => {
                                const labels = { a: 'ক', b: 'খ', c: 'গ', d: 'ঘ' };
                                const isCorrect = q.correct_answer === key;
                                return `
                                    <div class="col-md-6">
                                        <div class="input-group">
                                            <span class="input-group-text fw-bold ${isCorrect ? 'bg-success text-white' : ''}" style="min-width:40px;">${labels[key]}</span>
                                            <input type="text" name="questions[${idx}][options][${key}]" class="form-control ${isCorrect ? 'border-success' : ''}" value="${escapeHtml(q.options[key] || '')}">
                                        </div>
                                    </div>
                                `;
                            }).join('')}
                        </div>
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label class="form-label small fw-bold text-success">✅ সঠিক উত্তর</label>
                                <select name="questions[${idx}][correct_answer]" class="form-select form-select-sm">
                                    <option value="a" ${q.correct_answer === 'a' ? 'selected' : ''}>ক</option>
                                    <option value="b" ${q.correct_answer === 'b' ? 'selected' : ''}>খ</option>
                                    <option value="c" ${q.correct_answer === 'c' ? 'selected' : ''}>গ</option>
                                    <option value="d" ${q.correct_answer === 'd' ? 'selected' : ''}>ঘ</option>
                                </select>
                            </div>
                            <div class="col-md-8">
                                <label class="form-label small fw-bold text-muted">ব্যাখ্যা</label>
                                <input type="text" name="questions[${idx}][explanation]" class="form-control form-control-sm" value="${escapeHtml(q.explanation || '')}">
                            </div>
                        </div>
                    `;
                } else {
                    optionsHtml = `
                        <div class="row g-3">
                            <div class="col-md-8">
                                <label class="form-label small fw-bold text-success">✅ সঠিক উত্তর</label>
                                <textarea name="questions[${idx}][correct_answer]" class="form-control form-control-sm" rows="2">${escapeHtml(q.correct_answer || '')}</textarea>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label small fw-bold text-muted">ব্যাখ্যা (ঐচ্ছিক)</label>
                                <input type="text" name="questions[${idx}][explanation]" class="form-control form-control-sm" value="${escapeHtml(q.explanation || '')}">
                            </div>
                        </div>
                    `;
                }

                card.innerHTML = `
                    <div class="d-flex align-items-start gap-4">
                        <div class="pt-1">
                            <input type="checkbox" checked class="form-check-input q-select-cb" data-index="${idx}" onchange="toggleQSelection(this, ${idx})" style="width:20px;height:20px;">
                        </div>
                        <div class="flex-grow-1">
                            <div class="d-flex align-items-center gap-3 mb-3">
                                <span class="badge ${q.type === 'mcq' ? 'bg-success' : 'bg-info'} fs-8">${q.type === 'mcq' ? 'MCQ' : 'Short Q'}</span>
                                <span class="text-muted small">প্রশ্ন #${idx + 1}</span>
                            </div>
                            <div class="mb-3">
                                <textarea name="questions[${idx}][question]" class="form-control fw-semibold" rows="2" required>${escapeHtml(q.question)}</textarea>
                                <input type="hidden" name="questions[${idx}][type]" value="${q.type}">
                            </div>
                            ${optionsHtml}
                        </div>
                        <button type="button" class="btn btn-icon btn-sm btn-light-danger" onclick="removeQuestion(${idx})">
                            <i class="bi bi-trash-fill"></i>
                        </button>
                    </div>
                `;
                listWrapper.appendChild(card);
            });

            updateSelectedQCount();
        } else {
            tabQCount.classList.add('d-none');
            placeholder.classList.remove('d-none');
            formContainer.classList.add('d-none');
        }
    }

    // Tab Switching Logic
    function switchTab(tab) {
        activeTab = tab;
        const textBtn = document.getElementById('tab-text-btn');
        const questionsBtn = document.getElementById('tab-questions-btn');
        const textContent = document.getElementById('tab-text-content');
        const questionsContent = document.getElementById('tab-questions-content');

        if (tab === 'text') {
            textBtn.classList.add('active');
            questionsBtn.classList.remove('active');
            textContent.classList.remove('d-none');
            questionsContent.classList.add('d-none');
        } else {
            textBtn.classList.remove('active');
            questionsBtn.classList.add('active');
            textContent.classList.add('d-none');
            questionsContent.classList.remove('d-none');
        }
    }

    // AJAX API Call for Gemini Generation
    function generateQuestionsForActivePage() {
        const mcqCount = document.getElementById('ai-mcq-count').value;
        const shortCount = document.getElementById('ai-short-count').value;
        const language = document.getElementById('ai-language').value;

        if (mcqCount == 0 && shortCount == 0) {
            Swal.fire({ icon: 'warning', title: 'সতর্কতা', text: 'কমপক্ষে ১টি প্রশ্নের সংখ্যা সিলেক্ট করুন।' });
            return;
        }

        const modal = new bootstrap.Modal(document.getElementById('generatingModal'));
        modal.show();

        fetch(`/portal/pdf-questions/{{ $pdf->id }}/generate`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                page: activePage,
                mcq_count: mcqCount,
                short_count: shortCount,
                language: language
            })
        })
        .then(res => res.json())
        .then(data => {
            modal.hide();
            if (data.success) {
                Swal.fire({ icon: 'success', title: 'সাফল্য!', text: data.message, showConfirmButton: false, timer: 1500 });
                
                // Save questions locally to SPA state
                if (!generatedQuestions) generatedQuestions = {};
                generatedQuestions[activePage] = data.questions;

                // Re-render
                renderPagesList();
                setActivePage(activePage);
                
                // Auto switch to questions tab so admin can review instantly
                switchTab('questions');
            } else {
                Swal.fire({ icon: 'error', title: 'ব্যর্থতা', text: data.message });
            }
        })
        .catch(err => {
            modal.hide();
            Swal.fire({ icon: 'error', title: 'সার্ভার ত্রুটি', text: 'প্রশ্ন জেনারেট করতে ব্যর্থ হয়েছে। আবার চেষ্টা করুন।' });
        });
    }

    // Toggle Checkboxes for Questions
    function toggleQSelection(cb, index) {
        const card = document.getElementById(`q-card-${index}`);
        if (cb.checked) {
            card.classList.add('selected');
            card.querySelectorAll('input, select, textarea').forEach(el => el.disabled = false);
        } else {
            card.classList.remove('selected');
            card.querySelectorAll('input, select, textarea').forEach(el => {
                if (el !== cb) el.disabled = true; // disable fields so they don't submit
            });
        }
        updateSelectedQCount();
    }

    function toggleSelectAllQ(cb) {
        document.querySelectorAll('.q-select-cb').forEach(box => {
            box.checked = cb.checked;
            toggleQSelection(box, box.getAttribute('data-index'));
        });
    }

    function updateSelectedQCount() {
        const checkedCount = document.querySelectorAll('.q-select-cb:checked').length;
        document.getElementById('selected-q-count').textContent = checkedCount;
        
        const selectAllCb = document.getElementById('select-all-q');
        if (selectAllCb) {
            const totalCount = document.querySelectorAll('.q-select-cb').length;
            selectAllCb.checked = (checkedCount === totalCount);
        }
    }

    // Remove single question locally from UI
    function removeQuestion(index) {
        if (confirm('এই প্রশ্নটি বাতিল করবেন?')) {
            const questions = generatedQuestions[activePage] || [];
            questions.splice(index, 1);
            generatedQuestions[activePage] = questions;
            
            // Re-render list
            renderPagesList();
            setActivePage(activePage);
            switchTab('questions');
        }
    }

    // Range input helpers
    function updateRangeLabel(type, val) {
        document.getElementById(`${type}-val-label`).textContent = `${toBanglaNumber(val)}টি`;
    }

    // Utilities
    function toBanglaNumber(num) {
        const en = ['0','1','2','3','4','5','6','7','8','9'];
        const bn = ['০','১','২','৩','৪','৫','৬','৭','৮','৯'];
        return String(num).replace(/[0-9]/g, match => bn[en.indexOf(match)]);
    }

    function escapeHtml(text) {
        if (!text) return '';
        return text
            .replace(/&/g, "&amp;")
            .replace(/</g, "&lt;")
            .replace(/>/g, "&gt;")
            .replace(/"/g, "&quot;")
            .replace(/'/g, "&#039;");
    }
</script>
@endpush
