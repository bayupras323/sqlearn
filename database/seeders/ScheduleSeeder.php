<?php

namespace Database\Seeders;

use App\Models\Package;
use App\Models\Schedule;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use Carbon\Carbon;

class ScheduleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $packages = Package::all();

        foreach ($packages as $package) {
            // for experiment
            Schedule::create([
                'package_id' => $package->id,
                'name' => $package->name,
                'type' => 'practice',
                'start_date' => Carbon::parse('2023-05-02 07:00'),
                'end_date' => Carbon::parse('2023-05-12 18:00'),
            ]);

            // schedule for beta version
            Schedule::create([
                'package_id' => $package->id,
                'name' => '(Beta) ' . $package->name,
                'type' => 'practice',
                'start_date' => Carbon::parse('2023-04-17 20:00'),
                'end_date' => Carbon::parse('2023-05-01 23:59'),
            ]);
        }
    }
}
