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

        Schema::drop('password_resets');
    }
}
