-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 13, 2025 at 02:59 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `cityevchargers`
--

-- --------------------------------------------------------

--
-- Table structure for table `checkins`
--

CREATE TABLE `checkins` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `location_id` int(11) NOT NULL,
  `checkin_time` datetime NOT NULL,
  `checkout_time` datetime DEFAULT NULL,
  `cost` decimal(8,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `checkins`
--

INSERT INTO `checkins` (`id`, `user_id`, `location_id`, `checkin_time`, `checkout_time`, `cost`) VALUES
(1, 3, 1, '2025-08-13 17:23:03', '2025-08-13 19:13:04', 0.08),
(2, 2, 1, '2025-08-13 18:18:39', '2025-08-13 18:49:11', 0.08),
(3, 2, 1, '2025-08-13 18:25:07', '2025-08-13 18:49:16', 0.08),
(4, 2, 1, '2025-08-13 18:47:12', '2025-08-13 18:49:17', 0.08),
(5, 2, 1, '2025-08-13 18:49:41', '2025-08-13 18:49:45', 0.08),
(6, 2, 2, '2025-08-13 19:03:08', '2025-08-13 19:13:00', 0.25),
(7, 2, 1, '2025-08-13 19:13:24', '2025-08-13 19:19:32', 0.08),
(8, 1, 1, '2025-08-13 19:15:07', NULL, NULL),
(9, 2, 2, '2025-08-13 19:15:10', '2025-08-13 20:10:00', 0.25),
(10, 3, 2, '2025-08-13 19:15:13', '2025-08-13 19:21:40', 0.25),
(11, 3, 1, '2025-08-13 19:26:13', NULL, NULL),
(12, 2, 2, '2025-08-13 20:07:17', NULL, NULL),
(13, 2, 2, '2025-08-13 20:33:13', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `locations`
--

CREATE TABLE `locations` (
  `id` int(11) NOT NULL,
  `description` varchar(255) NOT NULL,
  `total_stations` int(11) NOT NULL,
  `cost_per_hour` decimal(6,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `locations`
--

INSERT INTO `locations` (`id`, `description`, `total_stations`, `cost_per_hour`) VALUES
(1, 'Hougang West', 2, 0.15),
(2, 'Punggol West', 15, 0.50);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `type` enum('user','admin') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `phone`, `email`, `password`, `type`) VALUES
(1, 'test', '12345678', 'test@test.com', '$2y$10$b5MGFDTmcCJk.tWEnKTMd.n7svs2tyAYZ2ov2GcHBLQMhP9.HhDvK', 'user'),
(2, 'test123', '12345678', 'test123@test.com', '$2y$10$TjVQSwgym7wo8GhaFAy6m.DgZqsIpirZboPXtdKFdLnUYD8hG82cS', 'user'),
(3, 'admin', '12345678', 'admin@admin.com', '$2y$10$I3LqK6wftgl8MSRiz1UOTuJgg4mC.lOIebqUR0/R1h.cZqSPXSgdi', 'admin');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `checkins`
--
ALTER TABLE `checkins`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `location_id` (`location_id`);

--
-- Indexes for table `locations`
--
ALTER TABLE `locations`
  ADD PRIMARY KEY (`id`);

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
-- AUTO_INCREMENT for table `checkins`
--
ALTER TABLE `checkins`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `locations`
--
ALTER TABLE `locations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `checkins`
--
ALTER TABLE `checkins`
  ADD CONSTRAINT `checkins_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `checkins_ibfk_2` FOREIGN KEY (`location_id`) REFERENCES `locations` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
