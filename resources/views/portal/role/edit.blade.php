@extends('layouts.admin', ['title' => 'Update Role Information'])
@push('style')
@endpush
@section('content')
<div class="app-main flex-column flex-row-fluid" id="app_main">
    <div class="d-flex flex-column flex-column-fluid">
        <div id="app_toolbar" class="app-toolbar py-3 py-lg-6">
            <div id="app_toolbar_container" class="app-container container-xxl d-flex flex-stack">
                <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
                    <h1 class="page-heading d-flex text-gray-900 fw-bold fs-3 flex-column justify-content-center my-0">
                        Update Role Information
                    </h1>
                    <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">
                        <li class="breadcrumb-item text-muted">
                            <a href="{{ route('portal.dashboard') }}" class="text-muted text-hover-primary">Home</a>
                        </li>
                        <li class="breadcrumb-item">
                            <span class="bullet bg-gray-500 w-5px h-2px"></span>
                        </li>
                        @if (Auth::guard('admin')->user()->hasPermissionTo('roles.view'))
                            <li class="breadcrumb-item text-muted">
                                <a href="{{ route('portal.roles.index') }}" class="text-muted text-hover-primary">
                                    Role Management
                                </a>
                            </li>
                        @endif
                        <li class="breadcrumb-item">
                            <span class="bullet bg-gray-500 w-5px h-2px"></span>
                        </li>
                        <li class="breadcrumb-item text-muted">Update Role Information</li>
                    </ul>
                </div>
            </div>
        </div>

        <div id="app_content" class="app-content flex-column-fluid">
            <div id="app_content_container" class="app-container container-xxl">
                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('portal.roles.update', $role->id) }}" method="POST" class="content_form">
                            @method('PATCH')
                            <div class="row">
                                
                                <div class="row">
                                    <div class="col-md-12 mx-auto">
                                        <div class="form-group">
                                            <label for="name">Role Name</label>
                                            <input type="text" required name="name" value="{{ $role->name }}" class="form-control">
                                        </div>
                                    </div>
                                </div>
                                <div class="row mt-4 table-responsive">
                                    <div class="col-md-12">
                                        <h4>Permission </h4>
                                        <table class="table table-bordered">
                                            @foreach (split_name($permissions) as $key => $element)
                                                <tr>
                                                    <td rowspan ="{{ count($element)+1}}">{!! $key !!}</td>
                                                    <td rowspan="{{ count($element)+1}}">
                                                        <div class="custom-control custom-checkbox">
                                                            <input type="checkbox" class="custom-control-input select_all" id="select_all_{{ toUnderscore($key)}}" data-id="{{ toUnderscore($key)}}">
                                                            <label class="custom-control-label" for="select_all_{{ toUnderscore($key)}}">Select all</label>
                                                        </div>
                                                    </td>
                                                </tr>
                                                @foreach ($element as $per)
                                                    <tr>
                                                        <td>
                                                            <div class="custom-control custom-checkbox">
                                                                <input type="checkbox" class="custom-control-input {{ toUnderscore($key)}}" id="{{$per}}" multiple="multiple" name="permissions[]" value="{{$per}}" {{in_array($per, $role_permissions)?'checked':''}}>
                                                                <label class="custom-control-label" for="{{$per}}">{{ toSpan($per) }}</label>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            @endforeach
                                        </table>
                                    </div>
                                </div>
                                
                                @if (Auth::guard('admin')->user()->hasPermissionTo('roles.update'))
                                    <div class="col-md-12 form-group text-center">
                                        <button type="submit" class="btn btn-sm btn-primary" id="update-password-submit">
                                            <i class="fas fa-paper-plane fa-fw"></i>
                                            Update Role 
                                        </button>
                                        <button style="display: none;" type="button" disabled class="btn btn-sm btn-primary" id="update-password-submitting" >
                                            <i class="fas fa-spinner fa-spin fa-fw"></i>
                                            Processing
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
    <script>
        _componentSelect();
        _formValidation();

        $(document).on('click','.select_all',function(){
            var id =$(this).data('id');
            if (this.checked) {
                $("."+id).prop('checked', true);
            } else{
                $("."+id).prop('checked', false);
            }
        });
    </script>
@endpush
