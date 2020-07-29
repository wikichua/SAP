@php
	foreach ($attributes as $key => $value) {
		$$key = $value;
	}
@endphp
<div class="form-group">
	<label for="{{ $name }}">{{ $label }}</label>
	<input type="text"
		class="form-control @error($name) is-invalid @enderror {{ implode(' ',$class) }}"
		id="{{ $id }}"
		name="{{ $name }}"
	>
	<span class="invalid-feedback" role="alert">
		<strong>{{ isset($message)? $message:'' }}</strong>
	</span>
</div>