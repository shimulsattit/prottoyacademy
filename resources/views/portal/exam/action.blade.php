@if(Auth::guard('admin')->user()->hasPermissionTo('exam.update'))
    <a href="{{ route('portal.exam.edit', $model->uuid) }}" class="btn btn-warning btn-sm">
        <i class="fas fa-edit"></i>
    </a>
@endif 

@if(Auth::guard('admin')->user()->hasPermissionTo('exam.delete'))
    <a href="javascript:;" id="delete_item" data-id ="{{ $model->uuid }}" data-url="{{ route('portal.exam.destroy',$model->uuid) }}" class="btn btn-danger btn-sm">
        <i class="fas fa-trash"></i>
    </a>
@endif 
