@php
    $id = str_random();
    foreach ($attributes as $key => $val) {
        $$key = $val;
    }
@endphp
<li class="nav-item">
    <a class="nav-link {{ $groupActive? '':'collapsed' }}" href="#" data-toggle="collapse" data-target="#{{ $id }}" aria-expanded="true"
        aria-controls="{{ $id }}">
        <i class="fas fa-fw fa-cog"></i>
        <span>Administrative</span>
    </a>
    <div id="{{ $id }}" class="collapse {{ $groupActive? 'show':'' }}" aria-labelledby="{{ $id }}" data-parent="#accordionSidebar">
        <div class="bg-white py-2 collapse-inner rounded">
            {{-- <h6 class="collapse-header">Custom Components:</h6> --}}
            <x-sap-menu-item :href="route('user.list')" active-pattern="user.*">User</x-sap-menu-item>
            <x-sap-menu-item :href="route('role.list')" active-pattern="role.*">Role</x-sap-menu-item>
            <x-sap-menu-item :href="route('permission.list')" active-pattern="permission.*">Permission</x-sap-menu-item>
            <x-sap-menu-item :href="route('setting.list')" active-pattern="setting.*">Setting</x-sap-menu-item>
            <x-sap-menu-item :href="route('activity_log.list')" active-pattern="activity_log.*">Activity Log</x-sap-menu-item>
        </div>
    </div>
</li>