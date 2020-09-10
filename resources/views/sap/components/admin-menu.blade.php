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
@if (Route::has('lfm.home'))
<x-sap-menu menu="menu" label="File Manager" :route="route('lfm.home')" icon="fas fa-fw fa-folder" :active-patterns="['lfm.*']"/>
@endif
@if (Route::has('seo.home'))
<x-sap-menu menu="menu" label="SEO Manager" :route="route('seo.home')" icon="fas fa-fw fa-folder" :active-patterns="['seo.*']"/>
@endif

<!--DoNotRemoveMe-->
