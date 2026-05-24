@extends('layouts.web', ['title' => 'Student Password Changes'])

@push('meta')

@endpush

@push('style')
<style>
    :root {
        --dark-bg: #07091e;
        --card-bg: rgba(255, 255, 255, 0.03);
        --glass-border: rgba(255, 255, 255, 0.08);
        --accent-gold: #f5c518;
        --text-white: #ffffff;
        --text-gray: rgba(255, 255, 255, 0.6);
    }

    body {
        background-color: var(--dark-bg) !important;
        color: var(--text-white) !important;
        font-family: 'Inter', 'Noto Sans Bengali', sans-serif;
    }
    
    .header-area, .footer-area {
        display: none !important;
    }
    
    .dashboard-wrapper {
        display: block;
        min-height: 100vh;
        background: var(--dark-bg);
    }
    
    .dashboard-content-area {
        margin-left: 260px; /* Account for fixed sidebar */
        padding: 40px;
        min-height: 100vh;
    }

    /* GLASS CARDS */
    .glass-card {
        background: var(--card-bg);
        backdrop-filter: blur(15px);
        -webkit-backdrop-filter: blur(15px);
        border: 1px solid var(--glass-border);
        border-radius: 24px;
        padding: 30px;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        text-decoration: none;
        display: block;
        margin-bottom: 25px;
    }
    
    .glass-card .card-header {
        background: transparent !important;
        border-bottom: 1px solid var(--glass-border) !important;
        padding-bottom: 15px;
        margin-bottom: 20px;
    }
    
    .glass-card .card-title {
        color: #fff;
        font-weight: 700;
        font-size: 1.3rem;
        margin-bottom: 0;
    }

    .form-control {
        background: rgba(255, 255, 255, 0.05) !important;
        border: 1px solid rgba(255, 255, 255, 0.1) !important;
        color: #fff !important;
        border-radius: 12px !important;
        padding: 12px 18px !important;
    }

    .form-control:focus {
        border-color: var(--accent-gold) !important;
        box-shadow: 0 0 0 0.25rem rgba(245, 197, 24, 0.25) !important;
    }

    label {
        color: var(--text-gray) !important;
        font-weight: 500;
        margin-bottom: 8px;
    }

    .section-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 35px;
    }

    .section-header h4 {
        font-weight: 800;
        font-size: 1.8rem;
        background: linear-gradient(135deg, #fff, #a5a5a5);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }

    @media (max-width: 991px) {
        .dashboard-content-area { margin-left: 0; padding: 30px 20px; }
        .mobile-header {
            display: flex !important;
            background: rgba(7, 9, 30, 0.95);
            backdrop-filter: blur(15px);
            padding: 15px 25px;
            justify-content: space-between;
            align-items: center;
            border-bottom: 1px solid var(--glass-border);
            position: sticky;
            top: 0;
            z-index: 1002;
        }
    }

    .mobile-header { display: none; }
    .menu-toggle { background: none; border: none; color: #fff; font-size: 26px; cursor: pointer; }
</style>
@endpush

@section('content')
<div class="mobile-header">
    <h5 class="fw-bold mb-0" style="color: var(--accent-gold);">Prottoy</h5>
    <button class="menu-toggle" id="mobile-sidebar-toggle">
        <i class="ri-menu-5-line"></i>
    </button>
</div>

<div class="dashboard-wrapper">
    @include('web.student.partials.sidebar')

    <!-- Main Content -->
    <div class="dashboard-content-area">
        <div class="section-header">
            <h4>পাসওয়ার্ড পরিবর্তন</h4>
        </div>

        <form action="{{ route('update.password') }}" method="POST" class="login-form" enctype="multipart/form-data">
            <div class="row">
                <div class="col-lg-12">
                    <div class="glass-card">
                        <div class="card-header">
                            <h4 class="card-title">Change Password</h4>
                        </div>
                        <div class="card-body p-0">
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
                        <button class="rn-btn edu-btn w-100 mb--15" id="submit" type="submit" style="background: linear-gradient(135deg, #f5c518, #ff8a00) !important; color: #07091e !important; font-weight: 700; border: none; border-radius: 12px; padding: 15px;">
                            <span>Update Password</span>
                        </button>
                        <button class="rn-btn edu-btn w-100 mb--15" id="submitting" disabled type="button" style="display: none; background: linear-gradient(135deg, #f5c518, #ff8a00) !important; color: #07091e !important; font-weight: 700; border: none; border-radius: 12px; padding: 15px;">
                            <span>Processing</span>
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            $('#mobile-sidebar-toggle').on('click', function() {
                $('.student-sidebar').toggleClass('active');
                $(this).find('i').toggleClass('ri-menu-5-line ri-close-line');
            });

            $(document).on('click', function(e) {
                if ($(window).width() <= 991) {
                    if (!$(e.target).closest('.student-sidebar').length && !$(e.target).closest('#mobile-sidebar-toggle').length) {
                        $('.student-sidebar').removeClass('active');
                        $('#mobile-sidebar-toggle').find('i').removeClass('ri-close-line').addClass('ri-menu-5-line');
                    }
                }
            });
        });

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
