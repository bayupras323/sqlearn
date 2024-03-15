<?php

namespace Database\Seeders;

use App\Models\MenuGroup;
use Illuminate\Database\Seeder;

class LogMenuGroupSeeder extends Seeder
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
                    'name' => 'Log Analytics',
                    'icon' => 'fas fa-chart-area',
                    'permission_name' => 'logs.management',
                ],
            ]
        );
    }
}
