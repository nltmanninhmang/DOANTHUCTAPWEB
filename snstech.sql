-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Máy chủ: 127.0.0.1:3306
-- Thời gian đã tạo: Th5 17, 2025 lúc 06:24 AM
-- Phiên bản máy phục vụ: 8.3.0
-- Phiên bản PHP: 8.2.18

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Cơ sở dữ liệu: `snstech`
--

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `orders`
--

DROP TABLE IF EXISTS `orders`;
CREATE TABLE IF NOT EXISTS `orders` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `phone` varchar(15) NOT NULL,
  `address` text NOT NULL,
  `email` varchar(255) NOT NULL,
  `payment_method` enum('cash','qr') NOT NULL,
  `total` int NOT NULL,
  `status` enum('Đang xử lý','Đang giao','Thành công') CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT 'Đang xử lý',
  `items` text NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Đang đổ dữ liệu cho bảng `orders`
--

INSERT INTO `orders` (`id`, `user_id`, `name`, `phone`, `address`, `email`, `payment_method`, `total`, `status`, `items`, `created_at`) VALUES
(1, 3, 'Nguyễn Lê Trần Minh', '0375567051', '228 Văn Thánh', 'nguyenletranminh2506@gmail.com', 'qr', 100000000, 'Đang giao', '[{\"id\":7,\"name\":\"Flycam Mavic Air 4\",\"price\":100000000,\"name_image\":\"\\/Uploads\\/1747117013_Mavic4Pro_box-picture.jpg\",\"quantity\":1},{\"id\":5,\"name\":\"Box Kh\\u00f4ng Phone\",\"price\":45000000,\"name_image\":\"\\/Uploads\\/1747116953_images.jfif\",\"quantity\":1}]', '2025-05-16 14:37:16'),
(2, 3, 'Nguyễn Lê Trần Minh', '0375567051', '228 Văn Thánh', 'nguyenletranminh2506@gmail.com', 'qr', 100000000, 'Thành công', '[{\"id\":7,\"name\":\"Flycam Mavic Air 4\",\"price\":100000000,\"name_image\":\"\\/Uploads\\/1747117013_Mavic4Pro_box-picture.jpg\",\"quantity\":1}]', '2025-05-16 15:13:01'),
(3, 3, 'Nguyễn Lê Trần Minh', '0375567051', '228 Văn Thánh', 'nguyenletranminh2506@gmail.com', 'cash', 10000000, 'Đang giao', '[{\"id\":3,\"name\":\"Samsung J7 Pro\",\"price\":10000000,\"name_image\":\"\\/Uploads\\/1747115668_images.jfif\",\"quantity\":1}]', '2025-05-17 08:13:54'),
(4, 3, 'Nguyễn Lê Trần Minh', '0375567051', '228 Văn Thánh', 'nguyenletranminh2506@gmail.com', 'cash', 145000000, 'Thành công', '[{\"id\":5,\"name\":\"Box Kh\\u00f4ng Phone\",\"price\":45000000,\"name_image\":\"\\/Uploads\\/1747116953_images.jfif\",\"quantity\":1},{\"id\":7,\"name\":\"Flycam Mavic Air 4\",\"price\":100000000,\"name_image\":\"\\/Uploads\\/1747117013_Mavic4Pro_box-picture.jpg\",\"quantity\":1}]', '2025-05-17 12:17:59');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `sanpham`
--

DROP TABLE IF EXISTS `sanpham`;
CREATE TABLE IF NOT EXISTS `sanpham` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `danhmuc` varchar(100) NOT NULL,
  `amount` int NOT NULL,
  `price` int NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `name_image` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Đang đổ dữ liệu cho bảng `sanpham`
--

INSERT INTO `sanpham` (`id`, `name`, `danhmuc`, `amount`, `price`, `created_at`, `name_image`) VALUES
(3, 'Samsung J7 Pro', 'boxphone', 1000, 10000000, '2025-01-20 03:45:48', '/Uploads/1747115668_images.jfif'),
(4, 'Samsung Note 8', 'boxphone', 1000, 15000000, '2025-01-20 03:49:35', '/Uploads/1747115823_images.jfif'),
(5, 'Box Không Phone', 'linhkien', 1000, 45000000, '2025-01-20 06:11:25', '/Uploads/1747116953_images.jfif'),
(7, 'Flycam Mavic Air 4', 'flycam', 100, 100000000, '2025-05-13 05:52:07', '/Uploads/1747117013_Mavic4Pro_box-picture.jpg');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `settings`
--

DROP TABLE IF EXISTS `settings`;
CREATE TABLE IF NOT EXISTS `settings` (
  `id` int NOT NULL AUTO_INCREMENT,
  `website_name` varchar(255) NOT NULL,
  `phone` int NOT NULL,
  `address` text NOT NULL,
  `email` varchar(255) NOT NULL,
  `logo` varchar(255) DEFAULT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_settings` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Đang đổ dữ liệu cho bảng `settings`
--

INSERT INTO `settings` (`id`, `website_name`, `phone`, `address`, `email`, `logo`, `updated_at`) VALUES
(1, 'SNSTECH', 375567999, 'Số nhà 1, Khu đô thị Manor Crown', 'contact@snstech.com', '/Uploads/logo_1747357927.png', '2025-05-16 08:19:42');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `level` int DEFAULT '1',
  `phone` varchar(15) DEFAULT NULL,
  `address` text,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Đang đổ dữ liệu cho bảng `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `created_at`, `level`, `phone`, `address`) VALUES
(3, 'Nguyễn Lê Trần Minh', 'nguyenletranminh2506@gmail.com', '$2y$10$m6b3ZZUr68DF8E1MGmS//OUiFPftkyW3nWwWvD9HScLUreSRpKOgy', '2025-01-16 11:39:55', 2, '0375567051', '228 Văn Thánh');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
