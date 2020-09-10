<?php
namespace Wikichua\SAP\Http\Traits;

use Illuminate\Http\Request;

trait BrandControllerTrait
{
    public function register($brandName)
    {
        $this->brand = app(config('sap.models.brand'))->query()
            ->where('name', $brandName)->first();
    }
    public function page(Request $request, $slug)
    {
        $model = app(config('sap.models.page'))->query()
            ->where('brand_id', $this->brand->id)
            ->where('slug', strtolower(trim($slug)))
            ->first();
        if (!$model) {
            abort(404);
        }
        return $this->getViewPage('page', compact('model'));
    }

    public function getViewPage($file, array $compact = [])
    {
        return view($this->page_path.$file, $compact);
    }
}
