<?php

namespace Brand\{%brand_name%}\Components;

use Illuminate\View\Component;

class NavbarTop extends Component
{
    public $slug;
    public $tags;
    public $brand;
    public function __construct($slug, array $tags = [])
    {
        $this->brand = '{%brand_string%}';
        $this->slug = $slug;
        $this->tags = $tags;
    }
    public function render()
    {
        $brand_id = app(config('sap.models.brand'))->where('name', $this->brand)->first()->id;
        $carousels = app(config('sap.models.carousel'))->query()
            ->where('status', 'A')
            ->where('brand_id', $brand_id)
            ->where('slug', $this->slug)
            ->whereJsonContains('tags', $this->tags)
            ->orderBy('seq')
            ->get();
        return view('{%brand_string%}::components.carousel', compact('carousels'));
    }
}
