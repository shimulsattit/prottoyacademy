@extends('layouts.admin', ['title' => 'Update Stuff Information'])
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
                        Update Stuff Information
                    </h1>
                    <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">
                        <li class="breadcrumb-item text-muted">
                            <a href="{{ route('portal.dashboard') }}" class="text-muted text-hover-primary">Home</a>
                        </li>
                        <li class="breadcrumb-item">
                            <span class="bullet bg-gray-500 w-5px h-2px"></span>
                        </li>
                        @if (Auth::guard('admin')->user()->hasPermissionTo('stuff.view'))
                            <li class="breadcrumb-item text-muted">
                                <a href="{{ route('portal.stuff.index') }}" class="text-muted text-hover-primary">Stuff Management</a>
                            </li>
                        @endif 
                        <li class="breadcrumb-item">
                            <span class="bullet bg-gray-500 w-5px h-2px"></span>
                        </li>
                        <li class="breadcrumb-item text-muted">Update Stuff Information</li>
                    </ul>
                </div>
            </div>
        </div>

        <div id="app_content" class="app-content flex-column-fluid">
            <div id="app_content_container" class="app-container container-xxl">
                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('portal.stuff.update', $model->id) }}" method="POST" enctype="multipart/form-data" class="content_form">
                            @method('PATCH')
                            <div class="row">
                                <div class="col-md-12 mb-3 form-group">
                                    <label for="roles">Roles</label>
                                    <select name="roles" class="form-control select" required>
                                        @foreach ($roles as $role)
                                            <option {{ $role->name == $model->getRoleNames()->toArray()[0] ? 'selected' : '' }} value="{{ $role->id }}">{{ $role->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                
                                <div class="col-md-2 mb-3 form-group">
                                    <label for="surname">Surname <span class="text-danger">*</span></label>
                                    <input type="text" name="surname" id="surname" class="form-control" required value="{{ $model->surname }}">
                                </div>

                                <div class="col-md-5 mb-3 form-group">
                                    <label for="first_name">First Name <span class="text-danger">*</span></label>
                                    <input type="text" name="first_name" id="first_name" class="form-control" required value="{{ $model->first_name }}">
                                </div>

                                <div class="col-md-5 mb-3 form-group">
                                    <label for="last_name">Last Name <span class="text-danger">*</span></label>
                                    <input type="text" name="last_name" id="last_name" class="form-control" required value="{{ $model->last_name }}">
                                </div>

                                <div class="col-md-6 mb-3 form-group">
                                    <label for="username">Username <span class="text-danger">*</span></label>
                                    <input type="text" name="username" id="username" class="form-control" required value="{{ $model->username }}">
                                </div>

                                <div class="col-md-6 mb-3 form-group">
                                    <label for="email">Email <span class="text-danger">*</span></label>
                                    <input type="text" name="email" id="email" class="form-control" required value="{{ $model->email }}">
                                </div>

                                <div class="col-md-12 mb-3 form-group">
                                    <label for="avatar">Avatar</label>
                                    <input type="file" name="avatar" id="avatar" class="form-control dropify" data-default-file="{{ $model->avatar ? asset($model->avatar) : '' }}">
                                </div>

                                <div class="col-md-12 mb-3 form-group">
                                    <label for="status">Status</label>
                                    <select name="status" id="status" class="form-control select">
                                        <option {{ $model->status == 1 ? 'selected' : '' }} value="1">Active</option>
                                        <option {{ $model->status == 0 ? 'selected' : '' }} value="0">Inactive</option>
                                    </select>
                                </div>

                                @if (Auth::guard('admin')->user()->hasPermissionTo('stuff.update'))
                                    <div class="col-md-12 mb-3 text-center">
                                        <button type="submit" class="btn btn-sm btn-block btn-primary" id="submit">
                                            <i class="fas fa-paper-plane fa-fw"></i> Update Stuff    
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
