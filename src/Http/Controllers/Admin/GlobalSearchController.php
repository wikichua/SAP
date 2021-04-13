<?php

namespace Wikichua\SAP\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

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

    public function suggest(Request $request)
    {
        $data = [];
        $searchable = app(config('sap.models.searchable'))->query();
        $queryStr = $request->input('q');
        $searchables = $searchable->filterTags($queryStr)->take(10)->get();
        foreach ($searchables as $item) {
            $desc = [];
            foreach ($item->tags as $key => $val) {
                $desc[] = ucwords($key).' : '.$val;
            }
            $data[] = [
                'title' => basename(str_replace('\\', '/', $item->model)),
                'url' => app($item->model)->find($item->model_id)->readUrl ?? '#',
                'created_at' => $item->updated_at ?? $item->created_at ?? '',
                'desc' => implode('<br />', $desc),
            ];
        }
        return response()->json($data);
    }

    private function search(string $queryStr = '')
    {
        $searchable = app(config('sap.models.searchable'))->query();
        return $searchable->filterTags($queryStr)->paginate(20);
    }
}
