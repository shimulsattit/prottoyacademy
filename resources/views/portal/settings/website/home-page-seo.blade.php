@extends('layouts.admin', ['title' => 'Website Home Page SEO & Content Settings'])
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
                        Website Home Page SEO & Content Settings
                    </h1>
                    <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">
                        <li class="breadcrumb-item text-muted">
                            <a href="{{ route('portal.dashboard') }}" class="text-muted text-hover-primary">Home</a>
                        </li>
                        <li class="breadcrumb-item">
                            <span class="bullet bg-gray-500 w-5px h-2px"></span>
                        </li>
                        <li class="breadcrumb-item text-muted">Website Home Page SEO & Content Settings</li>
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

                                <!-- home_site_title -->
                                <div class="col-md-12 mb-3 form-group">
                                    <label for="home_site_title">Site Title <span class="text-danger">*</span></label>
                                    <input type="text" name="home_site_title" id="home_site_title" class="form-control" required value="{{ get_settings('home_site_title') }}">
                                </div>
                                
                                <!-- home_meta_title -->
                                <div class="col-md-12 mb-3 form-group">
                                    <label for="home_meta_title">Meta Title <span class="text-danger">*</span></label>
                                    <input type="text" name="home_meta_title" id="home_meta_title" class="form-control" required value="{{ get_settings('home_meta_title') }}">
                                </div>

                                <!-- home_meta_description -->
                                <div class="col-md-12 mb-3 form-group">
                                    <label for="home_meta_description">Meta Description <span class="text-danger">*</span></label>
                                    <textarea name="home_meta_description" id="home_meta_description" cols="30" rows="3" class="form-control">{{ get_settings('home_meta_description') }}</textarea>
                                </div>

                                <!-- home_banner_short_title -->
                                <div class="col-md-12 mb-3 form-group">
                                    <label for="home_banner_short_title">Banner Short Title <span class="text-danger">*</span></label>
                                    <input type="text" name="home_banner_short_title" id="home_banner_short_title" class="form-control" required value="{{ get_settings('home_banner_short_title') }}">
                                </div>

                                <!-- home_banner_main_title -->
                                <div class="col-md-12 mb-3 form-group">
                                    <label for="home_banner_main_title">Banner Title <span class="text-danger">*</span></label>
                                    <input type="text" name="home_banner_main_title" id="home_banner_main_title" class="form-control" required value="{{ get_settings('home_banner_main_title') }}">
                                </div>

                                <!-- home_banner_description -->
                                <div class="col-md-12 mb-3 form-group">
                                    <label for="home_banner_description">Banner Description <span class="text-danger">*</span></label>
                                    <input type="text" name="home_banner_description" id="home_banner_description" class="form-control" required value="{{ get_settings('home_banner_description') }}">
                                </div>

                                <!-- home_banner_button_text -->
                                <div class="col-md-6 mb-3 form-group">
                                    <label for="home_banner_button_text">
                                        Banner Button Text 
                                    </label>
                                    <input type="text" name="home_banner_button_text" id="home_banner_button_text" class="form-control" value="{{ get_settings('home_banner_button_text') }}">
                                </div>

                                <!-- home_banner_button_url -->
                                <div class="col-md-6 mb-3 form-group">
                                    <label for="home_banner_button_url">
                                        Banner Button URL 
                                    </label>
                                    <input type="text" name="home_banner_button_url" id="home_banner_button_url" class="form-control" value="{{ get_settings('home_banner_button_url') }}">
                                </div>

                                <!-- ========================= -->
                                <!-- Why Choose Us - Cards -->
                                <!-- ========================= -->

                                <!-- card_one_picture -->
                                <div class="col-md-6 mb-3 form-group">
                                    <label for="card_one_picture">Card One Picture</label>
                                    <input type="file" name="card_one_picture" id="card_one_picture" class="form-control">
                                    @if (get_settings('card_one_picture'))
                                        <img src="{{ asset(get_settings('card_one_picture')) }}" alt="" class="mt-2" width="100">
                                    @endif
                                </div>

                                <!-- card_one_picture_alt_tag -->
                                <div class="col-md-6 mb-3 form-group">
                                    <label for="card_one_picture_alt_tag">Card One Picture Alt Tag</label>
                                    <input type="text" name="card_one_picture_alt_tag" id="card_one_picture_alt_tag" class="form-control" value="{{ get_settings('card_one_picture_alt_tag') }}">
                                </div>

                                <!-- card_one_counter -->
                                <div class="col-md-6 mb-3 form-group">
                                    <label for="card_one_counter">Card One Counter</label>
                                    <input type="text" name="card_one_counter" id="card_one_counter" class="form-control" value="{{ get_settings('card_one_counter') }}">
                                </div>

                                <!-- card_one_title -->
                                <div class="col-md-6 mb-3 form-group">
                                    <label for="card_one_title">Card One Title</label>
                                    <input type="text" name="card_one_title" id="card_one_title" class="form-control" value="{{ get_settings('card_one_title') }}">
                                </div>

                                <!-- ========================= -->
                                <!-- Card Two -->
                                <!-- ========================= -->

                                <div class="col-md-6 mb-3 form-group">
                                    <label for="card_two_picture">Card Two Picture</label>
                                    <input type="file" name="card_two_picture" id="card_two_picture" class="form-control">
                                    @if (get_settings('card_two_picture'))
                                        <img src="{{ asset(get_settings('card_two_picture')) }}" alt="" class="mt-2" width="100">
                                    @endif
                                </div>

                                <div class="col-md-6 mb-3 form-group">
                                    <label for="card_two_picture_alt_tag">Card Two Picture Alt Tag</label>
                                    <input type="text" name="card_two_picture_alt_tag" id="card_two_picture_alt_tag" class="form-control" value="{{ get_settings('card_two_picture_alt_tag') }}">
                                </div>

                                <div class="col-md-6 mb-3 form-group">
                                    <label for="card_two_counter">Card Two Counter</label>
                                    <input type="text" name="card_two_counter" id="card_two_counter" class="form-control" value="{{ get_settings('card_two_counter') }}">
                                </div>

                                <div class="col-md-6 mb-3 form-group">
                                    <label for="card_two_title">Card Two Title</label>
                                    <input type="text" name="card_two_title" id="card_two_title" class="form-control" value="{{ get_settings('card_two_title') }}">
                                </div>

                                <!-- ========================= -->
                                <!-- Card Three -->
                                <!-- ========================= -->

                                <div class="col-md-6 mb-3 form-group">
                                    <label for="card_three_picture">Card Three Picture</label>
                                    <input type="file" name="card_three_picture" id="card_three_picture" class="form-control">
                                    @if (get_settings('card_three_picture'))
                                        <img src="{{ asset(get_settings('card_three_picture')) }}" alt="" class="mt-2" width="100">
                                    @endif
                                </div>

                                <div class="col-md-6 mb-3 form-group">
                                    <label for="card_three_picture_alt_tag">Card Three Picture Alt Tag</label>
                                    <input type="text" name="card_three_picture_alt_tag" id="card_three_picture_alt_tag" class="form-control" value="{{ get_settings('card_three_picture_alt_tag') }}">
                                </div>

                                <div class="col-md-6 mb-3 form-group">
                                    <label for="card_three_counter">Card Three Counter</label>
                                    <input type="text" name="card_three_counter" id="card_three_counter" class="form-control" value="{{ get_settings('card_three_counter') }}">
                                </div>

                                <div class="col-md-6 mb-3 form-group">
                                    <label for="card_three_title">Card Three Title</label>
                                    <input type="text" name="card_three_title" id="card_three_title" class="form-control" value="{{ get_settings('card_three_title') }}">
                                </div>

                                <!-- ========================= -->
                                <!-- Card Four -->
                                <!-- ========================= -->

                                <div class="col-md-6 mb-3 form-group">
                                    <label for="card_four_picture">Card Four Picture</label>
                                    <input type="file" name="card_four_picture" id="card_four_picture" class="form-control">
                                    @if (get_settings('card_four_picture'))
                                        <img src="{{ asset(get_settings('card_four_picture')) }}" alt="" class="mt-2" width="100">
                                    @endif
                                </div>

                                <div class="col-md-6 mb-3 form-group">
                                    <label for="card_four_picture_alt_tag">Card Four Picture Alt Tag</label>
                                    <input type="text" name="card_four_picture_alt_tag" id="card_four_picture_alt_tag" class="form-control" value="{{ get_settings('card_four_picture_alt_tag') }}">
                                </div>

                                <div class="col-md-6 mb-3 form-group">
                                    <label for="card_four_counter">Card Four Counter</label>
                                    <input type="text" name="card_four_counter" id="card_four_counter" class="form-control" value="{{ get_settings('card_four_counter') }}">
                                </div>

                                <div class="col-md-6 mb-3 form-group">
                                    <label for="card_four_title">Card Four Title</label>
                                    <input type="text" name="card_four_title" id="card_four_title" class="form-control" value="{{ get_settings('card_four_title') }}">
                                </div>

                                <!-- ========================= -->
                                <!-- Choose Section Content -->
                                <!-- ========================= -->

                                <div class="col-md-12 mb-3 form-group">
                                    <label for="choose_section_short_title">Section Short Title</label>
                                    <input type="text" name="choose_section_short_title" id="choose_section_short_title" class="form-control" value="{{ get_settings('choose_section_short_title') }}">
                                </div>

                                <div class="col-md-12 mb-3 form-group">
                                    <label for="choose_section_title">Section Main Title</label>
                                    <input type="text" name="choose_section_title" id="choose_section_title" class="form-control" value="{{ get_settings('choose_section_title') }}">
                                </div>

                                <div class="col-md-12 mb-3 form-group">
                                    <label for="choose_section_details">Section Description</label>
                                    <textarea name="choose_section_details" id="choose_section_details" cols="30" rows="3" class="form-control">{{ get_settings('choose_section_details') }}</textarea>
                                </div>

                                <!-- ========================= -->
                                <!-- Choose Section Lists -->
                                <!-- ========================= -->

                                <!-- choose_list_one -->
                                <div class="col-md-4 mb-3 form-group">
                                    <label for="choose_list_one_icon">List One Icon (e.g. icon-smile or image URL)</label>
                                    <input type="text" name="choose_list_one_icon" id="choose_list_one_icon" class="form-control" value="{{ get_settings('choose_list_one_icon') }}">
                                </div>

                                <div class="col-md-4 mb-3 form-group">
                                    <label for="choose_list_one_header">List One Header</label>
                                    <input type="text" name="choose_list_one_header" id="choose_list_one_header" class="form-control" value="{{ get_settings('choose_list_one_header') }}">
                                </div>

                                <div class="col-md-4 mb-3 form-group">
                                    <label for="choose_list_one_details">List One Details</label>
                                    <textarea name="choose_list_one_details" id="choose_list_one_details" cols="30" rows="2" class="form-control">{{ get_settings('choose_list_one_details') }}</textarea>
                                </div>

                                <!-- choose_list_two -->
                                <div class="col-md-4 mb-3 form-group">
                                    <label for="choose_list_two_icon">List Two Icon (e.g. icon-book or image URL)</label>
                                    <input type="text" name="choose_list_two_icon" id="choose_list_two_icon" class="form-control" value="{{ get_settings('choose_list_two_icon') }}">
                                </div>

                                <div class="col-md-4 mb-3 form-group">
                                    <label for="choose_list_two_header">List Two Header</label>
                                    <input type="text" name="choose_list_two_header" id="choose_list_two_header" class="form-control" value="{{ get_settings('choose_list_two_header') }}">
                                </div>

                                <div class="col-md-4 mb-3 form-group">
                                    <label for="choose_list_two_details">List Two Details</label>
                                    <textarea name="choose_list_two_details" id="choose_list_two_details" cols="30" rows="2" class="form-control">{{ get_settings('choose_list_two_details') }}</textarea>
                                </div>

                                <!-- cta_short_title -->
                                <div class="col-md-12 mb-3 form-group">
                                    <label for="cta_short_title">CTA Short Title <span class="text-danger">*</span></label>
                                    <input type="text" name="cta_short_title" id="cta_short_title" class="form-control" required value="{{ get_settings('cta_short_title') }}">
                                </div>

                                <!-- cta_title -->
                                <div class="col-md-12 mb-3 form-group">
                                    <label for="cta_title">CTA Title <span class="text-danger">*</span></label>
                                    <input type="text" name="cta_title" id="cta_title" class="form-control" required value="{{ get_settings('cta_title') }}">
                                </div>

                                <!-- cta_button_one_text -->
                                <div class="col-md-6 mb-3 form-group">
                                    <label for="cta_button_one_text">Button One Text</label>
                                    <input type="text" name="cta_button_one_text" id="cta_button_one_text" class="form-control" value="{{ get_settings('cta_button_one_text') }}">
                                </div>

                                <!-- cta_button_one_url -->
                                <div class="col-md-6 mb-3 form-group">
                                    <label for="cta_button_one_url">Button One URL</label>
                                    <input type="text" name="cta_button_one_url" id="cta_button_one_url" class="form-control" value="{{ get_settings('cta_button_one_url') }}">
                                </div>

                                <!-- cta_button_two_text -->
                                <div class="col-md-6 mb-3 form-group">
                                    <label for="cta_button_two_text">Button Two Text</label>
                                    <input type="text" name="cta_button_two_text" id="cta_button_two_text" class="form-control" value="{{ get_settings('cta_button_two_text') }}">
                                </div>

                                <!-- cta_button_two_url -->
                                <div class="col-md-6 mb-3 form-group">
                                    <label for="cta_button_two_url">Button Two URL</label>
                                    <input type="text" name="cta_button_two_url" id="cta_button_two_url" class="form-control" value="{{ get_settings('cta_button_two_url') }}">
                                </div>

                                
                                <div class="col-md-4 mb-3">
                                    <button type="submit" class="btn btn-sm btn-block btn-primary" id="submit">
                                        <i class="fas fa-paper-plane fa-fw"></i> Update    
                                    </button>
                                    <button type="button" class="btn btn-sm btn-block btn-outline-primary" id="submitting" style="display: none;">
                                        <i class="fas fa-spinner fa-spin fa-fw"></i> Processing    
                                    </button>
                                </div>
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
