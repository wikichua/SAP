<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class SapComponentTable extends Migration
{
    public function up()
    {
        Schema::create('components', function (Blueprint $table) {
            $table->increments('id', true);
            $table->string('name')->nullable()->default('');
            $table->integer('created_by')->nullable()->default(0);
            $table->integer('updated_by')->nullable()->default(0);
            $table->timestamps();
            $table->softDeletes();
        });

        app(config('sap.models.permission'))->createGroup('Components', ['Read Components']);
    }
    public function down()
    {
        app(config('sap.models.permission'))->where('group', 'Components')->delete();
        Schema::dropIfExists('brands');
    }
}
