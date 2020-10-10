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
        cache()->forget('fillable-users');
        // create columns
        Schema::table('users', function (Blueprint $table) {
            $table->string('type')->default('User');
            $table->json('social')->nullable();
            $table->string('avatar')->nullable();
            $table->string('timezone')->default(config('app.timezone'))->index();
            $table->integer('created_by')->nullable()->default(1);
            $table->integer('updated_by')->nullable()->default(1);
        });

        // create default admin user
        $user = app(config('auth.providers.users.model'))->create([
            'name' => 'Admin',
            'email' => 'admin@email.com',
            'password' => Hash::make('admin123'),
            'type' => 'Admin',
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
            'type' => 'Admin',
        ]);
        $user = app(config('auth.providers.users.model'))->create([
            'name' => 'Manager',
            'email' => 'manager@email.com',
            'password' => Hash::make('admin123'),
            'type' => 'Admin',
        ]);
        // create permissions
        app(config('sap.models.permission'))->createGroup('Users', ['Create Users', 'Read Users', 'Update Users', 'Delete Users', 'Update Users Password']);

        Schema::dropIfExists('password_resets');
        Schema::create('password_resets', function (Blueprint $table) {
            $table->string('email')->index();
            $table->string('token')->index();
            $table->timestamp('created_at');
        });

        if (Schema::hasTable('personal_access_tokens')) {
            Schema::table('personal_access_tokens', function (Blueprint $table) {
                $table->string('plain_text_token')->nullable()->index();
            });
            app(config('sap.models.permission'))->createGroup('Personal Access Token', ['Create Personal Access Token', 'Read Personal Access Token', 'Delete Personal Access Token']);
        }
    }

    public function down()
    {
        // drop columns
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('type');
            $table->dropColumn('social');
            $table->dropColumn('timezone');
            $table->dropColumn('created_by');
            $table->dropColumn('updated_by');
        });

        // delete permissions
        app(config('sap.models.permission'))->where('group', 'Users')->delete();

        Schema::drop('password_resets');
    }
}
