<?php

namespace Database\Seeders;

use App\Models\Package;
use App\Models\Topic;
use App\Models\User;
use Illuminate\Database\Seeder;

class PackageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $lecturer = User::where('name', 'lecturer')->first();
        $topics = Topic::all();

        foreach ($topics as $topic) {
            Package::create([
                'user_id' => $lecturer->id,
                'topic_id' => $topic->id,
                'name' => 'Latihan Soal ' . $topic->name,
            ]);
        }
    }
}
