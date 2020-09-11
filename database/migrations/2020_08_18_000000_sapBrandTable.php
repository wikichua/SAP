<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class SapBrandTable extends Migration
{
    public function up()
    {
        cache()->forget('fillable-brands');
        Schema::create('brands', function (Blueprint $table) {
            $table->increments('id', true);
            $table->string('name')->nullable()->default('');
            $table->string('domain')->nullable()->default('');
            $table->date('published_at')->nullable();
            $table->date('expired_at')->nullable();
            $table->string('status', 1)->nullable()->default('');
            $table->integer('created_by')->nullable()->default(0);
            $table->integer('updated_by')->nullable()->default(0);
            $table->timestamps();
            $table->softDeletes();
        });

        app(config('sap.models.setting'))->create(['key' => 'brand_status','value' => ['A' => 'Published','P' => 'Pending','E' => 'Expired']]);
        app(config('sap.models.permission'))->createGroup('Brands', ['Read Brands', 'Update Brands']);
    }
    public function down()
    {
        app(config('sap.models.setting'))->where('key', 'brand_status')->forceDelete();
        app(config('sap.models.permission'))->where('group', 'Brands')->delete();
        Schema::dropIfExists('brands');
    }
}
