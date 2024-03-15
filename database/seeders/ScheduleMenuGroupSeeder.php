<?php

namespace Database\Seeders;

use App\Models\MenuGroup;
use Illuminate\Database\Seeder;

class ScheduleMenuGroupSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        MenuGroup::create([
            'name' => 'Manajemen Jadwal',
            'icon' => 'fas fa-calendar',
            'permission_name' => 'schedules.management',
        ]);
    }
}