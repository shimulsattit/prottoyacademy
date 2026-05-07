@extends('layouts.admin', ['title' => 'Quiz Question Management', 'modal' => 'lg'])
@section('content')
<div class="app-main flex-column flex-row-fluid" id="app_main">
    <div class="d-flex flex-column flex-column-fluid">
        <div id="app_toolbar" class="app-toolbar py-3 py-lg-6">
            <div id="app_toolbar_container" class="app-container container-xxl d-flex flex-stack">
                <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
                    <h1 class="page-heading d-flex text-gray-900 fw-bold fs-3 flex-column justify-content-center my-0">
                        Quiz Question Management
                    </h1>
                    <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">
                        <li class="breadcrumb-item text-muted">
                            <a href="{{ route('portal.dashboard') }}" class="text-muted text-hover-primary">Home</a>
                        </li>
                        <li class="breadcrumb-item">
                            <span class="bullet bg-gray-500 w-5px h-2px"></span>
                        </li>
                        <li class="breadcrumb-item text-muted">Quiz Question Management</li>
                    </ul>
                </div>

                @if(Auth::guard('admin')->user()->hasPermissionTo('question.create'))
                    <div class="d-flex align-items-center gap-2 gap-lg-3">
                        <a href="javascript:void(0)" class="btn btn-sm fw-bold btn-primary" onclick="handleCreateQuestion()">
                            Create
                        </a>
                    </div>
                @endif
            </div>
        </div>

        <div id="app_content" class="app-content flex-column-fluid">
            <div id="app_content_container" class="app-container container-xxl">
                <div class="card">
                    <div class="card-body">
                        <div class="row mt-3">
                            <div class="col-md-3">
                                <div id="category-panel">
                                    {{-- Parent Categories --}}
                                    <ul class="list-group" id="category-list">
                                        @foreach ($categories as $category)
                                            <li style="cursor: pointer;" class="list-group-item category-item" data-id="{{ $category->id }}">
                                                {{ $category->name }}
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        
                            <div class="col-md-9">
                                <div id="question-content"></div>
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

@endpush

@push('scripts')
    <script>
        function handleCreateQuestion() {
                if (!selectedCategoryId) {
                    toastr.warning('Please select a category first.');
                    return;
                }

                const url = `/portal/question/create?category_id=${selectedCategoryId}`;
                window.location.href = url;
            }

        $(document).ready(function () {
            

            // Handle category click with indentation level tracking
            $(document).on('click', '.category-item', function () {
                const id = $(this).data('id');
                const level = parseInt($(this).data('level')) || 0;
                const nextLevel = level + 1;

                // Highlight selected category
                $('.category-item').removeClass('active bg-primary text-white');
                $(this).addClass('active bg-primary text-white');

                // Clear previous question content and show loader there
                $('#question-content').html(`
                    <div class="text-center my-4" id="question-loader">
                        <div class="spinner-border text-primary" role="status" style="width: 2rem; height: 2rem;">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                    </div>
                `);

                $.get(`/portal/quiz/children/${id}`, function (data) {
                    $('#question-loader').remove();

                    if (data.length > 0) {
                        let html = '<ul class="list-group">';
                        data.forEach(item => {
                            html += `<li style="cursor:pointer; margin-left:${nextLevel * 15}px;" 
                                        class="list-group-item category-item" 
                                        data-id="${item.id}" 
                                        data-level="${nextLevel}">
                                        ${item.name}
                                    </li>`;
                        });
                        html += '</ul>';
                        $('#category-panel').append(html);
                    } else {
                        loadQuestionsTab(id);
                    }
                }).fail(function () {
                    $('#question-loader').remove();
                    toastr.error('Failed to load category data');
                });
            });

            let currentOffset = 0;
            const limit = 20;
            let currentCategoryId = null;

            function loadQuestionsTab(categoryId, append = false) {
                selectedCategoryId = categoryId; 

                $.get(`/portal/quiz/questions/${categoryId}`, { offset: currentOffset }, function (response) {
                    const questions = response.questions;
                    const hasMore = response.has_more;

                    if (questions.length === 0 && currentOffset === 0) {
                        toastr.warning(`No questions found for this category`, 'Questions Not Found');
                        $('#question-content').html(`<div class="text-center text-muted py-4">No questions found in this category.</div>`);
                        return;
                    }

                    let contentHtml = '';
                    const optionLabels = ['(a)', '(b)', '(c)', '(d)'];

                    questions.forEach((q, index) => {
                        const questionNumber = currentOffset + index + 1;
                        const correctAnswer = parseInt(q.correct_answer || '0');

                        contentHtml += `
                            <div id="question_${q.id}" class="mt-3 border-bottom pb-2">
                                <div class="d-flex justify-content-between align-items-center">
                                    <strong>Q. ${q.question}</strong>
                                    <div>
                                        <button id="content_management" data-url="/portal/question/${q.id}/edit" class="btn btn-sm btn-primary btn-icon">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                    </div>
                                </div>
                        `;

                        q.options.forEach((opt, optIndex) => {
                            const isCorrect = (optIndex + 1) === correctAnswer;
                            const textClass = isCorrect ? 'text-success fw-bold' : '';

                            if (optIndex % 2 === 0) contentHtml += `<div class="row">`;

                            contentHtml += `
                                <div class="col-md-6" id="question_option_${optIndex + 1}_${q.id}">
                                    <p class="${textClass} mb-1">
                                        ${optionLabels[optIndex]} ${opt}
                                    </p>
                                </div>
                            `;

                            if (optIndex % 2 === 1 || optIndex === q.options.length - 1) contentHtml += `</div>`;
                        });

                        contentHtml += `</div>`;
                    });

                    if (append) {
                        $('#question-content').append(contentHtml);
                    } else {
                        $('#question-content').html(contentHtml);
                    }

                    $('#load-more-btn').remove();

                    if (hasMore) {
                        $('#question-content').append(`
                            <div class="text-center mt-3">
                                <button id="load-more-btn" class="btn btn-dark" data-category-id="${categoryId}">Load More</button>
                            </div>
                        `);
                    } else if (questions.length > 0) {
                        $('#question-content').append(`<div class="text-center mt-3 text-muted">No more questions left</div>`);
                    }

                    currentOffset += questions.length;
                });
            }


            $(document).on('click', '.category-item', function () {
                currentCategoryId = $(this).data('id');
                currentOffset = 0; // âœ… reset question numbering for new category
                loadQuestionsTab(currentCategoryId);
            });

            // Delegate Load More button click
            $(document).on('click', '#load-more-btn', function () {
                const categoryId = currentCategoryId;
                loadQuestionsTab(categoryId, true);
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

        });
    </script>
@endpush
