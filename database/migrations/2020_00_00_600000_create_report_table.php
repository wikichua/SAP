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
            $table->uuid('id')->primary();
            $table->string('name')->nullable()->default('')->unique();
            $table->json('queries')->nullable()->default('');
            $table->string('status', 1)->nullable()->default('');
            $table->uuid('created_by')->nullable()->default(0);
            $table->uuid('updated_by')->nullable()->default(0);
            $table->integer('cache_ttl')->nullable()->default(300); // 5 mins
            $table->datetime('generated_at')->nullable();
            $table->datetime('next_generate_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }
    public function down()
    {
        Schema::dropIfExists('reports');
    }
}
