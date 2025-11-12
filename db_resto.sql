-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Server version:               5.7.33 - MySQL Community Server (GPL)
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;

-- ----------------------------
-- DROP TABEL (Urutan dari Anak ke Induk)
-- ----------------------------
DROP TABLE IF EXISTS `detail_pesanan`;
DROP TABLE IF EXISTS `pesanan`;
DROP TABLE IF EXISTS `menu`;
DROP TABLE IF EXISTS `meja`;
DROP TABLE IF EXISTS `kategori_menu`;
DROP TABLE IF EXISTS `users`;

-- ----------------------------
-- CREATE TABEL (Urutan dari Induk ke Anak)
-- ----------------------------

-- Table structure for users
CREATE TABLE `users` (
  `id_user` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','kitchen') NOT NULL,
  PRIMARY KEY (`id_user`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Table structure for kategori_menu
CREATE TABLE `kategori_menu` (
  `id_kategori` int(11) NOT NULL AUTO_INCREMENT,
  `nama_kategori` varchar(100) NOT NULL,
  PRIMARY KEY (`id_kategori`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Table structure for meja
CREATE TABLE `meja` (
  `id_meja` int(11) NOT NULL AUTO_INCREMENT,
  `nomor_meja` varchar(10) NOT NULL,
  `status` enum('kosong','terisi') NOT NULL DEFAULT 'kosong',
  PRIMARY KEY (`id_meja`),
  UNIQUE KEY `nomor_meja` (`nomor_meja`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Table structure for menu
CREATE TABLE `menu` (
  `id_menu` int(11) NOT NULL AUTO_INCREMENT,
  `nama_menu` varchar(255) NOT NULL,
  `deskripsi` text,
  `harga` decimal(10,2) NOT NULL,
  `gambar` varchar(255) DEFAULT NULL,
  `id_kategori` int(11) NOT NULL,
  PRIMARY KEY (`id_menu`),
  KEY `id_kategori` (`id_kategori`),
  CONSTRAINT `menu_ibfk_1` FOREIGN KEY (`id_kategori`) REFERENCES `kategori_menu` (`id_kategori`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Table structure for pesanan
CREATE TABLE `pesanan` (
  `id_pesanan` int(11) NOT NULL AUTO_INCREMENT,
  `id_meja` int(11) NOT NULL,
  `nama_pelanggan` varchar(100) DEFAULT NULL,
  `total_harga` decimal(10,2) NOT NULL,
  `status_pesanan` enum('baru','diproses','selesai','dibayar') NOT NULL DEFAULT 'baru',
  `waktu_pesan` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_pesanan`),
  KEY `id_meja` (`id_meja`),
  CONSTRAINT `pesanan_ibfk_1` FOREIGN KEY (`id_meja`) REFERENCES `meja` (`id_meja`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Table structure for detail_pesanan
CREATE TABLE `detail_pesanan` (
  `id_detail` int(11) NOT NULL AUTO_INCREMENT,
  `id_pesanan` int(11) NOT NULL,
  `id_menu` int(11) NOT NULL,
  `jumlah` int(11) NOT NULL,
  `harga_satuan` decimal(10,2) NOT NULL,
  PRIMARY KEY (`id_detail`),
  KEY `id_pesanan` (`id_pesanan`),
  KEY `id_menu` (`id_menu`),
  CONSTRAINT `detail_pesanan_ibfk_1` FOREIGN KEY (`id_pesanan`) REFERENCES `pesanan` (`id_pesanan`) ON DELETE CASCADE,
  CONSTRAINT `detail_pesanan_ibfk_2` FOREIGN KEY (`id_menu`) REFERENCES `menu` (`id_menu`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


-- ----------------------------
-- INSERT DATA
-- ----------------------------

-- Records of users (Password: admin123, kitchen123)
INSERT INTO `users` (`username`, `password`, `role`) VALUES
('admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin'),
('kitchen', '$2y$10$CwTycUXWue0Thq9StjUM0uJ6QGw.FvZjM5T2iYf6P3k2v1x0z9Y8W', 'kitchen');

-- Records of kategori_menu
INSERT INTO `kategori_menu` (`id_kategori`, `nama_kategori`) VALUES
(1, 'Makanan'),
(2, 'Minuman'),
(3, 'Dessert');

-- Records of meja
INSERT INTO `meja` (`id_meja`, `nomor_meja`, `status`) VALUES
(1, 'Meja 1', 'kosong'), (2, 'Meja 2', 'kosong'), (3, 'Meja 3', 'kosong'), (4, 'Meja 4', 'kosong'), (5, 'Meja 5', 'kosong'),
(6, 'Meja 6', 'kosong'), (7, 'Meja 7', 'kosong'), (8, 'Meja 8', 'kosong'), (9, 'Meja 9', 'kosong'), (10, 'Meja 10', 'kosong');

-- Records of menu
INSERT INTO `menu` (`id_menu`, `nama_menu`, `deskripsi`, `harga`, `gambar`, `id_kategori`) VALUES
(1, 'Nasi Goreng Spesial', 'Nasi goreng dengan telur mata sapi, ayam suwir, dan kerupuk', 25000.00, 'nasgor.jpg', 1),
(2, 'Mie Ayam Bakso', 'Mie ayam dengan bakso urat dan pangsit goreng', 22000.00, 'mieayam.jpg', 1),
(3, 'Ayam Bakar Madu', 'Ayam bakar dengan bumbu madu khas, lengkap dengan sambal dan lalapan', 35000.00, 'ayambakar.jpg', 1),
(4, 'Sop Buntut', 'Sop buntut sapi yang gurih dengan sayuran segar', 55000.00, 'sopbuntut.jpg', 1),
(5, 'Es Teh Manis', 'Teh manis dingin segar', 5000.00, 'esteh.jpg', 2),
(6, 'Jus Alpukat', 'Jus alpukat asli dengan susu dan madu', 15000.00, 'jusavo.jpg', 2),
(7, 'Lemon Tea', 'Teh dengan campuran lemon segar', 12000.00, 'lemontea.jpg', 2),
(8, 'Es Krim Vanilla', 'Es krim vanilla lembut dengan toping coklat', 12000.00, 'eskrim.jpg', 3),
(9, 'Pancake', 'Pancake lembut dengan madu dan potongan buah', 18000.00, 'pancake.jpg', 3),
(10, 'Gorengan', 'Pisang goreng, tempe goreng, tahu goreng', 15000.00, 'gorengan.jpg', 3);

/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IF(@OLD_FOREIGN_KEY_CHECKS IS NULL, 1, @OLD_FOREIGN_KEY_CHECKS) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;