<?php

namespace Wikichua\SAP\Models;

use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Model;

class Searchable extends Model
{
    use \Wikichua\SAP\Http\Traits\AllModelTraits;

    protected $dates = [];
    protected $fillable = [];

    protected $appends = [];

    protected $searchableFields = [];

    protected $casts = [
        'tags' => 'array'
    ];
    public function brand()
    {
        return $this->belongsTo(config('sap.models.brand'))->withDefault(['name' => null]);
    }
    public function scopeFilterTags($query, $search)
    {
        $searches = [
            $search,
            strtolower($search),
            strtoupper($search),
            ucfirst($search),
            ucwords($search),
        ];
        return $query->whereRaw('`tags` RLIKE ":\.*?('.implode('|', $searches).')\.*?"');
    }
}
