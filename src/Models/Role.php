<?php

namespace Wikichua\SAP\Models;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use \Wikichua\SAP\Http\Traits\AllModelTraits;
    public $searchableFields = ['name'];

    protected $appends = ['isAdmin', 'readUrl'];
    protected $menu_icon = 'fas fa-id-badge';

    protected $activity_logged = true;
    protected $snapshot = true;

    public function permissions()
    {
        return $this->belongsToMany(config('sap.models.permission'));
    }

    public function users()
    {
        return $this->belongsToMany(config('sap.models.user'));
    }

    public function getIsAdminAttribute($value)
    {
        return $this->admin ? 'Yes' : 'No';
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

    public function onCachedEvent()
    {
        cache()->tags('permissions')->flush();
        // $user_ids = \DB::table('role_user')->distinct('user_id')->where('role_id', $this->id)->pluck('user_id');
        // foreach ($user_ids as $user_id) {
        //     cache()->forget('permissions:'.$user_id);
        // }
    }
}
