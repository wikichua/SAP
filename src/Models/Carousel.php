<?php

namespace Wikichua\SAP\Models;

use Illuminate\Database\Eloquent\Model as Eloquent;
use Illuminate\Support\Carbon;

class Carousel extends Eloquent
{
    use \Illuminate\Database\Eloquent\SoftDeletes;
    use \Wikichua\SAP\Http\Traits\AllModelTraits;

    protected $activity_logged = true;

    protected $dates = ['deleted_at'];
    protected $fillable = [
        'created_by',
        'updated_by',
        'slug',
        'brand_id',
        'image_url',
        'caption',
        'seq',
        'tags',
        'published_at',
        'expired_at',
        'status'
    ];


    protected $appends = [
        'status_name',
        'readUrl'
    ];

    protected $searchableFields = [
        'slug'
    ];

    protected $casts = [
        'tags' => 'array'
    ];

    public function brand()
    {
        return $this->belongsTo(app(config('sap.models.brand')), 'brand_id', 'id');
    }

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
        return isset($this->attributes['status'])? settings('carousel_status')[$this->attributes['status']]:'';
    }

    public function scopeFilterSlug($query, $search)
    {
        return $query->where('slug', 'like', "%{$search}%");
    }

    public function scopeFilterTags($query, $search)
    {
        return $query->whereIn('tags', $search);
    }

    public function scopeFilterPublishedAt($query, $search)
    {
        $date = $this->getDateFilter($search);
        return $query->whereBetween('published_at', [ $this->inUserTimezone($date['start_at']), $this->inUserTimezone($date['stop_at'])]);
    }

    public function scopeFilterExpiredAt($query, $search)
    {
        $date = $this->getDateFilter($search);
        return $query->whereBetween('expired_at', [ $this->inUserTimezone($date['start_at']), $this->inUserTimezone($date['stop_at'])]);
    }

    public function scopeFilterStatus($query, $search)
    {
        return $query->whereIn('status', $search);
    }

    public function getReadUrlAttribute($value)
    {
        return $this->readUrl = route('carousel.show', $this->id);
    }
}
