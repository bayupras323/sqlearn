<?php

namespace Database\Seeders\Exercises;

use App\Models\Database;
use App\Models\Exercise;
use App\Models\Package;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class InnerOuterJoinSeeder extends Seeder
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
                'db_name' => 'sqlearn_courses', // 1. INNER JOIN
                'question' => '<p> Buatkan hasil eksekusi kueri berikut: </p><p> SELECT user.nama_user, user.email, transaksi.total, transaksi.tanggal_transaksi FROM transaksi INNER JOIN user ON transaksi.id_user = user.id </p>',
                'answer' => ['queries' => 'SELECT user.nama_user, user.email, transaksi.total, transaksi.tanggal_transaksi FROM transaksi INNER JOIN user ON transaksi.id_user = user.id'],
            ],
            [
                'db_name' => 'sqlearn_library', // 2. LEFT JOIN
                'question' => '<p> Buatkan hasil eksekusi kueri berikut: </p><p> SELECT a.ID_ANGGOTA, a.FULL_NAME, p.TGL_PINJAM FROM anggota a LEFT JOIN peminjaman p ON a.ID_ANGGOTA = p.ID_ANGGOTA </p>',
                'answer' => ['queries' => 'SELECT a.ID_ANGGOTA, a.FULL_NAME, p.TGL_PINJAM FROM anggota a LEFT JOIN peminjaman p ON a.ID_ANGGOTA = p.ID_ANGGOTA'],
            ],
            [
                'db_name' => 'sqlearn_employee', // 3. INNER JOIN
                'question' => '<p> Buatkan hasil eksekusi kueri berikut: </p><p> SELECT p.nama_pegawai, p.jabatan, d.nama_divisi FROM pegawai p INNER JOIN divisi d ON p.id_divisi = d.id </p>',
                'answer' => ['queries' => 'SELECT p.nama_pegawai, p.jabatan, d.nama_divisi FROM pegawai p INNER JOIN divisi d ON p.id_divisi = d.id'],
            ],
            [
                'db_name' => 'sqlearn_showroom', // 4. FULL OUTER JOIN
                'question' => '<p> Buatkan hasil eksekusi kueri berikut: </p><p> SELECT mobil.nama_mobil, brand.nama_brand FROM brand FULL OUTER JOIN mobil ON brand.id = mobil.id_brand </p>',
                'answer' => ['queries' => 'SELECT mobil.nama_mobil, brand.nama_brand FROM brand LEFT JOIN mobil ON brand.id = mobil.id_brand UNION SELECT mobil.nama_mobil, brand.nama_brand FROM brand RIGHT JOIN mobil ON brand.id = mobil.id_brand'],
            ],
            [
                'db_name' => 'sqlearn_dokter_hewan', // 5. RIGHT JOIN
                'question' => '<p> Buatkan hasil eksekusi kueri berikut: </p><p> SELECT r.nama_peliharaan, r.jenis_peliharaan, p.nama_pelayanan, p.harga FROM reservasi r RIGHT JOIN pelayanan p ON r.id_pelayanan = p.id </p>',
                'answer' => ['queries' => 'SELECT r.nama_peliharaan, r.jenis_peliharaan, p.nama_pelayanan, p.harga FROM reservasi r RIGHT JOIN pelayanan p ON r.id_pelayanan = p.id'],
            ],
            [
                'db_name' => 'sqlearn_swalayan', // 6. LEFT JOIN
                'question' => '<p> Buatkan hasil eksekusi kueri berikut: </p><p> SELECT produk.nama_produk, kategori.nama_kategori, produk.harga FROM produk LEFT JOIN kategori ON produk.id_kategori = kategori.id WHERE produk.harga < 50000 </p>',
                'answer' => ['queries' => 'SELECT produk.nama_produk, kategori.nama_kategori, produk.harga FROM produk LEFT JOIN kategori ON produk.id_kategori = kategori.id WHERE produk.harga < 50000'],
            ],
            [
                'db_name' => 'sqlearn_dokter_hewan', // 7. INNER JOIN
                'question' => '<p> Buatkan hasil eksekusi kueri berikut: </p><p> SELECT u.nama, r.jenis_peliharaan, p.nama_pelayanan, p.harga, r.status FROM reservasi r INNER JOIN user u ON r.id_user = u.id INNER JOIN pelayanan p ON r.id_pelayanan = p.id </p>',
                'answer' => ['queries' => 'SELECT u.nama, r.jenis_peliharaan, p.nama_pelayanan, p.harga, r.status FROM reservasi r INNER JOIN user u ON r.id_user = u.id INNER JOIN pelayanan p ON r.id_pelayanan = p.id'],
            ],
            [
                'db_name' => 'sqlearn_library', // 8. RIGHT JOIN
                'question' => '<p> Buatkan hasil eksekusi kueri berikut: </p><p> SELECT p.ID_PINJAM, p.TGL_PINJAM, a.FULLNAME, a.NO_TELP FROM peminjaman p RIGHT JOIN admin a ON p.ID_ADMIN  = a.ID_ADMIN GROUP BY a.FULLNAME </p>',
                'answer' => ['queries' => 'SELECT p.ID_PINJAM, p.TGL_PINJAM, a.FULLNAME, a.NO_TELP FROM peminjaman p RIGHT JOIN admin a ON p.ID_ADMIN  = a.ID_ADMIN GROUP BY a.FULLNAME'],
            ],
            [
                'db_name' => 'sqlearn_swalayan', // 9. FULL OUTER JOIN
                'question' => '<p> Buatkan hasil eksekusi kueri berikut: </p><p> SELECT p.nama_produk, k.nama_kategori, p.harga FROM kategori k FULL OUTER JOIN produk p ON k.id = p.id_kategori GROUP BY k.nama_kategori </p>',
                'answer' => ['queries' => 'SELECT p.nama_produk, k.nama_kategori, p.harga FROM kategori k LEFT JOIN produk p ON k.id = p.id_kategori GROUP BY k.nama_kategori UNION SELECT p.nama_produk, k.nama_kategori, p.harga FROM kategori k RIGHT JOIN produk p ON k.id = p.id_kategori GROUP BY k.nama_kategori'],
            ],
            [
                'db_name' => 'sqlearn_library', // 10. RIGHT JOIN
                'question' => '<p> Buatkan hasil eksekusi kueri berikut: </p><p> SELECT b.TITLE, b.AUTHOR, dp.TGL_KEMBALI FROM detail_pinjam dp RIGHT JOIN buku b ON dp.ID_BUKU = b.ID_BUKU WHERE dp.DENDA = 0 </p>',
                'answer' => ['queries' => 'SELECT b.TITLE, b.AUTHOR, dp.TGL_KEMBALI FROM detail_pinjam dp RIGHT JOIN buku b ON dp.ID_BUKU = b.ID_BUKU WHERE dp.DENDA = 0'],
            ],
            [
                'db_name' => 'sqlearn_library', // 11. INNER JOIN
                'question' => '<p> Buatkan hasil eksekusi kueri berikut: </p><p> SELECT a.FULL_NAME, p.TGL_PINJAM, p.STATS FROM peminjaman p INNER JOIN anggota a ON p.ID_ANGGOTA = a.ID_ANGGOTA </p>',
                'answer' => ['queries' => 'SELECT a.FULL_NAME, p.TGL_PINJAM, p.STATS FROM peminjaman p INNER JOIN anggota a ON p.ID_ANGGOTA = a.ID_ANGGOTA'],
            ],
            [
                'db_name' => 'sqlearn_showroom', // 12. RIGHT JOIN
                'question' => '<p> Buatkan hasil eksekusi kueri berikut: </p><p> SELECT m.nama_mobil, t.tanggal_pemesanan, t.status FROM transaksi t RIGHT JOIN mobil m ON t.id_mobil = m.id </p>',
                'answer' => ['queries' => 'SELECT m.nama_mobil, t.tanggal_pemesanan, t.status FROM transaksi t RIGHT JOIN mobil m ON t.id_mobil = m.id'],
            ],
            [
                'db_name' => 'sqlearn_employee', // 13. FULL OUTER JOIN
                'question' => '<p> Buatkan hasil eksekusi kueri berikut: </p><p> SELECT p.nama_pegawai, p.jenis_kelamin, d.id, d.nama_divisi FROM divisi d FULL OUTER JOIN pegawai p ON p.id_divisi = d.id </p>',
                'answer' => ['queries' => 'SELECT p.nama_pegawai, p.jenis_kelamin, d.id, d.nama_divisi FROM divisi d LEFT JOIN pegawai p ON p.id_divisi = d.id UNION SELECT p.nama_pegawai, p.jenis_kelamin, d.id, d.nama_divisi FROM divisi d RIGHT JOIN pegawai p ON p.id_divisi = d.id'],
            ],
            [
                'db_name' => 'sqlearn_courses', // 14. LEFT JOIN
                'question' => '<p> Buatkan hasil eksekusi kueri berikut: </p><p> SELECT k.judul, k.pembuat, t.tanggal_transaksi FROM kursus k LEFT JOIN detail_transaksi dt ON dt.id_kursus = k.id LEFT JOIN transaksi t ON dt.id_transaksi = t.id GROUP BY k.judul </p>',
                'answer' => ['queries' => 'SELECT k.judul, k.pembuat, t.tanggal_transaksi FROM kursus k LEFT JOIN detail_transaksi dt ON dt.id_kursus = k.id LEFT JOIN transaksi t ON dt.id_transaksi = t.id GROUP BY k.judul'],
            ],
            [
                'db_name' => 'sqlearn_showroom', // 15. INNER JOIN
                'question' => '<p> Buatkan hasil eksekusi kueri berikut: </p><p> SELECT m.nama_mobil, m.tahun, t.tanggal_pemesanan, t.jenis_pembayaran, t.status FROM transaksi t INNER JOIN mobil m ON t.id_mobil = m.id </p>',
                'answer' => ['queries' => 'SELECT m.nama_mobil, m.tahun, t.tanggal_pemesanan, t.jenis_pembayaran, t.status FROM transaksi t INNER JOIN mobil m ON t.id_mobil = m.id'],
            ],
            [
                'db_name' => 'sqlearn_courses', // 16. FULL OUTER JOIN
                'question' => '<p> Buatkan hasil eksekusi kueri berikut: </p><p> SELECT dt.id_transaksi, k.judul, k.harga FROM detail_transaksi dt FULL OUTER JOIN kursus k ON dt.id_kursus = k.id </p>',
                'answer' => ['queries' => 'SELECT dt.id_transaksi, k.judul, k.harga FROM detail_transaksi dt LEFT JOIN kursus k ON dt.id_kursus = k.id UNION SELECT dt.id_transaksi, k.judul, k.harga FROM detail_transaksi dt RIGHT JOIN kursus k ON dt.id_kursus = k.id'],
            ],
            [
                'db_name' => 'sqlearn_swalayan', // 17. INNER JOIN
                'question' => '<p> Buatkan hasil eksekusi kueri berikut: </p><p> SELECT p.nama_produk, b.nama_brand, k.nama_kategori, p.stock FROM produk p INNER JOIN brand b ON p.id_brand = b.id INNER JOIN kategori k ON p.id_kategori = k.id </p>',
                'answer' => ['queries' => 'SELECT p.nama_produk, b.nama_brand, k.nama_kategori, p.stock FROM produk p INNER JOIN brand b ON p.id_brand = b.id INNER JOIN kategori k ON p.id_kategori = k.id'],
            ],
            [
                'db_name' => 'sqlearn_employee', // 18. LEFT JOIN
                'question' => '<p> Buatkan hasil eksekusi kueri berikut: </p><p> SELECT d.id, d.nama_divisi, p.nip, p.nama_pegawai FROM divisi d LEFT JOIN pegawai p ON p.id_divisi = d.id </p>',
                'answer' => ['queries' => 'SELECT d.id, d.nama_divisi, p.nip, p.nama_pegawai FROM divisi d LEFT JOIN pegawai p ON p.id_divisi = d.id'],
            ],
            [
                'db_name' => 'sqlearn_dokter_hewan', // 19. RIGHT JOIN
                'question' => '<p> Buatkan hasil eksekusi kueri berikut: </p><p> SELECT u.nama, r.nama_peliharaan, p.nama_pelayanan FROM reservasi r RIGHT JOIN pelayanan p ON r.id_pelayanan = p.id RIGHT JOIN `user` u ON r.id_user = u.id </p>',
                'answer' => ['queries' => 'SELECT u.nama, r.nama_peliharaan, p.nama_pelayanan FROM reservasi r RIGHT JOIN pelayanan p ON r.id_pelayanan = p.id RIGHT JOIN `user` u ON r.id_user = u.id'],
            ],
            [
                'db_name' => 'sqlearn_showroom', // 20. LEFT JOIN
                'question' => "<p> Buatkan hasil eksekusi kueri berikut: </p><p> SELECT pelanggan.nama, pelanggan.alamat, transaksi.tanggal_pengiriman FROM pelanggan LEFT JOIN transaksi ON transaksi.id_pelanggan = pelanggan.id WHERE pelanggan.jenis_kelamin = 'Perempuan' </p>",
                'answer' => ['queries' => "SELECT pelanggan.nama, pelanggan.alamat, transaksi.tanggal_pengiriman FROM pelanggan LEFT JOIN transaksi ON transaksi.id_pelanggan = pelanggan.id WHERE pelanggan.jenis_kelamin = 'Perempuan'"],
            ],
        ];

        $package = Package::select('id')
            ->whereRelation('topic', 'name','SQL Query Inner dan Outer Join')
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
