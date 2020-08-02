@php
	foreach ($attributes as $key => $val) {
		$$key = $val;
	}
	if (isset($selected) && !is_array($selected)) {
		$selected = [$selected];
	}
    $options = is_array($options)? collect($options)->toArray():[$options => $options];
@endphp
<div class="form-group">
	<label for="{{ $id }}">{{ $label }}</label>
    <select 
    	name="{{ $name }}"
    	id="{{ $id }}"
    	class="selectpicker form-control {{ implode(' ',$class) }}"
        @foreach ($attribute_tags as $attr_key => $attr_val)
            {{ $attr_key }} = "{{ $attr_val }}"
        @endforeach
    	{{ isset($data) && is_array($data)? implode(' data-',$data):'' }}
    	>
        <option value=""></option>
        @foreach($options as $key => $val)
        <option value="{{ $key }}" {{ isset($selected) && in_array($key, $selected)? 'selected':'' }}>{{ $val }}</option>
        @endforeach
    </select>
    <span class="invalid-feedback font-weight-bold" role="alert" id="{{ $name }}-alert"></span>
</div>