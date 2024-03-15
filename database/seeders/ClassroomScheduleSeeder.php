<?php

namespace Database\Seeders;

use App\Models\Classroom;
use App\Models\ClassroomSchedule;
use App\Models\Schedule;
use App\Models\Student;
use Illuminate\Database\Seeder;

class ClassroomScheduleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $teamTopics = [
            '1941720000 - All Topic',
            '1941720068 - ERD Entity dan Atribut',
            '1941720064 - ERD Relationship',
            '1941720028 - SQL Query Select',
            '1941720134 - SQL Query Select Agregasi',
            '1941720012 - SQL Query Inner dan Outer Join',
            '2241727028 - SQL Query Union dan Cross Join',
            '1941720070 - SQL Subquery',
            '2241727030 - SQL Data Definition Language',
        ];

        foreach ($teamTopics as $team) {
            $parts = explode(" - ", $team);
            $nim = $parts[0];
            $topic = $parts[1];

            $classroom = Student::select('classrooms_id')->where('student_id_number', $nim)->first();
            $schedules = Schedule::select('id')->whereRelation('package.topic', 'name', $topic)->get();
            if (count($schedules) != 0) {
                foreach ($schedules as $schedule) {
                    ClassroomSchedule::create([
                        'schedule_id' => $schedule->id,
                        'classroom_id' => $classroom->classrooms_id,
                    ]);
                }
            } else {
                $schedules = Schedule::select('id')->get();
                foreach ($schedules as $schedule) {
                    ClassroomSchedule::create([
                        'schedule_id' => $schedule->id,
                        'classroom_id' => $classroom->classrooms_id,
                    ]);
                }
            }
        }
    }
}
