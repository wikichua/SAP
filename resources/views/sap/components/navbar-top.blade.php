@php
	foreach ($attributes as $key => $val) {
		$$key = $val;
	}
	$data = isset($data) && is_array($data)? $data:[];
@endphp
	@foreach ($navs as $nav)
<li class="nav-item active">
    <a class="nav-link" href="{{ route_slug(strtolower($nav->brand->name).'.page',$nav->route_slug,$nav->route_params, $nav->locale) }}">
        {{ $nav->name }}
        <span class="sr-only">
            (current)
        </span>
    </a>
</li>
@endforeach
@push('scripts')
<script>
    $(function() {
});
</script>
@endpush
