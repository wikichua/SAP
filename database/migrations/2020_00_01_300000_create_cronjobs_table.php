<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCronjobsTable extends Migration
{
    public function up()
    {
        cache()->tags(['fillable','cronjobs'])->flush();
        Schema::create('cronjobs', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->nullable()->default('');
            $table->string('mode',4)->nullable()->default('art'); // [art/exec]
            $table->string('timezone')->nullable()->default('UTC');
            $table->string('command')->nullable();
            $table->string('frequency')->nullable()->default('everyMinute');
            $table->string('status', 1)->nullable()->default('I');
            $table->integer('created_by')->nullable()->default(1);
            $table->integer('updated_by')->nullable()->default(1);
            $table->timestamps();
            $table->softDeletes();
        });
        app(config('sap.models.cronjob'))->query()->create([
            'name' => 'Inspire',
            'command' => 'inspire',
            'status' => 'A',
        ]);
    }
    public function down()
    {
        Schema::dropIfExists('cronjobs');
    }
}
