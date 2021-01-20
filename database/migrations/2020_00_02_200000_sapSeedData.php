<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class SapSeedData extends Migration
{
    public function up()
    {
        $user_id = 1;
        // create default admin user
        $user = app(config('auth.providers.users.model'))->create([
            'name' => 'Admin',
            'email' => 'admin@email.com',
            'password' => Hash::make('admin123'),
            'type' => 'Admin',
        ]);
        $user->created_by = $user_id;
        $user->updated_by = $user_id;
        $user->save();
        // create default admin role
        app(config('sap.models.role'))->create([
            'name' => 'Admin',
            'admin' => true,
            'created_by' => $user_id,
            'updated_by' => $user_id,
        ]);
        // give default admin user default admin role
        $user->roles()->attach(app(config('sap.models.role'))->where('admin', true)->first()->id);

        app(config('auth.providers.users.model'))->create([
            'created_by' => $user_id,
            'updated_by' => $user_id,
            'name' => 'User',
            'email' => 'user@email.com',
            'password' => Hash::make('admin123'),
        ]);

        app(config('sap.models.permission'))->createGroup('Activity Logs', ['Read Activity Logs'], $user_id);
        app(config('sap.models.permission'))->createGroup('Admin Panel', ['Access Admin Panel'], $user_id);
        app(config('sap.models.permission'))->createGroup('Permissions', ['Create Permissions', 'Read Permissions', 'Update Permissions', 'Delete Permissions'], $user_id);
        app(config('sap.models.permission'))->createGroup('Roles', ['Create Roles', 'Read Roles', 'Update Roles', 'Delete Roles'], $user_id);
        app(config('sap.models.permission'))->createGroup('Users', ['Create Users', 'Read Users', 'Update Users', 'Delete Users', 'Update Users Password'], $user_id);
        if (Schema::hasTable('personal_access_tokens')) {
            app(config('sap.models.permission'))->createGroup('Personal Access Token', ['Create Personal Access Token', 'Read Personal Access Token', 'Delete Personal Access Token'], $user_id);
        }

        app(config('sap.models.permission'))->createGroup('Settings', ['Create Settings', 'Read Settings', 'Update Settings', 'Delete Settings'], $user_id);

        app(config('sap.models.setting'))->create([
            'created_by' => $user_id, 'updated_by' => $user_id,
            'key' => 'permission_groups',
            'value' => ["Admin Panel" => "Admin Panel", "Permission" => "Permission", "Setting" => "Setting", "Role" => "Role", "User" => "User", "Activity Log" => "Activity Log", "System Log" => "System Log"],
        ]);

        app(config('sap.models.setting'))->create(['created_by' => $user_id, 'updated_by' => $user_id, 'key' => 'user_types', 'value' => ["Admin" => "Admin", "User" => "User"], ]);
        app(config('sap.models.setting'))->create(['created_by' => $user_id, 'updated_by' => $user_id, 'key' => 'user_status', 'value' => ["A" => "Active", "I" => "Inactive"], ]);
        app(config('sap.models.setting'))->create(['created_by' => $user_id, 'updated_by' => $user_id,'key' => 'locales','value' => ['en' => 'EN']]);
        app(config('sap.models.setting'))->create(['created_by' => $user_id, 'updated_by' => $user_id,'key' => 'report_status','value' => ['A' => 'Active','I' => 'Inactive']]);
        app(config('sap.models.permission'))->createGroup('Reports', ['Create Reports', 'Read Reports', 'Update Reports', 'Delete Reports'], $user_id);
        app(config('sap.models.setting'))->create(['created_by' => $user_id, 'updated_by' => $user_id,'key' => 'brand_status','value' => ['A' => 'Published','P' => 'Pending','E' => 'Expired']]);
        app(config('sap.models.permission'))->createGroup('Brands', ['Read Brands', 'Update Brands'], $user_id);
        app(config('sap.models.permission'))->createGroup('Components', ['Read Components'], $user_id);
        app(config('sap.models.setting'))->create(['created_by' => $user_id, 'updated_by' => $user_id,'key' => 'page_status','value' => ['A' => 'Published','P' => 'Pending','E' => 'Expired']]);
        app(config('sap.models.permission'))->createGroup('Pages', ['Create Pages', 'Read Pages', 'Update Pages', 'Delete Pages', 'Replicate Pages'], $user_id);
        app(config('sap.models.setting'))->create(['created_by' => $user_id, 'updated_by' => $user_id,'key' => 'nav_status','value' => ['A' => 'Active','I' => 'Inactive']]);
        app(config('sap.models.permission'))->createGroup('Navs', ['Create Navs', 'Read Navs', 'Update Navs', 'Delete Navs'], $user_id);
        app(config('sap.models.setting'))->create(['created_by' => $user_id, 'updated_by' => $user_id,'key' => 'carousel_tags','value' => ['new' => 'New','hot' => 'Hot','recommended' => 'Recommended']]);
        app(config('sap.models.setting'))->create(['created_by' => $user_id, 'updated_by' => $user_id,'key' => 'carousel_status','value' => ['A' => 'Active','I' => 'Inactive']]);
        app(config('sap.models.permission'))->createGroup('Carousels', ['Create Carousels', 'Read Carousels', 'Update Carousels', 'Delete Carousels'], $user_id);
        app(config('sap.models.permission'))->createGroup('Files', ['Upload Files', 'Rename Files', 'Delete Files', 'Copy Files'], $user_id);
        app(config('sap.models.permission'))->createGroup('Folders', ['Create Folders', 'Rename Folders', 'Delete Files', 'Copy Folders'], $user_id);
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
