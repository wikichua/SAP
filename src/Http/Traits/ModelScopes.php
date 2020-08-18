<?php

namespace Wikichua\SAP\Http\Traits;

use Illuminate\Support\Carbon;

trait ModelScopes
{
    use \Wikichua\SAP\Http\Traits\ElasticSearchable;
    public function scopeFilter($query, $filters)
    {
        parse_str($filters, $searches);
        if (count($searches)) {
            foreach ($searches as $field => $search) {
                if ((is_array($search) && count($search)) || $search != '') {
                    $query->where(function ($Q) use ($search, $field) {
                        $method = camel_case('scopeFilter_' . $field);
                        $scope = camel_case('filter_' . $field);
                        if (method_exists($this, $method)) {
                            $Q->{$scope}($search);
                        }
                    });
                }
            }
        }
        return $query;
    }

    public function scopeSorting($query, $sortBy, $sortDesc)
    {
        if ($sortBy != '') {
            $query->when($sortBy, function ($q) use ($sortDesc, $sortBy) {
                return $q->orderBy($sortBy, $sortDesc);
            });
        }
        return $query;
    }

    public function getDateFilter($search)
    {
        if (\Str::contains($search, ' - ')) { // date range
            $search = explode(' - ', $search);
            $start_at = Carbon::parse($search[0])->format('Y-m-d 00:00:00');
            $stop_at = Carbon::parse($search[1])->addDay()->format('Y-m-d 00:00:00');
        } else { // single date
            $start_at = Carbon::parse($search)->format('Y-m-d 00:00:00');
            $stop_at = Carbon::parse($search)->addDay()->format('Y-m-d 00:00:00');
        }
        return compact('start_at', 'stop_at');
    }
}
