@extends('layouts.admin', ['title' => 'Question Tag Management'])
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
                        Question Tag Management (প্রশ্ন ট্যাগ)
                    </h1>
                    <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">
                        <li class="breadcrumb-item text-muted">
                            <a href="{{ route('portal.dashboard') }}" class="text-muted text-hover-primary">Home</a>
                        </li>
                        <li class="breadcrumb-item">
                            <span class="bullet bg-gray-500 w-5px h-2px"></span>
                        </li>
                        <li class="breadcrumb-item text-muted">Question Tag Management</li>
                    </ul>
                </div>

                <div class="d-flex align-items-center gap-2 gap-lg-3">
                    <a href="{{ route('portal.question-tag.create') }}" class="btn btn-sm fw-bold btn-primary">
                        Create Tag (ট্যাগ তৈরি করুন)
                    </a>
                </div>
            </div>
        </div>

        <div id="app_content" class="app-content flex-column-fluid">
            <div id="app_content_container" class="app-container container-xxl">
                <div class="card">
                    <div class="card-body">
                        <table id="data-table" class="table table-bordered table-striped">
                            <thead>
                                <tr class="text-start text-gray-500 fw-bold fs-7 text-uppercase gs-0">
                                    <th>ID</th>
                                    <th>Name (নাম)</th>
                                    <th>Slug (স্লাগ)</th>
                                    <th>Status (অবস্থা)</th>
                                    <th>Since (তৈরির তারিখ)</th>
                                    <th class="text-end min-w-100px">Actions (অ্যাকশন)</th>
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
    <script src="https://cdn.datatables.net/1.11.4/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.4/js/dataTables.bootstrap5.min.js"></script>
    <script>
        $(function () {
            var table = $('#data-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('portal.question-tag.index') }}",
                columns: [
                    {
                        data: 'id',
                        name: 'id',
                        order: true,
                        visible: false
                    },
                    {data: 'name', name: 'name'},
                    {data: 'slug', name: 'slug'},
                    {data: 'status', name: 'status'},
                    {data: 'created_at', name: 'created_at'},
                    {data: 'action', name: 'action', orderable: false, searchable: false},
                ],
                order: [0, 'DESC']
            });
        });
    </script>
@endpush
