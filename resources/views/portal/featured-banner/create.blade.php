<form action="{{ route("portal.featured-banners.store") }}" method="POST" class="ajax-form">
    <div class="modal-header">
        <h5 class="h6 mb-0">
            <b>Add New Item</b>
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
    </div>
    <div class="modal-body">
        <div class="row">

            <div class="col-md-12 form-group">
                <label for="category_id">Category <span class="text-danger">*</span></label>
                <select name="category_id" id="category_id" class="form-control select" required data-placeholder="Select Category" data-parsley-errors-container="#category_id_error">
                    <option value="">Select Category</option>
                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}">{{ $category->category->name }}</option>
                    @endforeach
                </select>
                <span id="category_id_error"></span>
            </div>

            <div class="col-md-12 mt-3 form-group">
                <label for="banner">Banner <span class="text-danger">*</span></label>
                <input type="file" name="banner" id="banner" class="form-control dropify" required>
            </div>

            <div class="col-md-6 form-group mt-3">
                <label for="alt_tag">Alt Tag</label>
                <input type="text" name="alt_tag" id="alt_tag" class="form-control required">
            </div>
            
            <div class="col-md-6 form-group mt-3">
                <label for="content">Short Content</label>
                <input type="text" name="content" id="content" class="form-control required">
            </div>

            <div class="col-md-12 mt-3 form-group">
                <label for="status">Status <span class="text-danger">*</span></label>
                <select name="status" id="status" class="form-control select" required data-minimum-results-for-search="Infinity">
                    <option selected value="1">Active</option>
                    <option value="0">Inactive</option>
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
            Create
        </button>
        <button class="btn btn-sm btn-outline-dark" style="display: none;" id="submitting" type="button" disabled>
            <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
            Loading...
        </button>
    </div>
</form>
