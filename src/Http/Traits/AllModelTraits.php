<?php

namespace Wikichua\SAP\Http\Traits;

trait AllModelTraits
{
    use \Wikichua\SAP\Http\Traits\ModelScopes;
    use \Wikichua\SAP\Http\Traits\Searchable;
    use \Wikichua\SAP\Http\Traits\DynamicFillable;
    use \Wikichua\SAP\Http\Traits\UserTimezone;

    protected static function booted()
    {
        static::created(function ($model) {
            if (!str_contains(get_class($model), 'Searchable')) {
                $model->createSearchable();
            }
        });
        static::updated(function ($model) {
            if (!str_contains(get_class($model), 'Searchable')) {
                $model->updateSearchable();
            }
        });
        static::deleted(function ($model) {
            if (!str_contains(get_class($model), 'Searchable')) {
                $model->deleteSearchable();
            }
        });
    }
}
