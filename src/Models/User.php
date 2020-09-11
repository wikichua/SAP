<?php

namespace Wikichua\SAP\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Carbon;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
// use Illuminate\Notifications\Notifiable;

abstract class User extends Authenticatable
{
    use \Wikichua\SAP\Http\Traits\AdminUser;
    use \Wikichua\SAP\Http\Traits\AllModelTraits;
    use \Laravel\Sanctum\HasApiTokens;
    use \Lab404\Impersonate\Models\Impersonate;

    protected $appends = ['roles_string','readUrl'];
    // protected $fillable = [];
    public $searchableFields = ['name','email'];

    public function getRolesStringAttribute()
    {
        return $this->roles->sortBy('name')->implode('name', ', ');
    }

    public function scopeFilterType($query, $search)
    {
        return $query->where('type', $search);
    }

    public function scopeFilterName($query, $search)
    {
        return $query->where('name', 'like', "%{$search}%");
    }

    public function scopeFilterEmail($query, $search)
    {
        return $query->where('email', 'like', "%{$search}%");
    }

    public function scopeFilterCreatedAt($query, $search)
    {
        if (\Str::contains($search, ' - ')) { // date range
            $search = explode(' - ', $search);
            $start_at = Carbon::parse($search[0])->format('Y-m-d 00:00:00');
            $stop_at = Carbon::parse($search[1])->addDay()->format('Y-m-d 00:00:00');
        } else { // single date
            $start_at = Carbon::parse($search)->format('Y-m-d 00:00:00');
            $stop_at = Carbon::parse($search)->addDay()->format('Y-m-d 00:00:00');
        }

        return $query->whereBetween('created_at', [
            $this->inUserTimezone($start_at),
            $this->inUserTimezone($stop_at)
        ]);
    }

    public function getReadUrlAttribute($value)
    {
        return $this->readUrl = route('user.show', $this->id);
    }
}
