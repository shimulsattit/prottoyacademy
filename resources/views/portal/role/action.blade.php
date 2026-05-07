@if ($model->id != 1)
    @if (Auth::guard('admin')->user()->hasPermissionTo('roles.update'))
        <a href="{{ route('portal.roles.edit', $model->id) }}" class="btn btn-warning btn-sm">
            <i class="fas fa-edit"></i>
        </a>
    @endif

    @if (Auth::guard('admin')->user()->hasPermissionTo('roles.delete'))
        <a href="javascript:;" id="delete_item" data-id ="{{ $model->id }}" data-url="{{ route('portal.roles.destroy',$model->id) }}" class="btn btn-danger btn-sm">
            <i class="fas fa-trash"></i>
        </a>
    @endif
@endif
