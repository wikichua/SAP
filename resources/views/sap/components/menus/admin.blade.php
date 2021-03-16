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
    'Read Cronjobs',
    'Read Mailers',
    'Read Versionizers',
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
            <x-sap::menu-item :href="route('user.list')" active-pattern="user.*" icon="fas fa-users">User</x-sap-menu-item>
            @endcan
            @can('Read Roles')
            <x-sap::menu-item :href="route('role.list')" active-pattern="role.*" icon="fas fa-id-badge">Role</x-sap-menu-item>
            @endcan
            @can('Read Permissions')
            <x-sap::menu-item :href="route('permission.list')" active-pattern="permission.*" icon="fas fa-lock">Permission</x-sap-menu-item>
            @endcan
            @can('Read Settings')
            <x-sap::menu-item :href="route('setting.list')" active-pattern="setting.*" icon="fas fa-cogs">Setting</x-sap-menu-item>
            @endcan
            @can('Read Reports')
            <x-sap::menu-item :href="route('report.list')" active-pattern="report.*" icon="fas fa-file-contract">Report</x-sap-menu-item>
            @endcan
            @can('Read Cronjobs')
            <x-sap::menu-item :href="route('cronjob.list')" active-pattern="cronjob.*" icon="fas fa-voicemail">Cron Job</x-sap-menu-item>
            @endcan
            @can('Read Activity Logs')
            <x-sap::menu-item :href="route('activity_log.list')" active-pattern="activity_log.*" icon="fas fa-stream">Activity Log</x-sap-menu-item>
            @endcan
            @can('Read System Logs')
            <x-sap::menu-item :href="route('system_log.list')" active-pattern="system_log.*" icon="fas fa-bug">System Log</x-sap-menu-item>
            @endcan
            @can('Read Failed Jobs')
            <x-sap::menu-item :href="route('failed_job.list')" active-pattern="failed_job.*" icon="fas fa-recycle">Failed Job</x-sap-menu-item>
            @endcan
            @can('Read Mailers')
            <x-sap::menu-item :href="route('mailer.list')" :active-pattern="'mailer.*'" icon="fas fa-mail-bulk">Mailers</x-sap-menu-item>
            @endcan
            @can('Read Versionizers')
            <x-sap::menu-item :href="route('versionizer.list')" :active-pattern="'versionizer.*'" icon="fas fa-code-branch">Versionizers</x-sap-menu-item>
            @endcan
        </div>
    </div>
</li>
@endcanany
