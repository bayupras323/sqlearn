<?php

namespace Database\Seeders;

use App\Models\Database;
use Database\Seeders\Exercises\AggregrationSeeder;
use Database\Seeders\Exercises\CrossUnionJoinSeeder;
use Database\Seeders\Exercises\DataDefinitionSeeder;
use Database\Seeders\Exercises\EntityAtributeSeeder;
use Database\Seeders\Exercises\InnerOuterJoinSeeder;
use Database\Seeders\Exercises\RelationshipSeeder;
use Database\Seeders\Exercises\SelectSeeder;
use Database\Seeders\Exercises\SubquerySeeder;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Config;

class ExerciseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $database = [
            [
                'db_name' => 'sqlearn_library',
                'sql_file' => 'databases' . DIRECTORY_SEPARATOR . 'library.sql',
            ],
            [
                'db_name' => 'sqlearn_showroom',
                'sql_file' => 'databases' . DIRECTORY_SEPARATOR . 'showroom_mobil.sql',
            ],
            [
                'db_name' => 'sqlearn_employee',
                'sql_file' => 'databases' . DIRECTORY_SEPARATOR . 'employee.sql',
            ],
            [
                'db_name' => 'sqlearn_courses',
                'sql_file' => 'databases' . DIRECTORY_SEPARATOR . 'courses.sql',
            ],
            [
                'db_name' => 'sqlearn_swalayan',
                'sql_file' => 'databases' . DIRECTORY_SEPARATOR . 'swalayan.sql',
            ],
            [
                'db_name' => 'sqlearn_dokter_hewan',
                'sql_file' => 'databases' . DIRECTORY_SEPARATOR . 'sim_dokter_hewan.sql',
            ],
            [
                'db_name' => 'sqlearn_rental_car',
                'sql_file' => 'databases' . DIRECTORY_SEPARATOR . 'sqlearn_rental_car.sql',
            ],
            [
                'db_name' => 'sqlearn_sales',
                'sql_file' => 'databases' . DIRECTORY_SEPARATOR . 'sqlearn_sales.sql',
            ],
            [
                'db_name' => 'sqlearn_project',
                'sql_file' => 'databases' . DIRECTORY_SEPARATOR . 'sqlearn_project.sql',
            ],
            [
                'db_name' => 'sqlearn_warehouse',
                'sql_file' => 'databases' . DIRECTORY_SEPARATOR . 'sqlearn_warehouse.sql',
            ],
        ];

        foreach ($database as $db) {
            // drop database
            DB::statement("DROP DATABASE IF EXISTS `{$db['db_name']}`");
            DB::statement("CREATE DATABASE `{$db['db_name']}`");

            Config::set('database.connections.' . $db['db_name'], [
                'driver' => 'mysql',
                'host' => env('DB_HOST', '127.0.0.1'),
                'port' => env('DB_PORT', '3306'),
                'database' => $db['db_name'],
                'username' => env('DB_USERNAME'),
                'password' => env('DB_PASSWORD'),
                'charset' => 'utf8mb4',
                'collation' => 'utf8mb4_unicode_ci',
                'prefix' => '',
                'strict' => true,
                'engine' => null,
            ]);

            // insert to database table
            Database::create([
                'name' => $db['db_name'],
                'sql_file' => $db['sql_file'],
            ]);

            // execute sql file
            DB::connection($db['db_name'])->unprepared(file_get_contents(public_path($db['sql_file'])));
        }

        $this->call([
            SelectSeeder::class,
            AggregrationSeeder::class,
            InnerOuterJoinSeeder::class,
            CrossUnionJoinSeeder::class,
            SubquerySeeder::class,
            DataDefinitionSeeder::class,
            EntityAtributeSeeder::class,
            RelationshipSeeder::class,
        ]);
    }
}
