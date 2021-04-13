<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class SapSeed2Data extends Migration
{
    public function up()
    {
        $admin_id = 1;
        app(config('sap.models.permission'))->createGroup('Wiki Docs', ['Read Wiki Docs'], $admin_id);
        app(config('sap.models.permission'))->createGroup('SEO Manager', ['Manage SEO'], $admin_id);
    }
    public function down()
    {
        app(config('sap.models.permission'))->whereIn('group', ['Wiki Docs', 'SEO Manager'])->delete();
    }
}
