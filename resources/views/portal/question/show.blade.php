<div class="modal-header">
    <h5 class="modal-title">Question Information</h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>
<div class="modal-body">
    <div class="row">
        <div class="col-md-12 table-responsive">
            @if ($model->question_type == 'mcq')
                <table class="table table-bordered">
                    <tr>
                        <th colspan="2">Question: <b>
                            {!! str_replace(['<p>', '</p>'], ['<span>', '</span>'], html_entity_decode($model->question)) !!}
                            {{-- {!! html_entity_decode($model->question) !!} --}}
                        </b></th>
                    </tr>
                    <tr>
                        <td class="{{ $model->correct_answer == 1 ? 'bg-success text-white' : '' }}">(A). {!! $model->options ? str_replace(['<p>', '</p>'], ['<span>', '</span>'], html_entity_decode($model->option->option_one)) : '' !!}</td>
                        <td class="{{ $model->correct_answer == 2 ? 'bg-success text-white' : '' }}">(B). {!! $model->options ? str_replace(['<p>', '</p>'], ['<span>', '</span>'], html_entity_decode($model->option->option_two)) : '' !!}</td>
                    </tr>
                    <tr>
                        <td class="{{ $model->correct_answer == 3 ? 'bg-success text-white' : '' }}">(C). {!! $model->options ? str_replace(['<p>', '</p>'], ['<span>', '</span>'], html_entity_decode($model->option->option_three)) : '' !!}</td>
                        <td class="{{ $model->correct_answer == 4 ? 'bg-success text-white' : '' }}">(D). {!! $model->options ? str_replace(['<p>', '</p>'], ['<span>', '</span>'], html_entity_decode($model->option->option_four)) : '' !!}</td>
                    </tr>
                    @if ($model->options && $model->options->option_five != '')
                        <tr>
                            <td>(E). {!! $model->options ? html_entity_decode($model->options->option_five) : '' !!}</td>
                        </tr>
                    @endif
                </table>
            @elseif($model->question_type == 'short_answer' || $model->question_type == 'long_answer')
                <table class="table table-bordered">
                    <tr>
                        <th colspan="2">Question: <b>{!! html_entity_decode($model->question) !!}</b></th>
                    </tr>
                    <tr>
                        <td>{!! html_entity_decode($model->correct_answer) !!}</td>
                    </tr>
                </table>
            @endif
            
        </div>
    </div>
</div>
