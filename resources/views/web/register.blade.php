@extends('layouts.web', ['title' => 'Register | Prottoy Academy'])

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

    .register-wrapper {
        min-height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 120px 20px 80px 20px;
        background: radial-gradient(circle at bottom right, rgba(245, 197, 24, 0.05), transparent),
                    radial-gradient(circle at top left, rgba(255, 138, 0, 0.05), transparent);
    }

    .glass-register-card {
        background: var(--card-bg);
        backdrop-filter: blur(20px);
        -webkit-backdrop-filter: blur(20px);
        border: 1px solid var(--glass-border);
        border-radius: 30px;
        padding: 45px 40px;
        width: 100%;
        max-width: 550px;
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
    }

    .register-title {
        font-weight: 800;
        font-size: 2.2rem;
        margin-bottom: 10px;
        background: linear-gradient(135deg, #fff, #a5a5a5);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        text-align: center;
    }

    .register-subtitle {
        color: var(--text-gray);
        text-align: center;
        margin-bottom: 35px;
        font-size: 1.1rem;
    }

    .input-group-custom {
        margin-bottom: 20px;
    }

    .input-group-custom input {
        width: 100%;
        background: rgba(255, 255, 255, 0.04);
        border: 1px solid var(--glass-border);
        border-radius: 15px;
        padding: 14px 20px;
        color: #fff;
        font-size: 15px;
        transition: all 0.3s ease;
    }

    .input-group-custom input:focus {
        background: rgba(255, 255, 255, 0.07);
        border-color: var(--accent-gold);
        outline: none;
        box-shadow: 0 0 15px rgba(245, 197, 24, 0.1);
    }

    .btn-register {
        background: linear-gradient(135deg, var(--accent-gold), var(--accent-orange));
        border: none;
        border-radius: 15px;
        padding: 14px;
        color: #07091e;
        font-weight: 700;
        font-size: 17px;
        width: 100%;
        transition: all 0.3s ease;
        margin-top: 15px;
    }

    .btn-register:hover {
        transform: translateY(-3px);
        box-shadow: 0 10px 20px rgba(245, 197, 24, 0.3);
        color: #000;
    }

    .register-footer-links {
        margin-top: 25px;
        text-align: center;
        font-size: 14px;
        color: var(--text-gray);
    }

    .register-footer-links a {
        color: var(--accent-gold);
        text-decoration: none;
        font-weight: 600;
    }

    .register-footer-links a:hover {
        text-decoration: underline;
    }

    /* Override EDU-VIBE */
    .edu-breadcrumb-area { display: none !important; }
    .login-register-page-wrapper { background: transparent !important; padding: 0 !important; }

    @media (max-width: 576px) {
        .register-wrapper { padding: 80px 16px 40px; }
        .glass-register-card { padding: 35px 24px; border-radius: 20px; }
        .register-title { font-size: 1.8rem; }
        .register-subtitle { font-size: 1rem; margin-bottom: 25px; }
        .input-group-custom { margin-bottom: 15px; }
        .input-group-custom input { padding: 12px 16px; font-size: 14px; }
        .btn-register { padding: 12px; font-size: 16px; }
    }
</style>
@endpush

@section('content')
<div class="register-wrapper">
    <div class="glass-register-card">
        <h2 class="register-title">নতুন একাউন্ট</h2>
        <p class="register-subtitle">প্রত্যয় একাডেমিতে আপনাকে স্বাগতম</p>

        <form class="login-form" action="{{ route('register.post') }}" method="POST">
            @csrf
            <div class="input-group-custom">
                <input type="text" name="name" id="name" required placeholder="আপনার পূর্ণ নাম" />
            </div>
            
            <div class="input-group-custom">
                <input type="email" name="email" id="email" required placeholder="ইমেইল এড্রেস" />
            </div>
            
            <div class="input-group-custom">
                <input type="password" name="password" id="password" required placeholder="পাসওয়ার্ড" />
            </div>
            
            <div class="input-group-custom">
                <input type="password" name="password_confirmation" id="password_confirmation" required placeholder="পাসওয়ার্ড নিশ্চিত করুন" />
            </div>

            <button class="btn-register" id="submit" type="submit">একাউন্ট তৈরি করুন</button>
            
            <button class="btn-register" id="submitting" disabled type="button" style="display: none; opacity: 0.8;">
                <span class="spinner-border spinner-border-sm me-2"></span> প্রসেসিং হচ্ছে...
            </button>

            <div class="register-footer-links">
                ইতিমধ্যেই একাউন্ট আছে? <a href="{{ route('login') }}">লগইন করুন</a>
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
                                setTimeout(() => window.location.href = data.goto, 1000);
                            } else {
                                setTimeout(() => window.location.reload(), 1000);
                            }
                        }
                        $('#submit').show();
                        $('#submitting').hide();
                    },
                    error: function (data) {
                        toastr.error('রেজিস্ট্রেশন ব্যর্থ হয়েছে। আবার চেষ্টা করুন।');
                        $('#submit').show();
                        $('#submitting').hide();
                    }
                });
            });
        });
    </script>
@endpush
