<div class="form-group">
    <label for="scripts" class="col-md-2 col-form-label">Scripts</label>
    <div class="form-control h-auto" id="script-input-div">
        @if (isset($model->scripts) && is_array($model->scripts))
            @foreach ($model->scripts as $index => $val)
            <div class="row mb-1">
                <div class="col-11 d-flex justify-content-center">
                    <textarea type="text" name="scripts[]" class="form-control" placeholder="" rows="1">{{ $val }}</textarea>
                </div>
                <div class="col-1 d-flex justify-content-end">
                    <div class="btn-group" role="group">
                        <button type="button" class="addRowScript btn btn-outline-primary"><i class="fa fa-plus"></i></button>
                        <button type="button" class="minusRowScript btn btn-outline-danger"><i class="fa fa-minus"></i></button>
                    </div>
                </div>
            </div>
            @endforeach
        @else
        <div class="row mb-1">
            <div class="col-11 d-flex justify-content-center">
                <textarea type="text" name="scripts[]" class="form-control" placeholder="" rows="1"></textarea>
            </div>
            <div class="col-1 d-flex justify-content-end">
                <div class="btn-group" role="group">
                    <button type="button" class="addRowScript btn btn-outline-primary"><i class="fa fa-plus"></i></button>
                    <button type="button" class="minusRowScript btn btn-outline-danger"><i class="fa fa-minus"></i></button>
                </div>
            </div>
        </div>
        @endif
    </div>
</div>
@push('scripts')
<script id="template" type="text/x-lodash-template">
<div class="row mb-1">
    <div class="col-11 d-flex justify-content-center">
        <textarea type="text" name="scripts[]" class="form-control" placeholder="" rows="1"></textarea>
    </div>
    <div class="col-1 d-flex justify-content-end">
        <div class="btn-group" role="group">
            <button type="button" class="addRowScript btn btn-outline-primary"><i class="fa fa-plus"></i></button>
            <button type="button" class="minusRowScript btn btn-outline-danger"><i class="fa fa-minus"></i></button>
        </div>
    </div>
</div>
</script>
<script>
$(function () {
    $(document).on('click','.addRowScript',function() {
        var template = $('#template').html();
        var templateFn = _.template(template);
        var templateHTML = templateFn();
        $(this).closest('.row').after(templateHTML);
    });
    $(document).on('click','.minusRowScript',function() {
        if ($('#script-input-div').find('.row').length > 1) {
            $(this).closest('.row').remove();
        }
    });
});
</script>
@endpush
