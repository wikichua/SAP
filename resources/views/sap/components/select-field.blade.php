@php
	foreach ($attributes as $key => $val) {
		$$key = $val;
	}
	if (isset($selected) && !is_array($selected)) {
		$selected = [$selected];
	}
    $options = is_array($options)? collect($options)->toArray():[$options => $options];
    $data = isset($data) && is_array($data)? $data:[];
@endphp
<div class="form-group">
	<label for="{{ $id }}">{!! $label !!}</label>
    <select
    	id="{{ $id }}"
    	name="{{ $name }}{{ isset($attribute_tags) && in_array('multiple',$attribute_tags)? '[]':'' }}"
    	class="selectpicker form-control {{ implode(' ',$class) }}"
        @foreach (isset($attribute_tags)? $attribute_tags:[] as $attr_key => $attr_val)
            {{ $attr_key }} = "{{ $attr_val }}"
        @endforeach
        @foreach ($data as $data_key => $data_value)
            {{ 'data-'.$data_key }}="{{ $data_value }}"
        @endforeach
    	>
        @if (!isset($attribute_tags) || !in_array('multiple',$attribute_tags))
        <option value=""></option>
        @endif
        @foreach($options as $key => $val)
        <option value="{{ $key }}" {{ isset($selected) && in_array($key, $selected)? 'selected':'' }}>{{ $val }}</option>
        @endforeach
    </select>
    <span class="invalid-feedback font-weight-bold" role="alert" id="{{ $name }}-alert"></span>
</div>
