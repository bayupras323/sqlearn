<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class StudentRoleAndPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //Student Permission
        Permission::create(['name' => 'students.management']);
        Permission::create(['name' => 'students.index']);
        Permission::create(['name' => 'students.create']);
        Permission::create(['name' => 'students.edit']);
        Permission::create(['name' => 'students.destroy']);

        //Create Lecturer Role if not exist and assign the permission
        $roleLecturer = Role::findOrCreate('lecturer');
        $roleLecturer->givePermissionTo([
            'dashboard',
            'students.management',
            'students.index',
            'students.create',
            'students.edit',
            'students.destroy'
        ]);

        $lecturer = User::find(3);
        $lecturer->assignRole('lecturer');

        //Create Student Role if not exist and assign the permission
        $roleStudent = Role::findOrCreate('student');
        $roleStudent->givePermissionTo([
            'dashboard'
        ]);

        //Assign the Student Role to multiple users
        $users = User::whereIn('id', [4, 5, 6])->get();
        $users->each(function ($user) use ($roleStudent) {
            $user->assignRole($roleStudent);
        });
    }
}
