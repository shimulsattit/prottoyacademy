@extends('layouts.admin', ['title' => 'Update Question Information'])
@php
    $categoryBreadcrumb = collect([]);
    if ($model->category_id) {
        $categoryBreadcrumb = $model->category->breadcrumb();
    }
    
    // Academy Custom Form Detection
    $isAcademy = false;
    $preSelectedClass = null;
    $preSelectedChapter = null;
    $preSelectedSubject = $model->job_category;

    if ($model->category_id) {
        if ($model->category_id == 783 || $categoryBreadcrumb->contains('id', 783)) {
            $isAcademy = true;
            // Identify Class and Chapter in the path
            $academyIndex = $categoryBreadcrumb->search(function($c) { return $c->id == 783; });
            if ($academyIndex !== false) {
                if (isset($categoryBreadcrumb[$academyIndex + 1])) {
                    $preSelectedClass = $categoryBreadcrumb[$academyIndex + 1];
                }
                if (isset($categoryBreadcrumb[$academyIndex + 2])) {
                    $preSelectedChapter = $categoryBreadcrumb[$academyIndex + 2];
                }
            }
        }
    }
@endphp
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

                                @if($isAcademy)
                                    <!-- Academy Custom Selection UI -->
                                    <input type="hidden" name="is_academy" value="1">
                                    <input type="hidden" name="category_id[]" id="academy_final_category_id" value="{{ $preSelectedChapter ? $preSelectedChapter->id : ($preSelectedClass ? $preSelectedClass->id : 783) }}">

                                    <div class="col-md-4 form-group mb-3">
                                        <label for="academy_class_id">শ্রেণি (Class) <span class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <select name="academy_class_id" id="academy_class_id" class="form-control select" required>
                                                <option value="">-- শ্রেণি সিলেক্ট করুন --</option>
                                                @foreach(\App\Models\Category::where('parent_id', 783)->where('status', 1)->get() as $cls)
                                                    <option value="{{ $cls->id }}" {{ ($preSelectedClass && $preSelectedClass->id == $cls->id) ? 'selected' : '' }}>{{ $cls->name }}</option>
                                                @endforeach
                                            </select>
                                            <button type="button" class="btn btn-primary" onclick="openQuickAddClassModal()"><i class="fas fa-plus"></i></button>
                                        </div>
                                    </div>

                                    <div class="col-md-4 form-group mb-3">
                                        <label for="academy_subject_id">বিষয় (Subject) <span class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <select name="job_category_id" id="academy_subject_id" class="form-control select" required>
                                                <option value="">-- বিষয় সিলেক্ট করুন --</option>
                                                @if($preSelectedClass)
                                                    @foreach(\App\Models\JobCategory::where('category_id', $preSelectedClass->id)->where('status', 1)->get() as $sub)
                                                        <option value="{{ $sub->id }}" {{ ($preSelectedSubject && $preSelectedSubject->id == $sub->id) ? 'selected' : '' }}>{{ $sub->name }}</option>
                                                    @endforeach
                                                @endif
                                            </select>
                                            <button type="button" class="btn btn-primary" onclick="openQuickAddSubjectModal()"><i class="fas fa-plus"></i></button>
                                        </div>
                                    </div>

                                    <div class="col-md-4 form-group mb-3">
                                        <label for="academy_chapter_id">অধ্যায় (Chapter)</label>
                                        <div class="input-group">
                                            <select name="academy_chapter_id" id="academy_chapter_id" class="form-control select">
                                                <option value="">-- অধ্যায় সিলেক্ট করুন --</option>
                                                @if($preSelectedClass)
                                                    @foreach(\App\Models\Category::where('parent_id', $preSelectedClass->id)->where('status', 1)->get() as $chap)
                                                        <option value="{{ $chap->id }}" {{ ($preSelectedChapter && $preSelectedChapter->id == $chap->id) ? 'selected' : '' }}>{{ $chap->name }}</option>
                                                    @endforeach
                                                @endif
                                            </select>
                                            <button type="button" class="btn btn-primary" onclick="openQuickAddChapterModal()"><i class="fas fa-plus"></i></button>
                                        </div>
                                    </div>

                                    <div class="col-md-4 form-group mb-3">
                                        <label for="question_type">Question Type <span class="text-danger">*</span></label>
                                        <select name="question_type" id="question_type" class="form-control select" data-placeholder="Select One" data-parsley-errors-container="#question_type_error" required data-minimum-results-for-search="Infinity">
                                            <option value="">Select One</option>
                                            <option {{ $model->question_type == 'mcq' ? 'selected' : '' }} value="mcq">MCQ (Multiple Choice Question)</option>
                                            <option {{ $model->question_type == 'cq' ? 'selected' : '' }} value="cq">সৃজনশীল (CQ)</option>
                                            <option {{ $model->question_type == 'short_answer' ? 'selected' : '' }} value="short_answer">Short Answer</option>
                                            <option {{ $model->question_type == 'long_answer' ? 'selected' : '' }} value="long_answer">Long Answer</option>
                                            <option {{ $model->question_type == 'true_false' ? 'selected' : '' }} value="true_false">True/ False</option>
                                            <option {{ $model->question_type == 'fill_in_the_blanks' ? 'selected' : '' }} value="fill_in_the_blanks">Fill in the Blanks</option>
                                            <option {{ $model->question_type == 'matching' ? 'selected' : '' }} value="matching">Matching</option>
                                        </select>
                                    </div>
                                @else
                                    <!-- Standard Generic Category Inputs -->
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
                                        <label for="job_category_id">Job Category</label>
                                        <select name="job_category_id" id="job_category_id" class="form-control" data-parsley-errors-container="#job_category_id_error">
                                            @if ($model->job_category_id)
                                                <option selected value="{{ $model->job_category_id }}">{{ $model->job_category->name }}</option>
                                            @endif
                                        </select>
                                        <span id="job_category_id_error"></span>
                                    </div>
                                @endif

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

                                <!-- Correct Answer MCQ -->
                                <div class="col-md-12 form-group mb-3 mcq-answer" style="display: {{ $model->question_type == 'mcq' ? 'block' : 'none' }};">
                                    <label for="correct_answer_select">Correct Answer <span class="text-danger">*</span></label>
                                    <select name="correct_answer" id="correct_answer_select" class="form-control select" required {{ $model->question_type == 'mcq' ? '' : 'disabled' }}>
                                        <option {{ $model->correct_answer == 1 ? 'selected' : '' }} value="1">Option One</option>
                                        <option {{ $model->correct_answer == 2 ? 'selected' : '' }} value="2">Option Two</option>
                                        <option {{ $model->correct_answer == 3 ? 'selected' : '' }} value="3">Option Three</option>
                                        <option {{ $model->correct_answer == 4 ? 'selected' : '' }} value="4">Option Four</option>
                                    </select>
                                </div>

                                <!-- Correct Answer Non-MCQ -->
                                <div class="col-md-12 form-group mb-3 non-mcq-answer" style="display: {{ in_array($model->question_type, ['short_answer', 'long_answer', 'cq']) ? 'block' : 'none' }};">
                                    <label for="correct_answer_text">Correct Answer <span class="text-danger">*</span></label>
                                    <textarea name="correct_answer" id="correct_answer_text" cols="30" rows="3" class="form-control" required {{ in_array($model->question_type, ['short_answer', 'long_answer', 'cq']) ? '' : 'disabled' }}>{{ $model->correct_answer }}</textarea>
                                </div>
                                

                                <div class="col-md-12 mb-3 form-group">
                                    <label for="description">Description <span class="text-danger">*</span></label>
                                    <textarea name="description" id="description" cols="30" rows="3" class="form-control">{{ $model->content }}</textarea>
                                </div>

                                <!-- MCQ Options Wrapper -->
                                <div class="row w-100 m-0 p-0" id="mcq-options-wrapper" style="display: {{ $model->question_type == 'mcq' ? 'flex' : 'none' }};">
                                    <div class="col-md-3 form-group mb-3">
                                        <label for="option_one">Option One</label>
                                        <textarea name="option_one" id="option_one" cols="30" rows="3" class="form-control" required {{ $model->question_type == 'mcq' ? '' : 'disabled' }}>{{ $model->options ? $model->options->option_one : '' }}</textarea>
                                    </div>

                                    <div class="col-md-3 form-group mb-3">
                                        <label for="option_two">Option Two</label>
                                        <textarea name="option_two" id="option_two" cols="30" rows="3" class="form-control" required {{ $model->question_type == 'mcq' ? '' : 'disabled' }}>{{ $model->options ? $model->options->option_two : ''}}</textarea>
                                    </div>

                                    <div class="col-md-3 form-group mb-3">
                                        <label for="option_three">Option Three</label>
                                        <textarea name="option_three" id="option_three" cols="30" rows="3" class="form-control" {{ $model->question_type == 'mcq' ? '' : 'disabled' }}>{{ $model->options ? $model->options->option_three : '' }}</textarea>
                                    </div>

                                    <div class="col-md-3 form-group mb-3">
                                        <label for="option_four">Option Four</label>
                                        <textarea name="option_four" id="option_four" cols="30" rows="3" class="form-control" {{ $model->question_type == 'mcq' ? '' : 'disabled' }}>{{ $model->options ? $model->options->option_four : '' }}</textarea>
                                    </div>
                                </div>
                                
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
<!-- QUICK ADD CLASS MODAL -->
<div class="modal fade" id="quickAddClassModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">নতুন শ্রেণি যুক্ত করুন (Add New Class)</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="quickAddClassForm">
                <div class="modal-body">
                    <div class="form-group mb-3">
                        <label for="new_class_name">শ্রেণির নাম (Class Name) <span class="text-danger">*</span></label>
                        <input type="text" id="new_class_name" class="form-control" placeholder="যেমন: নবম শ্রেণি" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">বাতিল</button>
                    <button type="submit" class="btn btn-primary">সংরক্ষণ করুন</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- QUICK ADD SUBJECT MODAL -->
<div class="modal fade" id="quickAddSubjectModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">নতুন বিষয় যুক্ত করুন (Add New Subject)</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="quickAddSubjectForm">
                <div class="modal-body">
                    <div class="form-group mb-3">
                        <label for="new_subject_name">विषয়ের নাম (Subject Name) <span class="text-danger">*</span></label>
                        <input type="text" id="new_subject_name" class="form-control" placeholder="যেমন: গণিত" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">বাতিল</button>
                    <button type="submit" class="btn btn-primary">সংরক্ষণ করুন</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- QUICK ADD CHAPTER MODAL -->
<div class="modal fade" id="quickAddChapterModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">নতুন অধ্যায় যুক্ত করুন (Add New Chapter)</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="quickAddChapterForm">
                <div class="modal-body">
                    <div class="form-group mb-3">
                        <label for="new_chapter_name">অধ্যায়ের নাম (Chapter Name) <span class="text-danger">*</span></label>
                        <input type="text" id="new_chapter_name" class="form-control" placeholder="যেমন: প্রথম অধ্যায়" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">বাতিল</button>
                    <button type="submit" class="btn btn-primary">সংরক্ষণ করুন</button>
                </div>
            </form>
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

        // Question Type Dynamic Show/Hide Event
        $('#question_type').change(function() {
            let val = $(this).val();
            if(val == 'mcq') {
                $('.mcq-answer').show().find('select').prop('disabled', false).attr('required', true);
                $('.non-mcq-answer').hide().find('textarea').prop('disabled', true).removeAttr('required');
                $('#mcq-options-wrapper').show().find('textarea').prop('disabled', false);
                $('#option_one').attr('required', true);
                $('#option_two').attr('required', true);
            } else if(val == 'short_answer' || val == 'long_answer' || val == 'cq') {
                $('.mcq-answer').hide().find('select').prop('disabled', true).removeAttr('required');
                $('.non-mcq-answer').show().find('textarea').prop('disabled', false).attr('required', true);
                $('#mcq-options-wrapper').hide().find('textarea').prop('disabled', true).removeAttr('required');
            } else {
                $('.mcq-answer').hide().find('select').prop('disabled', true).removeAttr('required');
                $('.non-mcq-answer').hide().find('textarea').prop('disabled', true).removeAttr('required');
                $('#mcq-options-wrapper').hide().find('textarea').prop('disabled', true).removeAttr('required');
            }
        });
        
        // Trigger initial question type state check
        $('#question_type').trigger('change');

        // Fetch subcategories from backend for generic form
        function fetchSubCategories(parentId, level, parentSelect) {
            $.ajax({
                url: `/portal/question/create?parent_id=${parentId}`,
                type: 'GET',
                dataType: 'json',
                success: function (data) {
                    if (data.subs && data.subs.length > 0) {
                        appendSubCategories(data.subs, level, parentSelect);
                    }
                }
            });
        }

        // Append subcategories and bind change event for generic form
        function appendSubCategories(subCategories, level, parentSelect) {
            const subCategoryDiv = $('.Sub_Categories');

            const categoryGroup = $('<div>', {
                class: 'subcategory-group mb-3 col-md-4'
            });

            const select = $('<select>', {
                name: 'category_id[]',
                class: 'form-control select mb-2',
                required: true,
                'data-level': level
            });

            const selectLabel = '--Select ' + 'Sub '.repeat(level) + 'Category--';
            select.append(`<option value="" disabled selected>${selectLabel}</option>`);

            $.each(subCategories, function (index, sub) {
                select.append(`<option value="${sub.id}">${'-'.repeat(level)} ${sub.name}</option>`);
            });

            categoryGroup.append(select);
            subCategoryDiv.append(categoryGroup);

            // Initialize Select2
            select.select2();

            // Bind change event
            select.change(function () {
                const selectedSubCategoryId = $(this).val();
                const currentLevel = parseInt($(this).attr('data-level'));

                // Remove all subcategory selects below this level
                $('.Sub_Categories select').each(function () {
                    const level = parseInt($(this).attr('data-level'));
                    if (level > currentLevel) {
                        $(this).closest('.subcategory-group').remove();
                    }
                });

                // Fetch next level if selected
                if (selectedSubCategoryId) {
                    fetchSubCategories(selectedSubCategoryId, currentLevel + 1, $(this));
                }
            });
        }

        // --- ACADEMY DYNAMIC DEPENDENT DROPDOWNS & QUICK ADD ---
        @if($isAcademy)
            const token = $('meta[name="csrf-token"]').attr('content');

            // Setup select2 on new fields
            $('#academy_class_id').select2({ width: '100%' });
            $('#academy_subject_id').select2({ width: '100%' });
            $('#academy_chapter_id').select2({ width: '100%' });

            // Helper to update the final submitted category_id hidden input
            function updateAcademyFinalCategory() {
                const classId = $('#academy_class_id').val();
                const chapterId = $('#academy_chapter_id').val();
                
                if (chapterId) {
                    $('#academy_final_category_id').val(chapterId);
                } else if (classId) {
                    $('#academy_final_category_id').val(classId);
                } else {
                    $('#academy_final_category_id').val('783'); // Academy root fallback
                }
            }

            // On Class change, reload Subjects and Chapters
            $('#academy_class_id').change(function(e, isInitial) {
                const classId = $(this).val();
                
                if (isInitial === undefined) {
                    isInitial = false;
                }

                if (!isInitial) {
                    // Clear dependent selects
                    $('#academy_subject_id').empty().append('<option value="">-- বিষয় সিলেক্ট করুন --</option>').trigger('change');
                    $('#academy_chapter_id').empty().append('<option value="">-- অধ্যায় সিলেক্ট করুন --</option>').trigger('change');
                }
                
                updateAcademyFinalCategory();

                if (!classId) return;

                if (!isInitial) {
                    // 1. Fetch Chapters (Subcategories) under Class Category
                    $.ajax({
                        url: `/portal/question/create?parent_id=${classId}`,
                        type: 'GET',
                        dataType: 'JSON',
                        success: function(data) {
                            if (data.subs && data.subs.length > 0) {
                                $.each(data.subs, function(index, sub) {
                                    $('#academy_chapter_id').append(`<option value="${sub.id}">${sub.name}</option>`);
                                });
                                $('#academy_chapter_id').trigger('change');
                            }
                        }
                    });

                    // 2. Fetch Subjects (Job Categories) under Class Category
                    $.ajax({
                        url: '/search/job-category',
                        method: 'POST',
                        dataType: 'JSON',
                        data: {
                            searchTerm: '',
                            category_id: classId,
                            _token: token
                        },
                        success: function(response) {
                            if (response && response.length > 0) {
                                $.each(response, function(index, item) {
                                    $('#academy_subject_id').append(`<option value="${item.id}">${item.text}</option>`);
                                });
                                $('#academy_subject_id').trigger('change');
                            }
                        }
                    });
                }
            });

            // On Chapter change
            $('#academy_chapter_id').change(function() {
                updateAcademyFinalCategory();
            });

            // Open Modals
            window.openQuickAddClassModal = function() {
                $('#quickAddClassModal').modal('show');
            };

            window.openQuickAddSubjectModal = function() {
                const classId = $('#academy_class_id').val();
                if (!classId) {
                    toastr.warning('দয়া করে প্রথমে শ্রেণি সিলেক্ট করুন!');
                    return;
                }
                $('#quickAddSubjectModal').modal('show');
            };

            window.openQuickAddChapterModal = function() {
                const classId = $('#academy_class_id').val();
                if (!classId) {
                    toastr.warning('দয়া করে প্রথমে শ্রেণি সিলেক্ট করুন!');
                    return;
                }
                $('#quickAddChapterModal').modal('show');
            };

            // Helpers for Slug auto generation
            function generateQuickSlug(name) {
                return name
                    .toString()
                    .toLowerCase()
                    .trim()
                    .replace(/&/g, '-and-') 
                    .replace(/[^\p{L}\p{N}\s-]/gu, '') 
                    .replace(/\s+/g, '-') 
                    .replace(/-+/g, '-'); 
            }

            // Submit Add Class Form
            $('#quickAddClassForm').on('submit', function(e) {
                e.preventDefault();
                const name = $('#new_class_name').val();
                const slug = generateQuickSlug(name);

                $.ajax({
                    url: '/portal/category',
                    type: 'POST',
                    dataType: 'JSON',
                    data: {
                        parent_id: 783,
                        name: name,
                        slug: slug,
                        header: name,
                        description: name,
                        site_title: name,
                        meta_title: name,
                        status: 1,
                        _token: token
                    },
                    success: function(res) {
                        if (res.status && res.id) {
                            toastr.success('শ্রেণি সফলভাবে তৈরি হয়েছে!');
                            $('#quickAddClassModal').modal('hide');
                            $('#new_class_name').val('');
                            
                            // Append and select new class
                            const newOption = new Option(res.name, res.id, true, true);
                            $('#academy_class_id').append(newOption).trigger('change');
                        } else {
                            toastr.error(res.message || 'শ্রেণি তৈরি করতে ব্যর্থ হয়েছে।');
                        }
                    },
                    error: function(err) {
                        toastr.error('শ্রেণি তৈরি করতে কোনো সমস্যা হয়েছে।');
                    }
                });
            });

            // Submit Add Subject Form
            $('#quickAddSubjectForm').on('submit', function(e) {
                e.preventDefault();
                const name = $('#new_subject_name').val();
                const slug = generateQuickSlug(name);
                const classId = $('#academy_class_id').val();

                $.ajax({
                    url: '/portal/job-category',
                    type: 'POST',
                    dataType: 'JSON',
                    data: {
                        category_id: classId,
                        name: name,
                        slug: slug,
                        description: name,
                        site_title: name,
                        meta_title: name,
                        status: 1,
                        _token: token
                    },
                    success: function(res) {
                        if (res.status && res.id) {
                            toastr.success('বিষয় সফলভাবে তৈরি হয়েছে!');
                            $('#quickAddSubjectModal').modal('hide');
                            $('#new_subject_name').val('');
                            
                            // Append and select new subject
                            const newOption = new Option(res.name, res.id, true, true);
                            $('#academy_subject_id').append(newOption).trigger('change');
                        } else {
                            toastr.error(res.message || 'বিষয় তৈরি করতে ব্যর্থ হয়েছে।');
                        }
                    },
                    error: function(err) {
                        toastr.error('বিষয় তৈরি করতে কোনো সমস্যা হয়েছে।');
                    }
                });
            });

            // Submit Add Chapter Form
            $('#quickAddChapterForm').on('submit', function(e) {
                e.preventDefault();
                const name = $('#new_chapter_name').val();
                const slug = generateQuickSlug(name);
                const classId = $('#academy_class_id').val();

                $.ajax({
                    url: '/portal/category',
                    type: 'POST',
                    dataType: 'JSON',
                    data: {
                        parent_id: classId,
                        name: name,
                        slug: slug,
                        header: name,
                        description: name,
                        site_title: name,
                        meta_title: name,
                        status: 1,
                        _token: token
                    },
                    success: function(res) {
                        if (res.status && res.id) {
                            toastr.success('অধ্যায় সফলভাবে তৈরি হয়েছে!');
                            $('#quickAddChapterModal').modal('hide');
                            $('#new_chapter_name').val('');
                            
                            // Append and select new chapter
                            const newOption = new Option(res.name, res.id, true, true);
                            $('#academy_chapter_id').append(newOption).trigger('change');
                        } else {
                            toastr.error(res.message || 'অধ্যায় তৈরি করতে ব্যর্থ হয়েছে।');
                        }
                    },
                    error: function(err) {
                        toastr.error('অধ্যায় তৈরি করতে কোনো সমস্যা হয়েছে।');
                    }
                });
            });

            // Initialize pre-selected options dynamically
            $('#academy_class_id').trigger('change', [true]);
        @endif
    </script>
@endpush
