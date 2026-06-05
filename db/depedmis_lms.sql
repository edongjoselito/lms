-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 06, 2026 at 01:51 AM
-- Server version: 8.4.7
-- PHP Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `depedmis_lms`
--

-- --------------------------------------------------------

--
-- Table structure for table `activities`
--

CREATE TABLE `activities` (
  `id` int UNSIGNED NOT NULL,
  `module_id` int UNSIGNED NOT NULL,
  `type` enum('assignment','quiz','forum','resource','page','label') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'page',
  `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `content` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `settings` json DEFAULT NULL,
  `order_num` int DEFAULT '1',
  `is_published` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `activities`
--

INSERT INTO `activities` (`id`, `module_id`, `type`, `title`, `content`, `settings`, `order_num`, `is_published`, `created_at`) VALUES
(14, 27, 'quiz', 'Quiz 1', 'Assessment Instruction Here....', '{\"quiz_id\": 10}', 2, 1, '2026-06-03 02:29:31'),
(15, 28, 'quiz', 'Quiz 1 - computer history', '', '{\"quiz_id\": 11}', 4, 1, '2026-06-03 06:34:32'),
(16, 29, 'quiz', 'Quiz 1', '', '{\"quiz_id\": 12}', 2, 1, '2026-06-03 08:20:45'),
(18, 31, 'assignment', '\"Kung Walang Kasaysayan...\"', '<h3><p><b>Panuto:</b> Sumulat\r\nng 5-7 pangungusap na repleksyon tungkol sa tanong:</p>\r\n\r\n<p><b>\"Ano\r\nkaya ang mangyayari sa ating lipunan kung hindi natin pahahalagahan ang ating\r\nkasaysayan?\"</b></p>\r\n\r\n<p>Isaalang-alang ang:</p>\r\n\r\n<ul>\r\n <li>kahalagahan ng kasaysayan;</li>\r\n <li>epekto nito sa pagkakakilanlan ng mga Pilipino; at</li>\r\n <li>mga aral na makukuha mula rito.</li>\r\n</ul>\r\n\r\n<p><b>&nbsp;</b></p>\r\n\r\n<p><b>&nbsp;</b></p>\r\n\r\n<p><b>Pamantayan</b></p>\r\n\r\n<p>Nilalaman - 10</p>\r\n\r\n<p>Organisasyon ng Ideya - 10</p>\r\n\r\n<p>Wastong Paggamit ng Wika – 5</p>\r\n\r\n<p><b>Kabuuang Puntos - 25</b></p></h3>', '[]', 3, 1, '2026-06-03 22:17:45'),
(20, 31, 'quiz', 'WEEK 1', '', '{\"quiz_id\": 14}', 4, 1, '2026-06-03 22:32:54'),
(21, 32, 'quiz', 'summative test week 2', '', '{\"quiz_id\": 15}', 1, 0, '2026-06-03 23:18:19'),
(22, 35, 'quiz', 'Sample Quiz', '', '{\"quiz_id\": 16}', 1, 1, '2026-06-05 13:12:37');

-- --------------------------------------------------------

--
-- Table structure for table `activity_logs`
--

CREATE TABLE `activity_logs` (
  `id` int UNSIGNED NOT NULL,
  `user_id` int UNSIGNED DEFAULT NULL,
  `action` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `module` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `ip_address` varchar(45) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `activity_logs`
--

INSERT INTO `activity_logs` (`id`, `user_id`, `action`, `module`, `description`, `ip_address`, `created_at`) VALUES
(84, 139, 'view_course', 'student', 'Viewed course content for subject ID: 37', '14.1.65.211', '2026-06-03 10:22:04'),
(85, 139, 'view_course', 'student', 'Viewed course content for subject ID: 37', '14.1.65.211', '2026-06-03 10:22:10'),
(86, 139, 'view_course', 'student', 'Viewed course content for subject ID: 37', '14.1.65.211', '2026-06-03 10:22:17'),
(87, 139, 'view_course', 'student', 'Viewed course content for subject ID: 37', '14.1.65.211', '2026-06-03 10:23:01'),
(88, 139, 'view_course', 'student', 'Viewed course content for subject ID: 37', '14.1.65.211', '2026-06-03 10:23:12'),
(89, 139, 'view_course', 'student', 'Viewed course content for subject ID: 37', '14.1.65.211', '2026-06-03 10:24:00'),
(90, 139, 'view_course', 'student', 'Viewed course content for subject ID: 38', '14.1.65.211', '2026-06-03 10:24:57'),
(91, 139, 'view_course', 'student', 'Viewed course content for subject ID: 37', '14.1.65.211', '2026-06-03 10:34:45'),
(92, 139, 'view_course', 'student', 'Viewed course content for subject ID: 37', '14.1.65.211', '2026-06-03 10:43:06'),
(93, 139, 'view_course', 'student', 'Viewed course content for subject ID: 37', '14.1.65.211', '2026-06-03 10:48:10'),
(94, 139, 'view_course', 'student', 'Viewed course content for subject ID: 37', '14.1.65.211', '2026-06-03 10:48:58'),
(95, 139, 'view_course', 'student', 'Viewed course content for subject ID: 37', '14.1.65.211', '2026-06-03 11:19:37'),
(96, 139, 'view_course', 'student', 'Viewed course content for subject ID: 37', '14.1.65.211', '2026-06-03 11:25:41'),
(97, 139, 'view_course', 'student', 'Viewed course content for subject ID: 37', '14.1.65.211', '2026-06-03 11:26:52'),
(98, 139, 'view_course', 'student', 'Viewed course content for subject ID: 37', '14.1.65.211', '2026-06-03 13:31:05'),
(99, 139, 'view_course', 'student', 'Viewed course content for subject ID: 37', '14.1.65.211', '2026-06-03 13:31:19'),
(100, 139, 'view_course', 'student', 'Viewed course content for subject ID: 37', '14.1.65.211', '2026-06-03 13:35:04'),
(101, 139, 'view_course', 'student', 'Viewed course content for subject ID: 37', '14.1.65.211', '2026-06-03 13:35:07'),
(102, 139, 'view_course', 'student', 'Viewed course content for subject ID: 37', '14.1.65.211', '2026-06-03 13:35:15'),
(103, 139, 'view_course', 'student', 'Viewed course content for subject ID: 37', '14.1.65.211', '2026-06-03 13:36:36'),
(104, 139, 'view_course', 'student', 'Viewed course content for subject ID: 39', '14.1.65.211', '2026-06-03 14:11:36'),
(105, 139, 'view_course', 'student', 'Viewed course content for subject ID: 39', '14.1.65.211', '2026-06-03 14:11:56'),
(106, 139, 'view_course', 'student', 'Viewed course content for subject ID: 37', '14.1.65.211', '2026-06-03 14:12:34'),
(107, 139, 'view_course', 'student', 'Viewed course content for subject ID: 37', '14.1.65.211', '2026-06-03 14:15:10'),
(108, 139, 'view_course', 'student', 'Viewed course content for subject ID: 37', '14.1.65.211', '2026-06-03 14:24:44'),
(109, 139, 'view_course', 'student', 'Viewed course content for subject ID: 37', '14.1.65.211', '2026-06-03 14:25:57'),
(110, 139, 'view_course', 'student', 'Viewed course content for subject ID: 37', '14.1.65.211', '2026-06-03 14:35:50'),
(111, 139, 'view_course', 'student', 'Viewed course content for subject ID: 58', '138.84.115.169', '2026-06-05 06:45:49'),
(112, 139, 'view_course', 'student', 'Viewed course content for subject ID: 58', '127.0.0.1', '2026-06-05 09:24:51'),
(113, 154, 'view_course', 'student', 'Viewed course content for subject ID: 57', '127.0.0.1', '2026-06-05 19:18:22');

-- --------------------------------------------------------

--
-- Table structure for table `announcements`
--

CREATE TABLE `announcements` (
  `id` int UNSIGNED NOT NULL,
  `school_id` int UNSIGNED DEFAULT NULL,
  `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `content` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `audience` enum('all','teachers','students','parents','section') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'all',
  `section_id` int UNSIGNED DEFAULT NULL,
  `class_program_id` int UNSIGNED DEFAULT NULL,
  `is_pinned` tinyint(1) NOT NULL DEFAULT '0',
  `published_at` datetime DEFAULT NULL,
  `created_by` int UNSIGNED DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `attendance`
--

CREATE TABLE `attendance` (
  `id` int UNSIGNED NOT NULL,
  `user_id` int UNSIGNED NOT NULL,
  `course_id` int UNSIGNED DEFAULT NULL,
  `date` date NOT NULL,
  `login_time` datetime DEFAULT NULL,
  `logout_time` datetime DEFAULT NULL,
  `duration_minutes` int UNSIGNED DEFAULT '0',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `attendance`
--

INSERT INTO `attendance` (`id`, `user_id`, `course_id`, `date`, `login_time`, `logout_time`, `duration_minutes`, `created_at`) VALUES
(1, 6, 0, '2026-04-21', '2026-04-21 07:50:48', '2026-04-21 09:26:25', 96, '2026-04-21 07:50:48'),
(2, 16, 0, '2026-04-22', '2026-04-22 08:36:26', '2026-04-22 08:36:29', 0, '2026-04-22 05:32:05'),
(4, 6, 0, '2026-04-22', '2026-04-22 06:29:42', NULL, 0, '2026-04-22 06:29:42'),
(5, 18, 0, '2026-04-22', '2026-04-22 12:24:03', NULL, 0, '2026-04-22 08:35:17'),
(6, 6, 0, '2026-05-01', '2026-05-01 12:37:55', '2026-05-01 12:38:07', 0, '2026-05-01 12:37:55'),
(7, 23, 0, '2026-05-01', '2026-05-01 14:32:07', NULL, 0, '2026-05-01 12:55:18'),
(8, 23, 0, '2026-05-02', '2026-05-02 01:06:58', '2026-05-02 01:07:25', 0, '2026-05-02 01:06:58'),
(9, 29, 0, '2026-05-30', '2026-05-30 17:18:56', NULL, 0, '2026-05-30 14:44:59'),
(10, 29, 0, '2026-05-31', '2026-05-31 10:29:26', NULL, 0, '2026-05-31 06:05:22'),
(11, 98, 0, '2026-06-03', '2026-06-03 06:05:37', NULL, 0, '2026-06-03 05:51:07'),
(12, 139, 0, '2026-06-03', '2026-06-03 14:11:20', NULL, 0, '2026-06-03 09:24:29'),
(13, 139, 0, '2026-06-05', '2026-06-05 09:27:24', '2026-06-05 09:34:11', 7, '2026-06-05 06:45:43'),
(14, 154, 0, '2026-06-05', '2026-06-05 19:18:13', '2026-06-05 19:31:05', 13, '2026-06-05 19:18:13');

-- --------------------------------------------------------

--
-- Table structure for table `audit_logs`
--

CREATE TABLE `audit_logs` (
  `id` int UNSIGNED NOT NULL,
  `user_id` int UNSIGNED NOT NULL,
  `user_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `action` enum('create','update','delete') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `entity_type` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `entity_id` int UNSIGNED NOT NULL,
  `entity_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `ip_address` varchar(45) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `school_id` int UNSIGNED DEFAULT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `audit_logs`
--

INSERT INTO `audit_logs` (`id`, `user_id`, `user_name`, `action`, `entity_type`, `entity_id`, `entity_name`, `description`, `ip_address`, `school_id`, `created_at`) VALUES
(1, 1, 'Super Admin', 'delete', 'school', 2, 'Sigaboy Agricultural Vocational High School', 'Deleted school: Sigaboy Agricultural Vocational High School', '127.0.0.1', 1, '2026-04-21 09:44:43'),
(2, 1, 'Super Admin', 'create', 'school', 3, 'Chiko Bolero College', 'Created school: Chiko Bolero College (ched)', '127.0.0.1', NULL, '2026-04-21 10:05:27'),
(3, 1, 'Super Admin', 'update', 'school', 3, 'Chiko Bolero College', 'Updated school: Chiko Bolero College', '127.0.0.1', 3, '2026-04-21 10:52:44'),
(4, 13, 'School Admin', 'delete', 'subject', 1, 'College Algebra', 'Deleted subject: College Algebra', '127.0.0.1', 3, '2026-04-21 12:09:38'),
(5, 13, 'School Admin', 'delete', 'subject', 2, '', 'Deleted subject: ', '127.0.0.1', 3, '2026-04-21 12:09:41'),
(6, 13, 'School Admin', 'create', 'user', 14, 'Liam Xander Edong', 'Created user: Liam Xander Edong (liam@teacher.com)', '127.0.0.1', 3, '2026-04-21 15:46:17'),
(7, 13, 'School Admin', 'create', 'user', 15, 'course creator', 'Created user: course creator (course@lms.com)', '127.0.0.1', 3, '2026-04-21 16:29:53'),
(8, 13, 'School Admin', 'create', 'user', 16, 'Luz Paron', 'Created user: Luz Paron (luz.paron@localhost.com)', '127.0.0.1', 3, '2026-04-22 05:31:59'),
(9, 1, 'Super Admin', 'create', 'school', 4, 'DOIT COLLEGE', 'Created school: DOIT COLLEGE (both)', '127.0.0.1', NULL, '2026-04-22 08:07:19'),
(10, 17, 'School Admin', 'create', 'subject', 7, 'Tech 1', 'Created subject: Tech 1', '127.0.0.1', 4, '2026-04-22 08:33:32'),
(11, 17, 'School Admin', 'create', 'user', 18, 'Edgardo Amigio', 'Created user: Edgardo Amigio (edgardo.amigo@lms.com)', '127.0.0.1', 4, '2026-04-22 08:35:13'),
(12, 17, 'School Admin', 'update', 'subject', 7, 'Tech 1', 'Updated subject: Tech 1', '127.0.0.1', 4, '2026-04-22 08:42:41'),
(13, 17, 'School Admin', 'delete', 'subject', 7, 'Tech 1', 'Deleted subject: Tech 1', '127.0.0.1', 4, '2026-04-22 08:44:08'),
(14, 17, 'School Admin', 'create', 'subject', 8, 'Tec\'', 'Created subject: Tec\'', '127.0.0.1', 4, '2026-04-22 08:44:31'),
(15, 17, 'School Admin', 'create', 'subject', 9, 'dsf', 'Created subject: dsf', '127.0.0.1', 4, '2026-04-22 08:46:28'),
(16, 17, 'School Admin', 'create', 'subject', 10, 'sdf', 'Created subject: sdf', '127.0.0.1', 4, '2026-04-22 08:48:21'),
(17, 17, 'School Admin', 'delete', 'subject', 8, 'Tec\'', 'Deleted subject: Tec\'', '127.0.0.1', 4, '2026-04-22 08:48:31'),
(18, 17, 'School Admin', 'delete', 'subject', 9, 'dsf', 'Deleted subject: dsf', '127.0.0.1', 4, '2026-04-22 08:48:33'),
(19, 17, 'School Admin', 'create', 'subject', 11, 'Tech 1', 'Created subject: Tech 1', '127.0.0.1', 4, '2026-04-22 10:33:22'),
(20, 1, 'Super Admin', 'update', 'platform_setting', 0, 'Login Image', 'Updated BlueCampus login image.', '::1', NULL, '2026-04-24 16:55:15'),
(21, 1, 'Super Admin', 'create', 'user', 19, 'Janndale Buot', 'Created user: Janndale Buot (janndalebuot@gmail.com)', '::1', 4, '2026-04-25 11:36:28'),
(22, 19, 'Janndale Buot', 'create', 'user', 20, 'Micro Bots', 'Created user: Micro Bots (microbots06@gmail.com)', '::1', 4, '2026-04-25 14:08:05'),
(23, 19, 'Janndale Buot', 'create', 'user', 21, 'Janndale Buot', 'Created user: Janndale Buot (janndale.buot@deped.gov.ph)', '::1', 4, '2026-04-25 15:59:35'),
(24, 19, 'Janndale Buot', 'create', 'user', 22, 'Joselit Edong', 'Created user: Joselit Edong (jann.buot@user.com)', '::1', 4, '2026-05-01 11:50:35'),
(25, 19, 'Janndale Buot', 'update', 'user', 22, 'Joselit Edong', 'Updated user: Joselit Edong (jann.buot@user.com)', '::1', 4, '2026-05-01 11:51:34'),
(26, 19, 'Janndale Buot', 'create', 'user', 23, 'Javee Bayang', 'Created user: Javee Bayang (javee@softtehc.com)', '::1', 4, '2026-05-01 12:55:11'),
(27, 1, 'Super Admin', 'create', 'school', 5, 'Wesleyan College of Manila', 'Created school: Wesleyan College of Manila (deped)', '127.0.0.1', NULL, '2026-05-30 10:50:04'),
(28, 1, 'Super Admin', 'create', 'school', 6, 'TAGUM DOCTORS COLLEGE', 'Created school: TAGUM DOCTORS COLLEGE (deped)', '127.0.0.1', NULL, '2026-05-30 11:14:15'),
(29, 25, 'School Admin', 'create', 'user', 26, 'Course Creator', 'Created user: Course Creator (course.creator@school.com)', '127.0.0.1', 6, '2026-05-30 11:23:02'),
(30, 25, 'School Admin', 'create', 'user', 27, 'Teacher One', 'Created user: Teacher One (teacher.one@school.com)', '127.0.0.1', 6, '2026-05-30 11:47:58'),
(31, 25, 'School Admin', 'create', 'user', 28, 'Teacher  Two', 'Created user: Teacher  Two (teacher.two@school.com)', '127.0.0.1', 6, '2026-05-30 12:23:21'),
(32, 1, 'Super Admin', 'create', 'school', 7, 'CHRISTIAN ACADEMY IN DAVAO ORIENTAL TECHNOLOGICAL COLLEGES, INC.', 'Created school: CHRISTIAN ACADEMY IN DAVAO ORIENTAL TECHNOLOGICAL COLLEGES, INC. (deped)', '127.0.0.1', NULL, '2026-05-31 13:27:58'),
(33, 25, 'School Admin', 'update', 'school', 6, 'TAGUM DOCTORS COLLEGE', 'Updated school logo', '::1', 6, '2026-05-31 16:31:32'),
(34, 82, 'School Admin', 'update', 'school', 7, 'CHRISTIAN ACADEMY IN DAVAO ORIENTAL TECHNOLOGICAL COLLEGES, INC.', 'Updated school information', '::1', 7, '2026-05-31 16:33:19'),
(35, 82, 'School Admin', 'update', 'school', 7, 'CHRISTIAN ACADEMY IN DAVAO ORIENTAL TECHNOLOGICAL COLLEGES, INC.', 'Updated school information', '::1', 7, '2026-05-31 16:39:32'),
(36, 82, 'School Admin', 'update', 'school', 7, 'CHRISTIAN ACADEMY IN DAVAO ORIENTAL TECHNOLOGICAL COLLEGES, INC.', 'Updated school information', '::1', 7, '2026-05-31 16:39:47'),
(37, 82, 'School Admin', 'update', 'school', 7, 'CHRISTIAN ACADEMY IN DAVAO ORIENTAL TECHNOLOGICAL COLLEGES, INC.', 'Updated school information', '::1', 7, '2026-05-31 16:43:00'),
(38, 82, 'School Admin', 'update', 'school', 7, 'CHRISTIAN ACADEMY IN DAVAO ORIENTAL TECHNOLOGICAL COLLEGES, INC.', 'Updated school information', '::1', 7, '2026-05-31 16:47:19'),
(39, 82, 'School Admin', 'update', 'school', 7, 'CHRISTIAN ACADEMY IN DAVAO ORIENTAL TECHNOLOGICAL COLLEGES, INC.', 'Updated school information', '::1', 7, '2026-05-31 16:48:13'),
(40, 82, 'School Admin', 'update', 'school', 7, 'CHRISTIAN ACADEMY IN DAVAO ORIENTAL TECHNOLOGICAL COLLEGES, INC.', 'Updated school information', '::1', 7, '2026-05-31 16:50:10'),
(41, 1, 'Super Admin', 'update', 'school', 9, 'SCHOOL NAME HERE', 'Updated school: SCHOOL NAME HERE', '127.0.0.1', NULL, '2026-05-31 22:02:13'),
(42, 1, 'Super Admin', 'update', 'school', 11, 'LUZ PARON', 'Manually approved school: LUZ PARON', '127.0.0.1', NULL, '2026-06-01 11:31:24'),
(43, 1, 'Super Admin', 'update', 'platform_setting', 0, 'Login Image', 'Removed BlueCampus login image.', '138.84.114.148', NULL, '2026-06-02 11:48:05'),
(44, 1, 'Super Admin', 'delete', 'school', 3, 'Chiko Bolero College', 'Deleted school: Chiko Bolero College', '138.84.114.148', NULL, '2026-06-02 12:05:41'),
(45, 1, 'Super Admin', 'delete', 'school', 7, 'CHRISTIAN ACADEMY IN DAVAO ORIENTAL TECHNOLOGICAL COLLEGES, INC.', 'Deleted school: CHRISTIAN ACADEMY IN DAVAO ORIENTAL TECHNOLOGICAL COLLEGES, INC.', '138.84.114.148', NULL, '2026-06-02 12:05:46'),
(46, 1, 'Super Admin', 'delete', 'school', 1, 'Default School', 'Deleted school: Default School', '138.84.114.148', NULL, '2026-06-02 12:05:50'),
(47, 1, 'Super Admin', 'delete', 'school', 4, 'DOIT COLLEGE', 'Deleted school: DOIT COLLEGE', '138.84.114.148', NULL, '2026-06-02 12:05:56'),
(48, 1, 'Super Admin', 'delete', 'school', 8, 'FDSFG', 'Deleted school: FDSFG', '138.84.114.148', NULL, '2026-06-02 12:06:00'),
(49, 1, 'Super Admin', 'delete', 'school', 11, 'LUZ PARON', 'Deleted school: LUZ PARON', '138.84.114.148', NULL, '2026-06-02 12:06:05'),
(50, 1, 'Super Admin', 'delete', 'school', 9, 'SCHOOL NAME HERE', 'Deleted school: SCHOOL NAME HERE', '138.84.114.148', NULL, '2026-06-02 12:06:08'),
(51, 1, 'Super Admin', 'delete', 'school', 10, 'SCHOOL NAME HERE', 'Deleted school: SCHOOL NAME HERE', '138.84.114.148', NULL, '2026-06-02 12:06:14'),
(52, 1, 'Super Admin', 'delete', 'school', 6, 'TAGUM DOCTORS COLLEGE', 'Deleted school: TAGUM DOCTORS COLLEGE', '138.84.114.148', NULL, '2026-06-02 12:06:20'),
(53, 1, 'Super Admin', 'create', 'school', 14, 'ABC', 'Created school: ABC (deped)', '138.84.114.148', NULL, '2026-06-02 12:49:33'),
(54, 1, 'Super Admin', 'update', 'school', 15, 'SSS', 'Manually approved school: SSS', '138.84.114.148', NULL, '2026-06-02 13:00:27'),
(55, 86, 'School Admin', 'update', 'school', 15, 'SSS', 'Updated school information', '138.84.114.148', 15, '2026-06-02 13:28:03'),
(56, 1, 'Super Admin', 'update', 'platform_setting', 0, 'Login Image', 'Updated BlueCampus login image.', '138.84.115.245', NULL, '2026-06-02 21:32:08'),
(57, 86, 'School Admin', 'update', 'user', 98, 'Jennifer Chan', 'Updated user: Jennifer Chan (2025-0012@lms.com)', '138.84.115.245', 15, '2026-06-02 21:47:49'),
(58, 86, 'School Admin', 'update', 'user', 98, 'Jennifer Chan', 'Updated user: Jennifer Chan (2025-0012@lms.com)', '138.84.115.245', 15, '2026-06-02 21:49:55'),
(59, 193, 'School Admin', 'update', 'school', 21, 'BAGUMBAYAN ELEMENTARY SCHOOL', 'Updated school information', '175.176.93.162', 21, '2026-06-03 06:48:54'),
(60, 217, 'School Admin', 'update', 'school', 27, 'LUPON VOCATIONAL HIGH SCHOOL', 'Updated school information', '111.90.242.219', 27, '2026-06-03 07:17:05'),
(61, 195, 'School Admin', 'update', 'user', 233, 'RONALD QUER', 'Updated user: RONALD QUER (ronald.quer@deped.gov.ph)', '175.176.88.121', 24, '2026-06-03 07:24:27'),
(62, 236, 'School Admin', 'update', 'school', 31, 'AROMA BEACH ELEMENTARY SCHOOL', 'Updated school information', '175.176.93.162', 31, '2026-06-03 07:34:24'),
(63, 195, 'School Admin', 'update', 'school', 24, 'ENRIQUE ORENCIA ELEMENTARY SCHOOL', 'Updated school information', '175.176.88.121', 24, '2026-06-03 07:36:32'),
(64, 194, 'School Admin', 'update', 'school', 20, 'TANDANG SORA ELEMENTARY SCHOOL', 'Updated school information', '175.176.88.121', 20, '2026-06-03 07:39:24'),
(65, 194, 'School Admin', 'update', 'school', 20, 'TANDANG SORA ELEMENTARY SCHOOL', 'Updated school information', '175.176.88.121', 20, '2026-06-03 07:39:44'),
(66, 1, 'Super Admin', 'update', 'school', 32, 'SAN ISIDRO CENTRAL SCHOOL SPED CENTER', 'Manually approved school: SAN ISIDRO CENTRAL SCHOOL SPED CENTER', '150.228.224.42', NULL, '2026-06-03 08:28:36'),
(67, 142, 'School Admin', 'create', 'user', 451, 'School Admin', 'Created user: School Admin (newadmin@gmail.com)', '::1', 19, '2026-06-05 17:04:55'),
(68, 138, 'School Admin', 'update', 'user', 154, 'Jennifer Chan', 'Updated user: Jennifer Chan (abc@abc.com)', '127.0.0.1', 17, '2026-06-05 19:18:01');

-- --------------------------------------------------------

--
-- Table structure for table `class_programs`
--

CREATE TABLE `class_programs` (
  `id` int UNSIGNED NOT NULL,
  `section_id` int UNSIGNED NOT NULL,
  `subject_id` int UNSIGNED NOT NULL,
  `teacher_id` int UNSIGNED DEFAULT NULL,
  `enrollment_key` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_by_user_id` int UNSIGNED DEFAULT NULL,
  `semester_id` int UNSIGNED DEFAULT NULL,
  `schedule_day` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `schedule_time_start` time DEFAULT NULL,
  `schedule_time_end` time DEFAULT NULL,
  `room` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `courses`
--

CREATE TABLE `courses` (
  `id` int UNSIGNED NOT NULL,
  `school_id` int UNSIGNED NOT NULL DEFAULT '1',
  `code` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `category` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `enrollment_key` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `cover_image` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_by` int UNSIGNED DEFAULT NULL,
  `is_published` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `course_collaborators`
--

CREATE TABLE `course_collaborators` (
  `id` int UNSIGNED NOT NULL,
  `course_id` int UNSIGNED NOT NULL,
  `teacher_id` int UNSIGNED NOT NULL,
  `section_id` int UNSIGNED DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `course_enrollments`
--

CREATE TABLE `course_enrollments` (
  `id` int UNSIGNED NOT NULL,
  `course_id` int UNSIGNED NOT NULL,
  `user_id` int UNSIGNED NOT NULL,
  `role` enum('teacher','student') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'student',
  `status` enum('active','completed','dropped') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'active',
  `enrolled_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `course_enrollments`
--

INSERT INTO `course_enrollments` (`id`, `course_id`, `user_id`, `role`, `status`, `enrolled_at`) VALUES
(25, 36, 98, 'student', 'active', '2026-06-03 05:51:12'),
(26, 37, 139, 'student', 'active', '2026-06-03 10:22:04'),
(27, 38, 139, 'student', 'active', '2026-06-03 10:24:55'),
(28, 39, 139, 'student', 'active', '2026-06-03 14:11:20'),
(29, 58, 139, 'student', 'active', '2026-06-05 06:45:49'),
(30, 57, 154, 'student', 'active', '2026-06-05 19:18:22');

-- --------------------------------------------------------

--
-- Table structure for table `course_outcomes`
--

CREATE TABLE `course_outcomes` (
  `id` int UNSIGNED NOT NULL,
  `subject_id` int UNSIGNED NOT NULL,
  `code` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `order_num` tinyint DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `co_po_mapping`
--

CREATE TABLE `co_po_mapping` (
  `id` int UNSIGNED NOT NULL,
  `course_outcome_id` int UNSIGNED NOT NULL,
  `program_outcome_id` int UNSIGNED NOT NULL,
  `level` enum('I','D','A') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'I'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `enrollments`
--

CREATE TABLE `enrollments` (
  `id` int UNSIGNED NOT NULL,
  `student_id` int UNSIGNED NOT NULL,
  `school_id` int UNSIGNED NOT NULL DEFAULT '1',
  `school_year_id` int UNSIGNED NOT NULL,
  `section_id` int UNSIGNED DEFAULT NULL,
  `grade_level_id` int UNSIGNED DEFAULT NULL,
  `program_id` int UNSIGNED DEFAULT NULL,
  `year_level` tinyint DEFAULT NULL,
  `enrollment_date` date DEFAULT NULL,
  `status` enum('pending','enrolled','dropped','completed') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `enrollments`
--

INSERT INTO `enrollments` (`id`, `student_id`, `school_id`, `school_year_id`, `section_id`, `grade_level_id`, `program_id`, `year_level`, `enrollment_date`, `status`, `created_at`) VALUES
(7, 29, 6, 6, 23, 2, 18, 2, '2026-05-30', 'enrolled', '2026-05-30 19:13:27'),
(9, 36, 6, 6, 23, 18, 18, 2, '2026-05-31', 'enrolled', '2026-05-31 08:21:26'),
(10, 34, 6, 6, 26, 18, 18, 2, '2026-05-31', 'enrolled', '2026-05-31 09:08:11'),
(11, 98, 15, 10, 30, 20, 20, 6, '2026-06-03', 'enrolled', '2026-06-02 21:34:32'),
(12, 139, 17, 12, 79, 107, 107, 10, '2026-06-03', 'enrolled', '2026-06-03 02:23:46'),
(13, 154, 17, 12, 79, 107, 107, 10, '2026-06-03', 'enrolled', '2026-06-03 06:04:00'),
(14, 156, 17, 12, 32, 21, 21, 6, '2026-06-03', 'enrolled', '2026-06-03 06:07:10'),
(15, 240, 20, 14, 35, 25, 25, 4, '2026-06-03', 'enrolled', '2026-06-03 07:24:52'),
(16, 242, 20, 14, 35, 25, 25, 4, '2026-06-03', 'enrolled', '2026-06-03 07:25:24'),
(17, 242, 20, 14, 35, 25, 25, 4, '2026-06-03', 'enrolled', '2026-06-03 07:25:38'),
(18, 240, 20, 14, 35, 25, 25, 4, '2026-06-03', 'enrolled', '2026-06-03 07:25:52'),
(19, 243, 20, 14, 35, 25, 25, 4, '2026-06-03', 'enrolled', '2026-06-03 07:26:03'),
(21, 313, 22, 17, 40, 26, 26, 8, '2026-06-03', 'enrolled', '2026-06-03 07:28:55'),
(22, 318, 23, 19, 38, 27, 27, 5, '2026-06-03', 'enrolled', '2026-06-03 07:29:51'),
(23, 319, 23, 19, 38, 27, 27, 5, '2026-06-03', 'enrolled', '2026-06-03 07:30:10'),
(24, 312, 22, 17, 40, 26, 26, 8, '2026-06-03', 'enrolled', '2026-06-03 07:31:11'),
(25, 311, 22, 17, 40, 26, 26, 8, '2026-06-03', 'enrolled', '2026-06-03 07:31:30'),
(26, 311, 22, 17, 40, 26, 26, 8, '2026-06-03', 'enrolled', '2026-06-03 07:31:46'),
(27, 308, 22, 17, 40, 26, 26, 8, '2026-06-03', 'enrolled', '2026-06-03 07:31:59'),
(28, 316, 22, 17, 40, 26, 26, 8, '2026-06-03', 'enrolled', '2026-06-03 07:32:09'),
(29, 309, 22, 17, 40, 26, 26, 8, '2026-06-03', 'enrolled', '2026-06-03 07:32:19'),
(30, 314, 22, 17, 40, 26, 26, 8, '2026-06-03', 'enrolled', '2026-06-03 07:32:27'),
(31, 307, 22, 17, 40, 26, 26, 8, '2026-06-03', 'enrolled', '2026-06-03 07:32:36'),
(32, 315, 22, 17, 40, 26, 26, 8, '2026-06-03', 'enrolled', '2026-06-03 07:32:44'),
(33, 310, 22, 17, 40, 26, 26, 8, '2026-06-03', 'enrolled', '2026-06-03 07:32:53'),
(34, 321, 29, 20, 46, 28, 28, 11, '2026-06-03', 'enrolled', '2026-06-03 07:35:55'),
(35, 320, 29, 20, 46, 28, 28, 11, '2026-06-03', 'enrolled', '2026-06-03 07:36:10'),
(36, 324, 29, 20, 46, 28, 28, 11, '2026-06-03', 'enrolled', '2026-06-03 07:36:23'),
(37, 323, 29, 20, 46, 28, 28, 11, '2026-06-03', 'enrolled', '2026-06-03 07:36:34'),
(38, 322, 29, 20, 46, 28, 28, 11, '2026-06-03', 'enrolled', '2026-06-03 07:36:49'),
(39, 392, 24, 21, 33, 24, 24, 6, '2026-06-03', 'enrolled', '2026-06-03 07:49:33'),
(40, 393, 24, 21, 33, 24, 24, 6, '2026-06-03', 'enrolled', '2026-06-03 07:49:44'),
(41, 394, 24, 21, 33, 24, 24, 6, '2026-06-03', 'enrolled', '2026-06-03 07:49:51'),
(42, 395, 24, 21, 33, 24, 24, 6, '2026-06-03', 'enrolled', '2026-06-03 07:49:58'),
(43, 382, 27, 25, 53, 32, 32, 12, '2026-06-03', 'enrolled', '2026-06-03 08:01:16'),
(44, 386, 27, 25, 53, 32, 32, 12, '2026-06-03', 'enrolled', '2026-06-03 08:02:49'),
(45, 384, 27, 25, 53, 32, 32, 12, '2026-06-03', 'enrolled', '2026-06-03 08:02:59'),
(46, 377, 44, 23, 0, 33, 33, 10, '2026-06-03', 'enrolled', '2026-06-03 08:04:43'),
(47, 379, 44, 23, 0, 33, 33, 10, '2026-06-03', 'enrolled', '2026-06-03 08:05:02'),
(48, 380, 44, 23, 0, 33, 33, 10, '2026-06-03', 'enrolled', '2026-06-03 08:05:19'),
(49, 378, 44, 23, 0, 33, 33, 10, '2026-06-03', 'enrolled', '2026-06-03 08:05:35'),
(50, 374, 44, 23, 0, 33, 33, 10, '2026-06-03', 'enrolled', '2026-06-03 08:05:52'),
(51, 376, 44, 23, 0, 33, 33, 10, '2026-06-03', 'enrolled', '2026-06-03 08:06:09');

-- --------------------------------------------------------

--
-- Table structure for table `grade_components`
--

CREATE TABLE `grade_components` (
  `id` int UNSIGNED NOT NULL,
  `school_id` int UNSIGNED DEFAULT NULL,
  `system_type` enum('deped','ched') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'deped',
  `name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `code` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `weight_percentage` decimal(5,2) NOT NULL,
  `subject_category` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `grade_components`
--

INSERT INTO `grade_components` (`id`, `school_id`, `system_type`, `name`, `code`, `weight_percentage`, `subject_category`, `description`) VALUES
(1, NULL, 'deped', 'Written Works', 'WW', 30.00, 'core', NULL),
(2, NULL, 'deped', 'Performance Tasks', 'PT', 50.00, 'core', NULL),
(3, NULL, 'deped', 'Quarterly Assessment', 'QA', 20.00, 'core', NULL),
(4, NULL, 'deped', 'Written Works', 'WW', 25.00, 'mapeh_tle', NULL),
(5, NULL, 'deped', 'Performance Tasks', 'PT', 55.00, 'mapeh_tle', NULL),
(6, NULL, 'deped', 'Quarterly Assessment', 'QA', 20.00, 'mapeh_tle', NULL),
(7, NULL, 'ched', 'Prelim Exam', 'PRELIM', 20.00, NULL, NULL),
(8, NULL, 'ched', 'Midterm Exam', 'MIDTERM', 20.00, NULL, NULL),
(9, NULL, 'ched', 'Final Exam', 'FINAL', 20.00, NULL, NULL),
(10, NULL, 'ched', 'Quizzes', 'QUIZ', 15.00, NULL, NULL),
(11, NULL, 'ched', 'Activities/Projects', 'ACT', 15.00, NULL, NULL),
(12, NULL, 'ched', 'Attendance/Participation', 'ATT', 10.00, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `grade_entries`
--

CREATE TABLE `grade_entries` (
  `id` int UNSIGNED NOT NULL,
  `enrollment_id` int UNSIGNED NOT NULL,
  `class_program_id` int UNSIGNED NOT NULL,
  `component_id` int UNSIGNED NOT NULL,
  `semester_id` int UNSIGNED DEFAULT NULL,
  `activity_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `score` decimal(6,2) DEFAULT NULL,
  `total_score` decimal(6,2) DEFAULT NULL,
  `remarks` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `grade_levels`
--

CREATE TABLE `grade_levels` (
  `id` int UNSIGNED NOT NULL,
  `school_id` int UNSIGNED NOT NULL DEFAULT '1',
  `name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `code` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `level_order` tinyint NOT NULL,
  `category` enum('elementary','junior_high','senior_high') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `grade_levels`
--

INSERT INTO `grade_levels` (`id`, `school_id`, `name`, `code`, `level_order`, `category`, `status`) VALUES
(1, 1, 'Kindergarten', 'K', 0, 'elementary', 1),
(2, 1, 'Grade 1', 'G1', 1, 'elementary', 1),
(3, 1, 'Grade 2', 'G2', 2, 'elementary', 1),
(4, 1, 'Grade 3', 'G3', 3, 'elementary', 1),
(5, 1, 'Grade 4', 'G4', 4, 'elementary', 1),
(6, 1, 'Grade 5', 'G5', 5, 'elementary', 1),
(7, 1, 'Grade 6', 'G6', 6, 'elementary', 1),
(8, 1, 'Grade 7', 'G7', 7, 'junior_high', 1),
(9, 1, 'Grade 8', 'G8', 8, 'junior_high', 1),
(10, 1, 'Grade 9', 'G9', 9, 'junior_high', 1),
(11, 1, 'Grade 10', 'G10', 10, 'junior_high', 1),
(12, 1, 'Grade 11', 'G11', 11, 'senior_high', 1),
(13, 1, 'Grade 12', 'G12', 12, 'senior_high', 1);

-- --------------------------------------------------------

--
-- Table structure for table `learning_areas`
--

CREATE TABLE `learning_areas` (
  `id` int UNSIGNED NOT NULL,
  `name` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `code` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `category` enum('core','applied','specialized','elective') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'core'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `learning_areas`
--

INSERT INTO `learning_areas` (`id`, `name`, `code`, `category`) VALUES
(1, 'Filipino', 'FIL', 'core'),
(2, 'English', 'ENG', 'core'),
(3, 'Mathematics', 'MATH', 'core'),
(4, 'Science', 'SCI', 'core'),
(5, 'Araling Panlipunan', 'AP', 'core'),
(6, 'Edukasyon sa Pagpapakatao', 'ESP', 'core'),
(7, 'Technology and Livelihood Education', 'TLE', 'core'),
(8, 'MAPEH', 'MAPEH', 'core'),
(9, 'Mother Tongue', 'MTB', 'core'),
(10, 'Oral Communication', 'ORALCOMM', 'applied'),
(11, 'Reading and Writing', 'RW', 'applied'),
(12, 'Komunikasyon at Pananaliksik', 'KOMSA', 'applied'),
(13, 'General Mathematics', 'GENMATH', 'applied'),
(14, 'Statistics and Probability', 'STAT', 'applied'),
(15, 'Earth and Life Science', 'ELS', 'specialized'),
(16, 'Physical Science', 'PHYSCI', 'specialized');

-- --------------------------------------------------------

--
-- Table structure for table `learning_competencies`
--

CREATE TABLE `learning_competencies` (
  `id` int NOT NULL,
  `subject_id` int NOT NULL,
  `school_id` int DEFAULT NULL,
  `code` varchar(50) DEFAULT NULL,
  `description` text NOT NULL,
  `quarter` int DEFAULT NULL,
  `sort_order` int DEFAULT '0',
  `created_by` int UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `learning_competencies`
--

INSERT INTO `learning_competencies` (`id`, `subject_id`, `school_id`, `code`, `description`, `quarter`, `sort_order`, `created_by`, `created_at`, `updated_at`) VALUES
(1, 58, 17, 'LC 1', 'Nasusuri ang mga isyung pangkapaligiran, pang-ekonomiya, at pampolitika.', NULL, 0, 138, '2026-06-04 23:18:07', '2026-06-05 01:13:32'),
(2, 58, 17, 'LC 2', 'Nakabubuo ng mga mungkahing solusyon sa mga suliraning panlipunan.', NULL, 1, 138, '2026-06-04 23:18:28', '2026-06-04 23:18:28');

-- --------------------------------------------------------

--
-- Table structure for table `lessons`
--

CREATE TABLE `lessons` (
  `id` int UNSIGNED NOT NULL,
  `module_id` int UNSIGNED NOT NULL,
  `learning_competency_id` int UNSIGNED DEFAULT NULL,
  `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `content` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `content_type` enum('text','page','file','video','link') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'text',
  `file_path` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `external_url` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `attachment_path` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `duration_minutes` int DEFAULT NULL,
  `order_num` int DEFAULT '1',
  `is_published` tinyint(1) NOT NULL DEFAULT '0',
  `taught_at` datetime DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `lessons`
--

INSERT INTO `lessons` (`id`, `module_id`, `learning_competency_id`, `title`, `content`, `content_type`, `file_path`, `external_url`, `attachment_path`, `duration_minutes`, `order_num`, `is_published`, `taught_at`, `created_at`) VALUES
(1, 1, NULL, 'Lesson 1', '<div class=\"Y3BBE\" data-sfc-cp=\"\" data-sfc-root=\"c\" data-sfc-cb=\"\" data-hveid=\"CAEQAA\"><span data-subtree=\"aimfl,mfl\">English\r\n 101 focuses on foundational communication, covering basic greetings, \r\ndaily activities, and simple questions for beginners</span>. Discussions revolve around <mark class=\"HxTRcb\" data-sfc-root=\"c\" data-sfc-cb=\"\">improving conversational fluency through topics like weather, shopping, and, routines</mark>.\r\n Key skills include using present/past tenses, basic grammar, and, \r\nsentence structure to build confidence in real-world scenarios.<span class=\"uJ19be notranslate\" data-sfc-root=\"c\" data-wiz-uids=\"gMT71c_h,gMT71c_i\" data-sfc-cb=\"\"><span class=\"vKEkVd\" data-animation-atomic=\"\" data-wiz-attrbind=\"class=gMT71c_g/TKHnVd\"><span aria-hidden=\"true\">&nbsp;</span></span></span></div><div class=\"Y3BBE\" data-sfc-cp=\"\" data-sfc-root=\"c\" data-sfc-cb=\"\" data-hveid=\"CAIQAA\"><strong class=\"Yjhzub\" data-sfc-root=\"c\" data-sfc-cb=\"\">Key Topics in English 101 Discussions</strong><span class=\"txxDge notranslate\" data-sfc-root=\"c\" data-wiz-uids=\"gMT71c_r,gMT71c_s\" data-sfc-cb=\"\"><span class=\"vKEkVd\" data-animation-atomic=\"\" data-wiz-attrbind=\"class=gMT71c_q/TKHnVd\"><span aria-hidden=\"true\"></span></span></span></div><ul class=\"KsbFXc U6u95\" data-sfc-root=\"c\" data-sfc-cb=\"\"><li class=\"dF3vjf\" data-sfc-root=\"c\" data-sfc-cb=\"\" data-hveid=\"CAMQAA\"><span class=\"T286Pc\" data-sfc-cp=\"\" data-sfc-root=\"c\" data-sfc-cb=\"\"><strong class=\"Yjhzub\" data-sfc-root=\"c\" data-sfc-cb=\"\"><span data-sfc-root=\"c\" data-wiz-uids=\"gMT71c_y\" data-sfc-cb=\"\"><a class=\"GI370e\" data-ved=\"2ahUKEwicpqGqqv2TAxV5rlYBHUtULAAQgK4QegQIAxAB\" data-hveid=\"CAMQAQ\" href=\"https://www.google.com/search?q=Daily+Conversations&amp;sca_esv=23368486d762886d&amp;sxsrf=ANbL-n4WUsxyd7hhhGCzWGDVbiABueDtiQ%3A1776719056430&amp;source=hp&amp;ei=0JTmacOrGPnQ2roP1eiwoAc&amp;iflsig=AFdpzrgAAAAAaeai4LhEL4agfKAXDDMlkYZF9G01k3nn&amp;ved=2ahUKEwicpqGqqv2TAxV5rlYBHUtULAAQgK4QegQIAxAB&amp;uact=5&amp;oq=English+101+discussions&amp;gs_lp=Egdnd3Mtd2l6IhdFbmdsaXNoIDEwMSBkaXNjdXNzaW9uczIGEAAYFhgeMgYQABgWGB4yBhAAGBYYHjILEAAYgAQYigUYhgMyCxAAGIAEGIoFGIYDMgUQABjvBTIFEAAY7wUyCBAAGIkFGKIEMgUQABjvBTIIEAAYiQUYogRInTRQAFiTMnAGeACQAQCYAawBoAGwF6oBBDkuMTi4AQPIAQD4AQGYAiGgApsYwgIEECMYJ8ICCBAAGIAEGLEDwgIOEAAYgAQYigUYsQMYgwHCAgsQABiABBixAxiDAcICBRAAGIAEwgIIEAAYgAQYogTCAgQQIRgVwgIHECEYChigAcICBRAhGKABmAMAkgcFMTUuMTigB_NlsgcEOS4xOLgHkRjCBwYxLjI3LjXIBzWACAE&amp;sclient=gws-wiz&amp;mstk=AUtExfD8hv7Ik3S0jXdVlozfOk3kjdFZkMvnJ-4BSrgXK7vU2RDxgyjGYJMvJvX9XCF-jS10As0ZgO_0tjicLCQf9D1mTnmsM0-Mt4aJgiatVW92Nn_aGHeYMgYEVOwecwqlmUYQ2WOsD-grRD9CAvJu-ZoDGI06dMF8oMSDzOOwKmRyW8AWAosAAD595n0onN1BngXvAmZ8NDV_sAtk9E_l6Dr8ediqvPf2uEOr2E74iEzbnuzR63AckEjXAgKmEVCUH3JlEiFddE9YeKRzJsjiGHNZg3-oUF51xseXc5x5AMJpKw&amp;csui=3\">Daily Conversations</a></span>:</strong> Practical dialogues for shopping at a boutique, navigating a date, or discussing weekend plans.</span></li><li class=\"dF3vjf\" data-sfc-root=\"c\" data-sfc-cb=\"\" data-hveid=\"CAMQAg\"><span class=\"T286Pc\" data-sfc-cp=\"\" data-sfc-root=\"c\" data-sfc-cb=\"\"><strong class=\"Yjhzub\" data-sfc-root=\"c\" data-sfc-cb=\"\"><span data-sfc-root=\"c\" data-wiz-uids=\"gMT71c_13\" data-sfc-cb=\"\"><a class=\"GI370e\" data-ved=\"2ahUKEwicpqGqqv2TAxV5rlYBHUtULAAQgK4QegQIAxAD\" data-hveid=\"CAMQAw\" href=\"https://www.google.com/search?q=Essential+Grammar&amp;sca_esv=23368486d762886d&amp;sxsrf=ANbL-n4WUsxyd7hhhGCzWGDVbiABueDtiQ%3A1776719056430&amp;source=hp&amp;ei=0JTmacOrGPnQ2roP1eiwoAc&amp;iflsig=AFdpzrgAAAAAaeai4LhEL4agfKAXDDMlkYZF9G01k3nn&amp;ved=2ahUKEwicpqGqqv2TAxV5rlYBHUtULAAQgK4QegQIAxAD&amp;uact=5&amp;oq=English+101+discussions&amp;gs_lp=Egdnd3Mtd2l6IhdFbmdsaXNoIDEwMSBkaXNjdXNzaW9uczIGEAAYFhgeMgYQABgWGB4yBhAAGBYYHjILEAAYgAQYigUYhgMyCxAAGIAEGIoFGIYDMgUQABjvBTIFEAAY7wUyCBAAGIkFGKIEMgUQABjvBTIIEAAYiQUYogRInTRQAFiTMnAGeACQAQCYAawBoAGwF6oBBDkuMTi4AQPIAQD4AQGYAiGgApsYwgIEECMYJ8ICCBAAGIAEGLEDwgIOEAAYgAQYigUYsQMYgwHCAgsQABiABBixAxiDAcICBRAAGIAEwgIIEAAYgAQYogTCAgQQIRgVwgIHECEYChigAcICBRAhGKABmAMAkgcFMTUuMTigB_NlsgcEOS4xOLgHkRjCBwYxLjI3LjXIBzWACAE&amp;sclient=gws-wiz&amp;mstk=AUtExfD8hv7Ik3S0jXdVlozfOk3kjdFZkMvnJ-4BSrgXK7vU2RDxgyjGYJMvJvX9XCF-jS10As0ZgO_0tjicLCQf9D1mTnmsM0-Mt4aJgiatVW92Nn_aGHeYMgYEVOwecwqlmUYQ2WOsD-grRD9CAvJu-ZoDGI06dMF8oMSDzOOwKmRyW8AWAosAAD595n0onN1BngXvAmZ8NDV_sAtk9E_l6Dr8ediqvPf2uEOr2E74iEzbnuzR63AckEjXAgKmEVCUH3JlEiFddE9YeKRzJsjiGHNZg3-oUF51xseXc5x5AMJpKw&amp;csui=3\">Essential Grammar</a></span>:</strong> Focusing on noun usage (count vs. non-count), proper use of adjectives, and comparatives.</span></li><li class=\"dF3vjf\" data-sfc-root=\"c\" data-sfc-cb=\"\" data-hveid=\"CAMQBA\"><span class=\"T286Pc\" data-sfc-cp=\"\" data-sfc-root=\"c\" data-sfc-cb=\"\"><strong class=\"Yjhzub\" data-sfc-root=\"c\" data-sfc-cb=\"\"><span data-sfc-root=\"c\" data-wiz-uids=\"gMT71c_18\" data-sfc-cb=\"\"><a class=\"GI370e\" data-ved=\"2ahUKEwicpqGqqv2TAxV5rlYBHUtULAAQgK4QegQIAxAF\" data-hveid=\"CAMQBQ\" href=\"https://www.google.com/search?q=Basic+Questions&amp;sca_esv=23368486d762886d&amp;sxsrf=ANbL-n4WUsxyd7hhhGCzWGDVbiABueDtiQ%3A1776719056430&amp;source=hp&amp;ei=0JTmacOrGPnQ2roP1eiwoAc&amp;iflsig=AFdpzrgAAAAAaeai4LhEL4agfKAXDDMlkYZF9G01k3nn&amp;ved=2ahUKEwicpqGqqv2TAxV5rlYBHUtULAAQgK4QegQIAxAF&amp;uact=5&amp;oq=English+101+discussions&amp;gs_lp=Egdnd3Mtd2l6IhdFbmdsaXNoIDEwMSBkaXNjdXNzaW9uczIGEAAYFhgeMgYQABgWGB4yBhAAGBYYHjILEAAYgAQYigUYhgMyCxAAGIAEGIoFGIYDMgUQABjvBTIFEAAY7wUyCBAAGIkFGKIEMgUQABjvBTIIEAAYiQUYogRInTRQAFiTMnAGeACQAQCYAawBoAGwF6oBBDkuMTi4AQPIAQD4AQGYAiGgApsYwgIEECMYJ8ICCBAAGIAEGLEDwgIOEAAYgAQYigUYsQMYgwHCAgsQABiABBixAxiDAcICBRAAGIAEwgIIEAAYgAQYogTCAgQQIRgVwgIHECEYChigAcICBRAhGKABmAMAkgcFMTUuMTigB_NlsgcEOS4xOLgHkRjCBwYxLjI3LjXIBzWACAE&amp;sclient=gws-wiz&amp;mstk=AUtExfD8hv7Ik3S0jXdVlozfOk3kjdFZkMvnJ-4BSrgXK7vU2RDxgyjGYJMvJvX9XCF-jS10As0ZgO_0tjicLCQf9D1mTnmsM0-Mt4aJgiatVW92Nn_aGHeYMgYEVOwecwqlmUYQ2WOsD-grRD9CAvJu-ZoDGI06dMF8oMSDzOOwKmRyW8AWAosAAD595n0onN1BngXvAmZ8NDV_sAtk9E_l6Dr8ediqvPf2uEOr2E74iEzbnuzR63AckEjXAgKmEVCUH3JlEiFddE9YeKRzJsjiGHNZg3-oUF51xseXc5x5AMJpKw&amp;csui=3\">Basic Questions</a></span>:</strong> Learning to ask about present, past, and future actions, including asking about plans.</span></li><li class=\"dF3vjf\" data-sfc-root=\"c\" data-sfc-cb=\"\" data-hveid=\"CAMQBg\"><span class=\"T286Pc\" data-sfc-cp=\"\" data-sfc-root=\"c\" data-sfc-cb=\"\"><strong class=\"Yjhzub\" data-sfc-root=\"c\" data-sfc-cb=\"\"><span data-sfc-root=\"c\" data-wiz-uids=\"gMT71c_1d\" data-sfc-cb=\"\"><a class=\"GI370e\" data-ved=\"2ahUKEwicpqGqqv2TAxV5rlYBHUtULAAQgK4QegQIAxAH\" data-hveid=\"CAMQBw\" href=\"https://www.google.com/search?q=Fundamental+Vocabulary&amp;sca_esv=23368486d762886d&amp;sxsrf=ANbL-n4WUsxyd7hhhGCzWGDVbiABueDtiQ%3A1776719056430&amp;source=hp&amp;ei=0JTmacOrGPnQ2roP1eiwoAc&amp;iflsig=AFdpzrgAAAAAaeai4LhEL4agfKAXDDMlkYZF9G01k3nn&amp;ved=2ahUKEwicpqGqqv2TAxV5rlYBHUtULAAQgK4QegQIAxAH&amp;uact=5&amp;oq=English+101+discussions&amp;gs_lp=Egdnd3Mtd2l6IhdFbmdsaXNoIDEwMSBkaXNjdXNzaW9uczIGEAAYFhgeMgYQABgWGB4yBhAAGBYYHjILEAAYgAQYigUYhgMyCxAAGIAEGIoFGIYDMgUQABjvBTIFEAAY7wUyCBAAGIkFGKIEMgUQABjvBTIIEAAYiQUYogRInTRQAFiTMnAGeACQAQCYAawBoAGwF6oBBDkuMTi4AQPIAQD4AQGYAiGgApsYwgIEECMYJ8ICCBAAGIAEGLEDwgIOEAAYgAQYigUYsQMYgwHCAgsQABiABBixAxiDAcICBRAAGIAEwgIIEAAYgAQYogTCAgQQIRgVwgIHECEYChigAcICBRAhGKABmAMAkgcFMTUuMTigB_NlsgcEOS4xOLgHkRjCBwYxLjI3LjXIBzWACAE&amp;sclient=gws-wiz&amp;mstk=AUtExfD8hv7Ik3S0jXdVlozfOk3kjdFZkMvnJ-4BSrgXK7vU2RDxgyjGYJMvJvX9XCF-jS10As0ZgO_0tjicLCQf9D1mTnmsM0-Mt4aJgiatVW92Nn_aGHeYMgYEVOwecwqlmUYQ2WOsD-grRD9CAvJu-ZoDGI06dMF8oMSDzOOwKmRyW8AWAosAAD595n0onN1BngXvAmZ8NDV_sAtk9E_l6Dr8ediqvPf2uEOr2E74iEzbnuzR63AckEjXAgKmEVCUH3JlEiFddE9YeKRzJsjiGHNZg3-oUF51xseXc5x5AMJpKw&amp;csui=3\">Fundamental Vocabulary</a></span>:</strong> Topics include describing emotions (like envy/jealousy), discussing meals, and daily tasks.</span></li><li class=\"dF3vjf\" data-sfc-root=\"c\" data-sfc-cb=\"\" data-hveid=\"CAMQCA\"><span class=\"T286Pc\" data-sfc-cp=\"\" data-sfc-root=\"c\" data-sfc-cb=\"\"><strong class=\"Yjhzub\" data-sfc-root=\"c\" data-sfc-cb=\"\"><span data-sfc-root=\"c\" data-wiz-uids=\"gMT71c_1i\" data-sfc-cb=\"\"><a class=\"GI370e\" data-ved=\"2ahUKEwicpqGqqv2TAxV5rlYBHUtULAAQgK4QegQIAxAJ\" data-hveid=\"CAMQCQ\" href=\"https://www.google.com/search?q=Cultural+Context&amp;sca_esv=23368486d762886d&amp;sxsrf=ANbL-n4WUsxyd7hhhGCzWGDVbiABueDtiQ%3A1776719056430&amp;source=hp&amp;ei=0JTmacOrGPnQ2roP1eiwoAc&amp;iflsig=AFdpzrgAAAAAaeai4LhEL4agfKAXDDMlkYZF9G01k3nn&amp;ved=2ahUKEwicpqGqqv2TAxV5rlYBHUtULAAQgK4QegQIAxAJ&amp;uact=5&amp;oq=English+101+discussions&amp;gs_lp=Egdnd3Mtd2l6IhdFbmdsaXNoIDEwMSBkaXNjdXNzaW9uczIGEAAYFhgeMgYQABgWGB4yBhAAGBYYHjILEAAYgAQYigUYhgMyCxAAGIAEGIoFGIYDMgUQABjvBTIFEAAY7wUyCBAAGIkFGKIEMgUQABjvBTIIEAAYiQUYogRInTRQAFiTMnAGeACQAQCYAawBoAGwF6oBBDkuMTi4AQPIAQD4AQGYAiGgApsYwgIEECMYJ8ICCBAAGIAEGLEDwgIOEAAYgAQYigUYsQMYgwHCAgsQABiABBixAxiDAcICBRAAGIAEwgIIEAAYgAQYogTCAgQQIRgVwgIHECEYChigAcICBRAhGKABmAMAkgcFMTUuMTigB_NlsgcEOS4xOLgHkRjCBwYxLjI3LjXIBzWACAE&amp;sclient=gws-wiz&amp;mstk=AUtExfD8hv7Ik3S0jXdVlozfOk3kjdFZkMvnJ-4BSrgXK7vU2RDxgyjGYJMvJvX9XCF-jS10As0ZgO_0tjicLCQf9D1mTnmsM0-Mt4aJgiatVW92Nn_aGHeYMgYEVOwecwqlmUYQ2WOsD-grRD9CAvJu-ZoDGI06dMF8oMSDzOOwKmRyW8AWAosAAD595n0onN1BngXvAmZ8NDV_sAtk9E_l6Dr8ediqvPf2uEOr2E74iEzbnuzR63AckEjXAgKmEVCUH3JlEiFddE9YeKRzJsjiGHNZg3-oUF51xseXc5x5AMJpKw&amp;csui=3\">Cultural Context</a></span>:</strong> Understanding nuances for situations like visiting an American restaurant or traveling.</span><span class=\"uJ19be notranslate\" data-sfc-root=\"c\" data-wiz-uids=\"gMT71c_1k,gMT71c_1l\" data-sfc-cb=\"\"><span class=\"vKEkVd\" data-animation-atomic=\"\" data-wiz-attrbind=\"class=gMT71c_1j/TKHnVd\"><span aria-hidden=\"true\">&nbsp;</span></span></span></li></ul><div class=\"Y3BBE\" data-sfc-cp=\"\" data-sfc-root=\"c\" data-sfc-cb=\"\" data-hveid=\"CAQQAA\"><strong class=\"Yjhzub\" data-sfc-root=\"c\" data-sfc-cb=\"\">Key Skills Developed</strong><span class=\"txxDge notranslate\" data-sfc-root=\"c\" data-wiz-uids=\"gMT71c_1w,gMT71c_1x\" data-sfc-cb=\"\"><span class=\"vKEkVd\" data-animation-atomic=\"\" data-wiz-attrbind=\"class=gMT71c_1v/TKHnVd\"><span aria-hidden=\"true\"></span></span></span></div><ul class=\"KsbFXc U6u95\" data-sfc-root=\"c\" data-sfc-cb=\"\"><li class=\"dF3vjf\" data-sfc-root=\"c\" data-sfc-cb=\"\" data-hveid=\"CAUQAA\"><span class=\"T286Pc\" data-sfc-cp=\"\" data-sfc-root=\"c\" data-sfc-cb=\"\"><strong class=\"Yjhzub\" data-sfc-root=\"c\" data-sfc-cb=\"\">Vocabulary Building:</strong> Learning essential phrases to describe everyday life.</span></li><li class=\"dF3vjf\" data-sfc-root=\"c\" data-sfc-cb=\"\" data-hveid=\"CAUQAQ\"><span class=\"T286Pc\" data-sfc-cp=\"\" data-sfc-root=\"c\" data-sfc-cb=\"\"><strong class=\"Yjhzub\" data-sfc-root=\"c\" data-sfc-cb=\"\">Sentence Structure:</strong> Constructing simple, correct sentences to communicate clearly.</span></li><li class=\"dF3vjf\" data-sfc-root=\"c\" data-sfc-cb=\"\" data-hveid=\"CAUQAg\"><span class=\"T286Pc\" data-sfc-cp=\"\" data-sfc-root=\"c\" data-sfc-cb=\"\"><strong class=\"Yjhzub\" data-sfc-root=\"c\" data-sfc-cb=\"\">Confidence Building:</strong> Practicing greetings and introductions to overcome the fear of making mistakes.</span><span class=\"uJ19be notranslate\" data-sfc-root=\"c\" data-wiz-uids=\"gMT71c_29,gMT71c_2a\" data-sfc-cb=\"\"><span class=\"vKEkVd\" data-animation-atomic=\"\" data-wiz-attrbind=\"class=gMT71c_28/TKHnVd\"><span aria-hidden=\"true\">&nbsp;</span></span></span></li></ul><p><br></p>', 'text', NULL, NULL, NULL, NULL, 1, 1, NULL, '2026-04-21 05:05:13'),
(2, 1, NULL, 'Lesson 2', '', 'video', NULL, 'https://www.youtube.com/watch?v=t-g89HRlFo4', NULL, NULL, 2, 1, NULL, '2026-04-21 05:06:09'),
(3, 1, NULL, 'Lesson 3', '<p>fasdfsddsfsd sfsadfsfd</p>', 'text', NULL, NULL, NULL, NULL, 3, 1, NULL, '2026-04-21 05:07:22'),
(4, 1, NULL, 'Lesson About Computer', '<p>Being a modern-day kid you must have used, seen, or read about \r\ncomputers. This is because they are an integral part of our everyday \r\nexistence. Be it school, banks, shops, railway stations, hospital or \r\nyour own home, computers are present everywhere, making our work easier \r\nand faster for us. As they are such integral parts of our lives, we must\r\n know what they are and how they function. Let us start with defining \r\nthe term computer formally.</p>\r\n<p>The literal meaning of computer is a device that can calculate. However, modern computers can do a lot more than calculate. <b>Computer</b>\r\n is an electronic device that receives input, stores or processes the \r\ninput as per user instructions and provides output in desired format.</p>\r\n<h2>Input-Process-Output Model</h2>\r\n<p>Computer input is called <b>data</b> and the output obtained after processing it, based on users instructions is called <b>information</b>. Raw facts and figures which can be processed using arithmetic and logical operations to obtain information are called <b>data</b>.</p>\r\n<img src=\"https://www.tutorialspoint.com/basics_of_computers/images/workflow.jpg\" alt=\"Workflow\">\r\n<p>The processes that can be applied to data are of two types −</p>\r\n<ul class=\"list\"><li><p><b>Arithmetic operations</b> − Examples include calculations like addition, subtraction, differentials, square root, etc.</p></li><li><b>Logical operations</b> − Examples include comparison operations like greater than, less than, equal to, opposite, etc.</li></ul>\r\n<p>The corresponding figure for an actual computer looks something like this −</p>\r\n<img src=\"https://www.tutorialspoint.com/basics_of_computers/images/block_diagram.jpg\" alt=\"Block Diagram\">\r\n<p>The basic parts of a computer are as follows −</p>\r\n<ul class=\"list\"><li><p><b>Input Unit</b> − Devices like keyboard and mouse that are used to input data and instructions to the computer are called input unit.</p></li><li><p><b>Output Unit</b> − Devices like printer and visual display unit\r\n that are used to provide information to the user in desired format are \r\ncalled output unit.</p></li><li><p><b>Control Unit</b> − As the name suggests, this unit controls \r\nall the functions of the computer. All devices or parts of computer \r\ninteract through the control unit.</p></li><li><p><b>Arithmetic Logic Unit</b> − This is the brain of the computer where all arithmetic operations and logical operations take place.</p></li><li><p><b>Memory</b> − All input data, instructions and data interim to the processes are stored in the memory. Memory is of two types  <b>primary memory</b> and <b>secondary memory</b>. Primary memory resides within the CPU whereas secondary memory is external to it.</p></li></ul><p>Control unit, arithmetic logic unit and memory are together called the <b>central processing unit</b> or <b>CPU</b>. Computer devices like keyboard, mouse, printer, etc. that we can see and touch are the <b>hardware</b>\r\n components of a computer. The set of instructions or programs that make\r\n the computer function using these hardware parts are called <b>software</b>. We cannot see or touch software. Both hardware and software are necessary for working of a computer.</p><ul class=\"list\"><li><p><b>Speed</b> − Typically, a computer can carry out 3-4 million instructions per second.</p></li><li><p><b>Accuracy</b> − Computers exhibit a very high degree of \r\naccuracy. Errors that may occur are usually due to inaccurate data, \r\nwrong instructions or bug in chips  all human errors.</p></li><li><p><b>Reliability</b> − Computers can carry out same type of work \r\nrepeatedly without throwing up errors due to tiredness or boredom, which\r\n are very common among humans.</p></li><li><p><b>Versatility</b> − Computers can carry out a wide range of work\r\n from data entry and ticket booking to complex mathematical calculations\r\n and continuous astronomical observations. If you can input the \r\nnecessary data with correct instructions, computer will do the \r\nprocessing.</p></li><li><p><b>Storage Capacity</b> − Computers can store a very large amount\r\n of data at a fraction of cost of traditional storage of files. Also, \r\ndata is safe from normal wear and tear associated with paper</p></li></ul><p><br></p>', 'text', NULL, NULL, NULL, NULL, 4, 1, NULL, '2026-04-21 05:16:08'),
(5, 2, NULL, 'Sample PDF Lesson', '', 'file', 'uploads/lessons/c587fe44fa8f014285a6289b9febc590.pdf', NULL, NULL, NULL, 1, 1, NULL, '2026-04-21 05:27:04'),
(6, 3, NULL, 'You are here today', 'https://www.youtube.com/watch?v=e_ZJ1Ho9r20', 'video', NULL, NULL, NULL, 0, 1, 1, NULL, '2026-04-21 22:06:59'),
(7, 5, NULL, 'Lesson ', '<div class=\"lesson-video-embed ratio ratio-16x9 mb-3\" data-video-url=\"https://www.youtube.com/watch?v=e_ZJ1Ho9r20\"><iframe src=\"https://www.youtube.com/embed/e_ZJ1Ho9r20\" title=\"Lesson video\" allow=\"accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share\" allowfullscreen></iframe></div>\n<div class=\"lesson-video-notes\">The history of computers spans from ancient abacus tools to modern AI, transitioning from mechanical devices (1800s) to electronic, digital systems (1940s-present). Key milestones include Babbage\'s Analytical Engine, vacuum tubes (ENIAC), transistors, integrated circuits, and microprocessors. The 1970s microcomputer revolution, led by Apple and IBM, popularized personal computing.&nbsp;<br>Key Eras in Computer History<br><br>&nbsp; &nbsp; Early Calculating Devices (Pre-20th Century): The Abacus (3000 BC) was a foundational calculating tool. In 1642, Blaise Pascal invented the Pascaline, an early mechanical digital computer for addition.<br>&nbsp; &nbsp; The \"Father of Computers\": Charles Babbage designed the Analytical Engine (1830s), a mechanical, programmable computer, laying the groundwork for modern computers.<br>&nbsp; &nbsp; First-Generation Computers (1940s-1950s): Used vacuum tubes, these were large and power-hungry. Examples include the ENIAC (1943-1945) and UNIVAC (1951).<br>&nbsp; &nbsp; Second-Generation Computers (Late 1950s-1960s): Transistors replaced vacuum tubes, making computers smaller, faster, and more efficient.<br>&nbsp; &nbsp; Third-Generation Computers (1960s-1970s): Integrated circuits (ICs) combined multiple transistors on a single silicon chip.<br>&nbsp; &nbsp; Fourth-Generation Computers (1971-Present): Microprocessors (VLSI - Very Large Scale Integration) enabled personal computers (PCs). The Altair 8800 (1975) is often considered the first personal computer.<br>&nbsp; &nbsp; Modern Computing: The 1980s saw the IBM PC (1981) and Apple\'s Macintosh, driving home computing.&nbsp;<br><br>Key Historical Developments<br><br>&nbsp; &nbsp; Programmable Machines: Conrad Zuse\'s Z3 (1941) was the first working, programmable digital computer.<br>&nbsp; &nbsp; Computer Languages: The first programming languages, such as COBOL, were developed in the mid-1950s.<br>&nbsp; &nbsp; Graphical User Interface (GUI): Apple introduced the Lisa in 1983, bringing the GUI to personal computers.&nbsp;<br><br>Note: The history of computers also includes significant contributions to digital storage and the internet, facilitating the modern digital age.&nbsp;</div>', 'video', NULL, NULL, NULL, 0, 1, 1, NULL, '2026-04-21 22:11:20'),
(8, 6, NULL, 'Computer History', '<div align=\"left\">The history of computers spans from ancient abacus tools to modern AI, transitioning from mechanical devices (1800s) to electronic, digital systems (1940s-present). Key milestones include Babbage\'s Analytical Engine, vacuum tubes (ENIAC), transistors, integrated circuits, and microprocessors. The 1970s microcomputer revolution, led by Apple and IBM, popularized personal computing.&nbsp;<br>Key Eras in Computer History<br><br>Early Calculating Devices (Pre-20th Century): The Abacus (3000 BC) was a foundational calculating tool. In 1642, Blaise Pascal invented the Pascaline, an early mechanical digital computer for addition.<br>The \"Father of Computers\": Charles Babbage designed the Analytical Engine (1830s), a mechanical, programmable computer, laying the groundwork for modern computers.<br>&nbsp; &nbsp; First-Generation Computers (1940s-1950s): Used vacuum tubes, these were large and power-hungry. Examples include the ENIAC (1943-1945) and UNIVAC (1951).<br>&nbsp; &nbsp; Second-Generation Computers (Late 1950s-1960s): Transistors replaced vacuum tubes, making computers smaller, faster, and more efficient.<br>&nbsp; &nbsp; Third-Generation Computers (1960s-1970s): Integrated circuits (ICs) combined multiple transistors on a single silicon chip.<br>&nbsp; &nbsp; Fourth-Generation Computers (1971-Present): Microprocessors (VLSI - Very Large Scale Integration) enabled personal computers (PCs). The Altair 8800 (1975) is often considered the first personal computer.<br>&nbsp; &nbsp; Modern Computing: The 1980s saw the IBM PC (1981) and Apple\'s Macintosh, driving home computing.&nbsp;<br><br>Key Historical Developments<br><br>&nbsp; &nbsp; Programmable Machines: Conrad Zuse\'s Z3 (1941) was the first working, programmable digital computer.<br>&nbsp; &nbsp; Computer Languages: The first programming languages, such as COBOL, were developed in the mid-1950s.<br>&nbsp; &nbsp; Graphical User Interface (GUI): Apple introduced the Lisa in 1983, bringing the GUI to personal computers.</div>', 'text', NULL, NULL, NULL, NULL, 1, 1, NULL, '2026-04-22 09:11:36'),
(9, 6, NULL, 'Computer Networking', '<div class=\"lesson-video-embed ratio ratio-16x9 mb-3\" data-video-url=\"https://www.youtube.com/watch?v=F4rYmV4nvu0\"><iframe src=\"https://www.youtube.com/embed/F4rYmV4nvu0\" title=\"Lesson video\" allow=\"accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share\" allowfullscreen></iframe></div>\n<div class=\"lesson-video-notes\">Discussion here</div>', 'video', NULL, NULL, NULL, NULL, 2, 1, NULL, '2026-04-22 09:12:32'),
(10, 7, NULL, 'External Link Lesson', '<div class=\"lesson-link-embed mb-3\" data-link-url=\"https://google.com\"><a href=\"https://google.com\" target=\"_blank\" rel=\"noopener\" class=\"btn btn-outline-success\"><i class=\"bi bi-box-arrow-up-right me-1\"></i>Open External Link</a></div>\nDescription of the link here', 'link', NULL, NULL, NULL, NULL, 1, 1, NULL, '2026-04-22 09:16:25'),
(11, 7, NULL, 'Lesson - File Uploading', 'File Desription', 'file', NULL, NULL, NULL, NULL, 2, 1, NULL, '2026-04-22 09:17:19'),
(12, 9, NULL, 'Lesson 1 - Text and HTML', 'Lesson 1 content', 'text', NULL, NULL, NULL, NULL, 1, 1, NULL, '2026-04-22 10:34:15'),
(13, 9, NULL, 'Lesson 2 - File Upload', 'File Upload Description', 'file', NULL, NULL, NULL, NULL, 2, 1, NULL, '2026-04-22 10:34:52'),
(14, 10, NULL, 'Lesson 1 for Module 2 - Video Lesson', '<div class=\"lesson-video-embed ratio ratio-16x9 mb-3\" data-video-url=\"https://www.youtube.com/watch?v=6jKRownc1io\"><iframe src=\"https://www.youtube.com/embed/6jKRownc1io\" title=\"Lesson video\" allow=\"accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share\" allowfullscreen></iframe></div>\n<div class=\"lesson-video-notes\">Video Lesson Description</div>', 'video', NULL, NULL, NULL, NULL, 1, 1, NULL, '2026-04-22 10:36:05'),
(15, 10, NULL, 'Lesson 2 for Module 2 - External Link', 'External Link Description here', 'text', NULL, NULL, NULL, NULL, 2, 1, NULL, '2026-04-22 10:36:59'),
(16, 12, NULL, 'Wearable Health Tracker', '<div class=\"lesson-file-embed mb-3\" data-file-url=\"http://localhost/lms/uploads/lessons/5635e05c017e3f474d86308817cde1ff.pdf\"><div class=\"lesson-file-toolbar mb-2\"><a href=\"http://localhost/lms/uploads/lessons/5635e05c017e3f474d86308817cde1ff.pdf\" target=\"_blank\" rel=\"noopener\" class=\"btn btn-outline-primary\"><i class=\"bi bi-file-earmark-pdf me-1\"></i>Open PDF</a></div><div class=\"ratio ratio-4x3 lesson-file-preview\"><iframe src=\"http://localhost/lms/uploads/lessons/5635e05c017e3f474d86308817cde1ff.pdf\" title=\"PDF preview\" loading=\"lazy\"></iframe></div></div>', 'file', 'http://localhost/lms/uploads/lessons/5635e05c017e3f474d86308817cde1ff.pdf', NULL, NULL, NULL, 1, 1, NULL, '2026-04-25 15:28:39'),
(17, 13, NULL, 'Company', '<div class=\"lesson-file-embed mb-3\" data-file-url=\"http://localhost/lms/uploads/lessons/9d59afe9372be6621b010e393e0e5e99.pdf\"><div class=\"lesson-file-toolbar mb-2\"><a href=\"http://localhost/lms/uploads/lessons/9d59afe9372be6621b010e393e0e5e99.pdf\" target=\"_blank\" rel=\"noopener\" class=\"btn btn-outline-primary\"><i class=\"bi bi-file-earmark-pdf me-1\"></i>Open PDF</a></div><div class=\"ratio ratio-4x3 lesson-file-preview\"><iframe src=\"http://localhost/lms/uploads/lessons/9d59afe9372be6621b010e393e0e5e99.pdf\" title=\"PDF preview\" loading=\"lazy\"></iframe></div></div>', 'file', 'http://localhost/lms/uploads/lessons/9d59afe9372be6621b010e393e0e5e99.pdf', NULL, NULL, NULL, 1, 0, NULL, '2026-04-25 15:29:03'),
(18, 14, NULL, 'Lesson 1: Kahulugan at Kahalagahan ng Araling Panlipunan', '<b>Kahulugan ng Araling Panlipunan</b><br><br>Ang Araling Panlipunan ay asignaturang nag-aaral tungkol sa tao, lipunan, kultura, ekonomiya, pamahalaan, kasaysayan, at heograpiya. Layunin nitong maunawaan ng mga mag-aaral ang kanilang sarili bilang mamamayan at ang kanilang papel sa komunidad, bansa, at mundo.<br><b><br>Kahalagahan ng Araling Panlipunan</b><br><br>Nagpapalawak ng kaalaman tungkol sa lipunan – Tinutulungan tayong maunawaan ang kasaysayan, kultura, at pamumuhay ng iba\'t ibang tao at pangkat.<br><div>Humuhubog ng mabuting mamamayan – Itinuturo nito ang mga karapatan, tungkulin, at responsibilidad ng bawat mamamayan.</div><div><br></div>Nagpapaunlad ng kritikal na pag-iisip – Natututo ang mga mag-aaral na suriin ang mga isyung panlipunan at gumawa ng makatuwirang pagpapasya.<br>Nagpapahalaga sa kasaysayan at kultura – Nakakatulong ito upang mapanatili at maipagmalaki ang ating pambansang pagkakakilanlan.<br>Naghahanda para sa pakikilahok sa lipunan – Hinihikayat nito ang aktibong pakikilahok sa mga gawaing pangkomunidad at pambansa para sa ikabubuti ng lahat.<br><br><b>Buod:</b><br>Ang Araling Panlipunan ay mahalagang asignatura na nagbibigay ng kaalaman tungkol sa tao at lipunan, habang hinuhubog ang mga mag-aaral upang maging responsable, makabayan, at mapanuring mamamayan.', 'text', '', NULL, NULL, NULL, 1, 1, NULL, '2026-05-30 12:36:17'),
(19, 17, NULL, 'Lesson 1', 'Sample Content Here', 'text', '', NULL, NULL, NULL, 1, 0, NULL, '2026-05-30 17:16:30'),
(20, 20, NULL, 'Lesson 1', '<b>Kasaysayan</b><br><br>Mula sa Wikipedia, ang malayang ensiklopedya<br>Nakaturo papunta rito ang \"historya\". Huwag itong ikalito sa istorya.<br>Tungkol sa larangan ang artikulo na ito. Para sa kasaysayan ng tao, tingnan ang kasaysayan ng mundo. Para sa kasaysayan ng planeta, tingnan ang kasaysayan ng daigdig.<br>Si Herodoto ang itinuturing na \"Ama ng Kasaysayan.\"<br><br>Kasaysáyan[1][a] ang sistematikong pag-aaral at pagtatala sa nakaraan. Madalas tumutukoy ito sa nakaraan matapos ang pagkaimbento sa pagsulat; ang panahon bago ang puntong ito ay tinatawag naman na prehistorya. Para malaman ng mga historyador ang mga kaganapan sa nakaraan, naghahanap sila at pinag-aaralan ang mga mapagkukunang nila ng impormasyon, tulad ng mga nakasulat na dokumento, pasalitang paglalarawan, sining, at materyal na artipakto, gayundin ang mga iniwang bakas sa kalikasan.[3] Dahil patuloy na gumagalaw ang oras, hindi makukumpleto ang kasaysayan, at hindi kailanman makukuha ng mga historyador ang kumpletong larawan patungkol sa isang pangyayari nang walang kiling.<br><br>Isang larangan ang kasaysayan na gumagamit ng naratibo upang mailarawan, maipaliwanag, kwestyunin, at suriin ang mga pangyayari sa nakaraan, pati na rin ang pag-imbestiga sa kahalagahan at ang sanhi at bunga ng naturang pangyayari sa isang lugar o sa mundo. Pinagdedebatehan ng mga historyador ang kalikasan ng kasaysayan, at ang silbi nito sa paghugis sa mga problema sa kasalukuyan.[4]<br><br>Madalas ginugrupo bilang mga pamanang kultural ang mga kuwentong karaniwan sa isang kultura, tulad halimbawa ng mga kuwentong-bayan ka', 'text', '', NULL, NULL, NULL, 1, 1, NULL, '2026-05-30 19:24:23'),
(21, 20, NULL, 'Lesson 2', 'Another lesson', 'text', '', NULL, NULL, NULL, 2, 1, NULL, '2026-05-30 19:26:47'),
(22, 20, NULL, 'Lesson 3', 'dfasdf', 'text', '', NULL, NULL, NULL, 3, 1, NULL, '2026-05-30 19:27:04'),
(23, 21, NULL, 'Lesson 4', 'Lesson 4 here', 'text', '', NULL, NULL, NULL, 1, 1, NULL, '2026-05-30 19:32:40'),
(24, 22, NULL, 'Lesson 5', 'Lesson 5 content here', 'text', '', NULL, NULL, NULL, 1, 1, NULL, '2026-05-30 19:45:34'),
(25, 22, NULL, 'Lesson 6', 'Lesson 6 content', 'text', '', NULL, NULL, NULL, 2, 1, NULL, '2026-05-30 19:45:55'),
(26, 23, NULL, 'Lesson 1', 'Sample Text', 'text', '', NULL, NULL, NULL, 1, 1, NULL, '2026-05-31 08:53:17'),
(27, 24, NULL, 'Video Lesson', '<div class=\"lesson-video-embed ratio ratio-16x9 mb-3\" data-video-url=\"https://www.youtube.com/watch?v=r4cAnAIa48I&amp;list=PLNdfZn6P-7yidLTVoujM8N5bR2yceR0U6\"><iframe src=\"https://www.youtube.com/embed/r4cAnAIa48I\" title=\"Lesson video\" allow=\"accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share\" allowfullscreen></iframe></div>\n<div class=\"lesson-video-notes\">Sample Lesson</div>', 'video', '', NULL, NULL, NULL, 1, 1, NULL, '2026-05-31 08:53:34'),
(28, 24, NULL, 'Lesson - External Link', '<div class=\"lesson-link-embed mb-3\" data-link-url=\"https://srmsportal.com\"><a href=\"https://srmsportal.com\" target=\"_blank\" rel=\"noopener\" class=\"btn btn-outline-success\"><i class=\"bi bi-box-arrow-up-right me-1\"></i>Open External Link</a></div>', 'link', '', NULL, NULL, NULL, 2, 1, NULL, '2026-05-31 08:55:20'),
(29, 24, NULL, 'Lesson - File Upload ', '<div class=\"lesson-file-embed mb-3\" data-file-url=\"http://localhost/lms/uploads/lessons/be26bd28351ba445ab6485abd4eb65b5.pdf\"><div class=\"lesson-file-toolbar mb-2\"><a href=\"http://localhost/lms/uploads/lessons/be26bd28351ba445ab6485abd4eb65b5.pdf\" target=\"_blank\" rel=\"noopener\" class=\"btn btn-outline-primary\"><i class=\"bi bi-file-earmark-pdf me-1\"></i>Open PDF</a></div><div class=\"ratio ratio-4x3 lesson-file-preview\"><iframe src=\"http://localhost/lms/uploads/lessons/be26bd28351ba445ab6485abd4eb65b5.pdf\" title=\"PDF preview\" loading=\"lazy\"></iframe></div></div>', 'file', 'http://localhost/lms/uploads/lessons/be26bd28351ba445ab6485abd4eb65b5.pdf', NULL, NULL, NULL, 3, 1, NULL, '2026-05-31 08:56:16'),
(30, 24, NULL, 'Web Page', '<b>Discussion: Wika</b><div><b>Kahulugan ng Wika</b></div><div>Ang wika ay isang sistematikong paraan ng pakikipagkomunikasyon na gumagamit ng mga tunog, salita, simbolo, at tuntunin upang maipahayag ang kaisipan, damdamin, karanasan, at impormasyon. Ito ang pangunahing kasangkapan ng tao upang makipag-ugnayan sa kapwa.</div><div>Sa madaling salita, ang wika ay ginagamit upang magsalita, makinig, magsulat, magbasa, magpaliwanag, magtanong, at makipagpalitan ng ideya.</div><div><br></div><div><b>Kahalagahan ng Wika</b></div><div>1. Wika bilang Kasangkapan sa Komunikasyon</div><div>Ginagamit ang wika upang maipahayag ang ating iniisip at nararamdaman. Sa pamamagitan nito, nagkakaintindihan ang mga tao sa pamilya, paaralan, trabaho, pamahalaan, at lipunan.</div><div>Halimbawa:</div><div>Kapag nagtatanong ang mag-aaral sa guro, ginagamit niya ang wika upang malinaw na maipahayag ang kanyang nais malaman.</div><div><br></div><div>2. Wika bilang Pagkakakilanlan ng Isang Bansa</div><div>Ang wika ay bahagi ng kultura at identidad ng isang bansa. Ipinapakita nito ang kasaysayan, paniniwala, tradisyon, at pamumuhay ng mga mamamayan.</div><div>Halimbawa:</div><div>Ang wikang Filipino ay sumisimbolo sa pagkakaisa at pagkakakilanlan ng mga Pilipino.</div><div><br></div><div>3. Wika bilang Tagapag-ingat ng Kultura</div><div>Sa pamamagitan ng wika, naipapasa ang mga kuwento, salawikain, awit, tula, alamat, epiko, at iba pang panitikan mula sa isang henerasyon patungo sa susunod.</div><div>Halimbawa:</div><div>Ang mga kuwentong-bayan tulad ng alamat at pabula ay naipapanatili dahil sa paggamit ng wika.</div><div><br></div><div>4. Wika bilang Kasangkapan sa Edukasyon</div><div>Mahalaga ang wika sa pagtuturo at pagkatuto. Ito ang ginagamit upang ipaliwanag ang mga aralin, magbigay ng panuto, sumagot sa pagsusulit, at makibahagi sa talakayan.</div><div>Makikita rin sa larangan ng edukasyon na mahalaga ang malinaw na komunikasyon at paggamit ng mga platform sa pagkatuto, gaya ng binanggit sa uploaded lesson na ang mga e-learning platform ay tumutulong sa interaksyon ng guro at mag-aaral.&nbsp;</div><div><br></div><div>Katangian ng Wika</div><div>1. Ang wika ay masistemang balangkas</div><div>May sinusunod na tuntunin ang wika. Hindi basta-basta pinagsasama ang mga tunog at salita. May tamang ayos ang mga salita upang maging malinaw ang kahulugan.</div><div>Halimbawa:</div><div>Tama: Ang bata ay nagbabasa ng aklat.</div><div>Mali: Aklat bata nagbabasa ang ng ay.</div><div><br></div><div>2. Ang wika ay sinasalitang tunog</div><div>Ang wika ay karaniwang nagsisimula sa mga tunog na binibigkas ng tao. Ang mga tunog na ito ay nagkakaroon ng kahulugan kapag pinagsama-sama.</div><div><br></div><div>3. Ang wika ay arbitraryo</div><div>Ang kahulugan ng salita ay napagkasunduan lamang ng mga taong gumagamit nito. Walang likas na ugnayan ang tunog ng salita sa bagay na tinutukoy nito.</div><div>Halimbawa:</div><div>Ang salitang bahay sa Filipino ay house sa English at balay sa Cebuano.</div><div><br></div><div>4. Ang wika ay dinamiko</div><div>Nagbabago ang wika sa paglipas ng panahon. May mga bagong salita na nadaragdag dahil sa teknolohiya, kultura, at pagbabago sa lipunan.</div><div>Halimbawa:</div><div>Mga salitang tulad ng online class, chat, selfie, vlog, at hashtag ay bahagi na ng pang-araw-araw na komunikasyon.</div><div><br></div><div>5. Ang wika ay pantao</div><div>Tao lamang ang may kakayahang gumamit ng komplikadong wika upang magpahayag ng malalim na kaisipan, opinyon, damdamin, at karanasan.</div><div><br></div><div>Antas ng Wika</div><div>1. Pormal</div><div>Ito ay ginagamit sa paaralan, opisina, pamahalaan, at pormal na sulatin.</div><div>Halimbawa:</div><div>Ang edukasyon ay mahalaga sa pag-unlad ng mamamayan.</div><div>2. Di-pormal</div><div>Ito ay karaniwang ginagamit sa pang-araw-araw na pakikipag-usap.</div><div>Halimbawa:</div><div>Uy, kumusta ka na?</div><div>3. Kolokyal</div><div>Ito ay pinaikling salita na ginagamit sa ordinaryong usapan.</div><div>Halimbawa:</div><div>meron → mer’n</div><div>nasaan → asan</div><div>4. Balbal</div><div>Ito ay salitang-kalye o slang.</div><div>Halimbawa:</div><div>petmalu, lodi, chibog</div><div>5. Lalawiganin</div><div>Ito ay salitang ginagamit sa partikular na rehiyon o lugar.</div><div>Halimbawa:</div><div>balay — bahay</div><div>adto — punta</div><div>kaon — kain</div><div><br></div><div>Gampanin ng Wika</div><div>1. Instrumental</div><div>Ginagamit upang matugunan ang pangangailangan.</div><div>Halimbawa:</div><div>Pahingi po ng tubig.</div><div>2. Regulatoryo</div><div>Ginagamit upang magbigay ng utos, panuto, o batas.</div><div>Halimbawa:</div><div>Bawal tumawid dito.</div><div>3. Interaksyonal</div><div>Ginagamit upang makipag-ugnayan sa kapwa.</div><div>Halimbawa:</div><div>Kumusta ka?</div><div>4. Personal</div><div>Ginagamit upang ipahayag ang sariling damdamin o opinyon.</div><div>Halimbawa:</div><div>Masaya ako sa resulta ng pagsusulit.</div><div>5. Heuristiko</div><div>Ginagamit upang magtanong at matuto.</div><div>Halimbawa:</div><div>Bakit mahalaga ang wika sa lipunan?</div><div>6. Imahinatibo</div><div>Ginagamit sa malikhaing pagpapahayag.</div><div>Halimbawa:</div><div>Pagsulat ng tula, kuwento, awit, o dula.</div><div>7. Impormatibo</div><div>Ginagamit upang magbigay ng impormasyon.</div><div>Halimbawa:</div><div>Ang Filipino ang pambansang wika ng Pilipinas.</div><div><br></div><div>Konklusyon</div><div>Ang wika ay mahalagang bahagi ng buhay ng tao. Hindi lamang ito ginagamit sa pakikipag-usap, kundi nagsisilbi rin itong tulay ng pagkakaunawaan, instrumento ng edukasyon, tagapag-ingat ng kultura, at simbolo ng pagkakakilanlan. Sa patuloy na paggamit at pagpapahalaga sa wika, napapanatili natin ang ating kultura, pagkakaisa, at pagiging Pilipino.</div>', 'page', '', NULL, NULL, NULL, 4, 1, NULL, '2026-05-31 08:59:47'),
(31, 26, NULL, 'Text / HTML', '<div>The history of computing spans from ancient mechanical counting devices to modern digital systems. It is generally categorized into five technological generations, each defined by a major hardware breakthrough that made computers smaller, faster, and more powerful.</div><div><br></div><div><b>1. Early Mechanical Era (Pre-1940s)</b></div><div>Before electronic power, computing relied on physical gears and levers:The Abacus (3000 B.C.): Widely considered the earliest calculating tool, using beads for basic math.</div>', 'text', '', NULL, NULL, NULL, 1, 1, NULL, '2026-06-02 21:40:15'),
(32, 26, NULL, 'Video Lesson', '<div class=\"lesson-video-embed ratio ratio-16x9 mb-3\" data-video-url=\"https://www.youtube.com/watch?v=Ow1BLT29p9w\"><iframe src=\"https://www.youtube.com/embed/Ow1BLT29p9w\" title=\"Lesson video\" allow=\"accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share\" allowfullscreen></iframe></div>', 'video', '', NULL, NULL, NULL, 2, 1, NULL, '2026-06-02 21:41:11'),
(33, 26, NULL, 'PDF File', '<div class=\"lesson-file-embed mb-3\" data-file-url=\"https://lms.depeddavor.com/uploads/lessons/14e1e5bb284c82d7672afc6ef2552838.pdf\"><div class=\"lesson-file-toolbar mb-2\"><a href=\"https://lms.depeddavor.com/uploads/lessons/14e1e5bb284c82d7672afc6ef2552838.pdf\" target=\"_blank\" rel=\"noopener\" class=\"btn btn-outline-primary\"><i class=\"bi bi-file-earmark-pdf me-1\"></i>Open PDF</a></div><div class=\"ratio ratio-4x3 lesson-file-preview\"><iframe src=\"https://lms.depeddavor.com/uploads/lessons/14e1e5bb284c82d7672afc6ef2552838.pdf\" title=\"PDF preview\" loading=\"lazy\"></iframe></div></div>', 'file', 'https://lms.depeddavor.com/uploads/lessons/14e1e5bb284c82d7672afc6ef2552838.pdf', NULL, NULL, NULL, 3, 1, NULL, '2026-06-02 21:41:38'),
(34, 26, NULL, 'External Link', '<div class=\"lesson-link-embed mb-3\" data-link-url=\"https://www.computerhistory.org/timeline/computers/\"><a href=\"https://www.computerhistory.org/timeline/computers/\" target=\"_blank\" rel=\"noopener\" class=\"btn btn-outline-success\"><i class=\"bi bi-box-arrow-up-right me-1\"></i>Open External Link</a></div>', 'link', '', NULL, NULL, NULL, 4, 1, NULL, '2026-06-02 21:42:24'),
(35, 27, NULL, 'Intro to Information Technology (IT)', '<div><h2>Key Concepts</h2>\r\n<h3>1. What is Information Technology?</h3>\r\n<p><strong>Definition</strong>:<br>\r\nInformation Technology (IT) refers to the use of computers, software, networks, and systems to store, process, and transmit information.</p>\r\n<p>✅ Example:</p>\r\n<ul><li>Using a smartphone to send a message</li><li>Storing files on a computer</li></ul>\r\n\r\n<h3>2. Components of IT Systems</h3>\r\n<h4>a. Hardware</h4>\r\n<p>Physical parts of a computer system.</p>\r\n<p>Examples:</p>\r\n<ul><li>Keyboard</li><li>Monitor</li><li>CPU</li><li>Mouse</li><li>Printer</li></ul>\r\n<h4>b. Software</h4>\r\n<p>Programs that tell the computer what to do.</p>\r\n<p>Types:</p>\r\n<ul><li>System software (e.g., Windows, macOS)</li><li>Application software (e.g., MS Word, Excel, games)</li></ul>\r\n<h4>c. Networking</h4>\r\n<p>Connecting computers to share data.</p>\r\n<p>Examples:</p>\r\n<ul><li>Internet</li><li>Wi-Fi</li><li>Local Area Network (LAN)</li></ul>\r\n<h4>d. Users (Peopleware)</h4>\r\n<p>People who use the IT systems.</p>\r\n\r\n<h3>3. Basic Computer Operations</h3>\r\n<ul><li>Input → Processing → Output → Storage</li></ul>\r\n<p>Example:</p><ul><li>Typing on keyboard (input)</li><li>Computer processes data</li><li>Display result on screen (output)</li></ul></div>', 'text', '', NULL, NULL, NULL, 1, 1, NULL, '2026-06-03 01:34:23'),
(36, 27, NULL, 'Lesson in PDF Format', '<div class=\"lesson-file-embed mb-3\" data-file-url=\"https://lms.depeddavor.com/uploads/lessons/6f84ec4667a56b5db88a3f804b785228.pdf\"><div class=\"lesson-file-toolbar mb-2\"><a href=\"https://lms.depeddavor.com/uploads/lessons/6f84ec4667a56b5db88a3f804b785228.pdf\" target=\"_blank\" rel=\"noopener\" class=\"btn btn-outline-primary\"><i class=\"bi bi-file-earmark-pdf me-1\"></i>Open PDF</a></div><div class=\"ratio ratio-4x3 lesson-file-preview\"><iframe src=\"https://lms.depeddavor.com/uploads/lessons/6f84ec4667a56b5db88a3f804b785228.pdf\" title=\"PDF preview\" loading=\"lazy\"></iframe></div></div>', 'file', 'https://lms.depeddavor.com/uploads/lessons/6f84ec4667a56b5db88a3f804b785228.pdf', NULL, NULL, NULL, 3, 1, NULL, '2026-06-03 02:41:08'),
(37, 27, NULL, 'Video Lesson Sample', '<div class=\"lesson-video-embed ratio ratio-16x9 mb-3\" data-video-url=\"https://www.youtube.com/watch?v=4qmVGCIbNgk\"><iframe src=\"https://www.youtube.com/embed/4qmVGCIbNgk\" title=\"Lesson video\" allow=\"accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share\" allowfullscreen></iframe></div>', 'video', '', NULL, NULL, NULL, 4, 1, NULL, '2026-06-03 02:42:47'),
(38, 27, NULL, 'Understanding-Algorithms-The-Heart-of-Problem-Solving', '<div class=\"lesson-file-embed mb-3\" data-file-url=\"https://lms.depeddavor.com/uploads/lessons/b41187a0f73d5ac72bc3e4a9b6703e37.pdf\"><div class=\"lesson-file-toolbar mb-2\"><a href=\"https://lms.depeddavor.com/uploads/lessons/b41187a0f73d5ac72bc3e4a9b6703e37.pdf\" target=\"_blank\" rel=\"noopener\" class=\"btn btn-outline-primary\"><i class=\"bi bi-file-earmark-pdf me-1\"></i>Open PDF</a></div><div class=\"ratio ratio-4x3 lesson-file-preview\"><iframe src=\"https://lms.depeddavor.com/uploads/lessons/b41187a0f73d5ac72bc3e4a9b6703e37.pdf\" title=\"PDF preview\" loading=\"lazy\"></iframe></div></div>', 'file', 'https://lms.depeddavor.com/uploads/lessons/b41187a0f73d5ac72bc3e4a9b6703e37.pdf', NULL, NULL, NULL, 5, 1, NULL, '2026-06-03 05:35:10'),
(39, 27, NULL, 'Flowcharts-Visualizing-Processes-with-Clarity', '<div class=\"lesson-file-embed mb-3\" data-file-url=\"https://lms.depeddavor.com/uploads/lessons/0c3cd5197fdb63ad650ea12071e1a799.pdf\"><div class=\"lesson-file-toolbar mb-2\"><a href=\"https://lms.depeddavor.com/uploads/lessons/0c3cd5197fdb63ad650ea12071e1a799.pdf\" target=\"_blank\" rel=\"noopener\" class=\"btn btn-outline-primary\"><i class=\"bi bi-file-earmark-pdf me-1\"></i>Open PDF</a></div><div class=\"ratio ratio-4x3 lesson-file-preview\"><iframe src=\"https://lms.depeddavor.com/uploads/lessons/0c3cd5197fdb63ad650ea12071e1a799.pdf\" title=\"PDF preview\" loading=\"lazy\"></iframe></div></div>', 'file', 'https://lms.depeddavor.com/uploads/lessons/0c3cd5197fdb63ad650ea12071e1a799.pdf', NULL, NULL, NULL, 6, 1, NULL, '2026-06-03 05:36:31'),
(40, 28, NULL, 'Sample Lesson', '<div style=\"font-weight:bold\">The Five Generations of Computing<span></span></div><div><ul><li><span><strong style=\"font-weight:bold\">First Generation (1940–1956): Vacuum Tubes</strong><br>Early electronic computers used vacuum tubes as circuitry and magnetic drums for memory. They were massive, consumed immense amounts of power, and generated excessive heat.</span><ul><li><span><em>Key Examples:</em> <span>ENIAC</span> and UNIVAC.</span><span> [<a href=\"https://www.livescience.com/20718-computer-history.html\">1</a>, <a href=\"https://www.youtube.com/watch?v=gjVX47dLlN8&amp;vl=en\">2</a>, <a href=\"https://study.com/academy/lesson/video/history-of-computers-timeline-evolution.html\">3</a>]</span></li></ul></li><li><span><strong style=\"font-weight:bold\">Second Generation (1956–1963): Transistors</strong><br>Transistors replaced bulky vacuum tubes, allowing computers to become smaller, faster, cheaper, and more energy-efficient.</span></li></ul></div>', 'text', '', NULL, NULL, NULL, 1, 1, NULL, '2026-06-03 06:24:37'),
(41, 28, NULL, 'kkhjkjkhhk', '<div class=\"lesson-video-embed ratio ratio-16x9 mb-3\" data-video-url=\"https://www.youtube.com/watch?v=49M1FYOlUuo\"><iframe src=\"https://www.youtube.com/embed/49M1FYOlUuo\" title=\"Lesson video\" allow=\"accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share\" allowfullscreen></iframe></div>', 'video', '', NULL, NULL, NULL, 2, 1, NULL, '2026-06-03 06:25:48'),
(42, 28, NULL, 'PDF', '<div class=\"lesson-file-embed mb-3\" data-file-url=\"https://lms.depeddavor.com/uploads/lessons/24ac7d2ab2a8f3bf3084cce708117611.pdf\"><div class=\"lesson-file-toolbar mb-2\"><a href=\"https://lms.depeddavor.com/uploads/lessons/24ac7d2ab2a8f3bf3084cce708117611.pdf\" target=\"_blank\" rel=\"noopener\" class=\"btn btn-outline-primary\"><i class=\"bi bi-file-earmark-pdf me-1\"></i>Open PDF</a></div><div class=\"ratio ratio-4x3 lesson-file-preview\"><iframe src=\"https://lms.depeddavor.com/uploads/lessons/24ac7d2ab2a8f3bf3084cce708117611.pdf\" title=\"PDF preview\" loading=\"lazy\"></iframe></div></div>', 'file', 'https://lms.depeddavor.com/uploads/lessons/24ac7d2ab2a8f3bf3084cce708117611.pdf', NULL, NULL, NULL, 3, 1, NULL, '2026-06-03 06:29:36'),
(43, 29, NULL, 'Mga Suliraning Pangkapaligiran: Sanhi at Epekto sa Pilipinas at sa Mundo', '<div class=\"lesson-file-embed mb-3\" data-file-url=\"https://lms.depeddavor.com/uploads/lessons/8b4183e5cc78ad35753f61c6ba91cbe7.pdf\"><div class=\"lesson-file-toolbar mb-2\"><a href=\"https://lms.depeddavor.com/uploads/lessons/8b4183e5cc78ad35753f61c6ba91cbe7.pdf\" target=\"_blank\" rel=\"noopener\" class=\"btn btn-outline-primary\"><i class=\"bi bi-file-earmark-pdf me-1\"></i>Open PDF</a></div><div class=\"ratio ratio-4x3 lesson-file-preview\"><iframe src=\"https://lms.depeddavor.com/uploads/lessons/8b4183e5cc78ad35753f61c6ba91cbe7.pdf\" title=\"PDF preview\" loading=\"lazy\"></iframe></div></div>', 'file', 'https://lms.depeddavor.com/uploads/lessons/8b4183e5cc78ad35753f61c6ba91cbe7.pdf', NULL, NULL, NULL, 1, 1, NULL, '2026-06-03 08:16:21'),
(44, 31, NULL, 'KAMALAYANG PANGKASAYSAYAN', '<div class=\"lesson-file-embed mb-3\" data-file-url=\"https://lms.depeddavor.com/uploads/lessons/541c6d7df778556263411a2c2d2fc576.pdf\"><div class=\"lesson-file-toolbar mb-2\"><a href=\"https://lms.depeddavor.com/uploads/lessons/541c6d7df778556263411a2c2d2fc576.pdf\" target=\"_blank\" rel=\"noopener\" class=\"btn btn-outline-primary\"><i class=\"bi bi-file-earmark-pdf me-1\"></i>Open PDF</a></div><div class=\"ratio ratio-4x3 lesson-file-preview\"><iframe src=\"https://lms.depeddavor.com/uploads/lessons/541c6d7df778556263411a2c2d2fc576.pdf\" title=\"PDF preview\" loading=\"lazy\"></iframe></div></div>', 'file', 'https://lms.depeddavor.com/uploads/lessons/541c6d7df778556263411a2c2d2fc576.pdf', NULL, NULL, NULL, 1, 1, NULL, '2026-06-03 22:00:18'),
(45, 31, NULL, 'Kamalayang Pangkasaysayan', '<div class=\"lesson-video-embed ratio ratio-16x9 mb-3\" data-video-url=\"https://www.youtube.com/watch?v=132m81O5Jls\"><iframe src=\"https://www.youtube.com/embed/132m81O5Jls\" title=\"Lesson video\" allow=\"accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share\" allowfullscreen></iframe></div>', 'video', '', NULL, NULL, NULL, 2, 1, NULL, '2026-06-03 22:05:12'),
(46, 33, NULL, 'Sesyon 1 - Unemployment', '<div class=\"lesson-file-embed mb-3\" data-file-url=\"https://lms.depeddavor.com/uploads/lessons/1ae33e2f95e46001eacca2075d5a2811.pdf\"><div class=\"lesson-file-toolbar mb-2\"><a href=\"https://lms.depeddavor.com/uploads/lessons/1ae33e2f95e46001eacca2075d5a2811.pdf\" target=\"_blank\" rel=\"noopener\" class=\"btn btn-outline-primary\"><i class=\"bi bi-file-earmark-pdf me-1\"></i>Open PDF</a></div><div class=\"ratio ratio-4x3 lesson-file-preview\"><iframe src=\"https://lms.depeddavor.com/uploads/lessons/1ae33e2f95e46001eacca2075d5a2811.pdf\" title=\"PDF preview\" loading=\"lazy\"></iframe></div></div>\n<p><br></p>\r\n\r\n<p><br></p><div><p><i><br></i></p></div>', 'file', 'https://lms.depeddavor.com/uploads/lessons/1ae33e2f95e46001eacca2075d5a2811.pdf', NULL, NULL, NULL, 1, 1, NULL, '2026-06-04 12:25:20'),
(47, 34, 2, 'sample', 'sdfdf', 'text', '', NULL, NULL, NULL, 1, 1, NULL, '2026-06-05 07:23:11'),
(48, 34, 1, 'dsfadsf', 'dsfadsfds', 'text', '', NULL, NULL, NULL, 2, 1, NULL, '2026-06-05 07:34:08'),
(49, 35, 1, 'Bread', '', 'text', '', NULL, NULL, NULL, 2, 1, NULL, '2026-06-05 16:51:53');

-- --------------------------------------------------------

--
-- Table structure for table `lesson_completions`
--

CREATE TABLE `lesson_completions` (
  `id` int UNSIGNED NOT NULL,
  `student_id` int UNSIGNED NOT NULL,
  `lesson_id` int UNSIGNED NOT NULL,
  `completed_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `lesson_completions`
--

INSERT INTO `lesson_completions` (`id`, `student_id`, `lesson_id`, `completed_at`) VALUES
(35, 10, 35, '2026-06-03 11:27:01'),
(36, 10, 36, '2026-06-03 11:27:12'),
(37, 10, 37, '2026-06-03 11:27:59'),
(38, 10, 38, '2026-06-03 14:14:07'),
(39, 10, 39, '2026-06-03 14:15:06'),
(40, 10, 40, '2026-06-03 14:35:59'),
(41, 10, 41, '2026-06-03 14:36:55'),
(42, 10, 42, '2026-06-03 14:37:12'),
(43, 10, 46, '2026-06-05 06:46:16');

-- --------------------------------------------------------

--
-- Table structure for table `lesson_notes`
--

CREATE TABLE `lesson_notes` (
  `id` int UNSIGNED NOT NULL,
  `lesson_id` int UNSIGNED NOT NULL,
  `school_id` int UNSIGNED DEFAULT NULL,
  `note_text` text NOT NULL,
  `created_by` int UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `lesson_notes`
--

INSERT INTO `lesson_notes` (`id`, `lesson_id`, `school_id`, `note_text`, `created_by`, `created_at`, `updated_at`) VALUES
(1, 48, 17, 'Note sample', 138, '2026-06-05 06:19:06', '2026-06-05 06:19:06');

-- --------------------------------------------------------

--
-- Table structure for table `lesson_plans`
--

CREATE TABLE `lesson_plans` (
  `id` int NOT NULL,
  `lesson_id` int NOT NULL,
  `school_id` int DEFAULT NULL,
  `objectives` text,
  `subject_matter` text,
  `materials` text,
  `procedures` text,
  `evaluation` text,
  `assignment` text,
  `remarks` text,
  `created_by` int UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `lesson_progress`
--

CREATE TABLE `lesson_progress` (
  `id` int UNSIGNED NOT NULL,
  `student_id` int UNSIGNED NOT NULL,
  `lesson_id` int UNSIGNED NOT NULL,
  `status` enum('not_started','in_progress','completed') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT 'not_started',
  `progress_percent` tinyint DEFAULT '0',
  `completed_at` datetime DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `lesson_taught_statuses`
--

CREATE TABLE `lesson_taught_statuses` (
  `id` int UNSIGNED NOT NULL,
  `lesson_id` int UNSIGNED NOT NULL,
  `user_id` int UNSIGNED NOT NULL,
  `taught_at` datetime NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `lesson_taught_statuses`
--

INSERT INTO `lesson_taught_statuses` (`id`, `lesson_id`, `user_id`, `taught_at`, `created_at`) VALUES
(1, 46, 138, '2026-06-05 09:10:17', '2026-06-05 09:10:17'),
(2, 47, 138, '2026-06-05 09:11:39', '2026-06-05 09:11:39');

-- --------------------------------------------------------

--
-- Table structure for table `melcs`
--

CREATE TABLE `melcs` (
  `id` int UNSIGNED NOT NULL,
  `subject_id` int UNSIGNED NOT NULL,
  `competency_code` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `quarter` tinyint(1) DEFAULT NULL,
  `order_num` int DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `modules`
--

CREATE TABLE `modules` (
  `id` int UNSIGNED NOT NULL,
  `subject_id` int UNSIGNED DEFAULT NULL,
  `course_id` int UNSIGNED DEFAULT NULL,
  `class_program_id` int UNSIGNED DEFAULT NULL,
  `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `order_num` int DEFAULT '1',
  `is_published` tinyint(1) NOT NULL DEFAULT '0',
  `created_by` int UNSIGNED DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `modules`
--

INSERT INTO `modules` (`id`, `subject_id`, `course_id`, `class_program_id`, `title`, `description`, `order_num`, `is_published`, `created_by`, `created_at`) VALUES
(1, NULL, 1, NULL, 'Module 1', 'Module Description Here', 1, 1, 5, '2026-04-21 05:01:37'),
(2, NULL, 1, NULL, 'Module 2', 'Module 2 is all about kkkkkkkkk', 2, 1, 5, '2026-04-21 05:23:58'),
(3, 6, NULL, NULL, 'Module 1', 'asfdf', 1, 1, 15, '2026-04-21 17:08:03'),
(4, 5, NULL, NULL, 'Module 1', 'Description', 1, 1, 15, '2026-04-21 21:27:16'),
(5, 6, NULL, NULL, 'Module 2', 'Description', 2, 1, 15, '2026-04-21 22:09:23'),
(6, 10, NULL, NULL, 'Module 1', '', 1, 0, 17, '2026-04-22 09:00:00'),
(7, 10, NULL, NULL, 'Module 2', 'dsfadsf', 2, 1, 17, '2026-04-22 09:13:01'),
(8, 10, NULL, NULL, 'Assessment', '', 3, 0, 17, '2026-04-22 09:33:34'),
(9, 11, NULL, NULL, 'Module 1', '', 1, 1, 17, '2026-04-22 10:33:53'),
(10, 11, NULL, NULL, 'Module 2', '', 2, 1, 17, '2026-04-22 10:35:09'),
(11, 14, NULL, NULL, 'Module 1', '', 1, 1, 20, '2026-04-25 14:45:22'),
(12, 12, NULL, NULL, 'Week 1 Introduction', '', 1, 0, 20, '2026-04-25 15:27:49'),
(13, 12, NULL, NULL, 'Week 2 Pogramming', '', 2, 0, 20, '2026-04-25 15:28:02'),
(14, 17, NULL, NULL, 'Module 1', 'Description Here', 1, 1, 25, '2026-05-30 12:33:58'),
(15, 17, NULL, NULL, 'Module 2', '', 2, 1, 25, '2026-05-30 12:38:49'),
(16, 17, NULL, NULL, 'Module 3', '', 3, 1, 25, '2026-05-30 12:39:14'),
(17, 22, NULL, NULL, 'Module 1', '', 1, 0, 25, '2026-05-30 17:15:59'),
(18, 22, NULL, NULL, 'Module 2', '', 2, 0, 25, '2026-05-30 17:16:04'),
(19, 22, NULL, NULL, 'Module 3', '', 3, 0, 25, '2026-05-30 17:16:12'),
(26, 36, NULL, NULL, 'Module 1', 'Description Here', 1, 1, 86, '2026-06-02 21:38:43'),
(27, 37, NULL, NULL, 'Quarter 1', '', 1, 1, 138, '2026-06-03 01:31:42'),
(28, 37, NULL, NULL, 'Quarter 2', '', 2, 1, 138, '2026-06-03 06:18:50'),
(32, 55, NULL, NULL, 'Kontemporanyong Isyu', 'Unang Termino', 1, 0, 328, '2026-06-03 22:59:45'),
(33, 58, NULL, NULL, 'Term 1: Week 9', '', 2, 1, 196, '2026-06-04 11:26:10'),
(34, 58, NULL, NULL, 'Term 1: Week 2', '', 1, 1, 138, '2026-06-05 07:19:23'),
(35, 58, NULL, NULL, 'hhgh', 'jh,gjghjhg', 3, 1, 138, '2026-06-05 10:26:37'),
(36, 57, NULL, NULL, 'dfdf', 'dfdf', 1, 1, 138, '2026-06-05 12:08:59');

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `id` int UNSIGNED NOT NULL,
  `user_id` int UNSIGNED NOT NULL,
  `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `message` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `type` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT 'info',
  `link` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_read` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `parents`
--

CREATE TABLE `parents` (
  `id` int UNSIGNED NOT NULL,
  `user_id` int UNSIGNED NOT NULL,
  `relationship` enum('father','mother','guardian') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT 'guardian',
  `occupation` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `contact_number` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `parent_student`
--

CREATE TABLE `parent_student` (
  `id` int UNSIGNED NOT NULL,
  `parent_id` int UNSIGNED NOT NULL,
  `student_id` int UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `platform_settings`
--

CREATE TABLE `platform_settings` (
  `id` int UNSIGNED NOT NULL,
  `setting_key` varchar(100) NOT NULL,
  `setting_value` text,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Dumping data for table `platform_settings`
--

INSERT INTO `platform_settings` (`id`, `setting_key`, `setting_value`, `created_at`, `updated_at`) VALUES
(2, 'login_image', '841ac0f8a76da13bb2c879a04df40347.jpg', '2026-06-02 21:32:08', '2026-06-02 21:32:08');

-- --------------------------------------------------------

--
-- Table structure for table `programs`
--

CREATE TABLE `programs` (
  `id` int UNSIGNED NOT NULL,
  `school_id` int UNSIGNED NOT NULL DEFAULT '1',
  `year_level` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `programs`
--

INSERT INTO `programs` (`id`, `school_id`, `year_level`, `description`) VALUES
(45, 17, '6', ''),
(46, 18, '6', ''),
(47, 19, '6', ''),
(48, 20, '6', ''),
(49, 21, '6', ''),
(50, 22, '6', ''),
(51, 23, '6', ''),
(52, 24, '6', ''),
(53, 25, '6', ''),
(54, 26, '6', ''),
(55, 27, '6', ''),
(56, 28, '6', ''),
(57, 29, '6', ''),
(58, 30, '6', ''),
(59, 31, '6', ''),
(60, 32, '6', ''),
(61, 33, '6', ''),
(62, 34, '6', ''),
(63, 35, '6', ''),
(64, 36, '6', ''),
(65, 37, '6', ''),
(66, 38, '6', ''),
(67, 39, '6', ''),
(68, 40, '6', ''),
(69, 41, '6', ''),
(70, 42, '6', ''),
(71, 43, '6', ''),
(72, 44, '6', ''),
(73, 45, '6', ''),
(74, 46, '6', ''),
(75, 47, '6', ''),
(76, 17, '4', ''),
(77, 18, '4', ''),
(78, 19, '4', ''),
(79, 20, '4', ''),
(80, 21, '4', ''),
(81, 22, '4', ''),
(82, 23, '4', ''),
(83, 24, '4', ''),
(84, 25, '4', ''),
(85, 26, '4', ''),
(86, 27, '4', ''),
(87, 28, '4', ''),
(88, 29, '4', ''),
(89, 30, '4', ''),
(90, 31, '4', ''),
(91, 32, '4', ''),
(92, 33, '4', ''),
(93, 34, '4', ''),
(94, 35, '4', ''),
(95, 36, '4', ''),
(96, 37, '4', ''),
(97, 38, '4', ''),
(98, 39, '4', ''),
(99, 40, '4', ''),
(100, 41, '4', ''),
(101, 42, '4', ''),
(102, 43, '4', ''),
(103, 44, '4', ''),
(104, 45, '4', ''),
(105, 46, '4', ''),
(106, 47, '4', ''),
(107, 17, '10', ''),
(108, 18, '10', ''),
(109, 19, '10', ''),
(110, 20, '10', ''),
(111, 21, '10', ''),
(112, 22, '10', ''),
(113, 23, '10', ''),
(114, 24, '10', ''),
(115, 25, '10', ''),
(116, 26, '10', ''),
(117, 27, '10', ''),
(118, 28, '10', ''),
(119, 29, '10', ''),
(120, 30, '10', ''),
(121, 31, '10', ''),
(122, 32, '10', ''),
(123, 33, '10', ''),
(124, 34, '10', ''),
(125, 35, '10', ''),
(126, 36, '10', ''),
(127, 37, '10', ''),
(128, 38, '10', ''),
(129, 39, '10', ''),
(130, 40, '10', ''),
(131, 41, '10', ''),
(132, 42, '10', ''),
(133, 43, '10', ''),
(134, 44, '10', ''),
(135, 45, '10', ''),
(136, 46, '10', ''),
(137, 47, '10', ''),
(138, 17, '11', ''),
(139, 18, '11', ''),
(140, 19, '11', ''),
(141, 20, '11', ''),
(142, 21, '11', ''),
(143, 22, '11', ''),
(144, 23, '11', ''),
(145, 24, '11', ''),
(146, 25, '11', ''),
(147, 26, '11', ''),
(148, 27, '11', ''),
(149, 28, '11', ''),
(150, 29, '11', ''),
(151, 30, '11', ''),
(152, 31, '11', ''),
(153, 32, '11', ''),
(154, 33, '11', ''),
(155, 34, '11', ''),
(156, 35, '11', ''),
(157, 36, '11', ''),
(158, 37, '11', ''),
(159, 38, '11', ''),
(160, 39, '11', ''),
(161, 40, '11', ''),
(162, 41, '11', ''),
(163, 42, '11', ''),
(164, 43, '11', ''),
(165, 44, '11', ''),
(166, 45, '11', ''),
(167, 46, '11', ''),
(168, 47, '11', '');

-- --------------------------------------------------------

--
-- Table structure for table `program_outcomes`
--

CREATE TABLE `program_outcomes` (
  `id` int UNSIGNED NOT NULL,
  `program_id` int UNSIGNED NOT NULL,
  `code` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `order_num` tinyint DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `question_bank`
--

CREATE TABLE `question_bank` (
  `id` int UNSIGNED NOT NULL,
  `subject_id` int UNSIGNED NOT NULL,
  `question_type` enum('multiple_choice','identification','essay','true_false') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `question_text` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `answer_key` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `difficulty` enum('easy','medium','hard') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT 'medium',
  `created_by` int UNSIGNED DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `quizzes`
--

CREATE TABLE `quizzes` (
  `id` int UNSIGNED NOT NULL,
  `course_id` int UNSIGNED DEFAULT NULL,
  `class_program_id` int UNSIGNED DEFAULT NULL,
  `school_id` int UNSIGNED DEFAULT NULL,
  `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `quiz_type` enum('quiz','exam','assignment','activity') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'quiz',
  `component_id` int UNSIGNED DEFAULT NULL,
  `total_points` decimal(6,2) DEFAULT NULL,
  `time_limit_minutes` int DEFAULT NULL,
  `max_attempts` tinyint DEFAULT '1',
  `shuffle_questions` tinyint(1) DEFAULT '0',
  `show_results` tinyint(1) DEFAULT '1',
  `available_from` datetime DEFAULT NULL,
  `available_until` datetime DEFAULT NULL,
  `is_published` tinyint(1) NOT NULL DEFAULT '0',
  `passing_score` decimal(6,2) DEFAULT NULL,
  `quiz_password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_by` int UNSIGNED DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `quizzes`
--

INSERT INTO `quizzes` (`id`, `course_id`, `class_program_id`, `school_id`, `title`, `description`, `quiz_type`, `component_id`, `total_points`, `time_limit_minutes`, `max_attempts`, `shuffle_questions`, `show_results`, `available_from`, `available_until`, `is_published`, `passing_score`, `quiz_password`, `created_by`, `created_at`) VALUES
(10, 37, NULL, 17, 'Quiz 1', 'Assessment Instruction Here....', 'quiz', 14, 10.00, 2, 1, 1, 1, NULL, NULL, 1, NULL, NULL, 138, '2026-06-03 02:29:31'),
(11, 37, NULL, 17, 'Quiz 1 - computer history', '', 'quiz', 15, 10.00, 4, 1, 1, 1, NULL, NULL, 1, NULL, NULL, 138, '2026-06-03 06:34:32'),
(15, 55, NULL, 43, 'summative test week 2', '', 'quiz', 21, 0.00, NULL, 1, 0, 1, NULL, NULL, 0, NULL, NULL, 328, '2026-06-03 23:18:19'),
(16, 58, NULL, 17, 'Sample Quiz', '', 'quiz', 22, 0.00, 5, 1, 1, 1, NULL, NULL, 1, NULL, NULL, 138, '2026-06-05 13:12:37');

-- --------------------------------------------------------

--
-- Table structure for table `quiz_attempts`
--

CREATE TABLE `quiz_attempts` (
  `id` int UNSIGNED NOT NULL,
  `quiz_id` int UNSIGNED NOT NULL,
  `student_id` int UNSIGNED NOT NULL,
  `attempt_number` tinyint NOT NULL DEFAULT '1',
  `score` decimal(6,2) DEFAULT NULL,
  `total_points` decimal(6,2) DEFAULT NULL,
  `percentage` decimal(5,2) DEFAULT NULL,
  `status` enum('in_progress','submitted','graded') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'in_progress',
  `started_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `submitted_at` datetime DEFAULT NULL,
  `graded_at` datetime DEFAULT NULL,
  `graded_by` int UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `quiz_attempts`
--

INSERT INTO `quiz_attempts` (`id`, `quiz_id`, `student_id`, `attempt_number`, `score`, `total_points`, `percentage`, `status`, `started_at`, `submitted_at`, `graded_at`, `graded_by`) VALUES
(11, 10, 139, 1, 10.00, 10.00, 100.00, 'graded', '2026-06-03 11:29:15', '2026-06-03 11:30:10', '2026-06-03 11:30:10', NULL),
(12, 11, 139, 1, 4.00, 10.00, 40.00, 'graded', '2026-06-03 14:37:27', '2026-06-03 14:38:17', '2026-06-03 14:38:17', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `quiz_attempt_answers`
--

CREATE TABLE `quiz_attempt_answers` (
  `id` int UNSIGNED NOT NULL,
  `attempt_id` int UNSIGNED NOT NULL,
  `question_id` int UNSIGNED NOT NULL,
  `answer_text` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `choice_id` int UNSIGNED DEFAULT NULL,
  `is_correct` tinyint(1) DEFAULT NULL,
  `score` decimal(5,2) DEFAULT NULL,
  `feedback` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `quiz_attempt_answers`
--

INSERT INTO `quiz_attempt_answers` (`id`, `attempt_id`, `question_id`, `answer_text`, `choice_id`, `is_correct`, `score`, `feedback`) VALUES
(79, 11, 112, NULL, 438, 1, 1.00, NULL),
(80, 11, 113, NULL, 442, 1, 1.00, NULL),
(81, 11, 114, NULL, 446, 1, 1.00, NULL),
(82, 11, 115, NULL, 450, 1, 1.00, NULL),
(83, 11, 116, NULL, 454, 1, 1.00, NULL),
(84, 11, 117, NULL, 458, 1, 1.00, NULL),
(85, 11, 118, NULL, 462, 1, 1.00, NULL),
(86, 11, 119, NULL, 466, 1, 1.00, NULL),
(87, 11, 120, NULL, 470, 1, 1.00, NULL),
(88, 11, 121, NULL, 474, 1, 1.00, NULL),
(89, 12, 122, NULL, 480, 0, 0.00, NULL),
(90, 12, 123, NULL, 485, 0, 0.00, NULL),
(91, 12, 124, NULL, 486, 1, 1.00, NULL),
(92, 12, 125, NULL, 490, 1, 1.00, NULL),
(93, 12, 126, NULL, 494, 1, 1.00, NULL),
(94, 12, 127, NULL, 500, 0, 0.00, NULL),
(95, 12, 128, NULL, 504, 0, 0.00, NULL),
(96, 12, 129, NULL, 509, 0, 0.00, NULL),
(97, 12, 130, NULL, 510, 1, 1.00, NULL),
(98, 12, 131, NULL, 515, 0, 0.00, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `quiz_choices`
--

CREATE TABLE `quiz_choices` (
  `id` int UNSIGNED NOT NULL,
  `question_id` int UNSIGNED NOT NULL,
  `choice_text` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_correct` tinyint(1) NOT NULL DEFAULT '0',
  `order_num` tinyint DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `quiz_choices`
--

INSERT INTO `quiz_choices` (`id`, `question_id`, `choice_text`, `is_correct`, `order_num`) VALUES
(438, 112, 'Information Technology', 1, 1),
(439, 112, 'Internet Tools', 0, 2),
(440, 112, 'Integrated Technology', 0, 3),
(441, 112, 'Input Transmission', 0, 4),
(442, 113, 'Keyboard', 1, 1),
(443, 113, 'Microsoft Word', 0, 2),
(444, 113, 'Google Chrome', 0, 3),
(445, 113, 'Windows 11', 0, 4),
(446, 114, 'Monitor', 1, 1),
(447, 114, 'Mouse', 0, 2),
(448, 114, 'Printer', 0, 3),
(449, 114, 'Scanner', 0, 4),
(450, 115, 'Operating System', 1, 1),
(451, 115, 'Spreadsheet', 0, 2),
(452, 115, 'Presentation software', 0, 3),
(453, 115, 'Photo editor', 0, 4),
(454, 116, 'To manage computer hardware and software resources', 1, 1),
(455, 116, 'To create printed documents only', 0, 2),
(456, 116, 'To design websites only', 0, 3),
(457, 116, 'To protect the computer from dust', 0, 4),
(458, 117, 'Microsoft Excel', 1, 1),
(459, 117, 'RAM', 0, 2),
(460, 117, 'Motherboard', 0, 3),
(461, 117, 'CPU', 0, 4),
(462, 118, 'Central Processing Unit', 1, 1),
(463, 118, 'Computer Processing Utility', 0, 2),
(464, 118, 'Central Program Unit', 0, 3),
(465, 118, 'Control Power Unit', 0, 4),
(466, 119, 'USB flash drive', 1, 1),
(467, 119, 'Monitor', 0, 2),
(468, 119, 'Keyboard', 0, 3),
(469, 119, 'Speaker', 0, 4),
(470, 120, 'A global network of connected computers and devices', 1, 1),
(471, 120, 'A type of computer virus', 0, 2),
(472, 120, 'A hardware device inside the CPU', 0, 3),
(473, 120, 'A program used only for typing documents', 0, 4),
(474, 121, 'Using strong and unique passwords', 1, 1),
(475, 121, 'Sharing passwords with friends', 0, 2),
(476, 121, 'Clicking unknown links immediately', 0, 3),
(477, 121, 'Ignoring software updates', 0, 4),
(478, 122, 'Information Technology', 1, 1),
(479, 122, 'Internet Tools', 0, 2),
(480, 122, 'Integrated Technology', 0, 3),
(481, 122, 'Input Transmission', 0, 4),
(482, 123, 'Keyboard', 1, 1),
(483, 123, 'Microsoft Word', 0, 2),
(484, 123, 'Google Chrome', 0, 3),
(485, 123, 'Windows 11', 0, 4),
(486, 124, 'Monitor', 1, 1),
(487, 124, 'Mouse', 0, 2),
(488, 124, 'Printer', 0, 3),
(489, 124, 'Scanner', 0, 4),
(490, 125, 'Operating System', 1, 1),
(491, 125, 'Spreadsheet', 0, 2),
(492, 125, 'Presentation software', 0, 3),
(493, 125, 'Photo editor', 0, 4),
(494, 126, 'To manage computer hardware and software resources', 1, 1),
(495, 126, 'To create printed documents only', 0, 2),
(496, 126, 'To design websites only', 0, 3),
(497, 126, 'To protect the computer from dust', 0, 4),
(498, 127, 'Microsoft Excel', 1, 1),
(499, 127, 'RAM', 0, 2),
(500, 127, 'Motherboard', 0, 3),
(501, 127, 'CPU', 0, 4),
(502, 128, 'Central Processing Unit', 1, 1),
(503, 128, 'Computer Processing Utility', 0, 2),
(504, 128, 'Central Program Unit', 0, 3),
(505, 128, 'Control Power Unit', 0, 4),
(506, 129, 'USB flash drive', 1, 1),
(507, 129, 'Monitor', 0, 2),
(508, 129, 'Keyboard', 0, 3),
(509, 129, 'Speaker', 0, 4),
(510, 130, 'A global network of connected computers and devices', 1, 1),
(511, 130, 'A type of computer virus', 0, 2),
(512, 130, 'A hardware device inside the CPU', 0, 3),
(513, 130, 'A program used only for typing documents', 0, 4),
(514, 131, 'Using strong and unique passwords', 1, 1),
(515, 131, 'Sharing passwords with friends', 0, 2),
(516, 131, 'Clicking unknown links immediately', 0, 3),
(517, 131, 'Ignoring software updates', 0, 4),
(518, 132, 'Ang pagkaalam sa mga pangalan at petsa ng mga nakaraang kaganapan lamang.', 0, 1),
(519, 132, 'Ang malalim na pag-unawa sa ugnayan ng nakaraan, kasalukuyan, at hinaharap.', 1, 2),
(520, 132, 'Ang paggaya sa mga kultura ng ibang bansa upang umunlad.', 0, 3),
(521, 132, 'Ang simpleng pagbasa ng mga aklat tungkol sa mga sinaunang tao.', 0, 4),
(522, 133, 'Dahil pinatutunayan nito na walang pagkakamali ang ating mga ninuno.', 0, 1),
(523, 133, 'Dahil nagbibigay ito ng pagkakataon na makapagtatag ng kolonya sa ibang bansa.', 0, 2),
(524, 133, 'Dahil pinagbubuklod nito ang mga mamamayan sa pamamagitan ng ibinahaging karanasan at kultura.', 1, 3),
(525, 133, 'Dahil tinutulungan nito ang mga tao na makalimutan ang mga suliranin sa kasalukuyan.', 0, 4),
(526, 134, 'Pagtangkilik sa mga lokal na museo at makasaysayang pook.', 0, 1),
(527, 134, 'Pagwawalang-bahala sa mga aral ng Martial Law at pag-uulit sa mga pagkakamali nito.', 1, 2),
(528, 134, 'Pagsasagawa ng pananaliksik tungkol sa pinagmulan ng sariling komunidad.', 0, 3),
(529, 134, 'Paglahok sa mga aktibidad na gumugunita sa Araw ng Kalayaan.', 0, 4),
(530, 135, 'Andres Bonifacio', 0, 1),
(531, 135, 'Apolinario Mabini', 0, 2),
(532, 135, 'Jose Rizal', 1, 3),
(533, 135, 'Emilio Jacinto', 0, 4),
(534, 136, 'Pantayong Pananaw', 1, 1),
(535, 136, 'Pangkaming Pananaw', 0, 2),
(536, 136, 'Pansilang Pananaw', 0, 3),
(537, 136, 'Eurocentric na Pananaw', 0, 4),
(538, 137, 'Ito ay nagbibigay ng mga mahiwagang solusyon sa mga krisis.', 0, 1),
(539, 137, 'Nagbibigay ito ng konteksto at batayan upang mas maunawaan ang ugat ng mga kasalukuyang problema.', 1, 2),
(540, 137, 'Pinipilit nito ang mga pinuno na gayahin ang mga lumang batas ng Espanya.', 0, 3),
(541, 137, 'Ito ay nagpapakita na walang pagbabagong maaaring mangyari.', 0, 4),
(542, 138, 'Isang textbook sa kasaysayan na isinulat noong 2010.', 0, 1),
(543, 138, 'Ang orihinal na talaarawan o diary ng isang sundalo noong Digmaan.', 1, 2),
(544, 138, 'Isang pelikula tungkol kay Heneral Luna na ipinalabas sa sinehan.', 0, 3),
(545, 138, 'Isang artikulo sa Wikipedia tungkol sa Rebolusyong Pilipino.', 0, 4),
(546, 139, 'Ang Kasaysayan ay may pakahulugan (saysay), habang ang History ay nakatuon sa pagsisiyasat ng nakaraan (inquiry).', 1, 1),
(547, 139, 'Ang History ay para sa mga mayayaman lamang at ang Kasaysayan ay para sa mahihirap.', 0, 2),
(548, 139, 'Walang pagkakaiba ang dalawang salita sa kahit anong aspekto.', 0, 3),
(549, 139, 'Ang Kasaysayan ay kathang-isip lamang samantalang ang History ay katotohanan.', 0, 4),
(550, 140, 'Mas mabilis silang makakamit ng modernisasyon at teknolohiya.', 0, 1),
(551, 140, 'Magiging mas madali ang pag-unlad ng kanilang ekonomiya.', 0, 2),
(552, 140, 'Madali silang madadaya at mamanipula ng mga huwad na impormasyon at rebisyunismo.', 1, 3),
(553, 140, 'Magkakaroon sila ng mas malakas na hukbong sandatahan.', 0, 4),
(554, 141, 'Sa pamamagitan ng pagbili ng mga imported na kagamitan.', 0, 1),
(555, 141, 'Sa pamamagitan ng kritikal na pagsusuri sa mga balita at paggalang sa pambansang simbolo.', 1, 2),
(556, 141, 'Sa pamamagitan ng paggugol ng oras sa paglalaro ng video games tungkol sa digmaan.', 0, 3),
(557, 141, 'Sa pamamagitan ng pag-alis sa bansa upang doon magtrabaho.', 0, 4),
(558, 142, 'Historical Revisionism (na nakabatay sa bagong ebidensya)', 0, 1),
(559, 142, 'Historical Distortion o Negationism', 1, 2),
(560, 142, 'Historical Historiography', 0, 3),
(561, 142, 'Historical Methodology', 0, 4),
(562, 143, 'Ito ang nagpapatunay na walang kuwenta ang mga nakasulat na dokumento.', 0, 1),
(563, 143, 'Nagbibigay ito ng boses sa mga ordinaryong tao at pangkat na hindi madalas naisusulat sa mga opisyal na aklat.', 1, 2),
(564, 143, 'Ito ay ginagamit upang palitan ang lahat ng opisyal na batas ng bansa.', 0, 3),
(565, 143, 'Ito ay uri ng tsismis na walang batayan at hindi dapat paniwalaan.', 0, 4),
(566, 144, 'Dahil ang mga desisyon at aral ng nakaraan ang nagsisilbing gabay sa pagbuo ng mas magandang kinabukasan.', 1, 1),
(567, 144, 'Dahil maaari nating gamitin ang nakaraan upang hulaan ang eksaktong mangyayari bukas.', 0, 2),
(568, 144, 'Dahil ang hinaharap ay paulit-ulit na kopya lamang ng nakaraan nang walang pagbabago.', 0, 3),
(569, 144, 'Dahil ang kasaysayan ay nagbibigay ng kapangyarihan na maglakbay sa panahon (time travel).', 0, 4),
(570, 145, 'Teodoro Agoncillo', 1, 1),
(571, 145, 'Gregorio Zaide', 0, 2),
(572, 145, 'William Henry Scott', 0, 3),
(573, 145, 'Ferdinand Magellan', 0, 4),
(574, 146, 'Agad niya itong paniniwalaan at ibabahagi sa social media.', 0, 1),
(575, 146, 'Iinit ang kanyang ulo at makikipag-away sa mga nag-post nito.', 0, 2),
(576, 146, 'Sasaliksikin niya ang pinagmulan, susuriin ang ebidensya, at titingnan ang kredibilidad ng sanggunian.', 1, 3),
(577, 146, 'Hahayaan na lamang niya ito dahil wala naman itong epekto sa lipunan.', 0, 4);

-- --------------------------------------------------------

--
-- Table structure for table `quiz_questions`
--

CREATE TABLE `quiz_questions` (
  `id` int UNSIGNED NOT NULL,
  `quiz_id` int UNSIGNED NOT NULL,
  `question_type` enum('multiple_choice','identification','essay','true_false') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `question_text` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `question_image` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `points` decimal(5,2) NOT NULL DEFAULT '1.00',
  `order_num` int DEFAULT '1',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `quiz_questions`
--

INSERT INTO `quiz_questions` (`id`, `quiz_id`, `question_type`, `question_text`, `question_image`, `points`, `order_num`, `created_at`) VALUES
(2, 2, 'multiple_choice', 'Visual Basic (VB) is known for being ________-friendly and is widely used for enterprise applications.', NULL, 1.00, 1, '2026-04-25 15:54:28'),
(3, 2, 'multiple_choice', 'The software environment used to write and manage code is called an ________.', NULL, 1.00, 2, '2026-04-25 15:54:28'),
(4, 2, 'multiple_choice', 'The free version of Visual Studio used by individual developers is called Visual Studio 2019 ________ Edition.', NULL, 1.00, 3, '2026-04-25 15:54:28'),
(5, 2, 'multiple_choice', 'During installation, you must select the workload called \".NET ________ Development.\"', NULL, 1.00, 4, '2026-04-25 15:54:28'),
(6, 2, 'multiple_choice', 'The central area where you design your application\'s interface is called the ________.', NULL, 1.00, 5, '2026-04-25 15:54:28'),
(7, 2, 'multiple_choice', 'The panel that contains UI controls like buttons, labels, and textboxes is called the ________.', NULL, 1.00, 6, '2026-04-25 15:54:28'),
(8, 2, 'multiple_choice', 'The window that displays the files and forms in your project is called the ________ Explorer.', NULL, 1.00, 7, '2026-04-25 15:54:28'),
(9, 2, 'multiple_choice', 'To display a popup message in VB.NET, you can use the command ________.Show().', NULL, 1.00, 8, '2026-04-25 15:54:28'),
(10, 2, 'multiple_choice', 'Pressing the ________ key runs or starts your application.', NULL, 1.00, 9, '2026-04-25 15:54:28'),
(11, 2, 'multiple_choice', 'In Visual Basic, the keyword used to declare a variable is ________.', NULL, 1.00, 10, '2026-04-25 15:54:28'),
(12, 2, 'multiple_choice', 'The data type used for storing text or characters is called ________.', NULL, 1.00, 11, '2026-04-25 15:54:28'),
(13, 2, 'multiple_choice', 'The data type used for whole numbers is ________.', NULL, 1.00, 12, '2026-04-25 15:54:28'),
(14, 2, 'multiple_choice', 'In VB.NET, the symbol used to join or combine strings is the ________ symbol.', NULL, 1.00, 13, '2026-04-25 15:54:28'),
(15, 2, 'multiple_choice', 'To get the value entered in a TextBox named txtUsername, you use txtUsername.________.', NULL, 1.00, 14, '2026-04-25 15:54:28'),
(16, 2, 'multiple_choice', 'In control naming conventions, the prefix used for a Button control is ________.', NULL, 1.00, 15, '2026-04-25 15:54:28'),
(17, 2, 'multiple_choice', 'What is the main purpose of a login form in a VB.NET system?', NULL, 1.00, 16, '2026-04-25 15:54:28'),
(18, 2, 'multiple_choice', 'Which SQL statement is commonly used to verify a username and password from a users table?', NULL, 1.00, 17, '2026-04-25 15:54:28'),
(19, 2, 'multiple_choice', 'In CRUD, the letter C stands for ________.', NULL, 1.00, 18, '2026-04-25 15:54:28'),
(20, 2, 'multiple_choice', 'In CRUD, the letter R stands for ________.', NULL, 1.00, 19, '2026-04-25 15:54:28'),
(21, 2, 'multiple_choice', 'In CRUD, the letter U stands for ________.', NULL, 1.00, 20, '2026-04-25 15:54:28'),
(22, 2, 'multiple_choice', 'In CRUD, the letter D stands for ________.', NULL, 1.00, 21, '2026-04-25 15:54:28'),
(23, 2, 'multiple_choice', 'Which SQL command is used to add a new record to a table?', NULL, 1.00, 22, '2026-04-25 15:54:28'),
(24, 2, 'multiple_choice', 'Which SQL command is used to display records from a table?', NULL, 1.00, 23, '2026-04-25 15:54:28'),
(25, 2, 'multiple_choice', 'Which SQL command is used to modify an existing record?', NULL, 1.00, 24, '2026-04-25 15:54:28'),
(26, 2, 'multiple_choice', 'Which SQL command is used to remove records from a table?', NULL, 1.00, 25, '2026-04-25 15:54:28'),
(27, 2, 'multiple_choice', 'Which clause is commonly used to specify which record should be updated or deleted?', NULL, 1.00, 26, '2026-04-25 15:54:28'),
(28, 2, 'multiple_choice', 'Which control is commonly used in VB.NET to display many database records in rows and columns?', NULL, 1.00, 27, '2026-04-25 15:54:28'),
(29, 2, 'multiple_choice', 'In a basic student form, clicking Save usually performs which CRUD action?', NULL, 1.00, 28, '2026-04-25 15:54:28'),
(30, 2, 'multiple_choice', 'A Search feature in a CRUD system usually belongs to which action?', NULL, 1.00, 29, '2026-04-25 15:54:28'),
(31, 2, 'multiple_choice', 'When a user edits the selected record and clicks Update, which CRUD action is performed?', NULL, 1.00, 30, '2026-04-25 15:54:28'),
(32, 2, 'multiple_choice', 'When the user removes a selected record from the table, which CRUD action is performed?', NULL, 1.00, 31, '2026-04-25 15:54:28'),
(33, 2, 'multiple_choice', 'What is the safest way to pass username and password values to an SQL query in VB.NET?', NULL, 1.00, 32, '2026-04-25 15:54:28'),
(34, 2, 'multiple_choice', 'After a successful login, what usually happens next in a VB.NET system?', NULL, 1.00, 33, '2026-04-25 15:54:28'),
(35, 2, 'multiple_choice', 'If the entered username or password is incorrect, the system should usually ________.', NULL, 1.00, 34, '2026-04-25 15:54:28'),
(36, 2, 'multiple_choice', 'Which TextBox property is commonly used to hide password characters in a login form?', NULL, 1.00, 35, '2026-04-25 15:54:28'),
(37, 2, 'multiple_choice', 'Which class is commonly used in VB.NET with MySQL to open a database connection?', NULL, 1.00, 36, '2026-04-25 15:54:28'),
(38, 2, 'multiple_choice', 'Which object is commonly used to execute an SQL command in VB.NET?', NULL, 1.00, 37, '2026-04-25 15:54:28'),
(39, 2, 'multiple_choice', 'Which object is commonly used to fill a DataTable or DataSet from a database query?', NULL, 1.00, 38, '2026-04-25 15:54:28'),
(40, 2, 'multiple_choice', 'The comparison operator used to check if two values are equal in VB.NET is ________.', NULL, 1.00, 39, '2026-04-25 15:54:28'),
(41, 2, 'multiple_choice', 'The operator used to mean \"not equal to\" in VB.NET is ________.', NULL, 1.00, 40, '2026-04-25 15:54:28'),
(42, 2, 'multiple_choice', 'The keyword used to start a decision statement in Visual Basic is ________.', NULL, 1.00, 41, '2026-04-25 15:54:28'),
(43, 2, 'multiple_choice', 'The basic decision structure in VB.NET is written as If...Then...________...End If.', NULL, 1.00, 42, '2026-04-25 15:54:28'),
(44, 2, 'multiple_choice', 'If a program needs to check multiple conditions, it uses the ________ keyword.', NULL, 1.00, 43, '2026-04-25 15:54:28'),
(45, 2, 'multiple_choice', 'The code used to clear the content of a TextBox is ________().', NULL, 1.00, 44, '2026-04-25 15:54:28'),
(46, 2, 'multiple_choice', 'If a TextBox is empty and the program tries to convert it into a number, the program may ________.', NULL, 1.00, 45, '2026-04-25 15:54:28'),
(47, 2, 'multiple_choice', 'To convert text into an Integer, which function can be used?', NULL, 1.00, 46, '2026-04-25 15:54:28'),
(48, 2, 'multiple_choice', 'To convert text into a Double, which function is used?', NULL, 1.00, 47, '2026-04-25 15:54:28'),
(49, 2, 'multiple_choice', 'The ________ Case structure is used to compare one variable against multiple possible values.', NULL, 1.00, 48, '2026-04-25 15:54:28'),
(50, 2, 'multiple_choice', 'The control used to allow users to select only numeric values is called ________UpDown.', NULL, 1.00, 49, '2026-04-25 15:54:28'),
(51, 2, 'multiple_choice', 'The code ListBox1.Items.Add() is used to ________ an item to a ListBox.', NULL, 1.00, 50, '2026-04-25 15:54:28'),
(52, 4, 'multiple_choice', 'Sino ang kinikilalang Pambansang Bayani ng Pilipinas?', NULL, 1.00, 1, '2026-05-30 12:51:02'),
(53, 4, 'multiple_choice', 'Ano ang tatlong pangunahing pangkat ng mga pulo sa Pilipinas?', NULL, 1.00, 2, '2026-05-30 12:51:02'),
(54, 4, 'multiple_choice', 'Sino ang unang Pangulo ng Republika ng Pilipinas?', NULL, 1.00, 3, '2026-05-30 12:51:02'),
(55, 4, 'multiple_choice', 'Ano ang kabisera ng Pilipinas?', NULL, 1.00, 4, '2026-05-30 12:51:02'),
(56, 4, 'multiple_choice', 'Alin ang pangunahing tungkulin ng pamahalaan?', NULL, 1.00, 5, '2026-05-30 12:51:02'),
(57, 4, 'multiple_choice', 'Ano ang tawag sa mga bagay na nagmumula sa kalikasan at napapakinabangan ng tao?', NULL, 1.00, 6, '2026-05-30 12:51:02'),
(58, 4, 'multiple_choice', 'Alin sa sumusunod ang halimbawa ng karapatan ng isang mamamayan?', NULL, 1.00, 7, '2026-05-30 12:51:02'),
(59, 4, 'multiple_choice', 'Anong bansa ang sumakop sa Pilipinas sa loob ng mahigit 300 taon?', NULL, 1.00, 8, '2026-05-30 12:51:02'),
(60, 4, 'multiple_choice', 'Ano ang tawag sa sistema ng produksyon, distribusyon, at pagkonsumo ng produkto at serbisyo?', NULL, 1.00, 9, '2026-05-30 12:51:02'),
(61, 4, 'multiple_choice', 'Ano ang kahulugan ng araw sa watawat ng Pilipinas?', NULL, 1.00, 10, '2026-05-30 12:51:02'),
(62, 5, 'multiple_choice', 'Sino ang kinikilalang Pambansang Bayani ng Pilipinas?', NULL, 1.00, 1, '2026-05-30 17:18:07'),
(63, 5, 'multiple_choice', 'Ano ang tatlong pangunahing pangkat ng mga pulo sa Pilipinas?', NULL, 1.00, 2, '2026-05-30 17:18:07'),
(64, 5, 'multiple_choice', 'Sino ang unang Pangulo ng Republika ng Pilipinas?', NULL, 1.00, 3, '2026-05-30 17:18:07'),
(65, 5, 'multiple_choice', 'Ano ang kabisera ng Pilipinas?', NULL, 1.00, 4, '2026-05-30 17:18:07'),
(66, 5, 'multiple_choice', 'Alin ang pangunahing tungkulin ng pamahalaan?', NULL, 1.00, 5, '2026-05-30 17:18:07'),
(67, 5, 'multiple_choice', 'Ano ang tawag sa mga bagay na nagmumula sa kalikasan at napapakinabangan ng tao?', NULL, 1.00, 6, '2026-05-30 17:18:07'),
(68, 5, 'multiple_choice', 'Alin sa sumusunod ang halimbawa ng karapatan ng isang mamamayan?', NULL, 1.00, 7, '2026-05-30 17:18:07'),
(69, 5, 'multiple_choice', 'Anong bansa ang sumakop sa Pilipinas sa loob ng mahigit 300 taon?', NULL, 1.00, 8, '2026-05-30 17:18:07'),
(70, 5, 'multiple_choice', 'Ano ang tawag sa sistema ng produksyon, distribusyon, at pagkonsumo ng produkto at serbisyo?', NULL, 1.00, 9, '2026-05-30 17:18:07'),
(71, 5, 'multiple_choice', 'Ano ang kahulugan ng araw sa watawat ng Pilipinas?', NULL, 1.00, 10, '2026-05-30 17:18:07'),
(72, 6, 'multiple_choice', 'Sino ang kinikilalang Pambansang Bayani ng Pilipinas?', NULL, 1.00, 1, '2026-05-30 19:33:24'),
(73, 6, 'multiple_choice', 'Ano ang tatlong pangunahing pangkat ng mga pulo sa Pilipinas?', NULL, 1.00, 2, '2026-05-30 19:33:24'),
(74, 6, 'multiple_choice', 'Sino ang unang Pangulo ng Republika ng Pilipinas?', NULL, 1.00, 3, '2026-05-30 19:33:24'),
(75, 6, 'multiple_choice', 'Ano ang kabisera ng Pilipinas?', NULL, 1.00, 4, '2026-05-30 19:33:24'),
(76, 6, 'multiple_choice', 'Alin ang pangunahing tungkulin ng pamahalaan?', NULL, 1.00, 5, '2026-05-30 19:33:24'),
(77, 6, 'multiple_choice', 'Ano ang tawag sa mga bagay na nagmumula sa kalikasan at napapakinabangan ng tao?', NULL, 1.00, 6, '2026-05-30 19:33:24'),
(78, 6, 'multiple_choice', 'Alin sa sumusunod ang halimbawa ng karapatan ng isang mamamayan?', NULL, 1.00, 7, '2026-05-30 19:33:24'),
(79, 6, 'multiple_choice', 'Anong bansa ang sumakop sa Pilipinas sa loob ng mahigit 300 taon?', NULL, 1.00, 8, '2026-05-30 19:33:24'),
(80, 6, 'multiple_choice', 'Ano ang tawag sa sistema ng produksyon, distribusyon, at pagkonsumo ng produkto at serbisyo?', NULL, 1.00, 9, '2026-05-30 19:33:24'),
(81, 6, 'multiple_choice', 'Ano ang kahulugan ng araw sa watawat ng Pilipinas?', NULL, 1.00, 10, '2026-05-30 19:33:24'),
(82, 7, 'multiple_choice', 'Sino ang kinikilalang Pambansang Bayani ng Pilipinas?', NULL, 1.00, 1, '2026-05-30 19:46:47'),
(83, 7, 'multiple_choice', 'Ano ang tatlong pangunahing pangkat ng mga pulo sa Pilipinas?', NULL, 1.00, 2, '2026-05-30 19:46:47'),
(84, 7, 'multiple_choice', 'Sino ang unang Pangulo ng Republika ng Pilipinas?', NULL, 1.00, 3, '2026-05-30 19:46:47'),
(85, 7, 'multiple_choice', 'Ano ang kabisera ng Pilipinas?', NULL, 1.00, 4, '2026-05-30 19:46:47'),
(86, 7, 'multiple_choice', 'Alin ang pangunahing tungkulin ng pamahalaan?', NULL, 1.00, 5, '2026-05-30 19:46:47'),
(87, 7, 'multiple_choice', 'Ano ang tawag sa mga bagay na nagmumula sa kalikasan at napapakinabangan ng tao?', NULL, 1.00, 6, '2026-05-30 19:46:47'),
(88, 7, 'multiple_choice', 'Alin sa sumusunod ang halimbawa ng karapatan ng isang mamamayan?', NULL, 1.00, 7, '2026-05-30 19:46:47'),
(89, 7, 'multiple_choice', 'Anong bansa ang sumakop sa Pilipinas sa loob ng mahigit 300 taon?', NULL, 1.00, 8, '2026-05-30 19:46:47'),
(90, 7, 'multiple_choice', 'Ano ang tawag sa sistema ng produksyon, distribusyon, at pagkonsumo ng produkto at serbisyo?', NULL, 1.00, 9, '2026-05-30 19:46:47'),
(91, 7, 'multiple_choice', 'Ano ang kahulugan ng araw sa watawat ng Pilipinas?', NULL, 1.00, 10, '2026-05-30 19:46:47'),
(92, 8, 'multiple_choice', 'Sino ang kinikilalang Pambansang Bayani ng Pilipinas?', NULL, 1.00, 1, '2026-06-02 21:54:12'),
(93, 8, 'multiple_choice', 'Ano ang tatlong pangunahing pangkat ng mga pulo sa Pilipinas?', NULL, 1.00, 2, '2026-06-02 21:54:12'),
(94, 8, 'multiple_choice', 'Sino ang unang Pangulo ng Republika ng Pilipinas?', NULL, 1.00, 3, '2026-06-02 21:54:12'),
(95, 8, 'multiple_choice', 'Ano ang kabisera ng Pilipinas?', NULL, 1.00, 4, '2026-06-02 21:54:12'),
(96, 8, 'multiple_choice', 'Alin ang pangunahing tungkulin ng pamahalaan?', NULL, 1.00, 5, '2026-06-02 21:54:12'),
(97, 8, 'multiple_choice', 'Ano ang tawag sa mga bagay na nagmumula sa kalikasan at napapakinabangan ng tao?', NULL, 1.00, 6, '2026-06-02 21:54:12'),
(98, 8, 'multiple_choice', 'Alin sa sumusunod ang halimbawa ng karapatan ng isang mamamayan?', NULL, 1.00, 7, '2026-06-02 21:54:12'),
(99, 8, 'multiple_choice', 'Anong bansa ang sumakop sa Pilipinas sa loob ng mahigit 300 taon?', NULL, 1.00, 8, '2026-06-02 21:54:12'),
(100, 8, 'multiple_choice', 'Ano ang tawag sa sistema ng produksyon, distribusyon, at pagkonsumo ng produkto at serbisyo?', NULL, 1.00, 9, '2026-06-02 21:54:12'),
(101, 8, 'multiple_choice', 'Ano ang kahulugan ng araw sa watawat ng Pilipinas?', NULL, 1.00, 10, '2026-06-02 21:54:12'),
(102, 9, 'multiple_choice', 'Sino ang kinikilalang Pambansang Bayani ng Pilipinas?', NULL, 1.00, 1, '2026-06-02 22:04:56'),
(103, 9, 'multiple_choice', 'Ano ang tatlong pangunahing pangkat ng mga pulo sa Pilipinas?', NULL, 1.00, 2, '2026-06-02 22:04:56'),
(104, 9, 'multiple_choice', 'Sino ang unang Pangulo ng Republika ng Pilipinas?', NULL, 1.00, 3, '2026-06-02 22:04:56'),
(105, 9, 'multiple_choice', 'Ano ang kabisera ng Pilipinas?', NULL, 1.00, 4, '2026-06-02 22:04:56'),
(106, 9, 'multiple_choice', 'Alin ang pangunahing tungkulin ng pamahalaan?', NULL, 1.00, 5, '2026-06-02 22:04:56'),
(107, 9, 'multiple_choice', 'Ano ang tawag sa mga bagay na nagmumula sa kalikasan at napapakinabangan ng tao?', NULL, 1.00, 6, '2026-06-02 22:04:56'),
(108, 9, 'multiple_choice', 'Alin sa sumusunod ang halimbawa ng karapatan ng isang mamamayan?', NULL, 1.00, 7, '2026-06-02 22:04:56'),
(109, 9, 'multiple_choice', 'Anong bansa ang sumakop sa Pilipinas sa loob ng mahigit 300 taon?', NULL, 1.00, 8, '2026-06-02 22:04:56'),
(110, 9, 'multiple_choice', 'Ano ang tawag sa sistema ng produksyon, distribusyon, at pagkonsumo ng produkto at serbisyo?', NULL, 1.00, 9, '2026-06-02 22:04:56'),
(111, 9, 'multiple_choice', 'Ano ang kahulugan ng araw sa watawat ng Pilipinas?', NULL, 1.00, 10, '2026-06-02 22:04:56'),
(112, 10, 'multiple_choice', 'What does IT stand for?', NULL, 1.00, 1, '2026-06-03 02:34:24'),
(113, 10, 'multiple_choice', 'Which of the following is an example of hardware?', NULL, 1.00, 2, '2026-06-03 02:34:24'),
(114, 10, 'multiple_choice', 'Which device is mainly used to display visual output from a computer?', NULL, 1.00, 3, '2026-06-03 02:34:24'),
(115, 10, 'multiple_choice', 'Which of the following is system software?', NULL, 1.00, 4, '2026-06-03 02:34:24'),
(116, 10, 'multiple_choice', 'What is the main function of an operating system?', NULL, 1.00, 5, '2026-06-03 02:34:24'),
(117, 10, 'multiple_choice', 'Which of the following is an example of application software?', NULL, 1.00, 6, '2026-06-03 02:34:24'),
(118, 10, 'multiple_choice', 'What does CPU stand for?', NULL, 1.00, 7, '2026-06-03 02:34:24'),
(119, 10, 'multiple_choice', 'Which storage device is commonly used for portable file storage?', NULL, 1.00, 8, '2026-06-03 02:34:24'),
(120, 10, 'multiple_choice', 'What is the Internet?', NULL, 1.00, 9, '2026-06-03 02:34:24'),
(121, 10, 'multiple_choice', 'Which of the following is a good cybersecurity practice?', NULL, 1.00, 10, '2026-06-03 02:34:24'),
(122, 11, 'multiple_choice', 'What does IT stand for?', NULL, 1.00, 1, '2026-06-03 06:35:23'),
(123, 11, 'multiple_choice', 'Which of the following is an example of hardware?', NULL, 1.00, 2, '2026-06-03 06:35:23'),
(124, 11, 'multiple_choice', 'Which device is mainly used to display visual output from a computer?', NULL, 1.00, 3, '2026-06-03 06:35:23'),
(125, 11, 'multiple_choice', 'Which of the following is system software?', NULL, 1.00, 4, '2026-06-03 06:35:23'),
(126, 11, 'multiple_choice', 'What is the main function of an operating system?', NULL, 1.00, 5, '2026-06-03 06:35:23'),
(127, 11, 'multiple_choice', 'Which of the following is an example of application software?', NULL, 1.00, 6, '2026-06-03 06:35:23'),
(128, 11, 'multiple_choice', 'What does CPU stand for?', NULL, 1.00, 7, '2026-06-03 06:35:23'),
(129, 11, 'multiple_choice', 'Which storage device is commonly used for portable file storage?', NULL, 1.00, 8, '2026-06-03 06:35:23'),
(130, 11, 'multiple_choice', 'What is the Internet?', NULL, 1.00, 9, '2026-06-03 06:35:23'),
(131, 11, 'multiple_choice', 'Which of the following is a good cybersecurity practice?', NULL, 1.00, 10, '2026-06-03 06:35:23'),
(132, 14, 'multiple_choice', 'Ano ang pangunahing kahulugan ng \"Kamalayang Kasaysayan\"?', NULL, 1.00, 1, '2026-06-03 22:42:29'),
(133, 14, 'multiple_choice', 'Bakit mahalaga ang kamalayang kasaysayan sa pagbuo ng pambansang pagkakakilanlan?', NULL, 1.00, 2, '2026-06-03 22:42:29'),
(134, 14, 'multiple_choice', 'Alin sa mga sumusunod ang nagpapakita ng kawalan ng kamalayang kasaysayan?', NULL, 1.00, 3, '2026-06-03 22:42:29'),
(135, 14, 'multiple_choice', 'Sino sa mga sumusunod na bayani ang tanyag sa katagang, \"Ang hindi lumingon sa pinanggalingan ay hindi makararating sa paroroonan\"?', NULL, 1.00, 4, '2026-06-03 22:42:29'),
(136, 14, 'multiple_choice', 'Ano ang tawag sa perspektiba sa kasaysayan na gumagamit ng sariling mga kategorya, konsepto, at pagpapahalaga ng mga Pilipino sa pagsusuri?', NULL, 1.00, 5, '2026-06-03 22:42:29'),
(137, 14, 'multiple_choice', 'Paano nakatutulong ang kamalayang kasaysayan sa pagharap sa mga kontemporaryong isyu?', NULL, 1.00, 6, '2026-06-03 22:42:29'),
(138, 14, 'multiple_choice', 'Alin sa mga sumusunod ang itinuturing na \"Primaryang Sanggunian\" sa pag-aaral ng kasaysayan?', NULL, 1.00, 7, '2026-06-03 22:42:29'),
(139, 14, 'multiple_choice', 'Ano ang pangunahing pagkakaiba ng \"Kasaysayan\" sa \"History\" ayon sa kontekstong Pilipino?', NULL, 1.00, 8, '2026-06-03 22:42:29'),
(140, 14, 'multiple_choice', 'Ano ang kahihinatnan ng isang lipunan na may mababaw o walang kamalayang kasaysayan?', NULL, 1.00, 9, '2026-06-03 22:42:29'),
(141, 14, 'multiple_choice', 'Paano maisasabuhay ng isang mag-aaral ang kamalayang kasaysayan sa pang-araw-araw na buhay?', NULL, 1.00, 10, '2026-06-03 22:42:29'),
(142, 14, 'multiple_choice', 'Aling konsepto ang tumutukoy sa sadyang pagbaluktot o pagbabago sa mga napatunayang katotohanan sa kasaysayan para sa personal o politikal na interes?', NULL, 1.00, 11, '2026-06-03 22:42:29'),
(143, 14, 'multiple_choice', 'Ano ang gampanin ng \"Oral History\" o Sinasalitang Kasaysayan sa pagbuo ng kamalayan ng bayan?', NULL, 1.00, 12, '2026-06-03 22:42:29'),
(144, 14, 'multiple_choice', 'Bakit sinasabing ang kasaysayan ay hindi lamang tungkol sa nakaraan kundi tungkol din sa hinaharap?', NULL, 1.00, 13, '2026-06-03 22:42:29'),
(145, 14, 'multiple_choice', 'Sino ang itinuturing na \"Ama ng Historiograpiyang Pilipino\" na nag-ambag nang malaki sa pagsulat ng kasaysayan mula sa pananaw ng mga Pilipino?', NULL, 1.00, 14, '2026-06-03 22:42:29'),
(146, 14, 'multiple_choice', 'Kapag ang isang tao ay may malalim na kamalayang kasaysayan, ano ang kanyang magiging reaksyon sa mga \"Fake News\" o pekeng balita?', NULL, 1.00, 15, '2026-06-03 22:42:29');

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `id` int UNSIGNED NOT NULL,
  `name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `name`, `slug`, `description`) VALUES
(1, 'Super Admin', 'super_admin', 'Division / University level administrator'),
(2, 'School Admin', 'school_admin', 'School-level administrator'),
(3, 'Registrar', 'registrar', 'Manages enrollment and records'),
(4, 'Teacher', 'teacher', 'Instructor / Faculty'),
(5, 'Student', 'student', 'Learner'),
(6, 'Parent', 'parent', 'Parent/Guardian (DepEd K-12)'),
(7, 'Course Creator', 'course_creator', 'Manages LMS content per subject/course');

-- --------------------------------------------------------

--
-- Table structure for table `rubrics`
--

CREATE TABLE `rubrics` (
  `id` int UNSIGNED NOT NULL,
  `subject_id` int UNSIGNED NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `total_points` decimal(6,2) DEFAULT '100.00',
  `created_by` int UNSIGNED DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `rubric_criteria`
--

CREATE TABLE `rubric_criteria` (
  `id` int UNSIGNED NOT NULL,
  `rubric_id` int UNSIGNED NOT NULL,
  `criterion` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `excellent_desc` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `proficient_desc` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `developing_desc` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `beginning_desc` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `max_score` decimal(5,2) NOT NULL DEFAULT '25.00',
  `order_num` tinyint DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `schools`
--

CREATE TABLE `schools` (
  `id` int UNSIGNED NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `school_id_number` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `type` enum('deped','ched','both') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'deped',
  `address` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `contact_number` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `district` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `logo` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `confirmation_token` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `admin_password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `confirmed_at` datetime DEFAULT NULL,
  `division` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `region` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `schools`
--

INSERT INTO `schools` (`id`, `name`, `school_id_number`, `type`, `address`, `contact_number`, `district`, `email`, `logo`, `confirmation_token`, `admin_password`, `confirmed_at`, `division`, `region`, `status`, `created_at`) VALUES
(17, 'NANNY WANG ELEMENTARY SCHOOL', '1000000', 'deped', 'Sitio Talipokpok, Barangay Cantoratoy', '09122350149', 'Tarragona', 'joselito.edong@gmail.com', NULL, NULL, NULL, '2026-06-03 09:17:09', 'Davao Oriental', 'XI', 1, '2026-06-03 09:16:33'),
(18, 'TEST SCHOOL', '0998888', 'deped', 'City of Mati, Davao Oriental', '0099999', 'Caraga North', 'joselito.edong@softtechservices.net', NULL, NULL, NULL, '2026-06-03 12:53:18', 'Davao Oriental', 'XI', 1, '2026-06-03 12:52:31'),
(19, 'FE ANG NHS', '098765', 'deped', '', '', 'Baganga North', 'artadybasty@gmail.com', NULL, NULL, NULL, '2026-06-03 13:59:19', 'Davao Oriental', 'XI', 1, '2026-06-03 13:58:20'),
(20, 'TANDANG SORA ELEMENTARY SCHOOL', '129287', 'deped', 'Guijo,Tandang Sora,Governror Generoso,Davao Oriental', '09465739455', 'Gov. Gen North', '129287@deped.gov.ph', 'uploads/school_logos/school_20_1780472364.png', NULL, NULL, '2026-06-03 14:47:13', 'Davao Oriental', 'XI', 1, '2026-06-03 14:46:16'),
(21, 'BAGUMBAYAN ELEMENTARY SCHOOL', '129324', 'deped', 'Purok Ligaya, Bagumbayan, Lupon, Davao Oriental', '', 'Lupon West', '129324@deped.gov.ph', 'uploads/school_logos/school_21_1780469334.png', NULL, NULL, '2026-06-03 14:47:04', 'Davao Oriental', 'XI', 1, '2026-06-03 14:46:36'),
(22, 'SIGABOY  AGRICULTURAL VOCATIONAL HIGH SCHOOL', '304336', 'deped', '', '09763011130', 'Gov. Gen North', 'vecente.macalua@deped.gov.ph', NULL, NULL, NULL, '2026-06-03 14:53:54', 'Davao Oriental', 'XI', 1, '2026-06-03 14:48:20'),
(23, 'CARAGA CENTRAL ELEMENTARY SCHOOL', '129211', 'deped', 'Pichon St., Poblacion, Caraga, Davao Oriental', '09265203162', 'Caraga North', 'jeneva.cruz@deped.gov.ph', NULL, NULL, NULL, '2026-06-03 14:55:13', 'Davao Oriental', 'XI', 1, '2026-06-03 14:49:10'),
(24, 'ENRIQUE ORENCIA ELEMENTARY SCHOOL', '129283', 'deped', 'Purok 1 Sampaguita, Tibanban, Governor Generoso, Davao Oriental', '09954522821', 'Gov. Gen North', '129283@deped.gov.ph', 'uploads/school_logos/school_24_1780472192.jpg', NULL, NULL, '2026-06-03 14:51:07', 'Davao Oriental', 'XI', 1, '2026-06-03 14:50:05'),
(25, 'SANTIAGO NATIONAL HIGH SCHOOL', '304335', 'deped', 'Santiago, Caraga, Davao Oriental', '09951031258', 'Caraga South', 'krisna.alvar@deped.gov.ph', NULL, NULL, NULL, '2026-06-03 14:56:35', 'Davao Oriental', 'XI', 1, '2026-06-03 14:54:29'),
(26, 'BOSTON NATIONAL HIGH SCHOOL - SHS', '304304', 'deped', 'Brgy. Poblacion, Boston Davao Oriental', 'geearlintogonan26@gmail.com', 'Boston District', 'geearlintogonan26@gmail.com', NULL, NULL, NULL, '2026-06-03 14:58:09', 'Davao Oriental', 'XI', 1, '2026-06-03 14:54:52'),
(27, 'LUPON VOCATIONAL HIGH SCHOOL', '304319', 'deped', 'Cambing Baratua st., Poblacion, Lupon, Davao Oriental', '', 'Lupon West', 'ericqbaldon@gmail.com', 'uploads/school_logos/school_27_1780471025.jpg', NULL, NULL, '2026-06-03 14:55:19', 'Davao Oriental', 'XI', 1, '2026-06-03 14:55:04'),
(28, 'CRISPIN E. ROJAS NATIONAL HIGH SCHOOL', '304312', 'deped', 'Block 10, Lambajon, Baganga, Davao Oriental', '09383137855', 'Baganga North', 'verna.valles@deped.gov.ph', NULL, NULL, NULL, '2026-06-03 14:58:57', 'Davao Oriental', 'XI', 1, '2026-06-03 14:55:28'),
(29, 'TIBANBAN NATIONAL HIGH SCHOOL', '304340', 'deped', 'Purok Rose, Tibanban, Governor Generoso, Davao Oriental', '09685458732', 'Gov. Gen North', 'cherryrose.anides@deped.gov.ph', NULL, NULL, NULL, '2026-06-03 15:07:04', 'Davao Oriental', 'XI', 1, '2026-06-03 14:55:30'),
(30, 'MANUEL B. GUINEZ SR.  NATIONAL HIGH SCHOOL', '304301', 'deped', 'Purok 5-B, Poblacion, Banaybanay, Davao Oriental', '', 'Banaybanay', 'gabriel.candia@deped.gov.ph', NULL, NULL, NULL, '2026-06-03 14:56:54', 'Davao Oriental', 'XI', 1, '2026-06-03 14:56:26'),
(31, 'AROMA BEACH ELEMENTARY SCHOOL', '129323', 'deped', 'AROMA 1, POBLACION, LUPON, DAVAO ORIENTAL', '09503661463', 'Lupon West', '129323@deped.gov.ph', 'uploads/school_logos/school_31_1780472064.png', NULL, NULL, '2026-06-03 15:02:03', 'Davao Oriental', 'XI', 1, '2026-06-03 14:57:42'),
(32, 'SAN ISIDRO CENTRAL SCHOOL SPED CENTER', '205506', 'deped', 'BATOBATO SAN ISISDRO DAVAO ORIENTAL', '09524498768', 'San Isidro North', 'racquel.espiritu@deped.gov.ph', NULL, NULL, NULL, '2026-06-03 16:28:36', 'Davao Oriental', 'XI', 1, '2026-06-03 14:57:43'),
(33, 'NANGAN NATIONAL HIGH SCHOOL', '8323500', 'deped', 'Nangan, Governor Generoso, Davao Oriental', '09762094557', 'Gov. Gen South', 'christinemae.delrosario@deped.gov.ph', NULL, NULL, NULL, '2026-06-03 15:07:27', 'Davao Oriental', 'XI', 1, '2026-06-03 14:58:03'),
(34, 'BOSTON NATIONAL HIGH SCHOOL - JHS', '304305', 'deped', 'Poblacion, Boston Davao Oriental ', '09286203806', 'Boston District', 'catherine.lintogonan@deped.gov.ph', NULL, 'YE203BZC17GIhR5TA2AKQgrT3L7D5mFy9DdrzNbbHMZW6I1WQJPLEaetSGJi8ukx', '$2y$10$GiaWRTqVUMgq.kTwpvsRjuPhB0.zqHAG15RS3y4t7hENJpGHSncs6', NULL, 'Davao Oriental', 'XI', 0, '2026-06-03 14:59:04'),
(35, 'SAN JOSE ELEMENTARY SCHOOL', '129204', 'deped', 'SAN JOSE, BOSTON, DAVAO ORIENTAL', '09534624840', 'Boston District', 'arnel.toroba@deped.gov.ph', NULL, NULL, NULL, '2026-06-03 15:13:03', 'Davao Oriental', 'XI', 1, '2026-06-03 14:59:24'),
(36, 'TARRAGONA CENTRAL ELEMENTARY SCHOOL', '129462', 'deped', 'POBLACION, TARRAGONA, DAVAO ORIENTAL\r\n.0', '09163720203', 'Tarragona', 'mirasol.maimad@deped.gov.ph', NULL, NULL, NULL, '2026-06-03 15:15:46', 'Davao Oriental', 'XI', 1, '2026-06-03 15:00:15'),
(37, 'SAN VICTOR ELEMENTARY SCHOOL', '129161', 'deped', 'SAN VICTOR , BAGANGA , DAVAO ORIENTAL', '09961625155', 'Baganga North', 'sanvictores.baganganorth@deped.gov.ph', NULL, '70x2uqYUhHU4MNkjHImPyzC3cEaBypmqE9SSfXJtW8wCZJOdFzIoVg3LiG6W9Dvi', '$2y$10$03JkibnvYPEHUpLELIJ.KeIsn0uzpcw1GKZ83noKYvdRdDvH7VB9O', NULL, 'Davao Oriental', 'XI', 0, '2026-06-03 15:12:19'),
(38, 'CATEEL VOCATIONAL HIGH SCHOOL', '304311', 'deped', 'Castro Ave. Poblacion, Cateel, Davao Oriental', '', 'Cateel 1', '304311@deped.gov.ph', NULL, NULL, NULL, '2026-06-03 15:26:37', 'Davao Oriental', 'XI', 1, '2026-06-03 15:15:09'),
(39, 'SAOQUIGUE ELEMENTARY SCHOOL', '129175', 'deped', 'MAHAYAHAY, SAOQUIGUE BAGANGA DAVAO ORIENTAL', '09464919877', 'Baganga South', '129175@deped.gov.ph', NULL, NULL, NULL, '2026-06-04 07:13:11', 'Davao Oriental', 'XI', 1, '2026-06-03 15:17:10'),
(40, 'BOSTON CENTRAL ELEMENTARY SCHOOL', '129195', 'deped', 'Toroba St. Poblacion, Boston Davao Oriental', '09477469452', 'Boston District', 'mercedes.dante002@deped.gov.ph', NULL, 'Vnkb8bgnlCJmKe1yEwaIFS69GLp9t3HOCzMatj0X0doOTM2Eq37k5QR5fDY74ciU', '$2y$10$SI8/SI5YUrmDp1c.GHgKjeYoyR26OEf2GlrlIqHAQoIrFSv/PjwZS', NULL, 'Davao Oriental', 'XI', 0, '2026-06-03 15:19:01'),
(41, 'CARAGA NATIONAL HIGH SCHOOL', '304307', 'deped', 'Brgy. Poblacion, Caraga, Davao Oriental', '09772309543', '', 'anfannakrizza.quibod@deped.gov.ph', NULL, NULL, NULL, '2026-06-03 15:23:51', 'Davao Oriental', 'XI', 1, '2026-06-03 15:19:08'),
(42, 'BOSTON NATIONAL HIGH SCHOOL - JHS', '000000', 'deped', 'POBLACION, BOSTON DAVAO ORIENTAL', '09286203806', 'Boston District', 'catherinelintogonan20@gmail.com', NULL, NULL, NULL, '2026-06-03 15:21:52', 'Davao Oriental', 'XI', 1, '2026-06-03 15:21:42'),
(43, 'PUNDAGUITAN NATIONAL HIGH SCHOOL', '304330', 'deped', 'Purok 1, Pundaguitan National High School', 'gina.arlalejo@deped.gov.ph', 'Gov. Gen South', 'gina.arlalejo@deped.gov.ph', NULL, NULL, NULL, '2026-06-03 15:25:40', 'Davao Oriental', 'XI', 1, '2026-06-03 15:22:42'),
(44, 'TIBLAWAN NATIONAL HIGH SCHOOL', '30233', 'deped', 'TIBLAWAN NATIONAL HIGH SCHOOL', '09688570388', 'Gov. Gen South', 'hermie.ylanan@deped.gov.ph', NULL, NULL, NULL, '2026-06-03 15:24:18', 'Davao Oriental', 'XI', 1, '2026-06-03 15:23:12'),
(45, 'BOSTON CENTRAL ELEMENTARY SCHOOL', '12195', 'deped', 'Toroba st. Poblacion, Boston, Davao Oriental', '09759409602', 'Boston District', 'eric.dante@deped.gov.ph', NULL, NULL, NULL, '2026-06-03 15:44:37', 'Davao Oriental', 'XI', 1, '2026-06-03 15:37:43'),
(46, 'LAMBAJON CENTRAL ELEMENTARY SCHOOL SPED CENTER', '129157', 'deped', 'Bock 5, sto. nino, Lambajon, Baganga, Oriental', '', 'Baganga North', '129157@deped.gov.ph', NULL, NULL, NULL, '2026-06-03 15:47:56', 'Davao Oriental', 'XI', 1, '2026-06-03 15:47:29'),
(47, 'SAOQUIGUE ELEMENTARY SCHOOL', '129185', 'deped', 'SAOQUIGUE, BAGANGA DAVAO ORIENTAL', '09464919877', 'Baganga South', 'greggy.yu@deped.gov.ph', NULL, NULL, NULL, '2026-06-03 16:33:01', 'Davao Oriental', 'XI', 1, '2026-06-03 16:32:14');

-- --------------------------------------------------------

--
-- Table structure for table `school_years`
--

CREATE TABLE `school_years` (
  `id` int UNSIGNED NOT NULL,
  `school_id` int UNSIGNED NOT NULL DEFAULT '1',
  `year_start` year NOT NULL,
  `year_end` year NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `school_years`
--

INSERT INTO `school_years` (`id`, `school_id`, `year_start`, `year_end`, `is_active`, `created_at`) VALUES
(5, 5, '2026', '2027', 0, '2026-05-30 10:49:58'),
(9, 14, '2026', '2027', 0, '2026-06-02 12:49:32'),
(10, 15, '2026', '2027', 0, '2026-06-02 13:00:27'),
(11, 16, '2026', '2027', 0, '2026-06-02 23:18:45'),
(12, 17, '2026', '2027', 1, '2026-06-03 01:17:30'),
(13, 18, '2026', '2027', 0, '2026-06-03 04:53:45'),
(14, 20, '2026', '2027', 0, '2026-06-03 07:11:14'),
(15, 21, '2026', '2027', 0, '2026-06-03 07:12:26'),
(16, 31, '2026', '2027', 0, '2026-06-03 07:13:03'),
(17, 22, '2026', '2027', 1, '2026-06-03 07:14:57'),
(18, 26, '2026', '2027', 0, '2026-06-03 07:20:56'),
(19, 23, '2026', '2027', 0, '2026-06-03 07:23:45'),
(20, 29, '2026', '2027', 0, '2026-06-03 07:27:01'),
(21, 24, '2026', '2027', 0, '2026-06-03 07:30:16'),
(22, 28, '2026', '2027', 0, '2026-06-03 07:33:58'),
(23, 44, '2026', '2027', 0, '2026-06-03 07:48:44'),
(24, 43, '2026', '2027', 0, '2026-06-03 07:56:56'),
(25, 27, '2026', '2027', 0, '2026-06-03 08:00:21'),
(26, 25, '2026', '2027', 1, '2026-06-03 08:26:41'),
(27, 32, '2026', '2027', 0, '2026-06-03 08:28:36'),
(28, 46, '2026', '2027', 0, '2026-06-03 08:29:32'),
(29, 19, '2026', '2027', 1, '2026-06-04 08:18:11'),
(30, 38, '2026', '2027', 1, '2026-06-04 09:08:22');

-- --------------------------------------------------------

--
-- Table structure for table `sections`
--

CREATE TABLE `sections` (
  `id` int UNSIGNED NOT NULL,
  `school_year_id` int UNSIGNED DEFAULT NULL,
  `school_id` int UNSIGNED NOT NULL DEFAULT '1',
  `name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `program_id` int UNSIGNED DEFAULT NULL,
  `year_level` tinyint DEFAULT NULL,
  `adviser_id` int UNSIGNED DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sections`
--

INSERT INTO `sections` (`id`, `school_year_id`, `school_id`, `name`, `program_id`, `year_level`, `adviser_id`, `status`) VALUES
(75, 17, 22, 'Luna', 112, 10, 447, 1),
(76, 17, 22, 'Jaena', 112, 10, 449, 1),
(77, 17, 22, 'Bonifacio', 112, 10, 446, 1),
(78, 17, 22, 'Del Pilar', 112, 10, 448, 1),
(79, 12, 17, 'Arroyo', 107, 10, 168, 1);

-- --------------------------------------------------------

--
-- Table structure for table `section_enrollment_keys`
--

CREATE TABLE `section_enrollment_keys` (
  `id` int UNSIGNED NOT NULL,
  `course_id` int UNSIGNED NOT NULL,
  `section_id` int UNSIGNED NOT NULL,
  `enrollment_key` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `semesters`
--

CREATE TABLE `semesters` (
  `id` int UNSIGNED NOT NULL,
  `school_year_id` int UNSIGNED NOT NULL,
  `name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` enum('quarter','semester') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'quarter',
  `term_number` tinyint NOT NULL,
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `semesters`
--

INSERT INTO `semesters` (`id`, `school_year_id`, `name`, `type`, `term_number`, `start_date`, `end_date`, `is_active`) VALUES
(1, 1, '1st Quarter', 'quarter', 1, NULL, NULL, 1),
(2, 1, '2nd Quarter', 'quarter', 2, NULL, NULL, 0),
(3, 1, '3rd Quarter', 'quarter', 3, NULL, NULL, 0),
(4, 1, '4th Quarter', 'quarter', 4, NULL, NULL, 0),
(5, 1, '1st Semester', 'semester', 1, NULL, NULL, 1),
(6, 1, '2nd Semester', 'semester', 2, NULL, NULL, 0),
(7, 1, 'Summer', 'semester', 3, NULL, NULL, 0);

-- --------------------------------------------------------

--
-- Table structure for table `shs_strands`
--

CREATE TABLE `shs_strands` (
  `id` int UNSIGNED NOT NULL,
  `track_id` int UNSIGNED NOT NULL,
  `name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `code` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `shs_strands`
--

INSERT INTO `shs_strands` (`id`, `track_id`, `name`, `code`, `description`) VALUES
(1, 1, 'Science, Technology, Engineering and Mathematics', 'STEM', NULL),
(2, 1, 'Accountancy, Business and Management', 'ABM', NULL),
(3, 1, 'Humanities and Social Sciences', 'HUMSS', NULL),
(4, 1, 'General Academic Strand', 'GAS', NULL),
(5, 2, 'Home Economics', 'HE', NULL),
(6, 2, 'Information and Communications Technology', 'ICT', NULL),
(7, 2, 'Agri-Fishery Arts', 'AFA', NULL),
(8, 2, 'Industrial Arts', 'IA', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `shs_tracks`
--

CREATE TABLE `shs_tracks` (
  `id` int UNSIGNED NOT NULL,
  `name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `code` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `shs_tracks`
--

INSERT INTO `shs_tracks` (`id`, `name`, `code`, `description`) VALUES
(1, 'Academic', 'ACAD', NULL),
(2, 'Technical-Vocational-Livelihood', 'TVL', NULL),
(3, 'Sports', 'SPORTS', NULL),
(4, 'Arts and Design', 'ARTS', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `staff`
--

CREATE TABLE `staff` (
  `IDNumber` varchar(20) NOT NULL,
  `user_id` int UNSIGNED NOT NULL,
  `school_id` int UNSIGNED NOT NULL DEFAULT '1',
  `employee_id` varchar(30) DEFAULT NULL,
  `department` varchar(150) DEFAULT NULL,
  `specialization` varchar(255) DEFAULT NULL,
  `position` varchar(100) DEFAULT NULL,
  `employment_type` enum('regular','part_time','contractual') DEFAULT 'regular',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `staff`
--

INSERT INTO `staff` (`IDNumber`, `user_id`, `school_id`, `employee_id`, `department`, `specialization`, `position`, `employment_type`, `created_at`) VALUES
('STF20260001', 55, 6, NULL, NULL, NULL, NULL, 'regular', '2026-05-30 16:29:10'),
('STF20260002', 56, 6, NULL, NULL, NULL, NULL, 'regular', '2026-05-30 16:34:24'),
('STF20260003', 57, 6, NULL, NULL, NULL, NULL, 'regular', '2026-05-30 16:34:24'),
('STF20260004', 58, 6, NULL, NULL, NULL, NULL, 'regular', '2026-05-30 16:34:24'),
('STF20260005', 59, 6, NULL, NULL, NULL, NULL, 'regular', '2026-05-30 16:34:24'),
('STF20260006', 60, 6, NULL, NULL, NULL, NULL, 'regular', '2026-05-30 16:34:24'),
('STF20260007', 61, 6, NULL, NULL, NULL, NULL, 'regular', '2026-05-30 16:34:24'),
('STF20260008', 62, 6, NULL, NULL, NULL, NULL, 'regular', '2026-05-30 16:34:24'),
('STF20260009', 63, 6, NULL, NULL, NULL, NULL, 'regular', '2026-05-30 16:34:24'),
('STF20260010', 64, 6, NULL, NULL, NULL, NULL, 'regular', '2026-05-30 16:34:24'),
('STF20260011', 65, 6, NULL, NULL, NULL, NULL, 'regular', '2026-05-30 16:34:24'),
('STF20260012', 66, 6, NULL, NULL, NULL, NULL, 'regular', '2026-05-30 16:34:24'),
('STF20260013', 67, 6, NULL, NULL, NULL, NULL, 'regular', '2026-05-30 16:34:24'),
('STF20260014', 68, 6, NULL, NULL, NULL, NULL, 'regular', '2026-05-30 16:34:24'),
('STF20260015', 69, 6, NULL, NULL, NULL, NULL, 'regular', '2026-05-30 16:34:24'),
('STF20260016', 70, 6, NULL, NULL, NULL, NULL, 'regular', '2026-05-30 16:34:24'),
('STF20260017', 71, 6, NULL, NULL, NULL, NULL, 'regular', '2026-05-30 16:34:25'),
('STF20260018', 72, 6, NULL, NULL, NULL, NULL, 'regular', '2026-05-30 16:34:25'),
('STF20260019', 73, 6, NULL, NULL, NULL, NULL, 'regular', '2026-05-30 16:34:25'),
('STF20260020', 74, 6, NULL, NULL, NULL, NULL, 'regular', '2026-05-30 16:34:25'),
('STF20260021', 75, 6, NULL, NULL, NULL, NULL, 'regular', '2026-05-30 16:34:25'),
('STF20260022', 76, 6, NULL, NULL, NULL, NULL, 'regular', '2026-05-30 16:34:25'),
('STF20260023', 77, 6, NULL, NULL, NULL, NULL, 'regular', '2026-05-30 16:34:25'),
('STF20260024', 78, 6, NULL, NULL, NULL, NULL, 'regular', '2026-05-30 16:34:25'),
('STF20260025', 79, 6, NULL, NULL, NULL, NULL, 'regular', '2026-05-30 16:34:25'),
('STF20260026', 80, 6, NULL, NULL, NULL, NULL, 'regular', '2026-05-30 16:34:25'),
('STF20260027', 112, 15, NULL, NULL, NULL, NULL, 'regular', '2026-06-02 13:15:02'),
('STF20260028', 113, 15, NULL, NULL, NULL, NULL, 'regular', '2026-06-02 13:15:02'),
('STF20260029', 114, 15, NULL, NULL, NULL, NULL, 'regular', '2026-06-02 13:15:02'),
('STF20260030', 115, 15, NULL, NULL, NULL, NULL, 'regular', '2026-06-02 13:15:03'),
('STF20260031', 116, 15, NULL, NULL, NULL, NULL, 'regular', '2026-06-02 13:15:03'),
('STF20260032', 117, 15, NULL, NULL, NULL, NULL, 'regular', '2026-06-02 13:15:03'),
('STF20260033', 118, 15, NULL, NULL, NULL, NULL, 'regular', '2026-06-02 13:15:03'),
('STF20260034', 119, 15, NULL, NULL, NULL, NULL, 'regular', '2026-06-02 13:15:03'),
('STF20260035', 120, 15, NULL, NULL, NULL, NULL, 'regular', '2026-06-02 13:15:03'),
('STF20260036', 121, 15, NULL, NULL, NULL, NULL, 'regular', '2026-06-02 13:15:03'),
('STF20260037', 122, 15, NULL, NULL, NULL, NULL, 'regular', '2026-06-02 13:15:03'),
('STF20260038', 123, 15, NULL, NULL, NULL, NULL, 'regular', '2026-06-02 13:15:04'),
('STF20260039', 124, 15, NULL, NULL, NULL, NULL, 'regular', '2026-06-02 13:15:04'),
('STF20260040', 125, 15, NULL, NULL, NULL, NULL, 'regular', '2026-06-02 13:15:04'),
('STF20260041', 126, 15, NULL, NULL, NULL, NULL, 'regular', '2026-06-02 13:15:04'),
('STF20260042', 127, 15, NULL, NULL, NULL, NULL, 'regular', '2026-06-02 13:15:04'),
('STF20260043', 128, 15, NULL, NULL, NULL, NULL, 'regular', '2026-06-02 13:15:05'),
('STF20260044', 129, 15, NULL, NULL, NULL, NULL, 'regular', '2026-06-02 13:15:05'),
('STF20260045', 130, 15, NULL, NULL, NULL, NULL, 'regular', '2026-06-02 13:15:05'),
('STF20260046', 131, 15, NULL, NULL, NULL, NULL, 'regular', '2026-06-02 13:15:05'),
('STF20260047', 132, 15, NULL, NULL, NULL, NULL, 'regular', '2026-06-02 13:15:05'),
('STF20260048', 133, 15, NULL, NULL, NULL, NULL, 'regular', '2026-06-02 13:15:05'),
('STF20260049', 134, 15, NULL, NULL, NULL, NULL, 'regular', '2026-06-02 13:15:06'),
('STF20260050', 135, 15, NULL, NULL, NULL, NULL, 'regular', '2026-06-02 13:15:06'),
('STF20260051', 136, 15, NULL, NULL, NULL, NULL, 'regular', '2026-06-02 13:15:06'),
('STF20260052', 140, 17, NULL, NULL, NULL, NULL, 'regular', '2026-06-03 01:28:30'),
('STF20260053', 168, 17, NULL, NULL, NULL, NULL, 'regular', '2026-06-03 06:06:21'),
('STF20260054', 169, 17, NULL, NULL, NULL, NULL, 'regular', '2026-06-03 06:06:21'),
('STF20260055', 170, 17, NULL, NULL, NULL, NULL, 'regular', '2026-06-03 06:06:22'),
('STF20260056', 171, 17, NULL, NULL, NULL, NULL, 'regular', '2026-06-03 06:06:22'),
('STF20260057', 172, 17, NULL, NULL, NULL, NULL, 'regular', '2026-06-03 06:06:22'),
('STF20260058', 173, 17, NULL, NULL, NULL, NULL, 'regular', '2026-06-03 06:06:22'),
('STF20260059', 174, 17, NULL, NULL, NULL, NULL, 'regular', '2026-06-03 06:06:22'),
('STF20260060', 175, 17, NULL, NULL, NULL, NULL, 'regular', '2026-06-03 06:06:22'),
('STF20260061', 176, 17, NULL, NULL, NULL, NULL, 'regular', '2026-06-03 06:06:22'),
('STF20260062', 177, 17, NULL, NULL, NULL, NULL, 'regular', '2026-06-03 06:06:22'),
('STF20260063', 178, 17, NULL, NULL, NULL, NULL, 'regular', '2026-06-03 06:06:22'),
('STF20260064', 179, 17, NULL, NULL, NULL, NULL, 'regular', '2026-06-03 06:06:22'),
('STF20260065', 180, 17, NULL, NULL, NULL, NULL, 'regular', '2026-06-03 06:06:22'),
('STF20260066', 181, 17, NULL, NULL, NULL, NULL, 'regular', '2026-06-03 06:06:22'),
('STF20260067', 182, 17, NULL, NULL, NULL, NULL, 'regular', '2026-06-03 06:06:23'),
('STF20260068', 183, 17, NULL, NULL, NULL, NULL, 'regular', '2026-06-03 06:06:23'),
('STF20260069', 184, 17, NULL, NULL, NULL, NULL, 'regular', '2026-06-03 06:06:23'),
('STF20260070', 185, 17, NULL, NULL, NULL, NULL, 'regular', '2026-06-03 06:06:23'),
('STF20260071', 186, 17, NULL, NULL, NULL, NULL, 'regular', '2026-06-03 06:06:23'),
('STF20260072', 187, 17, NULL, NULL, NULL, NULL, 'regular', '2026-06-03 06:06:23'),
('STF20260073', 188, 17, NULL, NULL, NULL, NULL, 'regular', '2026-06-03 06:06:23'),
('STF20260074', 189, 17, NULL, NULL, NULL, NULL, 'regular', '2026-06-03 06:06:23'),
('STF20260075', 190, 17, NULL, NULL, NULL, NULL, 'regular', '2026-06-03 06:06:23'),
('STF20260076', 191, 17, NULL, NULL, NULL, NULL, 'regular', '2026-06-03 06:06:23'),
('STF20260077', 192, 17, NULL, NULL, NULL, NULL, 'regular', '2026-06-03 06:06:23'),
('STF20260078', 197, 20, NULL, NULL, NULL, NULL, 'regular', '2026-06-03 06:54:18'),
('STF20260079', 198, 20, NULL, NULL, NULL, NULL, 'regular', '2026-06-03 06:54:18'),
('STF20260080', 199, 20, NULL, NULL, NULL, NULL, 'regular', '2026-06-03 06:54:18'),
('STF20260081', 200, 20, NULL, NULL, NULL, NULL, 'regular', '2026-06-03 06:54:18'),
('STF20260082', 201, 20, NULL, NULL, NULL, NULL, 'regular', '2026-06-03 06:54:19'),
('STF20260083', 202, 20, NULL, NULL, NULL, NULL, 'regular', '2026-06-03 06:54:19'),
('STF20260084', 203, 20, NULL, NULL, NULL, NULL, 'regular', '2026-06-03 06:54:19'),
('STF20260085', 204, 20, NULL, NULL, NULL, NULL, 'regular', '2026-06-03 06:54:19'),
('STF20260086', 205, 20, NULL, NULL, NULL, NULL, 'regular', '2026-06-03 06:54:19'),
('STF20260087', 206, 20, NULL, NULL, NULL, NULL, 'regular', '2026-06-03 06:54:19'),
('STF20260088', 207, 20, NULL, NULL, NULL, NULL, 'regular', '2026-06-03 06:54:19'),
('STF20260089', 208, 20, NULL, NULL, NULL, NULL, 'regular', '2026-06-03 06:54:19'),
('STF20260090', 209, 20, NULL, NULL, NULL, NULL, 'regular', '2026-06-03 06:54:19'),
('STF20260091', 210, 20, NULL, NULL, NULL, NULL, 'regular', '2026-06-03 06:54:19'),
('STF20260092', 211, 21, NULL, NULL, NULL, NULL, 'regular', '2026-06-03 06:54:38'),
('STF20260093', 212, 21, NULL, NULL, NULL, NULL, 'regular', '2026-06-03 06:54:38'),
('STF20260094', 213, 21, NULL, NULL, NULL, NULL, 'regular', '2026-06-03 06:54:39'),
('STF20260095', 214, 21, NULL, NULL, NULL, NULL, 'regular', '2026-06-03 06:54:39'),
('STF20260097', 232, 24, NULL, NULL, NULL, NULL, 'regular', '2026-06-03 07:00:57'),
('STF20260098', 233, 24, NULL, NULL, NULL, NULL, 'regular', '2026-06-03 07:00:57'),
('STF20260099', 234, 21, NULL, NULL, NULL, NULL, 'regular', '2026-06-03 07:01:09'),
('STF20260102', 247, 31, NULL, NULL, NULL, NULL, 'regular', '2026-06-03 07:04:39'),
('STF20260103', 261, 23, NULL, NULL, NULL, NULL, 'regular', '2026-06-03 07:14:21'),
('STF20260104', 263, 24, NULL, NULL, NULL, NULL, 'regular', '2026-06-03 07:14:27'),
('STF20260105', 265, 24, NULL, NULL, NULL, NULL, 'regular', '2026-06-03 07:14:28'),
('STF20260106', 267, 24, NULL, NULL, NULL, NULL, 'regular', '2026-06-03 07:14:29'),
('STF20260107', 269, 24, NULL, NULL, NULL, NULL, 'regular', '2026-06-03 07:14:29'),
('STF20260108', 270, 24, NULL, NULL, NULL, NULL, 'regular', '2026-06-03 07:14:29'),
('STF20260109', 273, 24, NULL, NULL, NULL, NULL, 'regular', '2026-06-03 07:14:29'),
('STF20260110', 275, 24, NULL, NULL, NULL, NULL, 'regular', '2026-06-03 07:14:30'),
('STF20260111', 276, 24, NULL, NULL, NULL, NULL, 'regular', '2026-06-03 07:14:30'),
('STF20260112', 278, 24, NULL, NULL, NULL, NULL, 'regular', '2026-06-03 07:14:30'),
('STF20260113', 280, 24, NULL, NULL, NULL, NULL, 'regular', '2026-06-03 07:14:30'),
('STF20260114', 281, 24, NULL, NULL, NULL, NULL, 'regular', '2026-06-03 07:14:30'),
('STF20260115', 282, 24, NULL, NULL, NULL, NULL, 'regular', '2026-06-03 07:14:31'),
('STF20260116', 283, 24, NULL, NULL, NULL, NULL, 'regular', '2026-06-03 07:14:31'),
('STF20260117', 284, 24, NULL, NULL, NULL, NULL, 'regular', '2026-06-03 07:14:31'),
('STF20260118', 285, 24, NULL, NULL, NULL, NULL, 'regular', '2026-06-03 07:14:31'),
('STF20260119', 286, 24, NULL, NULL, NULL, NULL, 'regular', '2026-06-03 07:14:31'),
('STF20260120', 287, 24, NULL, NULL, NULL, NULL, 'regular', '2026-06-03 07:14:31'),
('STF20260121', 288, 24, NULL, NULL, NULL, NULL, 'regular', '2026-06-03 07:14:32'),
('STF20260122', 289, 24, NULL, NULL, NULL, NULL, 'regular', '2026-06-03 07:14:32'),
('STF20260123', 290, 24, NULL, NULL, NULL, NULL, 'regular', '2026-06-03 07:14:32'),
('STF20260124', 291, 24, NULL, NULL, NULL, NULL, 'regular', '2026-06-03 07:14:33'),
('STF20260125', 292, 24, NULL, NULL, NULL, NULL, 'regular', '2026-06-03 07:14:35'),
('STF20260126', 293, 24, NULL, NULL, NULL, NULL, 'regular', '2026-06-03 07:14:36'),
('STF20260127', 294, 24, NULL, NULL, NULL, NULL, 'regular', '2026-06-03 07:14:36'),
('STF20260128', 295, 24, NULL, NULL, NULL, NULL, 'regular', '2026-06-03 07:14:36'),
('STF20260129', 296, 24, NULL, NULL, NULL, NULL, 'regular', '2026-06-03 07:14:37'),
('STF20260130', 297, 24, NULL, NULL, NULL, NULL, 'regular', '2026-06-03 07:14:37'),
('STF20260131', 298, 24, NULL, NULL, NULL, NULL, 'regular', '2026-06-03 07:14:38'),
('STF20260132', 299, 24, NULL, NULL, NULL, NULL, 'regular', '2026-06-03 07:14:40'),
('STF20260133', 326, 26, NULL, NULL, NULL, NULL, 'regular', '2026-06-03 07:24:06'),
('STF20260134', 341, 29, NULL, NULL, NULL, NULL, 'regular', '2026-06-03 07:31:29'),
('STF20260135', 342, 29, NULL, NULL, NULL, NULL, 'regular', '2026-06-03 07:31:29'),
('STF20260136', 343, 29, NULL, NULL, NULL, NULL, 'regular', '2026-06-03 07:31:29'),
('STF20260137', 344, 29, NULL, NULL, NULL, NULL, 'regular', '2026-06-03 07:31:29'),
('STF20260138', 345, 29, NULL, NULL, NULL, NULL, 'regular', '2026-06-03 07:31:29'),
('STF20260139', 352, 25, NULL, NULL, NULL, NULL, 'regular', '2026-06-03 07:31:59'),
('STF20260140', 353, 25, NULL, NULL, NULL, NULL, 'regular', '2026-06-03 07:31:59'),
('STF20260141', 354, 25, NULL, NULL, NULL, NULL, 'regular', '2026-06-03 07:31:59'),
('STF20260142', 355, 25, NULL, NULL, NULL, NULL, 'regular', '2026-06-03 07:31:59'),
('STF20260143', 356, 25, NULL, NULL, NULL, NULL, 'regular', '2026-06-03 07:31:59'),
('STF20260144', 361, 28, NULL, NULL, NULL, NULL, 'regular', '2026-06-03 07:35:14'),
('STF20260145', 381, 23, NULL, NULL, NULL, NULL, 'regular', '2026-06-03 07:42:42'),
('STF20260146', 383, 23, NULL, NULL, NULL, NULL, 'regular', '2026-06-03 07:43:33'),
('STF20260147', 391, 23, NULL, NULL, NULL, NULL, 'regular', '2026-06-03 07:48:00'),
('STF20260148', 396, 46, NULL, NULL, NULL, NULL, 'regular', '2026-06-03 07:49:41'),
('STF20260149', 398, 31, NULL, NULL, NULL, NULL, 'regular', '2026-06-03 07:51:47'),
('STF20260150', 399, 31, NULL, NULL, NULL, NULL, 'regular', '2026-06-03 07:51:47'),
('STF20260151', 400, 31, NULL, NULL, NULL, NULL, 'regular', '2026-06-03 07:51:47'),
('STF20260152', 408, 27, NULL, NULL, NULL, NULL, 'regular', '2026-06-03 07:54:19'),
('STF20260153', 409, 31, NULL, NULL, NULL, NULL, 'regular', '2026-06-03 07:55:30'),
('STF20260154', 410, 44, NULL, NULL, NULL, NULL, 'regular', '2026-06-03 07:56:02'),
('STF20260155', 416, 31, NULL, NULL, NULL, NULL, 'regular', '2026-06-03 08:01:00'),
('STF20260156', 417, 28, NULL, NULL, NULL, NULL, 'regular', '2026-06-03 08:01:01'),
('STF20260157', 418, 28, NULL, NULL, NULL, NULL, 'regular', '2026-06-03 08:01:01'),
('STF20260158', 425, 35, NULL, NULL, NULL, NULL, 'regular', '2026-06-03 08:20:57'),
('STF20260159', 426, 35, NULL, NULL, NULL, NULL, 'regular', '2026-06-03 08:20:57'),
('STF20260160', 427, 35, NULL, NULL, NULL, NULL, 'regular', '2026-06-03 08:20:58'),
('STF20260161', 428, 35, NULL, NULL, NULL, NULL, 'regular', '2026-06-03 08:20:58'),
('STF20260162', 432, 30, NULL, NULL, NULL, NULL, 'regular', '2026-06-03 08:36:05'),
('STF20260163', 433, 30, NULL, NULL, NULL, NULL, 'regular', '2026-06-03 08:36:05'),
('STF20260164', 434, 30, NULL, NULL, NULL, NULL, 'regular', '2026-06-03 08:36:06'),
('STF20260165', 435, 30, NULL, NULL, NULL, NULL, 'regular', '2026-06-03 08:36:06'),
('STF20260166', 437, 43, NULL, NULL, NULL, NULL, 'regular', '2026-06-03 22:57:23'),
('STF20260167', 438, 43, NULL, NULL, NULL, NULL, 'regular', '2026-06-03 22:57:23'),
('STF20260168', 439, 43, NULL, NULL, NULL, NULL, 'regular', '2026-06-03 22:57:23'),
('STF20260169', 440, 43, NULL, NULL, NULL, NULL, 'regular', '2026-06-03 22:57:23'),
('STF20260170', 441, 43, NULL, NULL, NULL, NULL, 'regular', '2026-06-03 22:57:23'),
('STF20260171', 445, 22, NULL, NULL, NULL, NULL, 'regular', '2026-06-04 11:14:11'),
('STF20260172', 446, 22, NULL, NULL, NULL, NULL, 'regular', '2026-06-04 11:21:12'),
('STF20260173', 447, 22, NULL, NULL, NULL, NULL, 'regular', '2026-06-04 11:21:53'),
('STF20260174', 448, 22, NULL, NULL, NULL, NULL, 'regular', '2026-06-04 11:23:31'),
('STF20260175', 449, 22, NULL, NULL, NULL, NULL, 'regular', '2026-06-04 11:23:31');

-- --------------------------------------------------------

--
-- Table structure for table `studentprofile`
--

CREATE TABLE `studentprofile` (
  `id` int UNSIGNED NOT NULL,
  `user_id` int UNSIGNED DEFAULT NULL,
  `school_id` int UNSIGNED NOT NULL,
  `student_number` varchar(50) NOT NULL,
  `first_name` varchar(100) NOT NULL,
  `middle_name` varchar(100) DEFAULT NULL,
  `last_name` varchar(100) NOT NULL,
  `birth_date` date NOT NULL,
  `email` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `studentprofile`
--

INSERT INTO `studentprofile` (`id`, `user_id`, `school_id`, `student_number`, `first_name`, `middle_name`, `last_name`, `birth_date`, `email`, `created_at`, `updated_at`) VALUES
(1, 29, 6, '123', 'Liam Xander', 'Garcia', 'Edong', '2014-08-09', 'email@email.com', '2026-05-30 05:36:14', '2026-05-30 06:06:43'),
(2, 30, 6, '2025-0001', 'Juan', 'Dela', 'Cruz', '2010-05-15', 'juan.cruz@email.com', '2026-05-30 07:24:33', '2026-05-30 07:24:33'),
(3, 31, 6, '2025-0002', 'Maria', 'Santos', 'Reyes', '2010-06-20', 'maria.reyes@email.com', '2026-05-30 07:24:33', '2026-05-30 07:24:33'),
(4, 32, 6, '2025-0003', 'Jose', 'Garcia', 'Lim', '2010-07-10', 'jose.lim@email.com', '2026-05-30 07:24:33', '2026-05-30 07:24:33'),
(5, 33, 6, '2025-0004', 'Ana', 'Mendoza', 'Tan', '2010-08-05', 'ana.tan@email.com', '2026-05-30 07:24:33', '2026-05-30 07:24:33'),
(6, 34, 6, '2025-0005', 'Carlos', 'Dizon', 'Ong', '2010-09-12', 'carlos.ong@email.com', '2026-05-30 07:24:33', '2026-05-30 07:24:33'),
(7, 35, 6, '2025-0006', 'Patricia', 'Ng', 'Chua', '2010-10-18', 'patricia.chua@email.com', '2026-05-30 07:24:33', '2026-05-30 07:24:33'),
(8, 36, 6, '2025-0007', 'Michael', 'Reyes', 'Co', '2010-11-25', 'michael.co@email.com', '2026-05-30 07:24:33', '2026-05-30 07:24:33'),
(9, 37, 6, '2025-0008', 'Elizabeth', 'Sy', 'Lee', '2010-12-30', 'elizabeth.lee@email.com', '2026-05-30 07:24:33', '2026-05-30 07:24:33'),
(10, 38, 6, '2025-0009', 'David', 'Go', 'Ho', '2011-01-15', 'david.ho@email.com', '2026-05-30 07:24:33', '2026-05-30 07:24:33'),
(11, 39, 6, '2025-0010', 'Sarah', 'Cheng', 'Wong', '2011-02-20', 'sarah.wong@email.com', '2026-05-30 07:24:33', '2026-05-30 07:24:33'),
(12, 40, 6, '2025-0011', 'Robert', 'Tan', 'Lau', '2011-03-10', 'robert.lau@email.com', '2026-05-30 07:24:33', '2026-05-30 07:24:33'),
(13, 41, 6, '2025-0012', 'Jennifer', 'Lim', 'Chan', '2011-04-05', 'jennifer.chan@email.com', '2026-05-30 07:24:33', '2026-05-30 07:24:33'),
(14, 42, 6, '2025-0013', 'William', 'Huang', 'Wu', '2011-05-18', 'william.wu@email.com', '2026-05-30 07:24:33', '2026-05-30 07:24:33'),
(15, 43, 6, '2025-0014', 'Amanda', 'Zhao', 'Liu', '2011-06-22', 'amanda.liu@email.com', '2026-05-30 07:24:33', '2026-05-30 07:24:33'),
(16, 44, 6, '2025-0015', 'Christopher', 'Wang', 'Xu', '2011-07-08', 'christopher.xu@email.com', '2026-05-30 07:24:34', '2026-05-30 07:24:34'),
(17, 45, 6, '2025-0016', 'Jessica', 'Li', 'Yang', '2011-08-14', 'jessica.yang@email.com', '2026-05-30 07:24:34', '2026-05-30 07:24:34'),
(18, 46, 6, '2025-0017', 'Daniel', 'Zhou', 'Zhang', '2011-09-25', 'daniel.zhang@email.com', '2026-05-30 07:24:34', '2026-05-30 07:24:34'),
(19, 47, 6, '2025-0018', 'Michelle', 'Chen', 'Lin', '2011-10-30', 'michelle.lin@email.com', '2026-05-30 07:24:34', '2026-05-30 07:24:34'),
(20, 48, 6, '2025-0019', 'Matthew', 'Wu', 'Zhao', '2011-11-12', 'matthew.zhao@email.com', '2026-05-30 07:24:34', '2026-05-30 07:24:34'),
(21, 49, 6, '2025-0020', 'Laura', 'Xu', 'Wang', '2011-12-20', 'laura.wang@email.com', '2026-05-30 07:24:34', '2026-05-30 07:24:34'),
(22, 50, 6, '2025-0021', 'Andrew', 'Sun', 'Li', '2012-01-08', 'andrew.li@email.com', '2026-05-30 07:24:34', '2026-05-30 07:24:34'),
(23, 51, 6, '2025-0022', 'Stephanie', 'Moon', 'Kim', '2012-02-15', 'stephanie.kim@email.com', '2026-05-30 07:24:34', '2026-05-30 07:24:34'),
(24, 52, 6, '2025-0023', 'Joshua', 'Park', 'Lee', '2012-03-22', 'joshua.lee@email.com', '2026-05-30 07:24:34', '2026-05-30 07:24:34'),
(25, 53, 6, '2025-0024', 'Emily', 'Choi', 'Yoon', '2012-04-10', 'emily.yoon@email.com', '2026-05-30 07:24:34', '2026-05-30 07:24:34'),
(26, 54, 6, '2025-0025', 'Brian', 'Kang', 'Seo', '2012-05-18', 'brian.seo@email.com', '2026-05-30 07:24:34', '2026-05-30 07:24:34'),
(27, 81, 6, 'rwerew', 'sdfadsf', 'dfads', 'edong', '2026-05-30', '', '2026-05-30 09:20:37', '2026-05-30 09:20:37'),
(28, 87, 15, '2025-0001', 'Juan', 'Dela', 'Cruz', '2010-05-15', 'juan.cruz@email.com', '2026-06-02 13:01:21', '2026-06-02 13:01:21'),
(29, 88, 15, '2025-0002', 'Maria', 'Santos', 'Reyes', '2010-06-20', 'maria.reyes@email.com', '2026-06-02 13:01:21', '2026-06-02 13:01:21'),
(30, 89, 15, '2025-0003', 'Jose', 'Garcia', 'Lim', '2010-07-10', 'jose.lim@email.com', '2026-06-02 13:01:21', '2026-06-02 13:01:21'),
(31, 90, 15, '2025-0004', 'Ana', 'Mendoza', 'Tan', '2010-08-05', 'ana.tan@email.com', '2026-06-02 13:01:21', '2026-06-02 13:01:21'),
(32, 91, 15, '2025-0005', 'Carlos', 'Dizon', 'Ong', '2010-09-12', 'carlos.ong@email.com', '2026-06-02 13:01:21', '2026-06-02 13:01:21'),
(33, 92, 15, '2025-0006', 'Patricia', 'Ng', 'Chua', '2010-10-18', 'patricia.chua@email.com', '2026-06-02 13:01:22', '2026-06-02 13:01:22'),
(34, 93, 15, '2025-0007', 'Michael', 'Reyes', 'Co', '2010-11-25', 'michael.co@email.com', '2026-06-02 13:01:22', '2026-06-02 13:01:22'),
(35, 94, 15, '2025-0008', 'Elizabeth', 'Sy', 'Lee', '2010-12-30', 'elizabeth.lee@email.com', '2026-06-02 13:01:22', '2026-06-02 13:01:22'),
(36, 95, 15, '2025-0009', 'David', 'Go', 'Ho', '2011-01-15', 'david.ho@email.com', '2026-06-02 13:01:22', '2026-06-02 13:01:22'),
(37, 96, 15, '2025-0010', 'Sarah', 'Cheng', 'Wong', '2011-02-20', 'sarah.wong@email.com', '2026-06-02 13:01:22', '2026-06-02 13:01:22'),
(38, 97, 15, '2025-0011', 'Robert', 'Tan', 'Lau', '2011-03-10', 'robert.lau@email.com', '2026-06-02 13:01:22', '2026-06-02 13:01:22'),
(39, 98, 15, '2025-0012', 'Jennifer', 'Lim', 'Chan', '2011-04-05', 'jennifer.chan@email.com', '2026-06-02 13:01:22', '2026-06-02 13:01:22'),
(40, 99, 15, '2025-0013', 'William', 'Huang', 'Wu', '2011-05-18', 'william.wu@email.com', '2026-06-02 13:01:22', '2026-06-02 13:01:22'),
(41, 100, 15, '2025-0014', 'Amanda', 'Zhao', 'Liu', '2011-06-22', 'amanda.liu@email.com', '2026-06-02 13:01:22', '2026-06-02 13:01:22'),
(42, 101, 15, '2025-0015', 'Christopher', 'Wang', 'Xu', '2011-07-08', 'christopher.xu@email.com', '2026-06-02 13:01:22', '2026-06-02 13:01:22'),
(43, 102, 15, '2025-0016', 'Jessica', 'Li', 'Yang', '2011-08-14', 'jessica.yang@email.com', '2026-06-02 13:01:22', '2026-06-02 13:01:22'),
(44, 103, 15, '2025-0017', 'Daniel', 'Zhou', 'Zhang', '2011-09-25', 'daniel.zhang@email.com', '2026-06-02 13:01:22', '2026-06-02 13:01:22'),
(45, 104, 15, '2025-0018', 'Michelle', 'Chen', 'Lin', '2011-10-30', 'michelle.lin@email.com', '2026-06-02 13:01:23', '2026-06-02 13:01:23'),
(46, 105, 15, '2025-0019', 'Matthew', 'Wu', 'Zhao', '2011-11-12', 'matthew.zhao@email.com', '2026-06-02 13:01:23', '2026-06-02 13:01:23'),
(47, 106, 15, '2025-0020', 'Laura', 'Xu', 'Wang', '2011-12-20', 'laura.wang@email.com', '2026-06-02 13:01:23', '2026-06-02 13:01:23'),
(48, 107, 15, '2025-0021', 'Andrew', 'Sun', 'Li', '2012-01-08', 'andrew.li@email.com', '2026-06-02 13:01:23', '2026-06-02 13:01:23'),
(49, 108, 15, '2025-0022', 'Stephanie', 'Moon', 'Kim', '2012-02-15', 'stephanie.kim@email.com', '2026-06-02 13:01:23', '2026-06-02 13:01:23'),
(50, 109, 15, '2025-0023', 'Joshua', 'Park', 'Lee', '2012-03-22', 'joshua.lee@email.com', '2026-06-02 13:01:23', '2026-06-02 13:01:23'),
(51, 110, 15, '2025-0024', 'Emily', 'Choi', 'Yoon', '2012-04-10', 'emily.yoon@email.com', '2026-06-02 13:01:23', '2026-06-02 13:01:23'),
(52, 111, 15, '2025-0025', 'Brian', 'Kang', 'Seo', '2012-05-18', 'brian.seo@email.com', '2026-06-02 13:01:23', '2026-06-02 13:01:23'),
(53, 139, 17, '1234567890', 'NANNY', 'GO', 'WANG', '2026-06-03', 'nanny.wang@domain.com', '2026-06-03 01:18:39', '2026-06-03 01:18:39'),
(54, 143, 17, '2025-0001', 'Juan', 'Dela', 'Cruz', '2010-05-15', 'juan.cruz@email.com', '2026-06-03 06:02:37', '2026-06-03 06:02:37'),
(55, 144, 17, '2025-0002', 'Maria', 'Santos', 'Reyes', '2010-06-20', 'maria.reyes@email.com', '2026-06-03 06:02:37', '2026-06-03 06:02:37'),
(56, 145, 17, '2025-0003', 'Jose', 'Garcia', 'Lim', '2010-07-10', 'jose.lim@email.com', '2026-06-03 06:02:37', '2026-06-03 06:02:37'),
(57, 146, 17, '2025-0004', 'Ana', 'Mendoza', 'Tan', '2010-08-05', 'ana.tan@email.com', '2026-06-03 06:02:38', '2026-06-03 06:02:38'),
(58, 147, 17, '2025-0005', 'Carlos', 'Dizon', 'Ong', '2010-09-12', 'carlos.ong@email.com', '2026-06-03 06:02:38', '2026-06-03 06:02:38'),
(59, 148, 17, '2025-0006', 'Patricia', 'Ng', 'Chua', '2010-10-18', 'patricia.chua@email.com', '2026-06-03 06:02:38', '2026-06-03 06:02:38'),
(60, 149, 17, '2025-0007', 'Michael', 'Reyes', 'Co', '2010-11-25', 'michael.co@email.com', '2026-06-03 06:02:38', '2026-06-03 06:02:38'),
(61, 150, 17, '2025-0008', 'Elizabeth', 'Sy', 'Lee', '2010-12-30', 'elizabeth.lee@email.com', '2026-06-03 06:02:38', '2026-06-03 06:02:38'),
(62, 151, 17, '2025-0009', 'David', 'Go', 'Ho', '2011-01-15', 'david.ho@email.com', '2026-06-03 06:02:38', '2026-06-03 06:02:38'),
(63, 152, 17, '2025-0010', 'Sarah', 'Cheng', 'Wong', '2011-02-20', 'sarah.wong@email.com', '2026-06-03 06:02:38', '2026-06-03 06:02:38'),
(64, 153, 17, '2025-0011', 'Robert', 'Tan', 'Lau', '2011-03-10', 'robert.lau@email.com', '2026-06-03 06:02:38', '2026-06-03 06:02:38'),
(65, 154, 17, '2025-0012', 'Jennifer', 'Lim', 'Chan', '2011-04-05', 'jennifer.chan@email.com', '2026-06-03 06:02:38', '2026-06-03 06:02:38'),
(66, 155, 17, '2025-0013', 'William', 'Huang', 'Wu', '2011-05-18', 'william.wu@email.com', '2026-06-03 06:02:38', '2026-06-03 06:02:38'),
(67, 156, 17, '2025-0014', 'Amanda', 'Zhao', 'Liu', '2011-06-22', 'amanda.liu@email.com', '2026-06-03 06:02:38', '2026-06-03 06:02:38'),
(68, 157, 17, '2025-0015', 'Christopher', 'Wang', 'Xu', '2011-07-08', 'christopher.xu@email.com', '2026-06-03 06:02:38', '2026-06-03 06:02:38'),
(69, 158, 17, '2025-0016', 'Jessica', 'Li', 'Yang', '2011-08-14', 'jessica.yang@email.com', '2026-06-03 06:02:39', '2026-06-03 06:02:39'),
(70, 159, 17, '2025-0017', 'Daniel', 'Zhou', 'Zhang', '2011-09-25', 'daniel.zhang@email.com', '2026-06-03 06:02:39', '2026-06-03 06:02:39'),
(71, 160, 17, '2025-0018', 'Michelle', 'Chen', 'Lin', '2011-10-30', 'michelle.lin@email.com', '2026-06-03 06:02:39', '2026-06-03 06:02:39'),
(72, 161, 17, '2025-0019', 'Matthew', 'Wu', 'Zhao', '2011-11-12', 'matthew.zhao@email.com', '2026-06-03 06:02:39', '2026-06-03 06:02:39'),
(73, 162, 17, '2025-0020', 'Laura', 'Xu', 'Wang', '2011-12-20', 'laura.wang@email.com', '2026-06-03 06:02:39', '2026-06-03 06:02:39'),
(74, 163, 17, '2025-0021', 'Andrew', 'Sun', 'Li', '2012-01-08', 'andrew.li@email.com', '2026-06-03 06:02:39', '2026-06-03 06:02:39'),
(75, 164, 17, '2025-0022', 'Stephanie', 'Moon', 'Kim', '2012-02-15', 'stephanie.kim@email.com', '2026-06-03 06:02:39', '2026-06-03 06:02:39'),
(76, 165, 17, '2025-0023', 'Joshua', 'Park', 'Lee', '2012-03-22', 'joshua.lee@email.com', '2026-06-03 06:02:39', '2026-06-03 06:02:39'),
(77, 166, 17, '2025-0024', 'Emily', 'Choi', 'Yoon', '2012-04-10', 'emily.yoon@email.com', '2026-06-03 06:02:39', '2026-06-03 06:02:39'),
(78, 167, 17, '2025-0025', 'Brian', 'Kang', 'Seo', '2012-05-18', 'brian.seo@email.com', '2026-06-03 06:02:39', '2026-06-03 06:02:39'),
(79, 240, 20, '129287170001', 'mark', 'amo', 'legurpa', '0000-00-00', 'juan.cruz@email.com', '2026-06-03 07:03:08', '2026-06-03 07:03:08'),
(80, 241, 20, '129287170039', 'eric', 'dave', 'matinao', '0000-00-00', 'maria.reyes@email.com', '2026-06-03 07:03:08', '2026-06-03 07:03:08'),
(81, 242, 20, '129287190006', 'john', 'lao', 'empoc', '0000-00-00', 'jose.lim@email.com', '2026-06-03 07:03:08', '2026-06-03 07:03:08'),
(82, 243, 20, '129478190158', 'kaye', 'amad', 'lumantad', '0000-00-00', 'ana.tan@email.com', '2026-06-03 07:03:08', '2026-06-03 07:03:08'),
(92, 307, 22, '100000000001', 'Maria', 'Santos', 'Reyes', '0000-00-00', 'maria.reyes@student.example.com', '2026-06-03 07:17:25', '2026-06-03 07:17:25'),
(93, 308, 22, '100000000002', 'John', 'Dela Cruz', 'Garcia', '0000-00-00', 'john.garcia@student.example.com', '2026-06-03 07:17:25', '2026-06-03 07:17:25'),
(94, 309, 22, '100000000003', 'Angela', 'Torres', 'Mendoza', '0000-00-00', 'angela.mendoza@student.example.com', '2026-06-03 07:17:25', '2026-06-03 07:17:25'),
(95, 310, 22, '100000000004', 'Mark', 'Ramos', 'Santos', '0000-00-00', 'mark.santos@student.example.com', '2026-06-03 07:17:25', '2026-06-03 07:17:25'),
(96, 311, 22, '100000000005', 'Nicole', 'Lopez', 'Flores', '0000-00-00', 'nicole.flores@student.example.com', '2026-06-03 07:17:25', '2026-06-03 07:17:25'),
(97, 312, 22, '100000000006', 'Joshua', 'Villanueva', 'Cruz', '0000-00-00', 'joshua.cruz@student.example.com', '2026-06-03 07:17:26', '2026-06-03 07:17:26'),
(98, 313, 22, '100000000007', 'Sophia', 'Aquino', 'Bautista', '0000-00-00', 'sophia.bautista@student.example.com', '2026-06-03 07:17:26', '2026-06-03 07:17:26'),
(99, 314, 22, '100000000008', 'Daniel', 'Castillo', 'Navarro', '0000-00-00', 'daniel.navarro@student.example.com', '2026-06-03 07:17:26', '2026-06-03 07:17:26'),
(100, 315, 22, '100000000009', 'Christine', 'Morales', 'Rivera', '0000-00-00', 'christine.rivera@student.example.com', '2026-06-03 07:17:26', '2026-06-03 07:17:26'),
(101, 316, 22, '100000000010', 'Patrick', 'Fernandez', 'Lim', '0000-00-00', 'patrick.lim@student.example.com', '2026-06-03 07:17:26', '2026-06-03 07:17:26'),
(102, 318, 23, '12921145321', 'Raffy', 'Nonong', 'Gogo', '2014-02-09', 'raffy@gmail.com', '2026-06-03 07:22:31', '2026-06-03 07:57:00'),
(103, 319, 23, '12921123425', 'Carla', 'Mocam', 'Pagandahan', '2014-05-21', 'carla@gmail.com', '2026-06-03 07:22:32', '2026-06-03 07:58:06'),
(104, 320, 29, '2026-0001', 'Carlo', 'Ramos', 'Flores', '2010-05-05', 'carlo.flores@email.com', '2026-06-03 07:22:51', '2026-06-03 07:25:04'),
(105, 321, 29, '2026-0002', 'Sophia', 'Villanueva', 'Cruz', '2010-07-03', 'sophia.cruz@email.com', '2026-06-03 07:22:51', '2026-06-03 07:24:49'),
(106, 322, 29, '2026-0003', 'Miguel', 'Torres', 'Santos', '2010-09-09', 'miguel.santos@email.com', '2026-06-03 07:22:52', '2026-06-03 07:25:49'),
(107, 323, 29, '2026-0004', 'Angela', 'Navarro', 'Reyes', '2010-10-10', 'angela.reyes@email.com', '2026-06-03 07:22:52', '2026-06-03 07:25:35'),
(108, 324, 29, '2026-0005', 'Daniel', 'Mercado', 'Garcia', '2010-06-06', 'daniel.garcia@email.com', '2026-06-03 07:22:52', '2026-06-03 07:25:18'),
(109, 330, 31, '128299220043', 'Daniel', 'Balbarino', 'Adlawon', '0000-00-00', '129323@deped.gov.ph', '2026-06-03 07:26:43', '2026-06-03 07:26:43'),
(110, 331, 31, '136546220074', 'Stephen Clark', 'Poria', 'Benavidez', '0000-00-00', '129323@deped.gov.ph', '2026-06-03 07:26:43', '2026-06-03 07:26:43'),
(111, 332, 31, '129323220006', 'John Dave', 'Bandigan', 'Imperial', '0000-00-00', '129323@deped.gov.ph', '2026-06-03 07:26:44', '2026-06-03 07:26:44'),
(112, 333, 36, '129373190011', 'RYAN JAMES', ' VICENTE', 'AMPILANON', '2017-01-03', 'ryanvicente@gmail.com', '2026-06-03 07:27:42', '2026-06-03 07:27:42'),
(113, 334, 26, '12345678000', 'LIGAYA', 'M.', 'AGUDOYUN', '2026-06-01', '', '2026-06-03 07:28:57', '2026-06-03 07:28:57'),
(114, 335, 26, '12345678009', 'JOY', 'L.', 'LATIBAN', '2026-06-01', '', '2026-06-03 07:29:56', '2026-06-03 07:29:56'),
(115, 336, 28, '2026-01', 'Harold', 'Tomas', 'Aguilon', '0000-00-00', 'Harold.Aguilon@email.com', '2026-06-03 07:31:08', '2026-06-03 07:31:08'),
(116, 337, 28, '2026-02', 'Jeyboy', 'Reyes', 'Aquino', '0000-00-00', 'Jeyboy.Aquino@email.com', '2026-06-03 07:31:08', '2026-06-03 07:31:08'),
(117, 338, 28, '2026-03', 'Romel', 'Garcia', 'Bagwas', '0000-00-00', 'Romel.Bagwa@email.com', '2026-06-03 07:31:08', '2026-06-03 07:31:08'),
(118, 339, 28, '2026-04', 'John Vincent', 'Mendoza', 'Banoo n', '0000-00-00', 'John.Banoon@email.com', '2026-06-03 07:31:08', '2026-06-03 07:31:08'),
(119, 340, 28, '2026-05', 'Jaypaul', 'Ram', 'Bantang', '0000-00-00', 'jaypaul.bantang@email.com', '2026-06-03 07:31:09', '2026-06-03 07:31:09'),
(120, 346, 25, '129246150003', 'Cheska Mae', 'Parantar', 'Englisa', '0000-00-00', '', '2026-06-03 07:31:37', '2026-06-03 07:31:37'),
(121, 347, 25, '129246150046', 'Austin', 'Baby', 'De Castro', '0000-00-00', '', '2026-06-03 07:31:38', '2026-06-03 07:31:38'),
(122, 348, 25, '129214130002', 'Xachzna', 'Alvar', 'Del Monte', '0000-00-00', '', '2026-06-03 07:31:38', '2026-06-03 07:31:38'),
(123, 349, 25, '129246160002', 'Julio Javier', 'Del Monte', 'Cabras', '0000-00-00', '', '2026-06-03 07:31:38', '2026-06-03 07:31:38'),
(124, 350, 25, '129246150008', 'Aiarah', 'Tipudan', 'Dagami', '0000-00-00', '', '2026-06-03 07:31:38', '2026-06-03 07:31:38'),
(125, 351, 36, '129462220051', 'YOHANNE JAY-', 'N/A', 'BANGCAYAON', '2017-03-07', 'yohanne@gmail.gov.ph', '2026-06-03 07:31:46', '2026-06-03 07:31:46'),
(126, 357, 42, '1', 'Ariel', 'Dela', 'Cruz', '0000-00-00', 'ariel.cruz@email.com', '2026-06-03 07:32:27', '2026-06-03 07:32:27'),
(127, 358, 42, '2', 'Samantha', 'Santos', 'Reyes', '0000-00-00', 'samantha.reyes@email.com', '2026-06-03 07:32:27', '2026-06-03 07:32:27'),
(128, 359, 42, '3', 'Jones', 'Garcia', 'Lim', '0000-00-00', 'jones.lim@email.com', '2026-06-03 07:32:27', '2026-06-03 07:32:27'),
(129, 360, 42, '4', 'Grace', 'Mendoza', 'Tan', '0000-00-00', 'grace.tan@email.com', '2026-06-03 07:32:27', '2026-06-03 07:32:27'),
(130, 362, 30, '465521180017', 'Peter', 'Cetera', 'Dela Cruz', '0000-00-00', 'delacruz@email.com', '2026-06-03 07:36:32', '2026-06-03 07:36:32'),
(131, 363, 30, '465521180018', 'Diana', 'Lee', 'Ross', '0000-00-00', 'reyes@email.com', '2026-06-03 07:36:32', '2026-06-03 07:36:32'),
(132, 364, 30, '465521180019', 'Celine', 'Roxas', 'Dion', '0000-00-00', 'lim@email.com', '2026-06-03 07:36:33', '2026-06-03 07:36:33'),
(133, 365, 30, '465521180020', 'Elvis', 'Sanchez', 'Presley', '0000-00-00', 'tan@email.com', '2026-06-03 07:36:33', '2026-06-03 07:36:33'),
(134, 366, 30, '465521180021', 'Frank', 'Boaz', 'Sinatra', '0000-00-00', 'ong@email.com', '2026-06-03 07:36:33', '2026-06-03 07:36:33'),
(135, 367, 30, '465521180022', 'Mariah', 'Pilar', 'Carey', '0000-00-00', 'chua@email.com', '2026-06-03 07:36:33', '2026-06-03 07:36:33'),
(136, 368, 30, '465521180023', 'Freddie', 'Drilon', 'Mercury', '0000-00-00', 'co@email.com', '2026-06-03 07:36:33', '2026-06-03 07:36:33'),
(137, 369, 30, '465521180024', 'Miriam', 'Defensor', 'Santiago', '0000-00-00', 'lee@email.com', '2026-06-03 07:36:33', '2026-06-03 07:36:33'),
(138, 370, 30, '465521180025', 'Juan', 'Ponce', 'Enrile', '0000-00-00', 'ho@email.com', '2026-06-03 07:36:33', '2026-06-03 07:36:33'),
(139, 371, 41, '1234566778', 'Maria', 'Ong', 'Wang', '2005-08-06', '', '2026-06-03 07:36:52', '2026-06-03 07:36:52'),
(140, 372, 33, '5', 'Joseph', 'Lopez', 'Makiling', '0008-12-01', 'joseph.makiling@email.com', '2026-06-03 07:38:01', '2026-06-03 07:38:01'),
(142, 374, 44, '129299', 'KYLE JOHAMM', 'HAOM', 'YLANAN', '2009-10-16', 'kyle.ylanan@deped.gov.ph', '2026-06-03 07:40:50', '2026-06-03 07:40:50'),
(143, 376, 44, '2025-000-1', 'KYLE JOHANN', 'HAOM', 'YLANAN', '0000-00-00', 'kyle.ylanan@gmail.com', '2026-06-03 07:41:59', '2026-06-03 07:41:59'),
(144, 377, 44, '2025-000-2', 'MARIE', 'FE', 'CABALLES', '0000-00-00', 'marie.caballes@gmail.com', '2026-06-03 07:41:59', '2026-06-03 07:41:59'),
(145, 378, 44, '2025-000-3', 'ROGER', 'SO', 'TE', '0000-00-00', 'roger.te@deped.gov.ph', '2026-06-03 07:41:59', '2026-06-03 07:41:59'),
(146, 379, 44, '2025-000-4', 'LYCA', 'MIANO', 'OSOL', '0000-00-00', 'lyca.osol@deped.gov.ph', '2026-06-03 07:41:59', '2026-06-03 07:41:59'),
(147, 380, 44, '2025-000-5', 'ANGEL', 'NO', 'ROCACORBA', '0000-00-00', 'angel.rocacroba@gmail.com', '2026-06-03 07:41:59', '2026-06-03 07:41:59'),
(148, 382, 27, '1295636591751', 'JOEY', '', 'DELA CRUZ', '2006-01-03', '', '2026-06-03 07:43:08', '2026-06-03 07:43:08'),
(149, 384, 27, '1295636591752', 'KERK', '', 'MATER', '2007-02-03', '', '2026-06-03 07:44:00', '2026-06-03 07:44:00'),
(150, 386, 27, '1295636591753', 'MARK', '', 'GAYANGAN', '2007-03-03', '', '2026-06-03 07:44:49', '2026-06-03 07:44:49'),
(151, 387, 35, '1.29204E+11', 'Juan', 'Dela', 'Cruz', '0000-00-00', 'juan.cruz@email.com', '2026-06-03 07:47:32', '2026-06-03 07:47:32'),
(152, 388, 35, '1.29204E+13', 'Maria', 'Santos', 'Reyes', '0000-00-00', 'maria.reyes@email.com', '2026-06-03 07:47:32', '2026-06-03 07:47:32'),
(153, 389, 35, '1.29205E+11', 'Ana', 'Mendoza', 'Tan', '0000-00-00', 'ana.tan@email.com', '2026-06-03 07:47:32', '2026-06-03 07:47:32'),
(154, 392, 24, '123', 'JOHN RHAFAEL', 'CARPENTERO', 'ALBARACIN', '0000-00-00', 'johnrhafael.albaracin@gmail.com', '2026-06-03 07:48:33', '2026-06-03 07:48:33'),
(155, 393, 24, '122', 'JOSHUA ERL', 'ANCERO', 'BANTILES', '0000-00-00', 'joshuaerl.bantiles@gmail.com', '2026-06-03 07:48:33', '2026-06-03 07:48:33'),
(156, 394, 24, '121', 'REIN ZEON', '', 'CAPIN', '0000-00-00', 'reinzeon.capin@gmail.com', '2026-06-03 07:48:33', '2026-06-03 07:48:33'),
(157, 395, 24, '120', 'MEL JACOB', 'SILONGAN', 'JOVES', '0000-00-00', 'meljacob.joves@gmail.com', '2026-06-03 07:48:33', '2026-06-03 07:48:33'),
(158, 397, 36, '129462220087', 'PRINCESS AJ', 'N/A', 'AWING', '2017-05-02', 'princess@gmail.com.ph', '2026-06-03 07:50:59', '2026-06-03 07:50:59'),
(159, 401, 45, '129195000323', 'Lenie', 'Bat', 'Bakay', '2010-06-03', 'lenie.bakay@gmail.com', '2026-06-03 07:53:09', '2026-06-03 07:53:09'),
(160, 402, 23, '12921134567', 'TONE JR.', 'ALVAR', 'LEMENIO', '2014-05-12', '', '2026-06-03 07:53:18', '2026-06-03 07:53:18'),
(161, 403, 43, 'L001', 'Ginalyn', 'Dela', 'Cruz', '0000-00-00', 'gina.cruz@gmail.com', '2026-06-03 07:53:51', '2026-06-03 07:53:51'),
(162, 404, 43, 'L002', 'Carlo', 'Santos', 'Reyes', '0000-00-00', 'carla.reyes@email.com', '2026-06-03 07:53:51', '2026-06-03 07:53:51'),
(163, 405, 43, 'L003', 'Hana', 'Garcia', 'Lim', '0000-00-00', 'hannah.garcia@email.com', '2026-06-03 07:53:51', '2026-06-03 07:53:51'),
(164, 406, 43, 'L004', 'Carlota', 'Mendoza', 'Tan', '0000-00-00', 'caridad.mendoza@gmail.com', '2026-06-03 07:53:51', '2026-06-03 07:53:51'),
(165, 407, 43, 'L005', 'Thomas', 'Dizon', 'Ong', '0000-00-00', 'tomas.dizon@gmail.com', '2026-06-03 07:53:51', '2026-06-03 07:53:51'),
(166, 411, 45, '129195000324', 'Botay', 'Memoracion', 'Lino', '2011-02-10', 'lino.botay@gmail.com', '2026-06-03 07:56:56', '2026-06-03 07:56:56'),
(167, 412, 46, '129157131870', 'Kyle', 'Ubat', 'Abad', '2014-06-06', '129157@deped.gov.ph', '2026-06-03 07:58:36', '2026-06-03 07:58:36'),
(168, 414, 23, '12921123411', 'NERVANA', 'CAPALIT', 'BARBA', '2014-12-09', '', '2026-06-03 08:00:00', '2026-06-03 08:00:00'),
(169, 450, 19, '12345685222', 'abcdef', 'abcdef', 'abcdef', '2026-06-05', 'abcdef@ghmai.com', '2026-06-05 09:03:22', '2026-06-05 09:03:22');

-- --------------------------------------------------------

--
-- Table structure for table `students`
--

CREATE TABLE `students` (
  `id` int UNSIGNED NOT NULL,
  `user_id` int UNSIGNED NOT NULL,
  `school_id` int UNSIGNED DEFAULT '1',
  `lrn` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `student_id` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `system_type` enum('deped','ched') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'deped',
  `gender` enum('Male','Female') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `birthdate` date DEFAULT NULL,
  `birth_place` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `guardian_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `guardian_contact` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `guardian_user_id` int UNSIGNED DEFAULT NULL,
  `grade_level_id` int UNSIGNED DEFAULT NULL,
  `strand_id` int UNSIGNED DEFAULT NULL,
  `program_id` int UNSIGNED DEFAULT NULL,
  `year_level` tinyint DEFAULT NULL,
  `admission_date` date DEFAULT NULL,
  `status` enum('active','inactive','graduated','dropped','transferred') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'active',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `students`
--

INSERT INTO `students` (`id`, `user_id`, `school_id`, `lrn`, `student_id`, `system_type`, `gender`, `birthdate`, `birth_place`, `address`, `guardian_name`, `guardian_contact`, `guardian_user_id`, `grade_level_id`, `strand_id`, `program_id`, `year_level`, `admission_date`, `status`, `created_at`, `updated_at`) VALUES
(10, 139, 17, 'LRN-139-1780449869', 'STU-139-1780449869', 'deped', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'active', '2026-06-03 09:24:29', '2026-06-03 01:24:29'),
(11, 233, 24, 'LRN-233-1780471936', 'STU-233-1780471936', 'deped', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'active', '2026-06-03 15:32:16', '2026-06-03 07:32:16'),
(12, 408, 27, 'LRN-408-1780473382', 'STU-408-1780473382', 'deped', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'active', '2026-06-03 15:56:22', '2026-06-03 07:56:22'),
(13, 154, 17, 'LRN-154-1780658293', 'STU-154-1780658293', 'deped', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'active', '2026-06-05 19:18:13', '2026-06-05 19:18:13');

-- --------------------------------------------------------

--
-- Table structure for table `student_grades`
--

CREATE TABLE `student_grades` (
  `id` int UNSIGNED NOT NULL,
  `enrollment_id` int UNSIGNED NOT NULL,
  `class_program_id` int UNSIGNED NOT NULL,
  `semester_id` int UNSIGNED DEFAULT NULL,
  `system_type` enum('deped','ched') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'deped',
  `ww_score` decimal(6,2) DEFAULT NULL,
  `pt_score` decimal(6,2) DEFAULT NULL,
  `qa_score` decimal(6,2) DEFAULT NULL,
  `initial_grade` decimal(6,2) DEFAULT NULL,
  `transmuted_grade` int DEFAULT NULL,
  `ched_raw_grade` decimal(6,2) DEFAULT NULL,
  `ched_gpa` decimal(3,2) DEFAULT NULL,
  `final_grade` decimal(6,2) DEFAULT NULL,
  `remarks` enum('passed','failed','incomplete','dropped','in_progress') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT 'in_progress',
  `is_locked` tinyint(1) NOT NULL DEFAULT '0',
  `approved_by` int UNSIGNED DEFAULT NULL,
  `approved_at` datetime DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `subjects`
--

CREATE TABLE `subjects` (
  `id` int UNSIGNED NOT NULL,
  `school_id` int UNSIGNED DEFAULT NULL,
  `teacher_id` int DEFAULT NULL,
  `code` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `cover_photo` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `program_id` int UNSIGNED DEFAULT NULL,
  `year_level` tinyint DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `subjects`
--

INSERT INTO `subjects` (`id`, `school_id`, `teacher_id`, `code`, `description`, `cover_photo`, `program_id`, `year_level`, `status`, `created_at`) VALUES
(56, 0, NULL, 'AP 4', 'Araling Panlipunan 4', NULL, 76, 4, 1, '2026-06-04 08:18:58'),
(57, 0, NULL, 'AP 6', 'Araling Panlipunan 6', NULL, 45, 6, 1, '2026-06-04 08:19:19'),
(58, 0, NULL, 'AP 10', 'Araling Panlipunan 10', NULL, 107, 10, 1, '2026-06-04 08:19:36');

-- --------------------------------------------------------

--
-- Table structure for table `subject_teachers`
--

CREATE TABLE `subject_teachers` (
  `id` int NOT NULL,
  `subject_id` int NOT NULL,
  `user_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Dumping data for table `subject_teachers`
--

INSERT INTO `subject_teachers` (`id`, `subject_id`, `user_id`) VALUES
(33, 37, 140),
(34, 38, 140),
(35, 39, 192),
(43, 40, 202),
(37, 41, 202),
(45, 42, 246),
(40, 43, 232),
(39, 43, 233),
(41, 43, 286),
(42, 44, 352),
(44, 45, 343),
(46, 46, 202),
(55, 47, 342),
(49, 48, 410),
(50, 49, 261),
(51, 50, 408),
(52, 51, 212),
(53, 52, 213),
(54, 53, 234),
(56, 55, 439);

-- --------------------------------------------------------

--
-- Table structure for table `transmutation_table`
--

CREATE TABLE `transmutation_table` (
  `id` int UNSIGNED NOT NULL,
  `initial_grade_min` decimal(5,2) NOT NULL,
  `initial_grade_max` decimal(5,2) NOT NULL,
  `transmuted_grade` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `transmutation_table`
--

INSERT INTO `transmutation_table` (`id`, `initial_grade_min`, `initial_grade_max`, `transmuted_grade`) VALUES
(1, 100.00, 100.00, 100),
(2, 98.40, 99.99, 99),
(3, 96.80, 98.39, 98),
(4, 95.20, 96.79, 97),
(5, 93.60, 95.19, 96),
(6, 92.00, 93.59, 95),
(7, 90.40, 91.99, 94),
(8, 88.80, 90.39, 93),
(9, 87.20, 88.79, 92),
(10, 85.60, 87.19, 91),
(11, 84.00, 85.59, 90),
(12, 82.40, 83.99, 89),
(13, 80.80, 82.39, 88),
(14, 79.20, 80.79, 87),
(15, 77.60, 79.19, 86),
(16, 76.00, 77.59, 85),
(17, 74.40, 75.99, 84),
(18, 72.80, 74.39, 83),
(19, 71.20, 72.79, 82),
(20, 69.60, 71.19, 81),
(21, 68.00, 69.59, 80),
(22, 66.40, 67.99, 79),
(23, 64.80, 66.39, 78),
(24, 63.20, 64.79, 77),
(25, 61.60, 63.19, 76),
(26, 60.00, 61.59, 75),
(27, 56.00, 59.99, 74),
(28, 52.00, 55.99, 73),
(29, 48.00, 51.99, 72),
(30, 44.00, 47.99, 71),
(31, 40.00, 43.99, 70),
(32, 36.00, 39.99, 69),
(33, 32.00, 35.99, 68),
(34, 28.00, 31.99, 67),
(35, 24.00, 27.99, 66),
(36, 20.00, 23.99, 65),
(37, 16.00, 19.99, 64),
(38, 12.00, 15.99, 63),
(39, 8.00, 11.99, 62),
(40, 4.00, 7.99, 61),
(41, 0.00, 3.99, 60);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int UNSIGNED NOT NULL,
  `role_id` int UNSIGNED NOT NULL DEFAULT '4',
  `school_id` int UNSIGNED DEFAULT NULL,
  `first_name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `middle_name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `last_name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `suffix` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `avatar` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `last_login` datetime DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `role_id`, `school_id`, `first_name`, `middle_name`, `last_name`, `suffix`, `email`, `password`, `phone`, `avatar`, `status`, `last_login`, `created_at`, `updated_at`) VALUES
(1, 1, NULL, 'Super', NULL, 'Admin', NULL, 'admin@lms.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NULL, NULL, 1, '2026-06-05 09:34:16', '2026-04-20 20:36:52', '2026-06-05 09:34:16'),
(138, 2, 17, 'School', NULL, 'Admin', NULL, 'joselito.edong@gmail.com', '$2y$10$wniZRN1TBj/3C3biypkRpuH3vI2OrWqTqt/MgO/jAlbEnTf1iQReS', NULL, NULL, 1, '2026-06-05 19:31:12', '2026-06-03 09:17:09', '2026-06-05 19:31:12'),
(139, 5, 17, 'NANNY', 'GO', 'WANG', NULL, '1234567890', '$2y$10$WMFGrHpNRU25ZRo42gg/melwkmjmD9MAE096TI3pcl4myBCZTd7Ay', NULL, NULL, 1, '2026-06-05 09:27:24', '2026-06-03 09:18:39', '2026-06-05 09:27:24'),
(140, 4, 17, 'Luz', NULL, 'Paron', NULL, 'luz.paron@gmail.com', '$2y$10$vGlPKgEVvzsfRZPysHeFBOV2xjFwfyn1QVQlyvPOGsCRYpMw42OyO', NULL, NULL, 1, NULL, '2026-06-03 01:28:30', '2026-06-03 01:28:30'),
(141, 2, 18, 'School', NULL, 'Admin', NULL, 'joselito.edong@softtechservices.net', '$2y$10$gLSXKcamr8JAeIvG5cbx2uhcICqi4Nf4OZ4T3/lT2IvCn7URRDvee', NULL, NULL, 1, '2026-06-03 13:33:04', '2026-06-03 12:53:18', '2026-06-03 05:33:04'),
(142, 2, 19, 'School', NULL, 'Admin', NULL, 'artadybasty@gmail.com', '$2y$10$RReUORgHMtKJKhDEczI0lu2M19D7GlLP.74WmGlFW4XqZh6skkJIe', NULL, NULL, 1, '2026-06-05 16:52:09', '2026-06-03 13:59:19', '2026-06-05 16:52:09'),
(143, 5, 17, 'Juan', 'Dela', 'Cruz', NULL, '2025-0001', '$2y$10$GuBxPJrom25aBph7ZGAqCe5cscGf1a5iT21HR0Zt56TaXLLqWJBcK', NULL, NULL, 1, NULL, '2026-06-03 14:02:37', '2026-06-03 14:02:37'),
(144, 5, 17, 'Maria', 'Santos', 'Reyes', NULL, '2025-0002', '$2y$10$EvQL2zfx3vE6/Vr34lGy9ughKEfUSBP4G5teddS2wPa3G3FsCB5M.', NULL, NULL, 1, NULL, '2026-06-03 14:02:37', '2026-06-03 14:02:37'),
(145, 5, 17, 'Jose', 'Garcia', 'Lim', NULL, '2025-0003', '$2y$10$XOsJdcruZqZGTIHUsU.KJelI2SyaRVJFCJOyDE/iqfpGCGYINQfDa', NULL, NULL, 1, NULL, '2026-06-03 14:02:37', '2026-06-03 14:02:37'),
(146, 5, 17, 'Ana', 'Mendoza', 'Tan', NULL, '2025-0004', '$2y$10$Whmf.eSKHVd/mcWG4Hfcdu2HoVY4.e4EQHTBvSWj8rU1TmyIvNC0e', NULL, NULL, 1, NULL, '2026-06-03 14:02:38', '2026-06-03 14:02:38'),
(147, 5, 17, 'Carlos', 'Dizon', 'Ong', NULL, '2025-0005', '$2y$10$wP9/ne9uqw5RTvrq1ShV.OWyxOyumao75GnGS2gaGCM4D6dCdgdyS', NULL, NULL, 1, NULL, '2026-06-03 14:02:38', '2026-06-03 14:02:38'),
(148, 5, 17, 'Patricia', 'Ng', 'Chua', NULL, '2025-0006', '$2y$10$PrO7Sc51OmyxuVlDRROQC./CR1oY4ngFKiLH/67kHn8hFdW8V7mGq', NULL, NULL, 1, NULL, '2026-06-03 14:02:38', '2026-06-03 14:02:38'),
(149, 5, 17, 'Michael', 'Reyes', 'Co', NULL, '2025-0007', '$2y$10$lugv5k2Mfmis/IZPuAyo5.nM.TjEcw2ztWxWxSazgKYPzQHdLQIfW', NULL, NULL, 1, NULL, '2026-06-03 14:02:38', '2026-06-03 14:02:38'),
(150, 5, 17, 'Elizabeth', 'Sy', 'Lee', NULL, '2025-0008', '$2y$10$2ZwWutB03v/uS1DHnZwqv.LtQh/mUeWbtHqZAAhoJIxokN.7TJy4i', NULL, NULL, 1, NULL, '2026-06-03 14:02:38', '2026-06-03 14:02:38'),
(151, 5, 17, 'David', 'Go', 'Ho', NULL, '2025-0009', '$2y$10$sBt1/wx2PanIeH.bpU6bfe3EXFZ4ss2kQ.V9RCFicdfGcU1hphEg.', NULL, NULL, 1, NULL, '2026-06-03 14:02:38', '2026-06-03 14:02:38'),
(152, 5, 17, 'Sarah', 'Cheng', 'Wong', NULL, '2025-0010', '$2y$10$ewjMElLcJ4pNbOqG6QH9TOXzBURaANVE/Wo0o/ndPS.K3rUuhIihi', NULL, NULL, 1, NULL, '2026-06-03 14:02:38', '2026-06-03 14:02:38'),
(153, 5, 17, 'Robert', 'Tan', 'Lau', NULL, '2025-0011', '$2y$10$0tU89Qz5sxQ74BEdB921Hunjjs6AXPmKmOxNQl54xoeJndIVQi8B2', NULL, NULL, 1, NULL, '2026-06-03 14:02:38', '2026-06-03 14:02:38'),
(154, 5, 17, 'Jennifer', 'Lim', 'Chan', '', 'abc@abc.com', '$2y$10$hChcVKvrtCgyhNKe8yV0he6Fq10FvjezfldDdQJjo7kUc0W6KQDxW', '', NULL, 1, '2026-06-05 19:18:13', '2026-06-03 14:02:38', '2026-06-05 19:18:13'),
(155, 5, 17, 'William', 'Huang', 'Wu', NULL, '2025-0013', '$2y$10$QRpQq25g/Q0o5VVFL5hFCu19vN/./XLYNmxnfG.7vg4r4FnLJfMsG', NULL, NULL, 1, NULL, '2026-06-03 14:02:38', '2026-06-03 14:02:38'),
(156, 5, 17, 'Amanda', 'Zhao', 'Liu', NULL, '2025-0014', '$2y$10$hX3ypVDM5aJqamXNufqVA.xUW154jtshMiaaVsF8UMfOM0UsyMkJm', NULL, NULL, 1, NULL, '2026-06-03 14:02:38', '2026-06-03 14:02:38'),
(157, 5, 17, 'Christopher', 'Wang', 'Xu', NULL, '2025-0015', '$2y$10$93w8vO2hl49VCtYIU5vTu.9txleGH.RCF2M8WKxcXdw0Nho5YcmnG', NULL, NULL, 1, NULL, '2026-06-03 14:02:38', '2026-06-03 14:02:38'),
(158, 5, 17, 'Jessica', 'Li', 'Yang', NULL, '2025-0016', '$2y$10$PF3pZwp2DV30lsuvGwYFuufzr7IsZa4Ac4AFaB3Og5XnWQZec5TzW', NULL, NULL, 1, NULL, '2026-06-03 14:02:39', '2026-06-03 14:02:39'),
(159, 5, 17, 'Daniel', 'Zhou', 'Zhang', NULL, '2025-0017', '$2y$10$FaZkfL1OFHRRl7VTtr420uypdJT9/ATXDZoidBUHFLvQcaFBjN/kK', NULL, NULL, 1, NULL, '2026-06-03 14:02:39', '2026-06-03 14:02:39'),
(160, 5, 17, 'Michelle', 'Chen', 'Lin', NULL, '2025-0018', '$2y$10$J0nHbPAey0kriCLpTmw5L.sgQpHoQkysMmFdqEqCK0wcbTHrBZ8l.', NULL, NULL, 1, NULL, '2026-06-03 14:02:39', '2026-06-03 14:02:39'),
(161, 5, 17, 'Matthew', 'Wu', 'Zhao', NULL, '2025-0019', '$2y$10$yUZDsd9OcKitJeoebawVG.0N3Q.f6DF7rrZYl6zDTrB/n1rH5sxUm', NULL, NULL, 1, NULL, '2026-06-03 14:02:39', '2026-06-03 14:02:39'),
(162, 5, 17, 'Laura', 'Xu', 'Wang', NULL, '2025-0020', '$2y$10$xI3SnZTtLeRrOeldYoABuetaTT8o7YzuCpkXoKbI9SDgYFlVf7nT6', NULL, NULL, 1, NULL, '2026-06-03 14:02:39', '2026-06-03 14:02:39'),
(163, 5, 17, 'Andrew', 'Sun', 'Li', NULL, '2025-0021', '$2y$10$TiAhQli2nR0Njiu06Tsr.eqYuz5qR0cKuO4sEducB2SMuv.mHHkme', NULL, NULL, 1, NULL, '2026-06-03 14:02:39', '2026-06-03 14:02:39'),
(164, 5, 17, 'Stephanie', 'Moon', 'Kim', NULL, '2025-0022', '$2y$10$xPQAJOu9BH2W9w5/rz0JSes5B3ApHkHmYtpndt3W414Oo61nMTfLO', NULL, NULL, 1, NULL, '2026-06-03 14:02:39', '2026-06-03 14:02:39'),
(165, 5, 17, 'Joshua', 'Park', 'Lee', NULL, '2025-0023', '$2y$10$VZXudn.mzBGjasSb0eG3IujF1FqTo0uk8xRV2mrZ2XIUO92uhxMBC', NULL, NULL, 1, NULL, '2026-06-03 14:02:39', '2026-06-03 14:02:39'),
(166, 5, 17, 'Emily', 'Choi', 'Yoon', NULL, '2025-0024', '$2y$10$B7UwtMzgaFsWFdAxLcCmQ.nlBwXa9.Bjd8IzGBmcUqCXrXhc0uDrS', NULL, NULL, 1, NULL, '2026-06-03 14:02:39', '2026-06-03 14:02:39'),
(167, 5, 17, 'Brian', 'Kang', 'Seo', NULL, '2025-0025', '$2y$10$Plk2Aj96.PSuXoqo0f8i2uDVheETKdF7Dd9geEB1ag24nCKzZyTde', NULL, NULL, 1, NULL, '2026-06-03 14:02:39', '2026-06-03 14:02:39'),
(168, 4, 17, 'John', NULL, 'Smith', NULL, 'john.smith1@domain.com', '$2y$10$rrhezjfsqE76C8v32HwzSeDmmN2um.cGIR6NRZYcCunPsqSKmMVlW', NULL, NULL, 1, NULL, '2026-06-03 06:06:21', '2026-06-03 06:06:21'),
(169, 4, 17, 'Jane', NULL, 'Johnson', NULL, 'jane.johnson2@domain.com', '$2y$10$vgwmzqM3T/6NHtsqwVp7nu2n.JMQXZ.t2g0HW.ZTw2bBzNC2mtW9S', NULL, NULL, 1, NULL, '2026-06-03 06:06:21', '2026-06-03 06:06:21'),
(170, 4, 17, 'Michael', NULL, 'Williams', NULL, 'michael.williams3@domain.com', '$2y$10$U7WkNgO8pH8WoEuLDBrFKuADEtHrFQQ8yT/rfTJeHEjfHLvDuOOLu', NULL, NULL, 1, NULL, '2026-06-03 06:06:22', '2026-06-03 06:06:22'),
(171, 4, 17, 'Sarah', NULL, 'Brown', NULL, 'sarah.brown4@domain.com', '$2y$10$hFZWvI91MlvzwEH5NMhVLeTBQeiicCcEincn0jrK2sA8lhnLco1bS', NULL, NULL, 1, NULL, '2026-06-03 06:06:22', '2026-06-03 06:06:22'),
(172, 4, 17, 'David', NULL, 'Jones', NULL, 'david.jones5@domain.com', '$2y$10$28pSx1B2y0MUzWIEoGGarO.qdEVLI.kgIKfzJCQAddfU27QkEJRBy', NULL, NULL, 1, NULL, '2026-06-03 06:06:22', '2026-06-03 06:06:22'),
(173, 4, 17, 'Emily', NULL, 'Garcia', NULL, 'emily.garcia6@domain.com', '$2y$10$MZ0AtH6TCVs7/TYLoE4vb.rwu3WHAF4HEwXYK7Z8kFWJ.ap57srA6', NULL, NULL, 1, NULL, '2026-06-03 06:06:22', '2026-06-03 06:06:22'),
(174, 4, 17, 'Robert', NULL, 'Miller', NULL, 'robert.miller7@domain.com', '$2y$10$Nd1YE75MqeraXPwKr3PdLuMgGPFIQ9V9CAs7m2y5dJ9VNsfAWVLn6', NULL, NULL, 1, NULL, '2026-06-03 06:06:22', '2026-06-03 06:06:22'),
(175, 4, 17, 'Lisa', NULL, 'Davis', NULL, 'lisa.davis8@domain.com', '$2y$10$h9ha5vDLGM9Oyp1i7cpkJ.GSRi98DhMyq04XFuMkROiG5bSkaCh3G', NULL, NULL, 1, NULL, '2026-06-03 06:06:22', '2026-06-03 06:06:22'),
(176, 4, 17, 'William', NULL, 'Rodriguez', NULL, 'william.rodriguez9@domain.com', '$2y$10$u8gExqjIjJKyL5EuRJrMkeH8/pFBdR5mfJ.S9WMTtechFWLTwYhKq', NULL, NULL, 1, NULL, '2026-06-03 06:06:22', '2026-06-03 06:06:22'),
(177, 4, 17, 'Jennifer', NULL, 'Martinez', NULL, 'jennifer.martinez10@domain.com', '$2y$10$V2zlNv1C6o.VpkSaR5VP/.PUCtVTsnvbNmzPNTFblYP79LykKJF/W', NULL, NULL, 1, NULL, '2026-06-03 06:06:22', '2026-06-03 06:06:22'),
(178, 4, 17, 'James', NULL, 'Hernandez', NULL, 'james.hernandez11@domain.com', '$2y$10$ezBS7gQntfUu73ccfS.Z.OfewUOCLeWH8CyXEt2x0Tzk/pSGqVATa', NULL, NULL, 1, NULL, '2026-06-03 06:06:22', '2026-06-03 06:06:22'),
(179, 4, 17, 'Amanda', NULL, 'Lopez', NULL, 'amanda.lopez12@domain.com', '$2y$10$xWm1CGGqc6lQPABg/lh5SOvhfXfCKbVq/p/dTBLfQgm1nItjTGb9y', NULL, NULL, 1, NULL, '2026-06-03 06:06:22', '2026-06-03 06:06:22'),
(180, 4, 17, 'Daniel', NULL, 'Gonzalez', NULL, 'daniel.gonzalez13@domain.com', '$2y$10$TR2c9X6wkQPVKFrARcV7ZO58OyJGLBqTkG1hDwIwcQ4qcbY60RPKq', NULL, NULL, 1, NULL, '2026-06-03 06:06:22', '2026-06-03 06:06:22'),
(181, 4, 17, 'Jessica', NULL, 'Wilson', NULL, 'jessica.wilson14@domain.com', '$2y$10$Tf8sk1TanwmAd6Zeb.Hw3.488EdvL2/bcPeQe6./rZdntas/aftGO', NULL, NULL, 1, NULL, '2026-06-03 06:06:22', '2026-06-03 06:06:22'),
(182, 4, 17, 'Matthew', NULL, 'Anderson', NULL, 'matthew.anderson15@domain.com', '$2y$10$zE/fYomTPE1DzMXOXzZV3OhZ1Ik7k20Yi48aQMFhCanaW4Viiv2MS', NULL, NULL, 1, NULL, '2026-06-03 06:06:23', '2026-06-03 06:06:23'),
(183, 4, 17, 'Ashley', NULL, 'Thomas', NULL, 'ashley.thomas16@domain.com', '$2y$10$7eyGpDGrO3f5VKCKlLU1HOCRRPY8sFJg1Vj72vBdxHlMH/GhrITFu', NULL, NULL, 1, NULL, '2026-06-03 06:06:23', '2026-06-03 06:06:23'),
(184, 4, 17, 'Christopher', NULL, 'Taylor', NULL, 'christopher.taylor17@domain.com', '$2y$10$qmR0FBSaUmcAc1wgqex5rOdog8a3KHJR7W4oyyL.qKTFi2E9y/SMS', NULL, NULL, 1, NULL, '2026-06-03 06:06:23', '2026-06-03 06:06:23'),
(185, 4, 17, 'Stephanie', NULL, 'Moore', NULL, 'stephanie.moore18@domain.com', '$2y$10$kE2ThIh/GBiw4CeN7mfziOrxdInyDwgnw3MzLf1JSoc9JzieioLDC', NULL, NULL, 1, NULL, '2026-06-03 06:06:23', '2026-06-03 06:06:23'),
(186, 4, 17, 'Andrew', NULL, 'Jackson', NULL, 'andrew.jackson19@domain.com', '$2y$10$hjekBJk40Hg4FRrpxqHEJODHHro/SnLyhR75/3xGdIXiy.DpYrYci', NULL, NULL, 1, NULL, '2026-06-03 06:06:23', '2026-06-03 06:06:23'),
(187, 4, 17, 'Nicole', NULL, 'Martin', NULL, 'nicole.martin20@domain.com', '$2y$10$Xkkbm/7fiZ/NepEYj7XepOmj4ftX8rzVVHvD6mpK0hMCmzqp5Kb9C', NULL, NULL, 1, NULL, '2026-06-03 06:06:23', '2026-06-03 06:06:23'),
(188, 4, 17, 'Joshua', NULL, 'Lee', NULL, 'joshua.lee21@domain.com', '$2y$10$xH9hhiNk9ErZHKyz5I5a6.4JxeczUkHO4rFhNSQSP6HlkLzLmQo9q', NULL, NULL, 1, NULL, '2026-06-03 06:06:23', '2026-06-03 06:06:23'),
(189, 4, 17, 'Melissa', NULL, 'Perez', NULL, 'melissa.perez22@domain.com', '$2y$10$816kGE2bgMW4.J08kldVrOoHfrpyBi0ccQmN3XweHX3TrJCOJMIXa', NULL, NULL, 1, NULL, '2026-06-03 06:06:23', '2026-06-03 06:06:23'),
(190, 4, 17, 'Ryan', NULL, 'Thompson', NULL, 'ryan.thompson23@domain.com', '$2y$10$AyDSs.fITmW1UiFv0HTy0.oL7gu6EHeYsDE370iCfzkcnWB4GgOjW', NULL, NULL, 1, NULL, '2026-06-03 06:06:23', '2026-06-03 06:06:23'),
(191, 4, 17, 'Elizabeth', NULL, 'White', NULL, 'elizabeth.white24@domain.com', '$2y$10$OZR5MHZWayW.z1Y5VpcHEee55T/v74C97qlUg2vN.kDncLP00VXm.', NULL, NULL, 1, NULL, '2026-06-03 06:06:23', '2026-06-03 06:06:23'),
(192, 4, 17, 'Brandon', NULL, 'Harris', NULL, 'brandon.harris25@domain.com', '$2y$10$QiJ9Wju1AFGHF0BlYhm0.enApGY/rjDltuAwhkG1JNcSZWh9yTh3a', NULL, NULL, 1, NULL, '2026-06-03 06:06:23', '2026-06-03 06:06:23'),
(193, 2, 21, 'School', NULL, 'Admin', NULL, '129324@deped.gov.ph', '$2y$10$gY6NyqK93GiymsGwJHvQe.3n6Q8Utnvdh65FNtZUCsyBB0gCLRj6m', NULL, NULL, 1, '2026-06-03 16:20:53', '2026-06-03 14:47:04', '2026-06-03 08:20:53'),
(194, 2, 20, 'School', NULL, 'Admin', NULL, '129287@deped.gov.ph', '$2y$10$EzSNPeKNcn5XgvOUuxSxje4vH.D3aug4DxKPWbH26tbKqicRpskre', NULL, NULL, 1, '2026-06-03 16:33:44', '2026-06-03 14:47:13', '2026-06-03 08:33:44'),
(195, 2, 24, 'School', NULL, 'Admin', NULL, '129283@deped.gov.ph', '$2y$10$7BVEitZWOti5PcXFDXctgeAaZw.0Xk8K4uEf6Nlx.PrLrz0qeo6Pi', NULL, NULL, 1, '2026-06-03 15:37:48', '2026-06-03 14:51:07', '2026-06-03 07:37:48'),
(196, 2, 22, 'Vecente', NULL, 'Macalua', NULL, 'vecente.macalua@deped.gov.ph', '$2y$10$5D7BV5lfMCNnG53YiJGU1.7BhY88RDKaPXw5R9UxD9POF0jpr8Jfi', NULL, NULL, 1, '2026-06-04 19:02:46', '2026-06-03 14:53:54', '2026-06-04 12:30:19'),
(197, 4, 20, 'HANAH', NULL, 'BURBANO', NULL, 'hanah.burbano@deped.gov.ph', '$2y$10$pUoG5SSlhsucpy1FBSC0HuC54ttsnlVNlhoZgAyokX4bB/Q4HbCoa', NULL, NULL, 1, NULL, '2026-06-03 06:54:18', '2026-06-03 06:54:18'),
(198, 4, 20, 'NOVIE GYNE', NULL, 'CONCON', NULL, 'noviegyne.lim@deped.gov.ph', '$2y$10$WOI5xYUnCRgbDDm6mlOpBebeUxohNXnOFZa.URS3CfnE9CrBuIBsW', NULL, NULL, 1, NULL, '2026-06-03 06:54:18', '2026-06-03 06:54:18'),
(199, 4, 20, 'SHELLA MAE', NULL, 'CRODUA', NULL, 'shellamae.arenas@deped.gov.ph', '$2y$10$BZM58Gb8WwbeAlDRUpB00.mP51ABea523Cufm0JBONVd8bgtxUrMq', NULL, NULL, 1, NULL, '2026-06-03 06:54:18', '2026-06-03 06:54:18'),
(200, 4, 20, 'RETCHIE ', NULL, 'GUTUAL', NULL, 'retchie.gutual@deped.gov.ph', '$2y$10$Am6.hB4aAtEuAGM.J2Vccub0rx9JjfBO4HGx4XCl4jypZ2DgcSdKe', NULL, NULL, 1, NULL, '2026-06-03 06:54:18', '2026-06-03 06:54:18'),
(201, 4, 20, 'NINIA', NULL, 'JESURO', NULL, 'ninia.jesuro@deped.gov.ph', '$2y$10$2QJIr7R81UkSnaXYMUlk4OiC5p/HzkgpBL1mA7wEDisZhIKkptUem', NULL, NULL, 1, NULL, '2026-06-03 06:54:19', '2026-06-03 06:54:19'),
(202, 4, 20, 'JEFREY ', NULL, 'LEGURPA', NULL, 'jefrey.legurpa@deped.gov.ph', '$2y$10$RZorY.iEWOAT9rdxSd1Zve3UECjDlK85OKFqq6BmGJJIoZEsKKt5S', NULL, NULL, 1, NULL, '2026-06-03 06:54:19', '2026-06-03 06:54:19'),
(203, 4, 20, 'SHENA MAE', NULL, 'MAGDADARO', NULL, 'shenamae.amoguis001@deped.gov.ph', '$2y$10$/TMh4lELzADKgTDkojeJreDH3Yo0aSllAQ6PhhqlCrPyXoxw.cnry', NULL, NULL, 1, NULL, '2026-06-03 06:54:19', '2026-06-03 06:54:19'),
(204, 4, 20, 'VIOLETA', NULL, 'MEBRANO', NULL, 'violeta.duran@deped.gov.ph', '$2y$10$fxxi5pUsLnTlHLi/wKLK1u/QyV5JQVeXBRimYBAk0Fjiv5lyIsaGm', NULL, NULL, 1, NULL, '2026-06-03 06:54:19', '2026-06-03 06:54:19'),
(205, 4, 20, 'RITCHIE WARREN', NULL, 'MEBRANO', NULL, 'ritchiewarren.mebrano@deped.gov.ph', '$2y$10$aSfZ6q5i2NOGjTG7fj7upu0mzBl1D15cugZ8wNet8kadRl.Qjx.yO', NULL, NULL, 1, NULL, '2026-06-03 06:54:19', '2026-06-03 06:54:19'),
(206, 4, 20, 'NEGRIMINDA', NULL, 'RAMIA', NULL, 'negriminda.ramia@deped.gov.ph', '$2y$10$qMfeIl1tXR7piEuh.g02zeZ4md6v8uM6S6fURQ0cVs2ons9FquFJG', NULL, NULL, 1, NULL, '2026-06-03 06:54:19', '2026-06-03 06:54:19'),
(207, 4, 20, 'GUALBERTO', NULL, 'REMOLLO', NULL, 'gualberto.remollo001@deped.gov.ph', '$2y$10$2cch0C0yyOuKpdjByH7MzuuP0qlvax3CVqbeE466dU7icoJ0tvV4S', NULL, NULL, 1, NULL, '2026-06-03 06:54:19', '2026-06-03 06:54:19'),
(208, 4, 20, 'MIRASOL', NULL, 'SAMBULOT', NULL, 'mirasol.sambulot@deped.gov.ph', '$2y$10$SPBWcpwRcbak5TNcJ4Rq0e93DgxeKn2khU24OMKLaAVbGg3md4a4u', NULL, NULL, 1, NULL, '2026-06-03 06:54:19', '2026-06-03 06:54:19'),
(209, 4, 20, 'RHEALYN', NULL, 'SUMAYANG ', NULL, 'rhealyn.sumayang@deped.gov.ph', '$2y$10$Zvf7DQWcaCdMPcPf7WL0oedAJHdYWEaXEtaj9tnfUj2XiLsv/abbK', NULL, NULL, 1, NULL, '2026-06-03 06:54:19', '2026-06-03 06:54:19'),
(210, 4, 20, 'GRACE MAE', NULL, 'WENCESLAO', NULL, 'gracemae.guarin001@deped.gov.ph', '$2y$10$QnTEoTvai4KDtTvmqmt/K.4iPOyxBkxPWWCEaUnRn.MZHskneNB..', NULL, NULL, 1, NULL, '2026-06-03 06:54:19', '2026-06-03 06:54:19'),
(211, 4, 21, 'Hilda', NULL, 'Vallente', NULL, 'hilda.vallente@deped.gov.ph', '$2y$10$9V.gSMXVl9kYiyXABUumOOx3qLSU7CtwuBKWEYlvFDbRzOAH/IvsS', NULL, NULL, 1, '2026-06-03 15:11:25', '2026-06-03 06:54:38', '2026-06-03 07:11:25'),
(212, 4, 21, 'Jay Anne', NULL, 'Abendan', NULL, 'jayanne.abendan@deped.gov.ph', '$2y$10$PwixxdAcC/GgbGtJ/N4Pw.f6JaDrw4e84CofS33Mf0kGhNTQGOVqe', NULL, NULL, 1, NULL, '2026-06-03 06:54:38', '2026-06-03 06:54:38'),
(213, 4, 21, 'Lelith', NULL, 'Sison', NULL, 'lelith.sison@deped.gov.ph', '$2y$10$NYM2ZhBpqR9uLuxDrvyGwet0Qqpi94pvCCQkYn4WBanKSGYEpVSta', NULL, NULL, 1, NULL, '2026-06-03 06:54:39', '2026-06-03 06:54:39'),
(214, 4, 21, 'Michelle', NULL, 'Basalo', NULL, 'michelle.basalo@deped.gov.ph', '$2y$10$bTqBco/h9vBvnbTGSpuTnu/WfkBN1o1bGqM.mfFFCSnfn9MPb8aWq', NULL, NULL, 1, NULL, '2026-06-03 06:54:39', '2026-06-03 06:54:39'),
(216, 2, 23, 'School', NULL, 'Admin', NULL, 'jeneva.cruz@deped.gov.ph', '$2y$10$GPaMQJQTH0nhS7CXGxgNBOPEmG/AqRm5k0QByEtTSQ5XICzv6Zu9C', NULL, NULL, 1, '2026-06-03 16:34:00', '2026-06-03 14:55:13', '2026-06-03 08:34:00'),
(217, 2, 27, 'School', NULL, 'Admin', NULL, 'ericqbaldon@gmail.com', '$2y$10$N7bizFZJBXktku69IRWWZe0PL.t5VaSd/Yu3ZcIXpr0bTlv8l8z4u', NULL, NULL, 1, '2026-06-03 16:05:17', '2026-06-03 14:55:19', '2026-06-03 08:05:17'),
(218, 2, 25, 'School', NULL, 'Admin', NULL, 'krisna.alvar@deped.gov.ph', '$2y$10$CJn05YqIjSZP2oIrBNghnOVbpKLLkWCCnGKm64Qw9kkweyHy657iS', NULL, NULL, 1, '2026-06-04 12:28:53', '2026-06-03 14:56:35', '2026-06-04 04:28:53'),
(219, 2, 30, 'School', NULL, 'Admin', NULL, 'gabriel.candia@deped.gov.ph', '$2y$10$SN/HgOi1A/uyRUIiG9t64eLZNtbl6S6MN1WoLHNolUh4mHDczTS2u', NULL, NULL, 1, '2026-06-03 14:57:04', '2026-06-03 14:56:54', '2026-06-03 06:57:04'),
(220, 2, 26, 'School', NULL, 'Admin', NULL, 'geearlintogonan26@gmail.com', '$2y$10$/IuUEIYxLNNZFtF0Jdl6meftHbXxdASVVcB/S85nBUUdskltph7Cq', NULL, NULL, 1, '2026-06-04 11:21:19', '2026-06-03 14:58:09', '2026-06-04 03:21:19'),
(224, 2, 28, 'School', NULL, 'Admin', NULL, 'verna.valles@deped.gov.ph', '$2y$10$jKnWCnnoSESN80l0VPXG7ewI2pBbji8bgQyUuBdoKw2i464OWit02', NULL, NULL, 1, '2026-06-04 12:45:42', '2026-06-03 14:58:57', '2026-06-04 04:45:42'),
(232, 4, 24, 'REY MARK', NULL, 'GUTUAL', NULL, 'reymark.gutual@deped.gov.ph', '$2y$10$IazHeNM9StS3S.HbD7/H.OtKupWqVsE7rjqGXvLUu31G/T5DjJAa.', NULL, NULL, 1, '2026-06-03 15:37:12', '2026-06-03 07:00:57', '2026-06-03 07:37:12'),
(233, 4, 24, 'RONALD', 'PAGAS', 'QUER', '', 'ronald.quer@deped.gov.ph', '$2y$10$Y6eqKrja2d0yMR1nbNlK1ukn7kw/pxJ6nsCnt53zbY3rD2jbTx1KW', '09954522821', NULL, 1, '2026-06-03 15:36:00', '2026-06-03 07:00:57', '2026-06-03 07:36:00'),
(234, 4, 21, 'Paul', NULL, 'Sicabalo', NULL, 'paul.sicabalo@deped.gov.ph', '$2y$10$XntXAouf9B8sb3dY8/a3Ku/BTL8IESClnRQP0AKA.WKvU6aCi5Wb2', NULL, NULL, 1, '2026-06-04 12:52:31', '2026-06-03 07:01:09', '2026-06-04 04:52:31'),
(236, 2, 31, 'School', NULL, 'Admin', NULL, '129323@deped.gov.ph', '$2y$10$Mp4z3j6wj0cg8CfXhQNVv.iAhV33g2rFvsF0A0GEEjQjUpa24Vde.', NULL, NULL, 1, '2026-06-03 15:02:15', '2026-06-03 15:02:03', '2026-06-03 07:02:15'),
(240, 5, 20, 'mark', 'amo', 'legurpa', NULL, '129287170001', '$2y$10$vnslrP1Gt.Q6SoYcBTgP0OJCCNmKNNltrxrUKFCX4IutfQgHNzPh2', NULL, NULL, 1, NULL, '2026-06-03 15:03:08', '2026-06-03 15:03:08'),
(241, 5, 20, 'eric', 'dave', 'matinao', NULL, '129287170039', '$2y$10$ODrO/pLvNEdRzx2oK87mXugaR0WUGdY7ZOD6hlkK/E.BnEauWGVqK', NULL, NULL, 1, NULL, '2026-06-03 15:03:08', '2026-06-03 15:03:08'),
(242, 5, 20, 'john', 'lao', 'empoc', NULL, '129287190006', '$2y$10$nLOL0V1F7Kse6cTYhq71xO23zfLTGX8hOKP5s/ZWuKN73NBGwUFL2', NULL, NULL, 1, NULL, '2026-06-03 15:03:08', '2026-06-03 15:03:08'),
(243, 5, 20, 'kaye', 'amad', 'lumantad', NULL, '129478190158', '$2y$10$5WpanzXYr5Kl3ICOlzxr6uPcSip4UhJPvjNlgYcpGKJs6vlxX4cpO', NULL, NULL, 1, NULL, '2026-06-03 15:03:08', '2026-06-03 15:03:08'),
(247, 4, 31, 'DIZA', NULL, 'JUARIO', NULL, 'diza.juario001@deped.gov.ph', '$2y$10$GB3L/gU5YlvF0fIF4VNlGuXmbaB/gNbGh1M/HDU5KN9NBexfRhoq6', NULL, NULL, 1, NULL, '2026-06-03 07:04:39', '2026-06-03 07:04:39'),
(251, 2, 29, 'School', NULL, 'Admin', NULL, 'cherryrose.anides@deped.gov.ph', '$2y$10$YUR6jPIlzI.MK34RkHBVbe0tBln5pO2684hJFSHb7999FptrMKjLG', NULL, NULL, 1, '2026-06-04 05:49:44', '2026-06-03 15:07:04', '2026-06-03 21:49:44'),
(252, 2, 33, 'School', NULL, 'Admin', NULL, 'christinemae.delrosario@deped.gov.ph', '$2y$10$MB5b3gNcW.LJDwNVB07qSusXZ5ky2.PQE08Sl6nbRXkYM6A.a9JWe', NULL, NULL, 1, '2026-06-03 15:07:48', '2026-06-03 15:07:27', '2026-06-03 07:07:48'),
(259, 2, 35, 'ARNEL', NULL, 'TOROBA', NULL, 'arnel.toroba@deped.gov.ph', '$2y$10$vxGyLrihGiTkkMxvcZmv9.YbQYibcFYX4DDLMlpJWN21w1ijagN/K', NULL, NULL, 1, '2026-06-03 15:13:11', '2026-06-03 15:13:04', '2026-06-03 07:31:10'),
(261, 4, 23, 'Luzminda', NULL, 'Enriquez', NULL, 'luzminda.enriquez@deped.gov.ph', '$2y$10$smxinh1MK4y/HWZSRsSbKOMOX960ibuGcq0MVyf8lLDsHuxDideLa', NULL, NULL, 1, NULL, '2026-06-03 07:14:21', '2026-06-03 07:16:15'),
(263, 4, 24, 'JENNIFER', NULL, 'ABADIEZ', NULL, 'jennifer.abadiez@deped.gov.ph', '$2y$10$YxebxF76OKj.j.Cv6fzkuusfVjeYiL4CPFMWbZWH/04Z5ZmXM.Ffy', NULL, NULL, 1, NULL, '2026-06-03 07:14:27', '2026-06-03 07:14:27'),
(265, 4, 24, 'PRISCILLA', NULL, 'AGBAY', NULL, 'priscilla.agbay@deped.gov.ph', '$2y$10$sGO8kybzMpiG8dC5HM7Cfe38Caz1BPLODIakEmoVIJuZbaq6T3yeO', NULL, NULL, 1, NULL, '2026-06-03 07:14:28', '2026-06-03 07:14:28'),
(267, 4, 24, 'MARVEN', NULL, 'AGBAY', NULL, 'marven.agbay@deped.gov.ph', '$2y$10$Ht9hjv6vZGHnVX5Sva120e6nt6pIUrxgue/xJOX91UbEjTEZJ4lkS', NULL, NULL, 1, NULL, '2026-06-03 07:14:28', '2026-06-03 07:14:28'),
(269, 4, 24, 'IRENE GRACE ', NULL, 'ALBEZA', NULL, 'irenegrace.albeza@deped.gov.ph', '$2y$10$pQ7iTyCdwTlN260B0QunYeeWi/8MeXhBZzaCSomC/6vDwJ8shMB32', NULL, NULL, 1, NULL, '2026-06-03 07:14:29', '2026-06-03 07:14:29'),
(270, 4, 24, 'MARVIN', NULL, 'ALBEZA', NULL, 'marvin.albeza@deped.gov.ph', '$2y$10$mL7ny2eLLtqPBvQHsjy06uMCdS3WhFxtUWvT4BnV3meZnWXbMfRVu', NULL, NULL, 1, NULL, '2026-06-03 07:14:29', '2026-06-03 07:14:29'),
(273, 4, 24, 'JODI VAN', NULL, 'ALVAREZ', NULL, 'jodivan.alvarez@deped.gov.ph', '$2y$10$nl21aJ/xRZzOG5YiTcU9Me0M1BvhBxtLuFsNuBNGcPvPsi7PFGgne', NULL, NULL, 1, NULL, '2026-06-03 07:14:29', '2026-06-03 07:14:29'),
(275, 4, 24, 'JUDITH', NULL, 'ALVAREZ', NULL, 'judith.alvarez@deped.gov.ph', '$2y$10$s9ilmfdsWLEprzCtxwBfzOY/VGRBZCyEVl0UwqOLhuqfxL/OSc0te', NULL, NULL, 1, NULL, '2026-06-03 07:14:29', '2026-06-03 07:14:29'),
(276, 4, 24, 'ARLYN', NULL, 'ANGELES', NULL, 'arlyn.angeles@deped.gov.ph', '$2y$10$S/6pA161EXatNY3AxxYa9ORl0b4Z/p.RDMfSviHBMhQeV1.vYUig6', NULL, NULL, 1, NULL, '2026-06-03 07:14:30', '2026-06-03 07:14:30'),
(278, 4, 24, 'MARIANNE MAY', NULL, 'ARA?A', NULL, 'mariannemay.ara?a@deped.gov.ph', '$2y$10$0fa1tR8tvJHmllDGi98hq.yXhkopDGaARXSH0akfFz9tt1Ssu2KAW', NULL, NULL, 1, NULL, '2026-06-03 07:14:30', '2026-06-03 07:14:30'),
(280, 4, 24, 'EDLYN', NULL, 'BALINAS', NULL, 'edlyn.balinas@deped.gov.ph', '$2y$10$ls5dUxEaALrJiNh5nQvmduoVMx36M4hFqSKS4AtK44np3F9IpWoUu', NULL, NULL, 1, NULL, '2026-06-03 07:14:30', '2026-06-03 07:14:30'),
(281, 4, 24, 'EVELYN', NULL, 'BESAGAS', NULL, 'evelyn.besagas@deped.gov.ph', '$2y$10$cz6PCRRpP.olc4ALnCEscO1mZx0wKp0kQoHIdVPQaP/ru2jlGigem', NULL, NULL, 1, NULL, '2026-06-03 07:14:30', '2026-06-03 07:14:30'),
(282, 4, 24, 'ANA JOSEFA', NULL, 'BICO', NULL, 'anajosefa.bico@deped.gov.ph', '$2y$10$D4W2S1wdVeL0oZG3zgkExe.7MiO04M3gJlVqST.o3gc./fyb1U0ee', NULL, NULL, 1, NULL, '2026-06-03 07:14:31', '2026-06-03 07:14:31'),
(283, 4, 24, 'LEONORA', NULL, 'BOMOONG', NULL, 'leonora.bomoong@deped.gov.ph', '$2y$10$v47jSgjEdsKeAzw3IKq05epwrR1QfK8GflNvHuQYMhOSbnRgCl2Hm', NULL, NULL, 1, NULL, '2026-06-03 07:14:31', '2026-06-03 07:14:31'),
(284, 4, 24, 'LEIZEL', NULL, 'BULASO', NULL, 'leizel.bulaso@deped.gov.ph', '$2y$10$E7Euyt0eLzqArzdlfvnfL.ZGO.OWskrW18Vp9uQ2.waALL8pbqTsK', NULL, NULL, 1, NULL, '2026-06-03 07:14:31', '2026-06-03 07:14:31'),
(285, 4, 24, 'SERLOU', NULL, 'CANATOY', NULL, 'serlou.canatoy@deped.gov.ph', '$2y$10$MSziyNwYHAs4tNOZ8zv3puArT8.kekFmZhkPYFJr6oc0duAX/qn4W', NULL, NULL, 1, NULL, '2026-06-03 07:14:31', '2026-06-03 07:14:31'),
(286, 4, 24, 'CLEO HELLY', NULL, 'CHICOTE', NULL, 'cleohelly.chicote@deped.gov.ph', '$2y$10$8C7dXc8Atwsgmc4W5u12g.WQytoQDPebJbVZjHipYwnVjUDKIrbcG', NULL, NULL, 1, NULL, '2026-06-03 07:14:31', '2026-06-03 07:14:31'),
(287, 4, 24, 'ALEJANDRA', NULL, 'CODILLA', NULL, 'alejandra.codilla@deped.gov.ph', '$2y$10$maobZHlfW0FmbIwXafJISu6cBHWYn2KcfoBJZxZIpiBq5tgLLVAkO', NULL, NULL, 1, NULL, '2026-06-03 07:14:31', '2026-06-03 07:14:31'),
(288, 4, 24, 'AIZA', NULL, 'COQUILLA', NULL, 'aiza.coquilla@deped.gov.ph', '$2y$10$bESXQVI.M72btLDkoeAKauVzzqLacyIakdlNGkctRdBYcGu5nQO.q', NULL, NULL, 1, NULL, '2026-06-03 07:14:31', '2026-06-03 07:14:31'),
(289, 4, 24, 'NELIE', NULL, 'CORBO', NULL, 'nelie.corbo@deped.gov.ph', '$2y$10$26vuGFys8D8TifmZlqy1pujzbTbrFks70vGIk1iZ9asdTVbH3RuUG', NULL, NULL, 1, NULL, '2026-06-03 07:14:32', '2026-06-03 07:14:32'),
(290, 4, 24, 'CHERLYN', NULL, 'CRODUA', NULL, 'cherlyn.crodua@deped.gov.ph', '$2y$10$exjc4ZVk3Izj2x0cwKiM4Ob2jnsgev1W.7RS5wJ7MfDvzRQ2GT41G', NULL, NULL, 1, NULL, '2026-06-03 07:14:32', '2026-06-03 07:14:32'),
(291, 4, 24, 'ARNEL', NULL, 'DEJARISCO', NULL, 'arnel.dejarisco@deped.gov.ph', '$2y$10$Ev85zQ9SD54C0wRKp.l/auQktn13QO/vk.31Soq1CHqRb7ynvoju.', NULL, NULL, 1, NULL, '2026-06-03 07:14:32', '2026-06-03 07:14:32'),
(292, 4, 24, 'DIVINA', NULL, 'DEJARISCO', NULL, 'divina.dejarisco@deped.gov.ph', '$2y$10$3fm6f9xQh9jJMTqO1qGn9us4YUrME8IWqgbTTcPcHXaNTrxcUG0eu', NULL, NULL, 1, NULL, '2026-06-03 07:14:33', '2026-06-03 07:14:33'),
(293, 4, 24, 'GERALDINE', NULL, 'DONALDO', NULL, 'geraldine.donaldo@deped.gov.ph', '$2y$10$4HwP/y/9bRscUaGheBwoJuL4e87RP2jU8/33x/6SK5xJ9K7ldxl2W', NULL, NULL, 1, NULL, '2026-06-03 07:14:36', '2026-06-03 07:14:36'),
(294, 4, 24, 'GEMMA', NULL, 'DOROMAL', NULL, 'gemma.doromal@deped.gov.ph', '$2y$10$zkSQI.TWtovflsQxZcL7GeQj2lx2tGVE21H4DUsYsNUKsBs42o0Km', NULL, NULL, 1, NULL, '2026-06-03 07:14:36', '2026-06-03 07:14:36'),
(295, 4, 24, 'RICARJUNE', NULL, 'FERNANDEZ', NULL, 'ricarjune.fernandez@deped.gov.ph', '$2y$10$j/icwgBN3ZFfXNC4LPFOguIWXUWq2ihflhqn0bmYzgI56RF8yug4.', NULL, NULL, 1, NULL, '2026-06-03 07:14:36', '2026-06-03 07:14:36'),
(296, 4, 24, 'AMABELLE', NULL, 'FLORES', NULL, 'amabelle.flores@deped.gov.ph', '$2y$10$xdpZjGKKCV/CZFHD7C.HmeBmpXUUtl7ZV.zQGWS7yCxgU7Ql7xcU6', NULL, NULL, 1, NULL, '2026-06-03 07:14:37', '2026-06-03 07:14:37'),
(297, 4, 24, 'FE', NULL, 'GUARIN', NULL, 'fe.guarin@deped.gov.ph', '$2y$10$txNzEwW6PR5LjuolJ3RFleUHG1CKgQhXbvFTqNliamLr6PfjCe2ge', NULL, NULL, 1, NULL, '2026-06-03 07:14:37', '2026-06-03 07:14:37'),
(298, 4, 24, 'ARGEL', NULL, 'HAO', NULL, 'argel.hao@deped.gov.ph', '$2y$10$5aDA1rTRbPf1j6EO2zkO3e8spBCUI4Hitj9P/GlsA32y/qcNgdAAq', NULL, NULL, 1, NULL, '2026-06-03 07:14:38', '2026-06-03 07:14:38'),
(299, 4, 24, 'ELA MAE', NULL, 'HERNADEZ', NULL, 'elamae.hernadez@deped.gov.ph', '$2y$10$Msm9kjqStThZOJE.lH3BkuziGuexhK/CW0vZ8OUqr.I1wnnnHeZdi', NULL, NULL, 1, NULL, '2026-06-03 07:14:39', '2026-06-03 07:14:39'),
(301, 4, 24, 'RUTH', NULL, 'LABAJO', NULL, 'ruth.labajo@deped.gov.ph', '$2y$10$Cyo1924623AMg37rgRRBO.WoFFp.yfMxAmL5CB3LoaWdMKIoT6El6', NULL, NULL, 1, NULL, '2026-06-03 07:14:41', '2026-06-03 07:14:41'),
(305, 2, 36, 'School', NULL, 'Admin', NULL, 'mirasol.maimad@deped.gov.ph', '$2y$10$IYxWnOh.3130UHPlaLGHYu5nMxPHPIGqrEnVnbA3sBirW1EPq9jma', NULL, NULL, 1, '2026-06-03 15:18:02', '2026-06-03 15:15:46', '2026-06-03 07:18:02'),
(307, 5, 22, 'Maria', 'Santos', 'Reyes', NULL, '100000000001', '$2y$10$GoV5rTKdNwaEvx43MtDTPutS91hwtc5Ig3yejrnxRhJ5idyd.zLai', NULL, NULL, 1, NULL, '2026-06-03 15:17:25', '2026-06-03 15:17:25'),
(308, 5, 22, 'John', 'Dela Cruz', 'Garcia', NULL, '100000000002', '$2y$10$zeVkmANMtrKGprNazPU.1Otwf0ECfnyoBnqrNe6A5tHVqIe4WVAvK', NULL, NULL, 1, NULL, '2026-06-03 15:17:25', '2026-06-03 15:17:25'),
(309, 5, 22, 'Angela', 'Torres', 'Mendoza', NULL, '100000000003', '$2y$10$9jmijigYwrljXIIno93knu3562knlvAYRZW45fT96uzUQgq4TXgga', NULL, NULL, 1, NULL, '2026-06-03 15:17:25', '2026-06-03 15:17:25'),
(310, 5, 22, 'Mark', 'Ramos', 'Santos', NULL, '100000000004', '$2y$10$6InaCN0QhKk8R7vH/vHEsOtVuLZNC3fT857ArU4aR68zg6EysRWMG', NULL, NULL, 1, NULL, '2026-06-03 15:17:25', '2026-06-03 15:17:25'),
(311, 5, 22, 'Nicole', 'Lopez', 'Flores', NULL, '100000000005', '$2y$10$e4iQxocxCcagNt5j8u0f8OH.VjKM9AF2atXLf7V5e1a/RSNY0CG8G', NULL, NULL, 1, NULL, '2026-06-03 15:17:25', '2026-06-03 15:17:25'),
(312, 5, 22, 'Joshua', 'Villanueva', 'Cruz', NULL, '100000000006', '$2y$10$BzzVeIGuvMx.kV5KnYElS.NNsqOqTpzi1YTTNLIVrJHsCk02BJAGq', NULL, NULL, 1, NULL, '2026-06-03 15:17:26', '2026-06-03 15:17:26'),
(313, 5, 22, 'Sophia', 'Aquino', 'Bautista', NULL, '100000000007', '$2y$10$ELWf2uHwZg70qIVUhSC8zeHKChGXS5uCGu.VJKPqCbcZ0bcatZIsq', NULL, NULL, 1, NULL, '2026-06-03 15:17:26', '2026-06-03 15:17:26'),
(314, 5, 22, 'Daniel', 'Castillo', 'Navarro', NULL, '100000000008', '$2y$10$FpVYTxnSOwcnXpUy33bsmOp7cHl5tzqokl2.k8cUe5DH4O.mmIlfa', NULL, NULL, 1, NULL, '2026-06-03 15:17:26', '2026-06-03 15:17:26'),
(315, 5, 22, 'Christine', 'Morales', 'Rivera', NULL, '100000000009', '$2y$10$Jqvu6L0w9qEkBxZKCF8ONemdDpBdWgG1CPDos6OXaF8Cuw0mkjYNq', NULL, NULL, 1, NULL, '2026-06-03 15:17:26', '2026-06-03 15:17:26'),
(316, 5, 22, 'Patrick', 'Fernandez', 'Lim', NULL, '100000000010', '$2y$10$6Jpi1Cvk/m9c4M0Yr9nThOjcX9wH/TRwwCj3CsDBMXpmxaTZN2DE2', NULL, NULL, 1, NULL, '2026-06-03 15:17:26', '2026-06-03 15:17:26'),
(317, 2, 42, 'School', NULL, 'Admin', NULL, 'catherinelintogonan20@gmail.com', '$2y$10$1IQmxB6YcwmCXeJkeLjyiew/tv3wB45yWV832uJbcn0lk1vMA8L1.', NULL, NULL, 1, '2026-06-03 15:22:14', '2026-06-03 15:21:52', '2026-06-03 07:22:14'),
(318, 5, 23, 'Raffy', 'Nonong', 'Gogo', NULL, '12921145321', '$2y$10$SRQ9.Fl27VLWXPVEJ0MehuHSXSl3WtYyra5fQB.ZPSMN4Yu/z712i', NULL, NULL, 1, NULL, '2026-06-03 15:22:31', '2026-06-03 15:57:00'),
(319, 5, 23, 'Carla', 'Mocam', 'Pagandahan', NULL, '12921123425', '$2y$10$C3Qd8lYn6JcAP4Y.l9xi6O54VnEzdpKCwg53cgtN5vqxmUZS2h706', NULL, NULL, 1, NULL, '2026-06-03 15:22:32', '2026-06-03 15:58:06'),
(320, 5, 29, 'Carlo', 'Ramos', 'Flores', NULL, '2026-0001', '$2y$10$hqLSYD4GmpFDubjTOlO.IO2nzbVkXpPp91Hqn6Uu12MX8G4bMGHci', NULL, NULL, 1, NULL, '2026-06-03 15:22:51', '2026-06-03 15:25:04'),
(321, 5, 29, 'Sophia', 'Villanueva', 'Cruz', NULL, '2026-0002', '$2y$10$ZukjBLlVWcnSR8ZWXw2bYuzL6n59Cf4V7hQKMewcdaY/2GgSDe/Di', NULL, NULL, 1, NULL, '2026-06-03 15:22:51', '2026-06-03 15:24:48'),
(322, 5, 29, 'Miguel', 'Torres', 'Santos', NULL, '2026-0003', '$2y$10$UOy8M9jufQc3VJaVPJfXu.RQ3iWGIEQL1bF1zLQ8YnY8Uie2TXAbO', NULL, NULL, 1, NULL, '2026-06-03 15:22:51', '2026-06-03 15:25:48'),
(323, 5, 29, 'Angela', 'Navarro', 'Reyes', NULL, '2026-0004', '$2y$10$ZfuPlTANf8kKgWC8frzToudK/BzbhHgHnKfF0mIH7n4rxeYZ/xBpa', NULL, NULL, 1, NULL, '2026-06-03 15:22:52', '2026-06-03 15:25:35'),
(324, 5, 29, 'Daniel', 'Mercado', 'Garcia', NULL, '2026-0005', '$2y$10$DLbD/PKoZQiZdUvY8sl98eskZDIdFrkp1g2Na7VZTrtHo2XRlJIK6', NULL, NULL, 1, NULL, '2026-06-03 15:22:52', '2026-06-03 15:25:18'),
(325, 2, 41, 'School', NULL, 'Admin', NULL, 'anfannakrizza.quibod@deped.gov.ph', '$2y$10$RuQQr.MjAXqb70lHRylvxu7xL1CbVIjzENPawjIs02ntr1m.3qIcO', NULL, NULL, 1, '2026-06-03 15:24:12', '2026-06-03 15:23:51', '2026-06-03 07:24:12'),
(326, 4, 26, 'CATHERINE', NULL, 'LINTOGONAN', NULL, 'catherine.lintogonan@deped.gov.ph', '$2y$10$7DyuPexMy9D7TmD4Cl44d.YcjT8BIk9LuwQh2Ax11aquWLcx2SQx.', NULL, NULL, 1, NULL, '2026-06-03 07:24:06', '2026-06-03 07:24:06'),
(327, 2, 44, 'School', NULL, 'Admin', NULL, 'hermie.ylanan@deped.gov.ph', '$2y$10$bj/C4Pr.BJ/.HvQ/OYPDheYjUdA0sw53jIRbi59JnAP31mfKn1q9i', NULL, NULL, 1, '2026-06-03 15:24:28', '2026-06-03 15:24:18', '2026-06-03 07:24:28'),
(328, 2, 43, 'School', NULL, 'Admin', NULL, 'gina.arlalejo@deped.gov.ph', '$2y$10$7mfGAu6JgvVp0BIzMQgNZuIyy9QbrxSyW4dWCKsK3BEA9Cez4jYwm', NULL, NULL, 1, '2026-06-04 11:51:03', '2026-06-03 15:25:40', '2026-06-04 03:51:03'),
(329, 2, 38, 'School', NULL, 'Admin', NULL, '304311@deped.gov.ph', '$2y$10$huB3u0xmwMYZeYmMdPVltubfrPpVvRccPZde13UfFHCFcForTUN32', NULL, NULL, 1, '2026-06-04 17:08:22', '2026-06-03 15:26:37', '2026-06-04 09:08:22'),
(330, 5, 31, 'Daniel', 'Balbarino', 'Adlawon', NULL, '128299220043', '$2y$10$zcHPsUnGB7T9WpBOo8Ddq.X8LT41w5IMmRxQHIrPOFsLPwwsTQjhC', NULL, NULL, 1, NULL, '2026-06-03 15:26:43', '2026-06-03 15:26:43'),
(331, 5, 31, 'Stephen Clark', 'Poria', 'Benavidez', NULL, '136546220074', '$2y$10$DM88QmRWaHT.bKXhsJBwSeozEhWOj24bHjuZ9eAi9sFRTJRzYBkWu', NULL, NULL, 1, NULL, '2026-06-03 15:26:43', '2026-06-03 15:26:43'),
(332, 5, 31, 'John Dave', 'Bandigan', 'Imperial', NULL, '129323220006', '$2y$10$r8EVYpVSKcKVW1iDdGYCkeECVtov5T78vTKsjxvRjouoG1PFgixPu', NULL, NULL, 1, NULL, '2026-06-03 15:26:44', '2026-06-03 15:26:44'),
(333, 5, 36, 'RYAN JAMES', ' VICENTE', 'AMPILANON', NULL, '129373190011', '$2y$10$xFvf50znyp8EtcY.gSAJaO4WOyBa/wv4FLfYw98WwW5gY86qwdI52', NULL, NULL, 1, NULL, '2026-06-03 15:27:42', '2026-06-03 15:27:42'),
(334, 5, 26, 'LIGAYA', 'M.', 'AGUDOYUN', NULL, '12345678000', '$2y$10$u9OI/mFMIED5NqrAWX8W3eUdyRNchoESPfWzYN8LpD8K487JQBDWC', NULL, NULL, 1, NULL, '2026-06-03 15:28:57', '2026-06-03 15:28:57'),
(335, 5, 26, 'JOY', 'L.', 'LATIBAN', NULL, '12345678009', '$2y$10$.B95Z9aFWaY4frVQHqdYQ.aGtReoky124/by33sSxqZ3y3tqzsWpa', NULL, NULL, 1, NULL, '2026-06-03 15:29:56', '2026-06-03 15:29:56'),
(336, 5, 28, 'Harold', 'Tomas', 'Aguilon', NULL, '2026-01', '$2y$10$LM8.tD2XU8YK/jkxC2Sy8Ovb43H1GqngY58RRu.Ylmoh08moDy4xK', NULL, NULL, 1, NULL, '2026-06-03 15:31:08', '2026-06-03 15:31:08'),
(337, 5, 28, 'Jeyboy', 'Reyes', 'Aquino', NULL, '2026-02', '$2y$10$WOHKFMqi/6WyiMafYSDRbOOIGmvxjFqBJx705e3CeAmUvl8Qu6rd6', NULL, NULL, 1, NULL, '2026-06-03 15:31:08', '2026-06-03 15:31:08'),
(338, 5, 28, 'Romel', 'Garcia', 'Bagwas', NULL, '2026-03', '$2y$10$o5IfLtuwKvb8YbY/7EAhcew3IG17MPaLVb.oQWLsFEPSOmn2XgDfG', NULL, NULL, 1, NULL, '2026-06-03 15:31:08', '2026-06-03 15:31:08'),
(339, 5, 28, 'John Vincent', 'Mendoza', 'Banoo n', NULL, '2026-04', '$2y$10$N0OcUEYNaBfvaWc1On7UKepdc93KctKk5Hi224U1dowi.ZBChsbVi', NULL, NULL, 1, NULL, '2026-06-03 15:31:08', '2026-06-03 15:31:08'),
(340, 5, 28, 'Jaypaul', 'Ram', 'Bantang', NULL, '2026-05', '$2y$10$kS4aq7KlrvvQyOU/FuwKT.wojYrrnq5/RJ3s9Sg9YBc8nFjuT9NRy', NULL, NULL, 1, NULL, '2026-06-03 15:31:09', '2026-06-03 15:31:09'),
(341, 4, 29, 'Ethan', NULL, 'Davis', NULL, 'ethan.davis1@domain.com', '$2y$10$JTbOINJdr/zPla0J8T4x1eAPH0WBcNp53r2mq3N7aUa8ZeW9lDeNm', NULL, NULL, 1, NULL, '2026-06-03 07:31:29', '2026-06-03 07:31:29'),
(342, 4, 29, 'Olivia', NULL, 'Martinez', NULL, 'olivia.martinez2@domain.com', '$2y$10$VpJj9ArlZ9AN/shV2E3lFe6w1eVzpMsMlwOZDtemb/F7z1RoC5fE6', NULL, NULL, 1, NULL, '2026-06-03 07:31:29', '2026-06-03 07:31:29'),
(343, 4, 29, 'Liam', NULL, 'Anderson', NULL, 'liam.anderson3@domain.com', '$2y$10$OWDp5YBUjDszA7o.qM5pEO6efRNrjqhrL2Rc8ovg1ZkybY6rRVC8C', NULL, NULL, 1, NULL, '2026-06-03 07:31:29', '2026-06-03 07:31:29'),
(344, 4, 29, 'Sophia', NULL, 'Taylor', NULL, 'sophia.taylor4@domain.com', '$2y$10$tBkq7sKv97wPRb8TTfRydeRCXhV3a5s7qcyHhBzCdd4qISnGDarj6', NULL, NULL, 1, NULL, '2026-06-03 07:31:29', '2026-06-03 07:31:29'),
(345, 4, 29, 'Noah', NULL, 'Thomas', NULL, 'noah.thomas5@domain.com', '$2y$10$WMnTa5DDfDABiTUFfSdZfOPNAGvcdXDX2a7bGmNiYNkEoTZoa4C46', NULL, NULL, 1, NULL, '2026-06-03 07:31:29', '2026-06-03 07:31:29'),
(346, 5, 25, 'Cheska Mae', 'Parantar', 'Englisa', NULL, '129246150003', '$2y$10$l0mMl5iwl3mtJ2DbA9MveODgbzBT56Pxglz1U1NKriw4Di28QxWx2', NULL, NULL, 1, NULL, '2026-06-03 15:31:37', '2026-06-03 15:31:37'),
(347, 5, 25, 'Austin', 'Baby', 'De Castro', NULL, '129246150046', '$2y$10$yyfyM4rBRwAHYikKVPrnXePPiI.MxNFSyhTPRPALU0cxm7vhqL5pC', NULL, NULL, 1, NULL, '2026-06-03 15:31:38', '2026-06-03 15:31:38'),
(348, 5, 25, 'Xachzna', 'Alvar', 'Del Monte', NULL, '129214130002', '$2y$10$49uNCwKCafXNh3YjzJCj/OYfDJ96mSweeldGfgZa1sAZcc9hO68ka', NULL, NULL, 1, NULL, '2026-06-03 15:31:38', '2026-06-03 15:31:38'),
(349, 5, 25, 'Julio Javier', 'Del Monte', 'Cabras', NULL, '129246160002', '$2y$10$IqiwpR5upp8aucJHxH8SgepMRvO/oHINC7KZeCICxFAC5GKhwTO/e', NULL, NULL, 1, NULL, '2026-06-03 15:31:38', '2026-06-03 15:31:38'),
(350, 5, 25, 'Aiarah', 'Tipudan', 'Dagami', NULL, '129246150008', '$2y$10$gTHkcdCuYAzcfLEo.UQcs.u3YSrPwv6QhI1tjyeQQuwxBFFzc3rm.', NULL, NULL, 1, NULL, '2026-06-03 15:31:38', '2026-06-03 15:31:38'),
(351, 5, 36, 'YOHANNE JAY-', 'N/A', 'BANGCAYAON', NULL, '129462220051', '$2y$10$sGMnxOt/Uc7AB7ARwKbPouBPpOuLoKt98gL.xmkqlqg8I6hEPQWZK', NULL, NULL, 1, NULL, '2026-06-03 15:31:46', '2026-06-03 15:31:46'),
(352, 4, 25, 'Roselyn', NULL, 'Masaudling', NULL, 'roselyn.masaudling@deped.gov.ph', '$2y$10$23jUD7hJCd8627Ddg862q.DpN04HwEyofJLsamRfzrsx.RoxyjLQW', NULL, NULL, 1, NULL, '2026-06-03 07:31:59', '2026-06-03 07:31:59'),
(353, 4, 25, 'Vincent Roy', NULL, 'Benaning', NULL, 'vroybenaning@deped.gov.ph', '$2y$10$hFB70OA7NURgKk8hd6YT1.JPO7dytH8IvNIOKQjUVdZ3sBi6fvKDi', NULL, NULL, 1, NULL, '2026-06-03 07:31:59', '2026-06-03 07:31:59'),
(354, 4, 25, 'Michelle', NULL, 'Ramos', NULL, 'mramos@deped.gov.ph', '$2y$10$IvY7KR.o5YKXJNEoCw9IgObHlHB1wI/N5tvrjDAWsxYBHEPcYNH7C', NULL, NULL, 1, NULL, '2026-06-03 07:31:59', '2026-06-03 07:31:59'),
(355, 4, 25, 'Aiko ', NULL, 'Bantayan', NULL, 'abantayan@deped.gov.ph', '$2y$10$K4juZKaHqykKRbIyJ6EcJOUbu67O7RKEnLB8s3EsBvdtqvHg4IgWy', NULL, NULL, 1, NULL, '2026-06-03 07:31:59', '2026-06-03 07:31:59'),
(356, 4, 25, 'Shella May', NULL, 'Pal', NULL, 'palshe@deped.gov.ph', '$2y$10$jEhgSPya0QXQBbd/A.qQauzd..eRgU8s03g0X0L3jDztxAw5stpV.', NULL, NULL, 1, NULL, '2026-06-03 07:31:59', '2026-06-03 07:31:59'),
(357, 5, 42, 'Ariel', 'Dela', 'Cruz', NULL, '1', '$2y$10$rdPrknABM/iR3sKEQte83e949VtZY2YdCAPO5M90kly3.vR8Ewxk2', NULL, NULL, 1, NULL, '2026-06-03 15:32:27', '2026-06-03 15:32:27'),
(358, 5, 42, 'Samantha', 'Santos', 'Reyes', NULL, '2', '$2y$10$pUxDfEd04HP686HEIBH0N.9kXGiI/OiPrFWnXCrTkS/HZh6oR1BdK', NULL, NULL, 1, NULL, '2026-06-03 15:32:27', '2026-06-03 15:32:27'),
(359, 5, 42, 'Jones', 'Garcia', 'Lim', NULL, '3', '$2y$10$PB5qAV7nhPJUB9E9HrNho.Rc2IGuMa2NA3A4w3DoN.caxkzqFLR3K', NULL, NULL, 1, NULL, '2026-06-03 15:32:27', '2026-06-03 15:32:27'),
(360, 5, 42, 'Grace', 'Mendoza', 'Tan', NULL, '4', '$2y$10$oXArBxqhHuFa9Khid5gASOAAJ/PT/8DMfuPTvqPmjy7LvgDb.zjTe', NULL, NULL, 1, NULL, '2026-06-03 15:32:27', '2026-06-03 15:32:27'),
(361, 4, 28, 'Mary Joy', NULL, 'Iligan', NULL, 'maryjoy.iligan@deped.gov.ph', '$2y$10$0uCRQGgzHo2ud8Zzdstb5OphKIJ7CU3LTP1Mbkq4MIoz4XrIcjiDO', NULL, NULL, 1, NULL, '2026-06-03 07:35:14', '2026-06-03 07:35:14'),
(362, 5, 30, 'Peter', 'Cetera', 'Dela Cruz', NULL, '465521180017', '$2y$10$zD2W.C5D8YiP00eyuFWw2Og1Q2UFZJXpRGvjy8Axrih6VgDDtyDeG', NULL, NULL, 1, NULL, '2026-06-03 15:36:32', '2026-06-03 15:36:32'),
(363, 5, 30, 'Diana', 'Lee', 'Ross', NULL, '465521180018', '$2y$10$PBOxEL2Wpab/WMb181m6/O3qdYtBa1JDK2MR1ahGkMqmuyuOwe3fe', NULL, NULL, 1, NULL, '2026-06-03 15:36:32', '2026-06-03 15:36:32'),
(364, 5, 30, 'Celine', 'Roxas', 'Dion', NULL, '465521180019', '$2y$10$96wJG45fUk64bANEo7dy4OhEsJnRcvyntXiJ5RvzNDZt4jS1kGG42', NULL, NULL, 1, NULL, '2026-06-03 15:36:33', '2026-06-03 15:36:33'),
(365, 5, 30, 'Elvis', 'Sanchez', 'Presley', NULL, '465521180020', '$2y$10$niQwE2SyMLLxS9UQXx9Hv.aGSJdqkLaWmstS5e6h7hEdLlX6WtiEW', NULL, NULL, 1, NULL, '2026-06-03 15:36:33', '2026-06-03 15:36:33'),
(366, 5, 30, 'Frank', 'Boaz', 'Sinatra', NULL, '465521180021', '$2y$10$eTQaqE7BTGLmFd5pQxyeh.JdS4COmKvC1J184F4BySBgzK7fuGCxO', NULL, NULL, 1, NULL, '2026-06-03 15:36:33', '2026-06-03 15:36:33'),
(367, 5, 30, 'Mariah', 'Pilar', 'Carey', NULL, '465521180022', '$2y$10$2L3GZLLGEWl6lRsqjbmQ/.OgMQ6GfeIIHdgVNZ38wtExWcYm99vvO', NULL, NULL, 1, NULL, '2026-06-03 15:36:33', '2026-06-03 15:36:33'),
(368, 5, 30, 'Freddie', 'Drilon', 'Mercury', NULL, '465521180023', '$2y$10$qo1zCrKvW5UFRF/VnYGgHONtn9rJL9mywVKG3yI3HKi.vt8VKpP22', NULL, NULL, 1, NULL, '2026-06-03 15:36:33', '2026-06-03 15:36:33'),
(369, 5, 30, 'Miriam', 'Defensor', 'Santiago', NULL, '465521180024', '$2y$10$h5KPxkAtLHIWKE5/EpzgsO3XFAUxGSh5.dbcKxBVP6VcRPDU1kf6u', NULL, NULL, 1, NULL, '2026-06-03 15:36:33', '2026-06-03 15:36:33'),
(370, 5, 30, 'Juan', 'Ponce', 'Enrile', NULL, '465521180025', '$2y$10$59tz.yCK3POi/VL5tM.4eOApZNOiAQKvmooKXD/hUtTGqpJr9zi1i', NULL, NULL, 1, NULL, '2026-06-03 15:36:33', '2026-06-03 15:36:33'),
(371, 5, 41, 'Maria', 'Ong', 'Wang', NULL, '1234566778', '$2y$10$AzV027BwKLcaVvzb.uwzNO0f69KH40vf.aURpEK7Bwnt/5eFIXWIi', NULL, NULL, 1, NULL, '2026-06-03 15:36:52', '2026-06-03 15:36:52'),
(372, 5, 33, 'Joseph', 'Lopez', 'Makiling', NULL, '5', '$2y$10$oYkuTuybPYxnN4TO1kG.nO1AhvTV1SWyj52DLluufTCxljlHgDsJ.', NULL, NULL, 1, NULL, '2026-06-03 15:38:01', '2026-06-03 15:38:01'),
(374, 5, 44, 'KYLE JOHAMM', 'HAOM', 'YLANAN', NULL, '129299', '$2y$10$khSpbNRBetW/olR8eY.ZLuf8MiXX9fN8IXPpztD9Y2HDZMwnZ5t/q', NULL, NULL, 1, NULL, '2026-06-03 15:40:50', '2026-06-03 15:40:50'),
(376, 5, 44, 'KYLE JOHANN', 'HAOM', 'YLANAN', NULL, '2025-000-1', '$2y$10$gaIW90eHFgPcsTR6Xupok.uU6JfRzM7VROilpZVQPJQR4mqNpZlsS', NULL, NULL, 1, NULL, '2026-06-03 15:41:59', '2026-06-03 15:41:59'),
(377, 5, 44, 'MARIE', 'FE', 'CABALLES', NULL, '2025-000-2', '$2y$10$LCO4xUTFTHlfcn6JOQ2Keem8aIIgRTKTrHIseG1SCUOMiBNDmcMMa', NULL, NULL, 1, NULL, '2026-06-03 15:41:59', '2026-06-03 15:41:59'),
(378, 5, 44, 'ROGER', 'SO', 'TE', NULL, '2025-000-3', '$2y$10$74lYK6gx69549Lg5jADiQ.SC/i5Zlf0RRdIfNC3yFOEKoySbpUGLu', NULL, NULL, 1, NULL, '2026-06-03 15:41:59', '2026-06-03 15:41:59'),
(379, 5, 44, 'LYCA', 'MIANO', 'OSOL', NULL, '2025-000-4', '$2y$10$WjbDYzIdmh6eByKxqb3QI.etv6wen32SvfaQRv4W6WOML9bmeylD6', NULL, NULL, 1, NULL, '2026-06-03 15:41:59', '2026-06-03 15:41:59'),
(380, 5, 44, 'ANGEL', 'NO', 'ROCACORBA', NULL, '2025-000-5', '$2y$10$QrAc6I6ROMmYwkp1bZHSq.Mcz3OLCAY5ZEwBzHSVFKF3i1dnEv3f2', NULL, NULL, 1, NULL, '2026-06-03 15:41:59', '2026-06-03 15:41:59'),
(381, 4, 23, 'JANNETH', NULL, 'ACOSTA', NULL, 'janneth.tipudan@deped.gov.ph', '$2y$10$fl8.StpUf.xnlaXVmjsm3.aqVDNsuEaB3wCOimJ8fAE5.2hXjPuVC', NULL, NULL, 1, NULL, '2026-06-03 07:42:42', '2026-06-03 07:42:42'),
(382, 5, 27, 'JOEY', '', 'DELA CRUZ', NULL, '1295636591751', '$2y$10$Bso6r99yqrOUNufxBJePoecntdZgUkLZfN5PZmZJjnGh7el4A4/N6', NULL, NULL, 1, NULL, '2026-06-03 15:43:08', '2026-06-03 16:00:10'),
(383, 4, 23, 'LEONILO', NULL, 'BURGOS', NULL, 'leonilo.burgos@deped.gov.ph', '$2y$10$z7v4y98eqp80DQtcxBbgYOrYo8VURpizQLKKfMpu/REsKQP8iwAW2', NULL, NULL, 1, NULL, '2026-06-03 07:43:33', '2026-06-03 07:43:33'),
(384, 5, 27, 'KERK', '', 'MATER', NULL, '1295636591752', '$2y$10$svRA3hrXGJiAVaFpZwhQH.7EJ1bVexBiIle.ClTILABNLmgtCPe5q', NULL, NULL, 1, NULL, '2026-06-03 15:44:00', '2026-06-03 15:44:00'),
(385, 2, 45, 'School', NULL, 'Admin', NULL, 'eric.dante@deped.gov.ph', '$2y$10$aFR23QGWCEJu8YhkzEBVS.1DrkrudQK0j.RCAxfTVWH3p3YZLy3q6', NULL, NULL, 1, '2026-06-03 15:45:02', '2026-06-03 15:44:37', '2026-06-03 07:45:02'),
(386, 5, 27, 'MARK', '', 'GAYANGAN', NULL, '1295636591753', '$2y$10$i4OAokRF2KIq7tMnWP60N.mac5JPMJZEzFEccUl7XcwppffvMRBiy', NULL, NULL, 1, NULL, '2026-06-03 15:44:49', '2026-06-03 15:44:49'),
(387, 5, 35, 'Juan', 'Dela', 'Cruz', NULL, '1.29204E+11', '$2y$10$ibKRWKXBsYBa3mmA0gtI4uZ8gbFlEB7rrf.HFkfqzyW6utufZzdCu', NULL, NULL, 1, NULL, '2026-06-03 15:47:32', '2026-06-03 15:47:32'),
(388, 5, 35, 'Maria', 'Santos', 'Reyes', NULL, '1.29204E+13', '$2y$10$RMPps95QKLQbrwjQGYpAg.MnayTuDNh.62dULl4rGoga2S17GDyYa', NULL, NULL, 1, NULL, '2026-06-03 15:47:32', '2026-06-03 15:47:32'),
(389, 5, 35, 'Ana', 'Mendoza', 'Tan', NULL, '1.29205E+11', '$2y$10$LVwifqGpP8h32hBU7Tbs0OZ.t8dC7ikILtfj5kkzxvODQv1jMU3Lq', NULL, NULL, 1, NULL, '2026-06-03 15:47:32', '2026-06-03 15:47:32'),
(390, 2, 46, 'School', NULL, 'Admin', NULL, '129157@deped.gov.ph', '$2y$10$79SydY2M7X.sXkMMGHL57OsSp6NxZku2kVvRGmEIXsCBbQf1qyl3a', NULL, NULL, 1, '2026-06-03 15:48:04', '2026-06-03 15:47:56', '2026-06-03 07:48:04'),
(391, 4, 23, 'MARILYN', NULL, 'SOBRECAREY', NULL, 'marilyn.sobrecarey@deped.gov.ph', '$2y$10$vY67nfkvbi7KQcR5qPWD1uFL/DhtNJ/vE.BPhKveWJbKGah1ix2XK', NULL, NULL, 1, '2026-06-03 16:33:33', '2026-06-03 07:48:00', '2026-06-03 08:33:33'),
(392, 5, 24, 'JOHN RHAFAEL', 'CARPENTERO', 'ALBARACIN', NULL, '123', '$2y$10$Zx2E0ztA2LyGu1XDcuJc2Oa.H6nAdJNVzIjBIbh.fiAxFRYQhOese', NULL, NULL, 1, NULL, '2026-06-03 15:48:33', '2026-06-03 15:48:33'),
(393, 5, 24, 'JOSHUA ERL', 'ANCERO', 'BANTILES', NULL, '122', '$2y$10$poWYzBJh6cC55.vO75ixW.AuwGmZMvJ5csGAkfKvzgNPvMUH0ddBC', NULL, NULL, 1, NULL, '2026-06-03 15:48:33', '2026-06-03 15:48:33'),
(394, 5, 24, 'REIN ZEON', '', 'CAPIN', NULL, '121', '$2y$10$lreEhFGEVBt8K6eLDo84mu.IFx7CodABDYiX3aLU9/Fmal2walJc.', NULL, NULL, 1, NULL, '2026-06-03 15:48:33', '2026-06-03 15:48:33'),
(395, 5, 24, 'MEL JACOB', 'SILONGAN', 'JOVES', NULL, '120', '$2y$10$zrVeXlS1mf1ElmEpFoRp2OGIAC/Ifwb6qkL9d0ht6PYu3820tI/H.', NULL, NULL, 1, NULL, '2026-06-03 15:48:33', '2026-06-03 15:48:33'),
(396, 4, 46, 'Ercel', NULL, 'Marqueda', NULL, 'charley.cabras@deped.gov.ph', '$2y$10$bwXalR1y/rZuCqi5NTBW1.XzM2n2cIKgZWoygPwjEHah9wY2Wu/Y.', NULL, NULL, 1, NULL, '2026-06-03 07:49:41', '2026-06-03 07:49:41'),
(397, 5, 36, 'PRINCESS AJ', 'N/A', 'AWING', NULL, '129462220087', '$2y$10$fys3hx3l3h.waYt3dDvX3O0D6zr.dNlOYg95U55F2ORIs4qqukWnC', NULL, NULL, 1, NULL, '2026-06-03 15:50:58', '2026-06-03 15:50:58'),
(398, 4, 31, 'Errolyn', NULL, 'Cabilan', NULL, 'errolyn.gudes@deped.gov.ph', '$2y$10$aXW8iIUSxirR5ntC.DvneuQXIyYIyA.asuNoFiUCCKX/BKWcJLxA.', NULL, NULL, 1, NULL, '2026-06-03 07:51:47', '2026-06-03 07:51:47'),
(399, 4, 31, 'Bernadeth', NULL, 'Custodio', NULL, 'bernadeth.custodio003@deped.gov.ph', '$2y$10$IM954axG4zYUel8zv4iApeOON5jehPoADo6CQ3qytJQDJXDBgxLJy', NULL, NULL, 1, NULL, '2026-06-03 07:51:47', '2026-06-03 07:51:47'),
(400, 4, 31, 'Dorivina', NULL, 'Imperial', NULL, 'dorivina.imperial@deped.gov.ph', '$2y$10$8fI6tmvLN861JhRw3cMj..UQmr/WZ9083VLcLb3EMRoj6ZZB0dS.i', NULL, NULL, 1, NULL, '2026-06-03 07:51:47', '2026-06-03 07:51:47'),
(401, 5, 45, 'Lenie', 'Bat', 'Bakay', NULL, '129195000323', '$2y$10$xaVS8VC1XTnt4ww.05atneK656yf/DoAg7kZd/HlhwaWa1.J2mI9G', NULL, NULL, 1, NULL, '2026-06-03 15:53:09', '2026-06-03 15:53:09'),
(402, 5, 23, 'TONE JR.', 'ALVAR', 'LEMENIO', NULL, '12921134567', '$2y$10$K3VSrI7ynsB3xeJ3VKn/z.f0vNphVHYItRplRHekGQtoVt61/V0vW', NULL, NULL, 1, NULL, '2026-06-03 15:53:18', '2026-06-03 15:53:18'),
(403, 5, 43, 'Ginalyn', 'Dela', 'Cruz', NULL, 'L001', '$2y$10$xaw/TzWEZecCVwjI6dFEbOt.P2yUsT6NBr/o58G49B.n7lUnQIp5e', NULL, NULL, 1, NULL, '2026-06-03 15:53:51', '2026-06-03 15:53:51'),
(404, 5, 43, 'Carlo', 'Santos', 'Reyes', NULL, 'L002', '$2y$10$kHl8LgQ9TPs8HA/Y55ek4upcMHKemUHyAyDa.ugtD0G6SZknYdzNi', NULL, NULL, 1, NULL, '2026-06-03 15:53:51', '2026-06-03 15:53:51'),
(405, 5, 43, 'Hana', 'Garcia', 'Lim', NULL, 'L003', '$2y$10$aQCv/byp.1W0xxjldjL5Zudei4rT4FzgYoI16r7G5Tb5EcVGGExrq', NULL, NULL, 1, NULL, '2026-06-03 15:53:51', '2026-06-03 15:53:51'),
(406, 5, 43, 'Carlota', 'Mendoza', 'Tan', NULL, 'L004', '$2y$10$NrjJBmLm8zto6/3Ie9OOsOIbeTOijAVS4XxQGvJbbSmF2uJ6VUgGG', NULL, NULL, 1, NULL, '2026-06-03 15:53:51', '2026-06-03 15:53:51'),
(407, 5, 43, 'Thomas', 'Dizon', 'Ong', NULL, 'L005', '$2y$10$2eexcSCdVaGPxd/s3ncA5uhHq59o6qd65y0O7Alo2Z59MNK2mRRcW', NULL, NULL, 1, NULL, '2026-06-03 15:53:51', '2026-06-03 15:53:51'),
(408, 4, 27, 'AIMEE', NULL, 'MORALES', NULL, 'baldonqeric@gmail.com', '$2y$10$jThEBxrrzT.MMMAwz998PO9Z6wiiX.7LWLxgbG/T4pJOtcaivK.ui', NULL, NULL, 1, '2026-06-03 16:30:57', '2026-06-03 07:54:19', '2026-06-03 08:30:57'),
(409, 4, 31, 'Melody Grace', NULL, 'Sapalicio', NULL, 'melodygrace.sapalicio@deped.gov.ph', '$2y$10$Ef64eIWXh2kXj1Vdf1ZJx.uqbfBMiykoG1wEUkdtCfYX8dlYXnUQi', NULL, NULL, 1, NULL, '2026-06-03 07:55:30', '2026-06-03 07:55:30'),
(410, 4, 44, 'MAY', NULL, 'SO', NULL, 'may.so@gmail.com', '$2y$10$LN7pHy/4jenCapYMd.Baae1zdxPU5a.NeyfYMwGJU/KUC5XAX9BUS', NULL, NULL, 1, NULL, '2026-06-03 07:56:02', '2026-06-03 07:56:02'),
(411, 5, 45, 'Botay', 'Memoracion', 'Lino', NULL, '129195000324', '$2y$10$WW6M3.CDmlgL.4FOLH6eduWB.oVR.D1jQQfpu0NvPOvTg12xwmA4y', NULL, NULL, 1, NULL, '2026-06-03 15:56:56', '2026-06-03 15:56:56'),
(412, 5, 46, 'Kyle', 'Ubat', 'Abad', NULL, '129157131870', '$2y$10$0t43CXlDGJ3NNlyq2X6YYO11hkMKq81T1PG2i/AOfe00LMwKQhUQ.', NULL, NULL, 1, NULL, '2026-06-03 15:58:36', '2026-06-03 15:58:36'),
(414, 5, 23, 'NERVANA', 'CAPALIT', 'BARBA', NULL, '12921123411', '$2y$10$l49ntUZAiGQULs0x5mVBLeFlqPoqszdL7grPuw/v0FS9B2sT4s0F2', NULL, NULL, 1, NULL, '2026-06-03 16:00:00', '2026-06-03 16:00:00'),
(416, 4, 31, 'Rahima', NULL, 'Gadia', NULL, 'rahima.gadia@deped.gov.ph', '$2y$10$p8RZVX/oP1LjFIF0nJhPHeOowzWRRUe3P0sZjqmcjApd5Q/FtjA1u', NULL, NULL, 1, NULL, '2026-06-03 08:01:00', '2026-06-03 08:01:00'),
(417, 4, 28, 'Khien', NULL, 'Ayuba', NULL, 'khien.ayuba@deped.gov.ph', '$2y$10$ZjfXhAzbmGyGc5qM4MQlI.BpqECijR8HHui9.Q8elnx7cOHM9tQH.', NULL, NULL, 1, NULL, '2026-06-03 08:01:01', '2026-06-03 08:01:01'),
(418, 4, 28, 'Melody  Clair', NULL, 'Duay', NULL, 'melodyclair.duay@deped.gov.ph', '$2y$10$oQIfnff8f6VPnzdZmWvGfO7FKha76Avf1RQqJOybfQEmcRaNElM5S', NULL, NULL, 1, NULL, '2026-06-03 08:01:01', '2026-06-03 08:01:01'),
(425, 4, 35, 'Ellen', NULL, 'Toroba', NULL, 'ellen.toroba@gmail.com', '$2y$10$bhaA5XpshHXQKs057EwgYuY87xwpcSC0MuaNlNWJKPW/7eWkAivx.', NULL, NULL, 1, NULL, '2026-06-03 08:20:57', '2026-06-03 08:20:57'),
(426, 4, 35, 'Daisy Mae', NULL, 'Pawaon', NULL, 'daisymae.pawaon@deped.gov.ph', '$2y$10$5yuPMmlGRI77Y0hAo9dK.uGMEVUtGBLMAbR.VaCKmP0qt/dpeP0fa', NULL, NULL, 1, NULL, '2026-06-03 08:20:57', '2026-06-03 08:20:57'),
(427, 4, 35, 'Daisy', NULL, 'Crayo', NULL, 'daisy.crayo@deped.gov.ph', '$2y$10$bHFru7r4lsLL3ZqCVelY5OouDWWO5/z1XYg4KGrfa/XDp2p0r/hpW', NULL, NULL, 1, NULL, '2026-06-03 08:20:57', '2026-06-03 08:20:57'),
(428, 4, 35, 'Yolanda', NULL, 'Castro', NULL, 'yolanda.castro@deped.gov.ph', '$2y$10$LdD1W.bRwl6.x5eRoahMSeCtyQMSR5Htj7DaYALJPK42V3jf2vRqe', NULL, NULL, 1, NULL, '2026-06-03 08:20:58', '2026-06-03 08:20:58'),
(429, 2, 32, 'School', NULL, 'Admin', NULL, 'racquel.espiritu@deped.gov.ph', '$2y$10$zF6DfXnwTbq.eNJS5b1M7OE.bdXeE/QFExsqVJifa6UREWjSdWGBC', NULL, NULL, 1, '2026-06-03 16:34:26', '2026-06-03 16:28:36', '2026-06-03 08:34:26'),
(431, 2, 47, 'School', NULL, 'Admin', NULL, 'greggy.yu@deped.gov.ph', '$2y$10$g.FLq9gIgtEevwW6.BXPYeSY8/IJ7vYIVNzihbI44vbG4YcnU6Hlm', NULL, NULL, 1, '2026-06-03 16:33:42', '2026-06-03 16:33:01', '2026-06-03 08:33:42'),
(432, 4, 30, 'Rhodora', NULL, 'Candido', NULL, 'smith1@domain.com', '$2y$10$e2jUZDxgJlHG4FGlxnIOWeBvSK2/GBteV5m9Xl1y6s0mvwO5RnyWG', NULL, NULL, 1, NULL, '2026-06-03 08:36:05', '2026-06-03 08:36:05'),
(433, 4, 30, 'Hendrito ', NULL, 'Misa', NULL, 'johnson2@domain.com', '$2y$10$mkH81XHIrCIVlKjpOaKEvOHcuE2Uxp0zdTLEB/FQ5q4TEazG57iVO', NULL, NULL, 1, NULL, '2026-06-03 08:36:05', '2026-06-03 08:36:05'),
(434, 4, 30, 'Gabriel', NULL, 'Candia', NULL, 'williams3@domain.com', '$2y$10$.mVpwzQ8XP35FchpejhhHOPnjZDrCuxsutJr3Jh/6cbM4WNIxMDXW', NULL, NULL, 1, NULL, '2026-06-03 08:36:06', '2026-06-03 08:36:06'),
(435, 4, 30, 'Bethel Jane', NULL, 'Chacon', NULL, 'brown4@domain.com', '$2y$10$oQkh7xOPEzq4UAE4T881FucU4Q4qboIGGuVUMaxYy7gIkvVda4Dua', NULL, NULL, 1, NULL, '2026-06-03 08:36:06', '2026-06-03 08:36:06');
INSERT INTO `users` (`id`, `role_id`, `school_id`, `first_name`, `middle_name`, `last_name`, `suffix`, `email`, `password`, `phone`, `avatar`, `status`, `last_login`, `created_at`, `updated_at`) VALUES
(437, 4, 43, 'Mara', NULL, 'Thomson', NULL, 'mara.thomson@gmail.com', '$2y$10$E0I0.qfYTVryYmEScy/6vuWLyi/vUpLIZxq/Myz2d5W9oNrm6TT5C', NULL, NULL, 1, NULL, '2026-06-03 22:57:23', '2026-06-03 22:57:23'),
(438, 4, 43, 'Jerry', NULL, 'Brown', NULL, 'jerry.brown@gmail', '$2y$10$f6aKiT0FeC95G0a3JnxArurlPOAlLltWJI2jM4kbydbMccSbYVfKS', NULL, NULL, 1, NULL, '2026-06-03 22:57:23', '2026-06-03 22:57:23'),
(439, 4, 43, 'Mitch', NULL, 'Williams', NULL, 'mitch.william@gmail,com', '$2y$10$Lz.Dm19QgLEuDHI5CPPde.4/1/jGKr6pTUGqGGjq4ZctKLn4UDA3q', NULL, NULL, 1, NULL, '2026-06-03 22:57:23', '2026-06-03 22:57:23'),
(440, 4, 43, 'Sarah', NULL, 'White', NULL, 'sarah.white@gmail.com', '$2y$10$nqo7UoZndEWA4U9ez8hBQ.XgDp9hqBjqZRzZmIYNR.VkMX6MHydjG', NULL, NULL, 1, NULL, '2026-06-03 22:57:23', '2026-06-03 22:57:23'),
(441, 4, 43, 'Daryl', NULL, 'Jones', NULL, 'daryl.jones@gmail.com', '$2y$10$/6OUhu.2JNKXEGhtMDGRJOD3swbU72wt687s7L2buJFhEmzKgD1uC', NULL, NULL, 1, NULL, '2026-06-03 22:57:23', '2026-06-03 22:57:23'),
(442, 2, 39, 'SAO ES', NULL, 'Admin', NULL, '129175@deped.gov.ph', '$2y$10$AJaluAWpqKG3jLQfXiTSt.6SV8Lh5GEAn.jfj3qD9Q9y3WCOCm/7W', NULL, NULL, 1, '2026-06-04 07:13:18', '2026-06-04 07:13:11', '2026-06-04 00:30:28'),
(445, 4, 22, 'Vecente', NULL, 'Macalua', NULL, 'vicmacalua@gmail.com', '$2y$10$rbE1BXouSzMS6TvFWPszb.TT7kZVVXHnZd12TfmbJiWBW9rGFcuaO', NULL, NULL, 1, NULL, '2026-06-04 11:14:11', '2026-06-04 11:14:11'),
(446, 4, 22, 'Eula Mae ', NULL, 'Ombing', NULL, 'eulamae.ombing@deped.gov.ph', '$2y$10$mHIRVU.pU/lM4zFDVOtFM.4uB6oxdAWQzd/ZenUYFT/GYVEW16b0a', NULL, NULL, 1, NULL, '2026-06-04 11:21:12', '2026-06-04 11:21:12'),
(447, 4, 22, 'Gretchen', NULL, 'Canatoy-Pal', NULL, 'gretchen.canatoy@deped.gov.ph', '$2y$10$LxPY.g/ItjIyJ8uP4j/jOu3CTyYUIekxQ13311Za3GGC2MDwswa0C', NULL, NULL, 1, NULL, '2026-06-04 11:21:53', '2026-06-04 11:21:53'),
(448, 4, 22, 'Rechel', NULL, 'Bersales', NULL, 'rechel.bersales@deped.gov.ph', '$2y$10$qB6CJiNEJqac9yha4AM6VuEZnpO4t1DDdRMkFb6i8GQQvh76A7Rs.', NULL, NULL, 1, NULL, '2026-06-04 11:23:31', '2026-06-04 11:23:31'),
(449, 4, 22, 'Weldon', NULL, 'Sullano', NULL, 'weldon.sullano@deped.gov.ph', '$2y$10$0ogPhyrVOcQysSKZ9vWvkOTyEFGn2uF3JfPCWR8bb9ZYYc0Qy3Bda', NULL, NULL, 1, NULL, '2026-06-04 11:23:31', '2026-06-04 11:23:31'),
(450, 5, 19, 'abcdef', 'abcdef', 'abcdef', NULL, '12345685222', '$2y$10$7heee1UhJdvvnyYzNaCsae50lcOjCdadZXSpOGiCNonVTS9nHfnIe', NULL, NULL, 1, NULL, '2026-06-05 17:03:22', '2026-06-05 17:03:22'),
(451, 2, 19, 'School', '', 'Admin', '', 'newadmin@gmail.com', '$2y$10$hruyLsvEPoVRli8kPc5pLuXx8GihAJgRM1.cBwWEUbTqzWeVosekW', '', NULL, 1, NULL, '2026-06-05 17:04:55', '2026-06-05 17:04:55');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `activities`
--
ALTER TABLE `activities`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_activities_module` (`module_id`);

--
-- Indexes for table `activity_logs`
--
ALTER TABLE `activity_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_log_user` (`user_id`);

--
-- Indexes for table `announcements`
--
ALTER TABLE `announcements`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_ann_school` (`school_id`);

--
-- Indexes for table `attendance`
--
ALTER TABLE `attendance`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_attendance` (`user_id`,`course_id`,`date`),
  ADD KEY `idx_attendance_course` (`course_id`),
  ADD KEY `idx_attendance_user` (`user_id`);

--
-- Indexes for table `audit_logs`
--
ALTER TABLE `audit_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_user` (`user_id`),
  ADD KEY `idx_entity` (`entity_type`,`entity_id`),
  ADD KEY `idx_school` (`school_id`),
  ADD KEY `idx_created` (`created_at`);

--
-- Indexes for table `class_programs`
--
ALTER TABLE `class_programs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_cp_section` (`section_id`),
  ADD KEY `fk_cp_subject` (`subject_id`),
  ADD KEY `fk_cp_teacher` (`teacher_id`);

--
-- Indexes for table `courses`
--
ALTER TABLE `courses`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_courses_school` (`school_id`);

--
-- Indexes for table `course_collaborators`
--
ALTER TABLE `course_collaborators`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_collab_course` (`course_id`),
  ADD KEY `idx_collab_teacher` (`teacher_id`),
  ADD KEY `idx_collab_section` (`section_id`);

--
-- Indexes for table `course_enrollments`
--
ALTER TABLE `course_enrollments`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uk_course_user` (`course_id`,`user_id`),
  ADD KEY `idx_ce_user` (`user_id`);

--
-- Indexes for table `course_outcomes`
--
ALTER TABLE `course_outcomes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_co_subject` (`subject_id`);

--
-- Indexes for table `co_po_mapping`
--
ALTER TABLE `co_po_mapping`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_map_co` (`course_outcome_id`),
  ADD KEY `fk_map_po` (`program_outcome_id`);

--
-- Indexes for table `enrollments`
--
ALTER TABLE `enrollments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_enr_student` (`student_id`),
  ADD KEY `fk_enr_sy` (`school_year_id`),
  ADD KEY `fk_enr_section` (`section_id`),
  ADD KEY `fk_enrollments_school` (`school_id`);

--
-- Indexes for table `grade_components`
--
ALTER TABLE `grade_components`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_gc_school` (`school_id`);

--
-- Indexes for table `grade_entries`
--
ALTER TABLE `grade_entries`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_ge_enrollment` (`enrollment_id`),
  ADD KEY `fk_ge_cp` (`class_program_id`),
  ADD KEY `fk_ge_component` (`component_id`);

--
-- Indexes for table `grade_levels`
--
ALTER TABLE `grade_levels`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `code` (`code`),
  ADD KEY `idx_school_id` (`school_id`);

--
-- Indexes for table `learning_areas`
--
ALTER TABLE `learning_areas`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `learning_competencies`
--
ALTER TABLE `learning_competencies`
  ADD PRIMARY KEY (`id`),
  ADD KEY `subject_id` (`subject_id`),
  ADD KEY `school_id` (`school_id`),
  ADD KEY `created_by` (`created_by`);

--
-- Indexes for table `lessons`
--
ALTER TABLE `lessons`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_lesson_module` (`module_id`),
  ADD KEY `idx_lesson_learning_competency` (`learning_competency_id`);

--
-- Indexes for table `lesson_completions`
--
ALTER TABLE `lesson_completions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_student_lesson` (`student_id`,`lesson_id`),
  ADD KEY `idx_student_id` (`student_id`),
  ADD KEY `idx_lesson_id` (`lesson_id`);

--
-- Indexes for table `lesson_notes`
--
ALTER TABLE `lesson_notes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `lesson_id` (`lesson_id`),
  ADD KEY `school_id` (`school_id`),
  ADD KEY `created_by` (`created_by`);

--
-- Indexes for table `lesson_plans`
--
ALTER TABLE `lesson_plans`
  ADD PRIMARY KEY (`id`),
  ADD KEY `lesson_id` (`lesson_id`),
  ADD KEY `school_id` (`school_id`),
  ADD KEY `created_by` (`created_by`);

--
-- Indexes for table `lesson_progress`
--
ALTER TABLE `lesson_progress`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_lp_student` (`student_id`),
  ADD KEY `fk_lp_lesson` (`lesson_id`);

--
-- Indexes for table `lesson_taught_statuses`
--
ALTER TABLE `lesson_taught_statuses`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uniq_lesson_user_taught` (`lesson_id`,`user_id`),
  ADD KEY `idx_lesson_taught_user` (`user_id`);

--
-- Indexes for table `melcs`
--
ALTER TABLE `melcs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_melc_subject` (`subject_id`);

--
-- Indexes for table `modules`
--
ALTER TABLE `modules`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_mod_cp` (`class_program_id`),
  ADD KEY `idx_modules_subject` (`subject_id`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_notif_user` (`user_id`);

--
-- Indexes for table `parents`
--
ALTER TABLE `parents`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_parent_user` (`user_id`);

--
-- Indexes for table `parent_student`
--
ALTER TABLE `parent_student`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_ps_parent` (`parent_id`),
  ADD KEY `fk_ps_student` (`student_id`);

--
-- Indexes for table `platform_settings`
--
ALTER TABLE `platform_settings`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `setting_key` (`setting_key`);

--
-- Indexes for table `programs`
--
ALTER TABLE `programs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `program_outcomes`
--
ALTER TABLE `program_outcomes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_po_program` (`program_id`);

--
-- Indexes for table `question_bank`
--
ALTER TABLE `question_bank`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_qb_subject` (`subject_id`);

--
-- Indexes for table `quizzes`
--
ALTER TABLE `quizzes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_quiz_cp` (`class_program_id`),
  ADD KEY `fk_quiz_school` (`school_id`);

--
-- Indexes for table `quiz_attempts`
--
ALTER TABLE `quiz_attempts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_qa_quiz` (`quiz_id`),
  ADD KEY `fk_qa_student` (`student_id`);

--
-- Indexes for table `quiz_attempt_answers`
--
ALTER TABLE `quiz_attempt_answers`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_qaa_attempt` (`attempt_id`),
  ADD KEY `fk_qaa_question` (`question_id`);

--
-- Indexes for table `quiz_choices`
--
ALTER TABLE `quiz_choices`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_qc_question` (`question_id`);

--
-- Indexes for table `quiz_questions`
--
ALTER TABLE `quiz_questions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_qq_quiz` (`quiz_id`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `slug` (`slug`);

--
-- Indexes for table `rubrics`
--
ALTER TABLE `rubrics`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_rub_subject` (`subject_id`);

--
-- Indexes for table `rubric_criteria`
--
ALTER TABLE `rubric_criteria`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_rc_rubric` (`rubric_id`);

--
-- Indexes for table `schools`
--
ALTER TABLE `schools`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_confirmation_token` (`confirmation_token`);

--
-- Indexes for table `school_years`
--
ALTER TABLE `school_years`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_sy_school` (`school_id`);

--
-- Indexes for table `sections`
--
ALTER TABLE `sections`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_sec_sy` (`school_year_id`),
  ADD KEY `fk_sec_adviser` (`adviser_id`),
  ADD KEY `fk_sections_school` (`school_id`);

--
-- Indexes for table `section_enrollment_keys`
--
ALTER TABLE `section_enrollment_keys`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_section_key` (`course_id`,`section_id`),
  ADD KEY `idx_section_key_course` (`course_id`),
  ADD KEY `idx_section_key_section` (`section_id`);

--
-- Indexes for table `semesters`
--
ALTER TABLE `semesters`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_sem_sy` (`school_year_id`);

--
-- Indexes for table `shs_strands`
--
ALTER TABLE `shs_strands`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_strand_track` (`track_id`);

--
-- Indexes for table `shs_tracks`
--
ALTER TABLE `shs_tracks`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `staff`
--
ALTER TABLE `staff`
  ADD PRIMARY KEY (`IDNumber`),
  ADD KEY `fk_staff_user` (`user_id`);

--
-- Indexes for table `studentprofile`
--
ALTER TABLE `studentprofile`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_student_number_school` (`student_number`,`school_id`),
  ADD KEY `fk_sp_user` (`user_id`),
  ADD KEY `fk_sp_school` (`school_id`);

--
-- Indexes for table `students`
--
ALTER TABLE `students`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `lrn` (`lrn`),
  ADD UNIQUE KEY `student_id` (`student_id`),
  ADD KEY `fk_student_user` (`user_id`),
  ADD KEY `fk_students_school` (`school_id`);

--
-- Indexes for table `student_grades`
--
ALTER TABLE `student_grades`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_grade` (`enrollment_id`,`class_program_id`,`semester_id`),
  ADD KEY `fk_sg_enrollment` (`enrollment_id`),
  ADD KEY `fk_sg_cp` (`class_program_id`);

--
-- Indexes for table `subjects`
--
ALTER TABLE `subjects`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_subj_program` (`program_id`),
  ADD KEY `fk_subjects_school` (`school_id`);

--
-- Indexes for table `subject_teachers`
--
ALTER TABLE `subject_teachers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_subject_teacher` (`subject_id`,`user_id`);

--
-- Indexes for table `transmutation_table`
--
ALTER TABLE `transmutation_table`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `fk_users_role` (`role_id`),
  ADD KEY `fk_users_school` (`school_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `activities`
--
ALTER TABLE `activities`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `activity_logs`
--
ALTER TABLE `activity_logs`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=114;

--
-- AUTO_INCREMENT for table `announcements`
--
ALTER TABLE `announcements`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `attendance`
--
ALTER TABLE `attendance`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `audit_logs`
--
ALTER TABLE `audit_logs`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=69;

--
-- AUTO_INCREMENT for table `class_programs`
--
ALTER TABLE `class_programs`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `courses`
--
ALTER TABLE `courses`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `course_collaborators`
--
ALTER TABLE `course_collaborators`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `course_enrollments`
--
ALTER TABLE `course_enrollments`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `course_outcomes`
--
ALTER TABLE `course_outcomes`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `co_po_mapping`
--
ALTER TABLE `co_po_mapping`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `enrollments`
--
ALTER TABLE `enrollments`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=52;

--
-- AUTO_INCREMENT for table `grade_components`
--
ALTER TABLE `grade_components`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `grade_entries`
--
ALTER TABLE `grade_entries`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `grade_levels`
--
ALTER TABLE `grade_levels`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `learning_areas`
--
ALTER TABLE `learning_areas`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `learning_competencies`
--
ALTER TABLE `learning_competencies`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `lessons`
--
ALTER TABLE `lessons`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=50;

--
-- AUTO_INCREMENT for table `lesson_completions`
--
ALTER TABLE `lesson_completions`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=44;

--
-- AUTO_INCREMENT for table `lesson_notes`
--
ALTER TABLE `lesson_notes`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `lesson_plans`
--
ALTER TABLE `lesson_plans`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `lesson_progress`
--
ALTER TABLE `lesson_progress`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `lesson_taught_statuses`
--
ALTER TABLE `lesson_taught_statuses`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `melcs`
--
ALTER TABLE `melcs`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `modules`
--
ALTER TABLE `modules`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `parents`
--
ALTER TABLE `parents`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `parent_student`
--
ALTER TABLE `parent_student`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `platform_settings`
--
ALTER TABLE `platform_settings`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `programs`
--
ALTER TABLE `programs`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=169;

--
-- AUTO_INCREMENT for table `program_outcomes`
--
ALTER TABLE `program_outcomes`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `question_bank`
--
ALTER TABLE `question_bank`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `quizzes`
--
ALTER TABLE `quizzes`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `quiz_attempts`
--
ALTER TABLE `quiz_attempts`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `quiz_attempt_answers`
--
ALTER TABLE `quiz_attempt_answers`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=99;

--
-- AUTO_INCREMENT for table `quiz_choices`
--
ALTER TABLE `quiz_choices`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=578;

--
-- AUTO_INCREMENT for table `quiz_questions`
--
ALTER TABLE `quiz_questions`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=147;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `rubrics`
--
ALTER TABLE `rubrics`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `rubric_criteria`
--
ALTER TABLE `rubric_criteria`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `schools`
--
ALTER TABLE `schools`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=48;

--
-- AUTO_INCREMENT for table `school_years`
--
ALTER TABLE `school_years`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `sections`
--
ALTER TABLE `sections`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=80;

--
-- AUTO_INCREMENT for table `section_enrollment_keys`
--
ALTER TABLE `section_enrollment_keys`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `semesters`
--
ALTER TABLE `semesters`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `shs_strands`
--
ALTER TABLE `shs_strands`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `shs_tracks`
--
ALTER TABLE `shs_tracks`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `studentprofile`
--
ALTER TABLE `studentprofile`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=170;

--
-- AUTO_INCREMENT for table `students`
--
ALTER TABLE `students`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `student_grades`
--
ALTER TABLE `student_grades`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `subjects`
--
ALTER TABLE `subjects`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=59;

--
-- AUTO_INCREMENT for table `subject_teachers`
--
ALTER TABLE `subject_teachers`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=57;

--
-- AUTO_INCREMENT for table `transmutation_table`
--
ALTER TABLE `transmutation_table`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=42;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=452;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `lesson_taught_statuses`
--
ALTER TABLE `lesson_taught_statuses`
  ADD CONSTRAINT `fk_lesson_taught_lesson` FOREIGN KEY (`lesson_id`) REFERENCES `lessons` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_lesson_taught_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
