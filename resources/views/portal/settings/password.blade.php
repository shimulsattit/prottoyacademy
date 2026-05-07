@extends('layouts.admin', ['title' => 'Manage Password'])
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
                        Manage Password
                    </h1>
                    <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">
                        <li class="breadcrumb-item text-muted">
                            <a href="index.html" class="text-muted text-hover-primary">Home</a>
                        </li>
                        <li class="breadcrumb-item">
                            <span class="bullet bg-gray-500 w-5px h-2px"></span>
                        </li>
                        <li class="breadcrumb-item text-muted">Manage Password</li>
                    </ul>
                </div>
            </div>
        </div>

        <div id="app_content" class="app-content flex-column-fluid">
            <div id="app_content_container" class="app-container container-xxl">
                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('portal.password.update') }}" method="POST" enctype="multipart/form-data" class="content_form">
                            <div class="row">
                                <div class="col-md-12 form-group mb-3">
                                    <label for="old_password">Old Password</label>
                                    <input type="text" name="old_password" id="old_password" class="form-control" required>
                                </div>
                                <div class="col-md-12 form-group mb-3">
                                    <label for="new_password">New Password <span class="text-danger">*</span></label>
                                    <input type="text" name="new_password" id="new_password" class="form-control" required>
                                </div>
                                <div class="col-md-12 form-group mb-3">
                                    <label for="new_password_confirmation">Retype Password <span class="text-danger">*</span></label>
                                    <input type="text" name="new_password_confirmation" id="new_password_confirmation" class="form-control" required>
                                </div>
                                <div class="col-md-12 form-group text-center">
                                    <button type="submit" class="btn btn-sm btn-primary" id="update-password-submit">
                                        <i class="fas fa-paper-plane fa-fw"></i>
                                        Update Password 
                                    </button>
                                    <button style="display: none;" type="button" disabled class="btn btn-sm btn-primary" id="update-password-submitting" >
                                        <i class="fas fa-spinner fa-spin fa-fw"></i>
                                        Processing
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
