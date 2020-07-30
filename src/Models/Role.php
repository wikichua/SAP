<?php

namespace Wikichua\SAP\Models;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use \Wikichua\SAP\Http\Traits\ModelScopes;
    use \Wikichua\SAP\Http\Traits\DynamicFillable;
    use \Wikichua\SAP\Http\Traits\UserTimezone;

    // permissions relationship
    public function permissions()
    {
        return $this->belongsToMany(config('sap.models.permission'));
    }
}
