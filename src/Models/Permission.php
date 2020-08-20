<?php

namespace Wikichua\SAP\Models;

use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    use \Wikichua\SAP\Http\Traits\ModelScopes;
    use \Wikichua\SAP\Http\Traits\DynamicFillable;
    use \Wikichua\SAP\Http\Traits\UserTimezone;

    protected $appends = ['readUrl'];
    public $searchableFields = ['name'];

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

    public function scopeFilterName($query, $search)
    {
        return $query->where('name', 'like', "%{$search}%");
    }

    public function scopeFilterGroup($query, $search)
    {
        return $query->where('group', 'like', "%{$search}%");
    }

    public function getReadUrlAttribute($value)
    {
        return $this->readUrl = route('permission.show', $this->id);
    }
}
