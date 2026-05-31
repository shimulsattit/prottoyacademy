@php $counter = 0; $questions = 0; @endphp

@foreach($rows as $key => $row)
    @if($key === 0 || empty($row[0])) @continue @endif

    @php
        $counter++;
        $questions++;
        $correctAnswer = $row[6] ?? '';
    @endphp

    <div class="card mt-3">
        <div class="card-header">
            <h4 class="card-title">Question {{ $counter }}</h4>
        </div>
        <div class="card-body row">
            <div class="col-md-12j mb-3 form-group">
                <label for="question_{{ $counter }}">Question <span class="text-danger">*</span></label>
                <input type="text" 
                       name="questions[{{ $counter }}][question]" 
                       id="question_{{ $counter }}" 
                       class="form-control" 
                       required 
                       value="{{ old("questions.$counter.question", $row[0]) }}">
                <input type="hidden" name="questions[{{ $counter }}][description]" value="">
                <input type="hidden" name="questions[{{ $counter }}][job_category_id]" value="{{ $row[3] ?? '' }}">
            </div>

            <div class="col-md-12 form-group mb-3">
                <label for="correct_answer_{{ $counter }}">Correct Answer</label>
                <textarea type="text" 
                       name="questions[{{ $counter }}][correct_answer]" 
                       id="question_{{ $counter }}" 
                       class="form-control" 
                       required 
                >{{ old("questions.$counter.question", $row[1]) }}</textarea>
            </div>

            
        </div>
    </div>
@endforeach
