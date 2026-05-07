@extends('layouts.admin', ['title' => 'Recycle Bin - Category'])
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
                        Recycle Bin - Category
                    </h1>
                    <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">
                        <li class="breadcrumb-item text-muted">
                            <a href="{{ route('portal.dashboard') }}" class="text-muted text-hover-primary">Home</a>
                        </li>
                        <li class="breadcrumb-item">
                            <span class="bullet bg-gray-500 w-5px h-2px"></span>
                        </li>
                        <li class="breadcrumb-item text-muted">Recycle Bin - Category</li>
                    </ul>
                </div>
            </div>
        </div>

        <div id="app_content" class="app-content flex-column-fluid">
            <div id="app_content_container" class="app-container container-xxl">

                <div class="alert alert-primary d-flex align-items-center p-5">
                    <i class="fas fa-trash-alt fs-2hx text-primary me-4"><span class="path1"></span><span class="path2"></span></i>

                    <div class="d-flex flex-column">
                        <h4 class="mb-1 text-primary">Automatic Deletion of Recycle Bin Data</h4>

                        <span>For better storage management and system efficiency, any files in the Recycle Bin that are older than <b>one month</b> will be <b>automatically deleted</b>. This ensures that unnecessary data does not accumulate over time, keeping the system optimized and clutter-free.</span>
                    </div>
                </div>
                
                <div class="card">
                    <div class="card-body">
                        <table id="data-table" class="table table-bordered table-striped">
                            <thead>
                                <tr class="text-start text-gray-500 fw-bold fs-7 text-uppercase gs-0">
                                    <th>ID</th>
                                    <th>Name</th>
                                    <th>Since</th>
                                    <th>Deleted</th>
                                    <th class="w-75px">Actions</th>
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
                ajax: "{{ route('portal.bin.categories') }}",
                columns: [
                    {
                        data: 'id',
                        name: 'id',
                        order: true,
                        visible: false
                    },
                    {data: 'name', name: 'name'},
                    {data: 'created_at', name: 'created_at'},
                    {data: 'deleted_at', name: 'deleted_at'},
                    {data: 'action', name: 'action', orderable: false, searchable: false},
                ],
                order: [0, 'DESC']
            });
        });
    </script>
@endpush
