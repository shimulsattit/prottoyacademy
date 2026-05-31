@extends('layouts.admin', ['title' => 'General Configuration'])
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
                        General Configuration
                    </h1>
                    <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">
                        <li class="breadcrumb-item text-muted">
                            <a href="index.html" class="text-muted text-hover-primary">Home</a>
                        </li>
                        <li class="breadcrumb-item">
                            <span class="bullet bg-gray-500 w-5px h-2px"></span>
                        </li>
                        <li class="breadcrumb-item text-muted">General Configuration</li>
                    </ul>
                </div>
            </div>
        </div>

        <div id="app_content" class="app-content flex-column-fluid">
            <div id="app_content_container" class="app-container container-xxl">
                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('portal.settings.update') }}" method="POST" enctype="multipart/form-data" class="content_form">
                            <div class="row">
                                <div class="col-md-12 mb-3 form-group">
                                    <label for="system_name">System Name <span class="text-danger">*</span></label>
                                    <input type="text" name="system_name" id="system_name" class="form-control" required value="{{ get_settings('system_name') }}">
                                </div>

                                <div class="col-md-12 mb-3 form-group">
                                    <label for="system_footer_text">Footer Text <span class="text-danger">*</span></label>
                                    <textarea name="system_footer_text" id="system_footer_text" cols="30" rows="3" class="form-control">{{ get_settings('system_footer_text') }}</textarea>
                                </div>

                                <div class="col-md-12 mb-3 form-group">
                                    <label for="system_address">Address <span class="text-danger">*</span></label>
                                    <textarea name="system_address" id="system_address" cols="30" rows="3" class="form-control">{{ get_settings('system_address') }}</textarea>
                                </div>

                                <div class="col-md-6 mb-3 form-group">
                                    <label for="system_email">Email Address <span class="text-danger">*</span></label>
                                    <input type="email" name="system_email" id="system_email" class="form-control" required value="{{ get_settings('system_email') }}">
                                </div>

                                <div class="col-md-3 mb-3 form-group">
                                    <label for="system_phone">Phone Number <span class="text-danger">*</span></label>
                                    <input type="text" name="system_phone" id="system_phone" class="form-control" required value="{{ get_settings('system_phone') }}">
                                </div>
                                
                                <div class="col-md-3 mb-3 form-group">
                                    <label for="system_alternate_phone">Alternate Phone Number <span class="text-danger">*</span></label>
                                    <input type="text" name="system_alternate_phone" id="system_alternate_phone" class="form-control" required value="{{ get_settings('system_alternate_phone') }}">
                                </div>

                                <div class="col-md-12 mb-3 form-group">
                                    <label for="system_logo">Logo</label>
                                    <input type="file" name="system_logo" id="system_logo" class="form-control dropify" data-default-file="{{ get_settings('system_logo') ? asset(get_settings('system_logo')) : '' }}">
                                </div>

                                <div class="col-md-12 mb-3 form-group">
                                    <label for="system_favicon">Favicon</label>
                                    <input type="file" name="system_favicon" id="system_favicon" class="form-control dropify" data-default-file="{{ get_settings('system_favicon') ? asset(get_settings('system_favicon')) : '' }}">
                                </div>

                                <div class="col-md-12 mb-3 form-group">
                                    <label for="system_facebook_url">Facebook URL</label>
                                    <input type="text" name="system_facebook_url" id="system_facebook_url" class="form-control" value="{{ get_settings('system_facebook_url') }}">
                                </div>

                                <div class="col-md-12 mb-3 form-group">
                                    <label for="system_twitter_url">Twitter URL</label>
                                    <input type="text" name="system_twitter_url" id="system_twitter_url" class="form-control" value="{{ get_settings('system_twitter_url') }}">
                                </div>

                                <div class="col-md-12 mb-3 form-group">
                                    <label for="system_instagram_url">Instagram URL</label>
                                    <input type="text" name="system_instagram_url" id="system_instagram_url" class="form-control" value="{{ get_settings('system_instagram_url') }}">
                                </div>

                                <div class="col-md-12 mb-3 form-group">
                                    <label for="system_linkedin_url">LinkedIn URL</label>
                                    <input type="text" name="system_linkedin_url" id="system_linkedin_url" class="form-control" value="{{ get_settings('system_linkedin_url') }}">
                                </div>

                                <div class="col-md-12 mb-3 form-group">
                                    <label for="system_youtube_url">Youtube URL</label>
                                    <input type="text" name="system_youtube_url" id="system_youtube_url" class="form-control" value="{{ get_settings('system_youtube_url') }}">
                                </div>

                                <!-- ============================================================== -->
                                <!-- SEO, Google Analytics & Custom Scripts Integration -->
                                <!-- ============================================================== -->
                                <div class="col-md-12 my-4">
                                    <div class="separator separator-dashed my-5"></div>
                                    <h3 class="text-gray-900 fw-bold mb-5">
                                        <i class="fas fa-search-plus text-primary me-2"></i> SEO, Analytics & Custom Scripts
                                    </h3>
                                </div>

                                <!-- google_analytics_id -->
                                <div class="col-md-6 mb-4 form-group">
                                    <label for="google_analytics_id" class="fw-semibold text-gray-700 mb-1">Google Analytics Measurement ID (GA4)</label>
                                    <input type="text" name="google_analytics_id" id="google_analytics_id" class="form-control" placeholder="e.g. G-XXXXXXXXXX" value="{{ get_settings('google_analytics_id') }}">
                                    <span class="fs-7 text-muted">আপনার Google Analytics 4 (GA4) এর Measurement ID টি এখানে দিন (যেমন: G-XXXXXXXXXX)।</span>
                                </div>

                                <!-- google_search_console_id -->
                                <div class="col-md-6 mb-4 form-group">
                                    <label for="google_search_console_id" class="fw-semibold text-gray-700 mb-1">Google Search Console Verification Token</label>
                                    <input type="text" name="google_search_console_id" id="google_search_console_id" class="form-control" placeholder="e.g. google-site-verification token" value="{{ get_settings('google_search_console_id') }}">
                                    <span class="fs-7 text-muted">Google Search Console এর HTML tag ভেরিফিকেশনের <code>content</code> মানটি এখানে দিন।</span>
                                </div>

                                <!-- custom_header_scripts -->
                                <div class="col-md-12 mb-4 form-group">
                                    <label for="custom_header_scripts" class="fw-semibold text-gray-700 mb-1">Custom Header Scripts (&lt;head&gt;)</label>
                                    <textarea name="custom_header_scripts" id="custom_header_scripts" cols="30" rows="4" class="form-control font-monospace bg-dark text-success" placeholder="<!-- Insert code here, e.g., Meta Pixel, custom stylesheets -->">{{ get_settings('custom_header_scripts') }}</textarea>
                                    <span class="fs-7 text-muted">এই কোডটি ওয়েবসাইটের <code>&lt;head&gt;</code> ট্যাগ শেষ হওয়ার ঠিক আগে যুক্ত হবে।</span>
                                </div>

                                <!-- custom_footer_scripts -->
                                <div class="col-md-12 mb-5 form-group">
                                    <label for="custom_footer_scripts" class="fw-semibold text-gray-700 mb-1">Custom Footer Scripts (&lt;/body&gt;)</label>
                                    <textarea name="custom_footer_scripts" id="custom_footer_scripts" cols="30" rows="4" class="form-control font-monospace bg-dark text-success" placeholder="<!-- Insert code here, e.g., Live Chat widgets, custom JS -->">{{ get_settings('custom_footer_scripts') }}</textarea>
                                    <span class="fs-7 text-muted">এই কোডটি ওয়েবসাইটের <code>&lt;/body&gt;</code> ট্যাগ শেষ হওয়ার ঠিক ওপরে যুক্ত হবে।</span>
                                </div>

                                @if (Auth::guard('admin')->user()->hasPermissionTo('settings.update'))
                                    <div class="col-md-4 mb-3 mx-auto">
                                        <button type="submit" class="btn btn-sm btn-block btn-primary" id="submit">
                                            <i class="fas fa-paper-plane fa-fw"></i> Update    
                                        </button>
                                        <button type="button" class="btn btn-sm btn-block btn-outline-primary" id="submitting" style="display: none;">
                                            <i class="fas fa-spinner fa-spin fa-fw"></i> Processing    
                                        </button>
                                    </div>
                                @endif
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
    <script src="{{ asset('portal-resource/js/dropify.min.js') }}"></script>
    <script>
        _componentSelect();
        _formValidation();

        $('.dropify').dropify({
            imgFileExtensions: ['png', 'jpg', 'ico', 'jpeg', 'gif', 'bmp', 'webp']
        });
    </script>
@endpush
