<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users = [
            [
                'name' => 'SuperAdmin',
                'email' => 'superadmin@gmail.com',
                'password' => 'password',
            ],
            [
                'name' => 'user',
                'email' => 'user@gmail.com',
                'password' => 'password',
            ],
            [
                'name' => 'lecturer',
                'email' => 'lecturer@gmail.com',
                'password' => 'password',
            ],
        ];

        $students = [
            [
                'nim' => '1941720068',
                'name' => 'Ahmad Fauzi',
            ],
            [
                'nim' => '1941720064',
                'name' => 'Abdulloh Aqil',
            ],
            [
                'nim' => '1941720028',
                'name' => 'Wulan Widiasari',
            ],
            [
                'nim' => '1941720134',
                'name' => 'Nabila Senja',
            ],
            [
                'nim' => '1941720012',
                'name' => 'Farid Maulana',
            ],
            [
                'nim' => '2241727028',
                'name' => 'Refido Berliano',
            ],
            [
                'nim' => '1941720070',
                'name' => 'Genadi Dharma',
            ],
            [
                'nim' => '2241727030',
                'name' => 'Sella Novanda',
            ],
            [
                'nim' => '1941720000',
                'name' => 'Mahasiswa JTI',
            ],
        ];

        foreach ($users as $user) {
            User::create([
                'name' => $user['name'],
                'email' => $user['email'],
                'password' => Hash::make($user['password']),
                'email_verified_at' => Carbon::now()->format('Y-m-d H:i'),
            ]);
        }

        // for thesis team members
        foreach ($students as $student) {
            User::create([
                'name' => $student['name'],
                'email' => $student['nim'] . '@student.polinema.ac.id',
                'password' => Hash::make($student['nim']),
                'email_verified_at' => Carbon::now()->format('Y-m-d H:i'),
            ]);
        }
    }
}
