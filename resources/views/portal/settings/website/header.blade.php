@extends('layouts.admin', ['title' => 'Website Header Settings'])
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
                        Website Header Settings
                    </h1>
                    <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">
                        <li class="breadcrumb-item text-muted">
                            <a href="{{ route('portal.dashboard') }}" class="text-muted text-hover-primary">Home</a>
                        </li>
                        <li class="breadcrumb-item">
                            <span class="bullet bg-gray-500 w-5px h-2px"></span>
                        </li>
                        <li class="breadcrumb-item text-muted">Website Header Settings</li>
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
                                <div class="col-md-12 form-group mb-3">
                                    <div class="header-nav-menu">
                                        @if (get_settings('header_menu_labels') != null)
                                            @foreach ( json_decode(get_settings('header_menu_labels')) as $key => $value)
                                                @php
                                                    $rand = rand(10000, 1000000);
                                                @endphp
                                                <div class="row mb-3" id="data-{{ $rand }}">
                                                    <div class="col-4">
                                                        <div class="form-group">
                                                            <input type="text" class="form-control" placeholder="Label" name="header_menu_labels[]" required value="{{ $value }}">
                                                        </div>
                                                    </div>
                                                    <div class="col">
                                                        <div class="form-group">
                                                            <input type="text" class="form-control" placeholder="Link with http:// or https://" name="header_menu_links[]" value="{{ json_decode(App\Models\Setting::where('name', 'header_menu_links')->first()->value, true)[$key] }}" required>
                                                        </div>
                                                    </div>
                                                    <div class="col-auto">
                                                        <button type="button" class="mt-1 btn btn-sm btn-danger remove-parent" data-parent="{{ $rand }}">
                                                            <i class="fas fa-close"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                            @endforeach
                                        @endif
                                    </div>
                                </div>

                                @if (Auth::guard('admin')->user()->hasPermissionTo('settings.update'))
                                    <div class="col-md-4 mb-3 mx-auto">
                                        <button type="button" class="btn btn-dark btn-sm add-more">
                                            <i class="fas fa-plus"></i>
                                            Add New
                                        </button>
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

        $(document).on('click', '.add-more', function() {
            let id = Math.floor((Math.random() * 10000000) + 1);
            let content = `<div class="row mt-3" id="data-`+ id +`">
                    <div class="col-4">
                        <div class="form-group">
                            <input type="text" required class="form-control" placeholder="Label" name="header_menu_labels[]">
                        </div>
                    </div>
                    <div class="col">
                        <div class="form-group">
                            <input type="text" required class="form-control" placeholder="Link with http:// or https://" name="header_menu_links[]">
                        </div>
                    </div>
                    <div class="col-auto">
                        <button type="button" class="mt-1 btn btn-sm btn-danger remove-parent" data-parent="`+ id+`">
                            <i class="fas fa-close"></i>
                        </button>
                    </div>
                </div>`;
            $('.header-nav-menu').append(content);
        });

        $(document).on('click', '.remove-parent', function() {
            let id = $(this).data('parent');
            $('#data-'+id).remove();
        })
    </script>
@endpush
