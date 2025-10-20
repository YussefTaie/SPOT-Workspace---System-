-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 20, 2025 at 02:42 AM
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
(24, 'Ahmed Mohamed', 'ahmedelaabd@gmail.com', '01234567899', 'Dentistry', 'Azahar', '$2y$10$o.CV00oy2d.urOi.er6NSO4yQIGn8uS8KLjc//MGwcCxyCJyeVlvi', '2025-10-19 15:07:09', 0, 0, 0.00, '2025-10-19 15:07:09', '2025-10-19 15:07:14', 'qrcodes/24.png');

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
(11, '2025_10_14_134518_add_qr_code_path_to_guests_table', 4);

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `session_id` bigint(20) UNSIGNED NOT NULL,
  `menu_item_id` bigint(20) UNSIGNED NOT NULL,
  `quantity` int(11) NOT NULL DEFAULT 1,
  `status` enum('Pending','Accepted','InProgress','Done','Canceled') NOT NULL DEFAULT 'Pending',
  `ordered_by` varchar(255) DEFAULT NULL,
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

--
-- Dumping data for table `sessions`
--

INSERT INTO `sessions` (`id`, `guest_id`, `table_number`, `check_in`, `check_out`, `duration_minutes`, `rate_per_hour`, `bill_amount`, `created_at`, `updated_at`) VALUES
(69, 17, '1', '2025-10-19 01:16:34', '2025-10-19 01:30:03', 13, 60.00, 13.00, '2025-10-18 22:16:34', '2025-10-18 22:30:03'),
(70, 17, '1', '2025-10-19 01:32:43', '2025-10-19 01:33:32', 0, 60.00, 0.00, '2025-10-18 22:32:43', '2025-10-18 22:33:32'),
(71, 22, '1', '2025-10-19 01:00:40', '2025-10-19 01:56:40', 56, 60.00, 134.40, '2025-10-18 22:43:40', '2025-10-18 22:56:40'),
(72, 15, '1', '2025-10-19 01:12:22', '2025-10-19 02:25:19', 72, 60.00, 72.00, '2025-10-18 22:59:22', '2025-10-18 23:25:19'),
(73, 15, '1', '2025-10-18 01:41:34', '2025-10-18 02:42:26', 60, 60.00, 50.00, '2025-10-18 23:41:34', '2025-10-18 23:42:26'),
(74, 15, '1', '2025-10-18 14:44:27', '2025-10-19 02:45:30', 721, 60.00, 120.00, '2025-10-18 23:44:27', '2025-10-18 23:45:30'),
(75, 15, '1', '2025-10-19 03:30:50', '2025-10-19 04:40:37', 69, 60.00, 50.00, '2025-10-19 01:30:50', '2025-10-19 01:40:37'),
(76, 23, '1', '2025-10-19 04:49:10', '2025-10-19 04:51:23', 2, 60.00, 25.00, '2025-10-19 01:49:10', '2025-10-19 01:51:23'),
(77, 23, '1', '2025-10-19 04:52:00', '2025-10-19 04:52:37', 0, 60.00, 25.00, '2025-10-19 01:52:00', '2025-10-19 01:52:37'),
(78, 15, '1', '2025-10-19 12:50:21', '2025-10-19 13:47:38', 57, 60.00, 25.00, '2025-10-19 09:50:21', '2025-10-19 10:47:38'),
(79, 14, '1', '2025-10-19 12:55:15', '2025-10-19 14:43:09', 107, 60.00, 50.00, '2025-10-19 09:55:15', '2025-10-19 11:43:09'),
(80, 9, '1', '2025-10-19 13:29:53', '2025-10-19 14:43:06', 73, 60.00, 50.00, '2025-10-19 10:29:53', '2025-10-19 11:43:06'),
(81, 20, '1', '2025-10-19 13:49:05', '2025-10-19 14:43:04', 53, 60.00, 25.00, '2025-10-19 10:49:05', '2025-10-19 11:43:04'),
(82, 15, '1', '2025-10-19 18:09:49', NULL, 0, 60.00, 0.00, '2025-10-19 15:09:49', '2025-10-19 15:09:49'),
(83, 24, '1', '2025-10-19 18:10:12', NULL, 0, 60.00, 0.00, '2025-10-19 15:10:12', '2025-10-19 15:10:12');

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
  ADD KEY `orders_menu_item_id_foreign` (`menu_item_id`);

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
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `menu_items`
--
ALTER TABLE `menu_items`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `sessions`
--
ALTER TABLE `sessions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=84;

--
-- AUTO_INCREMENT for table `staff`
--
ALTER TABLE `staff`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_menu_item_id_foreign` FOREIGN KEY (`menu_item_id`) REFERENCES `menu_items` (`id`),
  ADD CONSTRAINT `orders_session_id_foreign` FOREIGN KEY (`session_id`) REFERENCES `sessions` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `sessions`
--
ALTER TABLE `sessions`
  ADD CONSTRAINT `sessions_guest_id_foreign` FOREIGN KEY (`guest_id`) REFERENCES `guests` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
