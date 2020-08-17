<?php

namespace Wikichua\SAP\Http\Traits;

trait ModelScopes
{
    use \Wikichua\SAP\Http\Traits\ElasticSearchable;
    public function scopeFilter($query, $filters)
    {
        parse_str($filters, $searches);
        if (count($searches)) {
            foreach ($searches as $field => $search) {
                $query->where(function ($Q) use ($search, $field) {
                    $method = camel_case('scopeFilter_' . $field);
                    $scope = camel_case('filter_' . $field);
                    if (method_exists($this, $method)) {
                        $Q->{$scope}($search);
                    }
                });
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
}
