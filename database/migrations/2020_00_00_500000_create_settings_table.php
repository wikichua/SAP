<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSettingsTable extends Migration
{
    public function up()
    {
        cache()->forget('fillable-settings');
        // create table
        Schema::create('settings', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('key')->index();
            $table->longText('value')->nullable();
            $table->boolean('protected')->default(false);
            $table->timestamps();
            $table->uuid('created_by')->nullable()->default(1);
            $table->uuid('updated_by')->nullable()->default(1);
        });
    }

    public function down()
    {
        // drop table
        Schema::dropIfExists('settings');
    }
}
