@extends('layouts.admin', ['title' => 'Create New Passage'])
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
                        Create New Passage
                    </h1>
                    <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">
                        <li class="breadcrumb-item text-muted">
                            <a href="{{ route('portal.dashboard') }}" class="text-muted text-hover-primary">Home</a>
                        </li>
                        <li class="breadcrumb-item">
                            <span class="bullet bg-gray-500 w-5px h-2px"></span>
                        </li>
                        @if(Auth::guard('admin')->user()->hasPermissionTo('passage.view'))
                            <li class="breadcrumb-item text-muted">
                                <a href="{{ route('portal.passage.index') }}" class="text-muted text-hover-primary">Passage Management</a>
                            </li>
                        @endif 
                        <li class="breadcrumb-item">
                            <span class="bullet bg-gray-500 w-5px h-2px"></span>
                        </li>
                        <li class="breadcrumb-item text-muted">Create New Passage</li>
                    </ul>
                </div>
            </div>
        </div>

        <div id="app_content" class="app-content flex-column-fluid">
            <div id="app_content_container" class="app-container container-xxl">
                <div class="card">
                    <div class="card-body">
                        <form data-editor="description" action="{{ route('portal.passage.store') }}" method="POST" class="content_form">
                            <div class="row">
                                <div class="col-md-12 mb-3 form-group">
                                    <label for="name">Name <span class="text-danger">*</span></label>
                                    <input type="text" name="name" id="name" class="form-control" required value="">
                                </div>

                                <div class="col-md-12 mb-3 form-group">
                                    <label for="description">Description <span class="text-danger">*</span></label>
                                    <textarea name="description" id="description" cols="30" rows="3" class="form-control"></textarea>
                                </div>

                                <div class="col-md-12 form-group mb-3">
                                    <label for="status">Status <span class="text-danger">*</span></label>
                                    <select name="status" id="status" class="form-control select" required>
                                        <option value="1">Publish</option>
                                        <option value="0">Unpublish</option>
                                    </select>
                                </div>
                                
                                @if(Auth::guard('admin')->user()->hasPermissionTo('passage.create'))
                                    <div class="col-md-12 mb-3 text-center">
                                        <button type="submit" class="btn btn-sm btn-block btn-primary" id="submit">
                                            <i class="fas fa-paper-plane fa-fw"></i> Create Passage    
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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/ckeditor/4.9.2/ckeditor.js"></script>    
    <script>
        _componentSelect();
        _formValidation();

        let _initCkEditor = function(editorName, startupFocus = false, editorHeight = false) {
            CKEDITOR.replace(editorName, {
                // filebrowserUploadUrl: 'ck_upload.php', //Later
                filebrowserUploadMethod: 'form',
                height: editorHeight ? editorHeight : '',
                startupFocus: startupFocus == 1 ? true : false,
                removePlugins: 'exportpdf',
                toolbar: [
                    ['Format', 'Font', 'FontSize', '-'],
                    ['Bold', 'Italic', 'Underline', 'Table', '-', 'NumberedList', 'BulletedList', '-'],
                    ["JustifyLeft", "JustifyCenter", "JustifyRight", "JustifyBlock"],
                    ['Link', 'Blockquote', 'Maximize', 'Image', 'TextColor', '-', 'Source']
                ],
                contentsCss: [
                    'https://cdn.ckeditor.com/4.16.0/standard-all/contents.css',
                    '/backend/assets/css/ck-editor-custom.css'
                ],
                bodyClass: 'ckeditor-dark-mode', 
            });
        }

        _initCkEditor('description');
    </script>
@endpush
