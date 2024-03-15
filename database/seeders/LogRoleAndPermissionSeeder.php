<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class LogRoleAndPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //Score Permission
        Permission::create(['name' => 'logs.management']);

        Permission::create(['name' => 'logs-pp.index']);
        Permission::create(['name' => 'logs-ppd.index']);
        Permission::create(['name' => 'logs-pp2d.index']);

        //Create Lecturer Role if not exist and assign the permission
        $roleUser = Role::findOrCreate('lecturer');
        $roleUser->givePermissionTo([
            'dashboard',
            'logs.management',
            'logs-pp.index',
            'logs-ppd.index',
            'logs-pp2d.index',
        ]);

        $user = User::find(3);
        $user->assignRole('lecturer');
    }
}
