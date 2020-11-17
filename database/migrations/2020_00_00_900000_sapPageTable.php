<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class SapPageTable extends Migration
{
    public function up()
    {
        cache()->forget('fillable-pages');
        Schema::create('pages', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('brand_id')->nullable()->default(0);
            $table->string('locale', 2)->nullable()->default('en');
            $table->string('name')->nullable()->default('');
            $table->string('template')->nullable()->default('layouts.main');
            $table->text('slug')->nullable()->default('');
            $table->longText('blade')->nullable()->default('');
            $table->json('styles')->nullable()->default('');
            $table->json('scripts')->nullable()->default('');
            $table->date('published_at')->nullable();
            $table->date('expired_at')->nullable();
            $table->string('status', 1)->nullable()->default('');
            $table->integer('created_by')->nullable()->default(0);
            $table->integer('updated_by')->nullable()->default(0);
            $table->timestamps();
            $table->softDeletes();
        });
    }
    public function down()
    {
        Schema::dropIfExists('pages');
    }
}
