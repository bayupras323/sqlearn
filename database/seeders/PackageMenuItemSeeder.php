<?php

namespace Database\Seeders;

use App\Models\MenuGroup;
use App\Models\MenuItem;
use Illuminate\Database\Seeder;

class PackageMenuItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $menuGroup = MenuGroup::where('name', 'Manajemen Paket Soal')->first();
        MenuItem::create([
            'name' => 'Data Paket Soal',
            'route' => 'packages',
            'permission_name' => 'packages.index',
            'menu_group_id' => $menuGroup->id,
        ]);
    }
}
