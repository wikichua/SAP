<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

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
