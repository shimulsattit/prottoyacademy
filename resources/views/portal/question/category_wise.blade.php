@extends('layouts.admin', ['title' => 'Category Wise Question Management', 'modal' => 'lg'])
@section('content')
<div class="app-main flex-column flex-row-fluid" id="app_main">
    <div class="d-flex flex-column flex-column-fluid">
        <div id="app_toolbar" class="app-toolbar py-3 py-lg-6">
            <div id="app_toolbar_container" class="app-container container-xxl d-flex flex-stack">
                <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
                    <h1 class="page-heading d-flex text-gray-900 fw-bold fs-3 flex-column justify-content-center my-0">
                        Category Wise Question Management
                    </h1>
                    <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">
                        <li class="breadcrumb-item text-muted">
                            <a href="{{ route('portal.dashboard') }}" class="text-muted text-hover-primary">Home</a>
                        </li>
                        <li class="breadcrumb-item">
                            <span class="bullet bg-gray-500 w-5px h-2px"></span>
                        </li>
                        <li class="breadcrumb-item text-muted">Category Wise Question Management</li>
                    </ul>
                </div>

                <div class="d-flex align-items-center gap-2 gap-lg-3">
                    @if(Auth::guard('admin')->user()->hasPermissionTo('question.create'))
                        <a href="{{ route('portal.question.create') }}?category_id={{ $category->id }}" class="btn btn-sm btn-primary" id="add-question-btn">
                            <i class="fas fa-plus-circle me-1"></i> Add Question
                        </a>
                    @endif
                    @if(Auth::guard('admin')->user()->hasPermissionTo('question.import'))
                        <a href="{{ route('portal.import-question') }}?category_id={{ $category->id }}" class="btn btn-sm btn-success" id="import-question-btn">
                            <i class="fas fa-upload me-1"></i> Import Question
                        </a>
                    @endif
                </div>
            </div>
        </div>

        <div id="app_content" class="app-content flex-column-fluid">
            <div id="app_content_container" class="app-container container-xxl">
                <div class="card">
                    <div class="card-body">
                        <div class="row mt-3">
                            <div class="col-md-3">
                                <div id="category-panel" style="max-height: 600px; overflow-y:scroll;">
                                    <ul class="list-group" id="category-list">
                                        @if ($job_categories && count($job_categories) > 0)
                                            @foreach ($job_categories as $job_category)
                                                <li style="cursor:pointer;" class="list-group-item d-flex justify-content-between align-items-center job-category" data-id="{{ $job_category->id }}">
                                                    {{ $job_category->name }}
                                                    <span class="badge badge-primary rounded-pill">{{ count($job_category->questions) }}</span>
                                                </li>
                                            
                                            @endforeach
                                        @endif
                                    </ul>
                                </div>
                            </div>
                        
                            <div class="col-md-9">
                                <div class="row">
                                    <div class="col-md-12">
                                        <ul style="width: 100%;overflow-x:scroll;" class="list-group list-group-horizontal w-100 mb-3" id="category-content"></ul>
                                    </div>
                                </div>
                                
                                <!-- MCQ / Short Question Tabs -->
                                <ul class="nav nav-tabs nav-line-tabs mb-3 fs-6" id="question-type-tabs" style="display: none; padding-left: 20px;">
                                    <li class="nav-item">
                                        <a class="nav-link active fw-bold text-uppercase" id="mcq-tab-link" href="javascript:void(0);" data-type="mcq" style="padding: 10px 20px; font-size: 14px;">
                                            <i class="fas fa-list-ol me-2"></i> MCQ Question
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link fw-bold text-uppercase" id="short-tab-link" href="javascript:void(0);" data-type="short_answer" style="padding: 10px 20px; font-size: 14px;">
                                            <i class="fas fa-align-left me-2"></i> Short Question
                                        </a>
                                    </li>
                                </ul>

                                <div id="question-content" class="p-5" style="border: 1px solid #e2e8f0; border-radius: 8px; background: #fff;">
                                    
                                    <div id="question-loader"><p id="selector"><i>Select Job Category First</i></p></div>
                                    
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('style')
    <style>
        #category-content {
            display: flex;
            overflow-x: auto;
            white-space: nowrap;
            padding: 0;
            margin: 0;
        }

        #category-content .list-group-item {
            flex: 0 0 150px;
            text-align: center;
            margin-right: 5px;
            white-space: normal;
        }
        
        #question-content {
            background: #fcfcfc;
            height: 600px;
            overflow-y:scroll;        
        }

        .question-loader {
            display: flex;
            justify-content: center;
            align-items: center;  
            background: #fcfcfc;
            width: 150px;          
            position: relative;
        }

        #question-loader {
            justify-content: center;
            align-items: center;  
            background: #fcfcfc;
        }

        #selector {
            text-align: center;
            font-style: italic;
            width: 350px;
        }

        .job-category {
            cursor: pointer;
        }

        .category {
            cursor: pointer;
        }

        .job-category:hover {
            background: #13146d;
            color: #fff;
        }

        .category:hover {
            background: #13146d;
            color: #fff;
        }

        .job-category.active {
            background: #13146d;
            color: #fff;
            border: none;
        }

        .category.active {
            background: #13146d;
            color: #fff;
            border: none;
        }

        span img {
            width: 80%;
        }

        .passage-group {
            background-color: #f8f9fc;
            border: 1px solid #dee2e6;
            border-radius: 6px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.04);
        }

        .passage-header {
            background: #f1f3ff;
            border-left: 5px solid #5e72e4;
            border-radius: 4px;
        }

        .passage-header h6 {
            font-weight: 600;
            color: #343a40;
        }

        .passage-text {
            color: #555;
            font-size: 0.95rem;
            margin-top: 4px;
        }

        .question-in-passage {
            padding-left: 10px;
            border-left: 3px solid #c4c9ff;
            margin-bottom: 15px;
        }

    </style>
@endpush

@push('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/ckeditor/4.9.2/ckeditor.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/mathjax@2/MathJax.js?config=TeX-AMS_HTML"></script>
    <script src="https://cdn.jsdelivr.net/npm/mathjax@2/MathJax.js?config=TeX-AMS_HTML"></script>

    <script>
        $(document).ready(function() {
            let categoryId = '{{ $category->id }}';
            const limit = 20;
            let currentOffset = 0;
            
            $(document).on('click', '.job-category', function() {
                let jobCategoryId = $(this).data('id');
                let jobCategoryName = $(this).contents().not($(this).children()).text().trim();

                $('.job-category').removeClass('active');
                $(this).addClass('active');

                // Dynamically update add and import buttons with the selected job category ID!
                let addBtn = $('#add-question-btn');
                let importBtn = $('#import-question-btn');
                
                if (addBtn.length) {
                    let addUrl = new URL(addBtn.prop('href'), window.location.origin);
                    addUrl.searchParams.set('job_category_id', jobCategoryId);
                    addBtn.attr('href', addUrl.toString());
                }
                if (importBtn.length) {
                    let importUrl = new URL(importBtn.prop('href'), window.location.origin);
                    importUrl.searchParams.set('job_category_id', jobCategoryId);
                    importBtn.attr('href', importUrl.toString());
                }

                $('#question-content').addClass('question-loader');
                $('#question-content').html('<div class="spinner-border text-primary" role="status" style="width: 2rem; height: 2rem;"><span class="visually-hidden">Loading...</span></div> <br>Loading ' + jobCategoryName + ' Questions. Please wait');

                loadQuestionsTab(categoryId, jobCategoryId, true);
                $('#question-content').removeClass('question-loader');
            })

            function htmlEntityDecode(str) {
                var txt = document.createElement("textarea");
                txt.innerHTML = str;
                return txt.value;
            }


            const permissions = {
                canUpdate: {{ Auth::guard('admin')->user()->hasPermissionTo('question.update') ? 'true' : 'false' }},
                canDelete: {{ Auth::guard('admin')->user()->hasPermissionTo('question.delete') ? 'true' : 'false' }},
            };

            function loadQuestionsTab(categoryId, jobCategoryId, mainCategory = false) {
                $.get(`/portal/quiz/questions/${categoryId}/${jobCategoryId}`, { offset: currentOffset }, function (response) {
                    const groupedQuestions = response.grouped_questions;
                    const hasMore = response.has_more;
                    const categories = response.categories;

                    if (groupedQuestions.length === 0 && currentOffset === 0) {
                        toastr.warning(`No questions found for this category`, 'Questions Not Found');
                        $('#question-content').html(`<div class="text-center text-muted py-4">No questions found in this category.</div>`);
                        return;
                    }

                    if (mainCategory) {
                        $('#category-content').html("");

                        let categoryHtml = '<li class="list-group-item active category" data-id="all"> All </li>';
                        categories.forEach((c) => {
                            if (categoryId != c.id) {
                                categoryHtml += `<li class="list-group-item category" data-id="${c.id}">${c.name}</li>`;
                            }
                        });
                        $('#category-content').html(categoryHtml);
                    }

                    let contentHtml = '';
                    const optionLabels = ['(a)', '(b)', '(c)', '(d)', '(e)'];
                    let counter = 0;

                    groupedQuestions.forEach((group) => {
                        contentHtml += `<div style="background:#13146d;"><h5 class="mt-3 py-3 text-white text-center">${group.category_name}</h5></div>`;

                        group.groups.forEach((g) => {
                            const hasPassage = g.passage_id !== null && g.passage_text !== null && g.passage_text.trim() !== "";

                            if (hasPassage) {
                                contentHtml += `
                                    <div class="passage-group my-4 p-3">
                                        <div class="passage-header px-3 py-2 mb-3 rounded">
                                            <h6 class="mb-1"><i class="fas fa-book-open"></i> ${g.passage_name}</h6>
                                            <div class="text passage-text">${g.passage_text}</div>
                                        </div>
                                `;
                            }

                            // g.questions.forEach((q, index) => {
                            //     const correctAnswer = parseInt(q.correct_answer || '0');

                            //     contentHtml += `
                            //         <div id="question_${q.id}" class="mt-3 pb-2 question-item ${hasPassage ? 'question-in-passage' : ''}">
                            //             <div class="clearfix">
                            //                 <strong class="float-start me-2">${++counter}.</strong>
                            //                 <span class="question-html float-start" data-html='${q.question.replace(/'/g, "&apos;")}'></span>

                            //                 <div class="float-end">
                            //                     ${permissions.canUpdate ? `
                            //                         <button id="content_management" data-url="/portal/question/${q.id}/short-edit" class="btn btn-sm btn-primary btn-icon">
                            //                             <i class="fas fa-edit"></i>
                            //                         </button>` : ''}
                            //                     ${permissions.canDelete ? `
                            //                         <button id="delete_item_custom" data-id="${q.id}" data-url="/portal/question/${q.uuid}" class="btn btn-sm btn-danger btn-icon">
                            //                             <i class="fas fa-trash"></i>
                            //                         </button>` : ''}
                            //                 </div>
                            //             </div>
                            //     `;

                            //     q.options.forEach((opt, optIndex) => {
                            //         if (opt != null) {
                            //             const isCorrect = (optIndex + 1) === correctAnswer;
                            //             const textClass = isCorrect ? 'text-success fw-bold' : '';

                            //             if (optIndex % 2 === 0) contentHtml += `<div class="row">`;

                            //             contentHtml += `
                            //                 <div class="col-md-6" id="question_option_${optIndex + 1}_${q.id}">
                            //                     <p class="${textClass} mb-1 option-html" data-html='${opt.replace(/'/g, "&apos;")}'>
                            //                         ${optionLabels[optIndex]}
                            //                     </p>
                            //                 </div>
                            //             `;

                            //             if (optIndex % 2 === 1 || optIndex === q.options.length - 1) contentHtml += `</div>`;
                            //         }
                            //     });

                            //     contentHtml += `
                            //         <div class="open-details text-muted" data-id="${q.id}" style="cursor:pointer;">Toggle Description</div>
                            //         <div id="description_${q.id}" style="display:none; margin-top:10px;">
                            //             <form class="description-form" data-id="${q.id}">
                            //                 <textarea id="editor_${q.id}" name="content" class="form-control" rows="4">${q?.content || ''}</textarea>
                            //                 <button type="submit" class="btn btn-sm btn-primary mt-2">Update</button>
                    // Render questions for active tab type
                    let activeType = $('#question-type-tabs .nav-link.active').data('type') || 'mcq';
                    renderQuestions(activeType);
                });
            }

            function renderQuestions(filterType) {
                const groupedQuestions = window.currentGroupedQuestions || [];
                let contentHtml = '';
                const optionLabels = ['(a)', '(b)', '(c)', '(d)', '(e)'];
                let counter = 0;

                groupedQuestions.forEach((group) => {
                    // Check if group has any questions matching the filterType
                    let hasMatchingQuestions = false;
                    group.groups.forEach((g) => {
                        const matching = g.questions.filter(q => {
                            if (filterType === 'mcq') {
                                return q.type === 'mcq';
                            } else {
                                return q.type !== 'mcq';
                            }
                        });
                        if (matching.length > 0) {
                            hasMatchingQuestions = true;
                        }
                    });

                    if (!hasMatchingQuestions) return;

                    contentHtml += `<div style="background:#1B4F72; border-radius: 8px; margin-top: 15px;"><h5 class="py-3 text-white text-center">${group.category_name}</h5></div>`;

                    group.groups.forEach((g) => {
                        const matchingQuestions = g.questions.filter(q => {
                            if (filterType === 'mcq') {
                                return q.type === 'mcq';
                            } else {
                                return q.type !== 'mcq';
                            }
                        });

                        if (matchingQuestions.length === 0) return;

                        const hasPassage = g.passage_id !== null && g.passage_text !== null && g.passage_text.trim() !== "";

                        if (hasPassage) {
                            contentHtml += `
                                <div class="passage-group my-4 p-3">
                                    <div class="passage-header px-3 py-2 mb-3 rounded">
                                        <h6 class="mb-1"><i class="fas fa-book-open"></i> ${g.passage_name}</h6>
                                        <div class="text passage-text">${g.passage_text}</div>
                                    </div>
                            `;
                        }

                        matchingQuestions.forEach((q) => {
                            const correctAnswer = parseInt(q.correct_answer || '0');

                            contentHtml += `
                                <div id="question_${q.id}" class="mt-3 pb-2 question-item ${hasPassage ? 'question-in-passage' : ''}">
                                    <div class="clearfix">
                                        <strong class="me-2">${++counter}.</strong>
                                        <span class="question-html" data-html='${q.question.replace(/'/g, "&apos;")}'></span>

                                        <div class="float-end mt-5">
                                            ${permissions.canUpdate ? `
                                                <button id="content_management" data-url="/portal/question/${q.id}/short-edit" class="btn btn-sm btn-primary btn-icon">
                                                    <i class="fas fa-edit"></i>
                                                </button>` : ''}
                                            ${permissions.canDelete ? `
                                                <button id="delete_item_custom" data-id="${q.id}" data-url="/portal/question/${q.uuid}" class="btn btn-sm btn-danger btn-icon">
                                                    <i class="fas fa-trash"></i>
                                                </button>` : ''}
                                        </div>
                                    </div>
                            `;

                            if (q.type === 'mcq') {
                                // MCQ options
                                q.options.forEach((opt, optIndex) => {
                                    if (opt != null && opt != '') {
                                        const isCorrect = (optIndex + 1) === correctAnswer;
                                        const textClass = isCorrect ? 'text-success fw-bold' : '';

                                        if (optIndex % 2 === 0) contentHtml += `<div class="row">`;

                                        contentHtml += `
                                            <div class="col-md-6" id="question_option_${optIndex + 1}_${q.id}">
                                                <p class="${textClass} mb-1 option-html" data-html='${opt.replace(/'/g, "&apos;")}'>
                                                    ${optionLabels[optIndex]}
                                                </p>
                                            </div>
                                        `;

                                        if (optIndex % 2 === 1 || optIndex === q.options.length - 1) contentHtml += `</div>`;
                                    }
                                });
                            } else {
                                contentHtml += `
                                    <div class="short-answer mt-2">
                                        <p class="text-primary">${q.correct_answer ? q.correct_answer : '<em>No answer provided</em>'}</p>
                                    </div>
                                `;
                            }

                            contentHtml += `
                                <div class="d-flex flex-wrap gap-2 my-2">
                                    <span class="badge" style="background-color: white; border: 1px solid #5e72e4; color: #5e72e4; padding: 5px 10px; border-radius: 5px; font-size: 11px;">${q.root_category_name || 'N/A'}</span>
                                    <span class="badge" style="background-color: white; border: 1px solid #50cd89; color: #50cd89; padding: 5px 10px; border-radius: 5px; font-size: 11px;">${q.job_category_name || 'N/A'}</span>
                                    <span class="badge" style="background-color: white; border: 1px solid #009ef7; color: #009ef7; padding: 5px 10px; border-radius: 5px; font-size: 11px;">${q.category_name || 'N/A'}</span>
                                </div>
                                <div class="open-details text-muted" data-id="${q.id}" style="cursor:pointer;">Toggle Description</div>
                                <div id="description_${q.id}" style="display:none; margin-top:10px;">
                                    <form class="description-form" data-id="${q.id}">
                                        <textarea id="editor_${q.id}" name="content" class="form-control" rows="4">${q?.content || ''}</textarea>
                                        <button type="submit" class="btn btn-sm btn-primary mt-2">Update</button>
                                    </form>
                                </div>
                            </div> </hr>`; // end question
                        });

                        if (hasPassage) {
                            contentHtml += `</div> `; // close .passage-group
                        }
                    });
                });

                $('#question-content').html(contentHtml);

                // Decode HTML safely
                $('.question-html').each(function () {
                    const html = $(this).attr('data-html');
                    $(this).html(htmlEntityDecode(html));
                });

                $('.option-html').each(function () {
                    const html = $(this).attr('data-html');
                    $(this).append(` ${htmlEntityDecode(html)}`);
                });
            }

            $(document).on('click', '.open-details', function () {
                const id = $(this).data('id');
                const descriptionBox = $(`#description_${id}`);
                const editorId = `editor_${id}`;

                descriptionBox.toggle();

                if (!CKEDITOR.instances[editorId]) {
                    editor(editorId);
                }
            });


            function editor(editorName) {
                console.log(editorName)
                if (CKEDITOR.instances[editorName]) {
                    CKEDITOR.instances[editorName].destroy(true);
                }

                CKEDITOR.replace(editorName, {
                    height: 150,
                    filebrowserUploadUrl: "/editor/upload?_token=" + document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    filebrowserUploadMethod: 'form',
                    toolbar: [
                        { name: 'basicstyles', items: ['Bold', 'Italic', 'Underline', 'RemoveFormat'] },
                        { name: 'paragraph', items: ['NumberedList', 'BulletedList', '-', 'Outdent', 'Indent'] },
                        { name: 'insert', items: ['Image', 'Table', 'HorizontalRule', 'SpecialChar'] },
                        { name: 'styles', items: ['Format', 'FontSize'] },
                        { name: 'tools', items: ['Maximize', 'Source'] }
                    ]
                });
            }


            $(document).on('submit', '.description-form', function (e) {
                e.preventDefault();

                const form = $(this);
                const id = form.data('id');
                const content = form.find('textarea[name="content"]').val();

                $.ajax({
                    url: `/portal/questions/${id}/update-description`, // change this route as needed
                    type: 'POST',
                    data: {
                        content: content,
                        _token: $('meta[name="csrf-token"]').attr('content') // include CSRF token
                    },
                    success: function (res) {
                        toastr.success('Description updated successfully!');
                    },
                    error: function (err) {
                        toastr.error('Failed to update description.');
                    }
                });
            });


            $(document).on('click', '#delete_item_custom', function(e) {
                e.preventDefault();
                var row = $(this).data('id');
                var url = $(this).data('url');

                Swal.fire({
                    title: 'Are you sure?',
                    text: "You won't be able to revert this!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Yes, delete it!',
                    cancelButtonText: 'No, cancel!',
                }).then((result) => {
                    if (result.isConfirmed) {

                    $('#question_'+row).remove();

                        $.ajax({
                            url: url,
                            method: 'Delete',
                            contentType: false,
                            cache: false,
                            processData: false,
                            dataType: 'JSON',
                            success: function(data) {

                                if (data.status) {

                                    toastr.success(data.message);
                                    

                                } else {
                                    toastr.warning(data.message);
                                }
                            },
                            error: function(data) {
                                var jsonValue = $.parseJSON(data.responseText);
                                const errors = jsonValue.errors
                                var i = 0;
                                $.each(errors, function(key, value) {
                                    toastr.error(value);
                                    i++;
                                });
                            }
                        });
                    }
                });

            });

            // $(document).on('click', '.open-details', function() {
            //     let id = $(this).data('id');
            //     $('#description_'+id).toggle();
            // })

            $(document).on('click', '.category', function () {
                let categoryId = $(this).data('id');
                if(categoryId == 'all') {
                    categoryId = '{{ $category->id }}';
                }
                let categoryName = '';
                categoryName = $(this).html().trim();
                let jobCategoryId = $('.job-category.active').data('id');
                let jobCategoryName = $('.job-category.active').html().trim();

                $('.category').removeClass('active');
                $(this).addClass('active');

                $('#question-content').addClass('question-loader');
                $('#question-content').html('<div class="spinner-border text-primary" role="status" style="width: 2rem; height: 2rem;"><span class="visually-hidden">Loading...</span></div> <br>Loading ' + jobCategoryName + ' <b>' + categoryName + '</b>' + ' Questions. Please wait');

                loadQuestionsTab(categoryId, jobCategoryId, false);
                $('#question-content').removeClass('question-loader');
            });

            $(document).on('click', '#content_management', function (e) {
                e.preventDefault();
                $('#modal_remote').modal('toggle');
                var url = $(this).data('url');
                $('.modal-content').html('');
                $('#modal-loader').show();
                $.ajax({
                    url: url,
                    type: 'Get',
                    dataType: 'html'
                })
                .done(function (data) {
                    $('.modal-content').html(data);
                    _componentSelect2Normal();
                    _modalClassFormValidation1();
                })
                .fail(function (data) {
                    $('.modal-content').html('<span style="color:red; font-weight: bold;"> Something Went Wrong. Please Try again later.......</span>');
                    $('#modal-loader').hide();
                });
            });

            function updateQuestionDOM(data) {
                const questionId = data.question_id;
                const $qBlock = $('#question_' + questionId);

                // Update question text
                $qBlock.find('strong').html(`Q. ${data.question}`);

                // Update options
                let options = [
                    { id: 1, text: data.option_one },
                    { id: 2, text: data.option_two },
                    { id: 3, text: data.option_three },
                    { id: 4, text: data.option_four }
                ];

                options.forEach(option => {
                    if (option.text && option.text.trim() !== '') {
                        const isCorrect = data.correct_answer == option.id;
                        const textClass = isCorrect ? 'text-success fw-bold' : '';
                        const label = isCorrect ? '<i class="fas fa-check fa-fw text-success"></i>' : `(${String.fromCharCode(96 + option.id)}).`;

                        $('#question_option_' + option.id + '_' + questionId).html(`
                            <p class="${textClass}">${label} ${option.text}</p>
                        `);
                    }
                });

                $('#description_' + questionId).html(data.content ?? '');

                // Flash highlight
                $qBlock.addClass('bg-warning-subtle');
                setTimeout(() => $qBlock.removeClass('bg-warning-subtle'), 1000);
            }

            var _modalClassFormValidation1 = function () {
                if ($('.ajax-form').length > 0) {
                    $('.ajax-form').parsley().on('field:validated', function () {
                        var ok = $('.parsley-error').length === 0;
                        $('.bs-callout-info').toggleClass('hidden', !ok);
                        $('.bs-callout-warning').toggleClass('hidden', ok);
                    });
                }
                $('.ajax-form').on('submit', function (e) {
                    e.preventDefault();
                    $('#submit').hide();
                    $('#submitting').show();
                    $(".ajax_error").remove();
                    var submit_url = $('.ajax-form').attr('action');
                    var formData = new FormData($(".ajax-form")[0]);
                    $.ajax({
                        url: submit_url,
                        type: 'POST',
                        data: formData,
                        contentType: false,
                        cache: false,
                        processData: false,
                        dataType: 'JSON',
                        success: function (data) {
                            if (data.status) {
                                toastr.success(data.message);
                                $('#submit').show();
                                $('#submitting').hide();

                                if (!data.stay) {
                                    $('#modal_remote').modal('toggle');
                                }

                                if (data.question_id) {
                                    updateQuestionDOM(data);
                                }
                            } else {
                                if (data.validator) {
                                    for (const [key, messages] of Object.entries(data.message)) {
                                        messages.forEach(message => {
                                            toastr.error(message);
                                        });
                                    }
                                } else {
                                    toastr.error(data.message);
                                }
                            }
                        },
                        error: function (data) {
                            var jsonValue = data.responseJSON;
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
                                toastr.error(jsonValue.message);
                            }

                            $('#submit').show();
                            $('#submitting').hide();
                        }
                    });
                });
            };
        })
    </script>
@endpush
