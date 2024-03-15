<?php

namespace Database\Seeders;

use App\Models\MenuItem;
use Illuminate\Database\Seeder;

class StudentMenuItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        MenuItem::insert(
            [
                [
                    'name' => 'Data Mahasiswa',
                    'route' => 'students',
                    'permission_name' => 'students.index',
                    'menu_group_id' => 6,
                ],
            ]
        );
    }
}
