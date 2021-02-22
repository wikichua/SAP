<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAlertsTable extends Migration
{
    public function up()
    {
        Schema::create('alerts', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('brand_id')->nullable()->default(0);
            $table->string('icon')->nullable()->default('');
            $table->string('link')->nullable();
            $table->text('message');
            $table->timestamps();
            $table->integer('sender_id')->default(0); // 0 - everyone
            $table->integer('receiver_id')->default(0); // 0 - everyone
            $table->string('status', 1)->default('u'); // r - read / u - unread
        });
    }

    public function down()
    {
        // drop tables
        Schema::dropIfExists('alerts');
    }
}
