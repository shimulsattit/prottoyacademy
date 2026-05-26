@extends('layouts.web', ['title' => 'পরীক্ষা দিন | প্রত্যয় একাডেমি'])

@push('style')
<style>
    :root {
        --dark-bg: #07091e;
        --card-bg: rgba(255, 255, 255, 0.03);
        --glass-border: rgba(255, 255, 255, 0.08);
        --accent-green: #22c55e;
        --accent-gold: #f5c518;
        --accent-red: #ef4444;
        --text-white: #ffffff;
        --text-gray: rgba(255, 255, 255, 0.6);
    }
    
    body {
        background-color: var(--dark-bg) !important;
        color: var(--text-white) !important;
        font-family: 'Inter', 'Noto Sans Bengali', sans-serif;
    }
    
    .header-area, .footer-area {
        display: none !important;
    }
    
    .dashboard-wrapper {
        min-height: 100vh;
        background: var(--dark-bg);
        display: block;
    }
    
    .dashboard-content-area {
        width: 100%;
        padding: 100px 20px 40px 20px;
        background: var(--dark-bg);
    }
    
    .exam-container {
        max-width: 900px;
        margin: 20px auto 0;
    }
    
    /* GLASS CARDS */
    .glass-card {
        background: var(--card-bg);
        backdrop-filter: blur(10px);
        -webkit-backdrop-filter: blur(10px);
        border: 1px solid var(--glass-border);
        border-radius: 24px;
        padding: 30px;
        margin-bottom: 25px;
        transition: all 0.3s ease;
    }
    
    .exam-header-banner {
        text-align: center;
        background: linear-gradient(135deg, rgba(245, 197, 24, 0.1), rgba(239, 68, 68, 0.05));
    }
    
    .exam-header-banner h2 {
        font-size: 2rem;
        font-weight: 800;
        color: var(--accent-gold);
        margin-bottom: 15px;
    }
    
    .setup-card-row {
        display: flex;
        justify-content: center;
        gap: 15px;
        margin-bottom: 30px;
    }
    
    .setup-card {
        background: rgba(255, 255, 255, 0.05);
        border: 1px solid var(--glass-border);
        padding: 15px;
        border-radius: 18px;
        text-align: center;
        flex: 1;
        max-width: 140px;
    }
    
    .setup-card i {
        font-size: 22px;
        color: var(--accent-gold);
        margin-bottom: 5px;
        display: block;
    }
    
    .setup-card strong {
        font-size: 20px;
        display: block;
        color: #fff;
    }
    
    .setup-card span {
        font-size: 11px;
        color: var(--text-gray);
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    
    /* QUESTIONS */
    .question-row {
        position: relative;
    }
    
    .question-text {
        font-size: 18px !important;
        font-weight: 600;
        margin-bottom: 25px;
        color: var(--text-white) !important;
        display: flex;
        gap: 15px;
        line-height: 1.6;
    }
    
    .q-number {
        color: var(--accent-gold);
        min-width: 35px;
    }
    
    .options-container {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 15px;
    }
    
    .omr-option {
        display: flex;
        align-items: center;
        cursor: pointer;
        background: rgba(255, 255, 255, 0.03);
        padding: 14px 18px;
        border-radius: 14px;
        border: 1px solid var(--glass-border);
        transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
    }
    
    .omr-option:hover {
        background: rgba(255, 255, 255, 0.06);
        border-color: rgba(245, 197, 24, 0.4);
        transform: translateX(5px);
    }
    
    .omr-option input { display: none; }
    
    .omr-circle {
        width: 32px;
        height: 32px;
        border: 2px solid rgba(255, 255, 255, 0.15);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 15px;
        font-weight: 700;
        color: var(--text-gray);
        font-size: 14px;
        flex-shrink: 0;
        transition: all 0.2s;
    }
    
    .omr-option input:checked + .omr-circle {
        background: var(--accent-gold);
        border-color: var(--accent-gold);
        color: #07091e;
        box-shadow: 0 0 15px rgba(245, 197, 24, 0.3);
    }
    
    .omr-option.disabled {
        opacity: 0.6;
        cursor: not-allowed;
    }
    
    .option-text {
        font-weight: 500;
        color: #efefef;
        font-size: 16px;
    }
    
    /* BOTTOM BAR */
    .fixed-bottom-bar {
        position: fixed;
        bottom: 20px;
        left: 50%;
        transform: translateX(-50%);
        width: 90%;
        max-width: 800px;
        height: 70px;
        background: rgba(255, 255, 255, 0.08);
        backdrop-filter: blur(20px);
        -webkit-backdrop-filter: blur(20px);
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 0 30px;
        z-index: 1000;
        color: white;
        border-radius: 100px;
        border: 1px solid rgba(255, 255, 255, 0.1);
        box-shadow: 0 10px 40px rgba(0,0,0,0.5);
    }
    
    .bottom-info {
        display: flex;
        align-items: center;
        gap: 25px;
        font-weight: 700;
        font-size: 18px;
    }
    
    .bottom-info i { color: var(--accent-gold); }
    
    .btn-submit-fixed {
        background: linear-gradient(135deg, #ef4444, #dc2626);
        color: white;
        border: none;
        padding: 10px 35px;
        border-radius: 50px;
        font-weight: 800;
        font-size: 16px;
        transition: all 0.3s;
        box-shadow: 0 4px 15px rgba(239, 68, 68, 0.3);
    }
    
    .btn-submit-fixed:hover {
        transform: translateY(-2px) scale(1.05);
        box-shadow: 0 6px 20px rgba(239, 68, 68, 0.4);
    }
    
    .subject-tag {
        background: rgba(255, 255, 255, 0.05);
        color: var(--text-gray);
        padding: 5px 15px;
        border-radius: 50px;
        font-size: 13px;
        border: 1px solid var(--glass-border);
    }
    
    .exam-pagination-wrapper { margin-bottom: 120px; }

    /* MODAL FIX */
    .modal-content {
        background: #0f1129 !important;
        border: 1px solid rgba(255,255,255,0.1) !important;
        border-radius: 30px !important;
    }
    
    @media (max-width: 768px) {
        .dashboard-content-area { padding: 80px 16px 40px; }
        .glass-card { padding: 20px; border-radius: 18px; }
        .exam-header-banner h2 { font-size: 1.5rem; }
        .options-container { grid-template-columns: 1fr; gap: 10px; }
        .omr-option { padding: 12px 14px; }
        .question-text { font-size: 16px !important; gap: 10px; }
        .setup-card-row { display: grid; grid-template-columns: repeat(2, 1fr); gap: 12px; }
        .setup-card { max-width: none; }
        .fixed-bottom-bar { width: 92%; padding: 0 15px; height: 60px; bottom: 10px; border-radius: 20px; }
        .bottom-info { gap: 10px; font-size: 14px; }
        .btn-submit-fixed { padding: 8px 20px; font-size: 14px; }
    }
</style>
@endpush

@section('content')
<div class="dashboard-wrapper">
    <div class="dashboard-content-area">
        <div class="exam-container">

            <div class="glass-card exam-header-banner" id="exam-header">
                <h2>{{ $jobCategory->name }}</h2>
                <div class="subject-tags d-flex flex-wrap justify-content-center gap-2">
                    @foreach($subjects as $subject)
                        <span class="subject-tag">{{ $subject }}</span>
                    @endforeach
                </div>
            </div>

            <div class="setup-card-row" id="setup-banner">
                <div class="setup-card">
                    <i class="ri-question-line"></i>
                    <strong>{{ str_replace(['0','1','2','3','4','5','6','7','8','9'], ['০','১','২','৩','৪','৫','৬','৭','৮','৯'], $totalQuestions) }}</strong>
                    <span>প্রশ্ন</span>
                </div>
                <div class="setup-card">
                    <i class="ri-award-line"></i>
                    <strong>{{ str_replace(['0','1','2','3','4','5','6','7','8','9'], ['০','১','২','৩','৪','৫','৬','৭','৮','৯'], $totalMarks) }}</strong>
                    <span>মোট নম্বর</span>
                </div>
                <div class="setup-card">
                    <i class="ri-time-line"></i>
                    <strong>{{ str_replace(['0','1','2','3','4','5','6','7','8','9'], ['০','১','২','৩','৪','৫','৬','৭','৮','৯'], $duration) }}</strong>
                    <span>মিনিট</span>
                </div>
            </div>

            <div class="exam-body">
                <!-- Start Screen -->
                <div class="start-screen text-center" id="start-screen">
                    <div class="glass-card mb-5">
                        <h4 class="mb-4 text-danger fw-bold">পরীক্ষা নির্দেশিকা</h4>
                        <div class="row justify-content-center">
                            <div class="col-md-10">
                                <ul class="text-start list-group list-group-flush bg-transparent border-0 text-white-50">
                                    <li class="list-group-item bg-transparent border-0 text-white py-2"><i class="ri-checkbox-circle-line text-success me-2"></i> প্রতিটি প্রশ্নের মান ১ নম্বর।</li>
                                    <li class="list-group-item bg-transparent border-0 text-white py-2"><i class="ri-error-warning-line text-danger me-2"></i> প্রতিটি ভুল উত্তরের জন্য ০.২৫ নম্বর কাটা হবে।</li>
                                    <li class="list-group-item bg-transparent border-0 text-white py-2"><i class="ri-pages-line text-info me-2"></i> প্রতি পেজে ২০টি করে প্রশ্ন দেখানো হবে।</li>
                                    <li class="list-group-item bg-transparent border-0 text-white py-2"><i class="ri-timer-flash-line text-warning me-2"></i> সময় শেষ হলে পরীক্ষা অটোমেটিক জমা হয়ে যাবে।</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <button class="btn btn-success btn-lg px-5 shadow-lg rounded-pill fw-bold" id="start-exam-btn" style="background: linear-gradient(135deg, #22c55e, #16a34a); border:none; padding: 15px 50px;">পরীক্ষা শুরু করুন</button>
                </div>

                <!-- Question Screen -->
                <div id="exam-screen" style="display: none;">
                    <div class="mb-4 pb-3 border-bottom border-secondary d-flex justify-content-between align-items-center">
                        <div>
                            <span class="text-white-50 small text-uppercase fw-bold">পেজ প্রগতি</span>
                            <h5 id="page-info" class="mb-0 text-white">পেজ ১</h5>
                        </div>
                        <div class="d-none d-md-block">
                             <div class="progress" style="height: 8px; width: 200px; background: rgba(255,255,255,0.1); border-radius: 10px;">
                                <div id="exam-progress-bar" class="progress-bar bg-success" style="width: 0%"></div>
                             </div>
                        </div>
                    </div>

                    <div id="questions-area">
                        <!-- Questions will be injected here -->
                    </div>
                    
                    <div class="mt-5 d-flex justify-content-between exam-pagination-wrapper">
                         <button class="btn btn-outline-light px-4 rounded-pill fw-bold" id="prev-btn" disabled>পূর্ববর্তী পেজ</button>
                         <button class="btn btn-success px-4 rounded-pill fw-bold" id="next-btn" style="background: #22c55e; border:none;">পরবর্তী পেজ</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Fixed Bottom Bar -->
<div class="fixed-bottom-bar" id="fixed-bottom-bar" style="display: none;">
    <div class="bottom-info">
        <div><i class="ri-timer-line"></i> <span id="time-left">০০:০০</span></div>
        <div class="d-none d-sm-block"><i class="ri-list-check"></i> <span id="progress-text">০/০</span></div>
    </div>
    <button class="btn-submit-fixed" id="submit-btn-fixed">সাবমিট</button>
</div>

<!-- Result Modal -->
<div class="modal fade" id="resultModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0">
            <div class="modal-body p-5">
                <div class="result-card text-center">
                    <div class="score-circle mx-auto mb-4" style="width:160px; height:160px; border-radius:50%; border:4px solid var(--accent-green); display:flex; flex-direction:column; align-items:center; justify-content:center; background:rgba(34,197,94,0.1);">
                        <small class="text-white-50">প্রাপ্ত নম্বর</small>
                        <strong class="fs-1 text-success" id="res-marks">০</strong>
                    </div>
                    <h3 class="mb-3 text-white fw-bold">চমৎকার প্রচেষ্টা!</h3>
                    <p class="text-white-50 mb-5">আপনি সফলভাবে পরীক্ষা সম্পন্ন করেছেন। নিচে আপনার বিস্তারিত ফলাফল দেওয়া হলো।</p>
                    
                    <div class="row g-4 mb-5 justify-content-center">
                        <div class="col-4">
                            <div class="p-3 bg-dark rounded-4 border border-secondary">
                                <small class="text-white-50 d-block mb-1">মোট প্রশ্ন</small>
                                <strong class="fs-4 text-white" id="res-total">০</strong>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="p-3 bg-success bg-opacity-50 rounded-4 border border-success">
                                <small class="text-white d-block mb-1">সঠিক উত্তর</small>
                                <strong class="fs-4 text-white" id="res-right">০</strong>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="p-3 bg-danger bg-opacity-50 rounded-4 border border-danger">
                                <small class="text-white d-block mb-1">ভুল উত্তর</small>
                                <strong class="fs-4 text-white" id="res-wrong">০</strong>
                            </div>
                        </div>
                    </div>
                    <div class="d-flex gap-3 justify-content-center flex-wrap">
                        <a href="{{ route('student.exams') }}" class="btn btn-success px-5 rounded-pill fw-bold">আমার ড্যাশবোর্ড</a>
                        <button onclick="location.reload()" class="btn btn-outline-light px-5 rounded-pill fw-bold">আবার পরীক্ষা দিন</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    let allQuestions = [];
    let currentPage = 0;
    const questionsPerPage = 20;
    let answers = {};
    let timerInterval;
    let timeLeft = 0;
    let resultModal;

    $(document).ready(function() {
        $('#resultModal').appendTo('body');
        resultModal = new bootstrap.Modal(document.getElementById('resultModal'));
        
        // CSRF Token Setup for AJAX
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        });
    });

    $('#start-exam-btn').on('click', function() {
        const btn = $(this);
        btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm"></span> লোড হচ্ছে...');
        
        $.ajax({
            url: "{{ route('exam.start', $jobCategory->id) }}",
            method: "POST",
            dataType: "json", // Force JSON response
            success: function(data) {
                if(data && data.questions && data.questions.length > 0) {
                    allQuestions = data.questions;
                    $('#start-screen').hide();
                    $('#setup-banner').hide();
                    $('#exam-screen').show();
                    $('#fixed-bottom-bar').fadeIn();
                    
                    timeLeft = allQuestions.length * 60;
                    startTimer();
                    renderPage(0);
                } else {
                    toastr.warning('এই পরীক্ষার জন্য কোনো প্রশ্ন পাওয়া যায়নি।');
                    btn.prop('disabled', false).text('পরীক্ষা শুরু করুন');
                }
            },
            error: function(xhr) {
                console.error("Exam Start Error:", xhr.status, xhr.responseText);
                
                // If it's a redirect to login (302) or 401 Unauthorized
                if (xhr.status === 401 || xhr.status === 302 || xhr.responseText.includes('login')) {
                    Swal.fire({
                        title: 'লগইন প্রয়োজন',
                        text: "পরীক্ষা দিতে হলে আপনাকে অবশ্যই ছাত্র হিসেবে লগইন করতে হবে।",
                        icon: 'info',
                        showCancelButton: true,
                        confirmButtonText: 'লগইন করুন',
                        cancelButtonText: 'বন্ধ করুন',
                        background: '#07091e',
                        color: '#fff',
                        confirmButtonColor: '#22c55e',
                        cancelButtonColor: '#ef4444',
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.location.href = "{{ route('login') }}";
                        }
                    });
                } else {
                    toastr.error('সার্ভার থেকে সঠিক রেসপন্স পাওয়া যাচ্ছে না। অনুগ্রহ করে লগইন করে আবার চেষ্টা করুন।');
                }
                btn.prop('disabled', false).text('পরীক্ষা শুরু করুন');
            }
        });
    });

    function startTimer() {
        timerInterval = setInterval(() => {
            if (timeLeft <= 0) {
                clearInterval(timerInterval);
                submitExam();
                return;
            }
            timeLeft--;
            let mins = Math.floor(timeLeft / 60);
            let secs = timeLeft % 60;
            $('#time-left').text(`${mins.toString().padStart(2, '0')}:${secs.toString().padStart(2, '0')}`);
        }, 1000);
    }

    function renderPage(pageIdx) {
        const start = pageIdx * questionsPerPage;
        const end = Math.min(start + questionsPerPage, allQuestions.length);
        const pageQuestions = allQuestions.slice(start, end);
        
        let html = '';
        const prefixes = ['ক', 'খ', 'গ', 'ঘ', 'ঙ'];
        const values = ['a', 'b', 'c', 'd', 'e'];

        pageQuestions.forEach((q, i) => {
            const actualNum = start + i + 1;
            let optionsHtml = '';
            
            q.options.forEach((opt, j) => {
                if (opt) {
                    const val = values[j];
                    const isAnswered = answers[q.id] !== undefined;
                    const checked = answers[q.id] === val ? 'checked' : '';
                    const disabledClass = isAnswered ? 'disabled' : '';

                    optionsHtml += `
                        <label class="omr-option ${disabledClass}">
                            <input type="radio" name="q_${q.id}" value="${val}" ${checked} onchange="saveAnswer(${q.id}, '${val}', this)">
                            <div class="omr-circle">${prefixes[j]}</div>
                            <div class="option-text">${opt}</div>
                        </label>
                    `;
                }
            });

            html += `
                <div class="glass-card question-row">
                    <div class="question-text">
                        <span class="q-number">${actualNum}.</span>
                        <div class="q-content">${q.question}</div>
                    </div>
                    <div class="options-container">${optionsHtml}</div>
                </div>
            `;
        });

        $('#questions-area').hide().html(html).fadeIn(400);
        
        const totalPages = Math.ceil(allQuestions.length / questionsPerPage);
        $('#page-info').text(`পেজ ${pageIdx + 1} (মোট ${totalPages} এর মধ্যে)`);
        $('#progress-text').text(`${Object.keys(answers).length}/${allQuestions.length}`);
        
        const progressPercent = (Object.keys(answers).length / allQuestions.length) * 100;
        $('#exam-progress-bar').css('width', progressPercent + '%');
        
        $('#prev-btn').prop('disabled', pageIdx === 0);
        $('#next-btn').toggle(pageIdx < totalPages - 1);
        
        window.scrollTo({ top: 0, behavior: 'smooth' });
    }

    window.saveAnswer = function(qId, val, element) {
        if (answers[qId] !== undefined) return;
        
        answers[qId] = val;
        const container = $(element).closest('.options-container');
        container.find('input').prop('disabled', true);
        container.find('.omr-option').addClass('disabled');
        
        const progressPercent = (Object.keys(answers).length / allQuestions.length) * 100;
        $('#exam-progress-bar').css('width', progressPercent + '%');
        $('#progress-text').text(`${Object.keys(answers).length}/${allQuestions.length}`);
    };

    $('#next-btn').on('click', () => {
        const totalPages = Math.ceil(allQuestions.length / questionsPerPage);
        if (currentPage < totalPages - 1) {
            currentPage++;
            renderPage(currentPage);
        }
    });

    $('#prev-btn').on('click', () => {
        if (currentPage > 0) {
            currentPage--;
            renderPage(currentPage);
        }
    });

    $('#submit-btn-fixed').on('click', () => {
        Swal.fire({
            title: 'পরীক্ষা জমা দিবেন?',
            text: "আপনি কি নিশ্চিত যে পরীক্ষা শেষ করতে চান?",
            icon: 'question',
            showCancelButton: true,
            background: '#07091e',
            color: '#fff',
            confirmButtonColor: '#22c55e',
            cancelButtonText: 'না',
            confirmButtonText: 'হ্যাঁ, জমা দিন'
        }).then((result) => {
            if (result.isConfirmed) {
                submitExam();
            }
        });
    });

    function submitExam() {
        clearInterval(timerInterval);
        const submitBtn = $('#submit-btn-fixed');
        submitBtn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm"></span>');
        
        $.post("{{ route('exam.submit', $jobCategory->id) }}", { answers: answers }, function(data) {
            $('#res-total').text(data.attempt.total_questions);
            $('#res-right').text(data.attempt.right_answers);
            $('#res-wrong').text(data.attempt.wrong_answers);
            $('#res-marks').text(data.attempt.marks_obtained);
            
            resultModal.show();
            submitBtn.prop('disabled', false).text('সাবমিট');
        }).fail(function() {
            toastr.error('জমা দিতে ব্যর্থ হয়েছে। পুনরায় চেষ্টা করুন।');
            submitBtn.prop('disabled', false).text('সাবমিট');
        });
    }
</script>
@endpush
