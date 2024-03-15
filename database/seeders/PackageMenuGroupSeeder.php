<?php

namespace Database\Seeders;

use App\Models\MenuGroup;
use Illuminate\Database\Seeder;

class PackageMenuGroupSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        MenuGroup::create([
            'name' => 'Manajemen Paket Soal',
            'icon' => 'fas fa-book',
            'permission_name' => 'packages.management',
        ]);
    }
}
