-- MariaDB dump 10.19  Distrib 10.11.2-MariaDB, for osx10.18 (arm64)
--
-- Host: localhost    Database: employee
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
-- Table structure for table `divisions`
--

DROP TABLE IF EXISTS `divisi`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `divisi` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `nama_divisi` varchar(30) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `divisions`
--

LOCK TABLES `divisi` WRITE;
/*!40000 ALTER TABLE `divisi` DISABLE KEYS */;
INSERT INTO `divisi` VALUES
(1,'Cartwright-Bins'),
(2,'Rowe, Kuvalis and Gaylord'),
(3,'Hammes, Barton and Kautzer'),
(4,'Schmitt Inc'),
(5,'Wyman, Stokes and Kunde'),
(6,'Hansen, Kuvalis and Farrell'),
(7,'Kreiger, Baumbach and Swift'),
(8,'Pollich Ltd'),
(9,'Larson, McKenzie and Schuster'),
(10,'Crooks-Witting');
/*!40000 ALTER TABLE `divisi` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `employees`
--

DROP TABLE IF EXISTS `pegawai`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `pegawai` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `id_divisi` bigint(20) unsigned DEFAULT NULL,
  `nip` varchar(18) NOT NULL,
  `nama_pegawai` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `jenis_kelamin` enum('Male','Female') NOT NULL DEFAULT 'Male',
  `nomor_telepon` varchar(13) NOT NULL,
  `alamat` text NOT NULL,
  `jabatan` varchar(50) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `employees_division_id_foreign` (`id_divisi`),
  CONSTRAINT `employees_division_id_foreign` FOREIGN KEY (`id_divisi`) REFERENCES `divisi` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=43 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `employees`
--

LOCK TABLES `pegawai` WRITE;
/*!40000 ALTER TABLE `pegawai` DISABLE KEYS */;
INSERT INTO `pegawai` VALUES
(1,4,'582330520663439667','Roselyn Anderson','nicholas.breitenberg@example.com','Male','+13364834256','1553 Gottlieb Harbors Suite 170\r\nEast Janaeview, AL 52836','Ceiling Tile Installer'),
(2,2,'391754514962717200','Walton Effertz','britney43@example.org','Male','+18474576597','26927 Florine Unions Apt. 934\r\nMathiasstad, AR 99285','Director Of Marketing'),
(3,5,'181634309838856991','Dr. Gregoria Towne','berge.oral@example.com','Male','+15518690635','986 Parisian Crossing\r\nSouth Baileymouth, NC 49619','MARCOM Director'),
(4,5,'327129775782655222','Mr. Consuelo Brown','hoeger.meta@example.com','Male','+14015703789','863 Schultz Meadow Apt. 769\r\nPort Vidal, LA 40830-6737','Precision Etcher and Engraver'),
(5,4,'722011677215778393','Dale Hegmann','riley.rogahn@example.net','Female','+17816125593','6016 Lemke Mission Suite 891\r\nRodriguezchester, MA 64953','Podiatrist'),
(6,3,'259069281050275435','Delfina Auer','raheem62@example.com','Female','+15717578481','6188 Kub Centers Apt. 811\r\nLangborough, CA 79282','Pharmaceutical Sales Representative'),
(7,11,'591649439300335743','Oren Lueilwitz','cheyanne25@example.com','Male','+14408995285','85499 Satterfield Stream\r\nNew Stanley, GA 80094','Computer Security Specialist'),
(8,9,'025454382333358200','Gisselle Homenick','prudence28@example.com','Female','+12816943403','253 Lorenzo Orchard Suite 488\r\nPort Jazlyn, NE 01821-1174','Optical Instrument Assembler'),
(9,7,'769843848332602628','Cooper Schneider','lemke.hermina@example.com','Male','+15418569307','12069 Cole Gardens Suite 868\r\nEstebanchester, AR 76970-2835','Storage Manager OR Distribution Manager'),
(10,8,'329457262320401770','Tamara Robel','mmclaughlin@example.net','Female','+12396260970','46239 Schmeler Corners\r\nJuniorburgh, MD 67429','Manager Tactical Operations');
/*!40000 ALTER TABLE `pegawai` ENABLE KEYS */;
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
