<?php

namespace Database\Seeders\Exercises;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Database;
use App\Models\Exercise;
use App\Models\Package;
class RelationshipSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [];

        $data[0]['db_name'] = 'sqlearn_library';
        $data[0]['type'] = 'ERD';
        $data[0]['question'] = '<p>Dalam sebuah perpustakaan terdapat kegiatan pinjam meminjam yang dilakukan oleh anggota dan diurus oleh admin, dengan praturan dimana setiap anggota dapat meminjam beberapa buku, setiap peminjaman ditangain oleh admin, setiap anggota sudah terdaftar terlebih dahulu melaui admin, dan masing masing buku bisa diperbarui oleh admin sewaktu waktu</p>';
        $data[0]['answer'] = 'databases/question/erd/relationship/sqlearn_library.txt';

        $data[1]['db_name'] = 'sqlearn_showroom';
        $data[1]['type'] = 'ERD';
        $data[1]['question'] = '<p>Didalam sebuah tempat showroom mobil, terdapat banyak brand dan mobil yang dapat di pilih sesuai selera pengunjung, dimana setiap pengunjung dapat melakukan transaksi untuk berbagai macam jenis mobil</p>';
        $data[1]['answer'] = 'databases/question/erd/relationship/sqlearn_showroom.txt';

        $data[2]['db_name'] = 'sqlearn_employee';
        $data[2]['type'] = 'ERD';
        $data[2]['question'] = '<p>Dalam sebuah perushaan terdapat divisi yang bergerak di bidang masing masing, yang dimana setiap divisi memiliki banyak pegawai didalamnya</p>';
        $data[2]['answer'] = 'databases/question/erd/relationship/sqlearn_employee.txt';

        $data[3]['db_name'] = 'sqlearn_courses';
        $data[3]['type'] = 'ERD';
        $data[3]['question'] = '<p>Ada sebuah kursus online yang dimana kursus ini memiliki banyak user dan setiap bisa memiliki banyak kursus melalui apa yang mereka pilih dalam setiap transaksinya</p>';
        $data[3]['answer'] = 'databases/question/erd/relationship/sqlearn_courses.txt';

        $data[4]['db_name'] = 'sqlearn_swalayan';
        $data[4]['type'] = 'ERD';
        $data[4]['question'] = '<p>Dalam sebuah swalayan terdapat banyak produk dan brand, dimana masing masing produk bisa terdiri dalam satu brand, dan satu produk memiliki banyak kategori</p>';
        $data[4]['answer'] = 'databases/question/erd/relationship/sqlearn_swalayan.txt';

        $data[5]['db_name'] = 'sqlearn_dokter_hewan';
        $data[5]['type'] = 'ERD';
        $data[5]['question'] = '<p>Dalam sebuah pelayanan dokter, terdapat cara untuk melakukan reservasi atau booking, dimana satu reservasi bisa meliputi banyak layanan serta reservasi ini dapat dilakukan oleh tiap user</p>';
        $data[5]['answer'] = 'databases/question/erd/relationship/sqlearn_dokter_hewan.txt';

        $data[6]['db_name'] = 'sqlearn_rental_car';
        $data[6]['type'] = 'ERD';
        $data[6]['question'] = '<p>Dalam rental mobil terdapat sistem yang dapat mengakomodir data penyewaan mobil yang berelasi dengan customer yg telah tedaftar</p>';
        $data[6]['answer'] = 'databases/question/erd/relationship/sqlearn_rental_car.txt';

        $data[7]['db_name'] = 'sqlearn_project';
        $data[7]['type'] = 'ERD';
        $data[7]['question'] = '<p>Dalam ekosistem project banyak client yang dimana client ini bisa memesan banyak project dalam waktu yang bersamaan</p>';
        $data[7]['answer'] = 'databases/question/erd/relationship/sqlearn_project.txt';

        $data[8]['db_name'] = 'sqlearn_sales';
        $data[8]['type'] = 'ERD';
        $data[8]['question'] = '<p>Sebagai seorang sales dalam sebuah perusahaan dapat memperoleh nasabah yang akan berimpact terhadap pendapatan reward dengan mendapatkan nasabah</p>';
        $data[8]['answer'] = 'databases/question/erd/relationship/sqlearn_sales.txt';

        $data[9]['db_name'] = 'sqlearn_warehouse';
        $data[9]['type'] = 'ERD';
        $data[9]['question'] = '<p>Dalam sebuah gudang terdapat banyak product dan satu product memiliki banyak item dimana setiap item sendiri bisa tergolong dalam 1 product</p>';
        $data[9]['answer'] = 'databases/question/erd/relationship/sqlearn_warehouse.txt';

        $package = Package::select('id')
            ->whereRelation('topic', 'name','ERD Relationship')
            ->first();

        foreach ($data as $key => $value)
        {
            $db = Database::where('name',$value['db_name'])->first();
            Exercise::create([
                'package_id' => $package->id,
                'database_id' => $db->id,
                'type' => $value['type'],
                'question' => $value['question'],
                'answer' => json_decode(file_get_contents(public_path().'/'.$value['answer']),true),
            ]);
        }
    }
}
