@extends('layouts.web', ['title' => 'Student Profile'])

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
                                    Student Profile
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
                        <form action="{{ route('update.profile') }}" method="POST" class="login-form" enctype="multipart/form-data">
                            <div class="row g-4">
                                <div class="col-12">
                                    <div class="card">
                                        <div class="card-header">
                                            <h4 class="card-title">General Information</h4>
                                        </div>
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-md-12 form-group mb-3">
                                                    <label for="name">Full Name <span class="text-danger">*</span></label>
                                                    <input type="text" class="form-control" id="name" name="name" value="{{ auth()->guard('student')->user()->name }}" required>
                                                </div>

                                                <div class="col-md-12 form-group mb-3">
                                                    <label for="username">Username <span class="text-danger">*</span></label>
                                                    <input type="text" class="form-control" id="username" name="username" value="{{ auth()->guard('student')->user()->username }}" required>
                                                </div>

                                                <div class="col-md-12 form-group mb-3">
                                                    <label for="avatar">Profile Photo</label>
                                                    <input type="file" class="form-control" id="avatar" name="avatar">
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Education Section -->
                                    <div class="card mt-4">
                                        <div class="card-header">
                                            <h4 class="card-title">Education</h4>
                                        </div>
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-md-12 form-group mb-3">
                                                    <label for="highest_education">Highest Education</label>
                                                    <input type="text" class="form-control" id="highest_education" name="highest_education" value="{{ auth()->guard('student')->user()->info->highest_education }}">
                                                </div>
                                                <div class="col-md-12 form-group mb-3">
                                                    <label for="university">University</label>
                                                    <input type="text" class="form-control" id="university" name="university" value="{{ auth()->guard('student')->user()->info->university }}">
                                                </div>
                                                <div class="col-md-12 form-group mb-3">
                                                    <label for="major">Major</label>
                                                    <input type="text" class="form-control" id="major" name="major" value="{{ auth()->guard('student')->user()->info->major }}">
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Professional Section -->
                                    <div class="card mt-4">
                                        <div class="card-header">
                                            <h4 class="card-title">Professional Information</h4>
                                        </div>
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-md-12 form-group mb-3">
                                                    <label for="current_job_title">Job Title</label>
                                                    <input type="text" class="form-control" id="current_job_title" name="current_job_title" value="{{ auth()->guard('student')->user()->info->current_job_title }}">
                                                </div>
                                                <div class="col-md-12 form-group mb-3">
                                                    <label for="current_company">Company</label>
                                                    <input type="text" class="form-control" id="current_company" name="current_company" value="{{ auth()->guard('student')->user()->info->current_company }}">
                                                </div>
                                                <div class="col-md-12 form-group mb-3">
                                                    <label for="years_of_experience">Years of Experience</label>
                                                    <input type="number" class="form-control" id="years_of_experience" name="years_of_experience" value="{{ auth()->guard('student')->user()->info->years_of_experience }}">
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Address Section -->
                                    <div class="card mt-4">
                                        <div class="card-header">
                                            <h4 class="card-title">Address</h4>
                                        </div>
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-md-12 form-group mb-3">
                                                    <label for="address">Address</label>
                                                    <input type="text" class="form-control" id="address" name="address" value="{{ auth()->guard('student')->user()->info->address }}">
                                                </div>
                                                <div class="col-md-12 form-group mb-3">
                                                    <label for="address_line_2">Address Line 2</label>
                                                    <input type="text" class="form-control" id="address_line_2" name="address_line_2" value="{{ auth()->guard('student')->user()->info->address_line_2 }}">
                                                </div>
                                                <div class="col-md-6 form-group mb-3">
                                                    <label for="city">City</label>
                                                    <input type="text" class="form-control" id="city" name="city" value="{{ auth()->guard('student')->user()->info->city }}">
                                                </div>
                                                <div class="col-md-6 form-group mb-3">
                                                    <label for="state">State</label>
                                                    <input type="text" class="form-control" id="state" name="state" value="{{ auth()->guard('student')->user()->info->state }}">
                                                </div>
                                                <div class="col-md-6 form-group mb-3">
                                                    <label for="postal_code">Postal Code</label>
                                                    <input type="text" class="form-control" id="postal_code" name="postal_code" value="{{ auth()->guard('student')->user()->info->postal_code }}">
                                                </div>
                                                <div class="col-md-6 form-group mb-3">
                                                    <label for="country">Country</label>
                                                    <input type="text" class="form-control" id="country" name="country" value="{{ auth()->guard('student')->user()->info->country }}">
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Social Media Section -->
                                    <div class="card mt-4">
                                        <div class="card-header">
                                            <h4 class="card-title">Social Media</h4>
                                        </div>
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-md-12 form-group mb-3">
                                                    <label for="linkedin_url">LinkedIn URL</label>
                                                    <input type="url" class="form-control" id="linkedin_url" name="linkedin_url" value="{{ auth()->guard('student')->user()->info->linkedin_url }}">
                                                </div>
                                                <div class="col-md-12 form-group mb-3">
                                                    <label for="github_url">GitHub URL</label>
                                                    <input type="url" class="form-control" id="github_url" name="github_url" value="{{ auth()->guard('student')->user()->info->github_url }}">
                                                </div>
                                                <div class="col-md-12 form-group mb-3">
                                                    <label for="twitter_url">Twitter URL</label>
                                                    <input type="url" class="form-control" id="twitter_url" name="twitter_url" value="{{ auth()->guard('student')->user()->info->twitter_url }}">
                                                </div>
                                                <div class="col-md-12 form-group mb-3">
                                                    <label for="facebook_url">Facebook URL</label>
                                                    <input type="url" class="form-control" id="facebook_url" name="facebook_url" value="{{ auth()->guard('student')->user()->info->facebook_url }}">
                                                </div>
                                                <div class="col-md-12 form-group mb-3">
                                                    <label for="instagram_url">Instagram URL</label>
                                                    <input type="url" class="form-control" id="instagram_url" name="instagram_url" value="{{ auth()->guard('student')->user()->info->instagram_url }}">
                                                </div>
                                                <div class="col-md-12 form-group mb-3">
                                                    <label for="youtube_url">YouTube URL</label>
                                                    <input type="url" class="form-control" id="youtube_url" name="youtube_url" value="{{ auth()->guard('student')->user()->info->youtube_url }}">
                                                </div>
                                                <div class="col-md-12 form-group mb-3">
                                                    <label for="personal_website_url">Personal Website URL</label>
                                                    <input type="url" class="form-control" id="personal_website_url" name="personal_website_url" value="{{ auth()->guard('student')->user()->info->personal_website_url }}">
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Privacy Settings Section -->
                                    <div class="card mt-4">
                                        <div class="card-header">
                                            <h4 class="card-title">Privacy Settings</h4>
                                        </div>
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-md-6 form-group mb-3">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox" id="show_email" name="show_email" value="1" {{ auth()->guard('student')->user()->info->show_email ? 'checked' : '' }}>
                                                        <label class="form-check-label" for="show_email">Show Email</label>
                                                    </div>
                                                </div>
                                                <div class="col-md-6 form-group mb-3">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox" id="show_mobile" name="show_mobile" value="1" {{ auth()->guard('student')->user()->info->show_mobile ? 'checked' : '' }}>
                                                        <label class="form-check-label" for="show_mobile">Show Mobile</label>
                                                    </div>
                                                </div>
                                                <div class="col-md-6 form-group mb-3">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox" id="show_education" name="show_education" value="1" {{ auth()->guard('student')->user()->info->show_education ? 'checked' : '' }}>
                                                        <label class="form-check-label" for="show_education">Show Education</label>
                                                    </div>
                                                </div>
                                                <div class="col-md-6 form-group mb-3">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox" id="show_professional" name="show_professional" value="1" {{ auth()->guard('student')->user()->info->show_professional ? 'checked' : '' }}>
                                                        <label class="form-check-label" for="show_professional">Show Professional Info</label>
                                                    </div>
                                                </div>
                                                <div class="col-md-6 form-group mb-3">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox" id="show_address" name="show_address" value="1" {{ auth()->guard('student')->user()->info->show_address ? 'checked' : '' }}>
                                                        <label class="form-check-label" for="show_address">Show Address</label>
                                                    </div>
                                                </div>
                                                <div class="col-md-6 form-group mb-3">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox" id="show_social_media" name="show_social_media" value="1" {{ auth()->guard('student')->user()->info->show_social_media ? 'checked' : '' }}>
                                                        <label class="form-check-label" for="show_social_media">Show Social Media</label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="mt-4">
                                        <button class="rn-btn edu-btn w-100 mb--15" id="submit" type="submit">
                                            <span>Save Changes</span>
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
