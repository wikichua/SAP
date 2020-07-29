<?php

namespace Wikichua\SAP\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use \Wikichua\SAP\Http\Traits\ModelScopes;
    use \Wikichua\SAP\Http\Traits\DynamicFillable;
    use \Wikichua\SAP\Http\Traits\UserTimezone;

    protected $appends = ['isMultiple', 'rows'];

    public function getValueAttribute($value)
    {
        if (json_decode($value)) {
            return json_decode($value, 1);
        }
        return $value;
    }
    public function setValueAttribute($value)
    {
        if (is_array($value)) {
            $this->attributes['value'] = json_encode($value);
        } else {
            $this->attributes['value'] = $value;
        }
    }
    public function getIsMultipleAttribute()
    {
        if (json_decode($this->attributes['value'])) {
            return true;
        }
        return false;
    }
    public function getRowsAttribute()
    {
        if (json_decode($this->attributes['value'])) {
            $array = json_decode($this->attributes['value'], true);
            $rows = [];
            foreach ($array as $key => $value) {
                $rows[] = [
                    'index' => $key,
                    'value' => $value,
                ];
            }
            return $rows;
        }
        return [
            'index' => null,
            'value' => null,
        ];;
    }
    public function getAllSettings()
    {
        $sets = [];
        $settings = app(config('vam.models.setting'))->query()->pluck('value', 'key');
        foreach ($settings as $key => $value) {
            if (is_array($value)) {
                foreach ($value as $k => $v) {
                    $sets[$key][] = [
                        'value' => $k,
                        'text' => $v,
                    ];
                }
            } else {
                $sets[$key] = $value;
            }
        }
        return $sets;
    }
}
