@php
    $currentRoute = str_replace(url(''),'',url()->current());
@endphp
@foreach ($navs as $nav)
@php
    $active = '';
    if(\Str::startsWith($nav->route_slug, 'http')) {
        $route = $nav->route_slug;
    } else if(\Str::startsWith($nav->route_slug, '.')) {
        $route = route_slug($brand_name.$nav->route_slug,'',$nav->route_params, $nav->locale);
    } else {
        $route = route_slug($brand_name.'.page',$nav->route_slug,$nav->route_params, $nav->locale);
    }
    $routePath = str_replace(url(''), '', $route);
    if($routePath == $currentRoute) {
        $active = 'active';
    }
@endphp
<li class="nav-item {{ $active }}">
    <a class="nav-link" href="{{ $route }}">
        {{ $nav->name }}
    </a>
</li>
@endforeach
@push('scripts')
<script>
    $(function() {
});
</script>
@endpush
