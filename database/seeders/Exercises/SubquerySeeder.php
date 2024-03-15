<?php

namespace Database\Seeders\Exercises;

use App\Models\Database;
use App\Models\Exercise;
use App\Models\Package;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SubquerySeeder extends Seeder
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
                'question' => "<p> Buatkan hasil eksekusi kueri berikut: </p><p> SELECT * FROM buku WHERE QTY = (SELECT MIN(jml_buku) FROM peminjaman) </p>",
                'answer' => ['queries' => "SELECT * FROM buku WHERE QTY = (SELECT MIN(jml_buku) FROM peminjaman)"],
            ],
            [
                'db_name' => 'sqlearn_library',
                'question' => "<p> Buatkan hasil eksekusi kueri berikut: </p><p> SELECT * FROM detail_pinjam WHERE DENDA > (SELECT AVG(DENDA) FROM detail_pinjam) </p>",
                'answer' => ['queries' => "SELECT * FROM detail_pinjam WHERE DENDA > (SELECT AVG(DENDA) FROM detail_pinjam)"],
            ],
            [
                'db_name' => 'sqlearn_library',
                'question' => "<p> Buatkan hasil eksekusi kueri berikut: </p><p> SELECT SUM(QTY) FROM buku WHERE QTY < (SELECT JML_BUKU FROM peminjaman WHERE ID_PINJAM = 'P170623001') </p>",
                'answer' => ['queries' => "SELECT SUM(QTY) FROM buku WHERE QTY < (SELECT JML_BUKU FROM peminjaman WHERE ID_PINJAM = 'P170623001')"],
            ],
            [
                'db_name' => 'sqlearn_library',
                'question' => "<p> Buatkan hasil eksekusi kueri berikut: </p><p> SELECT ID_PINJAM, TGL_PINJAM, JML_BUKU, STATS FROM peminjaman WHERE JML_BUKU = (SELECT MAX(KELUAR) FROM buku) </p>",
                'answer' => ['queries' => "SELECT ID_PINJAM, TGL_PINJAM, JML_BUKU, STATS FROM peminjaman WHERE JML_BUKU = (SELECT MAX(KELUAR) FROM buku)"],
            ],
            [
                'db_name' => 'sqlearn_library',
                'question' => "<p> Buatkan hasil eksekusi kueri berikut: </p><p> SELECT * FROM admin WHERE ID_ADMIN IN (SELECT ID_ADMIN FROM peminjaman) </p>",
                'answer' => ['queries' => "SELECT * FROM admin WHERE ID_ADMIN IN (SELECT ID_ADMIN FROM peminjaman)"],
            ],
            [
                'db_name' => 'sqlearn_library',
                'question' => "<p> Buatkan hasil eksekusi kueri berikut: </p><p> SELECT COUNT(*) FROM anggota WHERE ID_ANGGOTA NOT IN (SELECT ID_ANGGOTA FROM peminjaman) </p>",
                'answer' => ['queries' => "SELECT COUNT(*) FROM anggota WHERE ID_ANGGOTA NOT IN (SELECT ID_ANGGOTA FROM peminjaman) "],
            ],
            [
                'db_name' => 'sqlearn_swalayan',
                'question' => "<p> Buatkan hasil eksekusi kueri berikut: </p><p> SELECT nama_produk, stock FROM produk WHERE stock >= (SELECT AVG(stock) FROM produk) </p>",
                'answer' => ['queries' => "SELECT nama_produk, stock FROM produk WHERE stock >= (SELECT AVG(stock) FROM produk)"],
            ],
            [
                'db_name' => 'sqlearn_swalayan',
                'question' => "<p> Buatkan hasil eksekusi kueri berikut: </p><p> SELECT COUNT(id) FROM produk WHERE id_kategori <= (SELECT id FROM kategori WHERE nama_kategori = 'Mainan') </p>",
                'answer' => ['queries' => "SELECT COUNT(id) FROM produk WHERE id_kategori <= (SELECT id FROM kategori WHERE nama_kategori = 'Mainan')"],
            ],
            [
                'db_name' => 'sqlearn_swalayan',
                'question' => "<p> Buatkan hasil eksekusi kueri berikut: </p><p> SELECT nama_produk, harga FROM produk WHERE harga <> (SELECT MIN(harga) FROM produk) </p>",
                'answer' => ['queries' => "SELECT nama_produk, harga FROM produk WHERE harga <> (SELECT MIN(harga) FROM produk)"],
            ],
            [
                'db_name' => 'sqlearn_swalayan',
                'question' => "<p> Buatkan hasil eksekusi kueri berikut: </p><p> SELECT * FROM brand WHERE id IN (SELECT id_brand FROM produk) </p>",
                'answer' => ['queries' => "SELECT * FROM brand WHERE id IN (SELECT id_brand FROM produk)"],
            ],
            [
                'db_name' => 'sqlearn_swalayan',
                'question' => "<p> Buatkan hasil eksekusi kueri berikut: </p><p> SELECT * FROM kategori WHERE id NOT IN (SELECT id_kategori FROM produk) </p>",
                'answer' => ['queries' => "SELECT * FROM kategori WHERE id NOT IN (SELECT id_kategori FROM produk) "],
            ],
            [
                'db_name' => 'sqlearn_employee',
                'question' => "<p> Buatkan hasil eksekusi kueri berikut: </p><p> SELECT nip, nama_pegawai FROM pegawai WHERE nip IN (SELECT nip FROM pegawai WHERE jenis_kelamin = 'Female') </p>",
                'answer' => ['queries' => "SELECT nip, nama_pegawai FROM pegawai WHERE nip IN (SELECT nip FROM pegawai WHERE jenis_kelamin = 'Female')"],
            ],
            [
                'db_name' => 'sqlearn_employee',
                'question' => "<p> Buatkan hasil eksekusi kueri berikut: </p><p> SELECT COUNT(id) FROM divisi WHERE id NOT IN (SELECT id_divisi FROM pegawai) </p>",
                'answer' => ['queries' => "SELECT COUNT(id) FROM divisi WHERE id NOT IN (SELECT id_divisi FROM pegawai)"],
            ],
            [
                'db_name' => 'sqlearn_employee',
                'question' => "<p> Buatkan hasil eksekusi kueri berikut: </p><p> SELECT nama_divisi FROM divisi WHERE id = (SELECT id_divisi FROM pegawai WHERE jabatan = 'Podiatrist') </p>",
                'answer' => ['queries' => "SELECT nama_divisi FROM divisi WHERE id = (SELECT id_divisi FROM pegawai WHERE jabatan = 'Podiatrist')"],
            ],
            [
                'db_name' => 'sqlearn_employee',
                'question' => "<p> Buatkan hasil eksekusi kueri berikut: </p><p> SELECT * FROM divisi WHERE id IN (SELECT id_divisi FROM pegawai) </p>",
                'answer' => ['queries' => "SELECT * FROM divisi WHERE id IN (SELECT id_divisi FROM pegawai)"],
            ],
            [
                'db_name' => 'sqlearn_employee',
                'question' => "<p> Buatkan hasil eksekusi kueri berikut: </p><p> SELECT * FROM divisi WHERE id NOT IN (SELECT id_divisi FROM pegawai WHERE jenis_kelamin = 'Male') </p>",
                'answer' => ['queries' => "SELECT * FROM divisi WHERE id NOT IN (SELECT id_divisi FROM pegawai WHERE jenis_kelamin = 'Male')"],
            ],
            [
                'db_name' => 'sqlearn_courses',
                'question' => "<p> Buatkan hasil eksekusi kueri berikut: </p><p> SELECT judul, pembuat, harga FROM kursus WHERE harga > (SELECT AVG(harga) FROM kursus) </p>",
                'answer' => ['queries' => "SELECT judul, pembuat, harga FROM kursus WHERE harga > (SELECT AVG(harga) FROM kursus)"],
            ],
            [
                'db_name' => 'sqlearn_courses',
                'question' => "<p> Buatkan hasil eksekusi kueri berikut: </p><p> SELECT SUM(total) FROM transaksi WHERE total < (SELECT total FROM transaksi WHERE id = 3) </p>",
                'answer' => ['queries' => "SELECT SUM(total) FROM transaksi WHERE total < (SELECT total FROM transaksi WHERE id = 3)"],
            ],
            [
                'db_name' => 'sqlearn_courses',
                'question' => "<p> Buatkan hasil eksekusi kueri berikut: </p><p> SELECT id_user, total, status FROM transaksi WHERE total >= (SELECT MIN(total) FROM transaksi) </p>",
                'answer' => ['queries' => "SELECT id_user, total, status FROM transaksi WHERE total >= (SELECT MIN(total) FROM transaksi)"],
            ],
            [
                'db_name' => 'sqlearn_courses',
                'question' => "<p> Buatkan hasil eksekusi kueri berikut: </p><p> SELECT COUNT(id) FROM transaksi WHERE total <= (SELECT harga FROM kursus WHERE id = 6) </p>",
                'answer' => ['queries' => "SELECT COUNT(id) FROM transaksi WHERE total <= (SELECT harga FROM kursus WHERE id = 6)"],
            ],
            [
                'db_name' => 'sqlearn_showroom',
                'question' => "<p> Buatkan hasil eksekusi kueri berikut: </p><p> SELECT nama_mobil, tahun, harga, transmisi, bahan_bakar FROM mobil WHERE tahun <> (SELECT MAX(tahun) FROM mobil) </p>",
                'answer' => ['queries' => "SELECT nama_mobil, tahun, harga, transmisi, bahan_bakar FROM mobil WHERE tahun <> (SELECT MAX(tahun) FROM mobil)"],
            ],
            [
                'db_name' => 'sqlearn_showroom',
                'question' => "<p> Buatkan hasil eksekusi kueri berikut: </p><p> SELECT no_ktp, nama, jenis_kelamin FROM pelanggan WHERE id IN (SELECT id_pelanggan FROM transaksi) </p>",
                'answer' => ['queries' => "SELECT no_ktp, nama, jenis_kelamin FROM pelanggan WHERE id IN (SELECT id_pelanggan FROM transaksi)"],
            ],
            [
                'db_name' => 'sqlearn_showroom',
                'question' => "<p> Buatkan hasil eksekusi kueri berikut: </p><p> SELECT no_ktp, nama FROM pelanggan WHERE id NOT IN (SELECT id_pelanggan FROM transaksi)</p>",
                'answer' => ['queries' => "SELECT no_ktp, nama FROM pelanggan WHERE id NOT IN (SELECT id_pelanggan FROM transaksi)"],
            ],
            [
                'db_name' => 'sqlearn_showroom',
                'question' => "<p> Buatkan hasil eksekusi kueri berikut: </p><p> SELECT no_nota, jenis_pembayaran, status FROM transaksi WHERE id_mobil = (SELECT id FROM mobil WHERE id = 4) </p>",
                'answer' => ['queries' => "SELECT no_nota, jenis_pembayaran, status FROM transaksi WHERE id_mobil = (SELECT id FROM mobil WHERE id = 4)"],
            ],
            [
                'db_name' => 'sqlearn_showroom',
                'question' => "<p> Buatkan hasil eksekusi kueri berikut: </p><p> SELECT nama_mobil, tahun, transmisi, kapasitas FROM mobil WHERE kapasitas > (SELECT AVG(kapasitas) FROM mobil) </p>",
                'answer' => ['queries' => "SELECT nama_mobil, tahun, transmisi, kapasitas FROM mobil WHERE kapasitas > (SELECT AVG(kapasitas) FROM mobil)"],
            ],
            [
                'db_name' => 'sqlearn_dokter_hewan',
                'question' => "<p> Buatkan hasil eksekusi kueri berikut: </p><p> SELECT nama_pelayanan, harga FROM pelayanan WHERE harga < (SELECT MAX(harga) FROM pelayanan)  </p>",
                'answer' => ['queries' => "SELECT nama_pelayanan, harga FROM pelayanan WHERE harga < (SELECT MAX(harga) FROM pelayanan)"],
            ],
            [
                'db_name' => 'sqlearn_dokter_hewan',
                'question' => "<p> Buatkan hasil eksekusi kueri berikut: </p><p> SELECT AVG(harga) FROM pelayanan WHERE id >= (SELECT id_pelayanan FROM reservasi WHERE id = 2)  </p>",
                'answer' => ['queries' => "SELECT AVG(harga) FROM pelayanan WHERE id >= (SELECT id_pelayanan FROM reservasi WHERE id = 2)"],
            ],
            [
                'db_name' => 'sqlearn_dokter_hewan',
                'question' => "<p> Buatkan hasil eksekusi kueri berikut: </p><p> SELECT COUNT(*) FROM reservasi WHERE id IN (SELECT id FROM reservasi WHERE status = 'Accepted') </p>",
                'answer' => ['queries' => "SELECT COUNT(*) FROM reservasi WHERE id IN (SELECT id FROM reservasi WHERE status = 'Accepted')"],
            ],
            [
                'db_name' => 'sqlearn_dokter_hewan',
                'question' => "<p> Buatkan hasil eksekusi kueri berikut: </p><p> SELECT * FROM pelayanan WHERE id IN (SELECT id_pelayanan FROM reservasi) </p>",
                'answer' => ['queries' => "SELECT * FROM pelayanan WHERE id IN (SELECT id_pelayanan FROM reservasi)"],
            ],
            [
                'db_name' => 'sqlearn_dokter_hewan',
                'question' => "<p> Buatkan hasil eksekusi kueri berikut: </p><p> SELECT * FROM user WHERE id NOT IN (SELECT id_user FROM reservasi) </p>",
                'answer' => ['queries' => "SELECT * FROM user WHERE id NOT IN (SELECT id_user FROM reservasi)"],
            ],
        ];

        $package = Package::select('id')
            ->whereRelation('topic', 'name', 'SQL Subquery')
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
