<?php

namespace Wikichua\SAP\Http\Traits;

use Wikichua\SAP\Http\Observers\ElasticSearchObserver;

trait ElasticSearchable
{
    public static function bootElasticSearchable()
    {
        if (config('sap.elasticsearch.enabled', false)) {
            static::observe(ElasticSearchObserver::class);
        }
    }

    public function getEsFields()
    {
        return isset($this->EsFields)? $this->EsFields:null;
    }

    public function getSearchIndex()
    {
        return $this->getTable();
    }

    public function getSearchType()
    {
        if (property_exists($this, 'useSearchType')) {
            return $this->useSearchType;
        }

        return $this->getTable();
    }

    public function toSearchArray()
    {
        return $this->toArray();
    }

    public function getEsFieldAttribute()
    {
        return isset($this->EsFields[0])? $this->EsFields[0]:'name';
    }
}
