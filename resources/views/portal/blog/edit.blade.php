@extends('layouts.admin', ['title' => 'Update Blog Information'])
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
                        Update Blog Information
                    </h1>
                    <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">
                        <li class="breadcrumb-item text-muted">
                            <a href="{{ route('portal.dashboard') }}" class="text-muted text-hover-primary">Home</a>
                        </li>
                        <li class="breadcrumb-item">
                            <span class="bullet bg-gray-500 w-5px h-2px"></span>
                        </li>
                        <li class="breadcrumb-item text-muted">
                            <a href="{{ route('portal.blog.index') }}" class="text-muted text-hover-primary">Blog Management</a>
                        </li>
                        <li class="breadcrumb-item">
                            <span class="bullet bg-gray-500 w-5px h-2px"></span>
                        </li>
                        <li class="breadcrumb-item text-muted">Update Blog Information</li>
                    </ul>
                </div>
            </div>
        </div>

        <div id="app_content" class="app-content flex-column-fluid">
            <div id="app_content_container" class="app-container container-xxl">
                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('portal.blog.update', $model->id) }}" data-editor="content" method="POST" enctype="multipart/form-data" class="content_form">
                            @method('PATCH')
                            <div class="row">
                                <!-- blog_category_id -->
                                <div class="col-md-6 form-group mb-3">
                                    <label for="blog_category_id">Category <span class="text-danger">*</span></label>
                                    <select name="blog_category_id" id="blog_category_id" class="form-control select" data-placeholder="Select One" required data-parsley-errors-container="#blog_category_id_error">
                                        <option value="">Select One</option>
                                        @foreach ($categories as $category)
                                            <option {{ $model->blog_category_id == $category->id ? 'selected' : '' }} value="{{ $category->id }}">{{ $category->name }}</option>
                                        @endforeach
                                    </select>
                                    <span id="blog_category_id_error"></span>
                                </div>

                                <!-- blog_author_id -->
                                <div class="col-md-6 form-group mb-3">
                                    <label for="blog_author_id">Author <span class="text-danger">*</span></label>
                                    <select name="blog_author_id" id="blog_author_id" class="form-control select" data-placeholder="Select One" required data-parsley-errors-container="#blog_author_id_error">
                                        <option value="">Select One</option>
                                        @foreach ($authors as $author)
                                            <option {{ $model->blog_author_id == $author->id ? 'selected' : '' }} value="{{ $author->id }}">{{ $author->name }}</option>
                                        @endforeach
                                    </select>
                                    <span id="blog_author_id_error"></span>
                                </div>

                                <div class="col-md-6 mb-3 form-group">
                                    <label for="name">Name <span class="text-danger">*</span></label>
                                    <input type="text" name="title" id="name" class="form-control" required value="{{ $model->title }}">
                                </div>

                                <div class="col-md-6 mb-3 form-group">
                                    <label for="slug">Slug <span class="text-danger">*</span></label>
                                    <input type="text" name="slug" id="slug" class="form-control" required value="{{ $model->slug }}">
                                </div>

                                <div class="col-md-12 mb-3 form-group">
                                    <label for="short_description">Short Description <span class="text-danger">*</span></label>
                                    <textarea name="short_description" id="short_description" cols="30" rows="3" class="form-control">{{ $model->short_description }}</textarea>
                                </div>
                                
                                <div class="col-md-12 mb-3 form-group">
                                    <label for="content">Content <span class="text-danger">*</span></label>
                                    <textarea name="content" id="content" cols="30" rows="3" class="form-control">{{ $model->content }}</textarea>
                                </div>

                                <div class="col-md-6 form-group mb-3">
                                    <label for="thumbnail_image">Thumbnail Image</label>
                                    <input type="file" name="thumbnail_image" id="thumbnail_image" class="form-control dropify">
                                    @if ($model->thumbnail_image)
                                        <img src="{{ asset($model->thumbnail_image) }}" alt="Thumbnail Image" style="width: 100px;">
                                    @endif
                                </div>

                                <div class="col-md-6 form-group mb-3">
                                    <label for="banner_image">Banner Image</label>
                                    <input type="file" name="banner_image" id="banner_image" class="form-control dropify">
                                    @if ($model->banner_image)
                                        <img src="{{ asset($model->banner_image) }}" alt="Banner Image" style="width: 100px;">
                                    @endif
                                </div>

                                <div class="col-md-6 form-group mb-3">
                                    <label for="status">Status <span class="text-danger">*</span></label>
                                    <select name="status" id="status" class="form-control select" required>
                                        <option {{ $model->status == 1 ? 'selected' : '' }} value="1">Publish</option>
                                        <option {{ $model->status == 0 ? 'selected' : '' }} value="0">Unpublish</option>
                                    </select>
                                </div>
                                
                                <div class="col-md-6 form-group mb-3">
                                    <label for="featured">Featured Post <span class="text-danger">*</span></label>
                                    <select name="featured" id="featured" class="form-control select" required>
                                        <option {{ $model->featured == 1 ? 'selected' : '' }} value="1">Yes</option>
                                        <option {{ $model->featured == 0 ? 'selected' : '' }} value="0">No</option>
                                    </select>
                                </div>

                                <div class="col-md-12 mb-3 form-group">
                                    <label for="tags">Tags</label>
                                    <select name="tags[]" id="tags" class="form-control select" multiple data-placeholder="Select Tags">
                                        <option value="">Select Tags</option>
                                        @foreach ($tags as $tag)
                                            <option value="{{ $tag->id }}">{{ $tag->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                
                                <div class="col-md-12 form-group mb-3">
                                    <label for="site_title">Site Title <span class="text-danger">*</span></label>
                                    <input type="text" name="site_title" id="site_title" class="form-control" required value="{{ $model->site_title }}">
                                </div>
                                
                                <div class="col-md-12 form-group mb-3">
                                    <label for="meta_title">Meta Title <span class="text-danger">*</span></label>
                                    <input type="text" name="meta_title" id="meta_title" class="form-control" required value="{{ $model->meta_title }}">
                                </div>

                                <div class="col-md-12 mb-3 form-group">
                                    <label for="meta_keyword">Meta Keywords</label>
                                    <textarea name="meta_keyword" id="meta_keyword" cols="30" rows="3" class="form-control">{{ $model->meta_keyword }}</textarea>
                                </div>

                                <div class="col-md-12 mb-3 form-group">
                                    <label for="meta_description">Meta Description</label>
                                    <textarea name="meta_description" id="meta_description" cols="30" rows="3" class="form-control">{{ $model->meta_description }}</textarea>
                                </div>

                                <div class="col-md-12 mb-3 form-group">
                                    <label for="meta_google_schema">Google Schema</label>
                                    <textarea name="meta_google_schema" id="meta_google_schema" cols="30" rows="3" class="form-control">{{ $model->meta_google_schema }}</textarea>
                                </div>

                                <div class="col-md-12 mb-3 text-center">
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

        _initCkEditor('content');

        // Function to convert name to slug
        function generateSlug(name) {
            return name
                .toString()
                .toLowerCase()
                .trim()
                .replace(/&/g, '-and-') // Replace & with 'and'
                .replace(/[^\p{L}\p{N}\s-]/gu, '') // Remove invalid characters
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
                    id: '{{ $model->id }}',
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
