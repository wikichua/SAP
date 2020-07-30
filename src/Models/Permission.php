<?php

namespace Wikichua\SAP\Models;

use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    use \Wikichua\SAP\Http\Traits\ModelScopes;
    use \Wikichua\SAP\Http\Traits\DynamicFillable;
    use \Wikichua\SAP\Http\Traits\UserTimezone;

    // roles relationship
    public function roles()
    {
        return $this->belongsToMany(config('sap.models.role'));
    }

    // users relationship
    public function users()
    {
        return $this->belongsToMany(config('sap.models.user'));
    }

    // create permission group
    public function createGroup($group, $names = [])
    {
        foreach ($names as $name) {
            $this->create([
                'group' => $group,
                'name' => $name,
            ]);
        }
    }

    public function creator()
    {
        return $this->belongsTo('App\User', 'created_by', 'id');
    }

    public function modifier()
    {
        return $this->belongsTo('App\User', 'updated_by', 'id');
    }

    public function scopeFilterName($query, $search)
    {
        return $query->where('name', 'like', "%{$search}%");
    }

    public function scopeFilterGroup($query, $search)
    {
        return $query->where('group', 'like', "%{$search}%");
    }
}
