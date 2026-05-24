@extends('layouts.web', ['title' => 'My Attended Exams | Prottoy Academy'])

@push('meta')

@endpush

@push('style')
<style>
    :root {
        --dark-bg: #07091e;
        --card-bg: rgba(255, 255, 255, 0.03);
        --glass-border: rgba(255, 255, 255, 0.08);
        --accent-gold: #f5c518;
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
        display: block;
        min-height: 100vh;
        background: var(--dark-bg);
    }
    
    .dashboard-content-area {
        margin-left: 260px; /* Account for fixed sidebar */
        padding: 40px;
        min-height: 100vh;
    }

    /* GLASS CARDS */
    .glass-card {
        background: var(--card-bg);
        backdrop-filter: blur(15px);
        -webkit-backdrop-filter: blur(15px);
        border: 1px solid var(--glass-border);
        border-radius: 24px;
        padding: 30px;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        text-decoration: none;
        display: block;
        margin-bottom: 25px;
    }
    
    .glass-card .card-header {
        background: transparent !important;
        border-bottom: 1px solid var(--glass-border) !important;
        padding-bottom: 15px;
        margin-bottom: 20px;
    }
    
    .glass-card .card-title {
        color: #fff;
        font-weight: 700;
        font-size: 1.3rem;
        margin-bottom: 0;
    }

    .section-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 35px;
    }

    .section-header h4 {
        font-weight: 800;
        font-size: 1.8rem;
        background: linear-gradient(135deg, #fff, #a5a5a5);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }

    /* Table styling inside Glass Card */
    .exam-summary-table {
        color: #fff !important;
    }

    .exam-summary-table th {
        background: rgba(255, 255, 255, 0.02) !important;
        color: var(--accent-gold) !important;
        font-weight: 600;
        border-color: var(--glass-border) !important;
        padding: 15px 20px !important;
    }

    .exam-summary-table td {
        font-weight: 500;
        border-color: var(--glass-border) !important;
        color: #fff !important;
        padding: 15px 20px !important;
        background: transparent !important;
    }

    .exam-summary-table tr:hover {
        background: rgba(255, 255, 255, 0.02) !important;
    }

    .badge {
        font-size: 13px;
        padding: 6px 10px;
    }

    @media (max-width: 991px) {
        .dashboard-content-area { margin-left: 0; padding: 30px 20px; }
        .mobile-header {
            display: flex !important;
            background: rgba(7, 9, 30, 0.95);
            backdrop-filter: blur(15px);
            padding: 15px 25px;
            justify-content: space-between;
            align-items: center;
            border-bottom: 1px solid var(--glass-border);
            position: sticky;
            top: 0;
            z-index: 1002;
        }
    }

    .mobile-header { display: none; }
    .menu-toggle { background: none; border: none; color: #fff; font-size: 26px; cursor: pointer; }
</style>
@endpush

@section('content')
<div class="mobile-header">
    <h5 class="fw-bold mb-0" style="color: var(--accent-gold);">Prottoy</h5>
    <button class="menu-toggle" id="mobile-sidebar-toggle">
        <i class="ri-menu-5-line"></i>
    </button>
</div>

<div class="dashboard-wrapper">
    @include('web.student.partials.sidebar')

    <!-- Main Content -->
    <div class="dashboard-content-area">
        <div class="section-header">
            <h4>মডেল টেস্ট রিপোর্ট</h4>
        </div>

        @php
            $passPercent = $totalExams ? round(($passed/$totalExams)*100,2) : 0;
            $answerPercent = $totalQuestions ? round(($totalAnswers/$totalQuestions)*100,2) : 0;
            $rightPercent = $totalQuestions ? round(($rightAnswers/$totalQuestions)*100,2) : 0;
            $wrongPercent = $totalQuestions ? round(($wrongAnswers/$totalQuestions)*100,2) : 0;
            $noAnsPercent = $totalQuestions ? round(($noAnswers/$totalQuestions)*100,2) : 0;
            $obtainPercent = $totalMarks ? round(($obtainMarks/$totalMarks)*100,2) : 0;
            $negativePercent = $totalMarks ? round(($negativeMarks/$totalMarks)*100,2) : 0;
        @endphp

        <div class="row">
            <div class="col-lg-12">
                <div class="glass-card shadow-sm p-0 overflow-hidden" style="border-radius: 24px;">
                    <div class="card-header px-4 py-4" style="border-bottom: 1px solid var(--glass-border);">
                        <h5 class="mb-0 fw-bold" style="color: #fff;">My Exams Summary</h5>
                    </div>

                    <div class="table-responsive">
                        <table class="table mb-0 exam-summary-table">
                            <tbody>
                                <tr>
                                    <th>Exams</th>
                                    <td>{{ $totalExams }}</td>
                                </tr>

                                <tr>
                                    <th>Passed</th>
                                    <td>
                                        <span class="badge bg-success" style="background: rgba(34, 197, 94, 0.15) !important; color: #22c55e; border: 1px solid rgba(34, 197, 94, 0.2);">
                                            {{ $passed }} ({{ $passPercent }}%)
                                        </span>
                                    </td>
                                </tr>

                                <tr>
                                    <th>Failed</th>
                                    <td>
                                        <span class="badge bg-danger" style="background: rgba(239, 68, 68, 0.15) !important; color: #ef4444; border: 1px solid rgba(239, 68, 68, 0.2);">
                                            {{ $failed }} ({{ 100-$passPercent }}%)
                                        </span>
                                    </td>
                                </tr>

                                <tr>
                                    <th>Total Questions</th>
                                    <td>{{ $totalQuestions }}</td>
                                </tr>

                                <tr>
                                    <th>Answers</th>
                                    <td>
                                        {{ $totalAnswers }}
                                        <span style="color: var(--text-gray); font-size: 14px;">({{ $answerPercent }}%)</span>
                                    </td>
                                </tr>

                                <tr>
                                    <th style="color: #22c55e !important;">Right Answers</th>
                                    <td class="text-success fw-semibold" style="color: #22c55e !important;">
                                        {{ $rightAnswers }} ({{ $rightPercent }}%)
                                    </td>
                                </tr>

                                <tr>
                                    <th style="color: #ef4444 !important;">Wrong Answers</th>
                                    <td class="text-danger fw-semibold" style="color: #ef4444 !important;">
                                        {{ $wrongAnswers }} ({{ $wrongPercent }}%)
                                    </td>
                                </tr>

                                <tr>
                                    <th>No Answer</th>
                                    <td style="color: var(--text-gray) !important;">
                                        {{ $noAnswers }} ({{ $noAnsPercent }}%)
                                    </td>
                                </tr>

                                <tr>
                                    <th>Total Marks</th>
                                    <td>{{ $totalMarks }}</td>
                                </tr>

                                <tr>
                                    <th style="color: var(--accent-gold) !important;">Obtained Marks</th>
                                    <td class="fw-bold" style="color: var(--accent-gold) !important;">
                                        {{ $obtainMarks }} ({{ $obtainPercent }}%)
                                    </td>
                                </tr>

                                <tr>
                                    <th style="color: #f97316 !important;">Negative Marks</th>
                                    <td style="color: #f97316 !important;">
                                        {{ $negativeMarks }} ({{ $negativePercent }}%)
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            $('#mobile-sidebar-toggle').on('click', function() {
                $('.student-sidebar').toggleClass('active');
                $(this).find('i').toggleClass('ri-menu-5-line ri-close-line');
            });

            $(document).on('click', function(e) {
                if ($(window).width() <= 991) {
                    if (!$(e.target).closest('.student-sidebar').length && !$(e.target).closest('#mobile-sidebar-toggle').length) {
                        $('.student-sidebar').removeClass('active');
                        $('#mobile-sidebar-toggle').find('i').removeClass('ri-close-line').addClass('ri-menu-5-line');
                    }
                }
            });
        });
    </script>
@endpush
