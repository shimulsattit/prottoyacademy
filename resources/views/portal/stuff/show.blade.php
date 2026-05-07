@extends('layouts.admin', ['title' => 'Stuff Question Report'])
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
                        Stuff Question Report: {{ $user->first_name }} {{ $user->last_name }}
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
                        <li class="breadcrumb-item text-muted">Stuff Question Report</li>
                    </ul>
                </div>
            </div>
        </div>

        <div id="app_content" class="app-content flex-column-fluid">
            <div id="app_content_container" class="app-container container-xxl">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12 table-responsive">
                                <table class="table table-bordered table-striped">
                                    <thead>
                                        <th>Date</th>
                                        <th>Number of Question</th>
                                    </thead>
                                    <tbody>
                                        @if (count($stuffQuestions) == 0)
                                            <tr>
                                                <td colspan="2" class="text-center">No data available</td>
                                            </tr>
                                        @else 
                                            @foreach ($stuffQuestions as $stuffQuestion)
                                                <tr>
                                                    <td>{{ date('d F, Y', strtotime($stuffQuestion->date)) }}</td>
                                                    <td>{{ $stuffQuestion->total_questions }}</td>
                                                </tr>
                                            @endforeach
                                        @endif
                                        
                                    </tbody>
                                </table>

                                @if ($stuffQuestions->hasPages())
                                    <nav class="mt-4">
                                        <ul class="pagination justify-content-center">

                                            {{-- Previous Page Link --}}
                                            @if ($stuffQuestions->onFirstPage())
                                                <li class="page-item disabled">
                                                    <span class="page-link rounded">&laquo;</span>
                                                </li>
                                            @else
                                                <li class="page-item">
                                                    <a class="page-link rounded" href="{{ $stuffQuestions->previousPageUrl() }}" rel="prev">&laquo;</a>
                                                </li>
                                            @endif

                                            {{-- Pagination Elements --}}
                                            @foreach ($stuffQuestions->links()->elements[0] ?? [] as $page => $url)
                                                @if ($page == $stuffQuestions->currentPage())
                                                    <li class="page-item active">
                                                        <span class="page-link rounded">{{ $page }}</span>
                                                    </li>
                                                @else
                                                    <li class="page-item">
                                                        <a class="page-link rounded" href="{{ $url }}">{{ $page }}</a>
                                                    </li>
                                                @endif
                                            @endforeach

                                            {{-- Next Page Link --}}
                                            @if ($stuffQuestions->hasMorePages())
                                                <li class="page-item">
                                                    <a class="page-link rounded" href="{{ $stuffQuestions->nextPageUrl() }}" rel="next">&raquo;</a>
                                                </li>
                                            @else
                                                <li class="page-item disabled">
                                                    <span class="page-link rounded">&raquo;</span>
                                                </li>
                                            @endif

                                        </ul>
                                    </nav>
                                @endif

                            </div>
                        </div>
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
