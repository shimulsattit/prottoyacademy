@if (Auth::guard('admin')->user()->hasPermissionTo('year.view'))
    <a href="{{ route('portal.year.edit', $model->uuid) }}" class="btn btn-warning btn-sm">
        <i class="fas fa-edit"></i>
    </a>
@endif

@if (Auth::guard('admin')->user()->hasPermissionTo('year.delete'))
    <a href="javascript:;" id="delete_item" data-id ="{{ $model->uuid }}" data-url="{{ route('portal.year.destroy',$model->uuid) }}" class="btn btn-danger btn-sm">
        <i class="fas fa-trash"></i>
    </a>
@endif
