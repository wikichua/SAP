@php
	foreach ($attributes as $key => $val) {
        $$key = $val;
    }
@endphp
<a class="collapse-item {{ $menuActive? 'active':'' }}" href="{{ $href }}"><i class="mr-1 {{ $icon ?? '' }}"></i>{{ $slot }}</a>
