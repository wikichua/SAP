@php
	foreach ($attributes as $key => $val) {
		$$key = $val;
	}
@endphp
<div class="form-group">
	<label for="{{ $id }}">{{ $label }}</label>
	<div class="custom-file">
	    <input type="file" class="custom-file-input form-control {{ implode(' ',$class) }}"
	    class="form-control {{ implode(' ',$class) }}"
		id="{{ $id }}"
		name="{{ $name }}"
		@foreach ($attribute_tags as $attr_key => $attr_val)
			{{ $attr_key }} = "{{ $attr_val }}"
		@endforeach
		{{ isset($data) && is_array($data)? implode(' data-',$data):'' }}
		>
	    <label class="custom-file-label" for="{{ $id }}">Choose file</label>
	</div>
	<span class="invalid-feedback font-weight-bold" role="alert" id="{{ $name }}-alert"><span>
</div>