@extends('layouts.web', ['title' => 'Student Password Changes'])

@push('meta')

@endpush

@push('style')

@endpush

@section('content')
    <div class="edu-breadcrumb-area breadcrumb-style-1 ptb--60 ptb_md--40 ptb_sm--40 bg-image">
        <div class="container eduvibe-animated-shape">
            <div class="row">
                <div class="col-lg-12">
                    <div class="breadcrumb-inner text-start">
                        <div class="page-title">
                            <h3 class="title">Student Profile</h3>
                        </div>
                        <nav class="edu-breadcrumb-nav">
                            <ol class="edu-breadcrumb d-flex justify-content-start liststyle">
                                <li class="breadcrumb-item">
                                    <a href="{{ route('home') }}">
                                        Home
                                    </a>
                                </li>
                                <li class="separator">
                                    <i class="ri-arrow-drop-right-line"></i>
                                </li>
                                <li class="breadcrumb-item active" aria-current="page">
                                    Student Password Changes
                                </li>
                            </ol>
                        </nav>
                    </div>
                </div>
            </div>

            <div class="shape-dot-wrapper shape-wrapper d-xl-block d-none">
                <div class="shape-dot-wrapper shape-wrapper d-xl-block d-none">
                    <div class="shape-image shape-image-1">
                        <img src="{{ asset('assets/images/shapes/shape-11-07.png') }}" alt="Breadcrumb Shape Thumb One" />
                    </div>
                    <div class="shape-image shape-image-2">
                        <img src="{{ asset('assets/images/shapes/shape-01-02.png') }}" alt="Breadcrumb Shape Thumb Two" />
                    </div>
                    <div class="shape-image shape-image-3">
                        <img src="{{ asset('assets/images/shapes/shape-03.png') }}" alt="Breadcrumb Shape Thumb Three" />
                    </div>
                    <div class="shape-image shape-image-4">
                        <img src="{{ asset('assets/images/shapes/shape-13-12.png') }}" alt="Breadcrumb Shape Thumb Four" />
                    </div>
                    <div class="shape-image shape-image-5">
                        <img src="{{ asset('assets/images/shapes/shape-36.png') }}" alt="Breadcrumb Shape Thumb Five" />
                    </div>
                    <div class="shape-image shape-image-6">
                        <img src="{{ asset('assets/images/shapes/shape-05-07.png') }}" alt="Breadcrumb Shape Thumb Six" />
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="edu-elements-area edu-section-gap bg-color-white">
        <div class="container">
            <div class="row g-5">
                <div class="col-lg-3">
                    @include('web.student.partials.sidebar')
                </div>
                <div class="col-lg-9">
                    <!-- Main content area -->
                    <div class="dashboard-content">
                        <form action="{{ route('update.password') }}" method="POST" class="login-form" enctype="multipart/form-data">
                            <div class="row g-4">
                                <div class="col-12">
                                    <div class="card">
                                        <div class="card-header">
                                            <h4 class="card-title">Change Password</h4>
                                        </div>
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-md-12 form-group mb-3">
                                                    <label for="current_password">Current Password</label>
                                                    <input type="password" class="form-control" id="current_password" name="current_password" required>
                                                </div>
                                                <div class="col-md-12 form-group mb-3">
                                                    <label for="new_password">New Password</label>
                                                    <input type="password" class="form-control" id="new_password" name="new_password" required>
                                                </div>
                                                <div class="col-md-12 form-group mb-3">
                                                    <label for="confirm_new_password">Confirm New Password</label>
                                                    <input type="password" class="form-control" id="confirm_new_password" name="new_password_confirmation" required>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="mt-4">
                                        <button class="rn-btn edu-btn w-100 mb--15" id="submit" type="submit">
                                            <span>Update Password</span>
                                        </button>
                                        <button class="rn-btn edu-btn w-100 mb--15" id="submitting" disabled type="button" style="display: none;">
                                            <span>Processing</span>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
    <script>
        var _formValidation = function () {
            if ($('.login-form').length > 0) {
                $('.login-form').parsley().on('field:validated', function () {
                    var ok = $('.parsley-error').length === 0;
                    $('.bs-callout-info').toggleClass('hidden', !ok);
                    $('.bs-callout-warning').toggleClass('hidden', ok);
                });
            }

            $('.login-form').on('submit', function (e) {
                e.preventDefault();

                $('#submit').hide();
                $('#submitting').show();

                $(".ajax_error").remove();

                var submit_url = $('.login-form').attr('action');
                var formData = new FormData($(".login-form")[0]);
                var editor = $('.login-form').data('editor');
                if (editor && editor != '') {
                    const descriptionData = CKEDITOR.instances[editor].getData();
                    console.log(descriptionData);
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

                            $('.login-form')[0].reset();
                            if (data.goto) {
                                setTimeout(function () {

                                    window.location.href = data.goto;
                                }, 500);
                            }

                            if (data.load) {
                                setTimeout(function () {

                                    window.location.href = "";
                                }, 500);
                            }

                            if (data.window) {
                                $('.login-form')[0].reset();
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
        };

        _formValidation();
    </script>
@endpush
