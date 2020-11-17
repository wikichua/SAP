<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class SapComponentTable extends Migration
{
    public function up()
    {
        cache()->forget('fillable-components');
        Schema::create('components', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name')->nullable()->default('');
            $table->uuid('brand_id')->nullable()->default(0);
            $table->uuid('created_by')->nullable()->default(0);
            $table->uuid('updated_by')->nullable()->default(0);
            $table->timestamps();
            $table->softDeletes();
        });
    }
    public function down()
    {
        Schema::dropIfExists('components');
    }
}
