@php
	foreach ($attributes as $key => $val) {
		$$key = $val;
	}
	if (isset($checked) && !is_array($checked)) {
		$checked = [$checked];
	}
@endphp
<div class="form-group">
    <label for="{{ $id }}">{{ $label }}</label>
    <div class="form-control form-control-plaintext {{ implode(' ',$class) }} {{ isset($data) && is_array($data)? implode(' data-',$data):'' }}">
        @foreach($options as $key => $val)
        <div class="custom-control custom-checkbox">
            <input 
	            type="checkbox"
	            name="{{ $name }}[{{ $key }}]"
	            id="{{ $id }}-{{ $key }}"
	            class="custom-control-input"
	            value="{{ $key }}"
	            {{ isset($checked) && in_array($key, $checked)? 'checked':'' }}
            >
            <label for="{{ $id }}-{{ $key }}" class="custom-control-label">{{ $val }}</label>
        </div>
        @endforeach
    </div>
    <div>
        <span class="invalid-feedback font-weight-bold" role="alert" id="{{ $name }}-alert"><span>
    </div>
</div>