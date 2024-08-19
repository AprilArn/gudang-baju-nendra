-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 01, 2024 at 11:09 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `gudang_baju`
--

-- --------------------------------------------------------

--
-- Table structure for table `jenis`
--

CREATE TABLE `jenis` (
  `id_jenis` char(1) NOT NULL,
  `nama_jenis` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `jenis`
--

INSERT INTO `jenis` (`id_jenis`, `nama_jenis`) VALUES
('N', 'New'),
('S', 'Sold'),
('T', 'Thrift');

-- --------------------------------------------------------

--
-- Table structure for table `kategori`
--

CREATE TABLE `kategori` (
  `id_kategori` char(1) NOT NULL,
  `nama_kategori` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `kategori`
--

INSERT INTO `kategori` (`id_kategori`, `nama_kategori`) VALUES
('A', 'Atasan'),
('C', 'Celana'),
('D', 'Dress'),
('J', 'Jaket'),
('S', 'Setelan');

-- --------------------------------------------------------

--
-- Table structure for table `produk`
--

CREATE TABLE `produk` (
  `id_produk` char(6) NOT NULL,
  `id_jenis` char(1) NOT NULL,
  `id_kategori` char(1) NOT NULL,
  `nama_produk` varchar(100) NOT NULL,
  `harga_produk` decimal(11,2) NOT NULL,
  `link_ig` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Triggers `produk`
--
DELIMITER $$
CREATE TRIGGER `after_delete_produk` AFTER DELETE ON `produk` FOR EACH ROW BEGIN
    INSERT INTO produk_terhapus (id_produk) VALUES (OLD.id_produk);
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `before_insert_produk` BEFORE INSERT ON `produk` FOR EACH ROW BEGIN
    DECLARE counter INT;
    DECLARE available_id CHAR(6);

    -- Cek apakah ada ID yang tersedia dari produk yang terhapus
    SELECT id_produk INTO available_id
    FROM produk_terhapus
    WHERE id_produk LIKE CONCAT(NEW.id_jenis, NEW.id_kategori, '%')
    LIMIT 1;

    IF available_id IS NOT NULL THEN
        -- Gunakan ID yang tersedia
        SET NEW.id_produk = available_id;
        DELETE FROM produk_terhapus WHERE id_produk = available_id;
    ELSE
        -- Buat ID baru
        SET counter = (
            SELECT COALESCE(MAX(CAST(SUBSTRING(id_produk, 3, 4) AS UNSIGNED)), 0) + 1
            FROM produk
            WHERE id_jenis = NEW.id_jenis AND id_kategori = NEW.id_kategori
        );
        SET NEW.id_produk = CONCAT(NEW.id_jenis, NEW.id_kategori, LPAD(counter, 4, '0'));
    END IF;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `produk_terhapus`
--

CREATE TABLE `produk_terhapus` (
  `id_produk` char(6) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `jenis`
--
ALTER TABLE `jenis`
  ADD PRIMARY KEY (`id_jenis`);

--
-- Indexes for table `kategori`
--
ALTER TABLE `kategori`
  ADD PRIMARY KEY (`id_kategori`);

--
-- Indexes for table `produk`
--
ALTER TABLE `produk`
  ADD PRIMARY KEY (`id_produk`),
  ADD KEY `id_jenis` (`id_jenis`),
  ADD KEY `id_kategori` (`id_kategori`);

--
-- Indexes for table `produk_terhapus`
--
ALTER TABLE `produk_terhapus`
  ADD PRIMARY KEY (`id_produk`);

--
-- Constraints for dumped tables
--

--
-- Constraints for table `produk`
--
ALTER TABLE `produk`
  ADD CONSTRAINT `produk_ibfk_1` FOREIGN KEY (`id_jenis`) REFERENCES `jenis` (`id_jenis`),
  ADD CONSTRAINT `produk_ibfk_2` FOREIGN KEY (`id_kategori`) REFERENCES `kategori` (`id_kategori`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
