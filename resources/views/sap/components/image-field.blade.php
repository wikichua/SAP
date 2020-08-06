@php
	foreach ($attributes as $key => $val) {
		$$key = $val;
	}
    if (isset($value))
        $value = is_array($value)? $value:[$value];
    else
        $value = [];
@endphp
<div class="form-group">
    <label for="{{ $id }}">{{ $label }}</label>
	<input type="file" id="{{ $id }}"
        name="{{ $name }}{{ in_array('multiple',$attribute_tags) ?? '[]' }}"
        @foreach ($attribute_tags as $attr_key => $attr_val)
            {{ $attr_key }} = "{{ $attr_val }}"
        @endforeach
        {{ isset($data) && is_array($data)? implode(' data-',$data):'' }}
        class="file form-control {{ implode(' ', $class) }}"
        data-preview-file-type="text">
	<span class="invalid-feedback font-weight-bold" role="alert" id="{{ $name }}-alert"><span>
</div>

@push('scripts')
<script>
{{-- https://plugins.krajee.com/file-input --}}
$(function() {
	$("#{{ $id }}").fileinput({
        showUpload:false,
        showCancel:false,
        previewFileType:'image',
        initialPreview: {!! json_encode($value) !!},
    });
    $('.fileinput-upload-button, .fileinput-cancel-button').hide();
});
</script>
@endpush
