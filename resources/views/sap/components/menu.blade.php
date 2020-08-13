<x-sap-menu menu="dashboard" />
<x-sap-menu menu="admin" :active-patterns="[
        'user.*',
        'permission.*',
        'role.*',
        'setting.*',
        'activity_log.*',
        'system_log.*',
    ]"/>
<!--DoNotRemoveMe-->
