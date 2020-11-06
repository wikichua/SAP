<?php

namespace Wikichua\SAP\Http\Traits;

trait Searchable
{
    protected function createSearchable()
    {
        if (count($this->toSearchableArray())) {
            $searchable = app(config('sap.models.searchable'))->create([
                'model' => $this->searchableAs(),
                'model_id' => $this->id,
                'tags' => $this->toSearchableArray(),
                'brand_id' => $this->brand_id ?? 0,
            ]);
        }
    }

    protected function updateSearchable()
    {
        if (count($this->toSearchableArray())) {
            $searchable = app(config('sap.models.searchable'))
                ->where('model', $this->searchableAs())
                ->where('model_id', $this->id);

            if ($this->brand_id) {
                $searchable->where('brand_id', $this->brand_id);
            }

            $searchable = $searchable->update([
                'model' => $this->searchableAs(),
                'model_id' => $this->id,
                'tags' => $this->toSearchableArray(),
                'brand_id' => $this->brand_id ?? 0,
            ]);
        }
    }

    protected function deleteSearchable()
    {
        if (count($this->toSearchableArray())) {
            $searchable = app(config('sap.models.searchable'))
                ->where('model', $this->searchableAs())
                ->where('model_id', $this->id);

            if ($this->brand_id) {
                $searchable->where('brand_id', $this->brand_id);
            }

            $searchable->delete();
        }
    }

    public function searchableAs()
    {
        return get_class($this);
    }
    public function toSearchableArray()
    {
        $array = [];
        if (isset($this->searchableFields)) {
            foreach ($this->searchableFields as $field) {
                $array[$field] = $this->attributes[$field] ?? $this->{$field};
            }
        } else {
            $array = $this->toArray();
        }
        return $array;
    }
}
