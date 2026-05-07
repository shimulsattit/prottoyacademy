<a href="{{ route('portal.blog-tag.edit', $model->id) }}" class="btn btn-warning btn-sm">
    <i class="fas fa-edit"></i>
</a>

<a href="javascript:;" id="delete_item" data-id ="{{ $model->id }}" data-url="{{ route('portal.blog-tag.destroy',$model->id) }}" class="btn btn-danger btn-sm">
    <i class="fas fa-trash"></i>
</a>
