<x-sap::menu menu="dashboard" />
<x-sap::menu menu="admin" :active-patterns="[
    'user.*',
    'permission.*',
    'role.*',
    'setting.*',
    'report.*',
    'activity_log.*',
    'system_log.*',
    'failed_job.*',
    'cronjob.*',
]"/>
<x-sap::menu menu="cms" :active-patterns="[
    'brand.*',
    'page.*',
    'nav.*',
    'component.*',
    'carousel.*',
    'file.*',
    'mailer.*',
]"/>
@if (Route::has('lfm.home'))
<x-sap::menu menu="menu" label="File Manager" :route="route('lfm.home')" icon="fas fa-fw fa-folder" :active-patterns="['lfm.*']"/>
@endif
@if (Route::has('seo.home'))
<x-sap::menu menu="menu" label="SEO Manager" :route="route('seo.home')" icon="fas fa-fw fa-folder" :active-patterns="['seo.*']"/>
@endif
<!--DoNotRemoveMe-->
@foreach ($brandMenus as $brandMenu)
@include($brandMenu)
@endforeach
