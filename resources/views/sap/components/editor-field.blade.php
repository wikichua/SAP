@php
	foreach ($attributes as $key => $val) {
		$$key = $val;
	}
@endphp
<div class="form-group">
	<label for="{{ $id }}">{{ $label }}</label>
	<textarea class="form-control {{ implode(' ',$class) }}"
		id="{{ $id }}"
		name="{{ $name }}"
		@foreach ($attribute_tags as $attr_key => $attr_val)
			{{ $attr_key }} = "{{ $attr_val }}"
		@endforeach
		{{ isset($data) && is_array($data)? implode(' data-',$data):'' }}>{!! $value ?? '' !!}</textarea>
	<span class="invalid-feedback font-weight-bold" role="alert" id="{{ $name }}-alert"><span>
</div>

@push('scripts')
<script>
$(function() {
    $('#{{ $id }}').summernote({
        height: 300,
        minHeight: null,
        maxHeight: null,
        callbacks: {
            onImageUpload: function(files) {
                let file = files[0];
                onImageUpload(files[0],$(this));
            }
        }
    });
});
</script>
@endpush
