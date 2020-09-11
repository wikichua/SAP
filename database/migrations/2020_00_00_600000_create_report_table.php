<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReportTable extends Migration
{
    public function up()
    {
        cache()->forget('fillable-reports');
        Schema::create('reports', function (Blueprint $table) {
            $table->increments('id', true);
            $table->string('name')->nullable()->default('')->unique();
            $table->json('queries')->nullable()->default('');
            $table->string('status', 1)->nullable()->default('');
            $table->integer('created_by')->nullable()->default(0);
            $table->integer('updated_by')->nullable()->default(0);
            $table->timestamps();
            $table->softDeletes();
        });
        app(config('sap.models.setting'))->create(['key' => 'report_status','value' => ['A' => 'Active','I' => 'Inactive']]);
        app(config('sap.models.permission'))->createGroup('Reports', ['Create Reports', 'Read Reports', 'Update Reports', 'Delete Reports']);
    }
    public function down()
    {
        app(config('sap.models.setting'))->where('key', 'report_status')->forceDelete();
        app(config('sap.models.permission'))->where('group', 'Reports')->delete();
        Schema::dropIfExists('reports');
    }
}
