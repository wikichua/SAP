<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class SapCarouselTable extends Migration
{
    public function up()
    {
        cache()->forget('fillable-carousels');
        Schema::create('carousels', function (Blueprint $table) {
            $table->increments('id');
            $table->string('slug')->nullable()->default('');
            $table->integer('brand_id')->nullable()->default(0);
            $table->text('image_url')->nullable();
            $table->text('caption')->nullable();
            $table->integer('seq')->nullable()->default(1);
            $table->json('tags')->nullable();
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
        Schema::dropIfExists('carousels');
    }
}
