<?php

namespace Wikichua\SAP\Http\Controllers\Admin;

class LogViewerController extends \Rap2hpoutre\LaravelLogViewer\LogViewerController
{
    public function __construct()
    {
        $this->middleware(['auth_admin', 'can:Access Admin Panel']);
        $this->middleware('intend_url')->only(['index', 'read']);
        $this->middleware('can:Read System Logs')->only(['index', 'read']);
        parent::__construct();
        $this->view_log = 'sap::admin.log.viewer';
    }

    public function index()
    {
        return parent::index();
    }
}
