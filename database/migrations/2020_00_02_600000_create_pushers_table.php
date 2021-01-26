<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePushersTable extends Migration
{
    public function up()
    {
        $user_id = 1;
        Schema::create('pushers', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('brand_id')->nullable()->default(0);
            $table->string('locale', 2)->nullable()->default('en');
            $table->string('event')->nullable()->default('general');
            $table->string('title')->nullable()->default('');
            $table->string('link')->nullable()->default('');
            $table->string('icon')->nullable()->default('');
            $table->text('message')->nullable()->default('');
            $table->integer('timeout')->nullable()->default(5000);
            $table->datetime('scheduled_at')->nullable();
            $table->timestamps();
            $table->integer('created_by')->nullable()->default(1);
            $table->integer('updated_by')->nullable()->default(1);
            $table->string('status', 1)->nullable()->default('');
        });
        app(config('sap.models.permission'))->createGroup('Pushers', ['Read Pushers', 'Preview Pushers', 'Update Pushers', 'Delete Pushers', 'Push Pushers'], $user_id);
        app(config('sap.models.setting'))->create(['created_by' => $user_id, 'updated_by' => $user_id, 'key' => 'pusher_status','value' => ['A' => 'Active','I' => 'Inactive', 'S' => 'Sent']]);
        app(config('sap.models.setting'))->create(['created_by' => $user_id, 'updated_by' => $user_id, 'key' => 'pusher_events', 'value' => ["general" => "General"]]);
    }

    public function down()
    {
        // drop tables
        Schema::dropIfExists('pushers');
        app(config('sap.models.permission'))->whereIn('group', [
            'Pushers',
        ])->delete();
        app(config('sap.models.setting'))->whereIn('key', [
            'pusher_events',
            'pusher_status',
        ])->delete();
    }
}
