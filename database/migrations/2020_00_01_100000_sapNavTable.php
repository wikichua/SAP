<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class SapNavTable extends Migration
{
    public function up()
    {
        cache()->tags(['fillable'])->flush();
        Schema::create('navs', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('brand_id')->nullable()->default(0);
            $table->string('name')->nullable()->default('');
            $table->string('locale', 2)->nullable()->default('en');
            $table->string('group_slug')->nullable()->default('');
            $table->string('icon')->nullable()->default('');
            $table->string('route_slug')->nullable()->default('');
            $table->json('route_params')->nullable()->default('');
            $table->integer('seq')->default(1);
            $table->string('status', 1)->nullable()->default('A');
            $table->integer('created_by')->nullable()->default(0);
            $table->integer('updated_by')->nullable()->default(0);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('navs');
    }
}
