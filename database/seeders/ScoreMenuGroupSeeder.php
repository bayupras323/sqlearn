<?php

namespace Database\Seeders;

use App\Models\MenuGroup;
use Illuminate\Database\Seeder;

class ScoreMenuGroupSeeder extends Seeder
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
                    'name' => 'Nilai Mahasiswa',
                    'icon' => 'fas fa-list-ol',
                    'permission_name' => 'scores.management',
                ],
            ]
        );
    }
}
