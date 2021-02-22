<?php

namespace Wikichua\SAP\Models;

use Illuminate\Database\Eloquent\Model;

class Alert extends Model
{
    use \Wikichua\SAP\Http\Traits\AllModelTraits;

    protected $fillable = [
        'sender_id',
        'receiver_id',
        'brand_id',
        'message',
        'icon',
        'link',
        'status'
    ];

    protected $appends = [];
    public $searchableFields = [];

    public function brand()
    {
        return $this->belongsTo(app(config('sap.models.brand')), 'brand_id', 'id');
    }

    public function sender()
    {
        return $this->belongsTo(app(config('sap.models.user')), 'sender_id', 'id');
    }

    public function receiver()
    {
        return $this->belongsTo(app(config('sap.models.user')), 'receiver_id', 'id');
    }
}
