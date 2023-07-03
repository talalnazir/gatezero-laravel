-- phpMyAdmin SQL Dump
-- version 4.9.0.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 23, 2022 at 07:05 AM
-- Server version: 10.4.6-MariaDB
-- PHP Version: 7.3.9

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `takeofpoint`
--

-- --------------------------------------------------------

--
-- Table structure for table `evaluation`
--

CREATE TABLE `evaluation` (
  `evaluation_id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `initiative_name` varchar(150) DEFAULT NULL,
  `scorecard_name` varchar(150) DEFAULT NULL,
  `situation` varchar(150) DEFAULT NULL,
  `version` int(255) DEFAULT 1,
  `opt_score` int(255) DEFAULT NULL,
  `execution_score` int(255) DEFAULT NULL,
  `json` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL
) ;

--
-- Dumping data for table `evaluation`
--

INSERT INTO `evaluation` (`evaluation_id`, `user_id`, `initiative_name`, `scorecard_name`, `situation`, `version`, `opt_score`, `execution_score`, `json`, `created_at`, `updated_at`) VALUES
(721, 69, 'asd', 'asd', '2', 1, NULL, NULL, '{\"Initiative\":{\"fullname\":\"asd\",\"scorecard\":\"asd\",\"situation\":\"2\"},\"User\":{\"name\":\"wincom42\",\"country\":\"India\",\"email\":\"wincom42@gmail.com\",\"working\":\"entrepreneurship\",\"role\":\"user\",\"created_at\":\"2022-05-20T07:55:49.000000Z\",\"updated_at\":\"2022-05-20T07:55:49.000000Z\"},\"customeractorstakeholder\":{\"customer\":\"\",\"actor\":\"\",\"stakeholder\":\"\"},\"buyermotivation\":{\"score\":\"2\"},\"purchasedecisionalignment\":{\"customer\":\"true\",\"actor\":\"true\",\"stakeholders\":\"true\",\"total\":5},\"revenuescore\":{\"mentalmodelbarrier\":\"0\",\"pricingband\":\"1\",\"pricingmodel\":\"1\",\"total\":10}}', '2022-05-20 13:22:11', '2022-05-20 13:43:40');

-- --------------------------------------------------------

--
-- Table structure for table `steps`
--

CREATE TABLE `steps` (
  `steps_id` bigint(20) UNSIGNED NOT NULL,
  `evaluation_id` bigint(20) UNSIGNED NOT NULL,
  `form_name` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `country` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `working` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `role` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `country`, `email`, `password`, `working`, `remember_token`, `role`, `created_at`, `updated_at`) VALUES
(66, 'admin', 'India', 'admin@gmail.com', '$2y$10$ELt8Yn26ajEcoxSZR0XfM.6gslxHCN4aQtvtBHNnrQWSPbjFVnsvq', '2', NULL, 'admin', '2022-05-13 06:36:42', '2022-05-13 06:36:42'),
(69, 'wincom42', 'India', 'wincom42@gmail.com', '$2y$10$GUsNfMuV0QKFljEKBRIpUOfTEjwjii3SJuGMH4c95Lt3fB2AkM5ha', 'entrepreneurship', NULL, 'user', '2022-05-20 07:55:49', '2022-05-20 07:55:49');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `steps`
--
ALTER TABLE `steps`
  ADD PRIMARY KEY (`steps_id`),
  ADD KEY `evaluation_id` (`evaluation_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `evaluation`
--
ALTER TABLE `evaluation`
  MODIFY `evaluation_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `steps`
--
ALTER TABLE `steps`
  MODIFY `steps_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=70;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `steps`
--
ALTER TABLE `steps`
  ADD CONSTRAINT `steps_ibfk_1` FOREIGN KEY (`evaluation_id`) REFERENCES `evaluation` (`evaluation_id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
