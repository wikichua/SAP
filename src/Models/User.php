<?php

namespace Wikichua\SAP\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Carbon;

class User extends Authenticatable
{
    use Notifiable;

    use \Wikichua\SAP\Http\Traits\AdminUser;
    use \Wikichua\SAP\Http\Traits\ModelScopes;
    use \Wikichua\SAP\Http\Traits\DynamicFillable;
    use \Wikichua\SAP\Http\Traits\UserTimezone;

    protected $appends = ['roles_string'];
    protected $fillable = [];

    public function creator()
    {
        return $this->belongsTo('App\User', 'created_by', 'id');
    }

    public function modifier()
    {
        return $this->belongsTo('App\User', 'updated_by', 'id');
    }

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
}
