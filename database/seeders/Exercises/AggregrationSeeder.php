<?php

namespace Database\Seeders\Exercises;

use App\Models\Database;
use App\Models\Exercise;
use App\Models\Package;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AggregrationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $exercises = [
            [
                'db_name' => 'sqlearn_library',
                'question' => '<p> Buatkan hasil eksekusi kueri berikut: </p><p> SELECT SUM(jml_buku) FROM peminjaman </p>',
                'answer' => ['queries' => 'SELECT SUM(jml_buku) FROM peminjaman'],
            ],
            [
                'db_name' => 'sqlearn_dokter_hewan',
                'question' => '<p> Buatkan hasil eksekusi kueri berikut: </p><p> SELECT COUNT(*) FROM reservasi </p>',
                'answer' => ['queries' => 'SELECT COUNT(*) FROM reservasi'],
            ],
            [
                'db_name' => 'sqlearn_library',
                'question' => '<p> Buatkan hasil eksekusi kueri berikut: </p><p> SELECT MAX(jml_buku) FROM peminjaman </p>',
                'answer' => ['queries' => 'SELECT MAX(jml_buku) FROM peminjaman'],
            ],
            [
                'db_name' => 'sqlearn_dokter_hewan',
                'question' => '<p> Buatkan hasil eksekusi kueri berikut: </p><p> SELECT MIN(harga) FROM pelayanan </p>',
                'answer' => ['queries' => 'SELECT MIN(harga) FROM pelayanan'],
            ],
            [
                'db_name' => 'sqlearn_dokter_hewan',
                'question' => '<p> Buatkan hasil eksekusi kueri berikut: </p><p> SELECT AVG(harga) FROM pelayanan</p>',
                'answer' => ['queries' => 'SELECT AVG(harga) FROM pelayanan'],
            ],
            [
                'db_name' => 'sqlearn_showroom',
                'question' => '<p> Buatkan hasil eksekusi kueri berikut: </p><p> SELECT COUNT(*) FROM transaksi </p>',
                'answer' => ['queries' => 'SELECT COUNT(*) FROM transaksi'],
            ],
            [
                'db_name' => 'sqlearn_library',
                'question' => '<p> Buatkan hasil eksekusi kueri berikut: </p><p> SELECT SUM(QTY) FROM buku </p>',
                'answer' => ['queries' => 'SELECT SUM(QTY) FROM buku'],
            ],
            [
                'db_name' => 'sqlearn_dokter_hewan',
                'question' => '<p> Buatkan hasil eksekusi kueri berikut: </p><p> SELECT MAX(harga) FROM pelayanan </p>',
                'answer' => ['queries' => 'SELECT MAX(harga) FROM pelayanan'],
            ],
            [
                'db_name' => 'sqlearn_swalayan',
                'question' => '<p> Buatkan hasil eksekusi kueri berikut: </p><p> SELECT MIN(harga) FROM produk </p>',
                'answer' => ['queries' => 'SELECT MIN(harga) FROM produk'],
            ],
            [
                'db_name' => 'sqlearn_swalayan',
                'question' => '<p> Buatkan hasil eksekusi kueri berikut: </p><p> SELECT AVG(harga) FROM produk </p>',
                'answer' => ['queries' => 'SELECT AVG(harga) FROM produk'],
            ],         
            // [
            //     'db_name' => 'sqlearn_dokter_hewan',
            //     'question' => '<p> Buatkan hasil eksekusi kueri berikut: </p><p> SELECT SUM(harga) FROM pelayanan </p>',
            //     'answer' => ['queries' => 'SELECT SUM(harga) FROM pelayanan'],
            // ],
            // [
            //     'db_name' => 'sqlearn_library',
            //     'question' => '<p> Buatkan hasil eksekusi kueri berikut: </p><p> SELECT MAX(YEAR) FROM buku </p>',
            //     'answer' => ['queries' => 'SELECT MAX(YEAR) FROM buku'],
            // ],
            // [
            //     'db_name' => 'sqlearn_showroom',
            //     'question' => '<p> Buatkan hasil eksekusi kueri berikut: </p><p> SELECT AVG(harga) FROM cars </p>',
            //     'answer' => ['queries' => 'SELECT AVG(harga) FROM mobil'],
            // ],
            // [
            //     'db_name' => 'sqlearn_swalayan',
            //     'question' => '<p> Buatkan hasil eksekusi kueri berikut: </p><p> SELECT SUM(harga) FROM produk </p>',
            //     'answer' => ['queries' => 'SELECT SUM(harga) FROM produk'],
            // ],
            // [
            //     'db_name' => 'sqlearn_library',
            //     'question' => '<p> Buatkan hasil eksekusi kueri berikut: </p><p> SELECT COUNT(*) FROM buku </p>',
            //     'answer' => ['queries' => 'SELECT COUNT(*) FROM buku'],
            // ],
            // [
            //     'db_name' => 'sqlearn_library',
            //     'question' => '<p> Buatkan hasil eksekusi kueri berikut: </p><p> SELECT MAX(QTY) FROM buku </p>',
            //     'answer' => ['queries' => 'SELECT MAX(QTY) FROM buku'],
            // ],
            // [
            //     'db_name' => 'sqlearn_library',
            //     'question' => '<p> Buatkan hasil eksekusi kueri berikut: </p><p> SELECT SUM(KELUAR) FROM buku </p>',
            //     'answer' => ['queries' => 'SELECT SUM(KELUAR) FROM buku'],
            // ],
            // [
            //     'db_name' => 'sqlearn_library',
            //     'question' => '<p> Buatkan hasil eksekusi kueri berikut: </p><p> SELECT MIN(jml_buku) FROM peminjaman </p>',
            //     'answer' => ['queries' => 'SELECT MIN(jml_buku) FROM peminjaman'],
            // ],
            // [
            //     'db_name' => 'sqlearn_library',
            //     'question' => '<p> Buatkan hasil eksekusi kueri berikut: </p><p> SELECT COUNT(*) FROM peminjaman </p>',
            //     'answer' => ['queries' => 'SELECT COUNT(*) FROM peminjaman'],
            // ],
            // [
            //     'db_name' => 'sqlearn_library',
            //     'question' => '<p> Buatkan hasil eksekusi kueri berikut: </p><p> SELECT MIN(QTY) FROM buku </p>',
            //     'answer' => ['queries' => 'SELECT MIN(QTY) FROM buku'],
            // ]
        ];

        $package = Package::select('id')
            ->whereRelation('topic', 'name','SQL Query Select Agregasi')
            ->first();

        $database = Database::select('name', 'id')->get();

        // insert to exercises table
        foreach ($exercises as $exercise) {
            foreach ($database as $db) {
                if ($db->name == $exercise['db_name']) {
                    Exercise::create([
                        'package_id' => $package->id,
                        'database_id' => $db->id,
                        'type' => 'DML',
                        'question' => $exercise['question'],
                        'answer' => $exercise['answer'],
                    ]);
                }
            }
        }
    }
}
