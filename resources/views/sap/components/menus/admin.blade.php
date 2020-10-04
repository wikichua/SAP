@php
    $id = str_random();
    foreach ($attributes as $key => $val) {
        $$key = $val;
    }
@endphp
@canany([
    'Read Users',
    'Read Roles',
    'Read Permissions',
    'Read Settings',
    'Read Activity Logs',
    'Read System Logs',
    'Read Reports',
    'Read Brands',
    'Read Navs',
    'Read Pages',
    'Read Components',
    'Read Carousels',
])
<li class="nav-item">
    <a class="nav-link {{ $groupActive? '':'collapsed' }}" href="#" data-toggle="collapse" data-target="#{{ $id }}" aria-expanded="true"
        aria-controls="{{ $id }}">
        <i class="fas fa-fw fa-cog"></i>
        <span>Administrative</span>
    </a>
    <div id="{{ $id }}" class="collapse {{ $groupActive? 'show':'' }}" aria-labelledby="{{ $id }}" data-parent="#accordionSidebar">
        <div class="bg-white py-2 collapse-inner rounded">
            {{-- <h6 class="collapse-header">Custom Components:</h6> --}}
            @can('Read Users')
            <x-sap::menu-item :href="route('user.list')" active-pattern="user.*">User</x-sap-menu-item>
            @endcan
            @can('Read Roles')
            <x-sap::menu-item :href="route('role.list')" active-pattern="role.*">Role</x-sap-menu-item>
            @endcan
            @can('Read Permissions')
            <x-sap::menu-item :href="route('permission.list')" active-pattern="permission.*">Permission</x-sap-menu-item>
            @endcan
            @can('Read Settings')
            <x-sap::menu-item :href="route('setting.list')" active-pattern="setting.*">Setting</x-sap-menu-item>
            @endcan
            @can('Read Reports')
            <x-sap::menu-item :href="route('report.list')" active-pattern="report.*">Report</x-sap-menu-item>
            @endcan
            @can('Read Brands')
            <x-sap::menu-item :href="route('brand.list')" :active-pattern="'brand.*'">Brand</x-sap-menu-item>
            @endcan
            @can('Read Pages')
            <x-sap::menu-item :href="route('page.list')" :active-pattern="'page.*'">Page</x-sap-menu-item>
            @endcan
            @can('Read Navs')
            <x-sap::menu-item :href="route('nav.list')" :active-pattern="'nav.*'">Nav</x-sap-menu-item>
            @endcan
            @can('Read Components')
            <x-sap::menu-item :href="route('component.list')" :active-pattern="'component.*'">Component</x-sap-menu-item>
            @endcan
            @can('Read Carousels')
            <x-sap::menu-item :href="route('carousel.list')" :active-pattern="'carousel.*'">Carousel</x-sap-menu-item>
            @endcan
            @can('Read Activity Logs')
            <x-sap::menu-item :href="route('activity_log.list')" active-pattern="activity_log.*">Activity Log</x-sap-menu-item>
            @endcan
            @can('Read System Logs')
            <x-sap::menu-item :href="route('system_log.list')" active-pattern="system_log.*">System Log</x-sap-menu-item>
            @endcan
        </div>
    </div>
</li>
@endcanany
