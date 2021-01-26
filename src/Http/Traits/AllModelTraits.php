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
        static::saved(function ($model) {
            $onWhichEvent = $model->wasRecentlyCreated? 'onCreatedEvent':'onUpdatedEvent';
            $logWhichActivity = $model->wasRecentlyCreated? 'Created':'Updated';
            $model->executeEvents([$onWhichEvent,'onCachedEvent','updateSearchable']);
            $model->logActivity($logWhichActivity);
        });
        static::deleted(function ($model) {
            $model->executeEvents(['onDeletedEvent','onCachedEvent','deleteSearchable']);
            $model->logActivity('Deleted');
        });
    }

    protected function executeEvents(array $methods)
    {
        foreach ($methods as $method) {
            if (method_exists($this, $method)) {
                call_user_func_array([$this,$method], [$this]);
            }
        }
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

    public function setReadUrlAttribute($value)
    {
        return ;
    }
}
