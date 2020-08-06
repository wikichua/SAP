@php
	foreach ($attributes as $key => $val) {
		$$key = $val;
	}
@endphp
<div class="form-group">
	<label for="{{ $id }}">{{ $label }}</label>
	<input type="text"
		class="form-control {{ implode(' ',$class) }}"
		id="{{ $id }}"
		name="{{ $name }}"
		@foreach ($attribute_tags as $attr_key => $attr_val)
			{{ $attr_key }} = "{{ $attr_val }}"
		@endforeach
		{{ isset($data) && is_array($data)? implode(' data-',$data):'' }}
		value="{{ isset($value)? $value:'' }}"
	>
	<span class="invalid-feedback font-weight-bold" role="alert" id="{{ $name }}-alert"><span>
</div>

@push('scripts')
<script>
$(function() {
	$('#{{ $id }}').datepicker({ uiLibrary: 'bootstrap4', modal: true, header: true, footer: true });
});
</script>
@endpush
