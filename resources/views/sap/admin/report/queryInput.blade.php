<div class="form-group">
    <label for="queries" class="col-md-2 col-form-label">SQL queries</label>
    <div class="form-control h-auto" id="script-input-div">
        @if (isset($model->queries) && is_array($model->queries))
            @foreach ($model->queries as $index => $val)
            <div class="row mb-1">
                <div class="col-11 d-flex justify-content-center">
                    <textarea type="text" name="queries[]" class="form-control" placeholder="" rows="1">{{ $val }}</textarea>
                </div>
                <div class="col-1 d-flex justify-content-end">
                    <div class="btn-group" role="group">
                        <button type="button" class="addRow btn btn-outline-primary"><i class="fa fa-plus"></i></button>
                        <button type="button" class="minusRow btn btn-outline-danger"><i class="fa fa-minus"></i></button>
                    </div>
                </div>
            </div>
            @endforeach
        @else
        <div class="row mb-1">
            <div class="col-11 d-flex justify-content-center">
                <textarea type="text" name="queries[]" class="form-control" placeholder="" rows="1"></textarea>
            </div>
            <div class="col-1 d-flex justify-content-end">
                <div class="btn-group" role="group">
                    <button type="button" class="addRow btn btn-outline-primary"><i class="fa fa-plus"></i></button>
                    <button type="button" class="minusRow btn btn-outline-danger"><i class="fa fa-minus"></i></button>
                </div>
            </div>
        </div>
        @endif
    </div>
</div>
@push('queries')
<script id="template" type="text/x-lodash-template">
<div class="row mb-1">
    <div class="col-11 d-flex justify-content-center">
        <textarea type="text" name="queries[]" class="form-control" placeholder="" rows="1"></textarea>
    </div>
    <div class="col-1 d-flex justify-content-end">
        <div class="btn-group" role="group">
            <button type="button" class="addRow btn btn-outline-primary"><i class="fa fa-plus"></i></button>
            <button type="button" class="minusRow btn btn-outline-danger"><i class="fa fa-minus"></i></button>
        </div>
    </div>
</div>
</script>
<script>
$(function () {
    $(document).on('click','.addRow',function() {
        var template = $('#template').html();
        var templateFn = _.template(template);
        var templateHTML = templateFn();
        $(this).closest('.row').after(templateHTML);
    });
    $(document).on('click','.minusRow',function() {
        if ($('#script-input-div').find('.row').length > 1) {
            $(this).closest('.row').remove();
        }
    });
});
</script>
@endpush
