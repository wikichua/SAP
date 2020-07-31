<?php

namespace Wikichua\SAP\Models;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use \Wikichua\SAP\Http\Traits\ModelScopes;
    use \Wikichua\SAP\Http\Traits\DynamicFillable;
    use \Wikichua\SAP\Http\Traits\UserTimezone;

    protected $appends = ['isAdmin'];

    // permissions relationship
    public function permissions()
    {
        return $this->belongsToMany(config('sap.models.permission'));
    }

    public function creator()
    {
        return $this->belongsTo('App\User', 'created_by', 'id');
    }

    public function modifier()
    {
        return $this->belongsTo('App\User', 'updated_by', 'id');
    }

    public function getIsAdminAttribute($value)
    {
    	return $this->admin? 'Yes':'No';
    }

    public function scopeFilterName($query, $search)
    {
        return $query->where('name', 'like', "%{$search}%");
    }

    public function scopeFilterAdmin($query, $search)
    {
        return $query->where('admin', $search);
    }
}
