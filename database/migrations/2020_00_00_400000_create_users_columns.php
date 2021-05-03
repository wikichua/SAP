<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersColumns extends Migration
{
    public function up()
    {
        cache()->tags(['fillable'])->flush();
        Schema::table('users', function (Blueprint $table) {
            $table->string('type')->default('User');
            $table->json('social')->nullable();
            $table->string('avatar')->nullable();
            $table->string('timezone')->default(config('app.timezone'))->index();
            $table->integer('created_by')->nullable()->default(1);
            $table->integer('updated_by')->nullable()->default(1);
        });

        Schema::dropIfExists('password_resets');
        Schema::create('password_resets', function (Blueprint $table) {
            $table->string('email')->index();
            $table->string('token')->index();
            $table->timestamp('created_at');
        });

        if (Schema::hasTable('personal_access_tokens')) {
            Schema::table('personal_access_tokens', function (Blueprint $table) {
                if (config('database.default') != 'sqlite') {
                    $table->dropColumn('id');
                }
            });
            Schema::table('personal_access_tokens', function (Blueprint $table) {
                if (config('database.default') != 'sqlite') {
                    $table->increments('id')->before('tokenable');
                }
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
