@if(Auth::guard('admin')->user()->hasPermissionTo('job_category.update'))
    <a href="{{ route('portal.job-category.edit', $model->uuid) }}" class="btn btn-warning btn-sm">
        <i class="fas fa-edit"></i>
    </a>
@endif

@if(Auth::guard('admin')->user()->hasPermissionTo('job_category.delete'))
    <a href="javascript:;" id="delete_item" data-id ="{{ $model->uuid }}" data-url="{{ route('portal.job-category.destroy',$model->uuid) }}" class="btn btn-danger btn-sm">
        <i class="fas fa-trash"></i>
    </a>
@endif
