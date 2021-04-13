<?php

namespace Wikichua\SAP\Models;

use Illuminate\Database\Eloquent\Model;

class Page extends Model
{
    use \Illuminate\Database\Eloquent\SoftDeletes;
    use \Wikichua\SAP\Http\Traits\AllModelTraits;

    protected $activity_logged = true;
    protected $snapshot = true;
    protected $menu_icon = 'fas fa-envelope-open-text';
    protected $dates = ['deleted_at'];
    protected $fillable = [
        'created_by',
        'updated_by',
        'brand_id',
        'locale',
        'name',
        'slug',
        'blade',
        'styles',
        'scripts',
        'published_at',
        'expired_at',
        'status',
    ];

    protected $appends = [
        'status_name',
        'readUrl',
    ];

    protected $searchableFields = ['name', 'slug'];

    protected $casts = [
        'styles' => 'array',
        'scripts' => 'array',
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
        return settings('page_status')[$this->attributes['status']];
    }

    public function scopeFilterName($query, $search)
    {
        return $query->where('name', 'like', "%{$search}%");
    }

    public function scopeFilterSlug($query, $search)
    {
        return $query->where('slug', 'like', "%{$search}%");
    }

    public function scopeFilterPublishedAt($query, $search)
    {
        $date = $this->getDateFilter($search);

        return $query->whereBetween('published_at', [
            $this->inUserTimezone($date['start_at']),
            $this->inUserTimezone($date['stop_at']),
        ]);
    }

    public function scopeFilterExpiredAt($query, $search)
    {
        $date = $this->getDateFilter($search);

        return $query->whereBetween('expired_at', [
            $this->inUserTimezone($date['start_at']),
            $this->inUserTimezone($date['stop_at']),
        ]);
    }

    public function scopeFilterStatus($query, $search)
    {
        return $query->whereIn('status', $search);
    }

    public function getReadUrlAttribute($value)
    {
        return $this->readUrl = route('page.show', $this->id);
    }

    public function brand()
    {
        return $this->belongsTo(config('sap.models.brand'), 'brand_id', 'id');
    }

    public function onCachedEvent()
    {
        cache()->tags(['page'])->flush();
    }
}
