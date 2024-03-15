<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class ScoreRoleAndPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //Score Permission
        Permission::create(['name' => 'scores.management']);

        Permission::create(['name' => 'scores.index']);
        Permission::create(['name' => 'scores.show']);

        //Create Lecturer Role if not exist and assign the permission
        $roleUser = Role::findOrCreate('lecturer');
        $roleUser->givePermissionTo([
            'dashboard',
            'scores.management',
            'scores.index',
            'scores.show',
        ]);

        $user = User::find(3);
        $user->assignRole('lecturer');
    }
}
