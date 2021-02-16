<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddBladeFileFieldIntoPageTable extends Migration
{
    public function up()
    {
        cache()->tags(['fillable'])->flush();
        Schema::table('pages', function (Blueprint $table) {
            $table->string('blade_file')->nullable()->default('page');
        });
    }

    public function down()
    {
        cache()->tags(['fillable'])->flush();
        Schema::table('pages', function (Blueprint $table) {
            $table->dropColumn('blade_file');
        });
    }
}
