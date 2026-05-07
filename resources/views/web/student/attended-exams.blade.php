@extends('layouts.web', ['title' => 'My Attended Exams | Prottoy Academy'])

@push('meta')

@endpush

@push('style')
    <style>
        .exam-summary-card{
            border-radius:10px;
            overflow:hidden;
        }

        .exam-summary-card .card-header{
            background:#0d6efd;
            color:white;
            font-weight:600;
        }

        .exam-summary-table th{
            width:45%;
            background:#f8f9fa;
            font-weight:600;
        }

        .exam-summary-table td{
            font-weight:500;
        }

        .exam-summary-table tr:hover{
            background:#f5f8ff;
        }

        .exam-summary-table .percent{
            color:#6c757d;
            font-size:14px;
        }

        .badge{
            font-size:13px;
            padding:6px 10px;
        }
    </style>
@endpush

@section('content')
    <div class="edu-breadcrumb-area breadcrumb-style-1 ptb--60 ptb_md--40 ptb_sm--40 bg-image">
        <div class="container eduvibe-animated-shape">
            <div class="row">
                <div class="col-lg-12">
                    <div class="breadcrumb-inner text-start">
                        <div class="page-title">
                            <h3 class="title">My Attended Exams</h3>
                        </div>
                        <nav class="edu-breadcrumb-nav">
                            <ol class="edu-breadcrumb d-flex justify-content-start liststyle">
                                <li class="breadcrumb-item">
                                    <a href="{{ route('home') }}">
                                        Home
                                    </a>
                                </li>
                                <li class="separator">
                                    <i class="ri-arrow-drop-right-line"></i>
                                </li>
                                <li class="breadcrumb-item active" aria-current="page">
                                    My Attended Exams
                                </li>
                            </ol>
                        </nav>
                    </div>
                </div>
            </div>

            <div class="shape-dot-wrapper shape-wrapper d-xl-block d-none">
                <div class="shape-dot-wrapper shape-wrapper d-xl-block d-none">
                    <div class="shape-image shape-image-1">
                        <img src="{{ asset('assets/images/shapes/shape-11-07.png') }}" alt="Breadcrumb Shape Thumb One" />
                    </div>
                    <div class="shape-image shape-image-2">
                        <img src="{{ asset('assets/images/shapes/shape-01-02.png') }}" alt="Breadcrumb Shape Thumb Two" />
                    </div>
                    <div class="shape-image shape-image-3">
                        <img src="{{ asset('assets/images/shapes/shape-03.png') }}" alt="Breadcrumb Shape Thumb Three" />
                    </div>
                    <div class="shape-image shape-image-4">
                        <img src="{{ asset('assets/images/shapes/shape-13-12.png') }}" alt="Breadcrumb Shape Thumb Four" />
                    </div>
                    <div class="shape-image shape-image-5">
                        <img src="{{ asset('assets/images/shapes/shape-36.png') }}" alt="Breadcrumb Shape Thumb Five" />
                    </div>
                    <div class="shape-image shape-image-6">
                        <img src="{{ asset('assets/images/shapes/shape-05-07.png') }}" alt="Breadcrumb Shape Thumb Six" />
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="edu-elements-area edu-section-gap bg-color-white">
        <div class="container">
            <div class="row g-5">
                <div class="col-lg-3">
                    @include('web.student.partials.sidebar')
                </div>
                <div class="col-lg-9">
                    <!-- Main content area -->
                    <div class="dashboard-content">
                        @php
                            $passPercent = $totalExams ? round(($passed/$totalExams)*100,2) : 0;
                            $answerPercent = $totalQuestions ? round(($totalAnswers/$totalQuestions)*100,2) : 0;
                            $rightPercent = $totalQuestions ? round(($rightAnswers/$totalQuestions)*100,2) : 0;
                            $wrongPercent = $totalQuestions ? round(($wrongAnswers/$totalQuestions)*100,2) : 0;
                            $noAnsPercent = $totalQuestions ? round(($noAnswers/$totalQuestions)*100,2) : 0;
                            $obtainPercent = $totalMarks ? round(($obtainMarks/$totalMarks)*100,2) : 0;
                            $negativePercent = $totalMarks ? round(($negativeMarks/$totalMarks)*100,2) : 0;
                        @endphp
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="card exam-summary-card shadow-sm">
                                <div class="card-header">
                                    <h5 class="mb-0">My Exams Summary</h5>
                                </div>

                                <div class="card-body p-0">
                                    <table class="table table-bordered table-striped mb-0 exam-summary-table">
                                        <tbody>

                                            <tr>
                                                <th>Exams</th>
                                                <td>{{ $totalExams }}</td>
                                            </tr>

                                            <tr>
                                                <th>Passed</th>
                                                <td>
                                                    <span class="badge bg-success">
                                                        {{ $passed }} ({{ $passPercent }}%)
                                                    </span>
                                                </td>
                                            </tr>

                                            <tr>
                                                <th>Failed</th>
                                                <td>
                                                    <span class="badge bg-danger">
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
                                                    <span class="percent">({{ $answerPercent }}%)</span>
                                                </td>
                                            </tr>

                                            <tr>
                                                <th>Right Answers</th>
                                                <td class="text-success fw-semibold">
                                                    {{ $rightAnswers }} ({{ $rightPercent }}%)
                                                </td>
                                            </tr>

                                            <tr>
                                                <th>Wrong Answers</th>
                                                <td class="text-danger fw-semibold">
                                                    {{ $wrongAnswers }} ({{ $wrongPercent }}%)
                                                </td>
                                            </tr>

                                            <tr>
                                                <th>No Answer</th>
                                                <td class="text-muted">
                                                    {{ $noAnswers }} ({{ $noAnsPercent }}%)
                                                </td>
                                            </tr>

                                            <tr>
                                                <th>Total Marks</th>
                                                <td>{{ $totalMarks }}</td>
                                            </tr>

                                            <tr>
                                                <th>Obtained Marks</th>
                                                <td class="text-primary fw-bold">
                                                    {{ $obtainMarks }} ({{ $obtainPercent }}%)
                                                </td>
                                            </tr>

                                            <tr>
                                                <th>Negative Marks</th>
                                                <td class="text-warning">
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
        </div>
    </div>

@endsection

@push('scripts')

@endpush
