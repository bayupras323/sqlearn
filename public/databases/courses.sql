-- MariaDB dump 10.19  Distrib 10.11.2-MariaDB, for osx10.18 (arm64)
--
-- Host: localhost    Database: courses
-- ------------------------------------------------------
-- Server version	10.11.2-MariaDB

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
-- Table structure for table `courses`
--

DROP TABLE IF EXISTS `kursus`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `kursus` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `judul` varchar(255) NOT NULL,
  `pembuat` varchar(255) NOT NULL,
  `harga` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=31 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `courses`
--

LOCK TABLES `kursus` WRITE;
/*!40000 ALTER TABLE `kursus` DISABLE KEYS */;
INSERT INTO `kursus` VALUES
(1,'Riset Akuntansi Membumikan Religiolitas - Political Economy of Accounting (PEA) vs Revolusi (Akuntansi) Buya Hamka','Ari Kamayanti',180000),
(2,'Metodologi Konstruktif','Anonim',120000),
(3,'Metodologi-Dramaturgi','Dr.Aji Dedi Mulawarman',120000),
(4,'Metodologi-Menggerakkan Hijrah','Dr.Aji Dedi Mulawarman',120000),
(5,'Metodologi-Orientalisme','Bu Ari',120000),
(6,'Metodologi-Simulakra','Ari Kamayanti',120000),
(7,'Understanding to Constructing Social Reality - Koleksi dan Analisis Data Komunitas: Facebook dan Whatsapp Group','Anita Kristina',50000),
(8,'Understanding to Constructing Social Reality - Mengintai Sebagai Metode Koleksi Data Netnografi','La Ode Sumail',50000),
(9,'Understanding to Constructing Social Reality - Penyajian Netnografi','Novrida Qudsi Lutfillah',50000);
/*!40000 ALTER TABLE `kursus` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `transaction_details`
--

DROP TABLE IF EXISTS `detail_transaksi`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `detail_transaksi` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `id_transaksi` bigint(20) unsigned NOT NULL,
  `id_kursus` bigint(20) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `transaction_details_transaction_id_foreign` (`id_transaksi`),
  KEY `transaction_details_course_id_foreign` (`id_kursus`),
  CONSTRAINT `transaction_details_course_id_foreign` FOREIGN KEY (`id_kursus`) REFERENCES `kursus` (`id`),
  CONSTRAINT `transaction_details_transaction_id_foreign` FOREIGN KEY (`id_transaksi`) REFERENCES `transaksi` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `transaction_details`
--

LOCK TABLES `detail_transaksi` WRITE;
/*!40000 ALTER TABLE `detail_transaksi` DISABLE KEYS */;
INSERT INTO `detail_transaksi` VALUES
(1,1,7),
(2,1,10),
(3,1,13),
(4,2,2),
(5,2,3),
(6,3,5),
(7,3,3),
(8,4,1),
(9,4,2),
(10,5,4),
(11,6,6),
(12,7,3);
/*!40000 ALTER TABLE `detail_transaksi` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `transactions`
--

DROP TABLE IF EXISTS `transaksi`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `transaksi` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `id_user` bigint(20) unsigned NOT NULL,
  `total` int(11) NOT NULL,
  `status` varchar(255) NOT NULL,
  `tanggal_transaksi` date NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `transactions_user_id_foreign` (`id_user`),
  CONSTRAINT `transactions_user_id_foreign` FOREIGN KEY (`id_user`) REFERENCES `user` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `transactions`
--

LOCK TABLES `transaksi` WRITE;
/*!40000 ALTER TABLE `transaksi` DISABLE KEYS */;
INSERT INTO `transaksi` VALUES
(1,1,540000,'completed','2022-06-19'),
(2,5,360000,'failed','2022-06-19'),
(3,3,360000,'pending','2022-06-24'),
(4,2,300000,'completed','2022-05-19'),
(5,5,120000,'pending','2022-06-24'),
(6,8,120000,'completed','2022-07-03'),
(7,9,300000,'completed','2022-07-03'),
(8,2,120000,'completed','2022-07-03');
/*!40000 ALTER TABLE `transaksi` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `nama_user` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `nomor_telepon` varchar(15) DEFAULT NULL,
  `jenis_kelamin` varchar(10) DEFAULT NULL,
  `tanggal_lahir` date DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `user` WRITE;
/*!40000 ALTER TABLE `user` DISABLE KEYS */;
INSERT INTO `user` VALUES
(1,'Laravelia Putri','laravelia.putri@gmail.com','085771245934','P','2002-02-06'),
(2,'Ruby Arrayna','rubyarrayna@gmail.com','081328094410','P','2000-03-17'),
(3,'Flutterina','flutterina@gmail.com','081328094402','P','2000-05-22'),
(4,'Raden Ayu Pythonia','pythonia@gmail.com','082126758866',NULL,NULL),
(5,'Jason Andreas Van Abe','jasonandreas@gmail.com','08129331561','L','2002-06-24'),
(6,'Syah Qory Latifa','qorylatifa@gmail.com','08134559034',NULL,'2001-12-10'),
(7,'Taylor Swift','t.swift@gmail.com','081541331846','P',NULL),
(8,'Vue Jason','jason.vue@gmail.com','081548963392',NULL,NULL),
(9,'Max Bayesian','bayesian@gmail.com','081954355831','L','2001-05-28'),
(10,'Cynthia Sri Susanti','cynthia.ss@gmail.com','089452424423','P','2001-01-09');
/*!40000 ALTER TABLE `user` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2023-04-12 17:33:37
