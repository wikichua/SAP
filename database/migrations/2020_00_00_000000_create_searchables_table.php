<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSearchablesTable extends Migration
{
    public function up()
    {
        Schema::create('searchables', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('model');
            $table->uuid('model_id');
            $table->json('tags');
            $table->uuid('brand_id')->default(0);
            $table->timestamps();

            $table->index(['model']);
            $table->index(['brand_id']);
            $table->index(['model','model_id']);
            $table->index(['model','model_id','brand_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('searchables');
    }
}
