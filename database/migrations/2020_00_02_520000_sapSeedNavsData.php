<?php

use Illuminate\Database\Migrations\Migration;

class SapSeedNavsData extends Migration
{
    public function up()
    {
        app(config('sap.models.permission'))->createGroup('Pages', ['Migrate Pages'], 1);
        app(config('sap.models.permission'))->createGroup('Navs', ['Migrate Navs'], 1);
    }

    public function down()
    {
    }
}
