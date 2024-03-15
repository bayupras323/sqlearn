<?php

namespace Database\Seeders;

use App\Models\Classroom;
use App\Models\User;
use Illuminate\Database\Seeder;
use App\Models\Student;

class StudentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $students = User::select('id', 'email')
            ->where('email', 'LIKE', '%1941720%')
            ->orWhere('email', 'LIKE', '%2241727%')
            ->orderBy('id')
            ->get();
        $classrooms = Classroom::select('id')->where('semester', 8)->get()->toArray();

        foreach ($students as $index => $student) {
            $student_id_number = substr($student->email, 0, strrpos($student->email, '@'));
            Student::create([
                'user_id' => $student->id,
                'classrooms_id' => ($student_id_number == '1941720000') ? $classrooms[$index+1]['id'] : $classrooms[$index]['id'],
                'student_id_number' => $student_id_number,
            ]);
        }
    }
}
