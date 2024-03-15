<?php

namespace Database\Seeders\Exercises;

use App\Models\Addition;
use App\Models\Database;
use App\Models\Exercise;
use App\Models\Package;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DataDefinitionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $exercises = [

            //DDL CREATE TABLE 1
            [
                'db_name' => 'sqlearn_library',
                'ddl_type' => 'create table',
                'question' => '<p>Buatkan hasil eksekusi kueri berikut:</p><p>CREATE TABLE anggota (<br>&nbsp; &nbsp; ID_ANGGOTA VARCHAR(10) NOT NULL PRIMARY KEY,<br>&nbsp; &nbsp; ID_ADMIN VARCHAR(10),<br>&nbsp; &nbsp; FULL_NAME VARCHAR(128),<br>&nbsp; &nbsp; TMP_LAHIR VARCHAR(90),<br>&nbsp; &nbsp; TGL_LAHIR VARCHAR(20),<br>&nbsp; &nbsp; ALAMAT MEDIUMTEXT,<br>&nbsp; &nbsp; GENDER CHAR(1),<br>&nbsp; &nbsp; TELP VARCHAR(20),<br>&nbsp; &nbsp; FOREIGN KEY ID_ADMIN REFERENCES admin(ID_ADMIN)<br>)</p>',
                'answer' => ['queries' => 'DESC anggota'],
                'additions' => [
                    'table_name' => ['member', 'admin'],
                    'column_name' => ['NAMA_LENGKAP', 'TANGGAL_LAHIR', 'TEMPAT_LAHIR', 'TELEPON'],
                    'column_size' => [50, 255],
                    'column_default' => ['Malang', 'L', 'P'],
                ]
            ],

            //DDL DROP TABLE
            [
                'db_name' => 'sqlearn_library',
                'ddl_type' => 'drop table',
                'question' => '<p>Buatkan hasil eksekusi kueri berikut:</p><p>DROP TABLE anggota;</p><p>DROP TABLE buku;</p>',
                'answer' => ['table' => ['anggota', 'buku']],
                'additions' => [
                    'table_name' => ['member', 'kategori', 'transaksi']
                ]
            ],

            //DDL CREATE TABLE 2

            [
                'db_name' => 'sqlearn_library',
                'ddl_type' => 'create table',
                'question' => '<p>Buatkan hasil eksekusi kueri berikut:</p><p>CREATE TABLE admin (<br>&nbsp; &nbsp; ID_ADMIN VARCHAR(10) NOT NULL PRIMARY KEY,<br>&nbsp; &nbsp; USERNAME VARCHAR(128),<br>&nbsp; &nbsp; ROLE VARCHAR(15),<br>&nbsp; &nbsp; FULLNAME VARCHAR(128),<br>&nbsp; &nbsp; JENKEL CHAR(1),<br>&nbsp; &nbsp; NO_TELP VARCHAR(20),<br>&nbsp; &nbsp; ALAMAT MEDIUMTEXT <br>)</p>',
                'answer' => ['queries' => 'DESC admin'],
                'additions' => [
                    'table_name' => ['member', 'transaksi', 'anggota'],
                    'column_name' => ['USER', 'NAME', 'JK', 'TGL'],
                    'column_size' => [25, 100, 255],
                    'column_default' => ['10000', 'Belum selesai'],
                ]
            ],

            // DDL ALTER ADD COLUMN
            [
                'db_name' => 'sqlearn_library',
                'ddl_type' => 'alter add column',
                'question' => '<p>Buatkan hasil eksekusi kueri berikut:</p><p>ALTER TABLE buku ADD COLUMN (ISBN VARCHAR(20) NOT NULL);</p><p>ALTER TABLE buku ADD COLUMN (JENIS_BUKU VARCHAR(50) DEFAULT "fiksi");</p>',
                'answer' => [
                    'table' => 'buku',
                    'columns' => [
                        [
                            'name' => 'ISBN',
                            'type' => 'varchar(20)',
                            'nullability' => 'NO',
                            'key' => '',
                            'default' => '',
                            'extra' => '',
                        ],
                        [
                            'name' => 'JENIS_BUKU',
                            'type' => 'varchar(50)',
                            'nullability' => 'YES',
                            'key' => '',
                            'default' => 'fiksi',
                            'extra' => '',
                        ]
                    ]
                ],
                'additions' => [
                    'table_name' => ['kategori', 'fiksi'],
                    'column_name' => ['TANGGAL_PENGADAAN', 'CETAKAN_KE', 'NO_BUKU'],
                    'column_size' => [30, 100],
                    'column_default' => ['non-fiksi', 'novel', '1'],
                ]
            ],

            // DDL ALTER RENAME COLUMN
            [
                'db_name' => 'sqlearn_library',
                'ddl_type' => 'alter rename column',
                'question' => '<p>Buatkan hasil eksekusi kueri berikut:</p><p>ALTER TABLE anggota RENAME COLUMN GENDER TO JENIS_KELAMIN;</p><p>ALTER TABLE anggota RENAME COLUMN FULL_NAME TO NAMA_LENGKAP;</p><p>ALTER TABLE anggota RENAME COLUMN TELP TO TELEPON;</p>',
                'answer' => [
                    'table' => 'anggota',
                    'columns' => [
                        [
                            'old_name' => 'GENDER',
                            'new_name' => 'JENIS_KELAMIN',
                        ],
                        [
                            'old_name' => 'FULL_NAME',
                            'new_name' => 'NAMA_LENGKAP',
                        ],
                        [
                            'old_name' => 'TELP',
                            'new_name' => 'TELEPON',
                        ]
                    ]
                ],
                'additions' => [
                    'table_name' => ['user', 'member'],
                    'column_name' => ['EMAIL', 'ANGKATAN'],
                ]
            ],

            // DDL ALTER MODIFY COLUMN
            [
                'db_name' => 'sqlearn_library',
                'ddl_type' => 'alter modify column',
                'question' => '<p>Buatkan hasil eksekusi kueri berikut:</p><p>ALTER TABLE anggota MODIFY COLUMN ALAMAT VARCHAR(255);</p><p>ALTER TABLE anggota MODIFY COLUMN TGL_LAHIR DATE;</p>',
                'answer' => [
                    'table' => 'anggota',
                    'columns' => [
                        [
                            'name' => 'ALAMAT',
                            'type' => 'VARCHAR(255)',
                        ],
                        [
                            'name' => 'TGL_LAHIR',
                            'type' => 'DATE',
                        ]
                    ]
                ],
                'additions' => [
                    'table_name' => ['user', 'member'],
                    'column_name' => ['EMAIL', 'ANGKATAN', 'JENKEL'],
                    'column_size' => [50, 200, 255]
                ]
            ],

            //DDL ALTER DROP COLUMN
            [
                'db_name' => 'sqlearn_library',
                'ddl_type' => 'alter drop column',
                'question' => '<p>Buatkan hasil eksekusi kueri berikut:</p><p>ALTER TABLE admin DROP COLUMN ROLE;</p><p>ALTER TABLE admin DROP COLUMN JENKEL;</p>',
                'answer' => ['table' => 'admin', 'column' => ['ROLE', 'JENKEL']],
                'additions' => [
                    'table_name' => ['anggota', 'administrator', 'user'],
                    'column_name' => ['NAMA_LENGKAP', 'TANGGAL_LAHIR', 'TEMPAT_LAHIR', 'TELEPON'],
                ]
            ],

            // DDL ALTER ADD COLUMN 2
            [
                'db_name' => 'sqlearn_dokter_hewan',
                'ddl_type' => 'alter add column',
                'question' => '<p>Buatkan hasil eksekusi kueri berikut:</p><p>ALTER TABLE reservasi ADD COLUMN (<br>&nbsp; &nbsp; tanggal DATE NOT NULL,<br>&nbsp; &nbsp;  ras_peliharaan VARCHAR(50) DEFAULT "-" <br>);</p>',
                'answer' => [
                    'table' => 'reservasi',
                    'columns' => [
                        [
                            'name' => 'tanggal',
                            'type' => 'DATE',
                            'nullability' => 'NO',
                            'key' => '',
                            'default' => '',
                            'extra' => '',
                        ],
                        [
                            'name' => 'ras_peliharaan',
                            'type' => 'varchar(50)',
                            'nullability' => 'YES',
                            'key' => '',
                            'default' => '-',
                            'extra' => '',
                        ]
                    ]
                ],
                'additions' => [
                    'table_name' => ['user', 'pelanggan', 'karyawan'],
                    'column_name' => ['alamat', 'nama', 'tgl', 'no_telp'],
                    'column_size' => [10, 30, 100],
                    'column_default' => ['Tidak ada', 'Kucing'],
                ]
            ],

            // DDL ALTER RENAME COLUMN 2
            [
                'db_name' => 'sqlearn_showroom',
                'ddl_type' => 'alter rename column',
                'question' => '<p>Buatkan hasil eksekusi kueri berikut:</p><p>ALTER TABLE transaksi RENAME COLUMN tanggal_pengiriman TO tgl_kirim;</p><p>ALTER TABLE transaksi RENAME COLUMN tanggal_pemesanan TO tgl_pesan;</p>',
                'answer' => [
                    'table' => 'transaksi',
                    'columns' => [
                        [
                            'old_name' => 'tanggal_pengiriman',
                            'new_name' => 'tgl_kirim',
                        ],
                        [
                            'old_name' => 'tanggal_pemesanan',
                            'new_name' => 'tgl_pesan',
                        ]
                    ]
                ],
                'additions' => [
                    'table_name' => ['user', 'member', 'pesanan'],
                    'column_name' => ['nama', 'tanggal', 'no_transaksi', 'id'],
                ]
            ],

            //DDL ALTER DROP COLUMN 2
            [
                'db_name' => 'sqlearn_swalayan',
                'ddl_type' => 'alter drop column',
                'question' => '<p>Buatkan hasil eksekusi kueri berikut:</p><p>ALTER TABLE produk DROP COLUMN stock, DROP COLUMN harga;</p>',
                'answer' => ['table' => 'produk', 'column' => ['stock', 'harga']],
                'additions' => [
                    'table_name' => ['brand', 'merk', 'kategori'],
                    'column_name' => ['nama_produk', 'id', 'id_brand', 'id_kategori'],
                ]
            ],

        ];

        $package = Package::select('id')
            ->where('name', 'NOT LIKE', '%Beta%')
            ->whereRelation('topic', 'name', 'SQL Data Definition Language')
            ->first();

        $database = Database::select('name', 'id')->get();

        // insert to exercises table
        foreach ($exercises as $exercise) {
            foreach ($database as $db) {
                if ($exercise['db_name'] == $db['name']) {
                    $exercise_id = Exercise::create([
                        'package_id' => $package->id,
                        'database_id' => $db->id,
                        'type' => 'DDL',
                        'ddl_type' => $exercise['ddl_type'],
                        'question' => $exercise['question'],
                        'answer' => $exercise['answer'],
                    ]);
                    // insert data additions
                    if (array_key_exists("additions", $exercise)) {
                        foreach ($exercise['additions'] as $type => $contents) {
                            foreach ($contents as $content) {
                                Addition::create([
                                    'exercise_id' => $exercise_id->id,
                                    'type' => $type,
                                    'content' => $content
                                ]);
                            }
                        }
                    }
                }
            }
        }
    }
}
