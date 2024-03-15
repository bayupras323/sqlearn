<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            UserSeeder::class,
            RoleAndPermissionSeeder::class,
            MenuGroupSeeder::class,
            MenuItemSeeder::class,

            // Classroom seeder
            ClassroomRoleAndPermissionSeeder::class,
            ClassroomMenuGroupSeeder::class,
            ClassroomMenuItemSeeder::class,
            ClassroomSeeder::class,

            // Student seeder
            StudentRoleAndPermissionSeeder::class,
            // StudentMenuGroupSeeder::class,
            // StudentMenuItemSeeder::class,
            StudentSeeder::class,

            // Topic seeder
            TopicSeeder::class,

            // Package seeder
            PackageRoleAndPermissionSeeder::class,
            PackageMenuGroupSeeder::class,
            PackageMenuItemSeeder::class,
            PackageSeeder::class,

            // Schedule seeder
            ScheduleRoleAndPermissionSeeder::class,
            ScheduleMenuGroupSeeder::class,
            ScheduleMenuItemSeeder::class,
            ScheduleSeeder::class,
            ClassroomScheduleSeeder::class,

            // Exercise seeder
            ExerciseSeeder::class,

            // Score seeder
            ScoreRoleAndPermissionSeeder::class,
            ScoreMenuGroupSeeder::class,
            ScoreMenuItemSeeder::class,
//            ScoreSeeder::class,

            // Beta account seeder
            BetaAccountSeeder::class,
        ]);
    }
}
