@php
	foreach ($attributes as $key => $val) {
		$$key = $val;
	}
    $options = is_array($options)? collect($options)->toArray():[$options => $options];
@endphp
<select name="{{ $name }}" id="{{ $id }}" class="selectpicker form-control" data-style="border bg-white" data-live-search=true multiple="multiple">
    <option value=""></option>
    @foreach($options as $key => $val)
    <option value="{{ $key }}">{{ $val }}</option>
    @endforeach
</select>
