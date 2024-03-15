<?php

namespace Database\Seeders;

use App\Models\MenuItem;
use Illuminate\Database\Seeder;

class ClassroomMenuItemSeeder extends Seeder
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
                    'name' => 'Data Kelas',
                    'route' => 'classrooms',
                    'permission_name' => 'classrooms.index',
                    'menu_group_id' => 5,
                ],
            ]
        );
    }
}
