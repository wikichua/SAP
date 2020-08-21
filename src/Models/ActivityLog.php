<?php

namespace Wikichua\SAP\Models;

use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model
{
    use \Wikichua\SAP\Http\Traits\AllModelTraits;

    const UPDATED_AT = null;

    protected $casts = [
        'data' => 'array',
    ];
    public $searchableFields = [];

    // user relationship
    public function user()
    {
        return $this->belongsTo(config('sap.models.user'))->withDefault(['name' => null]);
    }

    // dynamic model
    public function model()
    {
        return $this->model_class ? app($this->model_class)->find($this->model_id) : null;
    }
}
