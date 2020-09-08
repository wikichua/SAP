<x-sap-menu menu="dashboard" />
<x-sap-menu menu="admin" :active-patterns="[
        'user.*',
        'permission.*',
        'role.*',
        'setting.*',
        'activity_log.*',
        'system_log.*',
        'brand.*',
        'page.*',
        'component.*',
    ]"/>
@if (Route::has('chatify'))
<x-sap-menu menu="chatify" />
@endif
@if (Route::has('lfm.home'))
<x-sap-menu menu="menu" label="File Manager" :route="route('lfm.home')" icon="fas fa-fw fa-folder" :active-patterns="['lfm.*']"/>
@endif

<!--DoNotRemoveMe-->
