<?php

namespace Wikichua\SAP\Models;

use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model
{
    use \Wikichua\SAP\Http\Traits\AllModelTraits;

    const UPDATED_AT = null;
    protected $fillable = [
        'id',
        'user_id',
        'model_id',
        'model_class',
        'message',
        'data',
        'agents',
        'opendns',
        'iplocation',
        'brand_id',
        'created_at',
    ];

    protected $casts = [
        'data' => 'array',
        'agents' => 'array',
        'iplocation' => 'array',
    ];
    public $searchableFields = [];

    protected $masks = [
        'password',
        'password_confirmation',
        'token'
    ];

    // user relationship
    public function user()
    {
        return $this->belongsTo(config('sap.models.user'))->withDefault(['name' => null]);
    }

    public function brand()
    {
        return $this->belongsTo(config('sap.models.brand'))->withDefault(['name' => null]);
    }

    // dynamic model
    public function model()
    {
        return $this->model_class ? app($this->model_class)->find($this->model_id) : null;
    }

    public function getDataAttribute($data)
    {
        $data = json_decode($data, 1);
        $masks = config('sap.activity_log.masks', $this->masks);
        foreach ($masks as $key) {
            if (isset($data[$key])) {
                $data[$key] = '***censored***';
            }
        }
        return $data;
    }
}
