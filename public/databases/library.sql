-- phpMyAdmin SQL Dump
-- version 4.6.5.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 16, 2017 at 03:11 PM
-- Server version: 10.1.21-MariaDB
-- PHP Version: 5.6.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `library`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `ID_ADMIN` varchar(10) NOT NULL,
  `USERNAME` varchar(128) DEFAULT NULL,
  `ROLE` varchar(15) DEFAULT NULL,
  `FULLNAME` varchar(128) DEFAULT NULL,
  `JENKEL` char(1) DEFAULT NULL,
  `NO_TELP` varchar(20) DEFAULT NULL,
  `ALAMAT` mediumtext
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`ID_ADMIN`, `USERNAME`, `ROLE`, `FULLNAME`, `JENKEL`, `NO_TELP`, `ALAMAT`) VALUES
('AD001', 'admin', 'superadmin', 'Ahmad Dzulfikar', NULL, NULL, NULL),
('AD002', 'sahid', 'admin', 'Sulaeman Sahid', NULL, NULL, NULL),
('AD003', 'hasim', 'superadmin', 'Hasim Rochmad', 'L', '088788899', 'Jl semeru'),
('AD004', 'tacik', 'admin', 'Riski Ananda W. P.', NULL, NULL, NULL),
('AD005', 'ravi', 'admin', 'Rafi Agatha', NULL, NULL, NULL),
('AD006', 'rehan', 'admin', 'Rehan Arroihan', 'L', '0897789088', 'Jl Jalan id'),
('AD007', 'rasta', 'superadmin', 'Rasta Hirgawana', NULL, NULL, NULL),
('AD008', 'slamet', 'superadmin', 'Slamet Riyadi', NULL, NULL, NULL),
('AD009', 'arroihan', 'superadmin', 'Multazam Arroihan Gusdiansyah', NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `anggota`
--

CREATE TABLE `anggota` (
  `ID_ANGGOTA` varchar(10) NOT NULL,
  `ID_ADMIN` varchar(10) DEFAULT NULL,
  `FULL_NAME` varchar(128) DEFAULT NULL,
  `TMP_LAHIR` varchar(90) DEFAULT NULL,
  `TGL_LAHIR` varchar(20) DEFAULT NULL,
  `ALAMAT` mediumtext,
  `GENDER` char(1) DEFAULT NULL,
  `TELP` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `anggota`
--

INSERT INTO `anggota` (`ID_ANGGOTA`, `ID_ADMIN`, `FULL_NAME`, `TMP_LAHIR`, `TGL_LAHIR`, `ALAMAT`, `GENDER`, `TELP`) VALUES
('AGT001', 'AD005', 'Multazam Arroihan G', 'Bojonegoro', '02/20/2000', 'Jl. Danau Kerinci V G6D4, Sawojajar, Malang', 'L', '085733573529'),
('AGT002', 'AD001', 'Galbie Elinour', 'Malang', '06/16/2017', 'Jl. Kawi no. 89, Malang Kota', 'P', '0897786788954'),
('AGT003', 'AD004', 'Alfan Cahyo Wicaksono', 'Malang', '10/13/1998', 'Jl. Silak Kesam 101', 'L', '087669887654'),
('AGT004', 'AD009', 'Sasna Salsabila Hapsari', 'Madiun', '03/08/1999', 'Perum. Aspol 443, Kota Madiun', 'P', '0818897546788'),
('AGT005', 'AD009', 'Riski Ananda Widiya P', 'Tulungagung', '02/19/2000', 'Perum. Grand Emerald ZU3, Tulungagung', 'P', '093889765643'),
('AGT006', 'AD003', 'Anggyanisa Mutia Putri', 'Madiun', '08/18/2000', 'Gg. Pattimura 89', 'P', '085389755644'),
('AGT007', 'AD002', 'Prasetya Ananta W. S.', 'Malang', '06/16/1999', 'Sulfat Indah 89', 'L', '08267599432'),
('AGT008', 'AD002', 'Irvina Firadila', 'Lumajang', '08/12/1999', 'Ds. Sambungrejo RT. 4 RW. 7 Kec. Welasasih, Lumajang', 'P', '0876654897765'),
('AGT009', 'AD001', 'Ridhwan Rofianto', 'Surabaya', '04/01/2001', 'Mburine cito', 'L', '089776598334'),
('AGT010', 'AD008', 'Muhamad Iqbal Samudra', 'Banyuwangi', '04/05/2000', 'Ds. Sumoharjo, RT. 5 RW. 9', 'L', '08677564987');

-- --------------------------------------------------------

--
-- Table structure for table `buku`
--

CREATE TABLE `buku` (
  `ID_BUKU` varchar(10) NOT NULL,
  `ID_ADMIN` varchar(10) DEFAULT NULL,
  `TITLE` varchar(150) DEFAULT NULL,
  `AUTHOR` varchar(128) DEFAULT NULL,
  `PUBLISHER` varchar(128) DEFAULT NULL,
  `YEAR` char(4) DEFAULT NULL,
  `QTY` int(11) DEFAULT NULL,
  `KELUAR` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `buku`
--

INSERT INTO `buku` (`ID_BUKU`, `ID_ADMIN`, `TITLE`, `AUTHOR`, `PUBLISHER`, `YEAR`, `QTY`, `KELUAR`) VALUES
('BKO004', 'AD003', 'Mikrotik Kung Fu : Kitab 1', 'Rendra Towidjojo', 'Jasakom', '2016', 2, 2),
('BKO005', 'AD001', 'Windows 10 : Panduan Lengkap', 'Indra Susanto', 'Jasakom', '2013', 4, 1),
('BKO006', 'AD001', 'Bisnis Online Revolution', 'Iwan Kenrianto', 'Gramedia', '2015', 2, 1),
('BKO007', 'AD001', '3 Tools Facebook Graph Gratisan', 'Suryadin Laoddang', 'Dosen Jualan', '2015', 1, 2),
('BKO008', 'AD001', 'Mengolah Database Excel', 'Sumonggo Surya', 'Andi Publisher', '2001', 0, 2),
('BKO009', 'AD001', 'Mahir Corel Draw Dalam 4 Hari', 'Yuli Kristanto S', 'Izuka Komp', '2008', 1, 2),
('BKO010', 'AD001', 'The Magic of Photoshop', 'Hendri Hendratama', 'Informatika', '2013', 4, 2),
('BKO011', 'AD001', 'Teknik Profesional Photoshop CS3', 'Rahmat Widiyanto', 'Elex Media Computindo', '2009', 3, 3),
('BKO012', 'AD001', 'Jago SEO', 'Hidayat Rahmad', 'Elex Media Computindo', '2011', 1, 2),
('BKO013', 'AD001', 'Hacking Wireless Network', 'Suryatama Udin', 'Jasakom', '2014', 6, 3);

-- --------------------------------------------------------

--
-- Table structure for table `detail_pinjam`
--

CREATE TABLE `detail_pinjam` (
  `ID_DIPINJAM` int(11) NOT NULL,
  `ID_PINJAM` varchar(10) DEFAULT NULL,
  `ID_BUKU` varchar(10) DEFAULT NULL,
  `TGL_KEMBALI` date DEFAULT NULL,
  `DENDA` int(11) DEFAULT NULL,
  `STATUS` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `detail_pinjam`
--

INSERT INTO `detail_pinjam` (`ID_DIPINJAM`, `ID_PINJAM`, `ID_BUKU`, `TGL_KEMBALI`, `DENDA`, `STATUS`) VALUES
(13, 'P170623001', 'BKO005', '2017-06-25', 0, 'Sudah Kembali'),
(14, 'P170623001', 'BKO008', NULL, 0, 'Belum Kembali'),
(16, 'P170623002', 'BKO008', NULL, 0, 'Belum Kembali'),
(17, 'P170624001', 'BKO013', '2017-07-09', 4000, 'Sudah Kembali'),
(18, 'P170624001', 'BKO007', NULL, 0, 'Belum Kembali'),
(20, 'P170624002', 'BKO004', NULL, 0, 'Belum Kembali'),
(21, 'P170624002', 'BKO005', NULL, 0, 'Belum Kembali'),
(23, 'P170624003', 'BKO011', NULL, 0, 'Belum Kembali'),
(24, 'P170624003', 'BKO004', NULL, 0, 'Belum Kembali'),
(27, 'P170624005', 'BKO013', NULL, 0, 'Belum Kembali'),
(28, 'P170624005', 'BKO006', NULL, 0, 'Belum Kembali'),
(29, 'P170624006', 'BKO011', '2017-06-25', 0, 'Sudah Kembali'),
(34, 'P170625002', 'BKO010', '2017-07-09', 3500, 'Sudah Kembali'),
(37, 'P170626001', 'BKO009', NULL, 0, 'Belum Kembali');

-- --------------------------------------------------------

--
-- Table structure for table `peminjaman`
--

CREATE TABLE `peminjaman` (
  `ID_PINJAM` varchar(10) NOT NULL,
  `ID_ANGGOTA` varchar(10) DEFAULT NULL,
  `ID_ADMIN` varchar(10) DEFAULT NULL,
  `TGL_PINJAM` date DEFAULT NULL,
  `JML_BUKU` int(11) NOT NULL,
  `STATS` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `peminjaman`
--

INSERT INTO `peminjaman` (`ID_PINJAM`, `ID_ANGGOTA`, `ID_ADMIN`, `TGL_PINJAM`, `JML_BUKU`, `STATS`) VALUES
('P170623001', 'AGT002', 'AD004', '2017-06-23', 3, 'Belum Kembali'),
('P170623002', 'AGT003', 'AD005', '2017-06-23', 1, 'Belum Kembali'),
('P170624001', 'AGT007', 'AD005', '2017-06-24', 3, 'Belum Kembali'),
('P170624002', 'AGT008', 'AD005', '2017-06-24', 3, 'Belum Kembali'),
('P170624003', 'AGT004', 'AD005', '2017-06-24', 3, 'Belum Kembali'),
('P170624005', 'AGT007', 'AD004', '2017-06-24', 2, 'Belum Kembali'),
('P170624006', 'AGT003', 'AD006', '2017-06-24', 2, 'Sudah Kembali'),
('P170625002', 'AGT010', 'AD006', '2017-06-25', 2, 'Belum Kembali'),
('P170626001', 'AGT006', 'AD004', '2017-06-26', 3, 'Belum Kembali');

-- --------------------------------------------------------

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`ID_ADMIN`),
  ADD UNIQUE KEY `ADMIN_PK` (`ID_ADMIN`);

--
-- Indexes for table `anggota`
--
ALTER TABLE `anggota`
  ADD PRIMARY KEY (`ID_ANGGOTA`),
  ADD UNIQUE KEY `ANGGOTA_PK` (`ID_ANGGOTA`),
  ADD KEY `MAKE_FK` (`ID_ADMIN`);

--
-- Indexes for table `buku`
--
ALTER TABLE `buku`
  ADD PRIMARY KEY (`ID_BUKU`),
  ADD UNIQUE KEY `BUKU_PK` (`ID_BUKU`),
  ADD KEY `CREATE_FK` (`ID_ADMIN`);

--
-- Indexes for table `detail_pinjam`
--
ALTER TABLE `detail_pinjam`
  ADD PRIMARY KEY (`ID_DIPINJAM`),
  ADD UNIQUE KEY `DETAIL_PINJAM_PK` (`ID_DIPINJAM`),
  ADD KEY `MEMILIKI_FK` (`ID_PINJAM`),
  ADD KEY `MENGAMBIL_FK` (`ID_BUKU`);

--
-- Indexes for table `peminjaman`
--
ALTER TABLE `peminjaman`
  ADD PRIMARY KEY (`ID_PINJAM`),
  ADD UNIQUE KEY `PEMINJAMAN_PK` (`ID_PINJAM`),
  ADD KEY `MELAKUKAN_FK` (`ID_ANGGOTA`),
  ADD KEY `MELAYANI_FK` (`ID_ADMIN`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `detail_pinjam`
--
ALTER TABLE `detail_pinjam`
  MODIFY `ID_DIPINJAM` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=50;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `anggota`
--
ALTER TABLE `anggota`
  ADD CONSTRAINT `FK_ANGGOTA_MAKE_ADMIN` FOREIGN KEY (`ID_ADMIN`) REFERENCES `admin` (`ID_ADMIN`);

--
-- Constraints for table `buku`
--
ALTER TABLE `buku`
  ADD CONSTRAINT `FK_BUKU_CREATE_ADMIN` FOREIGN KEY (`ID_ADMIN`) REFERENCES `admin` (`ID_ADMIN`);

--
-- Constraints for table `detail_pinjam`
--
ALTER TABLE `detail_pinjam`
  ADD CONSTRAINT `FK_DETAIL_P_MEMILIKI_PEMINJAM` FOREIGN KEY (`ID_PINJAM`) REFERENCES `peminjaman` (`ID_PINJAM`),
  ADD CONSTRAINT `FK_DETAIL_P_MENGAMBIL_BUKU` FOREIGN KEY (`ID_BUKU`) REFERENCES `buku` (`ID_BUKU`);

--
-- Constraints for table `peminjaman`
--
ALTER TABLE `peminjaman`
  ADD CONSTRAINT `FK_PEMINJAM_MELAKUKAN_ANGGOTA` FOREIGN KEY (`ID_ANGGOTA`) REFERENCES `anggota` (`ID_ANGGOTA`),
  ADD CONSTRAINT `FK_PEMINJAM_MELAYANI_ADMIN` FOREIGN KEY (`ID_ADMIN`) REFERENCES `admin` (`ID_ADMIN`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
