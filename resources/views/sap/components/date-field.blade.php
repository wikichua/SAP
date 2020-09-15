@php
	foreach ($attributes as $key => $val) {
		$$key = $val;
	}
	$data = isset($data) && is_array($data)? $data:[];
@endphp
<div class="form-group">
	<label for="{{ $id }}">{{ $label }}</label>
	<input type="text"
		class="form-control {{ implode(' ',$class) }}"
		id="{{ $id }}"
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
</div>

@push('scripts')
<script>
$(function() {
	$('#{{ $id }}').datepicker({ uiLibrary: 'bootstrap4', modal: true, header: true, footer: true, format: 'yyyy-mm-dd' });
});
</script>
@endpush
