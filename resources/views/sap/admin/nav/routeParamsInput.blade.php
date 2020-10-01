<div class="form-group">
    <label for="route_params" class="col-md-2 col-form-label">Route Params</label>
    <div class="form-control h-auto" id="route_params-input-div">
        @if (isset($model->route_params) && is_array($model->route_params))
            @foreach ($model->route_params as $index => $val)
            <div class="row mb-1">
                <div class="col-11 d-flex justify-content-center">
                    <input type="text" name="route_params[]" class="form-control" placeholder="" value="{{ $val }}">
                </div>
                <div class="col-1 d-flex justify-content-end">
                    <div class="btn-group" role="group">
                        <button type="button" class="addRowRouteParams btn btn-outline-primary"><i class="fa fa-plus"></i></button>
                        <button type="button" class="minusRowRouteParams btn btn-outline-danger"><i class="fa fa-minus"></i></button>
                    </div>
                </div>
            </div>
            @endforeach
        @else
        <div class="row mb-1">
            <div class="col-11 d-flex justify-content-center">
                <input type="text" name="route_params[]" class="form-control" placeholder="" value="">
            </div>
            <div class="col-1 d-flex justify-content-end">
                <div class="btn-group" role="group">
                    <button type="button" class="addRowRouteParams btn btn-outline-primary"><i class="fa fa-plus"></i></button>
                    <button type="button" class="minusRowRouteParams btn btn-outline-danger"><i class="fa fa-minus"></i></button>
                </div>
            </div>
        </div>
        @endif
    </div>
</div>
@push('scripts')
<script id="route_params-template" type="text/x-lodash-template">
<div class="row mb-1">
    <div class="col-11 d-flex justify-content-center">
        <input type="text" name="route_params[]" class="form-control" placeholder="" value="">
    </div>
    <div class="col-1 d-flex justify-content-end">
        <div class="btn-group" role="group">
            <button type="button" class="addRowRouteParams btn btn-outline-primary"><i class="fa fa-plus"></i></button>
            <button type="button" class="minusRowRouteParams btn btn-outline-danger"><i class="fa fa-minus"></i></button>
        </div>
    </div>
</div>
</script>
<script>
$(function () {
    $(document).on('click','.addRowRouteParams',function() {
        var template = $('#route_params-template').html();
        var templateFn = _.template(template);
        var templateHTML = templateFn();
        $(this).closest('.row').after(templateHTML);
    });
    $(document).on('click','.minusRowRouteParams',function() {
        if ($('#route_params-input-div').find('.row').length > 1) {
            $(this).closest('.row').remove();
        }
    });
});
</script>
@endpush
