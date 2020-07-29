<?php

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Str;

class CreateUsersColumns extends Migration
{
    public function up()
    {
        // create columns
        Schema::table('users', function (Blueprint $table) {
            $table->string('type')->default('user');
            $table->string('timezone')->default(config('app.timezone'))->index();
        });

        // create default admin user
        $user = app(config('auth.providers.users.model'))->create([
            'name' => 'Admin',
            'email' => 'admin@email.com',
            'password' => Hash::make('admin123'),
        ]);

        // give default admin user default admin role
        $user->roles()->attach(app(config('sap.models.role'))->where('admin', true)->first()->id);

        $user = app(config('auth.providers.users.model'))->create([
            'name' => 'User',
            'email' => 'user@email.com',
            'password' => Hash::make('admin123'),
        ]);
        $user = app(config('auth.providers.users.model'))->create([
            'name' => 'Developer',
            'email' => 'dev@email.com',
            'password' => Hash::make('admin123'),
        ]);
        $user = app(config('auth.providers.users.model'))->create([
            'name' => 'Manager',
            'email' => 'manager@email.com',
            'password' => Hash::make('admin123'),
        ]);
        // create permissions
        app(config('sap.models.permission'))->createGroup('Users', ['Create Users', 'Read Users', 'Update Users', 'Delete Users', 'Update Users Password']);

        Schema::dropIfExists('password_resets');
        Schema::create('password_resets', function (Blueprint $table) {
            $table->string('email')->index();
            $table->string('token')->index();
            $table->timestamp('created_at');
        });
    }

    public function down()
    {
        // drop columns
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('admin');
            $table->dropColumn('timezone');
        });

        // delete permissions
        app(config('sap.models.permission'))->where('group', 'Users')->delete();

        Schema::drop('password_resets');
    }
}
