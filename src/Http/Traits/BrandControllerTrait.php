<?php
namespace Wikichua\SAP\Http\Traits;

use Illuminate\Http\Request;

trait BrandControllerTrait
{
    public function register($brandName)
    {
        $this->brand = cache()->remember('register-'.$brandName, (60*60*24), function () use ($brandName) {
            return app(config('sap.models.brand'))->query()
                ->where('name', $brandName)->first();
        });
        \Config::set('main.brand', $this->brand);
        return $this;
    }
    public function setLocale()
    {
        $locale = request()->route('locale');
        if (!in_array($locale, $this->supportedLocales)) {
            $locale = 'en';
        }
        app()->setLocale($locale);
        return $this;
    }
    public function page(Request $request, $locale, $slug)
    {
        $model = app(config('sap.models.page'))->query()
            ->where('brand_id', $this->brand->id)
            ->where('locale', app()->getLocale())
            ->where('slug', strtolower($slug))
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
