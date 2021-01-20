<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSettingsTable extends Migration
{
    public function up()
    {
        cache()->tags(['fillable'])->flush();
        Schema::create('settings', function (Blueprint $table) {
            $table->increments('id');
            $table->string('key')->index();
            $table->longText('value')->nullable();
            $table->boolean('protected')->default(false);
            $table->timestamps();
            $table->integer('created_by')->nullable()->default(1);
            $table->integer('updated_by')->nullable()->default(1);
        });
    }

    public function down()
    {
        // drop table
        Schema::dropIfExists('settings');
    }
}
