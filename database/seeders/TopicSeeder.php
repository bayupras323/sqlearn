<?php

namespace Database\Seeders;

use App\Models\Topic;
use Illuminate\Database\Seeder;

class TopicSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $topics = [
            'ERD Entity dan Atribut',
            'ERD Relationship',
            'SQL Query Select',
            'SQL Query Select Agregasi',
            'SQL Query Inner dan Outer Join',
            'SQL Query Union dan Cross Join',
            'SQL Subquery',
            'SQL Data Definition Language',
            'Pasrsons Problem',
            'Pasrsons Problem 2D',
            'Pasrsons Problem Distractor'
        ];

        foreach ($topics as $topic) {
            Topic::create([
                'name' => $topic,
            ]);
        }
    }
}
