<?php

namespace Wikichua\SAP\Models;

use Illuminate\Database\Eloquent\Model;

class Brand extends Model
{
    use \Illuminate\Database\Eloquent\SoftDeletes;
    use \Wikichua\SAP\Http\Traits\ModelScopes;
    use \Wikichua\SAP\Http\Traits\DynamicFillable;
    use \Wikichua\SAP\Http\Traits\UserTimezone;

    protected $dates = ['deleted_at'];
    protected $fillable = [
        'created_by',
        'updated_by',
        'name',
        'published_at',
        'expired_at',
        'status'
    ];


    protected $appends = [
        'status_name',
        'readUrl',
        'esField'
    ];

    protected $EsFields = [];

    protected $casts = [

    ];



    public function getPublishedAtAttribute($value)
    {
        return $this->inUserTimezone($value);
    }

    public function getExpiredAtAttribute($value)
    {
        return $this->inUserTimezone($value);
    }

    public function getStatusNameAttribute($value)
    {
        return settings('brand_status')[$this->attributes['status']];
    }

    public function scopeFilterName($query, $search)
    {
        return $query->where('name', 'like', "%{$search}%");
    }

    public function scopeFilterPublishedAt($query, $search)
    {
        return $query->where('published_at', 'like', "%{$search}%");
    }

    public function scopeFilterExpiredAt($query, $search)
    {
        return $query->where('expired_at', 'like', "%{$search}%");
    }

    public function scopeFilterStatus($query, $search)
    {
        return $query->where('status', 'like', "%{$search}%");
    }

    public function getReadUrlAttribute($value)
    {
        return $this->readUrl = route('brand.show', $this->id);
    }
}
