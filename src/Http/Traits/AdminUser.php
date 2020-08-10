<?php

namespace Wikichua\SAP\Http\Traits;

use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Support\Facades\Cache;
use Wikichua\SAP\Notifications\ResetAdminPassword;

trait AdminUser
{
    // roles relationship
    public function roles()
    {
        return $this->belongsToMany(config('sap.models.role'));
    }

    // permissions relationship
    public function permissions()
    {
        return $this->belongsToMany(config('sap.models.permission'));
    }

    // activity logs relationship
    public function activity_logs()
    {
        return $this->hasMany(config('sap.models.activity_log'));
    }

    // combined user + role permissions
    public function flatPermissions()
    {
        return Cache::remember('permissions.'.auth()->id(), (60*60*24), function () {
            return $this->permissions->merge($this->roles->flatMap(function ($role) {
                return $role->permissions;
            }));
        });
    }

    // check if user has permission
    public function hasPermission($name)
    {
        $isAdmin = Cache::remember('isAdmin.'.auth()->id(), (60*60*24), function () {
            $this->roles->contains('admin', true);
        });
        return $isAdmin || $this->flatPermissions()->contains('name', $name);
    }

    // use admin url in password reset email link
    public function sendPasswordResetNotification($token)
    {
        if (request()->route()->getName('admin.password.email')) {
            $this->notify(new ResetAdminPassword($token));
        } else {
            $this->notify(new ResetPassword($token));
        }
    }

    public function allPermissions()
    {
        $permissions = [];
        foreach (app(config('sap.models.permission'))->all() as $permission) {
            if (auth()->user()->can($permission->name)) {
                $permissions[] = $permission->name;
            }
        }
        return $permissions;
    }
}
