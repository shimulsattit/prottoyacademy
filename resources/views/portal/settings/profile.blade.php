@extends('layouts.admin', ['title' => 'My Profile'])
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
                        My Profile
                    </h1>
                    <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">
                        <li class="breadcrumb-item text-muted">
                            <a href="index.html" class="text-muted text-hover-primary">Home</a>
                        </li>
                        <li class="breadcrumb-item">
                            <span class="bullet bg-gray-500 w-5px h-2px"></span>
                        </li>
                        <li class="breadcrumb-item text-muted">My Profile</li>
                    </ul>
                </div>
            </div>
        </div>

        <div id="app_content" class="app-content flex-column-fluid">
            <div id="app_content_container" class="app-container container-xxl">
                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('portal.profile.update') }}" method="POST" enctype="multipart/form-data" class="content_form">
                            <div class="row">
                                <div class="col-md-2 mb-3 form-group">
                                    <label for="surname">Surname <span class="text-danger">*</span></label>
                                    <input type="text" name="surname" id="surname" class="form-control" required value="{{ Auth::guard('admin')->user()->surname }}">
                                </div>

                                <div class="col-md-5 mb-3 form-group">
                                    <label for="first_name">First Name <span class="text-danger">*</span></label>
                                    <input type="text" name="first_name" id="first_name" class="form-control" required value="{{ Auth::guard('admin')->user()->first_name }}">
                                </div>

                                <div class="col-md-5 mb-3 form-group">
                                    <label for="last_name">Last Name <span class="text-danger">*</span></label>
                                    <input type="text" name="last_name" id="last_name" class="form-control" required value="{{ Auth::guard('admin')->user()->last_name }}">
                                </div>

                                <div class="col-md-6 mb-3 form-group">
                                    <label for="username">Username <span class="text-danger">*</span></label>
                                    <input type="text" name="username" id="username" class="form-control" required value="{{ Auth::guard('admin')->user()->username }}">
                                </div>

                                <div class="col-md-6 mb-3 form-group">
                                    <label for="email">Email <span class="text-danger">*</span></label>
                                    <input type="text" name="email" id="email" class="form-control" required value="{{ Auth::guard('admin')->user()->email }}">
                                </div>

                                <div class="col-md-12 mb-3 form-group">
                                    <label for="avatar">Avatar</label>
                                    <input type="file" name="avatar" id="avatar" class="form-control dropify" data-default-file="{{ Auth::guard('admin')->user()->avatar ? asset(Auth::guard('admin')->user()->avatar) : asset('portal-resource/images/300-1.jpg') }}">
                                </div>

                                <div class="col-md-4 mb-3 mx-auto">
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
