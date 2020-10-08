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
            $table->increments('id', true);
            $table->string('slug')->nullable()->default('');
            $table->string('brand_id', 1)->nullable()->default('');
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

        app(config('sap.models.setting'))->create(['key' => 'carousel_tags','value' => ['new' => 'New','hot' => 'Hot','recommended' => 'Recommended']]);
        app(config('sap.models.setting'))->create(['key' => 'carousel_status','value' => ['A' => 'Active','I' => 'Inactive']]);
        app(config('sap.models.permission'))->createGroup('Carousels', ['Create Carousels', 'Read Carousels', 'Update Carousels', 'Delete Carousels']);
    }
    public function down()
    {
        app(config('sap.models.setting'))->where('key','carousel_tags')->forceDelete();
        app(config('sap.models.setting'))->where('key','carousel_status')->forceDelete();
        app(config('sap.models.permission'))->where('group', 'Carousels')->delete();
        Schema::dropIfExists('carousels');
    }
}
