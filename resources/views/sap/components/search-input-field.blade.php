@php
	foreach ($attributes as $key => $val) {
		$$key = $val;
	}
@endphp
<input type="{{ $type }}" class="form-control filterInput" id="{{ $id }}" name="{{ $name }}">
