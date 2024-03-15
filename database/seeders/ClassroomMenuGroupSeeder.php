<?php

namespace Database\Seeders;

use App\Models\MenuGroup;
use Illuminate\Database\Seeder;

class ClassroomMenuGroupSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        MenuGroup::insert(
            [
                [
                    'name' => 'Manajemen Kelas',
                    'icon' => 'fas fa-chalkboard-teacher',
                    'permission_name' => 'classrooms.management',
                ],
            ]
        );
    }
}
