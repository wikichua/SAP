@php
	foreach ($attributes as $key => $val) {
		$$key = $val;
	}
@endphp
<div class="form-group">
    <label id="{{ $id }}-label">{{ $label }}</label>
    <div class="form-control h-auto {{ implode(' ',$class) }}" {{ isset($data) && is_array($data)? implode(' data-',$data):'' }} name="{{ $name }}">
        <div class="custom-control custom-checkbox">
            <input
                type="checkbox"
                name="{{ $name }}"
                id="{{ $id }}"
                class="custom-control-input"
                value="{{ $value }}"
                {{ isset($checked) && ($value == $checked)? 'checked':'' }}
            >
            <label for="{{ $id }}" class="custom-control-label">{{ $subLabel }}</label>
        </div>
    </div>
    <div>
        <span class="invalid-feedback font-weight-bold" role="alert" id="{{ $name }}-alert"><span>
    </div>
</div>
