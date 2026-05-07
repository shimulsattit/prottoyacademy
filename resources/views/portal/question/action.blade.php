@if(Auth::guard('admin')->user()->hasPermissionTo('question.update'))
    <a href="{{ route('portal.question.edit', $model->uuid) }}" class="btn btn-warning btn-sm">
        <i class="fas fa-edit"></i>
    </a>
@endif 

@if(Auth::guard('admin')->user()->hasPermissionTo('question.view'))
    <a href="javascript:;" id="content_management" data-url="{{ route('portal.question.show', $model->uuid) }}" class="btn btn-success btn-sm">
        <i class="fas fa-eye"></i>
    </a>
@endif 

@if(Auth::guard('admin')->user()->hasPermissionTo('question.delete'))
    <a href="javascript:;" id="delete_item" data-id ="{{ $model->uuid }}" data-url="{{ route('portal.question.destroy',$model->uuid) }}" class="btn btn-danger btn-sm">
        <i class="fas fa-trash"></i>
    </a>
@endif 
