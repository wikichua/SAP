<?php

namespace Wikichua\SAP\Models;

use Illuminate\Database\Eloquent\Model;

class FailedJob extends Model
{
    use \Wikichua\SAP\Http\Traits\AllModelTraits;
    protected $menu_icon = 'fas fa-recycle';

    protected $dates = ['failed_at'];

    public function scopeFilterQueue($query, $search)
    {
        return $query->where('queue', 'like', "%{$search}%");
    }

    public function scopeFilterException($query, $search)
    {
        return $query->where('exception', 'like', "%{$search}%");
    }

    public function scopeFilterFailedAt($query, $search)
    {
        if (\Str::contains($search, ' - ')) { // date range
            $search = explode(' - ', $search);
            $start_at = Carbon::parse($search[0])->format('Y-m-d 00:00:00');
            $stop_at = Carbon::parse($search[1])->addDay()->format('Y-m-d 00:00:00');
        } else { // single date
            $start_at = Carbon::parse($search)->format('Y-m-d 00:00:00');
            $stop_at = Carbon::parse($search)->addDay()->format('Y-m-d 00:00:00');
        }

        return $query->whereBetween('failed_at', [
            $this->inUserTimezone($start_at),
            $this->inUserTimezone($stop_at),
        ]);
    }
}
