-- MariaDB dump 10.19  Distrib 10.8.3-MariaDB, for osx10.17 (arm64)
--
-- Host: localhost    Database: showroom_mobil
-- ------------------------------------------------------
-- Server version	10.8.3-MariaDB

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `brands`
--

DROP TABLE IF EXISTS `brand`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `brand` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `nama_brand` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `deskripsi_brand` text COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `brands`
--

LOCK TABLES `brand` WRITE;
/*!40000 ALTER TABLE `brand` DISABLE KEYS */;
INSERT INTO `brand` VALUES
(1,'Toyota','Toyota Indonesia merupakan salah satu produsen ternama mobil baru asal Jepang dengan 25 model pilihan dengan berbagai spesifikasi.'),
(2,'Daihatsu','Daihatsu Indonesia merupakan produsen mobil baru dengan 9 model pilihan.'),
(3,'Honda','Honda merupakan salah satu produsen kendaraan terbesar di dunia sejak tahun 1959 dan juga produsen mesin pembakaran dalam terbesar dengan produksi lebih dari 14 juta unit tiap tahun.'),
(4, 'Nissan', 'Nissan Indonesia merupakan produsen mobil baru dengan 5 model pilihan. Sebagai salah satu raksasa otomotif di Tanah Air, Nissan memiliki line-up 2 model MPV (Nissan Serena, Livina), serta 3 model SUV dan crossover (Nissan Magnite, Kicks E-Power, Xtrail).'),
(5,'BMW','BMW Indonesia menyediakan hingga 23 model kendaraan penumpang di Indonesia dari berbagai jenis.'),
(6,'Mercedes-Benz','Mercedes-Benz pastinya punya desain mewah, Mercedes-Benz juga dinilai sangat cocok untuk kalangan eksekutif yang mendambakan kendaraan berperforma sangat baik, fitur canggih dan berlimpah, serta desain-material kualitas premium.'),
(7,'Audi','Audi merupakan salah satu produsen otomotif termuka dari Jerman. Brand otomotif yang berlogo empat cincin bergabung ini juga memiliki semboyan Vorsprung durch Technik yakni keunggulan melalui teknologi, tak ayal Audi tetap konsisten hingga kini dengan inovasi teknologinya dalam industri otomotif.'),
(8,'Suzuki','Suzuki Indonesia merupakan produsen mobil baru dengan 11 model pilihan.'),
(9,'Jaguar','Jaguar merupakan brand asal Inggris yang didirikan pada tahun 1922.');
/*!40000 ALTER TABLE `brand` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cars`
--

DROP TABLE IF EXISTS `mobil`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `mobil` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `nama_mobil` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tahun` year(4) NOT NULL,
  `harga` bigint(20) NOT NULL,
  `isi_silinder` int(11) NOT NULL,
  `transmisi` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `bahan_bakar` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `kapasitas` int(11) NOT NULL,
  `id_brand` bigint(20) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `cars_brand_id_foreign` (`id_brand`),
  CONSTRAINT `cars_brand_id_foreign` FOREIGN KEY (`id_brand`) REFERENCES `brand` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cars`
--

LOCK TABLES `mobil` WRITE;
/*!40000 ALTER TABLE `mobil` DISABLE KEYS */;
INSERT INTO `mobil` VALUES
(1,'Daihatsu Rocky 1.0 R TC MT',2021,232400000,998,'Otomatis','Bensin',5,2),
(2,'Civic Type R 6 Speed MT',2021,1177000000,1996,'Otomatis','Bensin',3,3),
(3,'Mercedes-Benz AMG GT R Coupe',2021,7165000000,6250,'Otomatis','Bensin',2,6),
(4,'Toyota Alphard 2.5 X AT',2021,1064000000,2494,'Otomatis','Bensin',6,1),
(5,'Mini Electric',2022,1046000000,28,'Otomatis','Electric',4,10),
(6,'Audi RS4',2021,2543000000,2894,'Otomatis','Bensin',5,7),
(7,'Suzuki Ertiga',2021,227300000,1462,'Otomatis','Bensin',7,8),
(8,'BMW 218i',2022,880000000,1499,'Otomatis','Bensin',5,5);
/*!40000 ALTER TABLE `mobil` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `customers`
--

DROP TABLE IF EXISTS `pelanggan`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `pelanggan` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `no_ktp` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nama` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `alamat` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `no_hp` varchar(15) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `jenis_kelamin` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `customers`
--

LOCK TABLES `pelanggan` WRITE;
/*!40000 ALTER TABLE `pelanggan` DISABLE KEYS */;
INSERT INTO `pelanggan` VALUES
(1,'3497172758716740','Indra Setiawan Budiaman','Wisma Gading Permai Menara A3, DKI Jakarta','021-45850160','setiawan.indra@gmail.com','Laki-Laki'),
(2,'3034951691552804','Agung Sukarno Darmali','JL.Raya Singosari No.119, Malang','0341-452383','agungdarmali@gmail.com','Laki-Laki'),
(3,'5827240038265099','Erlin Batari Irawan','Jl Sultan Iskandar Muda 1, Sumatera Utara','061-7151971','erlinbatari@gmail.com','Perempuan'),
(4,'3562169714016894','Ratna Leony Pranoto','Jl Lombok 10, Jawa Barat','022-4210047','leonypranoto@gmail.com','Perempuan'),
(5,'7297245475007300','Widyawati Siska Tanudjaja','Kompl Kings Shopping Centre, Jawa Barat','022-4200367','widyawati@gmail.com','Perempuan'),
(6,'9657831922919604','Melati Nirmala Tahyadi','Jl Tamblong 27, Jawa Barat','022-4205765','melatinirmala@gmail.com','Perempuan'),
(7,'4559446182773616','Wulan Devi Irawan','Jl Pemuda 27, Jawa Timur','031-5343373','devirawan@gmail.com','Perempuan'),
(8,'7184419737898422','Budi Agung Pranata',' Jl Balikpapan 22 B, Dki Jakarta','021-63864066','pranatagung@gmail.com','Laki-Laki'),
(9,'2253794088168868','Purnama Suharto Santoso','Jl Blunyah Gede 106 Yogyakarta, Jawa Tengah','0274-523370','suharto@gmail.com','Laki-Laki'),
(10,'7166486323811203','Ridwan Iman Hadiman','Jl Brigjen Katamso 421, Sumatera Utara','061-4159079','hadiman@gmail.com','Laki-Laki');
/*!40000 ALTER TABLE `pelanggan` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `transactions`
--

DROP TABLE IF EXISTS `transaksi`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `transaksi` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `no_nota` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `id_pelanggan` bigint(20) unsigned NOT NULL,
  `id_mobil` bigint(20) unsigned NOT NULL,
  `tanggal_pemesanan` date NOT NULL,
  `tanggal_pengiriman` date NOT NULL,
  `jenis_pembayaran` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `transactions_no_nota_unique` (`no_nota`),
  KEY `transactions_customer_id_foreign` (`id_pelanggan`),
  KEY `transactions_car_id_foreign` (`id_mobil`),
  CONSTRAINT `transactions_car_id_foreign` FOREIGN KEY (`id_mobil`) REFERENCES `mobil` (`id`),
  CONSTRAINT `transactions_customer_id_foreign` FOREIGN KEY (`id_pelanggan`) REFERENCES `pelanggan` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `transactions`
--

LOCK TABLES `transaksi` WRITE;
/*!40000 ALTER TABLE `transaksi` DISABLE KEYS */;
INSERT INTO `transaksi` VALUES
(1,'GR210609095833',9,4,'2021-06-09','2021-06-22','Tunai','Selesai'),
(2,'GR210611114121',2,7,'2021-06-11','2021-06-30','Transfer','Selesai'),
(3,'GR210611121904',3,2,'2021-06-11','2021-06-27','Tunai','Pending'),
(4,'GR210611123318',4,5,'2021-06-11','2021-06-20','Tunai','Selesai'),
(5,'GR210611124018',3,2,'2021-06-11','2021-06-22','Transfer','Pending'),
(6,'GR210611124147',6,2,'2021-06-11','2021-06-16','Transfer','Pending'),
(7,'GR210611124421',1,1,'2021-06-11','2021-06-16','Transfer','Pending'),
(8,'GR210611124637',8,1,'2021-06-11','2021-06-09','Tunai','Pending'),
(9,'GR210611033915',9,4,'2021-06-11','2021-06-24','Tunai','Selesai'),
(10,'GR210611034917',1,8,'2021-06-11','2021-06-16','Tunai','Selesai'),
(11,'GR210613031234',1,4,'2021-06-13','2021-06-30','Tunai','Selesai'),
(12,'GR210618101551',2,4,'2021-06-18','2021-06-09','Tunai','Pending'),
(13,'GR210620111920',3,3,'2021-06-20','2021-06-03','Tunai','Pending');
/*!40000 ALTER TABLE `transaksi` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2023-02-23 16:02:10
