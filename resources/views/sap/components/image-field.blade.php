@php
	foreach ($attributes as $key => $val) {
		$$key = $val;
	}
    $is_mulitple = in_array('multiple',$attribute_tags)? true:false;
    unset($attribute_tags['multiple']);
    if (isset($value))
        $values = is_array($value)? $value:[$value];
    else
        $values = [];
@endphp
<div class="form-group">
    <label for="{{ $id }}">{{ $label }}</label>
    @foreach ($values as $val)
        <div class="input-group mb-1">
        <div class="custom-file">
            <input type="file"
                class="image-file custom-file-input form-control {{ implode(' ', $class) }}"
                id="{{ $is_mulitple? $id:'' }}"
                name="{{ $name }}{{ $is_mulitple? '[]':'' }}"
                value="{{ $val }}"
            >
            <label class="custom-file-label" for="{{ $id }}">Choose file</label>
        </div>
        @if ($is_mulitple)
        <div class="input-group-append">
            <button type="button" class="add-{{ $id }} btn btn-outline-primary"><i class="fas fa-plus"></i></button>
            <button type="button" class="del-{{ $id }} btn btn-outline-danger mr-2"><i class="fas fa-minus"></i></button>
        </div>
        @endif
        <div class="col-12 mt-1 d-flex justify-content-around img-preview">
            @if ($val != '')
            <img src="{{ asset($val) }}" class="img-thumbnail col-1">
            @endif
        </div>
    </div>
    @endforeach
    <span class="invalid-feedback font-weight-bold" role="alert" id="{{ $name }}-alert"><span>
</div>

@push('scripts')
<script id="{{ $id }}-upload-template" type="text/x-lodash-template">
<div class="input-group mb-1">
    <div class="custom-file">
        <input type="file"
            class="image-file custom-file-input form-control {{ implode(' ', $class) }}"
            id="{{ $is_mulitple? $id:'' }}"
            name="{{ $name }}{{ $is_mulitple? '[]':'' }}"
        >
        <label class="custom-file-label" for="{{ $id }}">Choose file</label>
    </div>
    @if ($is_mulitple)
    <div class="input-group-append">
        <button type="button" class="add-{{ $id }} btn btn-outline-primary"><i class="fas fa-plus"></i></button>
        <button type="button" class="del-{{ $id }} btn btn-outline-danger mr-2"><i class="fas fa-minus"></i></button>
    </div>
    @endif
    <div class="col-12 mt-1 d-flex justify-content-around img-preview"></div>
</div>
</script>
<script>
$(function() {
    $(document).on('click', '.add-{{ $id }}', function(event) {
        event.preventDefault();
        let uploadTemplate = $("#{{ $id }}-upload-template").html();
        let templateFn = _.template(uploadTemplate);
        let templateHTML = templateFn();
        $(this).closest('.input-group').after(templateHTML);
    });
    $(document).on('click', '.del-{{ $id }}', function(event) {
        event.preventDefault();
        if ($(this).closest('.form-group').find('.input-group').length > 1) {
            $(this).closest('.input-group').remove();
        }
    });
});
</script>
@endpush
