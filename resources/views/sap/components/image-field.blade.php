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
        <div class="input-group mb-1">
        <div class="custom-file">
            <input type="file"
                class="image-file custom-file-input form-control {{ implode(' ', $class) }}"
                id="{{ $id }}"
                {{ $is_mulitple? 'multiple':'' }}
                name="{{ $name }}{{ $is_mulitple? '[]':'' }}"
                accept="image/*"
            >
            <label class="custom-file-label" for="{{ $id }}">Choose file</label>
        </div>
    </div>
    <div class="row">
        <div class="col-6 mt-1 img-uploaded">
            <h6>Uploaded</h6>
            <div class="row">
                @foreach ($values as $k => $val)
                @if ($val != '')
                <div class="col-2">
                    <button type="button" class="text-danger btn btn-link position-absolute text-decoration-none font-weight-bolder">
                        <i class="fas fa-times-circle"></i>
                    </button>
                    <a href="{{ asset($val) }}" target="_blank" class="btn btn-link text-decoration-none font-weight-bolder">
                        <img src="{{ asset($val) }}" class="img-thumbnail">
                    </a>
                </div>
                @endif
                @endforeach
            </div>
        </div>
        <div class="col-6 mt-1 img-preview">
            <h6>Pending</h6>
            <div class="row">
            </div>
        </div>
    </div>
    <span class="invalid-feedback font-weight-bold" role="alert" id="{{ $name }}-alert"><span>
</div>

@push('scripts')
<script>
$(function() {

});
</script>
@endpush
