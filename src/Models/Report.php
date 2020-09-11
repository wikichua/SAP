<?php

namespace Wikichua\SAP\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class Report extends Model
{
    use \Illuminate\Database\Eloquent\SoftDeletes;
    use \Wikichua\SAP\Http\Traits\AllModelTraits;

    protected $dates = ['deleted_at'];
    protected $fillable = [
        'created_by',
        'updated_by',
        'name',
        'queries',
        'status'
    ];

    protected $appends = [
        'status_name',
    ];

    protected $searchableFields = [];

    protected $casts = [
        'queries' => 'array',
    ];

    public function getStatusNameAttribute($value)
    {
        return settings('report_status')[$this->attributes['status']];
    }

    public function scopeFilterName($query, $search)
    {
        return $query->where('name', 'like', "%{$search}%");
    }

    public function scopeFilterStatus($query, $search)
    {
        return $query->whereIn('status', $search);
    }
}
