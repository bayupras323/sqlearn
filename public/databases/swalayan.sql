-- MariaDB dump 10.19  Distrib 10.11.2-MariaDB, for osx10.18 (arm64)
--
-- Host: localhost    Database: swalayan
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
-- Table structure for table `brands`
--

DROP TABLE IF EXISTS `brand`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `brand` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `nama_brand` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=38 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `brands`
--

LOCK TABLES `brand` WRITE;
/*!40000 ALTER TABLE `brand` DISABLE KEYS */;
INSERT INTO `brand` VALUES
(1,'ASUS'),
(2,'SanDisk'),
(3,'SGM'),
(4,'Formula'),
(5,'HotWheels'),
(6,'Indomilk'),
(7,'Wardah'),
(8,'Walls'),
(9,'Lego'),
(10,'Dancow'),
(11,'Kopiko'),
(12,'Pepsodent'),
(13,'Rexona');
/*!40000 ALTER TABLE `brand` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `categories`
--

DROP TABLE IF EXISTS `kategori`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `kategori` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `nama_kategori` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=39 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `categories`
--

LOCK TABLES `kategori` WRITE;
/*!40000 ALTER TABLE `kategori` DISABLE KEYS */;
INSERT INTO `kategori` VALUES
(1,'Laptop dan Komputer'),
(2,'Mainan'),
(3,'Makanan dan Susu Bayi'),
(4,'Es Krim'),
(5,'Susu, Kopi, dan Teh'),
(6,'Kosmetik'),
(7,'Perawatan Badan'),
(8,'Perawatan Diri'),
(9,'Kesehatan');
/*!40000 ALTER TABLE `kategori` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `products`
--

DROP TABLE IF EXISTS `produk`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `produk` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `nama_produk` varchar(255) NOT NULL,
  `stock` int(11) NOT NULL,
  `harga` int(11) NOT NULL,
  `id_kategori` bigint(20) unsigned NOT NULL,
  `id_brand` bigint(20) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `products_category_id_foreign` (`id_kategori`),
  KEY `products_brand_id_foreign` (`id_brand`),
  CONSTRAINT `products_brand_id_foreign` FOREIGN KEY (`id_brand`) REFERENCES `brand` (`id`),
  CONSTRAINT `products_category_id_foreign` FOREIGN KEY (`id_kategori`) REFERENCES `kategori` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=51 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `products`
--

LOCK TABLES `produk` WRITE;
/*!40000 ALTER TABLE `produk` DISABLE KEYS */;
INSERT INTO `produk` VALUES
(1,'Wardah Shampoo Anti Dandruff 170Ml',25,25500,6,7),
(2,'Rexona Deo Roll On Men Motionsense Invisible+Antibacterial 45Ml',20,20700,8,13),
(3,'Dancow Fortigro Susu Bubuk Instant Polybag 10X27g',28,28900,5,10),
(4,'Rexona Roll-On Glowing White 40Ml',12,12500,8,13),
(5,'Hot Wheels Fast & Furious Ford GT-40 08',49,49900,2,5),
(6,'Pepsodent Action 123 Pasta Gigi Herbal 190G',12,12500,10,12),
(7,'SANDISK 32 GB USB CRUZER BLADE CZ50',9,99000,1,2),
(8,'Formula Pasta Gigi + Sikat Gigi Sparkling White 190G',9,9400,8,4),
(9,'Kopiko Coffee Latte 78C 240Ml',6,6000,5,11),
(10,'SANDISK 64 GB USB CRUZER BLADE CZ50',14,149000,1,2),
(11,'Kopiko Minuman Kopi Susu Lucky Day 180Ml',6,6000,11,11),
(12,'HOT WHEELS BATMOBILE TUMBLER LIMITED EDITION',3,3999900,2,5),
(13,'Pepsodent Bamboo Salt Sikat Gigi Soft Multipack (Isi 2)',25,25400,8,12),
(14,'SGM Eksplor 5+ Pro-Gressmaxx Susu Pertumbuhan Madu 400G',30,30500,5,3);
/*!40000 ALTER TABLE `produk` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2023-04-12 17:33:53
