@php
	foreach ($attributes as $key => $val) {
		$$key = $val;
	}
@endphp
<div class="form-group">
	{{-- <div class="custom-file">
	    <input type="file" class="custom-file-input form-control {{ implode(' ',$class) }}"
	    class="form-control {{ implode(' ', $class) }}"
		id="{{ $id }}"
		name="{{ $name }}"
		@foreach ($attribute_tags as $attr_key => $attr_val)
			{{ $attr_key }} = "{{ $attr_val }}"
		@endforeach
		{{ isset($data) && is_array($data)? implode(' data-',$data):'' }}
		>
	    <label class="custom-file-label" for="{{ $id }}">Choose file</label>
	</div> --}}
	<div class="custom-file-container  form-control h-auto" data-upload-id="{{ $id }}">
        <label for="{{ $id }}">{{ $label }} <a href="javascript:void(0)" class="custom-file-container__image-clear" title="Clear Image">&times;</a></label>
        <label class="custom-file-container__custom-file">
            <input type="file"
            	class="custom-file-container__custom-file__custom-file-input {{ implode(' ', $class) }}"
            	accept="image/*"
            	name="{{ $name . (in_array('multiple',$attribute_tags)? '[]':'') }}"
	            @foreach ($attribute_tags as $attr_key => $attr_val)
					{{ $attr_key }} = "{{ $attr_val }}"
				@endforeach
				{{ isset($data) && is_array($data)? implode(' data-',$data):'' }}
				aria-label="Choose File"
			>
            <input type="hidden" name="MAX_FILE_SIZE" value="10485760" />
            <span class="custom-file-container__custom-file__custom-file-control"></span>
        </label>
        <div class="custom-file-container__image-preview"></div>
    </div>
	<span class="invalid-feedback font-weight-bold" role="alert" id="{{ $name }}-alert"><span>
</div>

@push('scripts')
<script>
$(function() {
	var upload = new FileUploadWithPreview('{{ $id }}');
});
</script>
@endpush
