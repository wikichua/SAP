@php
    $id = str_random();
    foreach ($attributes as $key => $val) {
        $$key = $val;
    }
@endphp
@canany([
    'Read Brands',
    'Read Navs',
    'Read Pages',
    'Read Components',
    'Read Carousels',
    'Read Files',
    'Read Pushers',
])
<li class="nav-item">
    <a class="nav-link {{ $groupActive? '':'collapsed' }}" href="#" data-toggle="collapse" data-target="#{{ $id }}" aria-expanded="true"
        aria-controls="{{ $id }}">
        <i class="fas fa-fw fa-database"></i>
        <span>CMS</span>
    </a>
    <div id="{{ $id }}" class="collapse {{ $groupActive? 'show':'' }}" aria-labelledby="{{ $id }}" data-parent="#accordionSidebar">
        <div class="bg-white py-2 collapse-inner rounded">
            {{-- <h6 class="collapse-header">Custom Components:</h6> --}}
            @can('Read Brands')
            <x-sap::menu-item :href="route('brand.list')" :active-pattern="'brand.*'" icon="fas fa-laptop">Brand</x-sap::menu-item>
            @endcan
            @can('Read Pages')
            <x-sap::menu-item :href="route('page.list')" :active-pattern="'page.*'" icon="fas fa-blog">Page</x-sap::menu-item>
            @endcan
            @can('Read Navs')
            <x-sap::menu-item :href="route('nav.list')" :active-pattern="'nav.*'" icon="fas fa-bars">Nav</x-sap::menu-item>
            @endcan
            @can('Read Components')
            <x-sap::menu-item :href="route('component.list')" :active-pattern="'component.*'" icon="fas fa-cube">Component</x-sap::menu-item>
            @endcan
            @can('Read Carousels')
            <x-sap::menu-item :href="route('carousel.list')" :active-pattern="'carousel.*'" icon="fas fa-images">Carousel</x-sap::menu-item>
            @endcan
            @can('Read Files')
            <x-sap::menu-item :href="route('file.list')" :active-pattern="'file.*'" icon="fas fa-archive">Files</x-sap::menu-item>
            @endcan
            @can('Read Pushers')
            <x-sap::menu-item :href="route('pusher.list')" :active-pattern="'pusher.*'" icon="fas fa-envelope-open-text">Pusher</x-sap::menu-item>
            @endcan
        </div>
    </div>
</li>
@endcanany
