@extends('layouts.web', ['title' => 'Login | Prottoy Academy'])

@push('style')
<style>
    :root {
        --dark-bg: #07091e;
        --card-bg: rgba(255, 255, 255, 0.03);
        --glass-border: rgba(255, 255, 255, 0.08);
        --accent-gold: #f5c518;
        --accent-orange: #ff8a00;
        --text-white: #ffffff;
        --text-gray: rgba(255, 255, 255, 0.6);
    }

    body {
        background-color: var(--dark-bg) !important;
        color: var(--text-white) !important;
    }

    .login-wrapper {
        min-height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 120px 20px 80px 20px;
        background: radial-gradient(circle at top right, rgba(245, 197, 24, 0.05), transparent),
                    radial-gradient(circle at bottom left, rgba(255, 138, 0, 0.05), transparent);
    }

    .glass-login-card {
        background: var(--card-bg);
        backdrop-filter: blur(20px);
        -webkit-backdrop-filter: blur(20px);
        border: 1px solid var(--glass-border);
        border-radius: 30px;
        padding: 50px 40px;
        width: 100%;
        max-width: 500px;
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
    }

    .login-title {
        font-weight: 800;
        font-size: 2.2rem;
        margin-bottom: 10px;
        background: linear-gradient(135deg, #fff, #a5a5a5);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        text-align: center;
    }

    .login-subtitle {
        color: var(--text-gray);
        text-align: center;
        margin-bottom: 40px;
        font-size: 1.1rem;
    }

    .input-group-custom {
        margin-bottom: 25px;
        position: relative;
    }

    .input-group-custom input {
        width: 100%;
        background: rgba(255, 255, 255, 0.04);
        border: 1px solid var(--glass-border);
        border-radius: 15px;
        padding: 15px 20px;
        color: #fff;
        font-size: 16px;
        transition: all 0.3s ease;
    }

    .input-group-custom input:focus {
        background: rgba(255, 255, 255, 0.07);
        border-color: var(--accent-gold);
        outline: none;
        box-shadow: 0 0 15px rgba(245, 197, 24, 0.1);
    }

    .btn-login {
        background: linear-gradient(135deg, var(--accent-gold), var(--accent-orange));
        border: none;
        border-radius: 15px;
        padding: 15px;
        color: #07091e;
        font-weight: 700;
        font-size: 17px;
        width: 100%;
        transition: all 0.3s ease;
        margin-top: 10px;
    }

    .btn-login:hover {
        transform: translateY(-3px);
        box-shadow: 0 10px 20px rgba(245, 197, 24, 0.3);
        color: #000;
    }

    .google-btn {
        background: rgba(255, 255, 255, 0.05);
        border: 1px solid var(--glass-border);
        border-radius: 15px;
        padding: 12px;
        color: #fff;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 12px;
        width: 100%;
        text-decoration: none;
        margin-top: 20px;
        transition: all 0.3s ease;
    }

    .google-btn:hover {
        background: rgba(255, 255, 255, 0.1);
        border-color: #fff;
        color: #fff;
    }

    .login-footer-links {
        margin-top: 30px;
        text-align: center;
        font-size: 14px;
        color: var(--text-gray);
    }

    .login-footer-links a {
        color: var(--accent-gold);
        text-decoration: none;
        font-weight: 600;
    }

    .login-footer-links a:hover {
        text-decoration: underline;
    }

    /* Override edu-vibe styles if they interfere */
    .edu-breadcrumb-area { display: none !important; }
    .login-register-page-wrapper { background: transparent !important; padding: 0 !important; }
</style>
@endpush

@section('content')
<div class="login-wrapper">
    <div class="glass-login-card">
        <h2 class="login-title">Student Portal</h2>
        <p class="login-subtitle">আপনার একাউন্টে লগইন করুন</p>

        <form class="login-form" action="{{ route('login.post') }}" method="POST">
            @csrf
            <div class="input-group-custom">
                <input type="email" name="email" id="email" required placeholder="ইমেইল এড্রেস" />
            </div>
            
            <div class="input-group-custom">
                <input type="password" name="password" id="password" required placeholder="পাসওয়ার্ড" />
            </div>

            <button class="btn-login" id="submit" type="submit">লগইন করুন</button>
            
            <button class="btn-login" id="submitting" disabled type="button" style="display: none; opacity: 0.7;">
                <span class="spinner-border spinner-border-sm me-2"></span> প্রসেসিং হচ্ছে...
            </button>

            <div class="separator-text my-4 text-center" style="position: relative;">
                <hr style="border-color: var(--glass-border);">
                <span style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); background: #11142e; padding: 0 15px; font-size: 12px; color: var(--text-gray);">অথবা</span>
            </div>

            <a href="{{ route('google.login') }}" class="google-btn">
                <img src="https://developers.google.com/identity/images/g-logo.png" alt="Google" width="20">
                <span>Google দিয়ে লগইন করুন</span>
            </a>

            <div class="login-footer-links">
                <a href="{{ route('forget.password') }}">পাসওয়ার্ড ভুলে গেছেন?</a>
                <div class="mt-2">
                    একাউন্ট নেই? <a href="{{ route('register') }}">নতুন একাউন্ট খুলুন</a>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            $('.login-form').on('submit', function (e) {
                e.preventDefault();

                $('#submit').hide();
                $('#submitting').show();

                var submit_url = $(this).attr('action');
                var formData = new FormData(this);

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
                                    messages.forEach(message => toastr.error(message));
                                }
                            } else {
                                toastr.error(data.message);
                            }
                        } else {
                            toastr.success(data.message);
                            if (data.goto) {
                                setTimeout(() => window.location.href = data.goto, 500);
                            } else {
                                setTimeout(() => window.location.reload(), 500);
                            }
                        }
                        $('#submit').show();
                        $('#submitting').hide();
                    },
                    error: function (data) {
                        toastr.error('সার্ভার থেকে রেসপন্স পাওয়া যাচ্ছে না।');
                        $('#submit').show();
                        $('#submitting').hide();
                    }
                });
            });
        });
    </script>
@endpush
