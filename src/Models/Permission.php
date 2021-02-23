<?php

namespace Wikichua\SAP\Models;

use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    use \Wikichua\SAP\Http\Traits\AllModelTraits;

    protected $menu_icon = 'fas fa-lock';
    protected $activity_logged = true;

    protected $appends = ['readUrl'];
    public $searchableFields = ['name','group'];

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
    public function createGroup($group, $names = [], $user_id = '1')
    {
        foreach ($names as $name) {
            $this->create([
                'group' => $group,
                'name' => $name,
                'created_by' => auth()->check()? auth()->id():$user_id,
                'updated_by' => auth()->check()? auth()->id():$user_id,
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

    public function onCachedEvent()
    {
        cache()->tags('permissions')->flush();
        // $role_ids = $this->roles()->pluck('role_id');
        // $user_ids = \DB::table('role_user')->distinct('user_id')->whereIn('role_id', $role_ids)->pluck('user_id');
        // foreach ($user_ids as $user_id) {
        //     cache()->forget('permissions:'.$user_id);
        // }
    }
}
