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
            <div class="col-md-9 mb-3 form-group">
                <label for="question_{{ $counter }}">Question <span class="text-danger">*</span></label>
                <input type="text" 
                       name="questions[{{ $counter }}][question]" 
                       id="question_{{ $counter }}" 
                       class="form-control" 
                       required 
                       value="{{ old("questions.$counter.question", $row[0]) }}">
                <input type="hidden" name="questions[{{ $counter }}][description]" value="">
            </div>

            <div class="col-md-3 form-group mb-3">
                <label for="correct_answer_{{ $counter }}">Correct Answer</label>
                <select name="questions[{{ $counter }}][correct_answer]" 
                        id="correct_answer_{{ $counter }}" 
                        class="form-control custom-select" 
                        required 
                        data-minimum-results-for-search="Infinity" 
                        data-placeholder="Select One">
                    <option value="">Select One</option>
                    @php
                        if (class_exists('NumberFormatter')) {
                            $formatter = new \NumberFormatter('en', \NumberFormatter::SPELLOUT);
                            $spellout = [];
                            for ($n = 1; $n <= 5; $n++) {
                                $spellout[$n] = ucfirst($formatter->format($n));
                            }
                        } else {
                            $spellout = [1 => 'a', 2 => 'b', 3 => 'c', 4 => 'd', 5 => 'e'];
                        }
                    @endphp

                    @for ($i = 1; $i <= 5; $i++)
                        <option value="{{ $i }}" {{ ($correctAnswer == $i) ? 'selected' : '' }}>
                            Option {{ $spellout[$i] }}
                        </option>
                    @endfor

                </select>
            </div>

            <div class="col-md-12 row" id="correct_answer_area">
                <div class="col-md-6 form-group mb-3">
                    <label for="option_one_{{ $counter }}">Option One <span class="text-danger">*</span></label>
                    <input type="text" name="questions[{{ $counter }}][option_one]" 
                           id="option_one_{{ $counter }}" 
                           class="form-control" 
                           required 
                           value="{{ $row[1] }}">
                </div>

                <div class="col-md-6 form-group mb-3">
                    <label for="option_two_{{ $counter }}">Option Two <span class="text-danger">*</span></label>
                    <input type="text" name="questions[{{ $counter }}][option_two]" 
                           id="option_two_{{ $counter }}" 
                           class="form-control" 
                           required 
                           value="{{ $row[2] }}">
                </div>

                <div class="col-md-6 form-group mb-3">
                    <label for="option_three_{{ $counter }}">Option Three</label>
                    <input type="text" name="questions[{{ $counter }}][option_three]" 
                           id="option_three_{{ $counter }}" 
                           class="form-control" 
                           value="{{ $row[3] }}">
                </div>

                <div class="col-md-6 form-group mb-3">
                    <label for="option_four_{{ $counter }}">Option Four</label>
                    <input type="text" name="questions[{{ $counter }}][option_four]" 
                           id="option_four_{{ $counter }}" 
                           class="form-control" 
                           value="{{ $row[4] }}">
                </div>

                <div class="col-md-6 form-group mb-3">
                    <label for="option_five_{{ $counter }}">Option Five</label>
                    <input type="text" name="questions[{{ $counter }}][option_five]" 
                           id="option_five_{{ $counter }}" 
                           class="form-control" 
                           value="{{ $row[5] }}">
                </div>
            </div>
        </div>
    </div>
@endforeach
