-- phpMyAdmin SQL Dump
-- version 5.2.1deb3
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Jan 17, 2025 at 03:12 AM
-- Server version: 8.0.40-0ubuntu0.24.04.1
-- PHP Version: 8.3.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `booking_room`
--

-- --------------------------------------------------------

--
-- Table structure for table `bookings`
--

CREATE TABLE `bookings` (
  `id` int NOT NULL,
  `user_id` int NOT NULL,
  `room_id` int NOT NULL,
  `subject` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `department` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `phone` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `attendees` int NOT NULL,
  `start_time` datetime NOT NULL,
  `end_time` datetime NOT NULL,
  `note` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `room_layout_image` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `status` enum('pending','approved','rejected') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT 'pending',
  `admin_id` int DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `bookings`
--

INSERT INTO `bookings` (`id`, `user_id`, `room_id`, `subject`, `department`, `phone`, `attendees`, `start_time`, `end_time`, `note`, `room_layout_image`, `status`, `admin_id`, `created_at`, `updated_at`) VALUES
(29, 8, 2, 'อบรมคณิต', 'หมวดคณิต', '0918623154', 100, '2025-01-24 11:23:00', '2025-01-25 11:23:00', 'โต๊ะรองรับครบจำนวนคนนั่ง', NULL, 'approved', NULL, '2025-01-07 04:24:22', '2025-01-07 04:33:06'),
(30, 10, 1, 'การเลือกตั้งสภานักเรียนประจำปีการศึกษา2568', 'งานสภานักเรียน', '0939833854', 1600, '2025-02-14 07:00:00', '2025-02-14 11:30:00', 'โต๊ะ 10 ตัว เก้าอี้ 20 ตัว', NULL, 'approved', NULL, '2025-01-07 04:49:59', '2025-01-08 06:05:46'),
(31, 10, 1, 'อบรมการทำแผนพัฒนาประจำปี2568-2572', 'งานแผน', '0939833854', 120, '2025-01-20 07:45:00', '2025-01-20 16:00:00', 'โต๊ะ+เก้าอี๊ 8 ชุด (4โต๊ะ 16 เก้าอี้ = 1 =ชุด)', NULL, 'approved', NULL, '2025-01-07 04:59:07', '2025-01-08 06:05:34'),
(37, 11, 6, 'แข่งขันทักษะคณิตศาสตร์', 'กลุ่มสาระฯคณิตศาสตร์', '0817272552', 10, '2025-01-17 12:30:00', '2025-01-17 15:00:00', 'เครื่องคอมพิวเตอร์ พร้อมโปรแกรม GSP 3 เครื่อง', NULL, 'approved', NULL, '2025-01-08 06:12:36', '2025-01-10 03:40:24'),
(38, 12, 2, 'กิจกรรมภาษาเกาหลี', 'กลุ่มสาระการเรียนรู้ภาษาต่างประเทศ', '0929885535', 80, '2025-02-13 07:30:00', '2025-02-13 15:30:00', 'โต๊ะ 10 ตัว เก้าอี้ 30', NULL, 'approved', NULL, '2025-01-08 06:23:51', '2025-01-08 12:12:46'),
(39, 13, 2, 'แข่งขันอัจฉริยภาพทางคณิตศาสตร์', 'กลุ่มสาระการเรียนรู้คณิตศาสตร์', '0875836100', 80, '2025-01-13 14:00:00', '2025-01-13 16:00:00', 'โต๊ะยาวสีขาว 35 ตัว\r\nเก้าอี้ 70 ตัว', NULL, 'approved', NULL, '2025-01-08 06:32:51', '2025-01-08 12:12:49'),
(41, 14, 7, 'PLC นิสิตฝึกประสบการณ์', 'วิชาการ', '0946134282', 20, '2025-01-17 14:20:00', '2025-01-17 16:00:00', '', NULL, 'approved', 1, '2025-01-09 02:36:05', '2025-01-10 03:40:51'),
(43, 18, 3, 'การประชุมคณะกรรมการฝ่ายบริหารกิจการนักเรียน', 'ฝ่ายบริหารกิจการนักเรียน', '0966635822', 19, '2025-01-17 14:30:00', '2025-01-17 16:00:00', '', NULL, 'approved', NULL, '2025-01-09 04:01:26', '2025-01-09 04:35:06'),
(44, 21, 2, 'การอบรมสุขภาพจิตนักเรียน', 'แนะแนว', '0918428420', 100, '2025-01-29 08:00:00', '2025-01-29 12:00:00', 'จัดเก้าอี้สำหรับแขก ครูวิทยากรนั่งด้านข้าง 10 ตัว', NULL, 'approved', NULL, '2025-01-09 04:50:20', '2025-01-09 07:53:43'),
(45, 21, 2, 'จัดกิจกรรมอบรมเพศวิถีนักเรียน', 'แนะแนว', '0918428420', 120, '2025-02-14 08:00:00', '2025-02-14 12:00:00', 'จัดกเาอี้สำหรับแขก วิทยากร ครูนั่งด้านข้าง 10 ตัว', NULL, 'approved', NULL, '2025-01-09 04:51:47', '2025-01-09 07:53:50'),
(46, 23, 3, 'ประชุมจัดสอบ o-net', 'งานบริการวิชาการ ฝ่ายวิชาการ', '0922558897', 16, '2025-01-10 13:30:00', '2025-01-10 16:00:00', '', NULL, 'approved', NULL, '2025-01-09 07:48:00', '2025-01-09 07:54:50'),
(47, 23, 5, 'สอบ o-net ม.6', 'งานบริการทางวิชาการ ฝ่ายวิชาการ', '0922558897', 40, '2025-03-01 07:30:00', '2025-03-02 16:30:00', '', NULL, 'approved', 1, '2025-01-09 08:52:36', '2025-01-10 03:45:22'),
(48, 23, 6, 'สอบ o-net ม.6', 'งานบริการวิชาการ ฝ่ายวิชาการ', '0922558897', 40, '2025-03-01 07:30:00', '2025-03-02 16:30:00', '', NULL, 'approved', 1, '2025-01-09 08:55:16', '2025-01-10 03:45:24'),
(49, 23, 5, 'สอบ o-net ม.6', 'งานบริการวิชาการ ฝ่ายวิชาการ', '0922558897', 40, '2025-02-22 07:30:00', '2025-02-23 16:30:00', '', NULL, 'approved', 1, '2025-01-09 08:57:12', '2025-01-10 03:44:47'),
(50, 23, 6, 'สอบ o-net ม.6', 'งานบริการวิชาการ ฝ่ายวิขาการ', '0922558897', 40, '2025-02-22 07:30:00', '2025-02-23 16:30:00', '', NULL, 'approved', 1, '2025-01-09 08:59:09', '2025-01-10 03:44:52'),
(51, 23, 4, 'สอบ o-net ม.6', 'งานบริการวิชาการ ฝ่ายวิชาการ', '0922558897', 40, '2025-02-22 07:30:00', '2025-02-23 16:30:00', 'จองห้องคอม จำนวน 4 ห้อง\r\nยอดนักเรียนสอบทั้งหมด 552', NULL, 'approved', 1, '2025-01-09 09:06:59', '2025-01-10 03:45:07'),
(52, 23, 4, 'สอบ o-net ม.6', 'งานบริการวิชาการ ฝ่ายวิชาการ', '0922558897', 40, '2025-03-01 07:30:00', '2025-03-02 16:30:00', '', NULL, 'approved', 1, '2025-01-09 09:08:43', '2025-01-10 03:45:27'),
(53, 20, 1, 'งานศิลปหัตถกรรม', 'ศูนย์การแข่งขันงานศิลปหัตถกรรม', '0839544519', 100, '2025-02-07 08:30:00', '2025-02-07 16:00:00', 'โต๊ะ 4 ตัวเก้าอี้ 30 ตัวโซฟา 1 ชุด', NULL, 'approved', 1, '2025-01-10 03:33:24', '2025-01-10 03:33:59'),
(54, 15, 2, 'ฉีดวัคซีนมะเร็งปากมดลูก เข็มที่ 2', 'งานอนามัยโรงเรียน', '0865911275', 140, '2025-01-17 08:30:00', '2025-01-17 12:00:00', 'โต๊ะสำหรับฉีดยา 4 ตัวโต๊ะ\r\nสำหรับลงทะเบียน 1 ตัว', NULL, 'approved', 1, '2025-01-10 03:48:29', '2025-01-12 22:47:18'),
(55, 10, 3, 'เตรียมงานวางแผนการทำแผนวันที่ 20 มค', 'งานแผน', '0939833854', 8, '2025-01-13 11:00:00', '2025-01-13 11:50:00', '', NULL, 'approved', 1, '2025-01-13 01:51:46', '2025-01-13 02:04:56'),
(56, 25, 2, 'ประชุมผู้ปกครอง นักเรียนติด 0 ร มส. และทำ MOU', 'บริหารวิชาการ', '0866809869', 80, '2025-01-18 09:00:00', '2025-01-18 00:00:00', '', NULL, 'approved', 1, '2025-01-13 02:25:40', '2025-01-14 01:17:51'),
(57, 26, 5, 'การอบรมเชิงปฏิบัติการนักเรียนห้องวิศวะ', 'วิชาการ', '0871733641', 40, '2025-01-17 13:00:00', '2025-01-17 16:00:00', '', NULL, 'approved', 4, '2025-01-13 02:50:22', '2025-01-13 04:17:12'),
(58, 26, 2, 'การติว A-Level นักเรียนห้องวิศวกรรม', 'วิชาการ', '0871733641', 40, '2025-01-17 13:00:00', '2025-01-17 16:00:00', 'เก้าอี้ 40 ตัว พร้อมโต๊ะ', NULL, 'approved', 4, '2025-01-13 02:52:02', '2025-01-13 04:18:03'),
(59, 27, 3, 'การแข่งขันเล่านิทานคุณธรรม ', 'กลุ่มสาระสังคมศึกษาศาสนาและวัฒนธรรม', '086-9318880', 25, '2025-01-31 08:30:00', '2025-01-31 12:00:00', '', NULL, 'approved', 4, '2025-01-13 04:12:28', '2025-01-13 04:18:39'),
(60, 27, 3, 'อบรมการทำพานพุ่ม', 'กลุ่มสาระการเรียนรู้สังคมศึกษา ', '0869318880', 10, '2025-01-15 12:00:00', '2025-01-15 16:00:00', '', NULL, 'approved', 1, '2025-01-13 06:20:51', '2025-01-14 01:17:22'),
(61, 12, 1, 'แข่งขันตอบคำถามภาษาต่างประเทศ', 'กลุ่มสาระการเรียนรู้ภาษาต่างประเทศ', '0929885535', 80, '2025-01-31 07:30:00', '2025-01-31 15:00:00', 'โต๊ะขาว 30 ตัว\r\nเก้าอี้ 120 ตัว', NULL, 'approved', 4, '2025-01-14 04:46:48', '2025-01-15 03:46:43'),
(62, 20, 2, 'อบรมสอบใบขับขี่', 'งานวินัยจราจร', '083-954-4519', 50, '2025-03-04 08:00:00', '2025-03-05 16:00:00', 'โต๊ะข่าว 4 ตัวเก้าอี้ 10 ตัวโซฟา 1 ชุดครับ', NULL, 'pending', NULL, '2025-01-15 04:10:52', '2025-01-15 04:10:52'),
(63, 30, 1, 'แนะแนวการศึกษาต่อ', 'งานแนะแนว', '0956340641', 500, '2025-01-22 15:10:00', '2025-01-22 16:00:00', 'โต๊ะ 1 ตัว\r\nเก้าอี้ 2 ตัว', NULL, 'pending', NULL, '2025-01-15 07:33:57', '2025-01-15 07:33:57'),
(64, 30, 1, 'แนะแนวการศึกษาต่อ', 'งานแนะแนว', '0956340641', 500, '2025-02-05 15:10:00', '2025-02-05 16:00:00', 'โต๊ะ 1 ตัว\r\nเก้าอี้ 2 ตัว', NULL, 'pending', NULL, '2025-01-15 07:36:41', '2025-01-15 07:36:41'),
(65, 31, 3, 'ประชุมเตรียมความพร้อมเพื่อรับการประเมิน', 'กิจการนักเรียน', '0884260318', 18, '2568-02-20 08:00:00', '2025-01-20 08:30:00', '', NULL, 'pending', NULL, '2025-01-15 08:05:30', '2025-01-15 08:05:30'),
(66, 32, 3, 'ประชุมการรับสมัครนักเรียนชั้นม.4', 'วิชาการ', '0845988951', 29, '2025-01-21 15:10:00', '2025-01-21 16:30:00', '', NULL, 'pending', NULL, '2025-01-15 08:14:49', '2025-01-15 08:14:49'),
(67, 32, 1, 'รับสมัครนักเรียนม.4 ปีการศึกษา 2568', 'วิชาการ', '0845988951', 39, '2025-01-22 08:30:00', '2025-01-26 16:30:00', '', NULL, 'pending', NULL, '2025-01-15 08:17:22', '2025-01-15 08:17:22');

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
(78, 29, 2),
(79, 29, 3),
(80, 29, 4),
(81, 29, 5),
(82, 30, 1),
(83, 30, 2),
(84, 30, 3),
(85, 30, 4),
(86, 30, 5),
(87, 31, 1),
(88, 31, 2),
(89, 31, 3),
(90, 31, 4),
(91, 31, 5),
(92, 31, 6),
(105, 38, 1),
(106, 38, 2),
(107, 38, 3),
(108, 38, 4),
(109, 38, 5),
(110, 39, 1),
(111, 39, 2),
(112, 39, 5),
(118, 43, 1),
(119, 43, 2),
(120, 43, 3),
(146, 45, 1),
(147, 45, 2),
(148, 45, 3),
(149, 45, 4),
(150, 45, 5),
(151, 44, 1),
(152, 44, 2),
(153, 44, 3),
(154, 44, 4),
(155, 44, 5),
(156, 46, 1),
(157, 46, 2),
(158, 46, 3),
(159, 46, 4),
(160, 47, 1),
(161, 47, 2),
(162, 47, 3),
(163, 47, 4),
(164, 48, 1),
(165, 48, 2),
(166, 48, 3),
(167, 48, 4),
(168, 49, 1),
(169, 49, 2),
(170, 49, 3),
(171, 49, 4),
(172, 50, 1),
(173, 50, 2),
(174, 50, 3),
(175, 50, 4),
(176, 51, 1),
(177, 51, 2),
(178, 51, 3),
(179, 51, 4),
(180, 52, 1),
(181, 52, 2),
(182, 52, 3),
(183, 52, 4),
(184, 53, 1),
(185, 53, 2),
(186, 53, 3),
(187, 53, 4),
(188, 53, 5),
(189, 37, 1),
(190, 54, 1),
(191, 54, 2),
(192, 54, 3),
(193, 54, 5),
(194, 56, 1),
(195, 56, 2),
(196, 56, 3),
(197, 56, 4),
(198, 57, 1),
(199, 57, 2),
(200, 57, 3),
(201, 58, 1),
(202, 58, 2),
(203, 58, 3),
(204, 59, 1),
(205, 59, 2),
(206, 59, 3),
(207, 61, 1),
(208, 61, 2),
(209, 61, 3),
(210, 61, 4),
(211, 61, 5),
(212, 62, 1),
(213, 62, 2),
(214, 62, 3),
(215, 62, 4),
(216, 62, 5),
(217, 63, 1),
(218, 63, 2),
(219, 63, 3),
(220, 64, 1),
(221, 64, 2),
(222, 64, 3),
(223, 66, 1),
(224, 66, 2),
(225, 66, 3);

-- --------------------------------------------------------

--
-- Table structure for table `equipments`
--

CREATE TABLE `equipments` (
  `id` int NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
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
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `capacity` int NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `color` varchar(7) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `rooms`
--

INSERT INTO `rooms` (`id`, `name`, `capacity`, `description`, `created_at`, `updated_at`, `color`) VALUES
(1, 'หอประชุม', 500, 'หอประชุมโรงเรียนเตรียมอุดมศึกษา ภาคเหนือ', '2024-12-22 19:04:15', '2024-12-22 20:50:18', '#ff0000'),
(2, 'ห้องโสตทัศนศึกษา 1', 100, 'อาคาร 5', '2024-12-22 19:04:44', '2024-12-22 20:50:33', '#ff00ea'),
(3, 'ห้องโสตทัศนศึกษา 2', 50, 'อาคาร 5', '2024-12-22 19:04:59', '2024-12-22 20:50:42', '#2b00ff'),
(4, 'ห้อง A-Level', 30, 'Chrome Book จำนวน 30 เครื่อง', '2024-12-24 03:25:45', '2024-12-24 03:26:36', '#00ccff'),
(5, 'ห้องปฏิบัติการคอมพิวเตอร์ 4', 40, 'เครื่องคอมพิวเตอร์ All-in-one  40 เครื่อง', '2025-01-07 04:46:46', '2025-01-07 04:47:51', '#982abc'),
(6, 'ห้องปฏิบัติการคอมพิวเตอร์ 3', 40, 'เครื่องคอมพิวเตอร์ All-in-one 40 เครื่อง', '2025-01-07 04:49:20', '2025-01-07 04:49:20', '#96d35f'),
(7, 'ห้องประชุมฝ่าย คอมพิวเตอร์', 25, 'ห้องประชุมอาคาร 5 ชั้น 3 ', '2025-01-07 04:51:08', '2025-01-07 04:51:08', '#4e7a27'),
(8, 'ระบบเครื่องเสียงเคลื่อนที่', 0, 'ชุดเครื่องเสียงเคลื่อนที่', '2025-01-07 05:09:49', '2025-01-07 05:09:49', '#37cdd7');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int NOT NULL,
  `username` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `first_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `last_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `phone` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `role` enum('user','admin') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT 'user',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `first_name`, `last_name`, `email`, `phone`, `role`, `created_at`, `updated_at`) VALUES
(1, 'admin', '$2y$10$A.D4UOD1HDThEdJaQvthne4gJbb08jVamIWJOf9xVb2.szIvv3t0K', 'admin', 'system', 'booking.room@tn.ac.th', '0854001276', 'admin', '2024-12-22 18:49:08', '2025-01-07 08:27:55'),
(2, 'u1', '$2y$10$WW2FeehPQmWe0qIyRWzLqOI966jlwbFyPXmEwDjrYADex6x6s78tm', 'u1', 'u1', 'u1@mail.com', '011223344', 'user', '2024-12-22 18:51:32', '2024-12-22 18:51:32'),
(3, 'nogittis', '$2y$10$fDDaUXhw5vsu7d/HkI3y3Omf3ftFjExWRWHUcDsMZl.MiKBLsAIJS', 'สิทธิกร', 'บุญเกิด', 'sittigon.b@tn.ac.th', '0854001276', 'user', '2024-12-24 12:57:14', '2025-01-08 12:54:32'),
(4, 'Edd', '$2y$10$6i4S9aBiwP7KP8PWJl4kKOOn8HKoL4OjHNld685P31qwTsuWSzthy', 'วีระชัย', 'จันทะเสน', 'werachai.j@tn.ac.th', '0896403687', 'admin', '2024-12-26 04:01:45', '2025-01-07 04:44:59'),
(7, 'lekhathaikan', '$2y$10$rcMOQbSQyuzpf5atIiBeV.1dig2fnOuRKpcPykgDHXTXzUxQ1skS2', 'หทัยกาญ', 'มั่นคง', 'hathaikan.m@tn.ac.th', '0882728213', 'admin', '2025-01-06 03:52:44', '2025-01-07 04:25:10'),
(8, 'memystyles', '$2y$10$cnRNKNQStFebUkaPAClQ9uEDqx6AmhaOO7W7w7OHfU03Ky7K2uIsG', 'สุเมธ', 'วงศ์ละคร', 'memystyles@gmail.com', '0918623154', 'admin', '2025-01-07 04:16:47', '2025-01-07 04:22:14'),
(9, 'suppakron.1995@gmail.com', '$2y$10$MP6apCLCuburHHlFemWYNupnFkP1DSWKYEiebc11TWn585MZBQN7y', 'นายศุภกรณ์', 'เพิ่มพูล', 'Suppakron.1995@gmail.com', '0939833854', 'user', '2025-01-07 04:43:52', '2025-01-07 04:43:52'),
(10, 'suppakron.p@tn.ac.th', '$2y$10$CZJ0y9asRb17Sq0QokjMdOEuwN10XnIBTrfRZngIHh9YStEDeCK4e', 'นายศุภกรณ์', 'เพิ่มพูล', 'suppakron.p@tn.ac.th', '0939833854', 'user', '2025-01-07 04:47:31', '2025-01-07 04:47:31'),
(11, 'BOONCHU', '$2y$10$Vwlu.Ad4qVOQ1W4JC5LZTuKGvG784QOeFedvhTYZTEPB4Jr1klLtO', 'บุญชู', 'กันเกตุ', 'boonchu.k@tn.ac.th', '0817272552', 'user', '2025-01-07 07:17:05', '2025-01-07 07:17:05'),
(12, 'ภัทราพร', '$2y$10$b6UG29Bix5OR29J.yIeBou6GTxul5LydsxPOw/OieIpBS1TUnCOZi', 'ภัทราพร ', 'เวทะธรรม', 'phattara.w@tn.ac.th', '0929885535', 'user', '2025-01-08 06:21:43', '2025-01-08 06:21:43'),
(13, 'Pornpan', '$2y$10$kVMtFsgjsNkHQ.kEYOT6tevrv15QJwuMJlhkCjgWipFUcfDmUd7Pi', 'นางสาวพรพรรณ', 'จันทร์วงค์', 'pornpan.j@tn.ac.th', '0875836100', 'user', '2025-01-08 06:31:05', '2025-01-08 06:31:05'),
(14, 'mansharee.w@tn.ac.th', '$2y$10$wE3BmsuJkNLyQlo9xXh7Uezig1GwP/fEUuWKKWsn5FSqpPnpeFC6e', 'Mansharee', 'Wongtang', 'mansharee.w@tn.ac.th', '0946134282', 'user', '2025-01-08 13:44:45', '2025-01-08 13:44:45'),
(15, 'Nuruk ', '$2y$10$l6V.DyU1a6.MtJmXspVaZ..N5mYMRGCjS/ybRECKZGBlAq/0Q53b6', 'อนุรักษ์', 'สุขเหมือน', 'anurak.s@tn.ac.th', '0865911275', 'user', '2025-01-08 15:16:47', '2025-01-08 15:16:47'),
(16, 'JINDAPORN ', '$2y$10$zvI6fLLx0QyyMvPUM3hNwevxDHsyQNwdiqUPofBF4Za8UsHRxvpZe', 'Jindaporn ', 'Chaloeypot', 'pokkrongroom@gmail.com', '0966635822', 'user', '2025-01-09 00:01:11', '2025-01-09 00:01:11'),
(18, 'jindaporn.c@tn.ac.th', '$2y$10$uWNJQatJncBjRtJxyL6KmuBkCSH1lBLdiLlM.z1OpEV1ep1QUuNKW', 'จินดาพร', 'เฉลยพจน์', 'jindaporn.c@tn.ac.th', '0966635822', 'user', '2025-01-09 03:22:00', '2025-01-09 03:59:51'),
(20, 'Ple', '$2y$10$JgFuPMb3B3p5Th73Uuip6u724IrXBrCVpaI4QbkT/Q6yYH8GsZc4q', 'นายฤทธิไกร', 'เงินทอง', 'rittikai102521@gmail.com', '0839544519', 'user', '2025-01-09 04:47:30', '2025-01-09 04:47:30'),
(21, '์Natkita', '$2y$10$SnCcAnk1Gyrf6zrrqSzCe.BFQtiHojcDK7KD695kYMWFeS/tFBsq6', 'ณัฐกฤตา ', 'เทียนหลง', 'tianlongnatkita@gmail.com', '0918428420', 'user', '2025-01-09 04:49:08', '2025-01-09 04:49:08'),
(22, 'เจเจบังมิด', '$2y$10$JQ.9D6uJdnbNsMuqebaoPuCNUMnI4fwUDZo3xiT7OrIsvskYStmmO', 'วรภพ', 'จันทร', 'ja.wo.rap.hop@gmail.com', '0967463596', 'user', '2025-01-09 04:49:17', '2025-01-09 04:49:17'),
(23, 'wirasinee', '$2y$10$B7zj.Mqy/G2A7O0R7Km7WOnlbNmHTa8amkq0PpJsC.Zv6.P8pB69q', 'วิลาสินี', 'ยอดวงศ์', 'wirasinee.y@tn.ac.th', '0922558897', 'user', '2025-01-09 07:44:05', '2025-01-09 07:44:05'),
(24, 'Khanadontr', '$2y$10$252wccysrQnhCM9MAmCVp.WHimw9BXJR9TXH7.eLGvHKHNWoUyEWa', 'คณดนตร์', 'วาทยกุล', 'khanadontr@gmail.com', '0974542935', 'user', '2025-01-10 03:57:51', '2025-01-10 03:57:51'),
(25, 'Pradapchai', '$2y$10$cQ3byeUJnbFr/LHmQ4oncu.n0X8sj991UdNY9sCUFKoCyF9iuFJGu', 'นายประดับชัย', 'อินมณี', 'pradapchai.i@tn.ac.th', '0866809869', 'user', '2025-01-13 02:18:20', '2025-01-13 02:18:20'),
(26, 'Sugunya', '$2y$10$nqJrSnvjpnepuKp9LlYUB.wzNsv.JHaSVkcfMvUIDBOLFLQ58TBca', 'นางสาวสุกัญญา', 'มงคล', 'sugunya.m@tn.ac.th', '0871733641', 'user', '2025-01-13 02:47:34', '2025-01-13 02:47:34'),
(27, 'Offza', '$2y$10$bpd5QA/dRwG9NMaKeCJiJuhY5sleK43ogud12HMwoWsEuZvkeIbJy', 'อรรถพร', 'เอี่ยมละออ', 'attapon.a@tn.ac.th', '0869318880', 'user', '2025-01-13 04:11:09', '2025-01-13 04:11:09'),
(28, 'Thawatchai', '$2y$10$pGhVuttoLDYNOBSSp72r1OQ7cRChJo0TdZMevPinRFrCsqnDId41.', 'นายธวัชชัย', 'มายอด', 'Thawatchai.m@tn.ac.th', '0906848593', 'user', '2025-01-15 03:51:28', '2025-01-15 03:51:28'),
(29, 'chonlathit', '$2y$10$/8e8LCwZAXi3/EPQIfNaIesULVqkyo9ddfrDR2BcSeVN0WPfsDRyW', 'ชลทิตย์', 'จีนาวงษ์', 'chonlathit.j@tn.ac.th', '0825916406', 'user', '2025-01-15 03:58:30', '2025-01-15 03:58:30'),
(30, 'apinya.t@tn.ac.th', '$2y$10$onDc/btAL0eQ8892I2zOKOVW1ouW6Jk93SqpeaeXC1KeWrYTlUu6i', 'นางสาวอภิญญา', 'โทนแจ้ง', 'apinya.t@tn.ac.th', '0956340641', 'user', '2025-01-15 07:23:37', '2025-01-15 07:23:37'),
(31, 'suthasinee.S@tn.ac.th', '$2y$10$TY.LB3NLHz11QawSI83y7.IVY4aFXlpGUMv/4cJVdhu9CsX6W0aIe', 'Suthasinee', 'Sortha', 'suthasinee.S@tn.ac.th', '0884260318', 'user', '2025-01-15 08:02:09', '2025-01-15 08:02:09'),
(32, 'Chaiwat', '$2y$10$85Xp6eFFI.zK4.saQD0H8evanV.rKkPR3kd2ujE7ialezIaznFTfS', 'ชัยวัฒน์ ', 'หอวรรณภากร', 'chaiwathow@yahoo.com', '0845988951', 'user', '2025-01-15 08:13:08', '2025-01-15 08:13:08');

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
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=68;

--
-- AUTO_INCREMENT for table `booking_equipments`
--
ALTER TABLE `booking_equipments`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=226;

--
-- AUTO_INCREMENT for table `equipments`
--
ALTER TABLE `equipments`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `rooms`
--
ALTER TABLE `rooms`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

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
