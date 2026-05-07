<form action="{{ route('portal.question.update', $question->uuid) }}" method="POST" class="ajax-form">
    @method('PATCH')
    <input type="hidden" name="method" value="short">
    <input type="hidden" name="category_id" value="{{ $question->category_id }}">
    <input type="hidden" name="job_category_id" value="{{ $question->job_category_id }}">
    <input type="hidden" name="year_id" value="{{ $question->year_id }}">
    <input type="hidden" name="exam_id" value="{{ $question->exam_id }}">
    <input type="hidden" name="passage_id" value="{{ $question->passage_id }}">
    <input type="hidden" name="question_type" value="{{ $question->question_type }}">
    <input type="hidden" name="hard_level" value="{{ $question->hard_level }}">
    <input type="hidden" name="question_mark" value="{{ $question->question_mark }}">
    <input type="hidden" name="site_title" value="{{ $question->site_title }}">
    <input type="hidden" name="meta_title" value="{{ $question->meta_title }}">
    <input type="hidden" name="meta_description" value="{{ $question->meta_description }}">
    <input type="hidden" name="meta_keywords" value="{{ $question->meta_keywords }}">
    <input type="hidden" name="meta_article_tag" value="{{ $question->meta_article_tag }}">
    <input type="hidden" name="status" value="{{ $question->status }}">
    <div class="modal-header">
        <h5 class="modal-title">Update Question Information</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
    </div>
    <div class="modal-body">
        <div class="row">
            <div class="col-md-9 form-group mb-3">
                <label for="question">Question <span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="question" name="question" placeholder="Enter question" value="{{ $question->question }}" required>
            </div>

            @if ($question->question_type == 'mcq')
                <div class="col-md-3 form-group mb-3">
                    <label for="correct_answer">Answer <span class="text-danger">*</span></label>
                    <select name="correct_answer" id="correct_answer" class="form-control select">
                        <option {{ $question->correct_answer == 1 ? 'selected' : '' }} value="1">One</option>
                        <option {{ $question->correct_answer == 2 ? 'selected' : '' }} value="2">Two</option>
                        <option {{ $question->correct_answer == 3 ? 'selected' : '' }} value="3">Three</option>
                        <option {{ $question->correct_answer == 4 ? 'selected' : '' }} value="4">Four</option>
                    </select>
                </div>

                <div class="col-md-6 form-group mb-3">
                    <label for="option_one">Option One <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="option_one" name="option_one" placeholder="Enter option one" value="{{ $question->options ? $question->options->option_one : '' }}" required>
                </div>

                <div class="col-md-6 form-group mb-3">
                    <label for="option_two">Option Two <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="option_two" name="option_two" placeholder="Enter option Two" value="{{ $question->options ? $question->options->option_two : '' }}" required>
                </div>

                <div class="col-md-6 form-group mb-3">
                    <label for="option_three">Option Three</label>
                    <input type="text" class="form-control" id="option_three" name="option_three" placeholder="Enter option Three" value="{{ $question->options ? $question->options->option_three : '' }}">
                </div>

                <div class="col-md-6 form-group mb-3">
                    <label for="option_four">Option Four</label>
                    <input type="text" class="form-control" id="option_four" name="option_four" placeholder="Enter option Four" value="{{ $question->options ? $question->options->option_four : '' }}">
                </div>

                <div class="col-md-6 form-group mb-3">
                    <label for="option_five">Option Five</label>
                    <input type="text" class="form-control" id="option_five" name="option_five" placeholder="Enter option Four" value="{{ $question->options ? $question->options->option_five : '' }}">
                </div>
            @else
                <div class="col-md-12 form-group mb-3">
                    <label for="correct_answer">Answer</label>
                    <textarea name="correct_answer" id="correct_answer" cols="30" rows="3" class="form-control">{{ $question->correct_answer }}</textarea>
                </div>
            @endif

            <div class="col-md-12 form-group mb-3">
                <label for="content">Description</label>
                <textarea name="description" id="content" cols="30" rows="3" class="form-control">{{ $question->content }}</textarea>
            </div>
        </div>
    </div>
    @if(Auth::guard('admin')->user()->hasPermissionTo('question.update'))
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            <button type="submit" id="submit" class="btn btn-primary">Save changes</button>
            <button type="button" id="submitting" style="display: none;" class="btn btn-primary">
                <i class="fas fa-spinner fa-spin"></i>
            </button>
        </div>
    @endif 
</form>
