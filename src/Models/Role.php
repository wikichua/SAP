<?php

namespace Wikichua\SAP\Models;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use \Wikichua\SAP\Http\Traits\AllModelTraits;

    protected $appends = ['isAdmin','readUrl'];
    public $searchableFields = ['name'];

    public function permissions()
    {
        return $this->belongsToMany(config('sap.models.permission'));
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

    public function getReadUrlAttribute($value)
    {
        return $this->readUrl = route('role.show', $this->id);
    }
}
