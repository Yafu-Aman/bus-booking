-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3307
-- Generation Time: Jun 05, 2026 at 11:14 AM
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
-- Database: `bus_booking`
--

-- --------------------------------------------------------

--
-- Table structure for table `bookings`
--

CREATE TABLE `bookings` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `route_id` int(11) NOT NULL,
  `seat_number` int(11) NOT NULL,
  `price` decimal(10,2) NOT NULL DEFAULT 0.00,
  `reference_code` varchar(20) NOT NULL,
  `status` enum('confirmed','cancelled') DEFAULT 'confirmed',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `bookings`
--

INSERT INTO `bookings` (`id`, `user_id`, `route_id`, `seat_number`, `price`, `reference_code`, `status`, `created_at`) VALUES
(1, 2, 1, 1, 0.00, 'BB-B5944F5E', 'confirmed', '2026-05-30 01:38:12'),
(2, 2, 1, 2, 0.00, 'BB-2CB11F72', 'cancelled', '2026-05-30 01:40:40'),
(3, 2, 2, 1, 0.00, 'BB-F1357DB7', 'cancelled', '2026-05-30 02:01:23'),
(4, 2, 2, 1, 0.00, 'BB-65487A97', 'cancelled', '2026-05-30 02:04:42'),
(5, 2, 1, 13, 0.00, 'BB-78E46333', 'cancelled', '2026-05-30 02:19:29'),
(6, 6, 1, 23, 3500.00, 'BB-8C52E749', 'confirmed', '2026-05-30 17:07:33'),
(7, 6, 9, 33, 6750.00, 'BB-43204688', 'cancelled', '2026-05-31 06:40:13'),
(8, 6, 29, 35, 2520.00, 'BB-08C41604', 'confirmed', '2026-05-31 13:22:08'),
(9, 6, 14, 34, 12000.00, 'BB-C279B086', 'confirmed', '2026-05-31 13:29:46');

-- --------------------------------------------------------

--
-- Table structure for table `buses`
--

CREATE TABLE `buses` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `total_seats` int(11) NOT NULL,
  `operator_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `buses`
--

INSERT INTO `buses` (`id`, `name`, `total_seats`, `operator_id`) VALUES
(1, 'Adey Bus 1', 45, 3),
(2, 'Adey Bus 2', 45, 3);

-- --------------------------------------------------------

--
-- Table structure for table `contact_messages`
--

CREATE TABLE `contact_messages` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `subject` varchar(200) NOT NULL,
  `message` text NOT NULL,
  `is_read` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `contact_messages`
--

INSERT INTO `contact_messages` (`id`, `name`, `email`, `subject`, `message`, `is_read`, `created_at`) VALUES
(1, 'Yafiet Amanuel', 'yafietamanuiel@gmail.com', 'Cancellation Request', 'i want to cancel my booking pls', 1, '2026-06-05 08:32:15');

-- --------------------------------------------------------

--
-- Table structure for table `pending_bookings`
--

CREATE TABLE `pending_bookings` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `route_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pending_bookings`
--

INSERT INTO `pending_bookings` (`id`, `user_id`, `route_id`, `created_at`) VALUES
(18, 2, 1, '2026-05-30 02:29:29');

-- --------------------------------------------------------

--
-- Table structure for table `routes`
--

CREATE TABLE `routes` (
  `id` int(11) NOT NULL,
  `from_city` varchar(100) NOT NULL,
  `to_city` varchar(100) NOT NULL,
  `departure_time` datetime NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `operator_id` int(11) NOT NULL,
  `approval_status` enum('pending','approved','rejected') NOT NULL DEFAULT 'pending',
  `bus_id` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `routes`
--

INSERT INTO `routes` (`id`, `from_city`, `to_city`, `departure_time`, `price`, `operator_id`, `approval_status`, `bus_id`, `created_at`) VALUES
(1, 'Addis Ababa', 'Hawassa', '2026-06-02 06:00:00', 3500.00, 3, 'approved', 1, '2026-05-22 06:11:52'),
(2, 'Addis Ababa', 'Bahir Dar', '2026-06-02 07:00:00', 5500.00, 3, 'approved', 1, '2026-05-22 06:11:52'),
(3, 'Addis Ababa', 'Dire Dawa', '2026-06-02 08:00:00', 6500.00, 3, 'approved', 1, '2026-05-22 06:11:52'),
(5, 'Hawassa', 'Addis Ababa', '2026-06-02 10:00:00', 3500.00, 3, 'approved', 1, '2026-05-22 06:11:52'),
(9, 'Addis Ababa', 'Gondar', '2026-07-01 06:00:00', 7500.00, 3, 'approved', NULL, '2026-05-30 07:31:34'),
(10, 'Addis Ababa', 'Jimma', '2026-07-04 07:00:00', 4500.00, 3, 'approved', NULL, '2026-05-30 07:31:34'),
(11, 'Addis Ababa', 'Dessie', '2026-07-05 06:00:00', 5000.00, 3, 'approved', NULL, '2026-05-30 07:31:34'),
(12, 'Hawassa', 'Dire Dawa', '2026-07-06 08:00:00', 6000.00, 3, 'approved', NULL, '2026-05-30 07:31:34'),
(13, 'Bahir Dar', 'Addis Ababa', '2026-07-07 06:00:00', 5500.00, 3, 'approved', NULL, '2026-05-30 07:31:34'),
(14, 'Addis Ababa', 'Mekelle', '2026-06-07 06:00:00', 10000.00, 3, 'approved', NULL, '2026-05-30 17:09:42'),
(16, 'Bahir Dar', 'Hawassa', '2026-06-27 06:00:00', 6000.00, 3, 'approved', NULL, '2026-05-31 07:35:17'),
(19, 'Gondar', 'Mekelle', '2026-08-01 09:00:00', 4500.00, 3, 'approved', NULL, '2026-05-31 07:49:04'),
(20, 'Addis Ababa', 'Adama', '2026-06-10 06:00:00', 2500.00, 8, 'approved', NULL, '2026-05-31 13:19:00'),
(21, 'Addis Ababa', 'Shashamane', '2026-06-10 07:00:00', 3200.00, 8, 'approved', NULL, '2026-05-31 13:19:00'),
(22, 'Addis Ababa', 'Arba Minch', '2026-06-11 06:00:00', 5500.00, 8, 'approved', NULL, '2026-05-31 13:19:00'),
(23, 'Addis Ababa', 'Wolkite', '2026-06-12 07:00:00', 3000.00, 8, 'approved', NULL, '2026-05-31 13:19:00'),
(24, 'Adama', 'Addis Ababa', '2026-06-10 14:00:00', 2500.00, 8, 'approved', NULL, '2026-05-31 13:19:00'),
(25, 'Shashamane', 'Addis Ababa', '2026-06-11 14:00:00', 3200.00, 8, 'approved', NULL, '2026-05-31 13:19:00'),
(26, 'Arba Minch', 'Addis Ababa', '2026-06-13 06:00:00', 5500.00, 8, 'approved', NULL, '2026-05-31 13:19:00'),
(27, 'Addis Ababa', 'Nekemte', '2026-06-15 06:00:00', 4500.00, 8, 'approved', NULL, '2026-05-31 13:19:00'),
(28, 'Addis Ababa', 'Asella', '2026-06-20 07:00:00', 3800.00, 8, 'approved', NULL, '2026-05-31 13:19:00'),
(29, 'Addis Ababa', 'Ziway', '2026-07-10 06:00:00', 2800.00, 8, 'approved', NULL, '2026-05-31 13:19:00');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `full_name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('passenger','operator','admin') NOT NULL DEFAULT 'passenger',
  `status` enum('active','suspended') NOT NULL DEFAULT 'active',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `full_name`, `email`, `password`, `role`, `status`, `created_at`) VALUES
(2, 'Yafiet Amanuel', 'yafuaman@gmail.com', '$2y$10$dxlK7rB2wCiMrKOuvQ3OYOtQcp/5ixx5bl.qlxtTVwU4qA2VOTQHS', 'passenger', 'active', '2026-05-22 05:39:36'),
(3, 'Eyob Berhanu', 'eyoboperator@gmail.com', '$2y$10$slf.vtCm/SWucDbWiOSir.vrwhWJTL/sCS8TJwBpVuSE0QLwHVH/W', 'operator', 'active', '2026-05-22 06:00:45'),
(4, 'Amanuel Legese', 'amalegese@gmail.com', '$2y$10$5tnl9y85N0Wbckn0YQmjt.xUeGWJzKeaMN6LeFwVWppLOcACXnp6S', 'passenger', 'active', '2026-05-22 11:20:20'),
(5, 'Yafu Admin', 'yafuadmin@gmail.com', '$2y$10$Yb1m.Eo3uJAQRPtmNyHX1exE3/Ah6dWZ7Zy3kxHRSWQA.aQy5kSze', 'admin', 'active', '2026-05-30 07:04:20'),
(6, 'Yafiet Amanuel', 'yafietamanuiel@gmail.com', '$2y$10$zC3IY/lhWp.vlf6Jk8hvQOXk.u8Ak8Gj3ehv/QMqviJ2fLZjppS3a', 'passenger', 'active', '2026-05-30 17:02:55'),
(7, 'Yafiet Aman', 'yafuoperator@gmail.com', '$2y$10$L74OJPq2WTyqUuGAsPFcYOr6TXxqyyfN7fcOVolmVD6QglB3n1MVa', 'operator', 'suspended', '2026-05-31 07:38:44'),
(8, 'Dagim Tamrat', 'dagimoperator@gmail.com', '$2y$10$nT2.O9k1pOP05gtts4OsxeVvPPlxHNUj84GoTopH24FAjWctCEpmO', 'operator', 'active', '2026-05-31 13:16:44');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `bookings`
--
ALTER TABLE `bookings`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `reference_code` (`reference_code`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `route_id` (`route_id`);

--
-- Indexes for table `buses`
--
ALTER TABLE `buses`
  ADD PRIMARY KEY (`id`),
  ADD KEY `operator_id` (`operator_id`);

--
-- Indexes for table `contact_messages`
--
ALTER TABLE `contact_messages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pending_bookings`
--
ALTER TABLE `pending_bookings`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `route_id` (`route_id`);

--
-- Indexes for table `routes`
--
ALTER TABLE `routes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `operator_id` (`operator_id`);

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
-- AUTO_INCREMENT for table `bookings`
--
ALTER TABLE `bookings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `buses`
--
ALTER TABLE `buses`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `contact_messages`
--
ALTER TABLE `contact_messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `pending_bookings`
--
ALTER TABLE `pending_bookings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=82;

--
-- AUTO_INCREMENT for table `routes`
--
ALTER TABLE `routes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `bookings`
--
ALTER TABLE `bookings`
  ADD CONSTRAINT `bookings_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `bookings_ibfk_2` FOREIGN KEY (`route_id`) REFERENCES `routes` (`id`);

--
-- Constraints for table `buses`
--
ALTER TABLE `buses`
  ADD CONSTRAINT `buses_ibfk_1` FOREIGN KEY (`operator_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `pending_bookings`
--
ALTER TABLE `pending_bookings`
  ADD CONSTRAINT `pending_bookings_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `pending_bookings_ibfk_2` FOREIGN KEY (`route_id`) REFERENCES `routes` (`id`);

--
-- Constraints for table `routes`
--
ALTER TABLE `routes`
  ADD CONSTRAINT `routes_ibfk_1` FOREIGN KEY (`operator_id`) REFERENCES `users` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
