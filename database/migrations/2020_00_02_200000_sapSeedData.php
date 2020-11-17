<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class SapSeedData extends Migration
{
    public function up()
    {
        // create default admin user
        \Cache::forget('SYSADMINID');
        $user = app(config('auth.providers.users.model'))->create([
            'name' => 'Admin',
            'email' => 'admin@email.com',
            'password' => Hash::make('admin123'),
            'type' => 'Admin',
        ]);
        $user->created_by = $user->id;
        $user->updated_by = $user->id;
        $user->save();
        // create default admin role
        app(config('sap.models.role'))->create([
            'name' => 'Admin',
            'admin' => true,
            'created_by' => $user->id,
            'updated_by' => $user->id,
        ]);
        // give default admin user default admin role
        $user->roles()->attach(app(config('sap.models.role'))->where('admin', true)->first()->id);

        app(config('auth.providers.users.model'))->create([
            'created_by' => $user->id,
            'updated_by' => $user->id,
            'name' => 'User',
            'email' => 'user@email.com',
            'password' => Hash::make('admin123'),
        ]);

        app(config('sap.models.permission'))->createGroup('Activity Logs', ['Read Activity Logs'], $user->id);
        app(config('sap.models.permission'))->createGroup('Admin Panel', ['Access Admin Panel'], $user->id);
        app(config('sap.models.permission'))->createGroup('Permissions', ['Create Permissions', 'Read Permissions', 'Update Permissions', 'Delet,$user->ide Permissions'], $user->id);
        app(config('sap.models.permission'))->createGroup('Roles', ['Create Roles', 'Read Roles', 'Update Roles', 'Delete Roles'], $user->id);
        app(config('sap.models.permission'))->createGroup('Users', ['Create Users', 'Read Users', 'Update Users', 'Delete Users', 'Update Users Password'], $user->id);
        if (Schema::hasTable('personal_access_tokens')) {
            app(config('sap.models.permission'))->createGroup('Personal Access Token', ['Create Personal Access Token', 'Read Personal Access Token', 'Delete Personal Access Token'], $user->id);
        }

        app(config('sap.models.permission'))->createGroup('Settings', ['Create Settings', 'Read Settings', 'Update Settings', 'Delete Settings'], $user->id);

        app(config('sap.models.setting'))->create([
            'created_by' => $user->id, 'updated_by' => $user->id,
            'key' => 'permission_groups',
            'value' => ["Admin Panel" => "Admin Panel", "Permission" => "Permission", "Setting" => "Setting", "Role" => "Role", "User" => "User", "Activity Log" => "Activity Log", "System Log" => "System Log"],
        ]);

        app(config('sap.models.setting'))->create([
            'created_by' => $user->id, 'updated_by' => $user->id,
            'key' => 'user_types',
            'value' => ["Admin" => "Admin", "User" => "User"],
        ]);

        app(config('sap.models.setting'))->create([
            'created_by' => $user->id, 'updated_by' => $user->id,
            'key' => 'user_status',
            'value' => ["A" => "Active", "I" => "Inactive"],
        ]);

        app(config('sap.models.setting'))->create(['created_by' => $user->id, 'updated_by' => $user->id,'key' => 'locales','value' => ['en' => 'EN']]);

        app(config('sap.models.setting'))->create(['created_by' => $user->id, 'updated_by' => $user->id,'key' => 'report_status','value' => ['A' => 'Active','I' => 'Inactive']]);
        app(config('sap.models.permission'))->createGroup('Reports', ['Create Reports', 'Read Reports', 'Update Reports', 'Delete Reports'], $user->id);
        app(config('sap.models.setting'))->create(['created_by' => $user->id, 'updated_by' => $user->id,'key' => 'brand_status','value' => ['A' => 'Published','P' => 'Pending','E' => 'Expired']]);
        app(config('sap.models.permission'))->createGroup('Brands', ['Read Brands', 'Update Brands'], $user->id);
        app(config('sap.models.permission'))->createGroup('Components', ['Read Components'], $user->id);
        app(config('sap.models.setting'))->create(['created_by' => $user->id, 'updated_by' => $user->id,'key' => 'page_status','value' => ['A' => 'Published','P' => 'Pending','E' => 'Expired']]);
        app(config('sap.models.permission'))->createGroup('Pages', ['Create Pages', 'Read Pages', 'Update Pages', 'Delete Pages'], $user->id);
        app(config('sap.models.setting'))->create(['created_by' => $user->id, 'updated_by' => $user->id,'key' => 'nav_status','value' => ['A' => 'Active','I' => 'Inactive']]);
        app(config('sap.models.permission'))->createGroup('Navs', ['Create Navs', 'Read Navs', 'Update Navs', 'Delete Navs'], $user->id);
        app(config('sap.models.setting'))->create(['created_by' => $user->id, 'updated_by' => $user->id,'key' => 'carousel_tags','value' => ['new' => 'New','hot' => 'Hot','recommended' => 'Recommended']]);
        app(config('sap.models.setting'))->create(['created_by' => $user->id, 'updated_by' => $user->id,'key' => 'carousel_status','value' => ['A' => 'Active','I' => 'Inactive']]);
        app(config('sap.models.permission'))->createGroup('Carousels', ['Create Carousels', 'Read Carousels', 'Update Carousels', 'Delete Carousels'], $user->id);
    }
    public function down()
    {
        app(config('sap.models.permission'))->whereIn('group', [
            'Activity Logs',
            'Admin Panel',
            'Roles',
            'Users',
            'Personal Access Token',
            'Permissions',
            'Reports',
            'Brands',
            'Components',
            'Pages',
            'Navs',
            'Carousels',
            'Settings',
        ])->delete();

        app(config('sap.models.setting'))->whereIn('key', [
            'carousel_tags',
            'carousel_status',
            'nav_status',
            'page_status',
            'brand_status',
            'report_status',
            'permission_groups',
            'user_types',
            'user_status',
            'locales',
        ])->delete();
    }
}
