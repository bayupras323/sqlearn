<?php

namespace Database\Seeders;

use App\Models\MenuGroup;
use Illuminate\Database\Seeder;

class StudentMenuGroupSeeder extends Seeder
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
                    'name' => 'Manajemen Siswa',
                    'icon' => 'fas fa-graduation-cap ',
                    'permission_name' => 'students.management',
                ],
            ]
        );
    }
}
