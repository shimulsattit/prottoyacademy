@extends('layouts.admin', ['title' => 'Update Question Information'])
@push('style')
    <link rel="stylesheet" href="{{ asset('portal-resource/css/dropify.min.css') }}">
@endpush
@section('content')
<div class="app-main flex-column flex-row-fluid" id="app_main">
    <div class="d-flex flex-column flex-column-fluid">
        <div id="app_toolbar" class="app-toolbar py-3 py-lg-6">
            <div id="app_toolbar_container" class="app-container container-xxl d-flex flex-stack">
                <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
                    <h1 class="page-heading d-flex text-gray-900 fw-bold fs-3 flex-column justify-content-center my-0">
                        Update Question Information
                    </h1>
                    <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">
                        <li class="breadcrumb-item text-muted">
                            <a href="{{ route('portal.dashboard') }}" class="text-muted text-hover-primary">Home</a>
                        </li>
                        <li class="breadcrumb-item">
                            <span class="bullet bg-gray-500 w-5px h-2px"></span>
                        </li>
                        @if(Auth::guard('admin')->user()->hasPermissionTo('question.view'))
                            <li class="breadcrumb-item text-muted">
                                <a href="{{ route('portal.question.index') }}" class="text-muted text-hover-primary">Question Management</a>
                            </li>
                        @endif
                        <li class="breadcrumb-item">
                            <span class="bullet bg-gray-500 w-5px h-2px"></span>
                        </li>
                        <li class="breadcrumb-item text-muted">Update Question Information</li>
                    </ul>
                </div>
            </div>
        </div>

        <div id="app_content" class="app-content flex-column-fluid">
            <div id="app_content_container" class="app-container container-xxl">
                <div class="card">
                    <div class="card-body">
                        <form data-editor="description" action="{{ route('portal.question.update', $model->uuid) }}" method="POST" enctype="multipart/form-data" class="content_form">
                            @method('PATCH')
                            <div class="row">

                                <div class="col-md-4 form-group mb-3">
                                    <label for="category_id">Category <span class="text-danger">*</span></label>
                                    <select name="category_id[]" id="category_id" class="form-control category_id007" required data-parsley-errors-container="#category_id_error">
                                        @if ($model->category_id)
                                            <option value="{{ $model->category_id }}" selected>{{ $model->category->name }}</option>
                                        @endif
                                    </select>
                                    <span id="category_id_error"></span>
                                </div>

                                <div class="Sub_Categories row"></div>

                                <div class="col-md-4 form-group mb-3">
                                    <label for="question_type">Question Type <span class="text-danger">*</span></label>
                                    <select name="question_type" id="question_type" class="form-control select" data-placeholder="Select One" data-parsley-errors-container="#question_type_error" required data-minimum-results-for-search="Infinity">
                                        <option value="">Select One</option>
                                        <option {{ $model->question_type == 'mcq' ? 'selected' : '' }} value="mcq">MCQ (Multiple Choice Question)</option>
                                        <option {{ $model->question_type == 'short_answer' ? 'selected' : '' }} value="short_answer">Short Answer</option>
                                        <option {{ $model->question_type == 'long_answer' ? 'selected' : '' }} value="long_answer">Long Answer</option>
                                        <option {{ $model->question_type == 'true_false' ? 'selected' : '' }} value="true_false">True/ False</option>
                                        <option {{ $model->question_type == 'fill_in_the_blanks' ? 'selected' : '' }} value="fill_in_the_blanks">Fill in the Blanks</option>
                                        <option {{ $model->question_type == 'matching' ? 'selected' : '' }} value="matching">Matching</option>
                                    </select>
                                </div>
                                
                                <div class="col-md-4 form-group mb-3">
                                    <label for="hard_level">Hard Level <span class="text-danger">*</span></label>
                                    <select name="hard_level" id="hard_level" class="form-control select" data-placeholder="Select One" data-parsley-errors-container="#hard_level_error" required data-minimum-results-for-search="Infinity">
                                        <option value="">Select One</option>
                                        <option {{ $model->hard_level == 'easy' ? 'selected' : '' }} value="easy">Easy</option>
                                        <option {{ $model->hard_level == 'medium' ? 'selected' : '' }} value="medium">Medium</option>
                                        <option {{ $model->hard_level == 'hard' ? 'selected' : '' }} value="hard">Hard</option>
                                        <option {{ $model->hard_level == 'very_hard' ? 'selected' : '' }} value="very_hard">Very Hard</option>
                                    </select>
                                </div>

                                <div class="col-md-4 form-group mb-3">
                                    <label for="job_category_id">Job Category</label>
                                    <select name="job_category_id" id="job_category_id" class="form-control" data-parsley-errors-container="#job_category_id_error">
                                        @if ($model->job_category_id)
                                            <option selected value="{{ $model->job_category_id }}">{{ $model->job_category->name }}</option>
                                        @endif
                                    </select>
                                    <span id="job_category_id_error"></span>
                                </div>

                                <div class="col-md-4 form-group mb-3">
                                    <label for="year_id">Year</label>
                                    <select name="year_id" {{ $model->year ? '' : 'disabled' }} id="year_id" class="form-control" data-parsley-errors-container="#year_id_error">
                                        @if ($model->year_id)
                                            <option selected value="{{ $model->year_id }}">{{ $model->year->name }}</option>
                                        @endif
                                    </select>
                                    <span id="year_id_error"></span>
                                </div>

                                <div class="col-md-4 form-group mb-3">
                                    <label for="exam_id">Exam</label>
                                    <select name="exam_id" {{ $model->exam ? '' : 'disabled' }} id="exam_id" class="form-control" data-parsley-errors-container="#exam_id_error">
                                        @if ($model->exam_id)
                                            <option selected value="{{ $model->exam_id }}">{{ $model->exam->name }}</option>
                                        @endif
                                    </select>
                                    <span id="exam_id_error"></span>
                                </div>

                                <div class="col-md-4 form-group mb-3">
                                    <label for="question_mark">Question Mark <span class="text-danger">*</span></label>
                                    <input type="text" name="question_mark" id="question_mark" class="form-control number" value="{{ $model->question_mark }}" required>
                                </div>
                                
                                <div class="col-md-4 form-group mb-3">
                                    <label for="status">Status <span class="text-danger">*</span></label>
                                    <select name="status" id="status" class="form-control select" required data-minimum-results-for-search="Infinity">
                                        <option {{ $model->status == 1 ? 'selected' : '' }} value="1">Publish</option>
                                        <option {{ $model->status == 0 ? 'selected' : '' }} value="0">Unpublish</option>
                                    </select>
                                </div>

                                <div class="col-md-4 form-group mb-3">
                                    <label for="passage_id">Passage </label>
                                    <select name="passage_id" id="passage_id" class="form-control" data-parsley-errors-container="#passage_id_error">
                                        @if ($model->passage_id)
                                            <option value="{{ $model->passage_id }}" selected>{{ $model->passage->name }}</option>
                                        @endif
                                    </select>
                                    <span id="passage_id_error"></span>
                                </div>

                                <div class="col-md-6 mb-3 form-group">
                                    <label for="question">Question <span class="text-danger">*</span></label>
                                    <input type="text" name="question" id="name" class="form-control" required value="{{ $model->question }}">
                                </div>

                                <div class="col-md-6 mb-3 form-group">
                                    <label for="slug">Slug <span class="text-danger">*</span></label>
                                    <input type="text" name="slug" id="slug" class="form-control" required value="{{ $model->slug }}">
                                </div>

                                @if ($model->question_type == 'mcq')
                                    <div class="col-md-12 form-group mb-3">
                                        <label for="correct_answer">Correct Answer <span class="text-danger">*</span></label>
                                        <select name="correct_answer" required id="correct_answer" class="form-control select">
                                            <option {{ $model->correct_answer == 1 ? 'selected' : '' }} value="1">Option One</option>
                                            <option {{ $model->correct_answer == 2 ? 'selected' : '' }} value="2">Option Two</option>
                                            <option {{ $model->correct_answer == 3 ? 'selected' : '' }} value="3">Option Three</option>
                                            <option {{ $model->correct_answer == 4 ? 'selected' : '' }} value="4">Option Four</option>
                                        </select>
                                    </div>
                                @elseif($model->question_type == 'short_answer' || $model->question_type == 'long_answer')
                                    <div class="col-md-12 form-group mb-3">
                                        <label for="correct_answer">Correct Answer <span class="text-danger">*</span></label>
                                        <textarea name="correct_answer" id="correct_answer" cols="30" rows="3" class="form-control">{{ $model->correct_answer }}</textarea>
                                    </div>
                                @endif
                                

                                <div class="col-md-12 mb-3 form-group">
                                    <label for="description">Description <span class="text-danger">*</span></label>
                                    <textarea name="description" id="description" cols="30" rows="3" class="form-control">{{ $model->content }}</textarea>
                                </div>

                                @if ($model->question_type == 'mcq')
                                <div class="col-md-3 form-group mb-3">
                                    <label for="option_one">Option One</label>
                                    <textarea name="option_one" id="option_one" cols="30" rows="3" class="form-control" required>{{ $model->options ? $model->options->option_one : '' }}</textarea>
                                </div>

                                <div class="col-md-3 form-group mb-3">
                                    <label for="option_two">Option Two</label>
                                    <textarea name="option_two" id="option_two" cols="30" rows="3" class="form-control" required>{{ $model->options ? $model->options->option_two : ''}}</textarea>
                                </div>

                                <div class="col-md-3 form-group mb-3">
                                    <label for="option_three">Option Three</label>
                                    <textarea name="option_three" id="option_three" cols="30" rows="3" class="form-control">{{ $model->options ? $model->options->option_three : '' }}</textarea>
                                </div>

                                <div class="col-md-3 form-group mb-3">
                                    <label for="option_four">Option Four</label>
                                    <textarea name="option_four" id="option_four" cols="30" rows="3" class="form-control">{{ $model->options ? $model->options->option_four : '' }}</textarea>
                                </div>
                                @endif
                                
                                <div class="col-md-12 form-group mb-3">
                                    <label for="site_title">Site Title <span class="text-danger">*</span></label>
                                    <input type="text" name="site_title" id="site_title" class="form-control" required value="{{ $model->site_title }}">
                                </div>
                                
                                <div class="col-md-12 form-group mb-3">
                                    <label for="meta_title">Meta Title <span class="text-danger">*</span></label>
                                    <input type="text" name="meta_title" id="meta_title" class="form-control" required value="{{ $model->meta_title }}">
                                </div>

                                <div class="col-md-12 mb-3 form-group">
                                    <label for="meta_keywords">Meta Keywords</label>
                                    <textarea name="meta_keywords" id="meta_keywords" cols="30" rows="3" class="form-control">{{ $model->meta_keywords }}</textarea>
                                </div>

                                <div class="col-md-12 mb-3 form-group">
                                    <label for="meta_description">Meta Description</label>
                                    <textarea name="meta_description" id="meta_description" cols="30" rows="3" class="form-control">{{ $model->meta_description }}</textarea>
                                </div>

                                <div class="col-md-12 mb-3 form-group">
                                    <label for="meta_article_tag">Google Schema</label>
                                    <textarea name="meta_article_tag" id="meta_article_tag" cols="30" rows="3" class="form-control">{{ $model->meta_article_tag }}</textarea>
                                </div>

                                @if(Auth::guard('admin')->user()->hasPermissionTo('question.update'))
                                    <div class="col-md-12 mb-3 text-center">
                                        <button type="submit" class="btn btn-sm btn-block btn-primary" id="submit">
                                            <i class="fas fa-paper-plane fa-fw"></i> Update Question    
                                        </button>
                                        <button type="button" class="btn btn-sm btn-block btn-outline-primary" id="submitting" style="display: none;">
                                            <i class="fas fa-spinner fa-spin fa-fw"></i> Processing    
                                        </button>
                                    </div>
                                @endif
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/ckeditor/4.9.2/ckeditor.js"></script>    
    <script>
        _componentSelect();
        _formValidation();

        let _initCkEditor = function(editorName, startupFocus = false, editorHeight = false) {
            CKEDITOR.replace(editorName, {
                // filebrowserUploadUrl: 'ck_upload.php', //Later
                filebrowserUploadMethod: 'form',
                height: editorHeight ? editorHeight : '',
                startupFocus: startupFocus == 1 ? true : false,
                removePlugins: 'exportpdf',
                toolbar: [
                    ['Format', 'Font', 'FontSize', '-'],
                    ['Bold', 'Italic', 'Underline', 'Table', '-', 'NumberedList', 'BulletedList', '-'],
                    ["JustifyLeft", "JustifyCenter", "JustifyRight", "JustifyBlock"],
                    ['Link', 'Blockquote', 'Maximize', 'Image', 'TextColor', '-', 'Source']
                ],
                contentsCss: [
                    'https://cdn.ckeditor.com/4.16.0/standard-all/contents.css',
                ],
                bodyClass: 'ckeditor-dark-mode', 
            });
        }

        _initCkEditor('description');

        // Function to convert name to slug
        function generateSlug(name) {
            return name
                .toString()
                .toLowerCase()
                .trim()
                .replace(/&/g, '-and-') // Replace & with 'and'
                .replace(/[^\p{L}\p{N}\s-]/gu, '') // Remove invalid characters
                .replace(/\s+/g, '-') // Replace spaces with -
                .replace(/-+/g, '-'); // Replace multiple - with single -
        }

        $('#name').on('input', function() {
            const name = $(this).val();
            const slug = generateSlug(name);
            $('#slug').val(slug);

            // Check if the slug exists
            $.ajax({
                url: '{{ route('slug.check') }}',
                type: 'GET',
                data: {
                    slug: slug,
                    id: '{{ $model->id }}',
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    if (response.exists) {
                        const timestamp = Date.now();
                        $('#slug').val(slug + '-' + timestamp);
                    }
                }
            });
        });

        $('#job_category_id').select2({
            width: '100%',
            placeholder: 'Select Job Category',
            ajax: {
                url: '/search/job-category',
                method: 'POST',
                dataType: 'JSON',
                delay: 250,
                cache: true,
                data: function (data) {
                    return {
                        searchTerm: data.term
                    };
                },

                processResults: function (response) {
                    return {
                        results:response
                    };
                }
            }
        });

        $('#year_id').select2({
            width: '100%',
            placeholder: 'Select Year',
            ajax: {
                url: '/search/year',
                method: 'POST',
                dataType: 'JSON',
                delay: 250,
                cache: true,
                data: function (data) {
                    return {
                        searchTerm: data.term
                    };
                },

                processResults: function (response) {
                    return {
                        results:response
                    };
                }
            }
        });

        $('.category_id007').select2({
            width: '100%',
            placeholder: 'Select category',
            ajax: {
                url: '/search/category',
                method: 'POST',
                dataType: 'JSON',
                delay: 250,
                cache: true,
                data: function (data) {
                    return {
                        searchTerm: data.term
                    };
                },

                processResults: function (response) {
                    return {
                        results:response
                    };
                }
            }
        });

        $('#category_id').change(function() {
            const categoryId = $(this).val();
            $('.Sub_Categories').empty();
            $('.specification_key').empty();
            $('#add-another').hide();

            if (categoryId) {
                fetchSubCategories(categoryId, 1, $(this));
            }
        });
    </script>
@endpush
