<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSettingsTable extends Migration
{
    public function up()
    {
        cache()->forget('fillable-settings');
        // create table
        Schema::create('settings', function (Blueprint $table) {
            $table->increments('id');
            $table->string('key')->index();
            $table->text('value')->nullable();
            $table->timestamps();
            $table->integer('created_by')->nullable()->default(1);
            $table->integer('updated_by')->nullable()->default(1);
        });

        // create example setting
        app(config('sap.models.setting'))->create([
            'key' => 'string',
            'value' => 'Hello World',
        ]);

        app(config('sap.models.setting'))->create([
            'key' => 'array',
            'value' => ['Hi', 'Hello', 'Aloha'],
        ]);

        app(config('sap.models.setting'))->create([
            'key' => 'permission_groups',
            'value' => ["Admin Panel" => "Admin Panel", "Permission" => "Permission", "Setting" => "Setting", "Role" => "Role", "User" => "User", "Activity Log" => "Activity Log", "System Log" => "System Log"],
        ]);

        app(config('sap.models.setting'))->create([
            'key' => 'user_types',
            'value' => ["Admin" => "Admin", "User" => "User"],
        ]);

        app(config('sap.models.setting'))->create([
            'key' => 'user_status',
            'value' => ["A" => "Active", "I" => "Inactive"],
        ]);

        app(config('sap.models.setting'))->create(['key' => 'locales','value' => ['en' => 'EN']]);

        // add permissions
        app(config('sap.models.permission'))->createGroup('Settings', ['Create Settings', 'Read Settings', 'Update Settings', 'Delete Settings']);
    }

    public function down()
    {
        // drop table
        Schema::dropIfExists('settings');

        // delete permissions
        app(config('sap.models.permission'))->where('group', 'Settings')->delete();
    }
}
