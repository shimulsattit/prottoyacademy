@extends('layouts.admin', ['title' => 'Create New Page'])
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
                        Create New Page
                    </h1>
                    <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">
                        <li class="breadcrumb-item text-muted">
                            <a href="{{ route('portal.dashboard') }}" class="text-muted text-hover-primary">Home</a>
                        </li>
                        <li class="breadcrumb-item">
                            <span class="bullet bg-gray-500 w-5px h-2px"></span>
                        </li>
                        <li class="breadcrumb-item text-muted">
                            <a href="{{ route('portal.page.index') }}" class="text-muted text-hover-primary">Page Management</a>
                        </li>
                        <li class="breadcrumb-item">
                            <span class="bullet bg-gray-500 w-5px h-2px"></span>
                        </li>
                        <li class="breadcrumb-item text-muted">Create New Page</li>
                    </ul>
                </div>
            </div>
        </div>

        <div id="app_content" class="app-content flex-column-fluid">
            <div id="app_content_container" class="app-container container-xxl">
                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('portal.page.store') }}" method="POST" class="content_form" data-editor="description">
                            <div class="row">
                                <div class="col-md-12 mb-3 form-group">
                                    <label for="name">Name <span class="text-danger">*</span></label>
                                    <input type="text" name="name" id="name" class="form-control" required>
                                </div>

                                <div class="col-md-12 mb-3 form-group">
                                    <label for="slug">Slug <span class="text-danger">*</span></label>
                                    <input type="text" name="slug" id="slug" class="form-control" required>
                                </div>
                        
                                <div class="col-md-6 mb-3 form-group">
                                    <label for="status">Status <span class="text-danger">*</span></label>
                                    <select name="status" id="status" class="form-control select" required>
                                        <option selected value="1">Active</option>
                                        <option value="0">Inactive</option>
                                    </select>
                                </div>

                                <div class="col-md-6 mb-3 form-group">
                                    <label for="show_on_navbar">Show on Navbar <span class="text-danger">*</span></label>
                                    <select name="show_on_navbar" id="show_on_navbar" class="form-control select" required>
                                        <option selected value="0">No</option>
                                        <option value="1">Yes</option>
                                    </select>
                                </div>

                                <div class="col-md-12 form-group mb-3">
                                    <label for="description">Description </label>
                                    <textarea name="description" id="description" cols="30" rows="3" class="form-control"></textarea>
                                </div>

                                <div class="col-md-12 mb-3 form-group">
                                    <label for="meta_tile">Meta Title <span class="text-danger">*</span></label>
                                    <input type="text" name="meta_title" id="meta_title" class="form-control" required placeholder="Enter your Meta Title">
                                </div>

                                <div class="col-md-6 mb-3 form-group">
                                    <label for="meta_keyword">Meta Keyword</label>
                                    <textarea name="meta_keyword" id="meta_keyword" cols="30" rows="4" class="form-control" placeholder="Enter your SEO Meta Keyword"></textarea>
                                </div>

                                <div class="col-md-6 mb-3 form-group">
                                    <label for="meta_description">Meta Description <span class="text-danger">*</span></label>
                                    <textarea name="meta_description" id="meta_description" cols="30" rows="4" class="form-control" placeholder="Enter your SEO Meta Description" required></textarea>
                                </div>

                                <div class="col-md-6 mb-3 form-group">
                                    <label for="meta_article_tag">Meta Article Tag</label>
                                    <textarea name="meta_article_tag" id="meta_article_tag" cols="30" rows="4" class="form-control" placeholder="Enter your SEO Meta Article Scripts"></textarea>
                                </div>

                                <div class="col-md-6 mb-3 form-group">
                                    <label for="meta_script_tag">Meta Script Tag</label>
                                    <textarea name="meta_script_tag" id="meta_script_tag" cols="30" rows="4" class="form-control" placeholder="Enter your SEO Meta Scripts"></textarea>
                                </div>

                                <div class="col-md-12 mb-3 form-group">
                                    <label for="meta_image">Meta image</label>
                                    <input type="file" accept=".jpg, .png, .webp"  name="meta_image" id="meta_image" class="form-control dropify">
                                </div>

                                <div class="col-md-12 mb-3 text-center">
                                    <button type="submit" class="btn btn-sm btn-block btn-primary" id="submit">
                                        <i class="fas fa-paper-plane fa-fw"></i> Create 
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
