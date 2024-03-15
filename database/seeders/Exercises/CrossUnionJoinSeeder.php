<?php

namespace Database\Seeders\Exercises;

use App\Models\Database;
use App\Models\Exercise;
use App\Models\Package;
use Illuminate\Database\Seeder;

class CrossUnionJoinSeeder extends Seeder
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
                // CROSS JOIN
                'db_name' => 'sqlearn_library',
                'question' => '<p> Buatkan hasil eksekusi kueri berikut: </p><p> SELECT p.ID_ADMIN, b.PUBLISHER FROM peminjaman p CROSS JOIN buku b LIMIT 5 </p>',
                'answer' => ['queries' => 'SELECT p.ID_ADMIN, b.PUBLISHER FROM peminjaman p CROSS JOIN buku b LIMIT 5'],
            ],
            [
                // UNION
                'db_name' => 'sqlearn_showroom',
                'question' => '<p> Buatkan hasil eksekusi kueri berikut: </p><p> SELECT nama_brand, deskripsi_brand FROM brand UNION SELECT nama_mobil, tahun FROM mobil LIMIT 5 </p>',
                'answer' => ['queries' => 'SELECT nama_brand, deskripsi_brand FROM brand UNION SELECT nama_mobil, tahun FROM mobil LIMIT 5'],
            ],
            [
                // UNION
                'db_name' => 'sqlearn_swalayan',
                'question' => '<p> Buatkan hasil eksekusi kueri berikut: </p><p> SELECT nama_kategori FROM kategori UNION SELECT nama_brand FROM brand LIMIT 5 </p>',
                'answer' => ['queries' => 'SELECT nama_kategori FROM kategori UNION SELECT nama_brand FROM brand LIMIT 5'],
            ],
            [
                // UNION ALL
                'db_name' => 'sqlearn_library',
                'question' => '<p> Buatkan hasil eksekusi kueri berikut: </p><p> SELECT ID_BUKU, PUBLISHER FROM buku UNION ALL SELECT ID_ANGGOTA, TGL_PINJAM FROM peminjaman LIMIT 5 </p>',
                'answer' => ['queries' => 'SELECT ID_BUKU, PUBLISHER FROM buku UNION ALL SELECT ID_ANGGOTA, TGL_PINJAM FROM peminjaman LIMIT 5'],
            ],
            [
                // CROSS JOIN
                'db_name' => 'sqlearn_courses',
                'question' => '<p> Buatkan hasil eksekusi kueri berikut: </p><p> SELECT * FROM kursus CROSS JOIN transaksi LIMIT 5 </p>',
                'answer' => ['queries' => 'SELECT * FROM kursus CROSS JOIN transaksi LIMIT 5'],
            ],
            [
                // CROSS JOIN
                'db_name' => 'sqlearn_dokter_hewan',
                'question' => '<p> Buatkan hasil eksekusi kueri berikut: </p><p> SELECT nama_pelayanan, harga FROM pelayanan CROSS JOIN reservasi LIMIT 5 </p>',
                'answer' => ['queries' => 'SELECT nama_pelayanan, harga FROM pelayanan CROSS JOIN reservasi LIMIT 5'],
            ],
            [
                // UNION ALL
                'db_name' => 'sqlearn_showroom',
                'question' => '<p> Buatkan hasil eksekusi kueri berikut: </p><p> SELECT nama_brand, deskripsi_brand FROM brand UNION ALL SELECT nama_mobil, tahun FROM mobil LIMIT 5 </p>',
                'answer' => ['queries' => 'SELECT nama_brand, deskripsi_brand FROM brand UNION ALL SELECT nama_mobil, tahun FROM mobil LIMIT 5'],
            ],
            [
                // CROSS JOIN
                'db_name' => 'sqlearn_library',
                'question' => '<p> Buatkan hasil eksekusi kueri berikut: </p><p> SELECT * FROM buku CROSS JOIN peminjaman LIMIT 5 </p>',
                'answer' => ['queries' => 'SELECT * FROM buku CROSS JOIN peminjaman LIMIT 5'],
            ],
            [
                // UNION
                'db_name' => 'sqlearn_library',
                'question' => '<p> Buatkan hasil eksekusi kueri berikut: </p><p> SELECT TITLE, AUTHOR FROM buku UNION SELECT ID_ANGGOTA, STATS FROM peminjaman LIMIT 5 </p>',
                'answer' => ['queries' => 'SELECT TITLE, AUTHOR FROM buku UNION SELECT ID_ANGGOTA, STATS FROM peminjaman LIMIT 5'],
            ],
            [
                // UNION
                'db_name' => 'sqlearn_library',
                'question' => '<p> Buatkan hasil eksekusi kueri berikut: </p><p> SELECT ID_PINJAM FROM peminjaman UNION SELECT ID_BUKU FROM buku LIMIT 5 </p>',
                'answer' => ['queries' => 'SELECT ID_PINJAM FROM peminjaman UNION SELECT ID_BUKU FROM buku LIMIT 5'],
            ],
            [
                // CROSS JOIN
                'db_name' => 'sqlearn_library',
                'question' => '<p> Buatkan hasil eksekusi kueri berikut: </p><p> SELECT * FROM buku CROSS JOIN anggota LIMIT 5 </p>',
                'answer' => ['queries' => 'SELECT * FROM buku CROSS JOIN anggota LIMIT 5'],
            ],
            [
                // UNION
                'db_name' => 'sqlearn_library',
                'question' => '<p> Buatkan hasil eksekusi kueri berikut: </p><p> SELECT ID_PINJAM, ID_ANGGOTA FROM peminjaman UNION SELECT ID_BUKU, QTY FROM buku LIMIT 5 </p>',
                'answer' => ['queries' => 'SELECT ID_PINJAM, ID_ANGGOTA FROM peminjaman UNION SELECT ID_BUKU, QTY FROM buku LIMIT 5'],
            ],
            [
                // UNION ALL
                'db_name' => 'sqlearn_swalayan',
                'question' => '<p> Buatkan hasil eksekusi kueri berikut: </p><p> SELECT nama_kategori FROM kategori UNION ALL SELECT nama_brand FROM brand LIMIT 5 </p>',
                'answer' => ['queries' => 'SELECT nama_kategori FROM kategori UNION ALL SELECT nama_brand FROM brand LIMIT 5'],
            ],
            [
                // UNION ALL
                'db_name' => 'sqlearn_library',
                'question' => '<p> Buatkan hasil eksekusi kueri berikut: </p><p> SELECT ID_BUKU, TITLE FROM buku UNION ALL SELECT ID_PINJAM, TGL_PINJAM FROM peminjaman LIMIT 5 </p>',
                'answer' => ['queries' => 'SELECT ID_BUKU, TITLE FROM buku UNION ALL SELECT ID_PINJAM, TGL_PINJAM FROM peminjaman LIMIT 5'],
            ],
            [
                // CROSS JOIN
                'db_name' => 'sqlearn_library',
                'question' => '<p> Buatkan hasil eksekusi kueri berikut: </p><p> SELECT p.ID_PINJAM, p.ID_ANGGOTA, b.TITLE, b.AUTHOR FROM peminjaman p CROSS JOIN buku b LIMIT 5 </p>',
                'answer' => ['queries' => 'SELECT p.ID_PINJAM, p.ID_ANGGOTA, b.TITLE, b.AUTHOR FROM peminjaman p CROSS JOIN buku b LIMIT 5'],
            ],
            [
                // UNION
                'db_name' => 'sqlearn_library',
                'question' => '<p> Buatkan hasil eksekusi kueri berikut: </p><p> SELECT ID_PINJAM, ID_ANGGOTA FROM peminjaman UNION SELECT ID_BUKU, QTY FROM buku UNION SELECT ID_ANGGOTA, FULL_NAME FROM anggota LIMIT 5 </p>',
                'answer' => ['queries' => 'SELECT ID_PINJAM, ID_ANGGOTA FROM peminjaman UNION SELECT ID_BUKU, QTY FROM buku UNION SELECT ID_ANGGOTA, FULL_NAME FROM anggota LIMIT 5'],
            ],
            [
                // UNION
                'db_name' => 'sqlearn_library',
                'question' => '<p> Buatkan hasil eksekusi kueri berikut: </p><p> SELECT QTY FROM buku UNION SELECT jml_buku FROM peminjaman LIMIT 5 </p>',
                'answer' => ['queries' => 'SELECT QTY FROM buku UNION SELECT jml_buku FROM peminjaman LIMIT 5'],
            ],
            [
                // CROSS JOIN
                'db_name' => 'sqlearn_employee',
                'question' => '<p> Buatkan hasil eksekusi kueri berikut: </p><p> SELECT p.nip, p.nama_pegawai, d.nama_divisi FROM pegawai p CROSS JOIN divisi d WHERE p.id_divisi = d.id LIMIT 5 </p>',
                'answer' => ['queries' => 'SELECT p.nip, p.nama_pegawai, d.nama_divisi FROM pegawai p CROSS JOIN divisi d WHERE p.id_divisi = d.id LIMIT 5'],
            ],
            [
                // UNION ALL
                'db_name' => 'sqlearn_library',
                'question' => '<p> Buatkan hasil eksekusi kueri berikut: </p><p> SELECT ID_BUKU, TITLE FROM buku UNION ALL SELECT ID_PINJAM, TGL_PINJAM FROM peminjaman UNION ALL SELECT ID_ANGGOTA, FULL_NAME FROM ANGGOTA LIMIT 5 </p>',
                'answer' => ['queries' => 'SELECT ID_BUKU, TITLE FROM buku UNION ALL SELECT ID_PINJAM, TGL_PINJAM FROM peminjaman UNION ALL SELECT ID_ANGGOTA, FULL_NAME FROM ANGGOTA LIMIT 5'],
            ],
            [
                // UNION ALL
                'db_name' => 'sqlearn_library',
                'question' => '<p> Buatkan hasil eksekusi kueri berikut: </p><p> SELECT ID_BUKU, TITLE FROM buku UNION ALL SELECT ID_PINJAM, TGL_PINJAM FROM peminjaman UNION ALL SELECT ID_ANGGOTA, FULL_NAME FROM ANGGOTA UNION ALL SELECT ID_BUKU, PUBLISHER FROM buku LIMIT 5 </p>',
                'answer' => ['queries' => 'SELECT ID_BUKU, TITLE FROM buku UNION ALL SELECT ID_PINJAM, TGL_PINJAM FROM peminjaman UNION ALL SELECT ID_ANGGOTA, FULL_NAME FROM ANGGOTA UNION ALL SELECT ID_BUKU, PUBLISHER FROM buku LIMIT 5'],
            ],
            [
                // CROSS JOIN
                'db_name' => 'sqlearn_library',
                'question' => '<p> Buatkan hasil eksekusi kueri berikut: </p><p> SELECT p.ID_PINJAM, d.ID_DIPINJAM, d.ID_BUKU FROM peminjaman p CROSS JOIN detail_pinjam d WHERE p.ID_PINJAM = d.ID_PINJAM LIMIT 5 </p>',
                'answer' => ['queries' => 'SELECT p.ID_PINJAM, d.ID_DIPINJAM, d.ID_BUKU FROM peminjaman p CROSS JOIN detail_pinjam d WHERE p.ID_PINJAM = d.ID_PINJAM LIMIT 5'],
            ],
            [
                // UNION
                'db_name' => 'sqlearn_showroom',
                'question' => '<p> Buatkan hasil eksekusi kueri berikut: </p><p> SELECT id, nama_mobil AS nama_produk FROM mobil UNION SELECT id, nama_brand AS nama_produk FROM brand LIMIT 5 </p>',
                'answer' => ['queries' => 'SELECT id, nama_mobil AS nama_produk FROM mobil UNION SELECT id, nama_brand AS nama_produk FROM brand LIMIT 5'],
            ],
            [
                // UNION ALL
                'db_name' => 'sqlearn_swalayan',
                'question' => '<p> Buatkan hasil eksekusi kueri berikut: </p><p> SELECT stock FROM produk UNION ALL SELECT nama_kategori FROM kategori LIMIT 5 </p>',
                'answer' => ['queries' => 'SELECT stock FROM produk UNION ALL SELECT nama_kategori FROM kategori LIMIT 5'],
            ],
        ];

        $package = Package::select('id')
            ->whereRelation('topic', 'name', 'SQL Query Union dan Cross Join')
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
