@php
	foreach ($attributes as $key => $value) {
		$$key = $value;
	}
@endphp
<div class="form-group">
	<label for="{{ $id }}">{{ $label }}</label>
    <select 
    	name="{{ $name }}"
    	id="{{ $id }}"
    	class="selectpicker form-control @error('{{ $name }}') is-invalid @enderror"
    	{{ isset($data) && is_array($data)? implode(' data-',$data):'' }}
    	>
        <option value=""></option>
        @foreach($options as $key => $val)
        <option value="{{ $key }}">{{ $val }}</option>
        @endforeach
    </select>
    <span class="invalid-feedback" role="alert">
		<strong>{{ isset($message)? $message:'' }}</strong>
    </span>
</div>