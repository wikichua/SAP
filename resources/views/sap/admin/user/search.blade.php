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
                    <label for="created_at">Created At</label>
                    <input type="text" class="form-control filterInput" name="created_at"
                        id="created_at">
                </div>
                <div class="form-group">
                    <label for="name">Name</label>
                    <input type="text" class="form-control filterInput" name="name"
                        id="name">
                </div>
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="text" class="form-control filterInput" name="email"
                        id="email">
                </div>
                <div class="form-group">
                    <label for="status">Type</label>
                    <select name="status" id="status" class="selectpicker form-control filterInput"
                        data-style="border bg-white" data-live-search="true">
                        <option value="">Please Select</option>
                        @foreach(settings('user_types') as $key => $val)
                            <option value="{{ $key }}">
                                {{ $val }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label for="status">Status</label>
                    <select name="status" id="status" class="selectpicker form-control filterInput"
                        data-style="border bg-white" data-live-search="true" multiple>
                        <option value="">Please Select</option>
                        @foreach(settings('user_status') as $key => $val)
                            <option value="{{ $key }}">
                                {{ $key }} - {{ $val }}
                            </option>
                        @endforeach
                    </select>
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
        $('#created_at').daterangepicker({
            "autoUpdateInput": false,
            ranges: {
                'Today': [moment(), moment()],
                'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                'This Month': [moment().startOf('month'), moment().endOf('month')],
                'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
            },
            "alwaysShowCalendars": true
        });
    });
</script>
@endpush
