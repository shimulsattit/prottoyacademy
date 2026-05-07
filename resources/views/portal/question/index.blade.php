@extends('layouts.admin', ['title' => 'Question Management', 'modal' => 'lg'])
@push('style')
<link href="https://cdn.datatables.net/1.11.4/css/dataTables.bootstrap5.min.css" rel="stylesheet">
@endpush
@section('content')
<div class="app-main flex-column flex-row-fluid" id="app_main">
    <div class="d-flex flex-column flex-column-fluid">
        <div id="app_toolbar" class="app-toolbar py-3 py-lg-6">
            <div id="app_toolbar_container" class="app-container container-xxl d-flex flex-stack">
                <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
                    <h1 class="page-heading d-flex text-gray-900 fw-bold fs-3 flex-column justify-content-center my-0">
                        Question Management
                    </h1>
                    <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">
                        <li class="breadcrumb-item text-muted">
                            <a href="{{ route('portal.dashboard') }}" class="text-muted text-hover-primary">Home</a>
                        </li>
                        <li class="breadcrumb-item">
                            <span class="bullet bg-gray-500 w-5px h-2px"></span>
                        </li>
                        <li class="breadcrumb-item text-muted">Question Management</li>
                    </ul>
                </div>

                @if(Auth::guard('admin')->user()->hasPermissionTo('question.create'))
                    <div class="d-flex align-items-center gap-2 gap-lg-3">
                        <a href="{{ route('portal.question.create') }}" class="btn btn-sm fw-bold btn-primary">
                            Create
                            {{-- @if ($category)
                                Under {{ $category->name }}
                            @endif --}}
                        </a>
                    </div>
                @endif
            </div>
        </div>

        <div id="app_content" class="app-content flex-column-fluid">
            <div id="app_content_container" class="app-container container-xxl">
                {{-- <div class="card mb-3">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12 form-group">
                                <div class="col-md-12 form-group mb-3">
                                    <label for="category_id">Category <span class="text-danger">*</span></label>
                                    <select name="category_id[]" id="category_id" class="form-control"></select>
                                </div>
                            </div>

                            <div class="Sub_Categories row"></div>

                            <div class="col-md-4">
                                <button type="button" id="search" class="btn btn-primary btn-sm btn-block">
                                    Search
                                </button>
                                @if ($category)
                                    <button type="button" id="reset" class="btn btn-danger btn-sm">
                                        Reset
                                    </button>
                                @endif
                            </div>

                        </div>
                    </div>
                </div> --}}
                <div class="card">
                    <div class="card-body">
                        @if ($category)
                            <h4 class="mb-3 pb-3">Result Showing For: {{ $category->name }} </h4>
                        @endif
                        <table id="data-table" class="table table-bordered table-striped">
                            <thead>
                                <tr class="text-start text-gray-500 fw-bold fs-7 text-uppercase gs-0">
                                    <th>ID</th>
                                    <th>Question</th>
                                    <th>Category</th>
                                    <th>Created By</th>
                                    <th>Status</th>
                                    <th>Since</th>
                                    <th class="text-end min-w-150px">Actions</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
    @php
        if($category) {
            $route = route('portal.question.index', ['category' => $category->id]);
        } else {
            $route = route('portal.question.index');
        }
    @endphp
    <script src="https://cdn.datatables.net/1.11.4/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.4/js/dataTables.bootstrap5.min.js"></script>
    <script>

        $(function () {

            $(document).on('click', '#search', function() {
                let category_ids = [];
                $('select[name="category_id[]"]').each(function() {
                    const val = $(this).val();
                    if (val) category_ids.push(val);
                });

                let lastCategoryId = category_ids[category_ids.length - 1];
                
                if (lastCategoryId) {
                    window.location.href = `/portal/question?category_id=${lastCategoryId}`;
                } else {
                    alert('Please select a category.');
                }
            });

            $(document).on('click', '#reset', function() {
                window.location.href="/portal/question";
            })
        
            var table = $('#data-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ $route }}",
                columns: [
                    {
                        data: 'id',
                        name: 'id',
                        order: true,
                        visible: false
                    },
                    {data: 'question', name: 'question'},
                    {data: 'category', name: 'category'},
                    {data: 'created_by', name: 'created_by'},
                    {data: 'status', name: 'status'},
                    {data: 'created_at', name: 'created_at'},
                    {data: 'action', name: 'action', orderable: false, searchable: false},
                ],
                order: [0, 'DESC']
            });

            _componentRemoteModalLoadAfterAjax();

            $('#category_id').select2({
                width: '100%',
                placeholder: 'Select category',
                ajax: {
                    url: '/search/category',
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

            $('#category_id').change(function() {
                const categoryId = $(this).val();
                $('.Sub_Categories').empty();
                $('.specification_key').empty();
                $('#add-another').hide();

                if (categoryId) {
                    fetchSubCategories(categoryId, 1, $(this));
                }
            });

            function fetchSubCategories(parentId, level, parentSelect) {
                $.ajax({
                    url: `/portal/question/create?parent_id=${parentId}`,
                    type: 'GET',
                    dataType: 'json',
                    success: function(data) {
                        if (data.subs && data.subs.length > 0) {
                            appendSubCategories(data.subs, level, parentSelect);
                        }
                    }
                });
            }

            function appendSubCategories(subCategories, level, parentSelect) {
                const subCategoryDiv = $('.Sub_Categories');
                const categoryGroup = $('<div>', {
                    class: 'subcategory-group mb-3'
                });

                const select = $('<select>', {
                    name: 'category_id[]',
                    class: 'form-control mb-2',
                    required:true,
                    'data-level': level
                });

                const selectLabel = '--Select ' + 'Sub '.repeat(level) + 'Category--';
                select.append(`<option value="" disabled selected>${selectLabel}</option>`);

                $.each(subCategories, function(index, sub) {
                    select.append(`<option value="${sub.id}">${'-'.repeat(level)} ${sub.name}</option>`);
                });

                categoryGroup.append(select);
                subCategoryDiv.append(categoryGroup);

                // Initialize Select2 on new subcategory select
                select.select2();

                select.change(function() {
                    const selectedSubCategoryId = $(this).val();
                    if (selectedSubCategoryId) {
                        fetchSubCategories(selectedSubCategoryId, level + 1, $(this));
                    }
                });
            }
        });
    </script>
@endpush
