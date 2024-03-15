<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class ScheduleRoleAndPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Permission::create(['name' => 'schedules.management']);
        Permission::create(['name' => 'schedules.index']);
        Permission::create(['name' => 'schedules.create']);
        Permission::create(['name' => 'schedules.edit']);
        Permission::create(['name' => 'schedules.destroy']);

        $roleUser = Role::findOrCreate('lecturer');
        $roleUser->givePermissionTo([
            'schedules.management',
            'schedules.index',
            'schedules.create',
            'schedules.edit',
            'schedules.destroy'
        ]);

        $lecturer = User::where('name', 'lecturer')->first();
        $lecturer->assignRole('lecturer');
    }
}
