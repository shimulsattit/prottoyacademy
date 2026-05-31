@extends('layouts.admin', ['title' => 'Create New Question'])
@@php
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
                        Create New Question
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
                        <li class="breadcrumb-item text-muted">Create New Question</li>
                    </ul>
                </div>
            </div>
        </div>

        <div id="app_content" class="app-content flex-column-fluid">
            <div id="app_content_container" class="app-container container-xxl">
                <form action="{{ route('portal.question.store') }}" method="POST" class="content_form">
                    @csrf
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

                                <div class="col-md-6 form-group mb-3">
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
                                    <label for="job_category_id">Job Category</label>
                                    <select name="job_category_id" id="job_category_id" class="form-control" data-parsley-errors-container="#job_category_id_error">
                                        @if($preSelectedJobCategory)
                                            <option value="{{ $preSelectedJobCategory->id }}" selected>{{ $preSelectedJobCategory->name }}</option>
                                        @endif
                                    </select>
                                    <span id="job_category_id_error"></span>
                                </div>

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

                                <div class="col-md-3 form-group mb-3">
                                    <label for="question_mark">Question Mark <span class="text-danger">*</span></label>
                                    <input type="text" name="question_mark" id="question_mark" class="form-control number" value="1" required>
                                </div>
                                
                                <div class="col-md-3 form-group mb-3">
                                    <label for="status">Status <span class="text-danger">*</span></label>
                                    <select name="status" id="status" class="form-control select" required>
                                        <option value="1">Publish</option>
                                        <option value="0">Unpublish</option>
                                    </select>
                                </div>

                                <div class="col-md-3 form-group mb-3">
                                    <label for="passage_id">Passage </label>
                                    <select name="passage_id" id="passage_id" class="form-control" data-parsley-errors-container="#passage_id_error"></select>
                                    <span id="passage_id_error"></span>
                                </div>

                                <div class="col-md-3 form-group mb-3">
                                    <label for="is_math">Is math Question? <span class="text-danger">*</span></label>
                                    <select name="is_math" id="is_math" class="form-control select" required>
                                        <option value="0">No</option>
                                        <option value="1">Yes</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div id="data-area">
                        <div class="card mt-3">
                            <div class="card-header">
                                <h4 class="card-title">Question 1</h4>
                            </div>
                            <div class="card-body row">
                                <div class="col-md-9 mb-3 form-group">
                                    <label for="question">Question <span class="text-danger">*</span></label>
                                    <textarea name="questions[0][question]" id="name" cols="30" rows="3" class="form-control"></textarea>
                                </div>

                                <div class="col-md-3 form-group mb-3">
                                    <label for="correct_answer">Correct Answer</label>
                                    <select name="questions[0][correct_answer]" id="correct_answer" class="form-control select" required data-minimum-results-for-search="Infinity" data-placeholder="Select One">
                                        <option value="">Select One</option>
                                        <option value="1">Option One</option>
                                        <option value="2">Option Two</option>
                                        <option value="3">Option Three</option>
                                        <option value="4">Option Four</option>
                                        <option value="5">Option Five</option>
                                    </select>
                                </div>

                                <div class="col-md-12 row" id="correct_answer_area">
                                    <div class="col-md-6 form-group mb-3">
                                        <label for="option_one">Option One <span class="text-danger">*</span></label>
                                        <textarea name="questions[0][option_one]" id="option_one" cols="30" rows="3" class="form-control"></textarea>
                                    </div>
    
                                    <div class="col-md-6 form-group mb-3">
                                        <label for="option_two">Option Two <span class="text-danger">*</span></label>
                                        <textarea name="questions[0][option_two]" id="option_two" cols="30" rows="3" class="form-control"></textarea>
                                    </div>
    
                                    <div class="col-md-6 form-group mb-3">
                                        <label for="option_three">Option Three</label>
                                        <textarea name="questions[0][option_three]" id="option_three" cols="30" rows="3" class="form-control"></textarea>
                                    </div>
    
                                    <div class="col-md-6 form-group mb-3">
                                        <label for="option_four">Option Four</label>
                                        <textarea name="questions[0][option_four]" id="option_four" cols="30" rows="3" class="form-control"></textarea>
                                    </div>

                                    <div class="col-md-6 form-group mb-3">
                                        <label for="option_five">Option Five</label>
                                        <textarea name="questions[0][option_five]" id="option_five" cols="30" rows="3" class="form-control"></textarea>
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <a href="javascript:;" data-bs-toggle="collapse" data-bs-target="#collapseExample" aria-expanded="false" aria-controls="collapseExample">
                                        Meta Information
                                    </a>
                                </div>

                                <div class="collapse" id="collapseExample">
                                    <div class="col-md-12 mb-3 form-group">
                                        <label for="description">Description</label>
                                        <textarea name="questions[0][description]" id="description" cols="30" rows="3" class="form-control"></textarea>
                                    </div>
                                    
                                    <div class="col-md-12 form-group mb-3">
                                        <label for="site_title">Site Title</label>
                                        <input type="text" name="questions[0][site_title]" id="site_title" class="form-control">
                                    </div>
                                    
                                    <div class="col-md-12 form-group mb-3">
                                        <label for="meta_title">Meta Title</label>
                                        <input type="text" name="questions[0][meta_title]" id="meta_title" class="form-control">
                                    </div>
    
                                    <div class="col-md-12 mb-3 form-group">
                                        <label for="meta_keywords">Meta Keywords</label>
                                        <textarea name="questions[0][meta_keywords]" id="meta_keywords" cols="30" rows="3" class="form-control"></textarea>
                                    </div>
    
                                    <div class="col-md-12 mb-3 form-group">
                                        <label for="meta_description">Meta Description</label>
                                        <textarea name="questions[0][meta_description]" id="meta_description" cols="30" rows="3" class="form-control"></textarea>
                                    </div>
    
                                    <div class="col-md-12 mb-3 form-group">
                                        <label for="meta_article_tag">Google Schema</label>
                                        <textarea name="questions[0][meta_article_tag]" id="meta_article_tag" cols="30" rows="3" class="form-control"></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div id="short-long-area" style="display: none;">
                        
                    </div>

                    @if(Auth::guard('admin')->user()->hasPermissionTo('question.create'))
                        <div class="card mt-3">
                            <div class="card-body">
                                <div class="col-md-12 text-center">
                                    <button type="button" class="btn btn-sm btn-block btn-info" id="add_more">
                                        <i class="fas fa-plus fa-fw"></i> Add More
                                    </button>
                                    <button type="button" class="btn btn-sm btn-block btn-info" id="add_more_short_or_long" style="display: none;">
                                        <i class="fas fa-plus fa-fw"></i> Add More
                                    </button>
                                    <button type="submit" class="btn btn-sm btn-block btn-primary" id="submit">
                                        <i class="fas fa-paper-plane fa-fw"></i> Create Question    
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

     <!-- KaTeX CSS (for frontend rendering) -->
     <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/katex@0.16.8/dist/katex.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/ckeditor/4.9.2/ckeditor.js"></script>
    <!-- Updated script part only -->
    <script src="https://cdn.jsdelivr.net/npm/mathjax@2/MathJax.js?config=TeX-AMS_HTML"></script>
    <script src="https://cdn.jsdelivr.net/npm/mathjax@2/MathJax.js?config=TeX-AMS_HTML"></script>

    <script>
        _componentSelect();

        var _formValidationQuestionInput = function () {
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

                for (var instanceName in CKEDITOR.instances) {
                    if (CKEDITOR.instances.hasOwnProperty(instanceName)) {
                        CKEDITOR.instances[instanceName].updateElement();
                    }
                }

                var submit_url = $('.content_form').attr('action');
                var formData = new FormData($(".content_form")[0]);

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
                            $('.content_form')[0].reset();

                            if (data.goto) {
                                setTimeout(function () {
                                    window.location.href = data.goto;
                                }, 500);
                            }

                            if (data.window) {
                                window.open(data.window, "_blank", "toolbar=yes,scrollbars=yes,resizable=yes,top=auto,left=auto,width=700,height=400");
                                setTimeout(function () {
                                    window.location.href = '';
                                }, 1000);
                            }

                            if (data.load) {
                                setTimeout(function () {
                                    window.location.href = "";
                                }, 1000);
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
                                const first_item = Object.keys(errors)[i];
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
        };

        _formValidationQuestionInput();

        // Define iMathEQ plugin only once
        if (!CKEDITOR.plugins.get('iMathEQ')) {
            CKEDITOR.plugins.add('iMathEQ', {
                icons: 'iMathEQ',
                init: function (editor) {
                    editor.ui.addButton('iMathEQ', {
                        label: 'Insert Math (iMathEQ)',
                        command: 'openMathEditor',
                        toolbar: 'insert',
                        icon: '{{ asset("portal-resource/images/2721290.png") }}' // Use your local asset
                    });

                    editor.addCommand('openMathEditor', {
                        exec: function () {
                            openMathEditorModal(editor);
                        }
                    });
                }
            });
        }

        // Open custom modal with iMathEQ iframe (cross-origin-safe)
        function openMathEditorModal(editor) {
            document.getElementById('mathEditorModal')?.remove();

            const modal = document.createElement('div');
            modal.id = 'mathEditorModal';
            modal.style.position = 'fixed';
            modal.style.top = '5%';
            modal.style.left = '5%';
            modal.style.width = '90%';
            modal.style.height = '90%';
            modal.style.background = '#fff';
            modal.style.border = '2px solid #ccc';
            modal.style.zIndex = 9999;
            modal.innerHTML = `
                <div style="padding: 10px;">
                    <p>After building your equation, click <strong>"Copy Code"</strong> in the iMathEQ editor and paste it manually into the CKEditor.</p>
                    <button onclick="document.getElementById('mathEditorModal').remove()">Close</button>
                </div>
                <iframe src="https://www.imatheq.com/imatheq/com/imatheq/math-equation-editor.html" width="100%" height="90%" frameborder="0"></iframe>
            `;
            document.body.appendChild(modal);

            window._currentCkEditor = editor; 
        }

        // This will not work due to cross-origin restriction â€” kept for reference
        function insertEquationToEditor() {
            const iframe = document.getElementById('mathEditorIframe');
            if (!iframe) return;

            try {
                const mathCode = iframe.contentWindow.document.getElementById('result')?.value;
                if (mathCode && window._currentCkEditor) {
                    window._currentCkEditor.insertHtml(mathCode);
                }
            } catch (e) {
                alert("Failed to access equation. Please make sure the iframe is from the same origin.");
            }

            document.getElementById('mathEditorModal')?.remove();
        }

        // Reusable CKEditor initializer
        function editor(editorName) {
            if (CKEDITOR.instances[editorName]) {
                CKEDITOR.instances[editorName].destroy(true);
            }

            CKEDITOR.replace(editorName, {
                extraPlugins: 'iMathEQ',
                height: 150,
                filebrowserUploadUrl: "/editor/upload?_token=" + document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                filebrowserUploadMethod: 'form',
                toolbar: [
                    { name: 'basicstyles', items: ['Bold', 'Italic', 'Underline', 'RemoveFormat'] },
                    { name: 'paragraph', items: ['NumberedList', 'BulletedList', '-', 'Outdent', 'Indent'] },
                    { name: 'insert', items: ['Image', 'Table', 'HorizontalRule', 'SpecialChar', 'iMathEQ'] },
                    { name: 'styles', items: ['Format', 'FontSize'] },
                    { name: 'tools', items: ['Maximize', 'Source'] }
                ]
            });
        }

        // MathML receive handler (once)
        window.addEventListener('message', function (event) {
            if (event.data.type === 'insertMathML') {
                if (window.mathTargetEditor) {
                    window.mathTargetEditor.insertHtml(event.data.data);
                }
            }
        }, false);

        function destroyEditor(editorName) {
            if (CKEDITOR.instances[editorName]) {
                CKEDITOR.instances[editorName].destroy(true);
            }
        }

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

        let index = 1;

        $(document).on('click', '#add_more', function () {
            index++;

            let content = `
                <div class="card mt-3">
                    <div class="card-header">
                        <div class="row">
                            <h4 class="card-title">
                                Question ${index}
                                &nbsp;&nbsp;<button type="button" class="btn remove btn-sm btn-danger"><i class="fas fa-close"></i></button>    
                            </h4>
                                
                        </div>
                    </div>
                    <div class="card-body row">
                        <div class="col-md-9 mb-3 form-group">
                            <label for="question_${index}">Question <span class="text-danger">*</span></label>
                            <textarea name="questions[${index}][question]" id="question_${index}" class="form-control" required></textarea>
                        </div>

                        <div class="col-md-3 form-group mb-3">
                            <label for="correct_answer_${index}">Correct Answer</label>
                            <select name="questions[${index}][correct_answer]" id="correct_answer_${index}" class="form-control select" required data-minimum-results-for-search="Infinity" data-placeholder="Select One">
                                <option value="">Select One</option>
                                <option value="1">Option One</option>
                                <option value="2">Option Two</option>
                                <option value="3">Option Three</option>
                                <option value="4">Option Four</option>
                                <option value="5">Option Five</option>
                            </select>
                        </div>

                        <div class="col-md-12 row" id="correct_answer_area">
                            <div class="col-md-6 form-group mb-3">
                                <label for="option_one_${index}">Option One <span class="text-danger">*</span></label>
                                <textarea name="questions[${index}][option_one]" id="option_one_${index}" class="form-control" required></textarea>
                            </div>

                            <div class="col-md-6 form-group mb-3">
                                <label for="option_two_${index}">Option Two <span class="text-danger">*</span></label>
                                <textarea name="questions[${index}][option_two]" id="option_two_${index}" class="form-control" required></textarea>
                            </div>

                            <div class="col-md-6 form-group mb-3">
                                <label for="option_three_${index}">Option Three</label>
                                <textarea name="questions[${index}][option_three]" id="option_three_${index}" class="form-control"></textarea>
                            </div>

                            <div class="col-md-6 form-group mb-3">
                                <label for="option_four_${index}">Option Four</label>
                                <textarea name="questions[${index}][option_four]" id="option_four_${index}" class="form-control"></textarea>
                            </div>

                            <div class="col-md-6 form-group mb-3">
                                <label for="option_five_${index}">Option Five</label>
                                <textarea name="questions[${index}][option_five]" id="option_five_${index}" class="form-control"></textarea>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <a href="javascript:;" data-bs-toggle="collapse" data-bs-target="#collapseExample${index}" aria-expanded="false" aria-controls="collapseExample${index}">
                                Meta Information
                            </a>
                        </div>

                        <div class="collapse" id="collapseExample${index}">
                            <div class="col-md-12 mb-3 form-group">
                                <label for="description_${index}">Description</label>
                                <textarea name="questions[${index}][description]" id="description_${index}" cols="30" rows="3" class="form-control"></textarea>
                            </div>

                            <div class="col-md-12 form-group mb-3">
                                <label for="site_title_${index}">Site Title</label>
                                <input type="text" name="questions[${index}][site_title]" id="site_title_${index}" class="form-control">
                            </div>

                            <div class="col-md-12 form-group mb-3">
                                <label for="meta_title_${index}">Meta Title</label>
                                <input type="text" name="questions[${index}][meta_title]" id="meta_title_${index}" class="form-control">
                            </div>

                            <div class="col-md-12 mb-3 form-group">
                                <label for="meta_keywords_${index}">Meta Keywords</label>
                                <textarea name="questions[${index}][meta_keywords]" id="meta_keywords_${index}" cols="30" rows="3" class="form-control"></textarea>
                            </div>

                            <div class="col-md-12 mb-3 form-group">
                                <label for="meta_description_${index}">Meta Description</label>
                                <textarea name="questions[${index}][meta_description]" id="meta_description_${index}" cols="30" rows="3" class="form-control"></textarea>
                            </div>

                            <div class="col-md-12 mb-3 form-group">
                                <label for="meta_article_tag_${index}">Google Schema</label>
                                <textarea name="questions[${index}][meta_article_tag]" id="meta_article_tag_${index}" cols="30" rows="3" class="form-control"></textarea>
                            </div>
                        </div>
                    </div>
                </div>
            `;

            $('#data-area').append(content);

            // Check is_math before enabling CKEditor
            if ($('#is_math').val() == 1) {
                editor(`question_${index}`);
                editor(`option_one_${index}`);
                editor(`option_two_${index}`);
                editor(`option_three_${index}`);
                editor(`option_four_${index}`);
                editor(`option_five_${index}`);
                editor(`description_${index}`);
            }
        });

        $(document).on('click', '.remove', function () {
            $(this).closest('.card').remove();
        });

        $(document).on('change', '#is_math', function () {
            let is_math = $(this).val();
            let question_type = $('#question_type').val();
            let editors = [];

            if(question_type == 'mcq') {
                editors = ['name', 'option_one', 'option_two', 'option_three', 'option_four', 'option_five'];
            } else {
                editors = ['short_question', 'correct_answer'];
            }

            if (is_math == 1) {
                editors.forEach(function (editorName) {
                    editor(editorName);
                });
            } else {
                editors.forEach(function (editorName) {
                    destroyEditor(editorName);
                });
            }
        });

        let short_long_content = `
            <div class="card mt-3">
                <div class="card-header">
                    <h4 class="card-title">Hola Question 1</h4>
                </div>
                <div class="card-body row">
                    <div class="col-md-12 mb-3 form-group">
                        <label for="question">Question <span class="text-danger">*</span></label>
                        <textarea type="text" name="questions[0][question]" id="short_question" class="form-control" required value=""></textarea>
                    </div>

                    <div class="col-md-12 form-group mb-3">
                        <label for="correct_answer">Correct Answer</label>
                        <textarea name="questions[0][correct_answer]" id="correct_answer" cols="30" rows="3" class="form-control" required></textarea>
                    </div>

                    <div class="col-md-12">
                        <a href="javascript:;" data-bs-toggle="collapse" data-bs-target="#collapseShort" aria-expanded="false" aria-controls="collapseShort">
                            Meta Information
                        </a>
                    </div>

                    <div class="collapse" id="collapseShort">
                        <div class="col-md-12 mb-3 form-group">
                            <label for="description">Description</label>
                            <textarea name="questions[0][description]" id="description" cols="30" rows="3" class="form-control"></textarea>
                        </div>
                        
                        <div class="col-md-12 form-group mb-3">
                            <label for="site_title">Site Title</label>
                            <input type="text" name="questions[0][site_title]" id="site_title" class="form-control">
                        </div>
                        
                        <div class="col-md-12 form-group mb-3">
                            <label for="meta_title">Meta Title</label>
                            <input type="text" name="questions[0][meta_title]" id="meta_title" class="form-control">
                        </div>

                        <div class="col-md-12 mb-3 form-group">
                            <label for="meta_keywords">Meta Keywords</label>
                            <textarea name="questions[0][meta_keywords]" id="meta_keywords" cols="30" rows="3" class="form-control"></textarea>
                        </div>

                        <div class="col-md-12 mb-3 form-group">
                            <label for="meta_description">Meta Description</label>
                            <textarea name="questions[0][meta_description]" id="meta_description" cols="30" rows="3" class="form-control"></textarea>
                        </div>

                        <div class="col-md-12 mb-3 form-group">
                            <label for="meta_article_tag">Google Schema</label>
                            <textarea name="questions[0][meta_article_tag]" id="meta_article_tag" cols="30" rows="3" class="form-control"></textarea>
                        </div>
                    </div>
                </div>
            </div>
        `;

        $(document).on('change', '#question_type', function() {
            let val = $(this).val();
            if(val == 'mcq') {
                $('#add_more').show();
                $('#data-area').html("");
                $('#data-area').show();

                $('#add_more_short_or_long').hide();
                $('#short-long-area').html("");
                $('#short-long-area').hide();
            } else if(val == 'short_answer' || val == 'long_answer') {
                $('#add_more').hide();
                $('#data-area').html("");
                $('#data-area').hide();

                $('#add_more_short_or_long').show();
                $('#short-long-area').html(short_long_content);
                $('#short-long-area').show();
            } else {
                $('#add_more').hide();
                $('#data-area').html("");
                $('#data-area').hide();
            }
        });

        let add_more_short_or_long_index = 1;
        $(document).on('click', '#add_more_short_or_long', function() {
            add_more_short_or_long_index++;
            let short_long_content = `
                <div class="card mt-3">
                    <div class="card-header">
                        <h4 class="card-title">
                            Question `+ add_more_short_or_long_index +`
                            &nbsp;&nbsp;<button type="button" class="btn remove btn-sm btn-danger"><i class="fas fa-close"></i></button>    
                        </h4>
                    </div>
                    <div class="card-body row">
                        <div class="col-md-12 mb-3 form-group">
                            <label for="short_question_`+ add_more_short_or_long_index +`">Question <span class="text-danger">*</span></label>
                            <textarea type="text" name="questions[`+ add_more_short_or_long_index +`][question]" id="short_question_`+ add_more_short_or_long_index +`" class="form-control" required></textarea>
                        </div>

                        <div class="col-md-12 form-group mb-3">
                            <label for="correct_answer_`+ add_more_short_or_long_index +`">Correct Answer</label>
                            <textarea name="questions[`+ add_more_short_or_long_index +`][correct_answer]" id="correct_answer_`+ add_more_short_or_long_index +`" cols="30" rows="3" class="form-control" required></textarea>
                        </div>

                        <div class="col-md-12">
                            <a href="javascript:;" data-bs-toggle="collapse" data-bs-target="#collapseShort_`+ add_more_short_or_long_index +`" aria-expanded="false" aria-controls="collapseShort_`+ add_more_short_or_long_index +`">
                                Meta Information
                            </a>
                        </div>

                        <div class="collapse" id="collapseShort_`+ add_more_short_or_long_index +`">
                            <div class="col-md-12 mb-3 form-group">
                                <label for="description_`+ add_more_short_or_long_index +`">Description</label>
                                <textarea name="questions[`+ add_more_short_or_long_index +`][description]" id="description_`+ add_more_short_or_long_index +`" cols="30" rows="3" class="form-control"></textarea>
                            </div>
                            
                            <div class="col-md-12 form-group mb-3">
                                <label for="site_title_`+ add_more_short_or_long_index +`">Site Title</label>
                                <input type="text" name="questions[`+ add_more_short_or_long_index +`][site_title]" id="site_title_`+ add_more_short_or_long_index +`" class="form-control">
                            </div>
                            
                            <div class="col-md-12 form-group mb-3">
                                <label for="meta_title_`+ add_more_short_or_long_index +`">Meta Title</label>
                                <input type="text" name="questions[`+ add_more_short_or_long_index +`][meta_title]" id="meta_title_`+ add_more_short_or_long_index +`" class="form-control">
                            </div>

                            <div class="col-md-12 mb-3 form-group">
                                <label for="meta_keywords_`+ add_more_short_or_long_index +`">Meta Keywords</label>
                                <textarea name="questions[`+ add_more_short_or_long_index +`][meta_keywords]" id="meta_keywords_`+ add_more_short_or_long_index +`" cols="30" rows="3" class="form-control"></textarea>
                            </div>

                            <div class="col-md-12 mb-3 form-group">
                                <label for="meta_description_`+ add_more_short_or_long_index +`">Meta Description</label>
                                <textarea name="questions[`+ add_more_short_or_long_index +`][meta_description]" id="meta_description_`+ add_more_short_or_long_index +`" cols="30" rows="3" class="form-control"></textarea>
                            </div>

                            <div class="col-md-12 mb-3 form-group">
                                <label for="meta_article_tag_`+ add_more_short_or_long_index +`">Google Schema</label>
                                <textarea name="questions[`+ add_more_short_or_long_index +`][meta_article_tag]" id="meta_article_tag_`+ add_more_short_or_long_index +`" cols="30" rows="3" class="form-control"></textarea>
                            </div>
                        </div>
                    </div>
                </div>
            `;

            $('#short-long-area').append(short_long_content);

            // Check is_math before enabling CKEditor
            if ($('#is_math').val() == 1) {
                editor(`short_question_${add_more_short_or_long_index}`);
                editor(`correct_answer_${add_more_short_or_long_index}`);
            }
        })
    </script>
@endpush
