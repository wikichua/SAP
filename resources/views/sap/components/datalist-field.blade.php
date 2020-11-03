@php
	foreach ($attributes as $key => $val) {
		$$key = $val;
	}
	if (isset($selected) && !is_array($selected)) {
		$selected = [$selected];
	}
    $options = is_array($options)? collect($options)->toArray():[$options => $options];
    $data = isset($data) && is_array($data)? $data:[];
    $uniqueId = $id.'-datalist';
@endphp
<div class="form-group">
	<label for="{{ $id }}">{!! $label !!}</label>
    <input type="text"
        class="form-control custom-select {{ implode(' ',$class) }}"
        id="{{ $id }}"
        list="{{ $uniqueId }}"
        name="{{ $name }}"
        @foreach (isset($attribute_tags)? $attribute_tags:[] as $attr_key => $attr_val)
            {{ $attr_key }} = "{{ $attr_val }}"
        @endforeach
        @foreach ($data as $data_key => $data_value)
            {{ 'data-'.$data_key }}="{{ $data_value }}"
        @endforeach
        value="{{ isset($value)? $value:'' }}"
    >
    <span class="invalid-feedback font-weight-bold" role="alert" id="{{ $name }}-alert"><span>
    <datalist id="{{ $uniqueId }}">
        @foreach($options as $key => $val)
        <option value="{{ $key }}" {{ isset($selected) && in_array($key, $selected)? 'selected':'' }}>{{ $val }}</option>
        @endforeach
    </datalist>
</div>
