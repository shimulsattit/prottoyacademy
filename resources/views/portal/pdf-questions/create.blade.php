@extends('layouts.admin', ['title' => 'PDF বা ইমেজ আপলোড করুন'])

@section('content')
<div class="app-main flex-column flex-row-fluid" id="app_main">
    <div class="d-flex flex-column flex-column-fluid">

        <div id="app_toolbar" class="app-toolbar py-3 py-lg-6">
            <div id="app_toolbar_container" class="app-container container-xxl d-flex flex-stack">
                <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
                    <h1 class="page-heading d-flex text-gray-900 fw-bold fs-3 flex-column justify-content-center my-0">
                        📄 নতুন PDF বা ইমেজ আপলোড
                    </h1>
                    <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">
                        <li class="breadcrumb-item text-muted">
                            <a href="{{ route('portal.dashboard') }}" class="text-muted text-hover-primary">Home</a>
                        </li>
                        <li class="breadcrumb-item"><span class="bullet bg-gray-500 w-5px h-2px"></span></li>
                        <li class="breadcrumb-item text-muted">
                            <a href="{{ route('portal.pdf.index') }}" class="text-muted text-hover-primary">ফাইল প্রশ্ন</a>
                        </li>
                        <li class="breadcrumb-item"><span class="bullet bg-gray-500 w-5px h-2px"></span></li>
                        <li class="breadcrumb-item text-muted">আপলোড</li>
                    </ul>
                </div>
            </div>
        </div>

        <div id="app_content" class="app-content flex-column-fluid">
            <div id="app_content_container" class="app-container container-xxl">
                <div class="row justify-content-center">
                    <div class="col-lg-8">

                        @if($errors->any())
                            <div class="alert alert-danger mb-4">
                                @foreach($errors->all() as $e)<div>{{ $e }}</div>@endforeach
                            </div>
                        @endif

                        <div class="card shadow-sm">
                            <div class="card-header border-0 pt-5">
                                <h3 class="card-title fw-bold">PDF তথ্য দিন</h3>
                            </div>
                            <div class="card-body">
                                <form action="{{ route('portal.pdf.store') }}" method="POST" enctype="multipart/form-data">
                                    @csrf

                                    <div class="mb-5">
                                        <label class="form-label fw-semibold required">PDF শিরোনাম</label>
                                        <input type="text" name="title" class="form-control form-control-lg"
                                               placeholder="যেমন: বাংলাদেশের ইতিহাস ও সংস্কৃতি"
                                               value="{{ old('title') }}" required>
                                        <div class="form-text">এই নামে প্রশ্নগুলো সংরক্ষিত হবে</div>
                                    </div>

                                    <div class="mb-5">
                                        <label class="form-label fw-semibold required">ক্যাটাগরি নির্বাচন করুন</label>
                                        <select name="category_id" class="form-select form-select-lg" required>
                                            <option value="">-- ক্যাটাগরি বেছে নিন --</option>
                                            @foreach($categories as $cat)
                                                <option value="{{ $cat->id }}" {{ old('category_id') == $cat->id ? 'selected' : '' }}>
                                                    {{ $cat->name }}
                                                    @if($cat->parent) ({{ $cat->parent->name }}) @endif
                                                </option>
                                            @endforeach
                                        </select>
                                        <div class="form-text">প্রশ্নগুলো এই ক্যাটাগরিতে যুক্ত হবে</div>
                                    </div>

                                    <div class="mb-7">
                                        <label class="form-label fw-semibold required">PDF / ইমেজ ফাইল বেছে নিন</label>
                                        <div id="drop-zone" class="border-2 border-dashed rounded-3 p-8 text-center"
                                             style="border-color: #009ef7; background: #f8f9fa; cursor: pointer; transition: all 0.3s;">
                                            <div id="drop-content">
                                                <i class="bi bi-cloud-upload fs-1 text-primary mb-3 d-block"></i>
                                                <p class="fw-semibold text-gray-700 mb-1">PDF অথবা ইমেজ ফাইল এখানে ড্র্যাগ করুন</p>
                                                <p class="text-muted small mb-3">অথবা</p>
                                                <label class="btn btn-primary btn-sm" for="pdf_file_input">
                                                    <i class="bi bi-folder2-open me-1"></i> ফাইল বেছে নিন
                                                </label>
                                                <p class="text-muted small mt-2">সর্বোচ্চ ২০ MB • PDF অথবা ইমেজ (JPG, PNG, WebP)</p>
                                            </div>
                                            <div id="file-preview" class="d-none">
                                                <i class="bi bi-file-earmark-pdf fs-1 text-danger mb-2 d-block"></i>
                                                <p id="file-name" class="fw-bold text-gray-800 mb-1"></p>
                                                <p id="file-size" class="text-muted small mb-2"></p>
                                                <button type="button" class="btn btn-sm btn-light-danger" id="remove-file">
                                                    <i class="bi bi-x-circle me-1"></i> পরিবর্তন করুন
                                                </button>
                                            </div>
                                        </div>
                                        <input type="file" name="pdf_file" id="pdf_file_input" accept=".pdf, image/*" class="d-none" required>
                                    </div>

                                    <div class="d-flex gap-3">
                                        <button type="submit" class="btn btn-primary btn-lg" id="submit-btn">
                                            <i class="bi bi-upload me-2"></i> ফাইল আপলোড করুন
                                        </button>
                                        <a href="{{ route('portal.pdf.index') }}" class="btn btn-light btn-lg">বাতিল</a>
                                    </div>
                                </form>
                            </div>
                        </div>

                        {{-- How it works --}}
                        <div class="card border-0 shadow-sm mt-5" style="background: linear-gradient(135deg, #667eea22, #764ba222);">
                            <div class="card-body p-6">
                                <h5 class="fw-bold mb-4">⚡ কিভাবে কাজ করে</h5>
                                <div class="row g-4">
                                    <div class="col-md-4 text-center">
                                        <div class="rounded-circle bg-primary bg-opacity-10 d-inline-flex align-items-center justify-content-center mb-3" style="width:56px;height:56px;">
                                            <span class="fw-bold text-primary">১</span>
                                        </div>
                                        <div class="fw-semibold small">ফাইল আপলোড করুন</div>
                                        <div class="text-muted" style="font-size:12px;">যেকোনো PDF অথবা পৃষ্ঠা ইমেজ</div>
                                    </div>
                                    <div class="col-md-4 text-center">
                                        <div class="rounded-circle bg-warning bg-opacity-10 d-inline-flex align-items-center justify-content-center mb-3" style="width:56px;height:56px;">
                                            <span class="fw-bold text-warning">২</span>
                                        </div>
                                        <div class="fw-semibold small">AI প্রশ্ন তৈরি করবে</div>
                                        <div class="text-muted" style="font-size:12px;">Google Gemini দিয়ে MCQ ও Short Q</div>
                                    </div>
                                    <div class="col-md-4 text-center">
                                        <div class="rounded-circle bg-success bg-opacity-10 d-inline-flex align-items-center justify-content-center mb-3" style="width:56px;height:56px;">
                                            <span class="fw-bold text-success">৩</span>
                                        </div>
                                        <div class="fw-semibold small">Approve করুন</div>
                                        <div class="text-muted" style="font-size:12px;">Question Bank এ সেভ হবে</div>
                                    </div>
                                </div>
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
const dropZone   = document.getElementById('drop-zone');
const fileInput  = document.getElementById('pdf_file_input');
const dropContent = document.getElementById('drop-content');
const filePreview = document.getElementById('file-preview');
const fileName   = document.getElementById('file-name');
const fileSize   = document.getElementById('file-size');
const removeBtn  = document.getElementById('remove-file');
const submitBtn  = document.getElementById('submit-btn');

function showFile(file) {
    fileName.textContent  = file.name;
    fileSize.textContent  = (file.size / 1024 / 1024).toFixed(2) + ' MB';
    dropContent.classList.add('d-none');
    filePreview.classList.remove('d-none');
}

fileInput.addEventListener('change', function() {
    if (this.files[0]) showFile(this.files[0]);
});

removeBtn.addEventListener('click', function() {
    fileInput.value = '';
    dropContent.classList.remove('d-none');
    filePreview.classList.add('d-none');
});

dropZone.addEventListener('dragover', e => { e.preventDefault(); dropZone.style.background = '#e8f4fd'; });
dropZone.addEventListener('dragleave', e => { dropZone.style.background = '#f8f9fa'; });
dropZone.addEventListener('drop', e => {
    e.preventDefault();
    dropZone.style.background = '#f8f9fa';
    const file = e.dataTransfer.files[0];
    if (file && (file.type === 'application/pdf' || file.type.startsWith('image/'))) {
        const dt = new DataTransfer();
        dt.items.add(file);
        fileInput.files = dt.files;
        showFile(file);
    } else {
        alert('শুধুমাত্র PDF অথবা ইমেজ ফাইল গ্রহণযোগ্য।');
    }
});

document.querySelector('form').addEventListener('submit', function() {
    submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span> আপলোড হচ্ছে...';
    submitBtn.disabled = true;
});
</script>
@endpush
