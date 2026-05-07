<form action="{{ route("portal.home-carousel.update", $model->id) }}" method="POST" class="ajax-form">
    @method('PATCH')
    <div class="modal-header">
        <h5 class="h6 mb-0">
            <b>Update Item Information</b>
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
    </div>
    <div class="modal-body">
        <div class="row">

            <div class="col-md-12 form-group">
                <label for="icon">Icon <span class="text-danger">*</span></label>
                <input type="text" name="icon" id="icon" value="{{ $model->icon }}" class="form-control" required>
            </div>

            <div class="col-md-6 mt-3 form-group">
                <label for="title">Title <span class="text-danger">*</span></label>
                <input type="text" name="title" id="title" value="{{ $model->title }}" class="form-control" required>
            </div>
            
            <div class="col-md-6 mt-3 form-group">
                <label for="content">Content <span class="text-danger">*</span></label>
                <input type="text" name="content" id="content" value="{{ $model->content }}" class="form-control" required>
            </div>

            <div class="col-md-12 mt-3 form-group">
                <label for="url">URL <span class="text-danger">*</span></label>
                <input type="url" name="url" id="url" value="{{ $model->url }}" class="form-control" required>
            </div>

            <div class="col-md-12 mt-3 form-group">
                <label for="status">Status <span class="text-danger">*</span></label>
                <select name="status" id="status" class="form-control select" required data-minimum-results-for-search="Infinity">
                    <option {{ $model->status == 1 ? 'selected' : '' }} value="1">Active</option>
                    <option {{ $model->status == 0 ? 'selected' : '' }} value="0">Inactive</option>
                </select>
            </div>
        </div>
    </div>
    <div class="modal-footer text-end">
        <button type="button" class="btn btn-sm btn-outline-danger" type="button" data-bs-dismiss="modal" aria-label="Close">
            <i class="fas fa-close"></i>
            Close
        </button>
        <button class="btn btn-sm btn-dark" type="submit" id="submit">
            <i class="fas fa-paper-plane"></i>
            Update
        </button>
        <button class="btn btn-sm btn-outline-dark" style="display: none;" id="submitting" type="button" disabled>
            <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
            Loading...
        </button>
    </div>
</form>
