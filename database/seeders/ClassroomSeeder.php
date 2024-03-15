<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use App\Models\Classroom;

class ClassroomSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $classroom = [
            [
                'prodi' => 'TI',
                'tingkat' => 1,
                'jumlah' => 9,
            ],
            [
                'prodi' => 'TI',
                'tingkat' => 4,
                'jumlah' => 9,
            ],
            [
                'prodi' => 'SIB',
                'tingkat' => 1,
                'jumlah' => 5,
            ],
        ];

        foreach ($classroom as $class) {
            for ($i = 1; $i <= $class['jumlah']; $i++) {
                Classroom::create([
                    'name' => $class['prodi'] . '-' . $class['tingkat'] . chr($i + 64),
                    'user_id' => 3,
                    'semester' => $class['tingkat'] * 2,
                ]);
            }
        }

        // for thesis team members
        Classroom::create([
            'name' => 'TI-4X',
            'user_id' => 3,
            'semester' => 8,
        ]);
    }
}
