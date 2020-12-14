<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRolesTable extends Migration
{
    public function up()
    {
        cache()->tags(['fillable'])->flush();
        Schema::create('roles', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->index();
            $table->boolean('admin')->default(false)->index();
            $table->timestamps();
            $table->integer('created_by')->nullable()->default(1);
            $table->integer('updated_by')->nullable()->default(1);
        });

        // create role user relation table
        Schema::create('role_user', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('role_id')->index();
            $table->integer('user_id')->index();
        });
    }

    public function down()
    {
        // drop tables
        Schema::dropIfExists('roles');
        Schema::dropIfExists('role_user');
    }
}
