-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 29, 2025 at 04:36 AM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db_sertifikasi`
--

-- --------------------------------------------------------

--
-- Table structure for table `articles`
--

CREATE TABLE `articles` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `category` varchar(100) NOT NULL,
  `photo` varchar(255) DEFAULT NULL,
  `admin_id` int(11) NOT NULL,
  `published_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `articles`
--

INSERT INTO `articles` (`id`, `title`, `content`, `category`, `photo`, `admin_id`, `published_at`) VALUES
(1, 'Senja di Dolhareubang: Menemukan Ketenangan dalam Secangkir Kopi Enak', 'Selamat datang di DolceVita, di mana setiap tegukan adalah sebuah perjalanan. Saat matahari mulai tenggelam, cahaya jingga yang lembut menyentuh dinding bata kami, menciptakan suasana hangat yang sempurna untuk jeda dari hiruk pikuk kota. Kopi kami bukan sekadar minuman, melainkan sebuah ritual. Kami menyajikan Signature Dolce Latte yang creamy, diracik dari biji kopi pilihan terbaik yang dipanggang dengan sempurna. Dipadukan dengan kelembutan Red Velvet Cake kami yang legendaris, momen ini adalah definisi dari \'kenikmatan sederhana\'. Apakah Anda mencari tempat untuk bekerja, membaca buku, atau sekadar menikmati kebersamaan, biarkan DolceVita menjadi rumah kedua Anda. Kunjungi kami dan rasakan ketenangan dalam setiap cangkir!', 'Kuliner', 'uploads/artikel/1761498570_SenjadiJeju.jpg', 1, '2025-10-26 17:09:30'),
(2, 'Akhir Pekan Ceria: Diskon 30% Semua Menu Dessert!', 'Jangan lewatkan Event Spesial kami akhir pekan ini! Dalam rangka menyambut datangnya musim hujan, DolceVita memberikan diskon 30% untuk semua item Dessert. Mulai dari Tiramisu Klasik kami yang lembut hingga Croissant Almond yang renyah. Sempurnakan malam minggu Anda dengan manisnya dessert terbaik kami. Promo ini hanya berlaku dari Jumat hingga Minggu, mulai pukul 17.00. Buruan datang sebelum kehabisan!', 'Event & Promosi', 'uploads/artikel/1761536462_SenjadiJeju.jpg', 1, '2025-10-27 03:41:02'),
(3, 'Mengapa Kopi Nusantara Selalu Menarik? Mengenal Asal-usulnya', 'Indonesia adalah rumah bagi beberapa biji kopi terbaik di dunia. Dari aroma earthy Mandailing Sumatera hingga citrusy Kintamani Bali, setiap daerah menawarkan profil rasa yang unik. Kami di DolceVita bangga menyajikan kopi single origin lokal, yang bukan hanya tentang rasa, tetapi juga tentang mendukung petani lokal. Keunikan kopi Nusantara terletak pada proses pascapanen tradisional, seperti proses Giling Basah di Aceh. Datang dan coba perbandingan rasa antar pulau yang kami sediakan!', 'Event', 'uploads/artikel/1761536563_SenjadiJeju.jpg', 1, '2025-10-27 03:42:43'),
(4, '5 Trik Rahasia Membuat Kopi Susu Creamy di Rumah', 'Ingin menikmati kopi ala cafe di rumah? Kuncinya ada pada tekstur susu! Trik pertama, selalu gunakan susu dingin dan kocok dengan whisk mini atau french press sebelum dipanaskan. Kedua, jangan pernah mendidihkannya; cukup panaskan hingga uap muncul. Trik ketiga, tambahkan sedikit garam ke biji kopi Anda sebelum digiling untuk menyeimbangkan rasa pahit. Di DolceVita, kami memastikan setiap cangkir memiliki krim yang sempurna. Cobalah tips ini, atau kunjungi kami untuk merasakan kualitas aslinya!', 'Tips & Trik', 'uploads/artikel/1761536621_SenjadiJeju.jpg', 1, '2025-10-27 03:43:41'),
(5, 'Mengenal Lebih Dekat Haenyeo: Filosofi Ketahanan Kopi Jeju', 'Menghubungkan ketahanan dan kerja keras penyelam wanita legendaris Jeju (Haenyeo) dengan filosofi single-origin coffee Anda. Konten ini memberikan kedalaman budaya pada produk Anda.', 'Event', 'uploads/artikel/1761557428_SenjadiJeju.jpg', 1, '2025-10-27 09:30:28'),
(6, 'Rahasia Kue Millet Jeju (Olle-Guksu) dan Pasangannya di Cafe', 'Berfokus pada makanan khas Jeju (millet) yang langka. Jelaskan mengapa kue millet terasa unik dan mengapa minuman kopi seperti Americano dingin atau Hallabong Ade menjadi pasangan yang sempurna. Ini mengangkat menu unik Anda.', 'Kuliner', 'uploads/artikel/1761638121_event.jpg', 4, '2025-10-27 09:38:20'),
(10, 'coba', 'coba coba', 'Event', 'uploads/artikel/1761638193_SenjadiJeju.jpg', 5, '2025-10-28 07:56:33');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `password_hash` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `role` varchar(50) NOT NULL DEFAULT 'pengunjung'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password_hash`, `role`) VALUES
(1, 'baba bibi', 'baba@gmail.com', 'baba1234', 'pengunjung'),
(4, 'boboboy', 'boboy@gmail.com', '$2y$10$5sybRwO4PmHPMQbPtsJS.eGRspjTO89.4z7jnAHQKCLyQQdp1rq0m', 'admin'),
(5, 'duan jiaxu', 'duan@gmail.com', '$2y$10$AwwLho54uMS20HmTxA5lY.igOKRiOCbj8.1iphGryefnPlQWCiWkq', 'admin'),
(6, 'sangzi', 'sangzi@gmail.com', '$2y$10$xTOm8lYME.7D7cRwyXwDIOBHP6odK7pMAGl0SAMI5oO0WbnGF7m2K', 'pengunjung'),
(7, 'Levi Ackerman', 'levi@gmail.com', '$2y$10$6ElVuTQboN1LhSS2FtKbc.5CR640ldPtBOGsoU46EaRlDMWDxIUqi', 'admin'),
(9, 'coba coba coba', 'coba1@gsjkddl', '$2y$10$CLr.sAvlahetxv/GW9T/YeIUdItHha2WzCg8nVMnWSgUfEx3K8B3u', 'pengunjung');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `articles`
--
ALTER TABLE `articles`
  ADD PRIMARY KEY (`id`),
  ADD KEY `admin_id` (`admin_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `articles`
--
ALTER TABLE `articles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `articles`
--
ALTER TABLE `articles`
  ADD CONSTRAINT `articles_ibfk_1` FOREIGN KEY (`admin_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
