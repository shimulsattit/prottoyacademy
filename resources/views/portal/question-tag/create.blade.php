@extends('layouts.admin', ['title' => 'Create New Question Tag'])
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
                        Create New Question Tag (নতুন প্রশ্ন ট্যাগ তৈরি করুন)
                    </h1>
                    <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">
                        <li class="breadcrumb-item text-muted">
                            <a href="{{ route('portal.dashboard') }}" class="text-muted text-hover-primary">Home</a>
                        </li>
                        <li class="breadcrumb-item">
                            <span class="bullet bg-gray-500 w-5px h-2px"></span>
                        </li>
                        <li class="breadcrumb-item text-muted">
                            <a href="{{ route('portal.question-tag.index') }}" class="text-muted text-hover-primary">Question Tag Management</a>
                        </li>
                        <li class="breadcrumb-item">
                            <span class="bullet bg-gray-500 w-5px h-2px"></span>
                        </li>
                        <li class="breadcrumb-item text-muted">Create Tag</li>
                    </ul>
                </div>
            </div>
        </div>

        <div id="app_content" class="app-content flex-column-fluid">
            <div id="app_content_container" class="app-container container-xxl">
                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('portal.question-tag.store') }}" method="POST" class="content_form">
                            <div class="row">
                                <div class="col-md-6 mb-3 form-group">
                                    <label for="name">Name (নাম) <span class="text-danger">*</span></label>
                                    <input type="text" name="name" id="name" class="form-control" required value="">
                                </div>

                                <div class="col-md-6 mb-3 form-group">
                                    <label for="slug">Slug (স্লাগ) <span class="text-danger">*</span></label>
                                    <input type="text" name="slug" id="slug" class="form-control" required value="">
                                </div>
                                
                                <div class="col-md-12 form-group mb-3">
                                    <label for="status">Status (অবস্থা) <span class="text-danger">*</span></label>
                                    <select name="status" id="status" class="form-control select" required>
                                        <option value="1">Publish (পাবলিশ)</option>
                                        <option value="0">Unpublish (আনপাবলিশ)</option>
                                    </select>
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
    <script>
        _componentSelect();
        _formValidation();

        function generateSlug(name) {
            return name
                .toString()
                .toLowerCase()
                .trim()
                .replace(/&/g, '-and-')
                .replace(/[^\u0980-\u09FF\w\s-]/g, '') // Allow Bangla characters
                .replace(/\s+/g, '-')
                .replace(/-+/g, '-');
        }

        $('#name').on('input', function() {
            const name = $(this).val();
            const slug = generateSlug(name);
            $('#slug').val(slug);

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
