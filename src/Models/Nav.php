<?php

namespace Wikichua\SAP\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class Nav extends Model
{
    use \Illuminate\Database\Eloquent\SoftDeletes;
    use \Wikichua\SAP\Http\Traits\AllModelTraits;

    protected $activity_logged = true;
    protected $menu_icon = 'fas fa-bars';

    protected $dates = ['deleted_at'];
    protected $table = 'navs';
    protected $fillable = [
        'name',
        'brand_id',
        'locale',
        'seq',
        'group_slug',
        'icon',
        'route_slug',
        'route_params',
        'status',
        'created_by',
        'updated_by',
    ];

    protected $appends = [
        'status_name',
        'readUrl',
    ];

    protected $searchableFields = ['name','route_slug','group_slug'];

    protected $casts = [
        'route_params' => 'array',
    ];

    public function getStatusNameAttribute($value)
    {
        return isset($this->attributes['status'])? settings('nav_status')[$this->attributes['status']]:'';
    }

    public function scopeFilterName($query, $search)
    {
        return $query->where('name', 'like', "%{$search}%");
    }

    public function scopeFilterGroupSlug($query, $search)
    {
        return $query->where('group_slug', 'like', "%{$search}%");
    }

    public function scopeFilterRouteSlug($query, $search)
    {
        return $query->where('route_slug', 'like', "%{$search}%");
    }

    public function scopeFilterStatus($query, $search)
    {
        return $query->whereIn('status', $search);
    }

    public function getReadUrlAttribute($value)
    {
        return $this->readUrl = route('nav.show', $this->id);
    }

    public function brand()
    {
        return $this->belongsTo(config('sap.models.brand'), 'brand_id', 'id');
    }
}
