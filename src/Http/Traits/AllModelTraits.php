<?php

namespace Wikichua\SAP\Http\Traits;

trait AllModelTraits
{
    use \Laravel\Scout\Searchable;
    use \Wikichua\SAP\Http\Traits\ModelScopes;
    use \Wikichua\SAP\Http\Traits\DynamicFillable;
    use \Wikichua\SAP\Http\Traits\UserTimezone;

    public function searchableAs()
    {
        return $this->getTable().'_index';
    }
    public function toSearchableArray()
    {
        $array = [];
        if (isset($this->searchableFields)) {
            foreach ($this->searchableFields as $field) {
                $array[$field] = $this->{$field};
            }
        } else {
            $array = $this->toArray();
        }
        return $array;
    }
}
