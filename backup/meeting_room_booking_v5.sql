-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Dec 25, 2024 at 02:15 AM
-- Server version: 8.0.30
-- PHP Version: 8.1.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `meeting_room_booking`
--

-- --------------------------------------------------------

--
-- Table structure for table `bookings`
--

CREATE TABLE `bookings` (
  `id` int NOT NULL,
  `user_id` int NOT NULL,
  `room_id` int NOT NULL,
  `subject` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `department` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `phone` varchar(20) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `attendees` int NOT NULL,
  `start_time` datetime NOT NULL,
  `end_time` datetime NOT NULL,
  `note` text COLLATE utf8mb4_general_ci,
  `room_layout_image` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `status` enum('pending','approved','rejected') COLLATE utf8mb4_general_ci DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `bookings`
--

INSERT INTO `bookings` (`id`, `user_id`, `room_id`, `subject`, `department`, `phone`, `attendees`, `start_time`, `end_time`, `note`, `room_layout_image`, `status`, `created_at`, `updated_at`) VALUES
(1, 2, 1, 'ทดสอบ1', 'ทดสอบ1', '011223348', 50, '2024-12-23 08:30:00', '2024-12-24 16:00:00', NULL, NULL, 'approved', '2024-12-22 19:08:00', '2024-12-22 19:10:00'),
(3, 2, 2, '01', '01', '0101010101', 20, '2024-12-25 04:19:00', '2024-12-25 05:19:00', '0-kkkk', NULL, 'approved', '2024-12-22 20:21:14', '2024-12-22 20:38:37'),
(4, 2, 3, 'asas', 'asas', '9191919191', 10, '2024-12-26 12:45:00', '2024-12-26 15:50:00', '---', NULL, 'pending', '2024-12-24 02:45:33', '2024-12-24 02:45:33'),
(6, 1, 1, 'ทดสอบส่งเมล', 'แอดมินเอง', '0990000000', 10, '2024-12-26 20:40:00', '2024-12-26 23:39:00', 'nnnn', NULL, 'pending', '2024-12-24 04:33:14', '2024-12-24 04:33:14'),
(7, 1, 3, 'ทดสอบส่งเมล4', '12345', '0887776666', 20, '2024-12-25 11:43:00', '2024-12-25 16:44:00', '---', NULL, 'pending', '2024-12-24 04:44:12', '2024-12-24 04:44:12'),
(8, 1, 1, 'ทดสอบส่งเมล5', 'แอดมินเอง', '0854001278', 70, '2024-12-30 12:11:00', '2024-12-30 18:11:00', '---', NULL, 'pending', '2024-12-24 05:11:33', '2024-12-24 05:11:33'),
(9, 1, 2, 'ทดสอบส่งเมล6', 'แอดมินเอง', '0854001270', 78, '2024-12-25 12:19:00', '2024-12-25 18:19:00', 'bbb', NULL, 'pending', '2024-12-24 05:19:22', '2024-12-24 05:19:22'),
(10, 1, 1, 'ทดสอบส่งเมล6', 'แอดมินเอง', '0229992233', 7, '2024-12-25 12:21:00', '2024-12-25 18:21:00', 'c', NULL, 'pending', '2024-12-24 05:21:31', '2024-12-24 05:21:31'),
(11, 1, 1, 'ทดสอบส่งเมล7', 'sss', '0229992233', 74, '2024-12-25 12:31:00', '2024-12-25 18:31:00', '988', NULL, 'pending', '2024-12-24 05:31:18', '2024-12-24 05:31:18'),
(12, 1, 1, 'ทดสอบส่งเมล7', 'แอดมินเอง', '0854001278', 34, '2024-12-26 15:15:00', '2024-12-26 19:15:00', 'iu', NULL, 'pending', '2024-12-24 07:18:18', '2024-12-24 07:18:18'),
(13, 1, 1, 'ทดสอบส่งเมล8', 'แอดมินเอง8', '0889989988', 88, '2024-12-26 14:21:00', '2024-12-26 20:22:00', '88', NULL, 'pending', '2024-12-24 07:22:08', '2024-12-24 07:22:08'),
(14, 3, 1, 'ทดสอบระบบ ครั้งที่ 10', 'ทดสอบระบบ ครั้งที่ 10', '0854001276', 10, '2024-12-26 19:58:00', '2024-12-26 22:58:00', 'ทดสอบระบบ ครั้งที่ 10', NULL, 'approved', '2024-12-24 12:59:03', '2024-12-24 14:47:08'),
(15, 3, 3, 'ทดสอบส่งเมล 10', 'ทดสอบส่งเมล 10', '0854001278', 10, '2024-12-26 08:45:00', '2024-12-26 10:45:00', '-', NULL, 'pending', '2024-12-25 01:46:07', '2024-12-25 01:46:07');

-- --------------------------------------------------------

--
-- Table structure for table `booking_equipments`
--

CREATE TABLE `booking_equipments` (
  `id` int NOT NULL,
  `booking_id` int NOT NULL,
  `equipment_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `booking_equipments`
--

INSERT INTO `booking_equipments` (`id`, `booking_id`, `equipment_id`) VALUES
(6, 1, 1),
(7, 1, 2),
(8, 1, 3),
(9, 1, 4),
(11, 3, 1),
(12, 4, 1),
(13, 4, 6),
(19, 6, 2),
(20, 7, 1),
(21, 8, 1),
(22, 8, 3),
(23, 9, 2),
(24, 10, 1),
(25, 11, 2),
(26, 12, 1),
(27, 12, 2),
(28, 13, 1),
(29, 14, 1),
(30, 14, 2),
(31, 14, 3),
(34, 15, 1),
(35, 15, 2);

-- --------------------------------------------------------

--
-- Table structure for table `equipments`
--

CREATE TABLE `equipments` (
  `id` int NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `description` text COLLATE utf8mb4_general_ci,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `equipments`
--

INSERT INTO `equipments` (`id`, `name`, `description`, `created_at`, `updated_at`) VALUES
(1, 'คอมพิวเตอร์', '', '2024-12-22 19:05:16', '2024-12-22 19:05:16'),
(2, 'โปรเจคเตอร์', '', '2024-12-22 19:05:24', '2024-12-22 19:05:24'),
(3, 'ระบบเครื่องเสียง', '', '2024-12-22 19:05:30', '2024-12-22 19:05:30'),
(4, 'บันทึกภาพ', '', '2024-12-22 19:05:37', '2024-12-22 19:05:37'),
(5, 'จัดสถานที่', '', '2024-12-22 19:05:45', '2024-12-22 19:05:45'),
(6, 'จัดของว่าง', '', '2024-12-22 19:05:52', '2024-12-22 19:05:52');

-- --------------------------------------------------------

--
-- Table structure for table `rooms`
--

CREATE TABLE `rooms` (
  `id` int NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `capacity` int NOT NULL,
  `description` text COLLATE utf8mb4_general_ci,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `color` varchar(7) COLLATE utf8mb4_general_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `rooms`
--

INSERT INTO `rooms` (`id`, `name`, `capacity`, `description`, `created_at`, `updated_at`, `color`) VALUES
(1, 'หอประชุม', 500, 'หอประชุมโรงเรียนเตรียมอุดมศึกษา ภาคเหนือ', '2024-12-22 19:04:15', '2024-12-22 20:50:18', '#ff0000'),
(2, 'ห้องโสตทัศนศึกษา 1', 100, 'อาคาร 5', '2024-12-22 19:04:44', '2024-12-22 20:50:33', '#ff00ea'),
(3, 'ห้องโสตทัศนศึกษา 2', 50, 'อาคาร 5', '2024-12-22 19:04:59', '2024-12-22 20:50:42', '#2b00ff'),
(4, 'ห้อง A-Level', 30, 'Chrome Book จำนวน 30 เครื่อง', '2024-12-24 03:25:45', '2024-12-24 03:26:36', '#00ccff');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int NOT NULL,
  `username` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `first_name` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `last_name` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `phone` varchar(20) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `role` enum('user','admin') COLLATE utf8mb4_general_ci DEFAULT 'user',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `first_name`, `last_name`, `email`, `phone`, `role`, `created_at`, `updated_at`) VALUES
(1, 'admin', '$2y$10$A.D4UOD1HDThEdJaQvthne4gJbb08jVamIWJOf9xVb2.szIvv3t0K', 'admin', 'system', 'knight.darkwing@gmail.com', '0801112233', 'admin', '2024-12-22 18:49:08', '2024-12-24 05:18:41'),
(2, 'u1', '$2y$10$WW2FeehPQmWe0qIyRWzLqOI966jlwbFyPXmEwDjrYADex6x6s78tm', 'u1', 'u1', 'u1@mail.com', '011223344', 'user', '2024-12-22 18:51:32', '2024-12-22 18:51:32'),
(3, 'nogittis', '$2y$10$C4DfhFW2Mevl0s0eDzTZUeIoHPaHWcsjHNn3lsPi4DH7sERAx3o02', 'สิทธิกร', 'บุญเกิด', 'sittigon.b@tn.ac.th', '0854001276', 'user', '2024-12-24 12:57:14', '2024-12-24 14:39:00');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `bookings`
--
ALTER TABLE `bookings`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `room_id` (`room_id`);

--
-- Indexes for table `booking_equipments`
--
ALTER TABLE `booking_equipments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `booking_id` (`booking_id`),
  ADD KEY `equipment_id` (`equipment_id`);

--
-- Indexes for table `equipments`
--
ALTER TABLE `equipments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `rooms`
--
ALTER TABLE `rooms`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `bookings`
--
ALTER TABLE `bookings`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `booking_equipments`
--
ALTER TABLE `booking_equipments`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- AUTO_INCREMENT for table `equipments`
--
ALTER TABLE `equipments`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `rooms`
--
ALTER TABLE `rooms`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `bookings`
--
ALTER TABLE `bookings`
  ADD CONSTRAINT `bookings_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `bookings_ibfk_2` FOREIGN KEY (`room_id`) REFERENCES `rooms` (`id`);

--
-- Constraints for table `booking_equipments`
--
ALTER TABLE `booking_equipments`
  ADD CONSTRAINT `booking_equipments_ibfk_1` FOREIGN KEY (`booking_id`) REFERENCES `bookings` (`id`),
  ADD CONSTRAINT `booking_equipments_ibfk_2` FOREIGN KEY (`equipment_id`) REFERENCES `equipments` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
