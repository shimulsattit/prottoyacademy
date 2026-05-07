@extends('layouts.portal-auth', ['title' => 'Login Portal'])

@section('content')
    <div class="d-flex flex-column flex-root" id="app_root">
        <div class="d-flex flex-column flex-lg-row flex-column-fluid">
            <div class="d-flex flex-column flex-lg-row-fluid w-lg-50 order-2 order-lg-1">
                <div class="d-flex flex-center flex-column flex-lg-row-fluid">
                    <div class="w-lg-500px p-10">
                        <form class="form w-100" id="sign_in_form" method="POST" action="{{ route('portal.login.post') }}">
                            <div class="text-center mb-11">
                                <a href="{{ route('home') }}" class="mb-0 mb-lg-12">
                                    <img alt="Logo" src="{{ asset(get_settings('system_logo') ?? 'portal-resource/images/custom-1.png') }}" class="h-60px h-lg-75px" />
                                </a>
                                <h1 class="text-gray-900 fw-bolder mb-3">Sign In</h1>
                                <div class="text-gray-500 fw-semibold fs-6">Your Social Campaigns</div>
                            </div>

                            <div class="fv-row mb-8">
                                <input type="text" placeholder="Email" name="email" autocomplete="off" class="form-control bg-transparent" />
                            </div>
                            <div class="fv-row mb-3">
                                <input type="password" placeholder="Password" name="password" autocomplete="off" class="form-control bg-transparent" />
                            </div>
                            <div class="d-flex flex-stack flex-wrap gap-3 fs-base fw-semibold mb-8">
                                <div></div>
                                <a href="javascript:;" class="link-primary">Forgot Password ?</a>
                            </div>
                            <div class="d-grid mb-10">
                                <button type="submit" id="sign_in_submit" class="btn btn-primary">
                                    <span class="indicator-label">Sign In</span>
                                    <span class="indicator-progress">Please wait... 
                                    <span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                                </button>
                            </div>
                            <div class="text-gray-500 text-center fw-semibold fs-6">Not a Member yet? 
                            <a href="authentication/layouts/corporate/sign-up.html" class="link-primary">Sign up</a></div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="d-flex flex-lg-row-fluid w-lg-50 bgi-size-cover bgi-position-center order-1 order-lg-2" style="background-image: url({{ asset('portal-resource/images/auth-bg.png') }})">
                <div class="d-flex flex-column flex-center py-7 py-lg-15 px-5 px-md-15 w-100">
                    <img class="d-none d-lg-block mx-auto w-275px w-md-50 w-xl-500px mb-10 mb-lg-20" src="{{ asset('portal-resource/images/auth-screens.png') }}" alt="" />
                    <h1 class="d-none d-lg-block text-white fs-2qx fw-bolder text-center mb-7">{{ get_settings('system_name') }}</h1>
               </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('portal-resource/js/pages/login.js') }}"></script>
@endpush
