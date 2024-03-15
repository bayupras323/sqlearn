<?php

namespace Database\Seeders;

use App\Models\Classroom;
use App\Models\ClassroomSchedule;
use App\Models\Package;
use App\Models\Schedule;
use App\Models\Student;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;

class BetaAccountSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $betaAccounts = [
            [
                'nim' => '2041720230',
                'class' => 'TI-3G',
                'name' => 'Ahmad Rafif Alaudin',
            ],
            [
                'nim' => '2041720016',
                'class' => 'TI-3L',
                'name' => 'Atmayanti',
            ],
            [
                'nim' => '2041720178',
                'class' => 'TI-3L',
                'name' => 'Muhammad Ghaniyu Haq Haryanto',
            ],
            [
                'nim' => '2141723001',
                'class' => 'TI-3L',
                'name' => 'Galiley Singgang Mangkuluhur Yasimaru',
            ],
            [
                'nim' => '2041720026',
                'class' => 'TI-3L',
                'name' => 'Rosi Latansa Salsabela',
            ],
            [
                'nim' => '2041720233',
                'class' => 'TI-3L',
                'name' => 'Thirsya Widya Sulaiman',
            ],
            [
                'nim' => '2141723002',
                'class' => 'TI-3L',
                'name' => 'Ariq Luthfi Rifqi',
            ],
        ];

        foreach ($betaAccounts as $account) {
            // create account in user database
            $user = User::create([
                'name' => $account['name'],
                'email' => $account['nim'] . '@student.polinema.ac.id',
                'password' => Hash::make($account['nim']),
                'email_verified_at' => Carbon::now()->format('Y-m-d H:i'),
            ]);

            // create classroom
            $classroom = Classroom::where('name', $account['class'])->first();
            if ($classroom == null) {
                $classroom = Classroom::create([
                    'name' => $account['class'],
                    'user_id' => 3,
                    'semester' => preg_replace('/[^0-9]/', '', $account['class']) * 2,
                ]);
                $classroom_id = $classroom->id;

                $schedules = Schedule::where('name', 'LIKE', '%beta%')->get();
                foreach ($schedules as $schedule) {
                    ClassroomSchedule::create([
                        'schedule_id' => $schedule->id,
                        'classroom_id' => $classroom_id,
                    ]);
                }
            } else {
                $classroom_id = $classroom->id;
            }

            // create student data
            Student::create([
                'user_id' => $user->id,
                'classrooms_id' => $classroom_id,
                'student_id_number' => $account['nim'],
            ]);
        }
    }
}
