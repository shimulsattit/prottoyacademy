<form action="{{ route("portal.testimonial.store") }}" method="POST" class="ajax-form">
    <div class="modal-header">
        <h5 class="h6 mb-0">
            <b>Add New Item</b>
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
    </div>
    <div class="modal-body">
        <div class="row">

            <div class="col-md-6 form-group mt-3">
                <label for="name">Name <span class="text-danger">*</span></label>
                <input type="text" name="name" id="name" class="form-control" required>
            </div>

            <div class="col-md-6 form-group mt-3">
                <label for="designation">Designation</label>
                <input type="text" name="designation" id="designation" class="form-control">
            </div>

            <div class="col-md-12 mt-3 form-group">
                <label for="picture">Picture</label>
                <input type="file" name="picture" id="picture" class="form-control dropify">
            </div>

            <div class="col-md-12 form-group mt-3">
                <label for="content">Content <span class="text-danger">*</span></label>
                <textarea name="content" id="content" cols="30" rows="3" class="form-control" required></textarea>
            </div>
            
            <div class="col-md-4 mt-3 form-group">
                <label for="star">Review <span class="text-danger">*</span></label>
                <select name="star" id="star" class="form-control select" required data-minimum-results-for-search="Infinity">
                    <option selected value="5">5</option>
                    <option value="4">4</option>
                    <option value="3">3</option>
                    <option value="2">2</option>
                    <option value="1">1</option>
                </select>
            </div>

            <div class="col-md-4 mt-3 form-group">
                <label for="status">Status <span class="text-danger">*</span></label>
                <select name="status" id="status" class="form-control select" required data-minimum-results-for-search="Infinity">
                    <option selected value="1">Active</option>
                    <option value="0">Inactive</option>
                </select>
            </div>

            <div class="col-md-4 mt-3 form-group">
                <label for="show_on_home_page">Homepage show? <span class="text-danger">*</span></label>
                <select name="show_on_home_page" id="show_on_home_page" class="form-control select" required data-minimum-results-for-search="Infinity">
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
