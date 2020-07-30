@php
	foreach ($attributes as $key => $value) {
		$$key = $value;
	}
@endphp
<div class="form-group">
	<label for="{{ $id }}">{{ $label }}</label>
	<input type="{{ $type }}"
		class="form-control @error($name) is-invalid @enderror {{ implode(' ',$class) }}"
		id="{{ $id }}"
		name="{{ $name }}"
		{{ isset($data) && is_array($data)? implode(' data-',$data):'' }}
	>
	<span class="invalid-feedback" role="alert">
		<strong>{{ isset($message)? $message:'' }}</strong>
	</span>
</div>