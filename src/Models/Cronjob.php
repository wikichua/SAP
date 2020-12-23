<?php

namespace Wikichua\SAP\Models;

use Illuminate\Database\Eloquent\Model as Eloquent;
use Illuminate\Support\Carbon;

class Cronjob extends Eloquent
{
    use \Illuminate\Database\Eloquent\SoftDeletes;
    use \Wikichua\SAP\Http\Traits\AllModelTraits;

    protected $activity_logged = true;

    protected $dates = ['deleted_at'];
    protected $fillable = [
        'name',
        'mode',
        'timezone',
        'command',
        'frequency',
        'status',
        'created_by',
        'updated_by',
    ];

    protected $appends = [
        'status_name',
        // 'readUrl'
    ];

    protected $searchableFields = [
        'name'
    ];

    protected $casts = [];

    public function scopeFilterName($query, $search)
    {
        return $query->where('name', 'like', "%{$search}%");
    }

    public function scopeFilterStatus($query, $search)
    {
        return $query->whereIn('status', $search);
    }

    public function getStatusNameAttribute($value)
    {
        return settings('cronjob_status')[$this->attributes['status']] ?? 'P';
    }

    public function getReadUrlAttribute($value)
    {
        return $this->readUrl = route('carousel.show', $this->id);
    }
}