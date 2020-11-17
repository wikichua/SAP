<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRolesTable extends Migration
{
    public function up()
    {
        cache()->forget('fillable-roles');
        // create table
        Schema::create('roles', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name')->index();
            $table->boolean('admin')->default(false)->index();
            $table->timestamps();
            $table->uuid('created_by')->nullable()->default(1);
            $table->uuid('updated_by')->nullable()->default(1);
        });

        // create role user relation table
        Schema::create('role_user', function (Blueprint $table) {
            $table->increments('id');
            $table->uuid('role_id')->index();
            $table->uuid('user_id')->index();
        });
    }

    public function down()
    {
        // drop tables
        Schema::dropIfExists('roles');
        Schema::dropIfExists('role_user');
    }
}
