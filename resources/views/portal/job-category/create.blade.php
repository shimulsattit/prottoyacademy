@extends('layouts.admin', ['title' => 'Create New Job Category'])
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
                        Create New Job Category
                    </h1>
                    <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">
                        <li class="breadcrumb-item text-muted">
                            <a href="{{ route('portal.dashboard') }}" class="text-muted text-hover-primary">Home</a>
                        </li>
                        <li class="breadcrumb-item">
                            <span class="bullet bg-gray-500 w-5px h-2px"></span>
                        </li>
                        @if(Auth::guard('admin')->user()->hasPermissionTo('job_category.view'))
                            <li class="breadcrumb-item text-muted">
                                <a href="{{ route('portal.job-category.index') }}" class="text-muted text-hover-primary">Job Category Management</a>
                            </li>
                        @endif 
                        <li class="breadcrumb-item">
                            <span class="bullet bg-gray-500 w-5px h-2px"></span>
                        </li>
                        <li class="breadcrumb-item text-muted">Create New Job Category</li>
                    </ul>
                </div>
            </div>
        </div>

        <div id="app_content" class="app-content flex-column-fluid">
            <div id="app_content_container" class="app-container container-xxl">
                <div class="card">
                    <div class="card-body">
                        <form data-editor="description" action="{{ route('portal.job-category.store') }}" method="POST" class="content_form">
                            <div class="row">
                                <div class="col-md-6 mb-3 form-group">
                                    <label for="name">Name <span class="text-danger">*</span></label>
                                    <input type="text" name="name" id="name" class="form-control" required value="">
                                </div>

                                <div class="col-md-6 mb-3 form-group">
                                    <label for="slug">Slug <span class="text-danger">*</span></label>
                                    <input type="text" name="slug" id="slug" class="form-control" required value="">
                                </div>

                                <div class="col-md-12 form-group mb-3">
                                    <label for="category_id">Select Category <span class="text-danger">*</span></label>
                                    <select name="category_id" id="category_id" class="form-control" required data-parsley-errors-container="#category_id_error"></select>
                                    <span id="category_id_error"></span>
                                </div>

                                <div class="col-md-12 mb-3 form-group">
                                    <label for="name_in_bangla">Name in Bangla</label>
                                    <input type="text" name="name_in_bangla" id="name_in_bangla" class="form-control" value="">
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
                                
                                <div class="col-md-12 form-group mb-3">
                                    <label for="site_title">Site Title <span class="text-danger">*</span></label>
                                    <input type="text" name="site_title" id="site_title" class="form-control" required>
                                </div>
                                
                                <div class="col-md-12 form-group mb-3">
                                    <label for="meta_title">Meta Title <span class="text-danger">*</span></label>
                                    <input type="text" name="meta_title" id="meta_title" class="form-control" required>
                                </div>

                                <div class="col-md-12 mb-3 form-group">
                                    <label for="meta_keywords">Meta Keywords</label>
                                    <textarea name="meta_keywords" id="meta_keywords" cols="30" rows="3" class="form-control"></textarea>
                                </div>

                                <div class="col-md-12 mb-3 form-group">
                                    <label for="meta_description">Meta Description</label>
                                    <textarea name="meta_description" id="meta_description" cols="30" rows="3" class="form-control"></textarea>
                                </div>

                                <div class="col-md-12 mb-3 form-group">
                                    <label for="meta_article_tag">Google Schema</label>
                                    <textarea name="meta_article_tag" id="meta_article_tag" cols="30" rows="3" class="form-control"></textarea>
                                </div>

                                @if(Auth::guard('admin')->user()->hasPermissionTo('job_category.create'))
                                    <div class="col-md-12 mb-3 text-center">
                                        <button type="submit" class="btn btn-sm btn-block btn-primary" id="submit">
                                            <i class="fas fa-paper-plane fa-fw"></i> Create Job Category    
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

        // Function to convert name to slug
        function generateSlug(name) {
            return name
                .toString()
                .toLowerCase()
                .trim()
                .replace(/&/g, '-and-') // Replace & with 'and'
                .replace(/[^\u0980-\u09FF\w\s-]/g, '') // Allow Bangla characters + word chars, space, dash
                .replace(/\s+/g, '-') // Replace spaces with -
                .replace(/-+/g, '-'); // Replace multiple - with single -
        }


        $('#category_id').select2({
            width: '100%',
            placeholder: 'Select Category',
            ajax: {
                url: '/search/all-category',
                method: 'POST',
                dataType: 'JSON',
                delay: 250,
                cache: true,
                data: function (data) {
                    return {
                        searchTerm: data.term
                    };
                },

                processResults: function (response) {
                    return {
                        results:response
                    };
                }
            }
        });

        $('#name').on('input', function() {
            const name = $(this).val();
            const slug = generateSlug(name);
            $('#slug').val(slug);

            // Check if the slug exists
            $.ajax({
                url: '{{ route('slug.check') }}',
                type: 'GET',
                data: {
                    slug: slug,
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    if (response.exists) {
                        const timestamp = Date.now();
                        $('#slug').val(slug + '-' + timestamp);
                    }
                }
            });
        });
    </script>
@endpush
