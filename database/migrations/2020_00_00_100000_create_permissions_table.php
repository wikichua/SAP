<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePermissionsTable extends Migration
{
    public function up()
    {
        cache()->forget('fillable-permissions');
        // create table
        Schema::create('permissions', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('group');
            $table->string('name');
            $table->timestamps();
            $table->uuid('created_by')->nullable()->default(1);
            $table->uuid('updated_by')->nullable()->default(1);
        });

        // create permission role relation table
        Schema::create('permission_role', function (Blueprint $table) {
            $table->increments('id');
            $table->uuid('permission_id')->index();
            $table->uuid('role_id')->index();
        });

        // create permission user relation table
        Schema::create('permission_user', function (Blueprint $table) {
            $table->increments('id');
            $table->uuid('permission_id')->index();
            $table->uuid('user_id')->index();
        });
    }

    public function down()
    {
        // drop tables
        Schema::dropIfExists('permissions');
        Schema::dropIfExists('permission_role');
        Schema::dropIfExists('permission_user');
    }
}
