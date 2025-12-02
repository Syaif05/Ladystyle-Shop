-- phpMyAdmin SQL Dump
-- version 6.0.0-dev+20251119.dfcf3dd949
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Dec 02, 2025 at 07:46 AM
-- Server version: 8.4.3
-- PHP Version: 8.3.26

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `ladystyle_shop_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` int NOT NULL,
  `name` varchar(100) NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`, `created_at`) VALUES
(1, 'Dress', '2025-12-02 05:17:21'),
(2, 'Blouse', '2025-12-02 05:17:21'),
(3, 'Hijab', '2025-12-02 05:17:21'),
(4, 'Celana', '2025-12-02 05:17:21'),
(5, 'Aksesoris', '2025-12-02 05:17:21');

-- --------------------------------------------------------

--
-- Table structure for table `landing_settings`
--

CREATE TABLE `landing_settings` (
  `id` int NOT NULL,
  `hero_title` varchar(255) NOT NULL,
  `hero_subtitle` text NOT NULL,
  `hero_image` varchar(255) NOT NULL,
  `cta_text` varchar(50) DEFAULT 'Belanja Sekarang',
  `cta_link` varchar(255) DEFAULT 'products.php',
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `landing_settings`
--

INSERT INTO `landing_settings` (`id`, `hero_title`, `hero_subtitle`, `hero_image`, `cta_text`, `cta_link`, `updated_at`) VALUES
(1, 'Tampil Cantik Setiap Hari', 'Temukan fashion wanita terbaik dengan bahan premium dan desain kekinian.', 'https://images.unsplash.com/photo-1483985988355-763728e1935b?q=80&w=2070&auto=format&fit=crop', 'Belanja Sekarang', 'products.php', '2025-12-02 06:41:35');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int NOT NULL,
  `order_code` varchar(50) NOT NULL,
  `product_id` int NOT NULL,
  `customer_name` varchar(150) NOT NULL,
  `customer_phone` varchar(50) NOT NULL,
  `customer_address` text NOT NULL,
  `shipping_courier` varchar(50) DEFAULT NULL,
  `shipping_service` varchar(50) DEFAULT NULL,
  `shipping_cost` int DEFAULT '0',
  `payment_method` varchar(50) DEFAULT NULL,
  `payment_fee` int DEFAULT '0',
  `platform_fee` int DEFAULT '1000',
  `size` varchar(20) DEFAULT NULL,
  `qty` int NOT NULL,
  `total_price` int NOT NULL,
  `grand_total` int DEFAULT '0',
  `status` enum('baru','diproses','dikirim','selesai','dibatalkan') NOT NULL DEFAULT 'baru',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int NOT NULL,
  `category_id` int NOT NULL,
  `name` varchar(150) NOT NULL,
  `price` int NOT NULL,
  `stock` int NOT NULL DEFAULT '0',
  `size_available` varchar(100) DEFAULT NULL,
  `color` varchar(100) DEFAULT NULL,
  `description` text,
  `image` varchar(255) DEFAULT NULL,
  `status` enum('active','inactive') NOT NULL DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `category_id`, `name`, `price`, `stock`, `size_available`, `color`, `description`, `image`, `status`, `created_at`, `updated_at`) VALUES
(4, 1, 'M&S - Dress Wanita - Denim V-Neck Tie Detail Midi Waisted Dress', 1199900, 200, 'All Size', 'Standard', 'SPU : MSS9810-T42\r\n\r\n\r\n\r\nThis pure cotton denim dress is a versatile addition to your warm-weather wardrobe. It\'s cut in a regular fit with three-quarter sleeves that are slightly puffed for a touch of volume. The flattering v-neckline and self-tie details put a feminine spin on this wardrobe staple. This piece is finished with handy side pockets and a discreet zip fastening.  Composition\r\n\r\n100% cotton (exclusive of trimmings) Sleeve lining - 100% polyester', 'https://down-id.img.susercontent.com/file/sg-11134201-7rdxv-md1buxw7j2f44e', 'active', '2025-12-02 06:53:34', NULL),
(5, 1, 'M&S - Dress Wanita - Linen Rich V-Neck Midaxi Tiered Dress', 559000, 200, 'All Size', 'Standard', 'SPU : MSS0616-T52\r\n\r\n\r\n\r\nElevate your off-duty style with this lightweight linen-rich dress. This effortless design is cut to an easy regular fit, featuring a tiered midaxi-length skirt to add swishy movement to the silhouette. Elasticated cuffs on the long sleeves offer a subtle touch of volume, while a v-shaped neck provides a feminine finish.\r\n\r\n  M&S Collection: easy-to-wear wardrobe staples that combine classic and contemporary styles.Care and composition Composition 55% flax, 45% viscose (exclusive of trimmings) Care instructions', 'https://down-id.img.susercontent.com/file/sg-11134201-825au-mgdx7er9hfyj39', 'active', '2025-12-02 06:54:31', NULL),
(6, 1, 'Ghaudens - Long Dress Giana Button Rayon Guardian 6868-5/6', 150000, 200, 'All Size', 'Standard', 'Bahan Rayon Guardian\r\n\r\n\r\n\r\nSize M (reguler) LD 100cm , PANJANG 120cm. referensi : BB 40-65kg\r\n\r\nSize XL (bigsize) LD 120cm , PANJANG 130cm. referensi : BB 66-85kg\r\n\r\n\r\n\r\nLengan Panjang ada karet (pergelangan tangan)\r\n\r\nAda karet dibagian pinggang\r\n\r\nBusui bumil friendly\r\n\r\nBuka video hingga akhir utk melihat detail produk\r\n\r\n\r\n\r\nnotes : REAL FOTO DAN VIDEO BY GHAUDENS! Dilarang keras mencuri atau mengambil foto tnp persetujuan!\r\n\r\n______________________________________________________________\r\n\r\nJgn tanya BB sekian2 muat apa tidak ya, ukur dgn detail yg telah disediakan. Karna BB itu relatif, bentuk badan setiap org itu berbeda.\r\n\r\nBb model 50kg, tinggi 160cm. Semua produk realpict!\r\n\r\nChat akan dibales sesuai urutan dari bawah ya jika belum dibales dapat lgsng diorder karna semua produk yg masih bisa diklik READY.', 'https://down-id.img.susercontent.com/file/id-11134207-7r98x-ltowwhyeacdf10.webp', 'active', '2025-12-02 06:55:38', NULL),
(7, 2, 'QUEENTIN Blouse Wanita Batwing 1167 Cotton Wash Polos Lengan Pendek', 117000, 200, 'All Size', 'Standard', 'Material: Cotton Wash\r\n\r\nKarakter Bahan Halus, Ringan & Breathable\r\n\r\n\r\n\r\nSize Chart\r\n\r\nLingkar Dada    : 112\r\n\r\nLingkar Ketiak   : 54\r\n\r\nPanjang Bahu   : 89\r\n\r\nPanjang Badan : 58\r\n\r\nLingkar Manset : 32 \r\n\r\n\r\n\r\n✨ Komplain/Retur diterima jika ada video unboxing yang lengkap menunjukan bagian yg dikeluhkan\r\n\r\n✨ Chat admin terlebih dahulu sebelum memberikan rating, kami siap memberikan solusi untuk setiap masalah Anda\r\n\r\n✨ Tidak menerima komplain dan retur setelah 3x24 jam dari barang di terima customer\r\n\r\n✨ Lengkap hangtag \r\n\r\n✨ Tidak ada bau parfum \r\n\r\n✨ Tidak dicuci\r\n\r\n✨ Sertai video unboxing yang lengkap (tidak boleh pause, video bahan detail kualitas yang tidak sesuai)\r\n\r\n\r\n\r\n⚠️ Penting ⚠️ \r\n\r\n* 𝙎𝙚𝙢𝙪𝙖 𝙛𝙤𝙩𝙤 𝙍𝙀𝘼𝙇 𝙋𝙄𝘾 \r\n\r\n* Semua ukuran bisa berbeda 1 - 3 cm sebagaimana diproduksi massal\r\n\r\n* Warna di photo bisa berbeda dengan fisik tergantung dari settingan display layar\r\n\r\n* Chat Admin terlebih dahulu sebelum memberikan rating, kami siap memberikan solusi untuk setiap masalah Anda\r\n\r\n* Ingat kami tidak menerima complain dan retur setelah 3x24 jam dari barang di terima customer\r\n\r\n\r\n\r\nCustomer Service: \r\n\r\n- Setiap Hari 08.00 - 22.00 WIB\r\n\r\n\r\n\r\nShipping: \r\n\r\n- Senin - Jumat (08.00 - 16.00 WIB)\r\n\r\n- Sabtu (08.00 - 14.00 WIB)\r\n\r\n\r\n\r\n*Minggu dan tanggal merah LIBUR\r\n\r\n*Pengiriman dilakukan dihari yang sama, kecuali minggu dan tanggal merah akan dikirim di hari berikutnya', 'https://down-id.img.susercontent.com/file/id-11134207-7ra0q-md49ww3xr5ure5.webp', 'active', '2025-12-02 06:57:04', NULL),
(8, 2, 'Savina Blouse Bordir Linen Rami Warna Navy', 85000, 200, 'All Size', 'Standard', '', 'https://down-id.img.susercontent.com/file/id-11134207-82251-mgvzysljbq4r3c.webp', 'active', '2025-12-02 06:57:49', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `product_gallery`
--

CREATE TABLE `product_gallery` (
  `id` int NOT NULL,
  `product_id` int NOT NULL,
  `image_url` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `product_gallery`
--

INSERT INTO `product_gallery` (`id`, `product_id`, `image_url`, `created_at`) VALUES
(1, 4, 'https://down-id.img.susercontent.com/file/sg-11134201-7rdyc-md1buz23sb7b4b', '2025-12-02 06:53:34'),
(2, 4, 'https://down-id.img.susercontent.com/file/sg-11134201-7rdyg-md1buyiom57q79', '2025-12-02 06:53:34'),
(3, 4, 'https://down-id.img.susercontent.com/file/sg-11134201-7rdwn-md1buzkf1jwgf1', '2025-12-02 06:53:34'),
(4, 5, 'https://down-id.img.susercontent.com/file/sg-11134201-8259n-mgdx7f5eiqdrd9', '2025-12-02 06:54:31'),
(5, 5, 'https://down-id.img.susercontent.com/file/sg-11134201-8258k-mgdx7fl817grd2.webp', '2025-12-02 06:54:31'),
(6, 5, 'https://down-id.img.susercontent.com/file/sg-11134201-825ad-mgdx7g00lf6558.webp', '2025-12-02 06:54:31'),
(7, 5, 'https://down-id.img.susercontent.com/file/sg-11134201-825ad-mgdx7g00lf6558.webp', '2025-12-02 06:54:31'),
(8, 6, 'https://down-id.img.susercontent.com/file/id-11134207-7r98s-ltowwhyeabug3d.webp', '2025-12-02 06:55:38'),
(9, 6, 'https://down-id.img.susercontent.com/file/id-11134207-7r98p-ltowwhyeacd6db.webp', '2025-12-02 06:55:38'),
(10, 6, 'https://down-id.img.susercontent.com/file/id-11134207-7r98u-ltowwhyeacjh02.webp', '2025-12-02 06:55:38'),
(11, 6, 'https://down-id.img.susercontent.com/file/id-11134207-7r98u-ltowwhyeacjh02.webp', '2025-12-02 06:55:38'),
(12, 7, 'https://down-id.img.susercontent.com/file/id-11134207-7ra0h-mcvu5blfcxto14.webp', '2025-12-02 06:57:04'),
(13, 7, 'https://down-id.img.susercontent.com/file/id-11134207-7ra0l-mcvu5blfece460.webp', '2025-12-02 06:57:04'),
(14, 7, 'https://down-id.img.susercontent.com/file/id-11134207-7ra0p-mcvu5blfh5j0dd.webp', '2025-12-02 06:57:04'),
(15, 7, 'https://down-id.img.susercontent.com/file/id-11134207-7ra0p-mcvu5blfh5j0dd.webp', '2025-12-02 06:57:04'),
(16, 7, 'https://down-id.img.susercontent.com/file/id-11134207-7ra0o-mcvu5blfik3gb0.webp', '2025-12-02 06:57:04'),
(17, 7, 'https://down-id.img.susercontent.com/file/id-11134207-7ra0m-mcvu5blfmrss70.webp', '2025-12-02 06:57:04'),
(18, 8, 'https://down-id.img.susercontent.com/file/id-11134207-82250-mgvzysc9og0cd6.webp', '2025-12-02 06:57:49'),
(19, 8, 'https://down-id.img.susercontent.com/file/id-11134207-82250-mgvzysiopgjw7a.webp', '2025-12-02 06:57:49'),
(20, 8, 'https://down-id.img.susercontent.com/file/id-11134207-82252-mgvzysc9n1fwf3.webp', '2025-12-02 06:57:49'),
(21, 8, 'https://down-id.img.susercontent.com/file/id-11134207-8224x-mgvzysjb6jnv39.webp', '2025-12-02 06:57:49'),
(22, 8, 'https://down-id.img.susercontent.com/file/id-11134207-8224v-mgvzysdi8kju96.webp', '2025-12-02 06:57:49');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `avatar` varchar(255) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `address` text,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','staff','customer') DEFAULT 'customer',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `avatar`, `phone`, `address`, `password`, `role`, `created_at`) VALUES
(1, 'Administrator', 'admin@ladystyle.local', NULL, NULL, NULL, 'admin123', 'admin', '2025-12-02 05:17:21'),
(2, 'agus', 'agus@gmail.com', NULL, 'admin', 'jalan jalan, kota kota', 'lady123', 'customer', '2025-12-02 06:17:24'),
(3, 'KING MU', 'syaifullohrohmat05@gmail.com', 'assets/images/profiles/KINGMU-8288.jpg', 'admin', '', 'password', 'customer', '2025-12-02 06:23:34'),
(4, 'Ucup', 'ucup@gmail.com', NULL, NULL, NULL, 'lady123', 'customer', '2025-12-02 06:29:23');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `landing_settings`
--
ALTER TABLE `landing_settings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `order_code` (`order_code`),
  ADD KEY `fk_orders_product` (`product_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_products_category` (`category_id`);

--
-- Indexes for table `product_gallery`
--
ALTER TABLE `product_gallery`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `product_gallery`
--
ALTER TABLE `product_gallery`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `fk_orders_product` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE RESTRICT;

--
-- Constraints for table `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `fk_products_category` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE RESTRICT;

--
-- Constraints for table `product_gallery`
--
ALTER TABLE `product_gallery`
  ADD CONSTRAINT `product_gallery_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
