@php
	foreach ($attributes as $key => $val) {
		$$key = $val;
	}
	if (isset($checked) && !is_array($checked)) {
		$checked = [$checked];
	}
    $options = is_array($options)? $options:[$options => $options];
    $isGroup = isset($isGroup)? $isGroup:false;
    if ($isGroup) {
        $groupOptions = $options;
    }
@endphp
<div class="form-group">
    <label for="{{ $id }}">{{ $label }}</label>
    <div class="form-control h-auto {{ isset($data) && is_array($data)? implode(' data-',$data):'' }}" name="{{ $name }}">
        @if ($isGroup)
            @foreach ($groupOptions as $group => $options)
            <b class="d-block{{ !$loop->first ? ' mt-3' : '' }}">{{ $group }}</b>
                @foreach($options as $key => $val)
                <div class="custom-control custom-control-{{ isset($stacked) && $stacked? 'stacked':'inline' }} custom-radio">
                    <input 
                        type="radio"
                        name="{{ $name }}"
                        id="{{ $id }}-{{ $key }}"
                        class="custom-control-input"
                        value="{{ $key }}"
                        {{ isset($checked) && in_array($key, $checked)? 'checked':'' }}
                    >
                    <label for="{{ $id }}-{{ $key }}" class="custom-control-label">{{ $val }}</label>
                </div>
                @endforeach
            @endforeach
        @else
            @foreach($options as $key => $val)
            <div class="custom-control custom-control-{{ isset($stacked) && $stacked? 'stacked':'inline' }} custom-radio">
                <input 
    	            type="radio"
    	            name="{{ $name }}"
    	            id="{{ $id }}-{{ $key }}"
    	            class="custom-control-input"
    	            value="{{ $key }}"
    	            {{ isset($checked) && in_array($key, $checked)? 'checked':'' }}
                >
                <label for="{{ $id }}-{{ $key }}" class="custom-control-label">{{ $val }} haha</label>
            </div>
            @endforeach
        @endif
    </div>
    <div>
        <span class="invalid-feedback font-weight-bold" role="alert" id="{{ $name }}-alert"><span>
    </div>
</div>