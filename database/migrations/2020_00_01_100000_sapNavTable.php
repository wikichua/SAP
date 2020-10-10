<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class SapNavTable extends Migration
{
    public function up()
    {
        Schema::create('navs', function (Blueprint $table) {
            $table->increments('id', true);
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
        app(config('sap.models.setting'))->create(['key' => 'nav_status','value' => ['A' => 'Active','I' => 'Inactive']]);
        app(config('sap.models.permission'))->createGroup('Navs', ['Create Navs', 'Read Navs', 'Update Navs', 'Delete Navs']);
    }
    public function down()
    {
        app(config('sap.models.setting'))->where('key', 'nav_status')->forceDelete();
        app(config('sap.models.permission'))->where('group', 'Navs')->delete();
        Schema::dropIfExists('navs');
    }
}
