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
    'pusher.*',
]"/>
@if (Route::has('lfm.home'))
<x-sap::menu menu="menu" label="File Manager" :route="route('lfm.home')" icon="fas fa-fw fa-folder" :active-patterns="['lfm.*']"/>
@endif
@if (Route::has('seo.home'))
@can('Manage SEO')
<x-sap::menu menu="menu" label="SEO Manager" :route="route('seo.home')" icon="fas fa-fw fa-folder" :active-patterns="['seo.*']"/>
@endcan
@endif
@if (Route::has('wiki.home'))
@can('Read Wiki Docs')
<x-sap::menu menu="menu" label="Wiki Docs" :route="route('wiki.home')" icon="fab fa-fw fa-wikipedia-w" :active-patterns="['wiki.*']"/>
@endcan
@endif
<!--DoNotRemoveMe-->
@foreach ($brandMenus as $brandMenu)
@include($brandMenu)
@endforeach
