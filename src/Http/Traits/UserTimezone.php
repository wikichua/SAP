<?php

namespace Wikichua\SAP\Http\Traits;

use Carbon\Carbon;

trait UserTimezone
{
    public function getCreatedAtAttribute($value)
    {
        return $this->inUserTimezone($value);
    }

    public function getUpdatedAtAttribute($value)
    {
        return $this->inUserTimezone($value);
    }

    public function getDeletedAtAttribute($value)
    {
        return $this->inUserTimezone($value);
    }

    public function getFailedAtAttribute($value)
    {
        return $this->inUserTimezone($value);
    }

    // convert date to user timezone
    public function inUserTimezone($value)
    {
        if (auth()->check() && $value) {
            $carbon = Carbon::parse($value);
            $carbon->tz(auth()->user()->timezone);

            return $carbon->toDateTimeString();
        } else {
            return $value;
        }
    }

    public function creator()
    {
        return $this->belongsTo(config('sap.models.user'), 'created_by', 'id');
    }

    public function modifier()
    {
        return $this->belongsTo(config('sap.models.user'), 'updated_by', 'id');
    }
}
