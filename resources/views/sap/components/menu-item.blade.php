@php
	foreach ($attributes as $key => $val) {
        $$key = $val;
    }
@endphp
<a class="collapse-item {{ $menuActive? 'active':'' }}" href="{{ $href }}">{{ $slot }}</a>