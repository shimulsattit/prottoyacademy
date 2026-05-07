@extends('layouts.admin', ['title' => 'Website Footer Settings'])
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
                        Website Footer Settings
                    </h1>
                    <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">
                        <li class="breadcrumb-item text-muted">
                            <a href="{{ route('portal.dashboard') }}" class="text-muted text-hover-primary">Home</a>
                        </li>
                        <li class="breadcrumb-item">
                            <span class="bullet bg-gray-500 w-5px h-2px"></span>
                        </li>
                        <li class="breadcrumb-item text-muted">Website Footer Settings</li>
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
                                
                                <div class="col-md-4">
                                    <div class="card">
                                        <div class="card-header">
                                            <h2 class="h6 card-title">
                                                <b>Link Widget One</b>
                                            </h2>
                                        </div>
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-md-12 form-group mb-3">
                                                    <label for="footer_menu_one_label_text">Title</label>
                                                    <input type="text" name="footer_menu_one_label_text" id="footer_menu_one_label_text" class="form-control" value="{{ get_settings('footer_menu_one_label_text') }}">
                                                </div>
                                                <div class="col-md-12 form-group mb-3">
                                                    <div class="footer-nav-menu-one">
                                                        @if (get_settings('footer_menu_one_labels') != null)
                                                            @foreach ( json_decode(get_settings('footer_menu_one_labels')) as $key => $value)
                                                                @php
                                                                    $rand = rand(10000, 1000000);
                                                                @endphp
                                                                <div class="row mt-3" id="data-{{ $rand}}">
                                                                    <div class="col-4">
                                                                        <div class="form-group">
                                                                            <input type="text" class="form-control" placeholder="Label" name="footer_menu_one_labels[]" required value="{{ $value }}">
                                                                        </div>
                                                                    </div>
                                                                    <div class="col">
                                                                        <div class="form-group">
                                                                            <input type="text" class="form-control" placeholder="Link with http:// or https://" name="footer_menu_one_links[]" value="{{ json_decode(App\Models\Setting::where('name', 'footer_menu_one_links')->first()->value, true)[$key] }}" required>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-auto">
                                                                        <button type="button" class="mt-1 btn btn-icon btn-circle btn-sm btn-outline-danger remove-parent" data-parent="{{ $rand }}">
                                                                            <i class="fas fa-plus"></i>
                                                                        </button>
                                                                    </div>
                                                                </div>
                                                            @endforeach
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card-footer">
                                            <button type="button" class="btn btn-outline-primary btn-sm add-more-one">
                                                <i class="fas fa-plus"></i>
                                                Add New
                                            </button>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="card">
                                        <div class="card-header">
                                            <h2 class="h6 card-title">
                                                <strong>Link Widget Two</strong>
                                            </h2>
                                        </div>
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-md-12 form-group mb-3">
                                                    <label for="footer_menu_tow_label_text">Title</label>
                                                    <input type="text" name="footer_menu_tow_label_text" id="footer_menu_tow_label_text" class="form-control" value="{{ get_settings('footer_menu_tow_label_text') }}">
                                                </div>
                                                <div class="col-md-12 form-group mb-3">
                                                    <div class="footer-nav-menu-two">
                                                        @if (get_settings('footer_menu_two_labels') != null)
                                                            @foreach ( json_decode(get_settings('footer_menu_two_labels')) as $key => $value)
                                                                @php
                                                                    $rand = rand(10000, 1000000);
                                                                @endphp
                                                                <div class="row mt-3" id="data-{{ $rand}}">
                                                                    <div class="col-4">
                                                                        <div class="form-group">
                                                                            <input type="text" class="form-control" placeholder="Label" name="footer_menu_two_labels[]" required value="{{ $value }}">
                                                                        </div>
                                                                    </div>
                                                                    <div class="col">
                                                                        <div class="form-group">
                                                                            <input type="text" class="form-control" placeholder="Link with http:// or https://" name="footer_menu_two_links[]" value="{{ json_decode(App\Models\Setting::where('name', 'footer_menu_two_links')->first()->value, true)[$key] }}" required>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-auto">
                                                                        <button type="button" class="mt-1 btn btn-icon btn-circle btn-sm btn-outline-danger remove-parent" data-parent="{{ $rand }}">
                                                                            <i class="fas fa-times"></i>
                                                                        </button>
                                                                    </div>
                                                                </div>
                                                            @endforeach
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card-footer">
                                            <button type="button" class="btn btn-sm btn-outline-primary btn-sm add-more-two">
                                                <i class="fas fa-plus"></i>        
                                                Add New
                                            </button>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="card">
                                        <div class="card-header">
                                            <h2 class="h6 card-title">
                                                <strong>Link Widget Three</strong>
                                            </h2>
                                        </div>
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-md-12 form-group mb-3">
                                                    <label for="footer_menu_three_label_text">Title</label>
                                                    <input type="text" name="footer_menu_three_label_text" id="footer_menu_three_label_text" class="form-control" value="{{ get_settings('footer_menu_three_label_text') }}">
                                                </div>
                                                <div class="col-md-12 form-group mb-3">
                                                    <div class="footer-nav-menu-three">
                                                        @if (get_settings('footer_menu_three_labels') != null)
                                                            @foreach ( json_decode(get_settings('footer_menu_three_labels')) as $key => $value)
                                                                @php
                                                                    $rand = rand(10000, 1000000);
                                                                @endphp
                                                                <div class="row mt-3" id="data-{{ $rand}}">
                                                                    <div class="col-4">
                                                                        <div class="form-group">
                                                                            <input type="text" class="form-control" placeholder="Label" name="footer_menu_three_labels[]" required value="{{ $value }}">
                                                                        </div>
                                                                    </div>
                                                                    <div class="col">
                                                                        <div class="form-group">
                                                                            <input type="text" class="form-control" placeholder="Link with http:// or https://" name="footer_menu_three_links[]" value="{{ json_decode(App\Models\Setting::where('name', 'footer_menu_three_links')->first()->value, true)[$key] }}" required>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-auto">
                                                                        <button type="button" class="mt-1 btn btn-icon btn-circle btn-sm btn-outline-danger remove-parent" data-parent="{{ $rand }}">
                                                                            <i class="fas fa-times"></i>
                                                                        </button>
                                                                    </div>
                                                                </div>
                                                            @endforeach
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card-footer">
                                            <button type="button" class="btn btn-sm btn-outline-primary btn-sm add-more-three">
                                                <i class="bi bi-plus"></i>        
                                                Add New
                                            </button>
                                        </div>
                                    </div>
                                </div>

                                @if (Auth::guard('admin')->user()->hasPermissionTo('settings.update'))
                                    <div class="col-md-4 mt-3 mx-auto">
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

        $(document).on('click', '.add-more-one', function() {
            let id = Math.floor((Math.random() * 10000000) + 1);
            let content = `<div class="row mt-3" id="data-`+ id +`">
                    <div class="col-4">
                        <div class="form-group">
                            <input type="text" required class="form-control" placeholder="Label" name="footer_menu_one_labels[]">
                        </div>
                    </div>
                    <div class="col">
                        <div class="form-group">
                            <input type="text" required class="form-control" placeholder="Link with http:// or https://" name="footer_menu_one_links[]">
                        </div>
                    </div>
                    <div class="col-auto">
                        <button type="button" class="mt-1 btn btn-icon btn-circle btn-sm btn-outline-danger remove-parent-one" data-parent="`+ id+`">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                </div>`;
            $('.footer-nav-menu-one').append(content);
        });

        $(document).on('click', '.remove-parent-one', function() {
            let id = $(this).data('parent');
            $('#data-'+id).remove();
        })
        $(document).on('click', '.remove-parent', function() {
            let id = $(this).data('parent');
            $('#data-'+id).remove();
        })

        $(document).on('click', '.add-more-two', function() {
            let id = Math.floor((Math.random() * 10000000) + 1);
            let content = `<div class="row mt-3" id="data-`+ id +`">
                    <div class="col-4">
                        <div class="form-group">
                            <input type="text" required class="form-control" placeholder="Label" name="footer_menu_two_labels[]">
                        </div>
                    </div>
                    <div class="col">
                        <div class="form-group">
                            <input type="text" required class="form-control" placeholder="Link with http:// or https://" name="footer_menu_two_links[]">
                        </div>
                    </div>
                    <div class="col-auto">
                        <button type="button" class="mt-1 btn btn-icon btn-circle btn-sm btn-outline-danger remove-parent-two" data-parent="`+ id+`">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                </div>`;
            $('.footer-nav-menu-two').append(content);
        });

        $(document).on('click', '.remove-parent-two', function() {
            let id = $(this).data('parent');
            $('#data-'+id).remove();
        });

        $(document).on('click', '.add-more-three', function() {
            let id = Math.floor((Math.random() * 10000000) + 1);
            let content = `<div class="row mt-3" id="data-`+ id +`">
                    <div class="col-4">
                        <div class="form-group">
                            <input type="text" required class="form-control" placeholder="Label" name="footer_menu_three_labels[]">
                        </div>
                    </div>
                    <div class="col">
                        <div class="form-group">
                            <input type="text" required class="form-control" placeholder="Link with http:// or https://" name="footer_menu_three_links[]">
                        </div>
                    </div>
                    <div class="col-auto">
                        <button type="button" class="mt-1 btn btn-icon btn-circle btn-sm btn-outline-danger remove-parent-three" data-parent="`+ id+`">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                </div>`;
            $('.footer-nav-menu-three').append(content);
        });

        $(document).on('click', '.remove-parent-three', function() {
            let id = $(this).data('parent');
            $('#data-'+id).remove();
        });
    </script>
@endpush
