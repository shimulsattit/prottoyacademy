<a id="content_management" href="javascript:;" data-url="{{ route('portal.home-carousel.edit', $model->id) }}" class="btn btn-warning btn-sm">
    <i class="fas fa-edit"></i>
</a>

<a href="javascript:;" id="delete_item" data-id ="{{ $model->id }}" data-url="{{ route('portal.home-carousel.destroy',$model->id) }}" class="btn btn-danger btn-sm">
    <i class="fas fa-trash"></i>
</a>
