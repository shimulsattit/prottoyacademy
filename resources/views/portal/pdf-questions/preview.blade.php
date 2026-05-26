@extends('layouts.admin', ['title' => 'প্রশ্ন Preview ও সেভ করুন'])

@section('content')
<div class="app-main flex-column flex-row-fluid" id="app_main">
    <div class="d-flex flex-column flex-column-fluid">
        <div id="app_toolbar" class="app-toolbar py-3 py-lg-6">
            <div id="app_toolbar_container" class="app-container container-xxl d-flex flex-stack">
                <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
                    <h1 class="page-heading d-flex text-gray-900 fw-bold fs-3 flex-column justify-content-center my-0">
                        ✅ প্রশ্ন Review ও Approve করুন
                    </h1>
                    <div class="text-muted small mt-1">
                        {{ $pdf->title }} — {{ $pdf->category->name ?? '' }}
                        · মোট <strong class="text-info">{{ count($questions) }}</strong>টি প্রশ্ন
                    </div>
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('portal.pdf.show', $pdf->id) }}" class="btn btn-sm btn-light">
                        <i class="bi bi-arrow-left me-1"></i> পেছনে
                    </a>
                </div>
            </div>
        </div>

        <div id="app_content" class="app-content flex-column-fluid">
            <div id="app_content_container" class="app-container container-xxl">

                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show mb-4">
                        <i class="bi bi-check-circle me-2"></i> {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @if(empty($questions))
                    <div class="card shadow-sm">
                        <div class="card-body text-center py-10">
                            <i class="bi bi-inbox fs-1 text-muted d-block mb-3"></i>
                            <h5 class="text-muted">কোনো প্রশ্ন পাওয়া যায়নি।</h5>
                            <a href="{{ route('portal.pdf.show', $pdf->id) }}" class="btn btn-primary mt-3">
                                আবার Generate করুন
                            </a>
                        </div>
                    </div>
                @else

                {{-- Top Action Bar --}}
                <div class="card shadow-sm mb-5" style="position: sticky; top: 70px; z-index: 100; background: #fff;">
                    <div class="card-body py-3 d-flex flex-wrap align-items-center justify-content-between gap-3">
                        <div class="d-flex align-items-center gap-4">
                            <label class="d-flex align-items-center gap-2 cursor-pointer">
                                <input type="checkbox" id="select-all" class="form-check-input" checked>
                                <span class="fw-semibold">সব নির্বাচন করুন</span>
                            </label>
                            <span class="text-muted">|</span>
                            <span id="selected-count" class="fw-bold text-primary">{{ count($questions) }}</span>
                            <span class="text-muted">টি নির্বাচিত</span>
                        </div>
                        <div class="d-flex gap-2">
                            <span class="badge bg-success fs-7 py-2 px-3">
                                MCQ: {{ collect($questions)->where('type', 'mcq')->count() }}
                            </span>
                            <span class="badge bg-info fs-7 py-2 px-3">
                                Short: {{ collect($questions)->where('type', 'short')->count() }}
                            </span>
                            <button type="button" class="btn btn-success btn-sm fw-bold" id="save-btn" onclick="submitQuestions()">
                                <i class="bi bi-floppy me-1"></i> নির্বাচিত প্রশ্ন সেভ করুন
                            </button>
                        </div>
                    </div>
                </div>

                {{-- Questions List --}}
                <form action="{{ route('portal.pdf.save', $pdf->id) }}" method="POST" id="save-form">
                    @csrf
                    <div id="questions-container">
                        @foreach($questions as $index => $q)
                            <div class="card shadow-sm mb-4 question-card" data-index="{{ $index }}">
                                <div class="card-body p-5">
                                    <div class="d-flex align-items-start gap-4">

                                        {{-- Checkbox --}}
                                        <div class="pt-1">
                                            <input type="checkbox" class="form-check-input q-checkbox" data-index="{{ $index }}" checked style="width:20px;height:20px;">
                                        </div>

                                        {{-- Content --}}
                                        <div class="flex-grow-1">
                                            <div class="d-flex align-items-center gap-3 mb-3">
                                                <span class="badge {{ $q['type'] === 'mcq' ? 'bg-success' : 'bg-info' }} fs-8">
                                                    {{ $q['type'] === 'mcq' ? 'MCQ' : 'Short Q' }}
                                                </span>
                                                <span class="text-muted small">প্রশ্ন #{{ $index + 1 }}</span>
                                            </div>

                                            {{-- Question Text --}}
                                            <div class="mb-3">
                                                <label class="form-label text-muted small fw-semibold">প্রশ্ন</label>
                                                <textarea name="questions[{{ $index }}][question]" class="form-control" rows="2"
                                                          required>{{ $q['question'] }}</textarea>
                                                <input type="hidden" name="questions[{{ $index }}][type]" value="{{ $q['type'] }}">
                                            </div>

                                            @if($q['type'] === 'mcq')
                                                {{-- MCQ Options --}}
                                                <div class="row g-3 mb-3">
                                                    @foreach(['a' => 'ক', 'b' => 'খ', 'c' => 'গ', 'd' => 'ঘ'] as $key => $label)
                                                        <div class="col-md-6">
                                                            <div class="input-group">
                                                                <span class="input-group-text fw-bold {{ ($q['correct_answer'] ?? '') === $key ? 'bg-success text-white' : '' }}"
                                                                      style="min-width:40px;">{{ $label }}</span>
                                                                <input type="text" name="questions[{{ $index }}][options][{{ $key }}]"
                                                                       class="form-control {{ ($q['correct_answer'] ?? '') === $key ? 'border-success' : '' }}"
                                                                       value="{{ $q['options'][$key] ?? '' }}" placeholder="অপশন {{ $label }}">
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                </div>

                                                <div class="row g-3">
                                                    <div class="col-md-4">
                                                        <label class="form-label small fw-semibold text-success">✅ সঠিক উত্তর</label>
                                                        <select name="questions[{{ $index }}][correct_answer]" class="form-select form-select-sm">
                                                            @foreach(['a' => 'ক', 'b' => 'খ', 'c' => 'গ', 'd' => 'ঘ'] as $key => $label)
                                                                <option value="{{ $key }}" {{ ($q['correct_answer'] ?? '') === $key ? 'selected' : '' }}>
                                                                    {{ $label }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <div class="col-md-8">
                                                        <label class="form-label small fw-semibold text-muted">ব্যাখ্যা (ঐচ্ছিক)</label>
                                                        <input type="text" name="questions[{{ $index }}][explanation]"
                                                               class="form-control form-control-sm"
                                                               value="{{ $q['explanation'] ?? '' }}" placeholder="সংক্ষিপ্ত ব্যাখ্যা">
                                                    </div>
                                                </div>

                                            @else
                                                {{-- Short Question --}}
                                                <div class="row g-3">
                                                    <div class="col-md-8">
                                                        <label class="form-label small fw-semibold text-success">✅ উত্তর</label>
                                                        <textarea name="questions[{{ $index }}][correct_answer]" class="form-control form-control-sm"
                                                                  rows="2">{{ $q['correct_answer'] ?? '' }}</textarea>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <label class="form-label small fw-semibold text-muted">ব্যাখ্যা</label>
                                                        <input type="text" name="questions[{{ $index }}][explanation]"
                                                               class="form-control form-control-sm"
                                                               value="{{ $q['explanation'] ?? '' }}">
                                                    </div>
                                                </div>
                                            @endif
                                        </div>

                                        {{-- Delete Button --}}
                                        <button type="button" class="btn btn-icon btn-sm btn-light-danger flex-shrink-0"
                                                onclick="removeQuestion({{ $index }})">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    {{-- Bottom Save Button --}}
                    <div class="d-flex gap-3 justify-content-end pb-8">
                        <a href="{{ route('portal.pdf.index') }}" class="btn btn-light btn-lg">বাতিল</a>
                        <button type="button" class="btn btn-success btn-lg fw-bold" onclick="submitQuestions()">
                            <i class="bi bi-floppy me-2"></i> নির্বাচিত প্রশ্ন Question Bank এ সেভ করুন
                        </button>
                    </div>
                </form>

                @endif

            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
// Select All toggle
document.getElementById('select-all')?.addEventListener('change', function() {
    document.querySelectorAll('.q-checkbox').forEach(cb => cb.checked = this.checked);
    updateCount();
});

document.querySelectorAll('.q-checkbox').forEach(cb => {
    cb.addEventListener('change', updateCount);
});

function updateCount() {
    const count = document.querySelectorAll('.q-checkbox:checked').length;
    document.getElementById('selected-count').textContent = count;
}

function removeQuestion(index) {
    const card = document.querySelector(`.question-card[data-index="${index}"]`);
    if (card) { card.remove(); updateCount(); }
}

function submitQuestions() {
    const checked = document.querySelectorAll('.q-checkbox:checked');
    if (checked.length === 0) {
        alert('কমপক্ষে একটি প্রশ্ন নির্বাচন করুন।');
        return;
    }

    // Disable unchecked question fields so they don't submit
    document.querySelectorAll('.q-checkbox').forEach(cb => {
        if (!cb.checked) {
            const card = cb.closest('.question-card');
            if (card) card.querySelectorAll('input, textarea, select').forEach(el => el.disabled = true);
        }
    });

    document.getElementById('save-btn').innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span> সেভ হচ্ছে...';
    document.getElementById('save-btn').disabled = true;
    document.getElementById('save-form').submit();
}
</script>
@endpush
