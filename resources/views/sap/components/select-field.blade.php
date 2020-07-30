@php
	foreach ($attributes as $key => $val) {
		$$key = $val;
	}
	if (isset($selected) && !is_array($selected)) {
		$selected = [$selected];
	}
@endphp
<div class="form-group">
	<label for="{{ $id }}">{{ $label }}</label>
    <select 
    	name="{{ $name }}"
    	id="{{ $id }}"
    	class="selectpicker form-control {{ implode(' ',$class) }}"
    	{{ isset($data) && is_array($data)? implode(' data-',$data):'' }}
    	>
        <option value=""></option>
        @foreach($options as $key => $val)
        <option value="{{ $key }}" {{ isset($selected) && in_array($key, $selected)? 'selected':'' }}>{{ $val }}</option>
        @endforeach
    </select>
    <span class="invalid-feedback font-weight-bold" role="alert" id="{{ $name }}-alert"></span>
</div>