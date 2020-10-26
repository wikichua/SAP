<?php

namespace Brand\{%brand_name%}\Components;

use Illuminate\View\Component;

class Carousel extends Component
{
    public $slug;
    public $tags;
    public $brand;
    public function __construct($slug = '', array $tags = [])
    {
        $this->brand = '{%brand_string%}';
        $this->slug = $slug;
        $this->tags = $tags;
    }
    public function render()
    {
        $uniqueId = \Str::uuid();
        $brand_id = brand($this->brand)->id;
        $carousels = app(config('sap.models.carousel'))->query()
            ->where('status', 'A')
            ->where('brand_id', $brand_id)
            ->where('slug', $this->slug)
            ->whereJsonContains('tags', $this->tags)
            ->orderBy('seq')
            ->get();
        return view('{%brand_string%}::components.carousel', compact('carousels','uniqueId'));
    }
}
