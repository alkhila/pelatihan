-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 27, 2025 at 10:28 PM
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
(6, 'Rahasia Kue Millet Jeju (Olle-Guksu) dan Pasangannya di Cafe', 'Berfokus pada makanan khas Jeju (millet) yang langka. Jelaskan mengapa kue millet terasa unik dan mengapa minuman kopi seperti Americano dingin atau Hallabong Ade menjadi pasangan yang sempurna. Ini mengangkat menu unik Anda.', 'Kuliner', 'uploads/artikel/1761557900_SenjadiJeju.jpg', 4, '2025-10-27 09:38:20');

-- --------------------------------------------------------

--
-- Table structure for table `cart`
--

CREATE TABLE `cart` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `order_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `total_amount` decimal(10,2) NOT NULL,
  `status` varchar(50) DEFAULT 'Pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `user_id`, `order_date`, `total_amount`, `status`) VALUES
(1, 5, '2025-10-27 17:05:47', 24500.00, 'Pending');

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `price_at_order` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `order_items`
--

INSERT INTO `order_items` (`id`, `order_id`, `product_id`, `quantity`, `price_at_order`) VALUES
(1, 1, 5, 2, 6000.00),
(2, 1, 4, 1, 12500.00);

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `category` varchar(50) DEFAULT NULL,
  `photo` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `name`, `description`, `price`, `category`, `photo`) VALUES
(1, 'Signature Hallabong Latte', 'Perpaduan unik espresso dengan susu dan sirup Hallabong (jeruk khas Jeju) segar. Manis, creamy, dan ada sentuhan rasa jeruk yang asam menyegarkan.', 7500.00, 'Specialty Coffee', '../img/latte.jpg'),
(2, 'Jeju Green Tea Frappe', 'Minuman dingin yang terbuat dari bubuk teh hijau premium yang dibudidayakan di perkebunan Jeju, diblender dengan es dan sedikit vanila.', 8000.00, 'Non-Coffee', '../img/latte.jpg'),
(3, 'Udo Peanut Ice Cream Croissant', 'Croissant renyah yang diisi dengan es krim vanila lembut dan taburan kacang Udo (Pulau Udo, dekat Jeju). Dessert khas yang gurih dan manis.', 9500.00, 'Dessert', '../img/croissant.jpg'),
(4, 'Black Pork Bulgogi Sandwich', 'Menu makanan berat khas, menggunakan daging babi hitam Jeju yang dimasak bulgogi, disajikan dalam roti sourdough artisan.', 12500.00, 'Meals', '../img/croissant.jpg'),
(5, 'Cold Brew (Single Origin)', 'Kopi seduh dingin 12 jam, menggunakan biji pilihan dengan profil rasa nutty dan cokelat. Pilihan sempurna untuk diminum di tepi pantai Jeju.', 6000.00, 'Specialty Coffee', '../img/latte.jpg');

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
(5, 'duan jiaxu', 'duan@gmail.com', '$2y$10$AwwLho54uMS20HmTxA5lY.igOKRiOCbj8.1iphGryefnPlQWCiWkq', 'pengunjung');

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
-- Indexes for table `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_id` (`order_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`);

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `cart`
--
ALTER TABLE `cart`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `articles`
--
ALTER TABLE `articles`
  ADD CONSTRAINT `articles_ibfk_1` FOREIGN KEY (`admin_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `cart`
--
ALTER TABLE `cart`
  ADD CONSTRAINT `cart_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `cart_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `order_items_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
