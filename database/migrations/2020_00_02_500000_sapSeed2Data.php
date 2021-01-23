<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class SapSeed2Data extends Migration
{
    public function up()
    {
        $admin_id = 1;
        app(config('sap.models.permission'))->createGroup('Wiki Docs', ['Read Wiki Docs'], $admin_id);
    }
    public function down()
    {
        app(config('sap.models.permission'))->whereIn('group', ['Wiki Docs'])->delete();
    }
}
