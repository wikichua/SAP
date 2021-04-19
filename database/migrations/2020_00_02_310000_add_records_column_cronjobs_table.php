<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddRecordsColumnCronjobsTable extends Migration
{
    public function up()
    {
        cache()->tags(['fillable', 'cronjobs'])->flush();
        Schema::table('cronjobs', function (Blueprint $table) {
            $table->json('output')->nullable();
        });
    }

    public function down()
    {
        cache()->tags(['fillable', 'cronjobs'])->flush();
        Schema::table('cronjobs', function (Blueprint $table) {
            $table->dropColumn('output');
        });
    }
}
