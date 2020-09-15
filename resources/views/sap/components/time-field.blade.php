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
	let ${{ $id }} = $('#{{ $id }}').timepicker({
	    uiLibrary: 'bootstrap4',
	    modal: true
	});
	$(document).on('focus', '#{{ $id }}', function(event) {
		event.preventDefault();
		${{ $id }}.open();
	});
});
</script>
@endpush
