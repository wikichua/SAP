<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateActivityLogsTable extends Migration
{
    public function up()
    {
        cache()->tags(['fillable'])->flush();
        Schema::create('activity_logs', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->nullable()->index();
            $table->integer('model_id')->nullable()->index();
            $table->string('model_class')->nullable();
            $table->string('message')->index();
            $table->json('data')->nullable();
            $table->json('agents')->nullable();
            $table->string('opendns')->nullable();
            $table->json('iplocation')->nullable();
            $table->integer('brand_id')->nullable()->index();
            $table->timestamp('created_at')->index();
        });
    }

    public function down()
    {
        // drop table
        Schema::dropIfExists('activity_logs');
    }
}
