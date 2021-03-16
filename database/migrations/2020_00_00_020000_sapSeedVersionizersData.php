<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class SapSeedVersionizersData extends Migration
{
    public function up()
    {
        app(config('sap.models.permission'))->createGroup('Versionizers', ['Read Versionizers', 'Revert Versionizers', 'Delete Versionizers'], 1);
    }
    public function down()
    {
        app(config('sap.models.permission'))->whereIn('group', [
                'Versionizers',
            ])->delete();
    }
}
