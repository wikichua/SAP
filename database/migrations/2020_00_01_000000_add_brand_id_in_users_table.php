<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddBrandIdInUsersTable extends Migration
{
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            if (config('database.default') != 'sqlite') {
                $table->dropColumn('id');
            }
        });
        Schema::table('users', function (Blueprint $table) {
            if (config('database.default') != 'sqlite') {
                $table->increments('id')->before('name');
            }
            $table->integer('brand_id')->nullable()->default(null);
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('brand_id');
        });
    }
}
