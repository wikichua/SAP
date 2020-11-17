<?php

namespace Wikichua\SAP\Http\Traits;

trait AllModelTraits
{
    use \Wikichua\SAP\Http\Traits\ModelScopes;
    use \Wikichua\SAP\Http\Traits\Searchable;
    use \Wikichua\SAP\Http\Traits\DynamicFillable;
    use \Wikichua\SAP\Http\Traits\UserTimezone;

    protected static $opendns;

    protected static function booted()
    {
        static::$opendns = opendns();
        static::created(function ($model) {
            $model->createSearchable();
            $model->logActivity('Created');
        });
        static::updated(function ($model) {
            $model->updateSearchable();
            $model->logActivity('Updated');
        });
        static::deleted(function ($model) {
            $model->deleteSearchable();
            $model->logActivity('Deleted');
        });
    }

    protected function logActivity($mode = 'Created')
    {
        if (!\Str::contains(get_class($this), ['Searchable','ActivityLog']) && isset($this->activity_logged) && $this->activity_logged) {
            $name = basename(str_replace('\\', '/', get_class($this)));
            if (isset($this->activity_name)) {
                $name = $this->activity_name;
            }
            activity($mode .' '. $name . ': ' . $this->id, $this->attributes, $this, static::$opendns);
        }
    }
}
