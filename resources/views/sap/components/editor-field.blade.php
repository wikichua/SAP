@php
	foreach ($attributes as $key => $val) {
		$$key = $val;
	}
    $data = isset($data) && is_array($data)? $data:[];
@endphp
<div class="form-group">
	<label for="{{ $id }}">{!! $label !!}</label>
	<textarea class="form-control {{ implode(' ',$class) }}"
		id="{{ $id }}"
		name="{{ $name }}"
		@foreach (isset($attribute_tags)? $attribute_tags:[] as $attr_key => $attr_val)
			{{ $attr_key }} = "{{ $attr_val }}"
		@endforeach
		@foreach ($data as $data_key => $data_value)
            {{ 'data-'.$data_key }}="{{ $data_value }}"
        @endforeach
        >{!! $value ?? '' !!}</textarea>
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
            onInit: function() {
                @if (isset($codeview) && $codeview == true)
                $("div.note-editor button.btn-codeview").click();
                @endif
            },
            onImageUpload: function(files) {
                let file = files[0];
                onImageUpload(files[0],$(this));
            }
        }
    });
});
</script>
@endpush
