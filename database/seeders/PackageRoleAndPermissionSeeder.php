<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use App\Models\User;

class PackageRoleAndPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        {
            // Paket Soal Permission
            Permission::create(['name' => 'packages.management']);
            Permission::create(['name' => 'packages.index']);
            Permission::create(['name' => 'packages.create']);
            Permission::create(['name' => 'packages.edit']);
            Permission::create(['name' => 'packages.destroy']);
    
            // Create Lecturer Role if not exist and assign the permission
            $roleUser = Role::findOrCreate('lecturer');
            $roleUser->givePermissionTo([
                'dashboard',
                'packages.management',
                'packages.index',
                'packages.create',
                'packages.edit',
                'packages.destroy'
            ]);
            
            $lecturer = User::where('name', 'lecturer')->first();
            $lecturer->assignRole('lecturer');
        }
    }
}
