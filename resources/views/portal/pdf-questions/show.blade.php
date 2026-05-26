@extends('layouts.admin', ['title' => 'PDF বিস্তারিত ও AI জেনারেট'])

@section('content')
<div class="app-main flex-column flex-row-fluid" id="app_main">
    <div class="d-flex flex-column flex-column-fluid">
        <div id="app_toolbar" class="app-toolbar py-3 py-lg-6">
            <div id="app_toolbar_container" class="app-container container-xxl d-flex flex-stack">
                <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
                    <h1 class="page-heading d-flex text-gray-900 fw-bold fs-3 flex-column justify-content-center my-0">
                        🤖 AI প্রশ্ন তৈরি করুন
                    </h1>
                </div>
                <a href="{{ route('admin.pdf.index') }}" class="btn btn-sm btn-light">
                    <i class="bi bi-arrow-left me-1"></i> পেছনে যান
                </a>
            </div>
        </div>

        <div id="app_content" class="app-content flex-column-fluid">
            <div id="app_content_container" class="app-container container-xxl">
                <div class="row g-5">

                    {{-- Left: PDF Info --}}
                    <div class="col-lg-4">
                        <div class="card shadow-sm">
                            <div class="card-body p-6">
                                <div class="text-center mb-4">
                                    <i class="bi bi-file-earmark-pdf text-danger" style="font-size: 60px;"></i>
                                    <h5 class="fw-bold mt-3">{{ $pdf->title }}</h5>
                                    <span class="badge bg-{{ $pdf->status_color }} fs-7">{{ $pdf->status_label }}</span>
                                </div>

                                <table class="table table-borderless table-sm">
                                    <tr><td class="text-muted">ক্যাটাগরি</td><td class="fw-semibold">{{ $pdf->category->name ?? '—' }}</td></tr>
                                    <tr><td class="text-muted">ফাইল</td><td class="fw-semibold small">{{ $pdf->original_name }}</td></tr>
                                    <tr><td class="text-muted">তৈরির তারিখ</td><td class="fw-semibold">{{ $pdf->created_at->format('d M, Y') }}</td></tr>
                                    <tr><td class="text-muted">প্রশ্ন তৈরি</td><td class="fw-bold text-info">{{ $pdf->questions_generated }}</td></tr>
                                    <tr><td class="text-muted">প্রশ্ন সেভ</td><td class="fw-bold text-success">{{ $pdf->questions_saved }}</td></tr>
                                </table>

                                @if($pdf->error_message)
                                    <div class="alert alert-danger small mt-3">
                                        <i class="bi bi-exclamation-triangle me-1"></i>
                                        {{ $pdf->error_message }}
                                    </div>
                                @endif

                                @if($pdf->generated_questions)
                                    <a href="{{ route('admin.pdf.preview', $pdf->id) }}" class="btn btn-success w-100 mt-3">
                                        <i class="bi bi-list-check me-2"></i> প্রশ্নগুলো দেখুন ও সেভ করুন
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>

                    {{-- Right: Steps --}}
                    <div class="col-lg-8">

                        {{-- Step 1: Extract Text --}}
                        <div class="card shadow-sm mb-5">
                            <div class="card-header border-0 pt-5">
                                <h3 class="card-title fw-bold">
                                    <span class="badge bg-primary me-2">ধাপ ১</span>
                                    PDF থেকে টেক্সট বের করুন
                                </h3>
                            </div>
                            <div class="card-body">
                                @if($pdf->extracted_text)
                                    <div class="alert alert-success mb-4">
                                        <i class="bi bi-check-circle me-2"></i>
                                        টেক্সট সফলভাবে বের হয়েছে। {{ number_format(strlen($pdf->extracted_text)) }} অক্ষর পাওয়া গেছে।
                                    </div>
                                    <div class="border rounded p-3 bg-light" style="max-height: 150px; overflow-y: auto; font-size: 13px; color: #555;">
                                        {{ Str::limit($pdf->extracted_text, 400) }}
                                    </div>
                                    <button class="btn btn-outline-secondary btn-sm mt-3" id="re-extract-btn">
                                        <i class="bi bi-arrow-repeat me-1"></i> পুনরায় এক্সট্রাক্ট করুন
                                    </button>
                                @else
                                    <p class="text-muted mb-4">PDF থেকে টেক্সট বের করতে নিচের বাটনে ক্লিক করুন।</p>
                                    <button class="btn btn-primary" id="extract-btn">
                                        <i class="bi bi-file-text me-2"></i> টেক্সট এক্সট্রাক্ট করুন
                                    </button>
                                @endif
                                <div id="extract-status" class="mt-3 d-none"></div>
                            </div>
                        </div>

                        {{-- Step 2: Generate Questions --}}
                        <div class="card shadow-sm">
                            <div class="card-header border-0 pt-5">
                                <h3 class="card-title fw-bold">
                                    <span class="badge bg-warning text-dark me-2">ধাপ ২</span>
                                    AI দিয়ে প্রশ্ন তৈরি করুন
                                    <span class="badge bg-info ms-2 small">Google Gemini</span>
                                </h3>
                            </div>
                            <div class="card-body">
                                @if(!$pdf->extracted_text)
                                    <div class="alert alert-warning">
                                        <i class="bi bi-exclamation-triangle me-2"></i>
                                        আগে ধাপ ১ সম্পন্ন করুন।
                                    </div>
                                @else
                                    <div class="row g-4 mb-5">
                                        <div class="col-md-4">
                                            <label class="form-label fw-semibold">MCQ প্রশ্ন সংখ্যা</label>
                                            <input type="number" id="mcq_count" class="form-control" value="20" min="5" max="50">
                                            <div class="form-text">৫ থেকে ৫০ পর্যন্ত</div>
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label fw-semibold">Short Question সংখ্যা</label>
                                            <input type="number" id="short_count" class="form-control" value="5" min="0" max="20">
                                            <div class="form-text">০ থেকে ২০ পর্যন্ত</div>
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label fw-semibold">ভাষা</label>
                                            <select id="language" class="form-select">
                                                <option value="bangla">বাংলা</option>
                                                <option value="english">English</option>
                                            </select>
                                        </div>
                                    </div>

                                    <button class="btn btn-warning fw-bold btn-lg" id="generate-btn">
                                        <i class="bi bi-stars me-2"></i> AI দিয়ে প্রশ্ন তৈরি করুন
                                    </button>
                                    <div class="form-text mt-2">এটি ৩০-৬০ সেকেন্ড সময় নিতে পারে।</div>
                                    <div id="generate-status" class="mt-3 d-none"></div>
                                @endif
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
const pdfId = {{ $pdf->id }};

// Extract Text
document.getElementById('extract-btn')?.addEventListener('click', doExtract);
document.getElementById('re-extract-btn')?.addEventListener('click', doExtract);

function doExtract() {
    const btn    = document.getElementById('extract-btn') || document.getElementById('re-extract-btn');
    const status = document.getElementById('extract-status');
    btn.disabled = true;
    btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span> এক্সট্রাক্ট হচ্ছে...';
    status.classList.remove('d-none');
    status.innerHTML = '<div class="alert alert-info"><i class="bi bi-hourglass-split me-2"></i> PDF থেকে টেক্সট বের করা হচ্ছে...</div>';

    fetch(`/portal/pdf-questions/${pdfId}/extract`, {
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Content-Type': 'application/json' }
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            status.innerHTML = `<div class="alert alert-success"><i class="bi bi-check-circle me-2"></i>${data.message}<br><small class="text-muted mt-1 d-block">Preview: ${data.preview}...</small></div>`;
            setTimeout(() => location.reload(), 2000);
        } else {
            status.innerHTML = `<div class="alert alert-danger"><i class="bi bi-x-circle me-2"></i>${data.message}</div>`;
            btn.disabled = false;
            btn.innerHTML = '<i class="bi bi-file-text me-2"></i> টেক্সট এক্সট্রাক্ট করুন';
        }
    })
    .catch(() => {
        status.innerHTML = '<div class="alert alert-danger">সার্ভার ত্রুটি। আবার চেষ্টা করুন।</div>';
        btn.disabled = false;
    });
}

// Generate Questions
document.getElementById('generate-btn')?.addEventListener('click', function() {
    const btn    = this;
    const status = document.getElementById('generate-status');
    btn.disabled = true;
    btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span> Gemini AI প্রশ্ন তৈরি করছে... (৩০-৬০ সেকেন্ড)';
    status.classList.remove('d-none');
    status.innerHTML = `<div class="alert alert-info">
        <i class="bi bi-cpu me-2"></i> Google Gemini AI কাজ করছে...
        <div class="progress mt-2" style="height:6px;"><div class="progress-bar progress-bar-striped progress-bar-animated w-100"></div></div>
    </div>`;

    fetch(`/portal/pdf-questions/${pdfId}/generate`, {
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Content-Type': 'application/json' },
        body: JSON.stringify({
            mcq_count:   document.getElementById('mcq_count').value,
            short_count: document.getElementById('short_count').value,
            language:    document.getElementById('language').value,
        })
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            status.innerHTML = `<div class="alert alert-success"><i class="bi bi-stars me-2"></i>${data.message} — Preview পেজে নিয়ে যাওয়া হচ্ছে...</div>`;
            setTimeout(() => window.location.href = data.redirect, 1500);
        } else {
            status.innerHTML = `<div class="alert alert-danger"><i class="bi bi-x-circle me-2"></i>${data.message}</div>`;
            btn.disabled = false;
            btn.innerHTML = '<i class="bi bi-stars me-2"></i> AI দিয়ে প্রশ্ন তৈরি করুন';
        }
    })
    .catch(() => {
        status.innerHTML = '<div class="alert alert-danger">সার্ভার ত্রুটি। আবার চেষ্টা করুন।</div>';
        btn.disabled = false;
        btn.innerHTML = '<i class="bi bi-stars me-2"></i> AI দিয়ে প্রশ্ন তৈরি করুন';
    });
});
</script>
@endpush
