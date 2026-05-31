@extends('layouts.admin', ['title' => 'Import Question'])
@php
    $preSelectedCategory = null;
    $categoryBreadcrumb = collect([]);
    if (request()->has('category_id')) {
        $preSelectedCategory = \App\Models\Category::find(request('category_id'));
        if ($preSelectedCategory) {
            $categoryBreadcrumb = $preSelectedCategory->breadcrumb();
        }
    }
    $preSelectedJobCategory = null;
    if (request()->has('job_category_id')) {
        $preSelectedJobCategory = \App\Models\JobCategory::find(request('job_category_id'));
    }
    $isCurrentAffairs = false;
    if ($categoryBreadcrumb->count() > 0) {
        $isCurrentAffairs = $categoryBreadcrumb->contains('id', 312);
    }
@endphp
@push('style')
<link href="https://cdn.datatables.net/1.11.4/css/dataTables.bootstrap5.min.css" rel="stylesheet">
@endpush
@section('content')
<div class="app-main flex-column flex-row-fluid" id="app_main">
    <div class="d-flex flex-column flex-column-fluid">
        <div id="app_toolbar" class="app-toolbar py-3 py-lg-6">
            <div id="app_toolbar_container" class="app-container container-xxl d-flex flex-stack">
                <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
                    <h1 class="page-heading d-flex text-gray-900 fw-bold fs-3 flex-column justify-content-center my-0">
                        Import Question
                    </h1>
                    <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">
                        <li class="breadcrumb-item text-muted">
                            <a href="{{ route('portal.dashboard') }}" class="text-muted text-hover-primary">Home</a>
                        </li>
                        <li class="breadcrumb-item">
                            <span class="bullet bg-gray-500 w-5px h-2px"></span>
                        </li>
                        <li class="breadcrumb-item text-muted">Import Question</li>
                    </ul>
                </div>

                <div class="d-flex align-items-center gap-2 gap-lg-3">
                    @if($isCurrentAffairs)
                        <a href="{{ asset('current-affairs-mcq-demo.xlsx') }}" download class="btn btn-sm fw-bold btn-primary">
                            <i class="fas fa-file-excel me-1"></i> Current Affairs MCQ File
                        </a>
                        <a href="{{ asset('current-affairs-short-demo.xlsx') }}" download class="btn btn-sm fw-bold btn-primary">
                            <i class="fas fa-file-excel me-1"></i> Current Affairs Short File
                        </a>
                    @else
                        <a href="{{ asset('question-demo.xlsx') }}" download class="btn btn-sm fw-bold btn-primary">
                            MCQ Question File
                        </a>
                        <a href="{{ asset('short-question-demo.xlsx') }}" download class="btn btn-sm fw-bold btn-primary">
                            Short Question File
                        </a>
                    @endif
                </div>
            </div>
        </div>

        <div id="app_content" class="app-content flex-column-fluid">
            <div id="app_content_container" class="app-container container-xxl">
                <form action="{{ route('portal.question.import') }}" method="POST" enctype="multipart/form-data" class="content_form">
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-12 form-group mb-3">
                                    <label for="category_id">Category <span class="text-danger">*</span></label>
                                    <select name="category_id[]" id="category_id" class="form-control category_id007 select">
                                        @if($categoryBreadcrumb->count() > 0)
                                            <option value="{{ $categoryBreadcrumb->first()->id }}" selected>{{ $categoryBreadcrumb->first()->name }}</option>
                                        @endif
                                    </select>
                                </div>

                                <div class="Sub_Categories row">
                                    @if($categoryBreadcrumb->count() > 1)
                                        @foreach($categoryBreadcrumb as $index => $cat)
                                            @if($index > 0)
                                                @php
                                                    $siblings = \App\Models\Category::where('parent_id', $categoryBreadcrumb[$index - 1]->id)->where('status', 1)->get();
                                                @endphp
                                                <div class="subcategory-group mb-3">
                                                    <select name="category_id[]" class="form-control select mb-2" required data-level="{{ $index }}">
                                                        <option value="" disabled>--Select Sub Category--</option>
                                                        @foreach($siblings as $sibling)
                                                            <option value="{{ $sibling->id }}" {{ $sibling->id == $cat->id ? 'selected' : '' }}>
                                                                {{ str_repeat('-', $index) }} {{ $sibling->name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            @endif
                                        @endforeach
                                    @endif
                                </div>

                                <div class="col-md-12 form-group mb-3">
                                    <label for="question_type">Question Type <span class="text-danger">*</span></label>
                                    <select name="question_type" id="question_type" class="form-control select" data-placeholder="Select One" data-parsley-errors-container="#question_type_error" required data-minimum-results-for-search="Infinity">
                                        <option value="">Select One</option>
                                        <option selected value="mcq">MCQ (Multiple Choice Question)</option>
                                        <option value="short_answer">Short Answer</option>
                                        <option value="long_answer">Long Answer</option>
                                        <option disabled value="true_false">True/ False</option>
                                        <option disabled value="fill_in_the_blanks">Fill in the Blanks</option>
                                        <option disabled value="matching">Matching</option>
                                    </select>
                                </div>

                                @if($isCurrentAffairs)
                                    <div class="col-md-12 mb-3">
                                        <div class="alert alert-dismissible bg-light-primary border border-primary d-flex flex-column flex-sm-row w-100 p-5 mb-5" style="background-color: rgba(94, 114, 228, 0.08); border-color: rgba(94, 114, 228, 0.3) !important;">
                                            <span class="svg-icon svg-icon-2hx svg-icon-primary me-4 mb-5 mb-sm-0">
                                                <i class="fas fa-info-circle fs-2hx text-primary"></i>
                                            </span>
                                            <div class="d-flex flex-column pe-0 pe-sm-10">
                                                <h5 class="mb-1 text-primary fw-bold" style="color: #5e72e4 !important;">Current Affairs Automatic Mapping</h5>
                                                <span class="text-gray-800 fs-7">For Current Affairs questions, the Job Category and all parent mappings are automatically parsed from your Excel sheet columns on import. No manual selections are required!</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4 form-group mb-3">
                                        <label for="job_category_id_display" class="fw-semibold text-gray-700 fs-7 mb-1">Job Category</label>
                                        <input type="text" id="job_category_id_display" class="form-control" value="Auto-imported from Excel sheet" disabled style="background: #f1f3f9;">
                                        <input type="hidden" name="job_category_id" value="">
                                    </div>
                                @else
                                    <div class="col-md-4 form-group mb-3">
                                        <label for="job_category_id">Job Category</label>
                                        <select name="job_category_id" id="job_category_id" class="form-control" data-parsley-errors-container="#job_category_id_error">
                                            @if($preSelectedJobCategory)
                                                <option value="{{ $preSelectedJobCategory->id }}" selected>{{ $preSelectedJobCategory->name }}</option>
                                            @endif
                                        </select>
                                        <span id="job_category_id_error"></span>
                                    </div>
                                @endif

                                <div class="col-md-4 form-group mb-3">
                                    <label for="year_id">Year</label>
                                    <select name="year_id" id="year_id" class="form-control" data-parsley-errors-container="#year_id_error"></select>
                                    <span id="year_id_error"></span>
                                </div>

                                <div class="col-md-4 form-group mb-3">
                                    <label for="exam_id">Exam</label>
                                    <select name="exam_id" id="exam_id" class="form-control select" data-parsley-errors-container="#exam_id_error" data-placeholder="Select one">
                                        <option value="">Select one</option>
                                    </select>
                                    <span id="exam_id_error"></span>
                                </div>
                                
                                <div class="col-md-6 form-group mb-3">
                                    <label for="status">Status <span class="text-danger">*</span></label>
                                    <select name="status" id="status" class="form-control select" required>
                                        <option value="1">Publish</option>
                                        <option value="0">Unpublish</option>
                                    </select>
                                </div>

                                <div class="col-md-6 form-group mb-3">
                                    <label for="passage_id">Passage </label>
                                    <select name="passage_id" id="passage_id" class="form-control" data-parsley-errors-container="#passage_id_error"></select>
                                    <span id="passage_id_error"></span>
                                </div>

                                <div class="col-md-6 form-group mb-3">
                                    <label for="hard_level">Hard Level <span class="text-danger">*</span></label>
                                    <select name="hard_level" id="hard_level" class="form-control select" data-placeholder="Select One" data-parsley-errors-container="#hard_level_error" required data-minimum-results-for-search="Infinity">
                                        <option value="">Select One</option>
                                        <option selected value="easy">Easy</option>
                                        <option value="medium">Medium</option>
                                        <option value="hard">Hard</option>
                                        <option value="very_hard">Very Hard</option>
                                    </select>
                                </div>

                                <div class="col-md-6 form-group mb-3">
                                    <label for="question_mark">Question Mark <span class="text-danger">*</span></label>
                                    <input type="text" name="question_mark" id="question_mark" class="form-control number" value="1" required>
                                </div>

                                <div class="col-md-12 form-group mb-3">
                                    <label for="file">File <span class="text-danger">*</span></label>
                                    <input type="file" required name="file" id="file" class="form-control" accept=".xlsx">
                                    <small class="text-danger">Only Excel file allowed. Max 2 MB.</small>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div id="data-area"></div>

                    @if(Auth::guard('admin')->user()->hasPermissionTo('question.import'))
                        <div class="card mt-3">
                            <div class="card-body">
                                <div class="col-md-12 mb-3 text-center">
                                    <button type="submit" class="btn btn-sm btn-block btn-primary" id="submit">
                                        <i class="fas fa-paper-plane fa-fw"></i> Fetch Question    
                                    </button>
                                    <button type="button" class="btn btn-sm btn-block btn-outline-primary" id="submitting" style="display: none;">
                                        <i class="fas fa-spinner fa-spin fa-fw"></i> Processing    
                                    </button>
                                </div>
                            </div>
                        </div>
                    @endif 
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
    <script>
        _componentSelect();
        

        $(document).on('change', '#question_type', function() {
            let val = $(this).val();
            if(val == 'mcq') {
                $('#correct_answer_area').show();
            } else {
                $('#correct_answer_area').hide();
            }
        })

        // Initialize main category select2
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
                        searchTerm: data.term,
                        _token: $('meta[name="csrf-token"]').attr('content')
                    };
                },
                processResults: function (response) {
                    return {
                        results: response
                    };
                }
            }
        });

        let categoryIdArray = {!! $categoryBreadcrumb->count() > 0 ? json_encode($categoryBreadcrumb->pluck('id')->toArray()) : '[]' !!};
        let categoryIdString = categoryIdArray.join(',');

        @if($preSelectedCategory && $categoryBreadcrumb->count() === 1)
            // Trigger change event to load subcategories automatically on load ONLY if it is a root category!
            $('#category_id').trigger('change');
        @endif

        // Initialize existing subcategory select boxes (pre-rendered via Blade)
        $('.Sub_Categories select').each(function () {
            $(this).select2();
            
            // Bind change event
            $(this).change(function () {
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
        });

        // Main category change event
        $('#category_id').change(function () {
            const categoryId = $(this).val();
            $('.Sub_Categories').empty(); 
            categoryIdArray = [];
            $('#add-another').hide();

            if (categoryId) {
                fetchSubCategories(categoryId, 1, $(this));
            }
        });

        // Fetch subcategories from backend
        function fetchSubCategories(parentId, level, parentSelect) {
            // Trim category ID array if going back to an earlier level
            categoryIdArray = categoryIdArray.slice(0, level - 1);

            if (!categoryIdArray.includes(parentId)) {
                categoryIdArray.push(parentId);
            }

            categoryIdString = categoryIdArray.join(',');

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

        // Append subcategories and bind change event
        function appendSubCategories(subCategories, level, parentSelect) {
            const subCategoryDiv = $('.Sub_Categories');

            const categoryGroup = $('<div>', {
                class: 'subcategory-group mb-3'
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
                        searchTerm: data.term,
                        category_id: categoryIdString
                    };
                },

                processResults: function (response) {
                    return {
                        results:response
                    };
                }
            }
        });

        $('#passage_id').select2({
            width: '100%',
            placeholder: 'Select Passage',
            ajax: {
                url: '/search/passage',
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

        $(document).on('change', '#year_id', function() {
            let year_id = $(this).val();
            $.ajax({
                url: '/get-exam-by-year',
                data: {
                    year_id: year_id 
                },
                method: 'GET',
                dataType: 'JSON',
                success: function(data) {
                    if (data.status) {
                        let examSelect = $('#exam_id'); 
                        
                        examSelect.empty(); 
                        examSelect.append('<option value="">Select Exam</option>'); 

                        $.each(data.exams, function(index, exam) {
                            examSelect.append(`<option value="${exam.id}">${exam.name}</option>`);
                        });

                        examSelect.trigger('change'); 
                    }

                }
            })
        });

        if ($('.content_form').length > 0) {
            $('.content_form').parsley().on('field:validated', function () {
                var ok = $('.parsley-error').length === 0;
                $('.bs-callout-info').toggleClass('hidden', !ok);
                $('.bs-callout-warning').toggleClass('hidden', ok);
            });
        }

        $('.content_form').on('submit', function (e) {
            e.preventDefault();

            $('#submit').hide();
            $('#submitting').show();

            $(".ajax_error").remove();

            var submit_url = $('.content_form').attr('action');
            var formData = new FormData($(".content_form")[0]);

            if (typeof CKEDITOR !== 'undefined' && CKEDITOR.instances.editor) {
                const descriptionData = CKEDITOR.instances.editor.getData();
                formData.append('description', descriptionData);
            }

            //Start Ajax
            $.ajax({
                url: submit_url,
                type: 'POST',
                data: formData,
                contentType: false,
                cache: false,
                processData: false,
                dataType: 'JSON',
                success: function (data) {
                    if (!data.status) {
                        if (data.validator) {
                            for (const [key, messages] of Object.entries(data.message)) {
                                messages.forEach(message => {
                                    toastr.error(message);
                                });
                            }
                        } else {
                            toastr.error(data.message);
                        }

                        if (data.errors) {
                            for (const [key, message] of Object.entries(data.errors)) {
                                toastr.error(message);
                            }
                        }
                    } else {
                        toastr.success(data.message);

                        // CKEDITOR.instances.editor.setData('');
                        // var preview = document.getElementById("preview");
                        // preview.innerHTML = "";

                        $('#data-area').html(data.html);

                        $('.custom-select').select2().trigger('change');

                        $('.content_form').attr('action', '{{ route('portal.question.store') }}');
                        $('#submit').html("<i class='fas fa-paper-plane fa-fw'></i> Import Question");
                        if(data.load) {
                            window.location.href="";
                        }
                    }

                    $('#submit').show();
                    $('#submitting').hide();
                },
                error: function (data) {
                    var jsonValue = $.parseJSON(data.responseText);
                    const errors = jsonValue.errors;
                    if (errors) {
                        var i = 0;
                        $.each(errors, function (key, value) {
                            const first_item = Object.keys(errors)[i]
                            const message = errors[first_item][0];
                            if ($('#' + first_item).length > 0) {
                                $('#' + first_item).parsley().removeError('required', {
                                    updateClass: true
                                });
                                $('#' + first_item).parsley().addError('required', {
                                    message: value,
                                    updateClass: true
                                });
                            }
                            // $('#' + first_item).after('<div class="ajax_error" style="color:red">' + value + '</div');
                            toastr.error(value);
                            i++;

                        });
                    } else {
                        toastr.warning(jsonValue.message);

                    }

                    $('#submit').show();
                    $('#submitting').hide();
                }
            });
        });
    </script>
@endpush
