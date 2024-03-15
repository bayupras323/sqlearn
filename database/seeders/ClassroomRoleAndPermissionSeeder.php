<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class ClassroomRoleAndPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //Classroom Permission
        Permission::create(['name' => 'classrooms.management']);

        Permission::create(['name' => 'classrooms.index']);
        Permission::create(['name' => 'classrooms.create']);
        Permission::create(['name' => 'classrooms.edit']);
        Permission::create(['name' => 'classrooms.destroy']);

        //Create Lecturer Role if not exist and assign the permission
        $roleUser = Role::findOrCreate('lecturer');
        $roleUser->givePermissionTo([
            'dashboard',
            'classrooms.management',
            'classrooms.index',
            'classrooms.create',
            'classrooms.edit',
            'classrooms.destroy'
        ]);

        $user = User::find(3);
        $user->assignRole('lecturer');
    }
}
