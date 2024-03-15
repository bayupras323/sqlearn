<?php

namespace Database\Seeders;

use App\Models\MenuItem;
use Illuminate\Database\Seeder;

class ScoreMenuItemSeeder extends Seeder
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
                    'name' => 'Daftar Nilai',
                    'route' => 'scores',
                    'permission_name' => 'scores.index',
                    'menu_group_id' => 8,
                ],
            ]
        );
    }
}
