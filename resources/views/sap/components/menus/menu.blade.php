@php
    foreach ($attributes as $key => $val) {
        $$key = $val;
    }
@endphp
<hr class="sidebar-divider my-0">
<li class="nav-item">
    <a class="nav-link" href="{{ $route }}">
        <i class="{{ $icon }}"></i>
        <span>{{ $label }}</span></a>
</li>
