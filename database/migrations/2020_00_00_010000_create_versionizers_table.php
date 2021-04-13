<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVersionizersTable extends Migration
{
    public function up()
    {
        cache()->tags(['fillable'])->flush();
        Schema::create('versionizers', function (Blueprint $table) {
            $table->increments('id');
            $table->string('mode');
            $table->string('model');
            $table->integer('model_id');
            $table->json('data');
            $table->json('changes');
            $table->integer('brand_id')->default(0);
            $table->date('reverted_at')->nullable();
            $table->integer('reverted_by')->nullable();
            $table->timestamps();

            $table->index(['model']);
            $table->index(['brand_id']);
            $table->index(['model', 'model_id']);
            $table->index(['model', 'model_id', 'brand_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('versionizers');
    }
}
