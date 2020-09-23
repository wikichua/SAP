<div class="form-group">
    <label for="styles" class="col-md-2 col-form-label">Styles</label>
    <div class="form-control h-auto" id="style-input-div">
        @if (isset($model->styles) && is_array($model->styles))
            @foreach ($model->styles as $index => $val)
            <div class="row mb-1">
                <div class="col-11 d-flex justify-content-center">
                    <textarea type="text" name="styles[]" class="form-control" placeholder="" rows="1">{{ $val }}</textarea>
                </div>
                <div class="col-1 d-flex justify-content-end">
                    <div class="btn-group" role="group">
                        <button type="button" class="addRowStyle btn btn-outline-primary"><i class="fa fa-plus"></i></button>
                        <button type="button" class="minusRowStyle btn btn-outline-danger"><i class="fa fa-minus"></i></button>
                    </div>
                </div>
            </div>
            @endforeach
        @else
        <div class="row mb-1">
            <div class="col-11 d-flex justify-content-center">
                <textarea type="text" name="styles[]" class="form-control" placeholder="" rows="1"></textarea>
            </div>
            <div class="col-1 d-flex justify-content-end">
                <div class="btn-group" role="group">
                    <button type="button" class="addRowStyle btn btn-outline-primary"><i class="fa fa-plus"></i></button>
                    <button type="button" class="minusRowStyle btn btn-outline-danger"><i class="fa fa-minus"></i></button>
                </div>
            </div>
        </div>
        @endif
    </div>
</div>
@push('scripts')
<script id="style-template" type="text/x-lodash-template">
<div class="row mb-1">
    <div class="col-11 d-flex justify-content-center">
        <textarea type="text" name="styles[]" class="form-control" placeholder="" rows="1"></textarea>
    </div>
    <div class="col-1 d-flex justify-content-end">
        <div class="btn-group" role="group">
            <button type="button" class="addRowStyle btn btn-outline-primary"><i class="fa fa-plus"></i></button>
            <button type="button" class="minusRowStyle btn btn-outline-danger"><i class="fa fa-minus"></i></button>
        </div>
    </div>
</div>
</script>
<script>
$(function () {
    $(document).on('click','.addRowStyle',function() {
        var template = $('#style-template').html();
        var templateFn = _.template(template);
        var templateHTML = templateFn();
        $(this).closest('.row').after(templateHTML);
    });
    $(document).on('click','.minusRowStyle',function() {
        if ($('#style-input-div').find('.row').length > 1) {
            $(this).closest('.row').remove();
        }
    });
});
</script>
@endpush
