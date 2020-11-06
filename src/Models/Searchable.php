<?php

namespace Wikichua\SAP\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

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
    public function scopeFilterTags($query, $search)
    {
        return $query->whereRaw('`tags` RLIKE "\\:\.'.$search.'\."');
    }
}
