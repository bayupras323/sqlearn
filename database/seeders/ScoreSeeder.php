<?php

namespace Database\Seeders;

use App\Models\Schedule;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use App\Models\Score;

class ScoreSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create();
        $schedules = Schedule::with('classrooms.students')
            ->has('classrooms.students')
            ->orderByDesc('id')
            ->take(5)
            ->get();

        foreach ($schedules as $schedule) {
            $score = $faker->randomElement(range(50, 100, 5));
            $classroom = $schedule->classrooms->random();
            $student = $classroom->students()->inRandomOrder()->first();

            Score::create([
                'student_id' => $student->id,
                'schedule_id' => $schedule->id,
                'score' => $score,
                'created_at' => Carbon::now()->format('Y-m-d H:i'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i'),
            ]);
        }
    }
}
