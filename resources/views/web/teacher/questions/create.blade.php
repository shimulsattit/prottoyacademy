@extends('layouts.teacher', ['title' => 'নতুন প্রশ্ন যোগ'])

@push('style')
<style>
    .form-card { padding: 28px 30px; }
    .form-label { display: block; font-size: 13px; font-weight: 600; color: rgba(255,255,255,.65); margin-bottom: 7px; }
    .form-label span { color: #ef4444; }
    .form-ctrl {
        width: 100%; padding: 12px 14px; background: rgba(255,255,255,.06);
        border: 1px solid rgba(255,255,255,.1); border-radius: 11px; color: #fff;
        font-size: 14px; font-family: inherit; outline: none; transition: border-color .2s;
    }
    .form-ctrl:focus { border-color: rgba(0,180,216,.5); background: rgba(255,255,255,.09); }
    .form-ctrl::placeholder { color: rgba(255,255,255,.25); }
    select.form-ctrl option { background: #07091e; color: #fff; }
    textarea.form-ctrl { resize: vertical; min-height: 110px; }
    .fg { margin-bottom: 20px; }
    .row-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 18px; }
    .row-3 { display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 18px; }
    .type-radios { display: flex; gap: 12px; flex-wrap: wrap; }
    .type-radio { display: none; }
    .type-label {
        display: flex; align-items: center; gap: 8px; padding: 10px 20px;
        border: 1.5px solid var(--bdr); border-radius: 10px; cursor: pointer;
        font-size: 14px; font-weight: 600; color: rgba(255,255,255,.55);
        transition: all .2s; user-select: none;
    }
    .type-radio:checked + .type-label { border-color: var(--tp); color: var(--tp); background: var(--tp-light); }
    .mcq-section { background: rgba(0,180,216,.05); border: 1px solid rgba(0,180,216,.15); border-radius: 14px; padding: 20px; margin-top: 4px; }
    .opt-row { display: flex; align-items: center; gap: 10px; margin-bottom: 10px; }
    .opt-radio { width: 18px; height: 18px; accent-color: #00b4d8; cursor: pointer; flex-shrink: 0; }
    .opt-input { flex: 1; }
    .opt-label { font-size: 12px; color: rgba(255,255,255,.45); min-width: 60px; }
    .section-title { font-size: 13px; font-weight: 700; color: rgba(255,255,255,.6); margin-bottom: 14px; letter-spacing: .5px; text-transform: uppercase; }
    .submit-btn { display: inline-flex; align-items: center; gap: 8px; padding: 13px 30px; background: linear-gradient(135deg, #00b4d8, #0077b6); border: none; border-radius: 11px; color: #07091e; font-family: inherit; font-size: 15px; font-weight: 700; cursor: pointer; box-shadow: 0 6px 18px rgba(0,180,216,.35); transition: transform .2s; }
    .submit-btn:hover { transform: translateY(-2px); }
    .info-box { background: rgba(245,197,24,.08); border: 1px solid rgba(245,197,24,.2); border-radius: 11px; padding: 12px 16px; margin-bottom: 22px; font-size: 13px; color: rgba(255,255,255,.6); }
    @media (max-width: 768px) { .row-2, .row-3 { grid-template-columns: 1fr; } }
</style>
@endpush

@section('content')
<div class="pg-head" style="display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:10px;">
    <div>
        <h2>নতুন প্রশ্ন যোগ করুন</h2>
        <p>প্রশ্নটি জমা দেওয়ার পর অ্যাডমিন অনুমোদন করলে প্রকাশিত হবে।</p>
    </div>
    <a href="{{ route('teacher.questions') }}" style="font-size:13px; color:var(--tp); text-decoration:none;">← ফিরে যান</a>
</div>

<div class="info-box">💡 প্রশ্ন জমা দেওয়ার পর অ্যাডমিনের অনুমোদনের জন্য অপেক্ষা করুন। অনুমোদিত হলে প্রশ্নটি ওয়েবসাইটে প্রকাশিত হবে।</div>

<div class="t-card form-card">
    <form method="POST" action="{{ route('teacher.questions.store') }}">
        @csrf

        {{-- Question Type --}}
        <div class="fg">
            <label class="form-label">প্রশ্নের ধরন <span>*</span></label>
            <div class="type-radios">
                <input type="radio" name="type" id="t-mcq" value="mcq" class="type-radio" {{ old('type','mcq') === 'mcq' ? 'checked' : '' }}>
                <label for="t-mcq" class="type-label">🔘 MCQ</label>

                <input type="radio" name="type" id="t-short" value="short" class="type-radio" {{ old('type') === 'short' ? 'checked' : '' }}>
                <label for="t-short" class="type-label">✏️ Short</label>

                <input type="radio" name="type" id="t-cq" value="cq" class="type-radio" {{ old('type') === 'cq' ? 'checked' : '' }}>
                <label for="t-cq" class="type-label">📄 CQ</label>
            </div>
        </div>

        {{-- Category & Job Category --}}
        <div class="row-2">
            <div class="fg">
                <label class="form-label">ক্যাটাগরি <span>*</span></label>
                <select name="category_id" class="form-ctrl" required>
                    <option value="">— ক্যাটাগরি বেছে নিন —</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" {{ old('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="fg">
                <label class="form-label">জব ক্যাটাগরি</label>
                <select name="job_category_id" class="form-ctrl">
                    <option value="">— ঐচ্ছিক —</option>
                    @foreach($jobCategories as $jc)
                        <option value="{{ $jc->id }}" {{ old('job_category_id') == $jc->id ? 'selected' : '' }}>{{ $jc->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        {{-- Year & Exam --}}
        <div class="row-2">
            <div class="fg">
                <label class="form-label">বছর</label>
                <select name="year_id" class="form-ctrl">
                    <option value="">— ঐচ্ছিক —</option>
                    @foreach($years as $yr)
                        <option value="{{ $yr->id }}" {{ old('year_id') == $yr->id ? 'selected' : '' }}>{{ $yr->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="fg">
                <label class="form-label">পরীক্ষা</label>
                <select name="exam_id" class="form-ctrl">
                    <option value="">— ঐচ্ছিক —</option>
                    @foreach($exams as $ex)
                        <option value="{{ $ex->id }}" {{ old('exam_id') == $ex->id ? 'selected' : '' }}>{{ $ex->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        {{-- Question Text --}}
        <div class="fg">
            <label class="form-label">প্রশ্ন <span>*</span></label>
            <textarea name="question" class="form-ctrl" placeholder="এখানে প্রশ্নটি লিখুন..." required>{{ old('question') }}</textarea>
        </div>

        {{-- MCQ Options --}}
        <div class="fg mcq-section" id="mcqSection">
            <div class="section-title">MCQ অপশনসমূহ</div>
            <p style="font-size:12px; color:rgba(255,255,255,.4); margin-bottom:14px;">সঠিক উত্তরের পাশের রেডিও বাটন সিলেক্ট করুন।</p>
            @foreach(['ক', 'খ', 'গ', 'ঘ'] as $i => $letter)
            <div class="opt-row">
                <input type="radio" name="correct_option" value="{{ $i }}" class="opt-radio" {{ $i === 0 ? 'checked' : '' }}>
                <span class="opt-label" style="background:rgba(0,180,216,.15); color:#00b4d8; padding:4px 10px; border-radius:6px; font-weight:700;">{{ $letter }}</span>
                <input type="text" name="options[{{ $i }}]" class="form-ctrl opt-input" placeholder="অপশন {{ $letter }} লিখুন..." value="{{ old('options.'.$i) }}">
            </div>
            @endforeach
        </div>

        {{-- Answer Description --}}
        <div class="fg">
            <label class="form-label">ব্যাখ্যা / উত্তর</label>
            <textarea name="answer_description" class="form-ctrl" placeholder="প্রশ্নের ব্যাখ্যা বা বিস্তারিত উত্তর লিখুন (ঐচ্ছিক)...">{{ old('answer_description') }}</textarea>
        </div>

        <button type="submit" class="submit-btn">
            <i class="ri-send-plane-line"></i> প্রশ্ন জমা দিন
        </button>
    </form>
</div>

@push('scripts')
<script>
    const radios   = document.querySelectorAll('input[name="type"]');
    const mcqBlock = document.getElementById('mcqSection');

    function toggleMcq() {
        const val = document.querySelector('input[name="type"]:checked')?.value;
        mcqBlock.style.display = val === 'mcq' ? 'block' : 'none';
    }
    radios.forEach(r => r.addEventListener('change', toggleMcq));
    toggleMcq();
</script>
@endpush
@endsection
