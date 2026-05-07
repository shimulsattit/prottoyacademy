<a href="javascript:;" id="restore_button_{{ $model->uuid }}" data-id="{{ $model->uuid }}" data-url="{{ route('portal.job-category.restore', $model->uuid) }}" class="btn btn-icon restore btn-success btn-sm">
    <i class="fas fa-backspace"></i>
</a>

<a href="javascript:;" id="delete_item" data-id ="{{ $model->uuid }}" data-url="{{ route('portal.job-category.force-delete',$model->uuid) }}" class="btn btn-icon btn-danger btn-sm">
    <i class="fas fa-trash"></i>
</a>
