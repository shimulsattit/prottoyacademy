@php $counter = 0; $questions = 0; @endphp

@foreach($rows as $key => $row)
    @if($key === 0 || empty($row[0])) @continue @endif

    @php
        $counter++;
        $questions++;
        $stimulus = $row[0] ?? '';
        $q_a = $row[1] ?? '';
        $ans_a = $row[2] ?? '';
        $q_b = $row[3] ?? '';
        $ans_b = $row[4] ?? '';
        $q_c = $row[5] ?? '';
        $ans_c = $row[6] ?? '';
        $q_d = $row[7] ?? '';
        $ans_d = $row[8] ?? '';
        $job_category_id = $row[10] ?? '';
    @endphp

    <div class="card mt-4" style="border: 1px solid #e4e6ef; border-radius: 8px; box-shadow: 0 1px 3px rgba(0,0,0,0.02);">
        <div class="card-header bg-light d-flex align-items-center justify-content-between py-3" style="border-bottom: 1px solid #eff2f5;">
            <h4 class="card-title fw-bold text-dark m-0 fs-5">
                <i class="fas fa-file-alt text-primary me-2"></i> Creative Question #{{ $counter }}
            </h4>
        </div>
        <div class="card-body">
            <input type="hidden" name="questions[{{ $counter }}][job_category_id]" value="{{ $job_category_id }}">
            
            <!-- Stimulus (উদ্দীপক) -->
            <div class="form-group mb-4">
                <label for="stimulus_{{ $counter }}" class="form-label fw-bold text-gray-800 fs-6">উদ্দীপকের বিষয়বস্তু <span class="text-danger">*</span></label>
                <textarea name="questions[{{ $counter }}][stimulus]" id="stimulus_{{ $counter }}" class="form-control {{ (isset($isMath) && $isMath == 1) ? 'editor-textarea' : '' }}" rows="4" required style="border-radius: 6px; border: 1px solid #dbdfe9;">{{ old("questions.$counter.stimulus", $stimulus) }}</textarea>
            </div>

            <!-- Sub Questions and Answers -->
            <div class="row">
                <!-- ক (জ্ঞানমূলক) -->
                <div class="col-md-6 mb-4">
                    <div class="border rounded p-4" style="background-color: #ffffff; border: 1px solid #eff2f5; border-radius: 8px; height: 100%;">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <span class="fw-bold text-dark fs-6">
                                <i class="fas fa-book-open text-primary me-2"></i> ক (জ্ঞানমূলক প্রশ্ন)
                            </span>
                            <span style="background-color: #f1f8f5; color: #181c32; padding: 4px 10px; border-radius: 4px; font-size: 11px; font-weight: 600; border: 1px solid #e4e6ef;">নম্বর: ১</span>
                        </div>
                        <input type="hidden" name="questions[{{ $counter }}][sub_questions][0][question_mark]" value="1">
                        <div class="form-group mb-3">
                            <label class="form-label fw-semibold text-gray-700 mb-1" style="font-size: 13px;">প্রশ্ন <span class="text-danger">*</span></label>
                            <textarea name="questions[{{ $counter }}][sub_questions][0][question]" id="cq_q_a_{{ $counter }}" class="form-control {{ (isset($isMath) && $isMath == 1) ? 'editor-textarea' : '' }}" rows="2" required style="border-radius: 6px; border: 1px solid #dbdfe9;">{{ old("questions.$counter.sub_questions.0.question", $q_a) }}</textarea>
                        </div>
                        <div class="form-group mb-0">
                            <label class="form-label fw-semibold text-gray-700 mb-1" style="font-size: 13px;">উত্তর <span class="text-danger">*</span></label>
                            <textarea name="questions[{{ $counter }}][sub_questions][0][correct_answer]" id="cq_ans_a_{{ $counter }}" class="form-control {{ (isset($isMath) && $isMath == 1) ? 'editor-textarea' : '' }}" rows="2" required style="border-radius: 6px; border: 1px solid #dbdfe9;">{{ old("questions.$counter.sub_questions.0.correct_answer", $ans_a) }}</textarea>
                        </div>
                    </div>
                </div>

                <!-- খ (অনুধাবনমূলক) -->
                <div class="col-md-6 mb-4">
                    <div class="border rounded p-4" style="background-color: #ffffff; border: 1px solid #eff2f5; border-radius: 8px; height: 100%;">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <span class="fw-bold text-dark fs-6">
                                <i class="fas fa-brain text-primary me-2"></i> খ (অনুধাবনমূলক প্রশ্ন)
                            </span>
                            <span style="background-color: #f1f8f5; color: #181c32; padding: 4px 10px; border-radius: 4px; font-size: 11px; font-weight: 600; border: 1px solid #e4e6ef;">নম্বর: ২</span>
                        </div>
                        <input type="hidden" name="questions[{{ $counter }}][sub_questions][1][question_mark]" value="2">
                        <div class="form-group mb-3">
                            <label class="form-label fw-semibold text-gray-700 mb-1" style="font-size: 13px;">প্রশ্ন <span class="text-danger">*</span></label>
                            <textarea name="questions[{{ $counter }}][sub_questions][1][question]" id="cq_q_b_{{ $counter }}" class="form-control {{ (isset($isMath) && $isMath == 1) ? 'editor-textarea' : '' }}" rows="2" required style="border-radius: 6px; border: 1px solid #dbdfe9;">{{ old("questions.$counter.sub_questions.1.question", $q_b) }}</textarea>
                        </div>
                        <div class="form-group mb-0">
                            <label class="form-label fw-semibold text-gray-700 mb-1" style="font-size: 13px;">উত্তর <span class="text-danger">*</span></label>
                            <textarea name="questions[{{ $counter }}][sub_questions][1][correct_answer]" id="cq_ans_b_{{ $counter }}" class="form-control {{ (isset($isMath) && $isMath == 1) ? 'editor-textarea' : '' }}" rows="2" required style="border-radius: 6px; border: 1px solid #dbdfe9;">{{ old("questions.$counter.sub_questions.1.correct_answer", $ans_b) }}</textarea>
                        </div>
                    </div>
                </div>

                <!-- গ (প্রয়োগমূলক) -->
                <div class="col-md-6 mb-4">
                    <div class="border rounded p-4" style="background-color: #ffffff; border: 1px solid #eff2f5; border-radius: 8px; height: 100%;">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <span class="fw-bold text-dark fs-6">
                                <i class="fas fa-pencil-alt text-primary me-2"></i> গ (প্রয়োগমূলক প্রশ্ন)
                            </span>
                            <span style="background-color: #f1f8f5; color: #181c32; padding: 4px 10px; border-radius: 4px; font-size: 11px; font-weight: 600; border: 1px solid #e4e6ef;">নম্বর: ৩</span>
                        </div>
                        <input type="hidden" name="questions[{{ $counter }}][sub_questions][2][question_mark]" value="3">
                        <div class="form-group mb-3">
                            <label class="form-label fw-semibold text-gray-700 mb-1" style="font-size: 13px;">প্রশ্ন <span class="text-danger">*</span></label>
                            <textarea name="questions[{{ $counter }}][sub_questions][2][question]" id="cq_q_c_{{ $counter }}" class="form-control {{ (isset($isMath) && $isMath == 1) ? 'editor-textarea' : '' }}" rows="2" required style="border-radius: 6px; border: 1px solid #dbdfe9;">{{ old("questions.$counter.sub_questions.2.question", $q_c) }}</textarea>
                        </div>
                        <div class="form-group mb-0">
                            <label class="form-label fw-semibold text-gray-700 mb-1" style="font-size: 13px;">উত্তর <span class="text-danger">*</span></label>
                            <textarea name="questions[{{ $counter }}][sub_questions][2][correct_answer]" id="cq_ans_c_{{ $counter }}" class="form-control {{ (isset($isMath) && $isMath == 1) ? 'editor-textarea' : '' }}" rows="2" required style="border-radius: 6px; border: 1px solid #dbdfe9;">{{ old("questions.$counter.sub_questions.2.correct_answer", $ans_c) }}</textarea>
                        </div>
                    </div>
                </div>

                <!-- ঘ (উচ্চতর দক্ষতা) -->
                <div class="col-md-6 mb-4">
                    <div class="border rounded p-4" style="background-color: #ffffff; border: 1px solid #eff2f5; border-radius: 8px; height: 100%;">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <span class="fw-bold text-dark fs-6">
                                <i class="fas fa-chart-line text-primary me-2"></i> ঘ (উচ্চতর দক্ষতা মূলক প্রশ্ন)
                            </span>
                            <span style="background-color: #f1f8f5; color: #181c32; padding: 4px 10px; border-radius: 4px; font-size: 11px; font-weight: 600; border: 1px solid #e4e6ef;">নম্বর: ৪</span>
                        </div>
                        <input type="hidden" name="questions[{{ $counter }}][sub_questions][3][question_mark]" value="4">
                        <div class="form-group mb-3">
                            <label class="form-label fw-semibold text-gray-700 mb-1" style="font-size: 13px;">প্রশ্ন <span class="text-danger">*</span></label>
                            <textarea name="questions[{{ $counter }}][sub_questions][3][question]" id="cq_q_d_{{ $counter }}" class="form-control {{ (isset($isMath) && $isMath == 1) ? 'editor-textarea' : '' }}" rows="2" required style="border-radius: 6px; border: 1px solid #dbdfe9;">{{ old("questions.$counter.sub_questions.3.question", $q_d) }}</textarea>
                        </div>
                        <div class="form-group mb-0">
                            <label class="form-label fw-semibold text-gray-700 mb-1" style="font-size: 13px;">উত্তর <span class="text-danger">*</span></label>
                            <textarea name="questions[{{ $counter }}][sub_questions][3][correct_answer]" id="cq_ans_d_{{ $counter }}" class="form-control {{ (isset($isMath) && $isMath == 1) ? 'editor-textarea' : '' }}" rows="2" required style="border-radius: 6px; border: 1px solid #dbdfe9;">{{ old("questions.$counter.sub_questions.3.correct_answer", $ans_d) }}</textarea>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endforeach
