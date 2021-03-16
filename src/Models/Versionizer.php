<?php

namespace Wikichua\SAP\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class Versionizer extends Model
{
    use \Wikichua\SAP\Http\Traits\AllModelTraits;
    protected $activity_logged = false;
    protected $menu_icon = 'fas fa-code-branch';

    protected $dates = ['reverted_at'];
    protected $fillable = [
        'id',
        'mode',
        'model',
        'model_id',
        'data',
        'changes',
        'brand_id',
        'reverted_at',
        'reverted_by',
        'created_at',
        'updated_at',
    ];

    protected $appends = [];

    protected $searchableFields = [];

    protected $casts = [
        'data' => 'array',
        'changes' => 'array',
    ];
    public function brand()
    {
        return $this->belongsTo(config('sap.models.brand'))->withDefault(['name' => null]);
    }
    public function getRevertedAtAttribute($value)
    {
        return $this->inUserTimezone($value);
    }
    public function revertor()
    {
        return $this->belongsTo(config('sap.models.user'), 'reverted_by', 'id');
    }
    public function scopeFilterData($query, $search)
    {
        $searches = [
            $search,
            strtolower($search),
            strtoupper($search),
            ucfirst($search),
            ucwords($search),
        ];
        return $query->whereRaw('`data` RLIKE ":\.*?('.implode('|', $searches).')\.*?"');
    }
    public function scopeFilterDirty($query, $search)
    {
        $searches = [
            $search,
            strtolower($search),
            strtoupper($search),
            ucfirst($search),
            ucwords($search),
        ];
        return $query->whereRaw('`dirty` RLIKE ":\.*?('.implode('|', $searches).')\.*?"');
    }
}
