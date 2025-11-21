-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 21, 2025 at 08:07 PM
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
-- Database: `spot_database`
--

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `guests`
--

CREATE TABLE `guests` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `fullname` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(255) NOT NULL,
  `college` varchar(255) DEFAULT NULL,
  `university` varchar(255) DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `registered_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `visits_count` int(11) NOT NULL DEFAULT 0,
  `total_time` int(11) NOT NULL DEFAULT 0,
  `total_expenses` decimal(10,2) NOT NULL DEFAULT 0.00,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `qr_code_path` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `guests`
--

INSERT INTO `guests` (`id`, `fullname`, `email`, `phone`, `college`, `university`, `password`, `registered_at`, `visits_count`, `total_time`, `total_expenses`, `created_at`, `updated_at`, `qr_code_path`) VALUES
(1, 'yussef Abdel Rasul', 'yiuwerwe@oitue.com', '1234567890', 'asdad', 'afsa', '123456789', '2025-10-06 20:46:14', 4, 2, 0.00, '2002-01-10 07:00:00', NULL, NULL),
(2, 'adam taie', 'adam@adam.com', '0123456789', 'admin', 'must', '$2y$10$7vZ991ZlA5UiiDfsMpYLGulZmMgJm3JJ/V0i8A.eKale0g7AUcaWK', '2025-10-07 18:21:48', 0, 0, 0.00, '2025-10-07 15:21:48', '2025-10-07 15:21:48', NULL),
(3, 'Ahmed Khaled', 'ahmed@example.com', '01234567890', 'B-Tech', 'CIC', '$2y$10$PVGMF3L/oovEe7wUUq4uH.PHq0tJ/VVYKoFZc7nTnRar2oJ3jVsJK', '2025-10-08 07:08:27', 0, 0, 0.00, '2025-10-08 04:08:27', '2025-10-08 04:08:27', NULL),
(4, 'Ahmed Mohamed', 'ahmed@mohamed.com', '0123456789', 'admin', 'CIC', '$2y$10$EfsP6BBRVtohHZLrEM5LvOR/GNANLPMMZPwNF19R9o0WMN3BfWZ1i', '2025-10-08 08:44:48', 0, 0, 0.00, '2025-10-08 05:44:48', '2025-10-08 05:44:48', NULL),
(5, 'taie', 'taie@gmail.com', '1234567890', 'sdfs', 'sdfsd', '$2y$10$ZSBjyApy/hPRGcT9JLmksOVeOzix/F4n9HWFI8wPL4hxxb/5bkcGi', '2025-10-13 21:10:13', 0, 0, 0.00, '2025-10-13 18:10:13', '2025-10-13 18:10:13', NULL),
(6, 'Ahmed Khaled', 'khaled@outlook.com', '0123456789', 'admin', 'must', '$2y$10$bNX4EGV6USE4cgHTBhlzd.Q9/HZpxe/i.c7iDueUdM44KYmjPbuvq', '2025-10-13 21:19:58', 0, 0, 0.00, '2025-10-13 18:19:58', '2025-10-13 18:19:58', NULL),
(7, 'asd', 'mi@outlook.com', '01234568', 'admin', 'CIC', '$2y$10$LsGUIImVHdw7MzOLJTKMXe6vNf1y14ZFXmQCxvsr/NId6qhxTzy9q', '2025-10-13 21:21:20', 0, 0, 0.00, '2025-10-13 18:21:20', '2025-10-13 18:21:20', NULL),
(8, 'taie', 'aa@gmail.com', '012345678', 'b', 'must', '$2y$10$fw1o.5879lg/pZ7B5lrYwOcQ0FxpBI4obMi2mx9zkctbrRJe/V5pO', '2025-10-14 14:20:01', 0, 0, 0.00, '2025-10-14 11:20:01', '2025-10-14 11:20:01', NULL),
(9, 'Ahmed Khaled', 'ss@gmail.com', '01234568', 'B-Tech', 'must', '$2y$10$NvsRrNF/9Sg901soAKu2POJk.js4cA8tSqodNBj8EeVT7bq8gTwoi', '2025-10-14 14:48:58', 0, 0, 0.00, '2025-10-14 11:48:58', '2025-10-14 11:48:59', 'qrcodes/9.png'),
(10, 'adam taie', 'ii@gmail.com', '0123456789', 'ji', 'ji', '$2y$10$nUMtMLSbg1yUp.jvMMUWdOUc2dZG0djqKFM3FnGdhu1qI/X1F/C..', '2025-10-14 15:32:15', 0, 0, 0.00, '2025-10-14 12:32:15', '2025-10-14 12:32:16', 'qrcodes/10.png'),
(11, 'adam taie', 'si@gmail.com', '0123456789', 'ji', 'ji', '$2y$10$g.lIGTyEq50duIe6d2mSteH0bBJjNoZMLFLQ4rwjdJdx6AzxLOyca', '2025-10-14 17:16:01', 0, 0, 0.00, '2025-10-14 14:16:01', '2025-10-14 14:16:01', NULL),
(12, 'khaled', 'khaled@khaled.com', '0123456789', 'jhki', 'kjii', '$2y$10$HFGJfjeeR.8/d04y7aNLdeAgQlty6HKvc7Lu0uZO5iutpr9XK87JS', '2025-10-14 17:16:46', 0, 0, 0.00, '2025-10-14 14:16:46', '2025-10-14 14:16:46', NULL),
(13, 'taie', 'adshiasdh@quihdihsa.com', '23872389472', 'jhjh', 'uihuih', '$2y$10$xVu8ZrbdaHhu7jJafd7nn.ZQZP4MUvhDC.loxME3ufI0RzxnpXmRe', '2025-10-14 17:18:23', 0, 0, 0.00, '2025-10-14 14:18:23', '2025-10-14 14:18:39', 'qrcodes/13.png'),
(14, 'Ahmed Mohsen', 'ahmedmohsen@gmail.com', '01098764326', 'BIS', 'CIC', '$2y$10$qIZZKhyVuzf/V5oe3k695eanAFutGFKtSmvqtPB92Nx9Y3uANPj7m', '2025-10-14 17:44:23', 0, 0, 0.00, '2025-10-14 14:44:23', '2025-10-14 14:44:24', 'qrcodes/14.png'),
(15, 'Amr Mohsen', 'amr@gmail.com', '0123467897', 'B-Tech', 'CIC', '$2y$10$ew7G4DZaYXSSxjaPHNUjS.xtITB8fIuV4uK84r/vKoAtWUXQL3jKy', '2025-10-14 17:49:39', 0, 0, 0.00, '2025-10-14 14:49:39', '2025-10-14 14:49:40', 'qrcodes/15.png'),
(16, 'noraan', 'noran@gmail.com', '0123456789', 'uiiuy', 'yuiy', '$2y$10$pYX69kC4DGvveLTUPuNAR.tYZHU57bGoZiGtT8bWzKKkPXZ.E2MhO', '2025-10-14 19:38:03', 0, 0, 0.00, '2025-10-14 16:38:03', '2025-10-14 16:38:05', 'qrcodes/16.png'),
(17, 'ahmed sherif', 'tetjudk@gmail.com', '0123456789', 'jdb', 'gfujtkf', '$2y$10$prHWMN26QkTZt88z/y3Wt.Q9zDuPfXUsSyZnwrKoO1RQ1dvVlOuwS', '2025-10-14 20:34:52', 0, 0, 0.00, '2025-10-14 17:34:52', '2025-10-14 17:34:52', NULL),
(18, 'hjh', 'ajkhdam@adam.com', '012345678', 'admin', 'CIC', '$2y$10$2DJdJ6Pf/8irAJPNJJ5By.QANxhIuWeq4AoMGy6SUBBN6Rr/cefjC', '2025-10-14 20:35:37', 0, 0, 0.00, '2025-10-14 17:35:37', '2025-10-14 17:35:37', NULL),
(19, 'Ahmed Khaled', 'yikuwerwe@oitue.com', '0123456789', 'B-Tech', 'CIC', '$2y$10$k/9XJIQuTWbJyLI1q6LVzOFQJqD0XtjJVsDE7fpXpcFy5hhDmGgdW', '2025-10-14 20:38:24', 0, 0, 0.00, '2025-10-14 17:38:24', '2025-10-14 17:38:26', 'qrcodes/19.png'),
(20, 'Ahmed Khaled', 'ahmghfdmed@mohamed.com', '0123456789', 'admin', 'CIC', '$2y$10$sME4dK1czB0yiEVpvcPLK.Bi02jfMMsdnEZBiyAf3nXhCeZnjEtQ2', '2025-10-14 20:39:08', 0, 0, 0.00, '2025-10-14 17:39:08', '2025-10-14 17:39:11', 'qrcodes/20.png'),
(21, 'Mohamed Gaml', 'Gamal@gmail.com', '0123456789', 'CS', 'MUST', '$2y$10$K32qs.Aghx80kPNQpId5juTnYoqOB8K86aEc5APIUxPJd2KfJpBXK', '2025-10-15 17:26:54', 0, 0, 0.00, '2025-10-15 14:26:54', '2025-10-15 14:26:59', 'qrcodes/21.png'),
(22, 'Ahmed Amin', 'amin@gmail.com', '0123456789', 'Batman', 'Ironman', '$2y$10$TvNGvL10/g2Wzpgdqtq.6u32K2QJQembhDjRFbngGGAffeSqLNMkq', '2025-10-17 16:56:40', 0, 0, 0.00, '2025-10-17 13:56:40', '2025-10-17 13:56:41', 'qrcodes/22.png'),
(23, 'hema', 'hema@gmail.com', '01234567890', NULL, NULL, '$2y$10$Hide863s9dq0v1hkRp8z3ue1FshgdI6Qhg6uLmDMPGRS.4/qVm/r2', '2025-10-19 01:48:47', 0, 0, 0.00, '2025-10-19 01:48:47', '2025-10-19 01:48:52', 'qrcodes/23.png'),
(24, 'Ahmed Mohamed', 'ahmedelaabd@gmail.com', '01234567899', 'Dentistry', 'Azahar', '$2y$10$o.CV00oy2d.urOi.er6NSO4yQIGn8uS8KLjc//MGwcCxyCJyeVlvi', '2025-10-19 15:07:09', 0, 0, 0.00, '2025-10-19 15:07:09', '2025-10-19 15:07:14', 'qrcodes/24.png'),
(25, 'asdj', 'aosdha@ishd.com', '876876878', 'kjkjh', 'jhkjk', '$2y$10$MReMVu6x6wldcVLt32LFa.LL/ksxUPt46q4rW5J3xMr3YK3sQgly6', '2025-10-20 13:33:31', 0, 0, 0.00, '2025-10-20 13:33:31', '2025-10-20 13:33:36', 'qrcodes/25.png'),
(26, 'Barista (staff)', 'staff_2@internal.local', '01000000000', NULL, NULL, '$2y$10$RUw720VyKVp7v2N3SkmeHOXG62ER8M8qE5R.GobZ7IJD7M2.CVmlS', '2025-11-14 10:48:07', 0, 0, 0.00, '2025-11-14 10:48:07', '2025-11-14 10:48:07', NULL),
(27, 'qlkwe', 'wklqe@jkj.com', '273628', 'iqwey', 'ieywey', '$2y$10$eSONKrXI5I9i9jCUn4WQNuW/VeZsYkWL3jbfrYNJQpaKidY6.q7k.', '2025-11-19 20:13:16', 0, 0, 0.00, '2025-11-19 21:13:16', '2025-11-19 21:13:21', 'qrcodes/27.png'),
(28, 'woeto', 'iowuriow@uoiwu.com', '2846325', 'wiuery', 'iuyerw', '$2y$10$6kzBr5bF/jomHhmQwrovqejoN96rFyPBUoDlOEnL0WJn7GNKNvCY.', '2025-11-19 21:03:41', 0, 0, 0.00, '2025-11-19 22:03:41', '2025-11-19 22:03:46', 'qrcodes/28.png'),
(29, 'ioqweu', 'wqupoi@iwye.com', '87263786', 'uiqwe', 'uiyiuy', '$2y$10$4IJstnTaYwr1HlW.rPjjI.l0acIukfe1hM308RqNQVep3bGvpjkN2', '2025-11-19 21:35:29', 0, 0, 0.00, '2025-11-19 22:35:29', '2025-11-19 22:35:32', 'qrcodes/29.png'),
(30, 'erwrwr', 'rwer@erwe.com', '234234', 'rwerw', 'rwer', '$2y$10$ccI3TUKsVnuP.Ay7kttu1./XIlxfJJQ6Xo7pxhZyUSo63YqTUyUtK', '2025-11-20 22:05:01', 0, 0, 0.00, '2025-11-20 23:05:01', '2025-11-20 23:05:06', 'qrcodes/30.png'),
(31, 'q9wruq', 'woiru@gmail.com', '892374', 'iwyer', 'ikyiuyi', '$2y$10$d2J.QZbErrIbO5hij.hIAOSpArBEOqL6hxL5gaK1WWf57Eq4yWteC', '2025-11-20 22:09:50', 0, 0, 0.00, '2025-11-20 23:09:50', '2025-11-20 23:09:53', 'qrcodes/31.png'),
(32, 'ioejw', 'opwer@woierf.com', '2093', 'io', 'oi', '$2y$10$IR.ThUA.uNfDqpHQPrNtMu8Y1NdMXEGf3JWMr/whlXDdNl0zLJPum', '2025-11-20 22:16:17', 0, 0, 0.00, '2025-11-20 23:16:17', '2025-11-20 23:16:20', 'qrcodes/32.png'),
(33, 'worj', 'oirweo@ioerw.com', '2389787', 'iuy', 'yiuy', '$2y$10$OH9ekmbnFeoufwcyYjNTk./bv77WoHhO3yRvPVneoFjzBmf7.Q0QK', '2025-11-20 22:20:13', 0, 0, 0.00, '2025-11-20 23:20:13', '2025-11-20 23:20:13', 'qrcodes/33.png'),
(34, 'qolej', 'wioeu@iuh.com', '868876', 'yiuy', 'yiuy', '$2y$10$nlzGgIaG1A44CMaPM9.dqOtIcGQIPEmHdNjIvDWLxapDdaMX9TizC', '2025-11-20 22:23:07', 0, 0, 0.00, '2025-11-20 23:23:07', '2025-11-20 23:23:10', 'qrcodes/34.png');

-- --------------------------------------------------------

--
-- Table structure for table `menu_items`
--

CREATE TABLE `menu_items` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `category` varchar(255) DEFAULT NULL,
  `available` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `menu_items`
--

INSERT INTO `menu_items` (`id`, `name`, `description`, `price`, `image`, `category`, `available`, `created_at`, `updated_at`) VALUES
(89, 'Tea', '', 18.00, NULL, 'Hot Drinks', 1, '2025-11-21 17:31:38', '2025-11-21 17:31:38'),
(90, 'Green tea', '', 20.00, NULL, 'Hot Drinks', 1, '2025-11-21 17:31:38', '2025-11-21 17:31:38'),
(91, 'Herbal Infusion (Anise, Lemon, Ginger, Cinnamon)', '', 15.00, NULL, 'Hot Drinks', 1, '2025-11-21 17:31:38', '2025-11-21 17:31:38'),
(92, 'Hot Ginger Classic', '', 15.00, NULL, 'Hot Drinks', 1, '2025-11-21 17:31:38', '2025-11-21 17:31:38'),
(93, 'Top Secret', '', 25.00, NULL, 'Hot Drinks', 1, '2025-11-21 17:31:38', '2025-11-21 17:31:38'),
(94, 'Hot Cider', '', 30.00, NULL, 'Hot Drinks', 1, '2025-11-21 17:31:38', '2025-11-21 17:31:38'),
(95, 'Hot Orange Classic', '', 30.00, NULL, 'Hot Drinks', 1, '2025-11-21 17:31:38', '2025-11-21 17:31:38'),
(96, 'Tea Latte', '', 25.00, NULL, 'Hot Drinks', 1, '2025-11-21 17:31:38', '2025-11-21 17:31:38'),
(97, 'Sahlab (Nuts / Fruit / Nutella)', '', 33.00, NULL, 'Hot Drinks', 1, '2025-11-21 17:31:38', '2025-11-21 17:31:38'),
(98, 'Hot Chocolate Classic', '', 28.00, NULL, 'Hot Chocolate', 1, '2025-11-21 17:31:38', '2025-11-21 17:31:38'),
(99, 'Hot Chocolate Marshmallow', '', 33.00, NULL, 'Hot Chocolate', 1, '2025-11-21 17:31:38', '2025-11-21 17:31:38'),
(100, 'Hot Oreo', '', 35.00, NULL, 'Hot Chocolate', 1, '2025-11-21 17:31:38', '2025-11-21 17:31:38'),
(101, 'Hot Nutella', '', 38.00, NULL, 'Hot Chocolate', 1, '2025-11-21 17:31:38', '2025-11-21 17:31:38'),
(102, 'Hot Lotus', '', 40.00, NULL, 'Hot Chocolate', 1, '2025-11-21 17:31:38', '2025-11-21 17:31:38'),
(103, 'Hot White Chocolate', '', 38.00, NULL, 'Hot Chocolate', 1, '2025-11-21 17:31:38', '2025-11-21 17:31:38'),
(104, 'Hot Pistachio', '', 45.00, NULL, 'Hot Chocolate', 1, '2025-11-21 17:31:38', '2025-11-21 17:31:38'),
(105, 'Hot Caramel', '', 35.00, NULL, 'Hot Chocolate', 1, '2025-11-21 17:31:38', '2025-11-21 17:31:38'),
(106, 'Turkish Coffee (S / D)', '', 25.00, NULL, 'Hot Coffee', 1, '2025-11-21 17:31:38', '2025-11-21 17:31:38'),
(107, 'French Coffee', '', 30.00, NULL, 'Hot Coffee', 1, '2025-11-21 17:31:38', '2025-11-21 17:31:38'),
(108, 'Hazelnut Coffee (S/D)', '', 35.00, NULL, 'Hot Coffee', 1, '2025-11-21 17:31:38', '2025-11-21 17:31:38'),
(109, 'Espresso(S/D)', '', 30.00, NULL, 'Hot Coffee', 1, '2025-11-21 17:31:38', '2025-11-21 17:31:38'),
(110, 'Macchiato', '', 35.00, NULL, 'Hot Coffee', 1, '2025-11-21 17:31:38', '2025-11-21 17:31:38'),
(111, 'Café Latte', '', 38.00, NULL, 'Hot Coffee', 1, '2025-11-21 17:31:38', '2025-11-21 17:31:38'),
(112, 'Café Mocha', '', 42.00, NULL, 'Hot Coffee', 1, '2025-11-21 17:31:38', '2025-11-21 17:31:38'),
(113, 'Spanish Latte', '', 45.00, NULL, 'Hot Coffee', 1, '2025-11-21 17:31:38', '2025-11-21 17:31:38'),
(114, 'Cappuccino', '', 40.00, NULL, 'Hot Coffee', 1, '2025-11-21 17:31:38', '2025-11-21 17:31:38'),
(115, 'Americano', '', 35.00, NULL, 'Hot Coffee', 1, '2025-11-21 17:31:38', '2025-11-21 17:31:38'),
(116, 'Iced Latte', '', 50.00, NULL, 'Iced Coffee', 1, '2025-11-21 17:31:38', '2025-11-21 17:31:38'),
(117, 'Spanish Latte Iced', '', 65.00, NULL, 'Iced Coffee', 1, '2025-11-21 17:31:38', '2025-11-21 17:31:38'),
(118, 'Iced Cappuccino', '', 60.00, NULL, 'Iced Coffee', 1, '2025-11-21 17:31:38', '2025-11-21 17:31:38'),
(119, 'Iced Americano', '', 50.00, NULL, 'Iced Coffee', 1, '2025-11-21 17:31:38', '2025-11-21 17:31:38'),
(120, 'Pistachio Latte Iced', '', 70.00, NULL, 'Iced Coffee', 1, '2025-11-21 17:31:38', '2025-11-21 17:31:38'),
(121, 'Iced Mocha', '', 55.00, NULL, 'Iced Coffee', 1, '2025-11-21 17:31:38', '2025-11-21 17:31:38'),
(122, 'Iced Blue Coffee', '', 65.00, NULL, 'Iced Coffee', 1, '2025-11-21 17:31:38', '2025-11-21 17:31:38'),
(123, 'Iced Matcha Coffee', '', 75.00, NULL, 'Iced Coffee', 1, '2025-11-21 17:31:38', '2025-11-21 17:31:38'),
(124, 'Florida', '', 60.00, NULL, 'Mocktails', 1, '2025-11-21 17:31:38', '2025-11-21 17:31:38'),
(125, 'Pina Colada', '', 60.00, NULL, 'Mocktails', 1, '2025-11-21 17:31:38', '2025-11-21 17:31:38'),
(126, 'Energy Drink', '', 80.00, NULL, 'Mocktails', 1, '2025-11-21 17:31:38', '2025-11-21 17:31:38'),
(127, 'Florida Fruit', '', 70.00, NULL, 'Mocktails', 1, '2025-11-21 17:31:38', '2025-11-21 17:31:38'),
(128, 'Kiwi Mango', '', 70.00, NULL, 'Mocktails', 1, '2025-11-21 17:31:38', '2025-11-21 17:31:38'),
(129, 'Passion Fruit', '', 60.00, NULL, 'Mocktails', 1, '2025-11-21 17:31:38', '2025-11-21 17:31:38'),
(130, 'Angex', '', 65.00, NULL, 'Mocktails', 1, '2025-11-21 17:31:38', '2025-11-21 17:31:38'),
(131, 'Heaven on Earth', '', 65.00, NULL, 'Mocktails', 1, '2025-11-21 17:31:38', '2025-11-21 17:31:38'),
(132, 'Sex on the Beach', '', 60.00, NULL, 'Mocktails', 1, '2025-11-21 17:31:38', '2025-11-21 17:31:38'),
(133, 'Hazelnut', '', 60.00, NULL, 'Milkshakes', 1, '2025-11-21 17:31:38', '2025-11-21 17:31:38'),
(134, 'Oreo', '', 65.00, NULL, 'Milkshakes', 1, '2025-11-21 17:31:38', '2025-11-21 17:31:38'),
(135, 'Nutella', '', 65.00, NULL, 'Milkshakes', 1, '2025-11-21 17:31:38', '2025-11-21 17:31:38'),
(136, 'Vanilla', '', 55.00, NULL, 'Milkshakes', 1, '2025-11-21 17:31:38', '2025-11-21 17:31:38'),
(137, 'Mango', '', 55.00, NULL, 'Milkshakes', 1, '2025-11-21 17:31:38', '2025-11-21 17:31:38'),
(138, 'Strawberry', '', 55.00, NULL, 'Milkshakes', 1, '2025-11-21 17:31:38', '2025-11-21 17:31:38'),
(139, 'Blueberry', '', 65.00, NULL, 'Milkshakes', 1, '2025-11-21 17:31:38', '2025-11-21 17:31:38'),
(140, 'Raspberry', '', 65.00, NULL, 'Milkshakes', 1, '2025-11-21 17:31:38', '2025-11-21 17:31:38'),
(141, 'Snickers', '', 70.00, NULL, 'Milkshakes', 1, '2025-11-21 17:31:38', '2025-11-21 17:31:38'),
(142, 'Kinder', '', 75.00, NULL, 'Milkshakes', 1, '2025-11-21 17:31:38', '2025-11-21 17:31:38'),
(143, 'Lotus', '', 75.00, NULL, 'Milkshakes', 1, '2025-11-21 17:33:22', '2025-11-21 17:33:22'),
(144, 'Caramel', '', 55.00, NULL, 'Milkshakes', 1, '2025-11-21 17:33:22', '2025-11-21 17:33:22'),
(145, 'Pistachio', '', 80.00, NULL, 'Milkshakes', 1, '2025-11-21 17:33:22', '2025-11-21 17:33:22'),
(146, 'White Chocolate', '', 60.00, NULL, 'Milkshakes', 1, '2025-11-21 17:33:22', '2025-11-21 17:33:22'),
(147, 'Dark Chocolate', '', 65.00, NULL, 'Milkshakes', 1, '2025-11-21 17:33:22', '2025-11-21 17:33:22'),
(148, 'Half & Half', '', 70.00, NULL, 'Milkshakes', 1, '2025-11-21 17:33:22', '2025-11-21 17:33:22'),
(149, 'Mango', '', 50.00, NULL, 'Fresh Juices', 1, '2025-11-21 17:33:22', '2025-11-21 17:33:22'),
(150, 'Guava', '', 45.00, NULL, 'Fresh Juices', 1, '2025-11-21 17:33:22', '2025-11-21 17:33:22'),
(151, 'Strawberry', '', 45.00, NULL, 'Fresh Juices', 1, '2025-11-21 17:33:22', '2025-11-21 17:33:22'),
(152, 'Kiwi Milk', '', 65.00, NULL, 'Fresh Juices', 1, '2025-11-21 17:33:22', '2025-11-21 17:33:22'),
(153, 'Avocado (Nuts / Ice / Mix)', '', 90.00, NULL, 'Fresh Juices', 1, '2025-11-21 17:33:22', '2025-11-21 17:33:22'),
(154, 'Banana Milk', '', 50.00, NULL, 'Fresh Juices', 1, '2025-11-21 17:33:22', '2025-11-21 17:33:22'),
(155, 'Pineapple', '', 50.00, NULL, 'Fresh Juices', 1, '2025-11-21 17:33:22', '2025-11-21 17:33:22'),
(156, 'Cantaloupe', '', 50.00, NULL, 'Fresh Juices', 1, '2025-11-21 17:33:22', '2025-11-21 17:33:22'),
(157, 'Watermelon', '', 50.00, NULL, 'Fresh Juices', 1, '2025-11-21 17:33:22', '2025-11-21 17:33:22'),
(158, 'Mint Lemon', '', 40.00, NULL, 'Fresh Juices', 1, '2025-11-21 17:33:22', '2025-11-21 17:33:22'),
(159, 'Orange', '', 40.00, NULL, 'Fresh Juices', 1, '2025-11-21 17:33:22', '2025-11-21 17:33:22'),
(160, 'Mandarin', '', 55.00, NULL, 'Fresh Juices', 1, '2025-11-21 17:33:22', '2025-11-21 17:33:22'),
(161, 'Strawberry Milk', '', 60.00, NULL, 'Fresh Juices', 1, '2025-11-21 17:33:22', '2025-11-21 17:33:22'),
(162, 'Date Milk', '', 60.00, NULL, 'Fresh Juices', 1, '2025-11-21 17:33:22', '2025-11-21 17:33:22'),
(163, 'Mango', '', 50.00, NULL, 'Smoothies', 1, '2025-11-21 17:33:22', '2025-11-21 17:33:22'),
(164, 'Strawberry', '', 50.00, NULL, 'Smoothies', 1, '2025-11-21 17:33:22', '2025-11-21 17:33:22'),
(165, 'Cantaloupe', '', 50.00, NULL, 'Smoothies', 1, '2025-11-21 17:33:22', '2025-11-21 17:33:22'),
(166, 'Kiwi', '', 70.00, NULL, 'Smoothies', 1, '2025-11-21 17:33:22', '2025-11-21 17:33:22'),
(167, 'Lemon Mint', '', 45.00, NULL, 'Smoothies', 1, '2025-11-21 17:33:22', '2025-11-21 17:33:22'),
(168, 'Blueberry', '', 50.00, NULL, 'Smoothies', 1, '2025-11-21 17:33:22', '2025-11-21 17:33:22'),
(169, 'Mixed Berry', '', 55.00, NULL, 'Smoothies', 1, '2025-11-21 17:33:22', '2025-11-21 17:33:22'),
(170, 'Watermelon', '', 55.00, NULL, 'Smoothies', 1, '2025-11-21 17:33:22', '2025-11-21 17:33:22'),
(171, 'Pineapple', '', 55.00, NULL, 'Smoothies', 1, '2025-11-21 17:33:22', '2025-11-21 17:33:22'),
(172, 'Kiwi Mango', '', 75.00, NULL, 'Smoothies', 1, '2025-11-21 17:33:22', '2025-11-21 17:33:22'),
(173, 'Mix (Customer Choice)', '', 70.00, NULL, 'Smoothies', 1, '2025-11-21 17:33:22', '2025-11-21 17:33:22'),
(174, 'Sun Shine', '', 50.00, NULL, 'Mix Soda', 1, '2025-11-21 17:33:22', '2025-11-21 17:33:22'),
(175, 'Sun Rise', '', 50.00, NULL, 'Mix Soda', 1, '2025-11-21 17:33:22', '2025-11-21 17:33:22'),
(176, 'Blue Sky', '', 55.00, NULL, 'Mix Soda', 1, '2025-11-21 17:33:22', '2025-11-21 17:33:22'),
(177, 'Scotch Mint', '', 50.00, NULL, 'Mix Soda', 1, '2025-11-21 17:33:22', '2025-11-21 17:33:22'),
(178, 'Energy Drink', '', 70.00, NULL, 'Mix Soda', 1, '2025-11-21 17:33:22', '2025-11-21 17:33:22'),
(179, 'Cherry Cola', '', 50.00, NULL, 'Mix Soda', 1, '2025-11-21 17:33:22', '2025-11-21 17:33:22'),
(180, 'Blueberry Soda', '', 50.00, NULL, 'Mix Soda', 1, '2025-11-21 17:33:22', '2025-11-21 17:33:22'),
(181, 'Mixed Berry Soda', '', 55.00, NULL, 'Mix Soda', 1, '2025-11-21 17:33:22', '2025-11-21 17:33:22'),
(182, 'Blue Redbull', '', 75.00, NULL, 'Mix Soda', 1, '2025-11-21 17:33:22', '2025-11-21 17:33:22'),
(183, 'Mojito (Flavored)', '', 55.00, NULL, 'Mix Soda', 1, '2025-11-21 17:33:22', '2025-11-21 17:33:22'),
(184, 'Pina Colada', '', 50.00, NULL, 'Mix Soda', 1, '2025-11-21 17:33:22', '2025-11-21 17:33:22'),
(185, 'Passion Martini', '', 55.00, NULL, 'Mix Soda', 1, '2025-11-21 17:33:22', '2025-11-21 17:33:22'),
(186, 'Vanilla Frappe', '', 65.00, NULL, 'Frappe & Frappuccino', 1, '2025-11-21 17:33:22', '2025-11-21 17:33:22'),
(187, 'Chocolate Frappe', '', 65.00, NULL, 'Frappe & Frappuccino', 1, '2025-11-21 17:33:22', '2025-11-21 17:33:22'),
(188, 'Lotus Frappe', '', 95.00, NULL, 'Frappe & Frappuccino', 1, '2025-11-21 17:33:22', '2025-11-21 17:33:22'),
(189, 'Nutella Frappe', '', 70.00, NULL, 'Frappe & Frappuccino', 1, '2025-11-21 17:33:22', '2025-11-21 17:33:22'),
(190, 'Caramel Frappe', '', 65.00, NULL, 'Frappe & Frappuccino', 1, '2025-11-21 17:33:22', '2025-11-21 17:33:22'),
(191, 'Hazelnut Frappe', '', 65.00, NULL, 'Frappe & Frappuccino', 1, '2025-11-21 17:33:22', '2025-11-21 17:33:22'),
(192, 'White Chocolate Frappe', '', 70.00, NULL, 'Frappe & Frappuccino', 1, '2025-11-21 17:33:22', '2025-11-21 17:33:22'),
(193, 'Matcha Frappe', '', 75.00, NULL, 'Frappe & Frappuccino', 1, '2025-11-21 17:33:22', '2025-11-21 17:33:22'),
(194, 'Hazelnut Frappuccino', '', 70.00, NULL, 'Frappe & Frappuccino', 1, '2025-11-21 17:33:22', '2025-11-21 17:33:22'),
(195, 'Chocolate Frappuccino', '', 70.00, NULL, 'Frappe & Frappuccino', 1, '2025-11-21 17:33:22', '2025-11-21 17:33:22'),
(196, 'Lotus Pistachio Frappuccino', '', 75.00, NULL, 'Frappe & Frappuccino', 1, '2025-11-21 17:33:22', '2025-11-21 17:33:22'),
(197, 'Nutella Vanilla Frappuccino', '', 90.00, NULL, 'Frappe & Frappuccino', 1, '2025-11-21 17:33:22', '2025-11-21 17:33:22'),
(198, 'Coffee Frappuccino', '', 70.00, NULL, 'Frappe & Frappuccino', 1, '2025-11-21 17:33:22', '2025-11-21 17:33:22'),
(199, 'Dark Coffee Frappuccino', '', 70.00, NULL, 'Frappe & Frappuccino', 1, '2025-11-21 17:33:22', '2025-11-21 17:33:22'),
(200, 'Caramel Frappuccino', '', 75.00, NULL, 'Frappe & Frappuccino', 1, '2025-11-21 17:33:22', '2025-11-21 17:33:22'),
(201, 'Zabado (Mango / Strawberry / Blueberry / Fruit)', '', 55.00, NULL, 'Zabado & Ice Cocktail', 1, '2025-11-21 17:33:35', '2025-11-21 17:33:35'),
(202, 'Yogurt Spot', '', 65.00, NULL, 'Zabado & Ice Cocktail', 1, '2025-11-21 17:33:35', '2025-11-21 17:33:35'),
(203, 'Boreo Ice', '', 55.00, NULL, 'Zabado & Ice Cocktail', 1, '2025-11-21 17:33:35', '2025-11-21 17:33:35'),
(204, 'Oreo Ice', '', 60.00, NULL, 'Zabado & Ice Cocktail', 1, '2025-11-21 17:33:35', '2025-11-21 17:33:35'),
(205, 'Hoho\'s Ice', '', 60.00, NULL, 'Zabado & Ice Cocktail', 1, '2025-11-21 17:33:35', '2025-11-21 17:33:35'),
(206, 'Mixed Chocolate', '', 70.00, NULL, 'Zabado & Ice Cocktail', 1, '2025-11-21 17:33:35', '2025-11-21 17:33:35'),
(207, 'Ice Spot', '', 90.00, NULL, 'Zabado & Ice Cocktail', 1, '2025-11-21 17:33:35', '2025-11-21 17:33:35'),
(208, 'Nutella Waffle', '', 65.00, NULL, 'Waffles', 1, '2025-11-21 17:33:35', '2025-11-21 17:33:35'),
(209, 'White Chocolate Waffle', '', 65.00, NULL, 'Waffles', 1, '2025-11-21 17:33:35', '2025-11-21 17:33:35'),
(210, 'Lotus Waffle', '', 75.00, NULL, 'Waffles', 1, '2025-11-21 17:33:35', '2025-11-21 17:33:35'),
(211, 'Pistachio Waffle', '', 90.00, NULL, 'Waffles', 1, '2025-11-21 17:33:35', '2025-11-21 17:33:35'),
(212, 'Four Seasons', '', 70.00, NULL, 'Waffles', 1, '2025-11-21 17:33:35', '2025-11-21 17:33:35');

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2014_10_12_000000_create_users_table', 1),
(2, '2014_10_12_100000_create_password_resets_table', 1),
(3, '2019_08_19_000000_create_failed_jobs_table', 1),
(4, '2019_12_14_000001_create_personal_access_tokens_table', 1),
(5, '2025_10_06_145242_fresh', 2),
(6, '2025_10_06_145316_create_guests_table', 2),
(7, '2025_10_06_145325_create_sessions_table', 2),
(8, '2025_10_06_145334_create_menu_items_table', 2),
(9, '2025_10_06_145524_create_orders_table', 2),
(10, '2025_10_06_155713_create_staff_table', 3),
(11, '2025_10_14_134518_add_qr_code_path_to_guests_table', 4),
(12, '2025_10_14_165923_add_guest_fk_to_sessions_table', 5),
(13, '2025_11_11_124818_add_prices_to_orders_table', 5);

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `session_id` bigint(20) UNSIGNED NOT NULL,
  `menu_item_id` bigint(20) UNSIGNED NOT NULL,
  `quantity` int(11) NOT NULL DEFAULT 1,
  `unit_price` decimal(10,2) DEFAULT NULL,
  `total_price` decimal(10,2) DEFAULT NULL,
  `takeaway` tinyint(1) NOT NULL DEFAULT 0,
  `status` enum('Pending','Accepted','InProgress','Done','Canceled') NOT NULL DEFAULT 'Pending',
  `accepted_at` timestamp NULL DEFAULT NULL,
  `served_at` timestamp NULL DEFAULT NULL,
  `ordered_by` varchar(255) DEFAULT NULL,
  `staff_id` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `password_resets`
--

CREATE TABLE `password_resets` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `personal_access_tokens`
--

CREATE TABLE `personal_access_tokens` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tokenable_type` varchar(255) NOT NULL,
  `tokenable_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `token` varchar(64) NOT NULL,
  `abilities` text DEFAULT NULL,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `guest_id` bigint(20) UNSIGNED NOT NULL,
  `table_number` varchar(255) DEFAULT NULL,
  `check_in` datetime NOT NULL,
  `check_out` datetime DEFAULT NULL,
  `duration_minutes` int(11) NOT NULL DEFAULT 0,
  `rate_per_hour` decimal(10,2) NOT NULL DEFAULT 0.00,
  `bill_amount` decimal(10,2) NOT NULL DEFAULT 0.00,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `staff`
--

CREATE TABLE `staff` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','barista','manager') NOT NULL DEFAULT 'barista',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `staff`
--

INSERT INTO `staff` (`id`, `name`, `email`, `phone`, `password`, `role`, `created_at`, `updated_at`) VALUES
(1, 'Admin', 'admin@spot.com', '01000000000', '$2y$10$w4uecoe1Ki8dc1pkF.V0juqU2dH4GRBYJoXJMPyVZrcNDsOKT8pz2', 'admin', '2025-11-10 22:55:07', '2025-11-10 22:55:07'),
(2, 'Barista', 'barista@spot.com', '01000000000', '$2y$10$V5uRmYnzeFNrcnq69XIjlumka3XECQdlwfhT85Ah1NcGIICfvQTfO', 'barista', '2025-11-13 21:21:11', '2025-11-13 21:21:11');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `guests`
--
ALTER TABLE `guests`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `guests_email_unique` (`email`);

--
-- Indexes for table `menu_items`
--
ALTER TABLE `menu_items`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `orders_session_id_foreign` (`session_id`),
  ADD KEY `orders_menu_item_id_foreign` (`menu_item_id`),
  ADD KEY `orders_staff_id_foreign` (`staff_id`);

--
-- Indexes for table `password_resets`
--
ALTER TABLE `password_resets`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_guest_id_foreign` (`guest_id`);

--
-- Indexes for table `staff`
--
ALTER TABLE `staff`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `staff_email_unique` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `guests`
--
ALTER TABLE `guests`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- AUTO_INCREMENT for table `menu_items`
--
ALTER TABLE `menu_items`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=213;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=62;

--
-- AUTO_INCREMENT for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `sessions`
--
ALTER TABLE `sessions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=135;

--
-- AUTO_INCREMENT for table `staff`
--
ALTER TABLE `staff`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_menu_item_id_foreign` FOREIGN KEY (`menu_item_id`) REFERENCES `menu_items` (`id`),
  ADD CONSTRAINT `orders_session_id_foreign` FOREIGN KEY (`session_id`) REFERENCES `sessions` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `orders_staff_id_foreign` FOREIGN KEY (`staff_id`) REFERENCES `staff` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `sessions`
--
ALTER TABLE `sessions`
  ADD CONSTRAINT `sessions_guest_id_foreign` FOREIGN KEY (`guest_id`) REFERENCES `guests` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
