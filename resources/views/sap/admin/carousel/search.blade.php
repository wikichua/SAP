<div class="modal fade" id="filterModalCenter" tabindex="-1" role="dialog" aria-labelledby="filterModalCenterTitle"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="filterModalCenterTitle">Advanced Filter</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                <div class="form-group">
    <label for="slug">Slug</label>
    <x-sap::search-input-field type="text" name="slug" id="slug"/>
    </div>

    <div class="form-group">
    <label for="tags">Tags</label>
    <x-sap::search-select-field name="tags" id="tags" :options="settings('carousel_tags')"/>
    </div>

    <div class="form-group">
    <label for="published_at">Published Date</label>
    <x-sap::search-date-field type="text" name="published_at" id="published_at"/>
    </div>

    <div class="form-group">
    <label for="expired_at">Expired Date</label>
    <x-sap::search-date-field type="text" name="expired_at" id="expired_at"/>
    </div>

    <div class="form-group">
    <label for="status">Status</label>
    <x-sap::search-select-field name="status" id="status" :options="settings('carousel_status')"/>
    </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary btn-sm" id="filterBtn">
                    <i class="fas fa-search mr-2"></i>Filter
                </button>
            </div>
        </div>
    </div>
</div>
@push('scripts')
<script>
    $(document).ready(function () {

    });
</script>
@endpush
