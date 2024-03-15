<?php

namespace Database\Seeders;

use App\Models\MenuItem;
use Illuminate\Database\Seeder;

class LogMenuItemSeeder extends Seeder
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
                    'name' => 'Parsons Problem',
                    'route' => 'logs-pp',
                    'permission_name' => 'logs-pp.index',
                    'menu_group_id' => 9,
                ],
                [
                    'name' => 'Parsons Problem Distractor',
                    'route' => 'logs-ppd',
                    'permission_name' => 'logs-ppd.index',
                    'menu_group_id' => 9,
                ],
                [
                    'name' => 'Parsons Problem 2D',
                    'route' => 'logs-pp2d',
                    'permission_name' => 'logs-pp2d.index',
                    'menu_group_id' => 9,
                ],
            ]
        );
    }
}
