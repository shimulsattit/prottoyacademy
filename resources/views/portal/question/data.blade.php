@foreach ($questions as $question)
    <div class="col-md-12 p-3 border">
        <div class="row">
            <div class="col-md-12">
                <h3 id="question_{{ $question->id }}" class="h4">
                    Q. {!! str_replace(['<p>', '</p>'], ['<span>', '</span>'], html_entity_decode($question->question)) !!}

                    
                    @if(Auth::guard('admin')->user()->hasPermissionTo('question.update'))
                        <div style="float: right;">
                            <button id="content_management" data-url="{{ route('portal.question.edit.short', $question->id) }}" class="btn btn-sm btn-primary btn-icon">
                                <i class="fas fa-edit"></i>
                            </button>
                        </div>
                    @endif 
                </h3>
                <div class="d-flex flex-wrap gap-2 my-2">
                    @if($question->category?->breadcrumb()->first())
                        <span class="badge" style="background-color: white; border: 1px solid #5e72e4; color: #5e72e4; padding: 5px 10px; border-radius: 5px; font-size: 11px;">
                            @if($question->category?->breadcrumb()->get(1) && $question->category?->breadcrumb()->get(1)->name != $question->category?->breadcrumb()->first()->name)
                                {{ $question->category?->breadcrumb()->first()->name }}: {{ $question->category?->breadcrumb()->get(1)->name }}
                            @else
                                {{ $question->category?->breadcrumb()->first()->name }}
                            @endif
                        </span>
                    @endif
                    @if($question->job_category)
                        <span class="badge" style="background-color: white; border: 1px solid #50cd89; color: #50cd89; padding: 5px 10px; border-radius: 5px; font-size: 11px;">{{ $question->job_category->name }}</span>
                    @endif
                    @if($question->exam)
                        <span class="badge" style="background-color: white; border: 1px solid #f39c12; color: #f39c12; padding: 5px 10px; border-radius: 5px; font-size: 11px;">{{ $question->exam->name }}</span>
                    @endif
                    @if($question->category)
                        <span class="badge" style="background-color: white; border: 1px solid #009ef7; color: #009ef7; padding: 5px 10px; border-radius: 5px; font-size: 11px;">{{ $question->category->name }}</span>
                    @endif
                </div>
            </div>
            @if ($question->options && $question->options->option_one != '')
                <div class="col-md-6" id="question_option_1_{{ $question->id }}">
                    <p>
                        @if ($question->correct_answer == 1)
                            <i class="fas fa-check fa-fw text-success"></i>
                        @else    
                            (a). 
                        @endif
                        {!! str_replace(['<p>', '</p>'], ['<span>', '</span>'], html_entity_decode($question->options->option_one)) !!}
                    </p>
                </div>
            @endif
            @if ($question->options && $question->options->option_two != '')
                <div class="col-md-6" id="question_option_2_{{ $question->id }}">
                    <p>
                        @if ($question->correct_answer == 2)
                            <i class="fas fa-check fa-fw text-success"></i>
                        @else    
                            (b). 
                        @endif
                        {!! str_replace(['<p>', '</p>'], ['<span>', '</span>'], html_entity_decode($question->options->option_two)) !!}
                        {{-- {!! html_entity_decode($question->options->option_two) !!} --}}
                    </p>
                </div>
            @endif
            @if ($question->options && $question->options->option_three != '')
                <div class="col-md-6" id="question_option_3_{{ $question->id }}">
                    <p>
                        @if ($question->correct_answer == 3)
                            <i class="fas fa-check fa-fw text-success"></i>
                        @else    
                            (c). 
                        @endif
                        {!! str_replace(['<p>', '</p>'], ['<span>', '</span>'], html_entity_decode($question->options->option_three)) !!}
                        {{-- {!! html_entity_decode($question->options->option_three) !!} --}}
                    </p>
                </div>
            @endif
            @if ($question->options && $question->options->option_four != '')
                <div class="col-md-6" id="question_option_4_{{ $question->id }}">
                    <p>
                        @if ($question->correct_answer == 4)
                            <i class="fas fa-check fa-fw text-success"></i>
                        @else    
                            (d). 
                        @endif
                        {!! str_replace(['<p>', '</p>'], ['<span>', '</span>'], html_entity_decode($question->options->option_four)) !!}
                        {{-- {!! html_entity_decode($question->options->option_four) !!} --}}
                    </p>
                </div>
            @endif
            @if ($question->options && $question->options->option_five != '')
                <div class="col-md-6" id="question_option_4_{{ $question->id }}">
                    <p>
                        @if ($question->correct_answer == 4)
                            <i class="fas fa-check fa-fw text-success"></i>
                        @else    
                            (e). 
                        @endif
                        {!! str_replace(['<p>', '</p>'], ['<span>', '</span>'], html_entity_decode($question->options->option_five)) !!}
                        {{-- {!! html_entity_decode($question->options->option_five) !!} --}}
                    </p>
                </div>
            @endif
        </div>
    </div>
@endforeach
