@if (Auth::guard('admin')->user()->hasPermissionTo('stuff.view'))
    <a href="{{ route('portal.stuff.show', $model->id) }}" class="btn btn-success btn-sm">
        <i class="fas fa-eye"></i>
    </a> &nbsp;
@endif

@if ($model->id != 1)
    @if (Auth::guard('admin')->user()->hasPermissionTo('stuff.update'))
        <a href="{{ route('portal.stuff.edit', $model->id) }}" class="btn btn-warning btn-sm">
            <i class="fas fa-edit"></i>
        </a> &nbsp;
    @endif

    @if (Auth::guard('admin')->user()->hasPermissionTo('stuff.delete'))
        <a href="javascript:;" id="delete_item" data-id ="{{ $model->id }}" data-url="{{ route('portal.stuff.destroy',$model->id) }}" class="btn btn-danger btn-sm">
            <i class="fas fa-trash"></i>
        </a>
    @endif
@endif
