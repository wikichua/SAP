<?php

namespace Wikichua\SAP\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class GlobalSearchController extends Controller
{
    public function __construct()
    {
    }

    public function index(Request $request)
    {
        $request->validate([
            'q' => 'required',
        ]);
        $queryStr = $request->input('q');
        $items = $this->search($queryStr);
        return view('sap::admin.dashboard.globalsearch')->with(compact('items'));
    }

    private function search(string $queryStr = '')
    {
        $searchable = app(config('sap.models.searchable'))->query();
        return $searchable->filterTags($queryStr)->paginate(25);
    }
}
