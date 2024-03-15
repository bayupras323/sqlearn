<?php

namespace Database\Seeders\Exercises;

use App\Models\Database;
use App\Models\Exercise;
use App\Models\Package;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SelectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $exercises = [
            // 1. SELECT WHERE
            [
                'db_name' => 'sqlearn_library',
                'question' => '<p> Buatkan hasil eksekusi kueri berikut: </p><p> SELECT * FROM anggota WHERE GENDER="P" </p>',
                'answer' => ['queries' => "SELECT * FROM anggota WHERE GENDER='P'"],
            ],

            // 2. SELECT >
            [
                'db_name' => 'sqlearn_swalayan',
                'question' => '<p> Buatkan hasil eksekusi kueri berikut: </p><p> SELECT nama_produk, stock FROM produk WHERE stock > 20 </p>',
                'answer' => ['queries' => "SELECT nama_produk, stock FROM produk WHERE stock > 20"],
            ],

            // 3. SELECT <
            [
                'db_name' => 'sqlearn_courses',
                'question' => '<p> Buatkan hasil eksekusi kueri berikut: </p><p> SELECT id, tanggal_transaksi FROM transaksi WHERE tanggal_transaksi < 2022-07-03 </p>',
                'answer' => ['queries' => "SELECT id, tanggal_transaksi FROM transaksi WHERE tanggal_transaksi < '2022-07-03'"],
            ],

            // 4. SELECT >= AND
            [
                'db_name' => 'sqlearn_dokter_hewan',
                'question' => '<p> Buatkan hasil eksekusi kueri berikut: </p><p> SELECT id_user, nama_peliharaan, jenis_peliharaan, id_pelayanan FROM reservasi WHERE id_user >= 4 AND id_pelayanan >= 5 </p>',
                'answer' => ['queries' => "SELECT id_user, nama_peliharaan, jenis_peliharaan, id_pelayanan FROM reservasi WHERE id_user >= 4 AND id_pelayanan >= 5"],
            ],

            // 5. SELECT <=
            [
                'db_name' => 'sqlearn_library',
                'question' => '<p> Buatkan hasil eksekusi kueri berikut: </p><p> SELECT TITLE, QTY FROM buku WHERE QTY <= 4 </p>',
                'answer' => ['queries' => "SELECT TITLE, QTY FROM buku WHERE QTY <= 4"],
            ],

            // 6. SELECT >= AND <=
            [
                'db_name' => 'sqlearn_showroom',
                'question' => '<p> Buatkan hasil eksekusi kueri berikut: </p><p> SELECT * FROM brand WHERE LENGTH (nama_brand) >= 5 AND LENGTH (nama_brand) <= 10 </p>',
                'answer' => ['queries' => "SELECT * FROM brand WHERE LENGTH (nama_brand) >= 5 AND LENGTH (nama_brand) <= 10"],
            ],

            // 7. SELECT <>
            [
                'db_name' => 'sqlearn_employee',
                'question' => '<p> Buatkan hasil eksekusi kueri berikut: </p><p> SELECT id_divisi, nama_pegawai, jenis_kelamin FROM pegawai WHERE jenis_kelamin <> "Female" </p>',
                'answer' => ['queries' => "SELECT id_divisi, nama_pegawai, jenis_kelamin FROM pegawai WHERE jenis_kelamin <> 'Female'"],
            ],


            // 8. SELECT LIKE
            [
                'db_name' => 'sqlearn_swalayan',
                'question' => '<p> Buatkan hasil eksekusi kueri berikut: </p><p> SELECT * FROM brand WHERE nama_brand LIKE "s%" </p>',
                'answer' => ['queries' => "SELECT * FROM brand WHERE nama_brand LIKE 's%'"],
            ],

            // 9. SELECT OR
            [
                'db_name' => 'sqlearn_showroom',
                'question' => '<p> Buatkan hasil eksekusi kueri berikut: </p><p> SELECT * FROM pelanggan WHERE alamat LIKE "%Yogyakarta%" OR jenis_kelamin  LIKE "%Perempuan%" </p>',
                'answer' => ['queries' => "SELECT * FROM pelanggan WHERE alamat LIKE '%Yogyakarta%' OR jenis_kelamin  LIKE '%Perempuan%'"],
            ],

            // 10. SELECT IN
            [
                'db_name' => 'sqlearn_courses',
                'question' => '<p> Buatkan hasil eksekusi kueri berikut: </p><p> SELECT id, id_user, total, status FROM transaksi WHERE status IN ("failed", "pending") </p>',
                'answer' => ['queries' => "SELECT id, id_user, total, status FROM transaksi WHERE status IN ('failed', 'pending')"],
            ],

            // 11. SELECT AND
            [
                'db_name' => 'sqlearn_swalayan',
                'question' => '<p> Buatkan hasil eksekusi kueri berikut: </p><p> SELECT * FROM brand WHERE id = 7 AND nama_brand = "Wardah" </p>',
                'answer' => ['queries' => "SELECT * FROM brand WHERE id = 7 AND nama_brand = 'Wardah'"],
            ],


            // 12. SELECT NOT
            [
                'db_name' => 'sqlearn_employee',
                'question' => '<p> Buatkan hasil eksekusi kueri berikut: </p><p> SELECT * FROM divisi WHERE nama_divisi NOT "Pollich Ltd" </p>',
                'answer' => ['queries' => "SELECT * FROM divisi WHERE nama_divisi != 'Pollich Ltd'"],
            ],


            // 13. SELECT EXIST
            [
                'db_name' => 'sqlearn_dokter_hewan',
                'question' => '<p> Buatkan hasil eksekusi kueri berikut: </p><p> SELECT id, nama_pelayanan, harga FROM pelayanan p WHERE harga > 100000 AND EXISTS (SELECT * FROM pelayanan WHERE id = p.id)</p>',
                'answer' => ['queries' => "SELECT id, nama_pelayanan, harga FROM pelayanan p WHERE harga > 100000 AND EXISTS (SELECT * FROM pelayanan WHERE id = p.id)"],
            ],

            // 14. SELECT IS NULL
            [
                'db_name' => 'sqlearn_library',
                'question' => '<p> Buatkan hasil eksekusi kueri berikut: </p><p> SELECT ID_DIPINJAM, ID_PINJAM, ID_BUKU, TGL_KEMBALI FROM detail_pinjam WHERE TGL_KEMBALI IS NULL</p>',
                'answer' => ['queries' => "SELECT ID_DIPINJAM, ID_PINJAM, ID_BUKU, TGL_KEMBALI FROM detail_pinjam WHERE TGL_KEMBALI IS NULL"],
            ],

            // 15. SELECT BETWEEN
            [
                'db_name' => 'sqlearn_dokter_hewan',
                'question' => '<p> Buatkan hasil eksekusi kueri berikut: </p><p> SELECT * FROM pelayanan WHERE harga BETWEEN 50000 AND 125000 </p>',
                'answer' => ['queries' => 'SELECT * FROM pelayanan WHERE harga BETWEEN 50000 AND 125000'],
            ],


            // 16. SELECT IS NULL OR KOMBINASI
            [
                'db_name' => 'sqlearn_courses',
                'question' => '<p> Buatkan hasil eksekusi kueri berikut: </p><p> SELECT id, email, nomor_telepon, jenis_kelamin, tanggal_lahir FROM user WHERE jenis_kelamin IS NULL OR tanggal_lahir IS NULL </p>',
                'answer' => ['queries' => "SELECT id, email, nomor_telepon, jenis_kelamin, tanggal_lahir FROM user WHERE jenis_kelamin IS NULL OR tanggal_lahir IS NULL"],
            ],

            // 17. SELECT <= AND LIKE
            [
                'db_name' => 'sqlearn_showroom',
                'question' => '<p> Buatkan hasil eksekusi kueri berikut: </p><p> SELECT id, nama_mobil, tahun, bahan_bakar FROM mobil WHERE tahun >= 2022 AND bahan_bakar LIKE "b%" </p>',
                'answer' => ['queries' => "SELECT id, nama_mobil, tahun, bahan_bakar FROM mobil WHERE tahun >= 2022 AND bahan_bakar LIKE 'b%'"],
            ],

            // 18. SELECT LIKE <>
            [
                'db_name' => 'sqlearn_swalayan',
                'question' => '<p> Buatkan hasil eksekusi kueri berikut: </p><p> SELECT * FROM kategori WHERE nama_kategori LIKE "%n" and id <> 2 </p>',
                'answer' => ['queries' => "SELECT * FROM kategori WHERE nama_kategori LIKE '%n' and id <> 2"],
            ],

            // 19. SELECT NOT IN KOMBINASI
            [
                'db_name' => 'sqlearn_courses',
                'question' => '<p> Buatkan hasil eksekusi kueri berikut: </p><p> SELECT * FROM kursus WHERE pembuat NOT IN ("Ari Kamayanti", "Dr.Aji Dedi Mulawarman") </p>',
                'answer' => ['queries' => "SELECT * FROM kursus WHERE pembuat NOT IN ('Ari Kamayanti', 'Dr.Aji Dedi Mulawarman')"],
            ],

            // 20. SELECT < >= OR KOMBINASI
            [
                'db_name' => 'sqlearn_swalayan',
                'question' => '<p> Buatkan hasil eksekusi kueri berikut: </p><p> SELECT nama_produk, stock, harga FROM produk WHERE stock < 5 OR harga >= 100000 </p>',
                'answer' => ['queries' => "SELECT nama_produk, stock, harga FROM produk WHERE stock < 5 OR harga >= 100000"],
            ],


            // SOAL CADANGAN

            // 1. SELECT WHERE
            // [
            //     'db_name' => 'sqlearn_library',
            //     'question' => '<p> Buatkan hasil eksekusi kueri berikut: </p><p> SELECT * FROM buku WHERE YEAR="2016" </p>',
            //     'answer' => ['queries' => "SELECT * FROM buku WHERE YEAR= '2016'"],
            // ],
            // [
            //     'db_name' => 'sqlearn_library',
            //     'question' => '<p> Buatkan hasil eksekusi kueri berikut: </p><p> SELECT * FROM peminjaman WHERE TGL_PINJAM="2017-06-24" </p>',
            //     'answer' => ['queries' => "SELECT * FROM peminjaman WHERE TGL_PINJAM= '2017-06-24'"],
            // ],

            // 2. SELECT >
            // [
            //     'db_name' => 'sqlearn_library',
            //     'question' => '<p> Buatkan hasil eksekusi kueri berikut: </p><p> SELECT * FROM buku WHERE QTY "3" </p>',
            //     'answer' => ['queries' => "SELECT * FROM buku WHERE QTY > 3"],
            // ],
            // [
            //     'db_name' => 'sqlearn_library',
            //     'question' => '<p> Buatkan hasil eksekusi kueri berikut: </p><p> SELECT * FROM buku WHERE KELUAR >"2" </p>',
            //     'answer' => ['queries' => 'SELECT * FROM buku WHERE KELUAR > 2'],
            // ],

            // 3. SELECT <
            // [
            //     'db_name' => 'sqlearn_library',
            //     'question' => '<p> Buatkan hasil eksekusi kueri berikut: </p><p> SELECT * FROM peminjaman WHERE JML_BUKU <"2" </p>',
            //     'answer' => ['queries' => "SELECT * FROM peminjaman WHERE JML_BUKU < 2"],
            // ],
            // [
            //     'db_name' => 'sqlearn_library',
            //     'question' => '<p> Buatkan hasil eksekusi kueri berikut: </p><p> SELECT * FROM buku WHERE QTY <"3" </p>',
            //     'answer' => ['queries' => "SELECT * FROM buku WHERE QTY < 3"],
            // ],

            // 4. SELECT >=
            // [
            //     'db_name' => 'sqlearn_library',
            //     'question' => '<p> Buatkan hasil eksekusi kueri berikut: </p><p> SELECT * FROM buku WHERE YEAR >="2013" </p>',
            //     'answer' => ['queries' => "SELECT * FROM buku WHERE YEAR >= '2013'"],
            // ],
            // [
            //     'db_name' => 'sqlearn_library',
            //     'question' => '<p> Buatkan hasil eksekusi kueri berikut: </p><p> SELECT * FROM buku WHERE KELUAR >="2" </p>',
            //     'answer' => ['queries' => "SELECT * FROM buku WHERE KELUAR >= 2"],
            // ],

            // 5. SELECT <=
            // [
            //     'db_name' => 'sqlearn_library',
            //     'question' => '<p> Buatkan hasil eksekusi kueri berikut: </p><p> SELECT * FROM buku WHERE YEAR <="2014" </p>',
            //     'answer' => ['queries' => "SELECT * FROM buku WHERE YEAR <= 2014"],
            // ],
            // [
            //     'db_name' => 'sqlearn_library',
            //     'question' => '<p> Buatkan hasil eksekusi kueri berikut: </p><p> SELECT * FROM buku WHERE KELUAR <="3" </p>',
            //     'answer' => ['queries' => "SELECT * FROM buku WHERE KELUAR <= 3"],
            // ],

            // 6. SELECT <>
            // [
            //     'db_name' => 'sqlearn_library',
            //     'question' => '<p> Buatkan hasil eksekusi kueri berikut: </p><p> SELECT * FROM buku WHERE KELUAR <>"3" </p>',
            //     'answer' => ['queries' => 'SELECT * FROM buku WHERE KELUAR <> 3'],
            // ],

            // 7. SELECT IN
            // [
            //     'db_name' => 'sqlearn_courses',
            //     'question' => '<p> Buatkan hasil eksekusi kueri berikut: </p><p> SELECT * FROM kategori WHERE id IN (1, 3, 5) </p>',
            //     'answer' => ['queries' => "SELECT * FROM kategori WHERE id IN (1, 3, 5)"],
            // ],

            // 8. SELECT LIKE
            // [
            //     'db_name' => 'sqlearn_showroom',
            //     'question' => '<p> Buatkan hasil eksekusi kueri berikut: </p><p> SELECT * FROM mobil WHERE nama_mobil LIKE "%b%" </p>',
            //     'answer' => ['queries' => "SELECT * FROM mobil WHERE nama_mobil LIKE '%b%'"],
            // ],

            // 9. SELECT IN
            // [
            //     'db_name' => 'sqlearn_swalayan',
            //     'question' => '<p> Buatkan hasil eksekusi kueri berikut: </p><p> SELECT * FROM kategori WHERE id IN (1, 3, 5) </p>',
            //     'answer' => ['queries' => "SELECT * FROM kategori WHERE id IN (1, 3, 5)"],
            // ],
        ];

        $package = Package::select('id')
            ->whereRelation('topic', 'name', 'SQL Query Select')
            ->first();

        $database = Database::select('name', 'id')->get();

        // insert to exercises table
        foreach ($exercises as $index => $exercise) {
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
