<?php

namespace Database\Seeders;

use App\Models\MenuGroup;
use App\Models\MenuItem;
use Illuminate\Database\Seeder;

class ScheduleMenuItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $menuGroup = MenuGroup::where('name', 'Manajemen Jadwal')->first();

        MenuItem::create([
            'name' => 'Data Jadwal',
            'route' => 'schedules',
            'permission_name' => 'schedules.index',
            'menu_group_id' => $menuGroup->id,
        ]);
    }
}
