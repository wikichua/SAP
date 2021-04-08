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
        static::$opendns = trim(static::$opendns) == '' ?? opendns();
        static::saved(function ($model) {
            $onWhichEvent = $model->wasRecentlyCreated? 'onCreatedEvent':'onUpdatedEvent';
            $mode = $model->wasRecentlyCreated? 'Created':'Updated';
            $model->executeEvents([$onWhichEvent,'onCachedEvent','updateSearchable']);
            $model->logActivity($mode);
            $model->snapshotIt($mode);
        });
        static::deleted(function ($model) {
            $model->executeEvents(['onDeletedEvent','onCachedEvent','deleteSearchable']);
            $model->logActivity('Deleted');
            $model->snapshotIt('Deleted');
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
        if (!\Str::contains(get_class($this), ['Searchable','ActivityLog','Alert','Versionizer']) && isset($this->activity_logged) && $this->activity_logged) {
            $name = basename(str_replace('\\', '/', get_class($this)));
            if (isset($this->activity_name)) {
                $name = $this->activity_name;
            }
            activity($mode .' '. $name . ': ' . $this->id, $this->attributes, $this, static::$opendns);
        }
    }

    protected function snapshotIt($mode = 'Updated')
    {
        if (strtolower($mode) != 'created' && !\Str::contains(get_class($this), ['Searchable','ActivityLog','Alert','Versionizer']) && isset($this->snapshot) && $this->snapshot) {
            $changes = $this->getChanges();
            if (count($changes) || strtolower($mode) == 'deleted') {
                $data = $this->getOriginal();
                app(config('sap.models.versionizer'))->create([
                    'mode' => $mode,
                    'model' => get_class($this),
                    'model_id' => $this->id,
                    'data' => $data,
                    'changes' => $changes,
                    'brand_id' => $this->brand_id ?? 0,
                ]);
            }
        }
    }

    public function setReadUrlAttribute($value)
    {
        return ;
    }
}
