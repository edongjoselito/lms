-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 22, 2026 at 06:35 AM
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
-- Database: `lms_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `activities`
--

CREATE TABLE `activities` (
  `id` int UNSIGNED NOT NULL,
  `module_id` int UNSIGNED NOT NULL,
  `type` enum('assignment','quiz','forum','resource','page','label') NOT NULL DEFAULT 'page',
  `title` varchar(255) NOT NULL,
  `content` text,
  `settings` json DEFAULT NULL,
  `order_num` int DEFAULT '1',
  `is_published` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `activities`
--

INSERT INTO `activities` (`id`, `module_id`, `type`, `title`, `content`, `settings`, `order_num`, `is_published`, `created_at`) VALUES
(2, 3, 'page', 'title of the content', '<h2 data-path-to-node=\"0\">The Evolution of Computing: From Beads to Bits</h2><p data-path-to-node=\"1\">The history of computers isn\'t just about machines; it’s a story of humanity’s desire to automate the tedious and solve the \"impossible.\" We can break this journey down into five distinct eras.</p><hr data-path-to-node=\"2\"><h3 data-path-to-node=\"3\">1. The Pre-Electronic Era (The Dawn of Calculation)</h3><p data-path-to-node=\"4\">Before electricity, \"computers\" were people or simple mechanical tools used to track trade and stars.</p><ul data-path-to-node=\"5\"><li><p data-path-to-node=\"5,0,0\"><b data-path-to-node=\"5,0,0\" data-index-in-node=\"0\">The Abacus (c. 2700–2300 BC):</b> One of the first \"calculators\" using beads on rods.</p></li><li><p data-path-to-node=\"5,1,0\"><b data-path-to-node=\"5,1,0\" data-index-in-node=\"0\">The Pascaline (1642):</b> Blaise Pascal invented a mechanical calculator that used gears and wheels to add and subtract.</p></li><li><p data-path-to-node=\"5,2,0\"><b data-path-to-node=\"5,2,0\" data-index-in-node=\"0\">The Difference Engine (1822):</b> Charles Babbage (the \"Father of the Computer\") designed a massive steam-powered machine to calculate mathematical tables. Although never fully built in his lifetime, it laid the blueprint for modern logic.</p></li><li><p data-path-to-node=\"5,3,0\"><b data-path-to-node=\"5,3,0\" data-index-in-node=\"0\">The First Programmer:</b> Ada Lovelace recognized that Babbage’s machine could do more than just math—it could process symbols—making her the first computer programmer.</p></li></ul><hr data-path-to-node=\"6\"><h3 data-path-to-node=\"7\">2. The First Generation (1940–1956): Vacuum Tubes</h3><p data-path-to-node=\"8\">These were the behemoths. They filled entire rooms, consumed massive amounts of electricity, and generated enough heat to blow fuses constantly.</p><ul data-path-to-node=\"9\"><li><p data-path-to-node=\"9,0,0\"><b data-path-to-node=\"9,0,0\" data-index-in-node=\"0\">Technology:</b> <b data-path-to-node=\"9,0,0\" data-index-in-node=\"12\">Vacuum Tubes</b> acted as the electronic switches.</p></li><li><p data-path-to-node=\"9,1,0\"><b data-path-to-node=\"9,1,0\" data-index-in-node=\"0\">Key Machine:</b> <b data-path-to-node=\"9,1,0\" data-index-in-node=\"13\">ENIAC</b> (Electronic Numerical Integrator and Computer). It was 1,000 times faster than electro-mechanical machines but had to be \"programmed\" by manually flipping switches and plugging in cables.</p></li><li><p data-path-to-node=\"9,2,0\"><b data-path-to-node=\"9,2,0\" data-index-in-node=\"0\">Input/Output:</b> Punched cards and paper tape.</p></li></ul>3. The Second Generation (1956–1963): Transistors<p data-path-to-node=\"12\">The invention of the transistor at Bell Labs changed everything. It did the same job as a vacuum tube but was smaller, faster, cheaper, and more reliable.</p><ul data-path-to-node=\"13\"><li><p data-path-to-node=\"13,0,0\"><b data-path-to-node=\"13,0,0\" data-index-in-node=\"0\">Technology:</b> <b data-path-to-node=\"13,0,0\" data-index-in-node=\"12\">Transistors</b>.</p></li><li><p data-path-to-node=\"13,1,0\"><b data-path-to-node=\"13,1,0\" data-index-in-node=\"0\">Impact:</b> Computers became slightly smaller and more accessible to businesses and universities.</p></li><li><p data-path-to-node=\"13,2,0\"><b data-path-to-node=\"13,2,0\" data-index-in-node=\"0\">Software:</b> This era saw the birth of early high-level programming languages like <b data-path-to-node=\"13,2,0\" data-index-in-node=\"80\">COBOL</b> and <b data-path-to-node=\"13,2,0\" data-index-in-node=\"90\">FORTRAN</b>.</p></li></ul><hr data-path-to-node=\"14\"><h3 data-path-to-node=\"15\">4. The Third Generation (1964–1971): Integrated Circuits</h3><p data-path-to-node=\"16\">This was the \"miniaturization\" phase. Engineers figured out how to put many transistors onto a single silicon chip.</p><ul data-path-to-node=\"17\"><li><p data-path-to-node=\"17,0,0\"><b data-path-to-node=\"17,0,0\" data-index-in-node=\"0\">Technology:</b> <b data-path-to-node=\"17,0,0\" data-index-in-node=\"12\">Integrated Circuits (IC)</b>.</p></li><li><p data-path-to-node=\"17,1,0\"><b data-path-to-node=\"17,1,0\" data-index-in-node=\"0\">Innovation:</b> Users began interacting with computers through <b data-path-to-node=\"17,1,0\" data-index-in-node=\"59\">keyboards and monitors</b> instead of punched cards.</p></li><li><p data-path-to-node=\"17,2,0\"><b data-path-to-node=\"17,2,0\" data-index-in-node=\"0\">Operating Systems:</b> For the first time, a computer could run many different applications at once with a central program monitoring the memory.</p></li></ul><hr data-path-to-node=\"18\"><h3 data-path-to-node=\"19\">5. The Fourth Generation (1971–Present): Microprocessors</h3><p data-path-to-node=\"20\">This is the era of the \"PC\" (Personal Computer). Thousands of integrated circuits were built onto a single silicon chip—the <b data-path-to-node=\"20\" data-index-in-node=\"124\">Microprocessor</b>.</p><ul data-path-to-node=\"21\"><li><p data-path-to-node=\"21,0,0\"><b data-path-to-node=\"21,0,0\" data-index-in-node=\"0\">The Intel 4004:</b> The first microprocessor, which put all the components of a computer (CPU, memory, controls) on a single chip.</p></li><li><p data-path-to-node=\"21,1,0\"><b data-path-to-node=\"21,1,0\" data-index-in-node=\"0\">The Rise of Giants:</b> This era gave birth to Apple, Microsoft, and the IBM PC.</p></li><li><p data-path-to-node=\"21,2,0\"><b data-path-to-node=\"21,2,0\" data-index-in-node=\"0\">GUI (Graphical User Interface):</b> Moving away from text-only commands to icons and mice (perfected by the Macintosh in 1984).</p></li></ul><hr data-path-to-node=\"22\"><h3 data-path-to-node=\"23\">Summary Table: The Five Generations</h3><div class=\"horizontal-scroll-wrapper\"><div class=\"table-block-component\"><div _ngcontent-ng-c3842299341=\"\" class=\"table-block has-export-button new-table-style is-at-scroll-start is-at-scroll-end\"><div _ngcontent-ng-c3842299341=\"\" class=\"table-content\" data-hveid=\"0\" data-ved=\"0CAAQ3ecQahcKEwjCpunMiv-TAxUAAAAAHQAAAAAQJg\"><table data-path-to-node=\"24\"><thead><tr><th><span data-path-to-node=\"24,0,0,0\">Generation</span></th><th><span data-path-to-node=\"24,0,1,0\">Core Technology</span></th><th><span data-path-to-node=\"24,0,2,0\">Primary Characteristic</span></th></tr></thead><tbody><tr><td><span data-path-to-node=\"24,1,0,0\"><b data-path-to-node=\"24,1,0,0\" data-index-in-node=\"0\">1st</b></span></td><td><span data-path-to-node=\"24,1,1,0\">Vacuum Tubes</span></td><td><span data-path-to-node=\"24,1,2,0\">Huge, expensive, high heat</span></td></tr><tr><td><span data-path-to-node=\"24,2,0,0\"><b data-path-to-node=\"24,2,0,0\" data-index-in-node=\"0\">2nd</b></span></td><td><span data-path-to-node=\"24,2,1,0\">Transistors</span></td><td><span data-path-to-node=\"24,2,2,0\">Smaller, faster, more reliable</span></td></tr><tr><td><span data-path-to-node=\"24,3,0,0\"><b data-path-to-node=\"24,3,0,0\" data-index-in-node=\"0\">3rd</b></span></td><td><span data-path-to-node=\"24,3,1,0\">Integrated Circuits</span></td><td><span data-path-to-node=\"24,3,2,0\">Keyboards, monitors, multitasking</span></td></tr><tr><td><span data-path-to-node=\"24,4,0,0\"><b data-path-to-node=\"24,4,0,0\" data-index-in-node=\"0\">4th</b></span></td><td><span data-path-to-node=\"24,4,1,0\">Microprocessors</span></td><td><span data-path-to-node=\"24,4,2,0\">Personal computers, handhelds</span></td></tr><tr><td><span data-path-to-node=\"24,5,0,0\"><b data-path-to-node=\"24,5,0,0\" data-index-in-node=\"0\">5th</b></span></td><td><span data-path-to-node=\"24,5,1,0\">AI &amp; Parallel Processing</span></td><td><span data-path-to-node=\"24,5,2,0\">Voice recognition, machine learning</span></td></tr></tbody></table></div><div _ngcontent-ng-c3842299341=\"\" class=\"table-footer hide-from-message-actions\"><button _ngcontent-ng-c3842299341=\"\" class=\"mdc-button mat-mdc-button-base export-sheets-button-container mat-mdc-button mat-unthemed ng-star-inserted\"><span class=\"mat-mdc-button-persistent-ripple mdc-button__ripple\"></span><span class=\"mdc-button__label\"><span _ngcontent-ng-c3842299341=\"\" class=\"export-sheets-button\"><span _ngcontent-ng-c3842299341=\"\" class=\"export-sheets-icon\"></span><span _ngcontent-ng-c3842299341=\"\">Export to Sheets</span></span></span><span class=\"mat-focus-indicator\"></span><span class=\"mat-mdc-button-touch-target\"></span></button><button _ngcontent-ng-c3842299341=\"\" aria-label=\"Copy table\" data-test-id=\"copy-table-button\" class=\"mdc-icon-button mat-mdc-icon-button mat-mdc-button-base mat-mdc-tooltip-trigger copy-button mat-unthemed ng-star-inserted\"><span class=\"mat-mdc-button-persistent-ripple mdc-icon-button__ripple\"></span><span class=\"mat-focus-indicator\"></span><span class=\"mat-mdc-button-touch-target\"></span></button></div></div></div></div><hr data-path-to-node=\"25\"><h3 data-path-to-node=\"26\">The Future: The Fifth Generation and Beyond</h3><p data-path-to-node=\"27\">We are currently living in the transition to the Fifth Generation. This involves <b data-path-to-node=\"27\" data-index-in-node=\"81\">Artificial Intelligence (AI)</b>, quantum computing, and nanotechnology. Unlike previous generations that relied on \"brute force\" calculation, modern systems aim to simulate human-like reasoning and learn from data.</p><br>', '[]', 2, 1, '2026-04-21 21:48:47'),
(3, 5, 'assignment', 'Assignment Sample', 'Assignment Instruction', '[]', 1, 1, '2026-04-21 22:30:27'),
(4, 8, 'quiz', 'Quiz 1', '', '[]', 1, 1, '2026-04-22 09:33:55');

-- --------------------------------------------------------

--
-- Table structure for table `activity_logs`
--

CREATE TABLE `activity_logs` (
  `id` int UNSIGNED NOT NULL,
  `user_id` int UNSIGNED DEFAULT NULL,
  `action` varchar(100) NOT NULL,
  `module` varchar(50) DEFAULT NULL,
  `description` text,
  `ip_address` varchar(45) DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `announcements`
--

CREATE TABLE `announcements` (
  `id` int UNSIGNED NOT NULL,
  `school_id` int UNSIGNED DEFAULT NULL,
  `title` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `audience` enum('all','teachers','students','parents','section') NOT NULL DEFAULT 'all',
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
(5, 18, 0, '2026-04-22', '2026-04-22 12:24:03', NULL, 0, '2026-04-22 08:35:17');

-- --------------------------------------------------------

--
-- Table structure for table `audit_logs`
--

CREATE TABLE `audit_logs` (
  `id` int UNSIGNED NOT NULL,
  `user_id` int UNSIGNED NOT NULL,
  `user_name` varchar(255) NOT NULL,
  `action` enum('create','update','delete') NOT NULL,
  `entity_type` varchar(100) NOT NULL,
  `entity_id` int UNSIGNED NOT NULL,
  `entity_name` varchar(255) DEFAULT NULL,
  `description` text,
  `ip_address` varchar(45) DEFAULT NULL,
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
(19, 17, 'School Admin', 'create', 'subject', 11, 'Tech 1', 'Created subject: Tech 1', '127.0.0.1', 4, '2026-04-22 10:33:22');

-- --------------------------------------------------------

--
-- Table structure for table `class_programs`
--

CREATE TABLE `class_programs` (
  `id` int UNSIGNED NOT NULL,
  `section_id` int UNSIGNED NOT NULL,
  `subject_id` int UNSIGNED NOT NULL,
  `teacher_id` int UNSIGNED DEFAULT NULL,
  `enrollment_key` varchar(50) DEFAULT NULL,
  `semester_id` int UNSIGNED DEFAULT NULL,
  `schedule_day` varchar(20) DEFAULT NULL,
  `schedule_time_start` time DEFAULT NULL,
  `schedule_time_end` time DEFAULT NULL,
  `room` varchar(50) DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `class_programs`
--

INSERT INTO `class_programs` (`id`, `section_id`, `subject_id`, `teacher_id`, `enrollment_key`, `semester_id`, `schedule_day`, `schedule_time_start`, `schedule_time_end`, `room`, `status`) VALUES
(3, 5, 6, NULL, 'CIT1A', NULL, NULL, NULL, NULL, NULL, 1),
(4, 6, 10, NULL, '2026', NULL, NULL, NULL, NULL, NULL, 1),
(5, 6, 11, NULL, '2026', NULL, NULL, NULL, NULL, NULL, 1);

-- --------------------------------------------------------

--
-- Table structure for table `courses`
--

CREATE TABLE `courses` (
  `id` int UNSIGNED NOT NULL,
  `school_id` int UNSIGNED NOT NULL DEFAULT '1',
  `code` varchar(30) DEFAULT NULL,
  `title` varchar(255) NOT NULL,
  `description` text,
  `category` varchar(100) DEFAULT NULL,
  `enrollment_key` varchar(50) DEFAULT NULL,
  `cover_image` varchar(500) DEFAULT NULL,
  `created_by` int UNSIGNED DEFAULT NULL,
  `is_published` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `courses`
--

INSERT INTO `courses` (`id`, `school_id`, `code`, `title`, `description`, `category`, `enrollment_key`, `cover_image`, `created_by`, `is_published`, `created_at`, `updated_at`) VALUES
(1, 1, 'Eng 1', 'English 101', 'English Description Here', 'Eng', '101', NULL, 5, 1, '2026-04-21 05:01:18', '2026-04-21 06:07:24'),
(2, 1, 'Math 10', 'Mathematics', 'College Algebra', 'Math', '101', NULL, 5, 1, '2026-04-21 05:40:16', '2026-04-21 05:49:15');

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
  `role` enum('teacher','student') NOT NULL DEFAULT 'student',
  `status` enum('active','completed','dropped') NOT NULL DEFAULT 'active',
  `enrolled_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `course_enrollments`
--

INSERT INTO `course_enrollments` (`id`, `course_id`, `user_id`, `role`, `status`, `enrolled_at`) VALUES
(1, 1, 5, 'teacher', 'active', '2026-04-21 05:01:18'),
(3, 2, 5, 'teacher', 'active', '2026-04-21 05:40:16'),
(6, 2, 6, 'student', 'active', '2026-04-20 23:51:36'),
(7, 1, 9, 'student', 'active', '2026-04-21 00:06:10'),
(8, 1, 10, 'student', 'active', '2026-04-21 00:06:10'),
(9, 1, 11, 'student', 'active', '2026-04-21 00:06:10'),
(10, 1, 12, 'student', 'active', '2026-04-21 00:06:10'),
(11, 1, 6, 'student', 'active', '2026-04-21 00:07:39'),
(12, 2, 9, 'student', 'active', '2026-04-21 06:11:05'),
(13, 2, 11, 'student', 'active', '2026-04-21 06:11:05'),
(14, 2, 10, 'student', 'active', '2026-04-21 06:11:05'),
(15, 2, 12, 'student', 'active', '2026-04-21 06:11:05'),
(16, 11, 18, 'student', 'active', '2026-04-22 11:42:44'),
(17, 6, 6, 'student', 'active', '2026-04-22 11:49:56'),
(18, 10, 18, 'student', 'active', '2026-04-22 11:49:56');

-- --------------------------------------------------------

--
-- Table structure for table `course_outcomes`
--

CREATE TABLE `course_outcomes` (
  `id` int UNSIGNED NOT NULL,
  `subject_id` int UNSIGNED NOT NULL,
  `code` varchar(20) NOT NULL,
  `description` text NOT NULL,
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
  `level` enum('I','D','A') NOT NULL DEFAULT 'I'
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
  `system_type` enum('deped','ched') NOT NULL DEFAULT 'deped',
  `grade_level_id` int UNSIGNED DEFAULT NULL,
  `strand_id` int UNSIGNED DEFAULT NULL,
  `program_id` int UNSIGNED DEFAULT NULL,
  `year_level` tinyint DEFAULT NULL,
  `semester_id` int UNSIGNED DEFAULT NULL,
  `enrollment_date` date DEFAULT NULL,
  `status` enum('pending','enrolled','dropped','completed') NOT NULL DEFAULT 'pending',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `enrollments`
--

INSERT INTO `enrollments` (`id`, `student_id`, `school_id`, `school_year_id`, `section_id`, `system_type`, `grade_level_id`, `strand_id`, `program_id`, `year_level`, `semester_id`, `enrollment_date`, `status`, `created_at`) VALUES
(1, 1, 1, 1, 1, 'ched', NULL, NULL, 1, 1, 1, '2026-04-20', 'enrolled', '2026-04-20 20:44:12');

-- --------------------------------------------------------

--
-- Table structure for table `gpa_records`
--

CREATE TABLE `gpa_records` (
  `id` int UNSIGNED NOT NULL,
  `student_id` int UNSIGNED NOT NULL,
  `school_year_id` int UNSIGNED NOT NULL,
  `semester_id` int UNSIGNED DEFAULT NULL,
  `total_units` decimal(5,1) DEFAULT NULL,
  `total_grade_points` decimal(8,2) DEFAULT NULL,
  `gpa` decimal(4,3) DEFAULT NULL,
  `status` enum('regular','dean_list','probation','dismissed') DEFAULT 'regular',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `grade_components`
--

CREATE TABLE `grade_components` (
  `id` int UNSIGNED NOT NULL,
  `school_id` int UNSIGNED DEFAULT NULL,
  `system_type` enum('deped','ched') NOT NULL DEFAULT 'deped',
  `name` varchar(100) NOT NULL,
  `code` varchar(20) NOT NULL,
  `weight_percentage` decimal(5,2) NOT NULL,
  `subject_category` varchar(50) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL
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
  `activity_name` varchar(255) DEFAULT NULL,
  `score` decimal(6,2) DEFAULT NULL,
  `total_score` decimal(6,2) DEFAULT NULL,
  `remarks` varchar(255) DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `grade_levels`
--

CREATE TABLE `grade_levels` (
  `id` int UNSIGNED NOT NULL,
  `school_id` int UNSIGNED NOT NULL DEFAULT '1',
  `name` varchar(50) NOT NULL,
  `code` varchar(10) NOT NULL,
  `level_order` tinyint NOT NULL,
  `category` enum('elementary','junior_high','senior_high') NOT NULL,
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
  `name` varchar(150) NOT NULL,
  `code` varchar(20) NOT NULL,
  `category` enum('core','applied','specialized','elective') NOT NULL DEFAULT 'core'
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
-- Table structure for table `lessons`
--

CREATE TABLE `lessons` (
  `id` int UNSIGNED NOT NULL,
  `module_id` int UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `content` longtext,
  `content_type` enum('text','page','file','video','link') NOT NULL DEFAULT 'text',
  `file_path` varchar(500) DEFAULT NULL,
  `external_url` varchar(500) DEFAULT NULL,
  `attachment_path` varchar(500) DEFAULT NULL,
  `duration_minutes` int DEFAULT NULL,
  `order_num` int DEFAULT '1',
  `is_published` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `lessons`
--

INSERT INTO `lessons` (`id`, `module_id`, `title`, `content`, `content_type`, `file_path`, `external_url`, `attachment_path`, `duration_minutes`, `order_num`, `is_published`, `created_at`) VALUES
(1, 1, 'Lesson 1', '<div class=\"Y3BBE\" data-sfc-cp=\"\" data-sfc-root=\"c\" data-sfc-cb=\"\" data-hveid=\"CAEQAA\"><span data-subtree=\"aimfl,mfl\">English\r\n 101 focuses on foundational communication, covering basic greetings, \r\ndaily activities, and simple questions for beginners</span>. Discussions revolve around <mark class=\"HxTRcb\" data-sfc-root=\"c\" data-sfc-cb=\"\">improving conversational fluency through topics like weather, shopping, and, routines</mark>.\r\n Key skills include using present/past tenses, basic grammar, and, \r\nsentence structure to build confidence in real-world scenarios.<span class=\"uJ19be notranslate\" data-sfc-root=\"c\" data-wiz-uids=\"gMT71c_h,gMT71c_i\" data-sfc-cb=\"\"><span class=\"vKEkVd\" data-animation-atomic=\"\" data-wiz-attrbind=\"class=gMT71c_g/TKHnVd\"><span aria-hidden=\"true\">&nbsp;</span></span></span></div><div class=\"Y3BBE\" data-sfc-cp=\"\" data-sfc-root=\"c\" data-sfc-cb=\"\" data-hveid=\"CAIQAA\"><strong class=\"Yjhzub\" data-sfc-root=\"c\" data-sfc-cb=\"\">Key Topics in English 101 Discussions</strong><span class=\"txxDge notranslate\" data-sfc-root=\"c\" data-wiz-uids=\"gMT71c_r,gMT71c_s\" data-sfc-cb=\"\"><span class=\"vKEkVd\" data-animation-atomic=\"\" data-wiz-attrbind=\"class=gMT71c_q/TKHnVd\"><span aria-hidden=\"true\"></span></span></span></div><ul class=\"KsbFXc U6u95\" data-sfc-root=\"c\" data-sfc-cb=\"\"><li class=\"dF3vjf\" data-sfc-root=\"c\" data-sfc-cb=\"\" data-hveid=\"CAMQAA\"><span class=\"T286Pc\" data-sfc-cp=\"\" data-sfc-root=\"c\" data-sfc-cb=\"\"><strong class=\"Yjhzub\" data-sfc-root=\"c\" data-sfc-cb=\"\"><span data-sfc-root=\"c\" data-wiz-uids=\"gMT71c_y\" data-sfc-cb=\"\"><a class=\"GI370e\" data-ved=\"2ahUKEwicpqGqqv2TAxV5rlYBHUtULAAQgK4QegQIAxAB\" data-hveid=\"CAMQAQ\" href=\"https://www.google.com/search?q=Daily+Conversations&amp;sca_esv=23368486d762886d&amp;sxsrf=ANbL-n4WUsxyd7hhhGCzWGDVbiABueDtiQ%3A1776719056430&amp;source=hp&amp;ei=0JTmacOrGPnQ2roP1eiwoAc&amp;iflsig=AFdpzrgAAAAAaeai4LhEL4agfKAXDDMlkYZF9G01k3nn&amp;ved=2ahUKEwicpqGqqv2TAxV5rlYBHUtULAAQgK4QegQIAxAB&amp;uact=5&amp;oq=English+101+discussions&amp;gs_lp=Egdnd3Mtd2l6IhdFbmdsaXNoIDEwMSBkaXNjdXNzaW9uczIGEAAYFhgeMgYQABgWGB4yBhAAGBYYHjILEAAYgAQYigUYhgMyCxAAGIAEGIoFGIYDMgUQABjvBTIFEAAY7wUyCBAAGIkFGKIEMgUQABjvBTIIEAAYiQUYogRInTRQAFiTMnAGeACQAQCYAawBoAGwF6oBBDkuMTi4AQPIAQD4AQGYAiGgApsYwgIEECMYJ8ICCBAAGIAEGLEDwgIOEAAYgAQYigUYsQMYgwHCAgsQABiABBixAxiDAcICBRAAGIAEwgIIEAAYgAQYogTCAgQQIRgVwgIHECEYChigAcICBRAhGKABmAMAkgcFMTUuMTigB_NlsgcEOS4xOLgHkRjCBwYxLjI3LjXIBzWACAE&amp;sclient=gws-wiz&amp;mstk=AUtExfD8hv7Ik3S0jXdVlozfOk3kjdFZkMvnJ-4BSrgXK7vU2RDxgyjGYJMvJvX9XCF-jS10As0ZgO_0tjicLCQf9D1mTnmsM0-Mt4aJgiatVW92Nn_aGHeYMgYEVOwecwqlmUYQ2WOsD-grRD9CAvJu-ZoDGI06dMF8oMSDzOOwKmRyW8AWAosAAD595n0onN1BngXvAmZ8NDV_sAtk9E_l6Dr8ediqvPf2uEOr2E74iEzbnuzR63AckEjXAgKmEVCUH3JlEiFddE9YeKRzJsjiGHNZg3-oUF51xseXc5x5AMJpKw&amp;csui=3\">Daily Conversations</a></span>:</strong> Practical dialogues for shopping at a boutique, navigating a date, or discussing weekend plans.</span></li><li class=\"dF3vjf\" data-sfc-root=\"c\" data-sfc-cb=\"\" data-hveid=\"CAMQAg\"><span class=\"T286Pc\" data-sfc-cp=\"\" data-sfc-root=\"c\" data-sfc-cb=\"\"><strong class=\"Yjhzub\" data-sfc-root=\"c\" data-sfc-cb=\"\"><span data-sfc-root=\"c\" data-wiz-uids=\"gMT71c_13\" data-sfc-cb=\"\"><a class=\"GI370e\" data-ved=\"2ahUKEwicpqGqqv2TAxV5rlYBHUtULAAQgK4QegQIAxAD\" data-hveid=\"CAMQAw\" href=\"https://www.google.com/search?q=Essential+Grammar&amp;sca_esv=23368486d762886d&amp;sxsrf=ANbL-n4WUsxyd7hhhGCzWGDVbiABueDtiQ%3A1776719056430&amp;source=hp&amp;ei=0JTmacOrGPnQ2roP1eiwoAc&amp;iflsig=AFdpzrgAAAAAaeai4LhEL4agfKAXDDMlkYZF9G01k3nn&amp;ved=2ahUKEwicpqGqqv2TAxV5rlYBHUtULAAQgK4QegQIAxAD&amp;uact=5&amp;oq=English+101+discussions&amp;gs_lp=Egdnd3Mtd2l6IhdFbmdsaXNoIDEwMSBkaXNjdXNzaW9uczIGEAAYFhgeMgYQABgWGB4yBhAAGBYYHjILEAAYgAQYigUYhgMyCxAAGIAEGIoFGIYDMgUQABjvBTIFEAAY7wUyCBAAGIkFGKIEMgUQABjvBTIIEAAYiQUYogRInTRQAFiTMnAGeACQAQCYAawBoAGwF6oBBDkuMTi4AQPIAQD4AQGYAiGgApsYwgIEECMYJ8ICCBAAGIAEGLEDwgIOEAAYgAQYigUYsQMYgwHCAgsQABiABBixAxiDAcICBRAAGIAEwgIIEAAYgAQYogTCAgQQIRgVwgIHECEYChigAcICBRAhGKABmAMAkgcFMTUuMTigB_NlsgcEOS4xOLgHkRjCBwYxLjI3LjXIBzWACAE&amp;sclient=gws-wiz&amp;mstk=AUtExfD8hv7Ik3S0jXdVlozfOk3kjdFZkMvnJ-4BSrgXK7vU2RDxgyjGYJMvJvX9XCF-jS10As0ZgO_0tjicLCQf9D1mTnmsM0-Mt4aJgiatVW92Nn_aGHeYMgYEVOwecwqlmUYQ2WOsD-grRD9CAvJu-ZoDGI06dMF8oMSDzOOwKmRyW8AWAosAAD595n0onN1BngXvAmZ8NDV_sAtk9E_l6Dr8ediqvPf2uEOr2E74iEzbnuzR63AckEjXAgKmEVCUH3JlEiFddE9YeKRzJsjiGHNZg3-oUF51xseXc5x5AMJpKw&amp;csui=3\">Essential Grammar</a></span>:</strong> Focusing on noun usage (count vs. non-count), proper use of adjectives, and comparatives.</span></li><li class=\"dF3vjf\" data-sfc-root=\"c\" data-sfc-cb=\"\" data-hveid=\"CAMQBA\"><span class=\"T286Pc\" data-sfc-cp=\"\" data-sfc-root=\"c\" data-sfc-cb=\"\"><strong class=\"Yjhzub\" data-sfc-root=\"c\" data-sfc-cb=\"\"><span data-sfc-root=\"c\" data-wiz-uids=\"gMT71c_18\" data-sfc-cb=\"\"><a class=\"GI370e\" data-ved=\"2ahUKEwicpqGqqv2TAxV5rlYBHUtULAAQgK4QegQIAxAF\" data-hveid=\"CAMQBQ\" href=\"https://www.google.com/search?q=Basic+Questions&amp;sca_esv=23368486d762886d&amp;sxsrf=ANbL-n4WUsxyd7hhhGCzWGDVbiABueDtiQ%3A1776719056430&amp;source=hp&amp;ei=0JTmacOrGPnQ2roP1eiwoAc&amp;iflsig=AFdpzrgAAAAAaeai4LhEL4agfKAXDDMlkYZF9G01k3nn&amp;ved=2ahUKEwicpqGqqv2TAxV5rlYBHUtULAAQgK4QegQIAxAF&amp;uact=5&amp;oq=English+101+discussions&amp;gs_lp=Egdnd3Mtd2l6IhdFbmdsaXNoIDEwMSBkaXNjdXNzaW9uczIGEAAYFhgeMgYQABgWGB4yBhAAGBYYHjILEAAYgAQYigUYhgMyCxAAGIAEGIoFGIYDMgUQABjvBTIFEAAY7wUyCBAAGIkFGKIEMgUQABjvBTIIEAAYiQUYogRInTRQAFiTMnAGeACQAQCYAawBoAGwF6oBBDkuMTi4AQPIAQD4AQGYAiGgApsYwgIEECMYJ8ICCBAAGIAEGLEDwgIOEAAYgAQYigUYsQMYgwHCAgsQABiABBixAxiDAcICBRAAGIAEwgIIEAAYgAQYogTCAgQQIRgVwgIHECEYChigAcICBRAhGKABmAMAkgcFMTUuMTigB_NlsgcEOS4xOLgHkRjCBwYxLjI3LjXIBzWACAE&amp;sclient=gws-wiz&amp;mstk=AUtExfD8hv7Ik3S0jXdVlozfOk3kjdFZkMvnJ-4BSrgXK7vU2RDxgyjGYJMvJvX9XCF-jS10As0ZgO_0tjicLCQf9D1mTnmsM0-Mt4aJgiatVW92Nn_aGHeYMgYEVOwecwqlmUYQ2WOsD-grRD9CAvJu-ZoDGI06dMF8oMSDzOOwKmRyW8AWAosAAD595n0onN1BngXvAmZ8NDV_sAtk9E_l6Dr8ediqvPf2uEOr2E74iEzbnuzR63AckEjXAgKmEVCUH3JlEiFddE9YeKRzJsjiGHNZg3-oUF51xseXc5x5AMJpKw&amp;csui=3\">Basic Questions</a></span>:</strong> Learning to ask about present, past, and future actions, including asking about plans.</span></li><li class=\"dF3vjf\" data-sfc-root=\"c\" data-sfc-cb=\"\" data-hveid=\"CAMQBg\"><span class=\"T286Pc\" data-sfc-cp=\"\" data-sfc-root=\"c\" data-sfc-cb=\"\"><strong class=\"Yjhzub\" data-sfc-root=\"c\" data-sfc-cb=\"\"><span data-sfc-root=\"c\" data-wiz-uids=\"gMT71c_1d\" data-sfc-cb=\"\"><a class=\"GI370e\" data-ved=\"2ahUKEwicpqGqqv2TAxV5rlYBHUtULAAQgK4QegQIAxAH\" data-hveid=\"CAMQBw\" href=\"https://www.google.com/search?q=Fundamental+Vocabulary&amp;sca_esv=23368486d762886d&amp;sxsrf=ANbL-n4WUsxyd7hhhGCzWGDVbiABueDtiQ%3A1776719056430&amp;source=hp&amp;ei=0JTmacOrGPnQ2roP1eiwoAc&amp;iflsig=AFdpzrgAAAAAaeai4LhEL4agfKAXDDMlkYZF9G01k3nn&amp;ved=2ahUKEwicpqGqqv2TAxV5rlYBHUtULAAQgK4QegQIAxAH&amp;uact=5&amp;oq=English+101+discussions&amp;gs_lp=Egdnd3Mtd2l6IhdFbmdsaXNoIDEwMSBkaXNjdXNzaW9uczIGEAAYFhgeMgYQABgWGB4yBhAAGBYYHjILEAAYgAQYigUYhgMyCxAAGIAEGIoFGIYDMgUQABjvBTIFEAAY7wUyCBAAGIkFGKIEMgUQABjvBTIIEAAYiQUYogRInTRQAFiTMnAGeACQAQCYAawBoAGwF6oBBDkuMTi4AQPIAQD4AQGYAiGgApsYwgIEECMYJ8ICCBAAGIAEGLEDwgIOEAAYgAQYigUYsQMYgwHCAgsQABiABBixAxiDAcICBRAAGIAEwgIIEAAYgAQYogTCAgQQIRgVwgIHECEYChigAcICBRAhGKABmAMAkgcFMTUuMTigB_NlsgcEOS4xOLgHkRjCBwYxLjI3LjXIBzWACAE&amp;sclient=gws-wiz&amp;mstk=AUtExfD8hv7Ik3S0jXdVlozfOk3kjdFZkMvnJ-4BSrgXK7vU2RDxgyjGYJMvJvX9XCF-jS10As0ZgO_0tjicLCQf9D1mTnmsM0-Mt4aJgiatVW92Nn_aGHeYMgYEVOwecwqlmUYQ2WOsD-grRD9CAvJu-ZoDGI06dMF8oMSDzOOwKmRyW8AWAosAAD595n0onN1BngXvAmZ8NDV_sAtk9E_l6Dr8ediqvPf2uEOr2E74iEzbnuzR63AckEjXAgKmEVCUH3JlEiFddE9YeKRzJsjiGHNZg3-oUF51xseXc5x5AMJpKw&amp;csui=3\">Fundamental Vocabulary</a></span>:</strong> Topics include describing emotions (like envy/jealousy), discussing meals, and daily tasks.</span></li><li class=\"dF3vjf\" data-sfc-root=\"c\" data-sfc-cb=\"\" data-hveid=\"CAMQCA\"><span class=\"T286Pc\" data-sfc-cp=\"\" data-sfc-root=\"c\" data-sfc-cb=\"\"><strong class=\"Yjhzub\" data-sfc-root=\"c\" data-sfc-cb=\"\"><span data-sfc-root=\"c\" data-wiz-uids=\"gMT71c_1i\" data-sfc-cb=\"\"><a class=\"GI370e\" data-ved=\"2ahUKEwicpqGqqv2TAxV5rlYBHUtULAAQgK4QegQIAxAJ\" data-hveid=\"CAMQCQ\" href=\"https://www.google.com/search?q=Cultural+Context&amp;sca_esv=23368486d762886d&amp;sxsrf=ANbL-n4WUsxyd7hhhGCzWGDVbiABueDtiQ%3A1776719056430&amp;source=hp&amp;ei=0JTmacOrGPnQ2roP1eiwoAc&amp;iflsig=AFdpzrgAAAAAaeai4LhEL4agfKAXDDMlkYZF9G01k3nn&amp;ved=2ahUKEwicpqGqqv2TAxV5rlYBHUtULAAQgK4QegQIAxAJ&amp;uact=5&amp;oq=English+101+discussions&amp;gs_lp=Egdnd3Mtd2l6IhdFbmdsaXNoIDEwMSBkaXNjdXNzaW9uczIGEAAYFhgeMgYQABgWGB4yBhAAGBYYHjILEAAYgAQYigUYhgMyCxAAGIAEGIoFGIYDMgUQABjvBTIFEAAY7wUyCBAAGIkFGKIEMgUQABjvBTIIEAAYiQUYogRInTRQAFiTMnAGeACQAQCYAawBoAGwF6oBBDkuMTi4AQPIAQD4AQGYAiGgApsYwgIEECMYJ8ICCBAAGIAEGLEDwgIOEAAYgAQYigUYsQMYgwHCAgsQABiABBixAxiDAcICBRAAGIAEwgIIEAAYgAQYogTCAgQQIRgVwgIHECEYChigAcICBRAhGKABmAMAkgcFMTUuMTigB_NlsgcEOS4xOLgHkRjCBwYxLjI3LjXIBzWACAE&amp;sclient=gws-wiz&amp;mstk=AUtExfD8hv7Ik3S0jXdVlozfOk3kjdFZkMvnJ-4BSrgXK7vU2RDxgyjGYJMvJvX9XCF-jS10As0ZgO_0tjicLCQf9D1mTnmsM0-Mt4aJgiatVW92Nn_aGHeYMgYEVOwecwqlmUYQ2WOsD-grRD9CAvJu-ZoDGI06dMF8oMSDzOOwKmRyW8AWAosAAD595n0onN1BngXvAmZ8NDV_sAtk9E_l6Dr8ediqvPf2uEOr2E74iEzbnuzR63AckEjXAgKmEVCUH3JlEiFddE9YeKRzJsjiGHNZg3-oUF51xseXc5x5AMJpKw&amp;csui=3\">Cultural Context</a></span>:</strong> Understanding nuances for situations like visiting an American restaurant or traveling.</span><span class=\"uJ19be notranslate\" data-sfc-root=\"c\" data-wiz-uids=\"gMT71c_1k,gMT71c_1l\" data-sfc-cb=\"\"><span class=\"vKEkVd\" data-animation-atomic=\"\" data-wiz-attrbind=\"class=gMT71c_1j/TKHnVd\"><span aria-hidden=\"true\">&nbsp;</span></span></span></li></ul><div class=\"Y3BBE\" data-sfc-cp=\"\" data-sfc-root=\"c\" data-sfc-cb=\"\" data-hveid=\"CAQQAA\"><strong class=\"Yjhzub\" data-sfc-root=\"c\" data-sfc-cb=\"\">Key Skills Developed</strong><span class=\"txxDge notranslate\" data-sfc-root=\"c\" data-wiz-uids=\"gMT71c_1w,gMT71c_1x\" data-sfc-cb=\"\"><span class=\"vKEkVd\" data-animation-atomic=\"\" data-wiz-attrbind=\"class=gMT71c_1v/TKHnVd\"><span aria-hidden=\"true\"></span></span></span></div><ul class=\"KsbFXc U6u95\" data-sfc-root=\"c\" data-sfc-cb=\"\"><li class=\"dF3vjf\" data-sfc-root=\"c\" data-sfc-cb=\"\" data-hveid=\"CAUQAA\"><span class=\"T286Pc\" data-sfc-cp=\"\" data-sfc-root=\"c\" data-sfc-cb=\"\"><strong class=\"Yjhzub\" data-sfc-root=\"c\" data-sfc-cb=\"\">Vocabulary Building:</strong> Learning essential phrases to describe everyday life.</span></li><li class=\"dF3vjf\" data-sfc-root=\"c\" data-sfc-cb=\"\" data-hveid=\"CAUQAQ\"><span class=\"T286Pc\" data-sfc-cp=\"\" data-sfc-root=\"c\" data-sfc-cb=\"\"><strong class=\"Yjhzub\" data-sfc-root=\"c\" data-sfc-cb=\"\">Sentence Structure:</strong> Constructing simple, correct sentences to communicate clearly.</span></li><li class=\"dF3vjf\" data-sfc-root=\"c\" data-sfc-cb=\"\" data-hveid=\"CAUQAg\"><span class=\"T286Pc\" data-sfc-cp=\"\" data-sfc-root=\"c\" data-sfc-cb=\"\"><strong class=\"Yjhzub\" data-sfc-root=\"c\" data-sfc-cb=\"\">Confidence Building:</strong> Practicing greetings and introductions to overcome the fear of making mistakes.</span><span class=\"uJ19be notranslate\" data-sfc-root=\"c\" data-wiz-uids=\"gMT71c_29,gMT71c_2a\" data-sfc-cb=\"\"><span class=\"vKEkVd\" data-animation-atomic=\"\" data-wiz-attrbind=\"class=gMT71c_28/TKHnVd\"><span aria-hidden=\"true\">&nbsp;</span></span></span></li></ul><p><br></p>', 'text', NULL, NULL, NULL, NULL, 1, 1, '2026-04-21 05:05:13'),
(2, 1, 'Lesson 2', '', 'video', NULL, 'https://www.youtube.com/watch?v=t-g89HRlFo4', NULL, NULL, 2, 1, '2026-04-21 05:06:09'),
(3, 1, 'Lesson 3', '<p>fasdfsddsfsd sfsadfsfd</p>', 'text', NULL, NULL, NULL, NULL, 3, 1, '2026-04-21 05:07:22'),
(4, 1, 'Lesson About Computer', '<p>Being a modern-day kid you must have used, seen, or read about \r\ncomputers. This is because they are an integral part of our everyday \r\nexistence. Be it school, banks, shops, railway stations, hospital or \r\nyour own home, computers are present everywhere, making our work easier \r\nand faster for us. As they are such integral parts of our lives, we must\r\n know what they are and how they function. Let us start with defining \r\nthe term computer formally.</p>\r\n<p>The literal meaning of computer is a device that can calculate. However, modern computers can do a lot more than calculate. <b>Computer</b>\r\n is an electronic device that receives input, stores or processes the \r\ninput as per user instructions and provides output in desired format.</p>\r\n<h2>Input-Process-Output Model</h2>\r\n<p>Computer input is called <b>data</b> and the output obtained after processing it, based on users instructions is called <b>information</b>. Raw facts and figures which can be processed using arithmetic and logical operations to obtain information are called <b>data</b>.</p>\r\n<img src=\"https://www.tutorialspoint.com/basics_of_computers/images/workflow.jpg\" alt=\"Workflow\">\r\n<p>The processes that can be applied to data are of two types −</p>\r\n<ul class=\"list\"><li><p><b>Arithmetic operations</b> − Examples include calculations like addition, subtraction, differentials, square root, etc.</p></li><li><b>Logical operations</b> − Examples include comparison operations like greater than, less than, equal to, opposite, etc.</li></ul>\r\n<p>The corresponding figure for an actual computer looks something like this −</p>\r\n<img src=\"https://www.tutorialspoint.com/basics_of_computers/images/block_diagram.jpg\" alt=\"Block Diagram\">\r\n<p>The basic parts of a computer are as follows −</p>\r\n<ul class=\"list\"><li><p><b>Input Unit</b> − Devices like keyboard and mouse that are used to input data and instructions to the computer are called input unit.</p></li><li><p><b>Output Unit</b> − Devices like printer and visual display unit\r\n that are used to provide information to the user in desired format are \r\ncalled output unit.</p></li><li><p><b>Control Unit</b> − As the name suggests, this unit controls \r\nall the functions of the computer. All devices or parts of computer \r\ninteract through the control unit.</p></li><li><p><b>Arithmetic Logic Unit</b> − This is the brain of the computer where all arithmetic operations and logical operations take place.</p></li><li><p><b>Memory</b> − All input data, instructions and data interim to the processes are stored in the memory. Memory is of two types  <b>primary memory</b> and <b>secondary memory</b>. Primary memory resides within the CPU whereas secondary memory is external to it.</p></li></ul><p>Control unit, arithmetic logic unit and memory are together called the <b>central processing unit</b> or <b>CPU</b>. Computer devices like keyboard, mouse, printer, etc. that we can see and touch are the <b>hardware</b>\r\n components of a computer. The set of instructions or programs that make\r\n the computer function using these hardware parts are called <b>software</b>. We cannot see or touch software. Both hardware and software are necessary for working of a computer.</p><ul class=\"list\"><li><p><b>Speed</b> − Typically, a computer can carry out 3-4 million instructions per second.</p></li><li><p><b>Accuracy</b> − Computers exhibit a very high degree of \r\naccuracy. Errors that may occur are usually due to inaccurate data, \r\nwrong instructions or bug in chips  all human errors.</p></li><li><p><b>Reliability</b> − Computers can carry out same type of work \r\nrepeatedly without throwing up errors due to tiredness or boredom, which\r\n are very common among humans.</p></li><li><p><b>Versatility</b> − Computers can carry out a wide range of work\r\n from data entry and ticket booking to complex mathematical calculations\r\n and continuous astronomical observations. If you can input the \r\nnecessary data with correct instructions, computer will do the \r\nprocessing.</p></li><li><p><b>Storage Capacity</b> − Computers can store a very large amount\r\n of data at a fraction of cost of traditional storage of files. Also, \r\ndata is safe from normal wear and tear associated with paper</p></li></ul><p><br></p>', 'text', NULL, NULL, NULL, NULL, 4, 1, '2026-04-21 05:16:08'),
(5, 2, 'Sample PDF Lesson', '', 'file', 'uploads/lessons/c587fe44fa8f014285a6289b9febc590.pdf', NULL, NULL, NULL, 1, 1, '2026-04-21 05:27:04'),
(6, 3, 'You are here today', 'https://www.youtube.com/watch?v=e_ZJ1Ho9r20', 'video', NULL, NULL, NULL, 0, 1, 1, '2026-04-21 22:06:59'),
(7, 5, 'Lesson ', '<div class=\"lesson-video-embed ratio ratio-16x9 mb-3\" data-video-url=\"https://www.youtube.com/watch?v=e_ZJ1Ho9r20\"><iframe src=\"https://www.youtube.com/embed/e_ZJ1Ho9r20\" title=\"Lesson video\" allow=\"accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share\" allowfullscreen></iframe></div>\n<div class=\"lesson-video-notes\">The history of computers spans from ancient abacus tools to modern AI, transitioning from mechanical devices (1800s) to electronic, digital systems (1940s-present). Key milestones include Babbage\'s Analytical Engine, vacuum tubes (ENIAC), transistors, integrated circuits, and microprocessors. The 1970s microcomputer revolution, led by Apple and IBM, popularized personal computing.&nbsp;<br>Key Eras in Computer History<br><br>&nbsp; &nbsp; Early Calculating Devices (Pre-20th Century): The Abacus (3000 BC) was a foundational calculating tool. In 1642, Blaise Pascal invented the Pascaline, an early mechanical digital computer for addition.<br>&nbsp; &nbsp; The \"Father of Computers\": Charles Babbage designed the Analytical Engine (1830s), a mechanical, programmable computer, laying the groundwork for modern computers.<br>&nbsp; &nbsp; First-Generation Computers (1940s-1950s): Used vacuum tubes, these were large and power-hungry. Examples include the ENIAC (1943-1945) and UNIVAC (1951).<br>&nbsp; &nbsp; Second-Generation Computers (Late 1950s-1960s): Transistors replaced vacuum tubes, making computers smaller, faster, and more efficient.<br>&nbsp; &nbsp; Third-Generation Computers (1960s-1970s): Integrated circuits (ICs) combined multiple transistors on a single silicon chip.<br>&nbsp; &nbsp; Fourth-Generation Computers (1971-Present): Microprocessors (VLSI - Very Large Scale Integration) enabled personal computers (PCs). The Altair 8800 (1975) is often considered the first personal computer.<br>&nbsp; &nbsp; Modern Computing: The 1980s saw the IBM PC (1981) and Apple\'s Macintosh, driving home computing.&nbsp;<br><br>Key Historical Developments<br><br>&nbsp; &nbsp; Programmable Machines: Conrad Zuse\'s Z3 (1941) was the first working, programmable digital computer.<br>&nbsp; &nbsp; Computer Languages: The first programming languages, such as COBOL, were developed in the mid-1950s.<br>&nbsp; &nbsp; Graphical User Interface (GUI): Apple introduced the Lisa in 1983, bringing the GUI to personal computers.&nbsp;<br><br>Note: The history of computers also includes significant contributions to digital storage and the internet, facilitating the modern digital age.&nbsp;</div>', 'video', NULL, NULL, NULL, 0, 1, 1, '2026-04-21 22:11:20'),
(8, 6, 'Computer History', '<div align=\"left\">The history of computers spans from ancient abacus tools to modern AI, transitioning from mechanical devices (1800s) to electronic, digital systems (1940s-present). Key milestones include Babbage\'s Analytical Engine, vacuum tubes (ENIAC), transistors, integrated circuits, and microprocessors. The 1970s microcomputer revolution, led by Apple and IBM, popularized personal computing.&nbsp;<br>Key Eras in Computer History<br><br>Early Calculating Devices (Pre-20th Century): The Abacus (3000 BC) was a foundational calculating tool. In 1642, Blaise Pascal invented the Pascaline, an early mechanical digital computer for addition.<br>The \"Father of Computers\": Charles Babbage designed the Analytical Engine (1830s), a mechanical, programmable computer, laying the groundwork for modern computers.<br>&nbsp; &nbsp; First-Generation Computers (1940s-1950s): Used vacuum tubes, these were large and power-hungry. Examples include the ENIAC (1943-1945) and UNIVAC (1951).<br>&nbsp; &nbsp; Second-Generation Computers (Late 1950s-1960s): Transistors replaced vacuum tubes, making computers smaller, faster, and more efficient.<br>&nbsp; &nbsp; Third-Generation Computers (1960s-1970s): Integrated circuits (ICs) combined multiple transistors on a single silicon chip.<br>&nbsp; &nbsp; Fourth-Generation Computers (1971-Present): Microprocessors (VLSI - Very Large Scale Integration) enabled personal computers (PCs). The Altair 8800 (1975) is often considered the first personal computer.<br>&nbsp; &nbsp; Modern Computing: The 1980s saw the IBM PC (1981) and Apple\'s Macintosh, driving home computing.&nbsp;<br><br>Key Historical Developments<br><br>&nbsp; &nbsp; Programmable Machines: Conrad Zuse\'s Z3 (1941) was the first working, programmable digital computer.<br>&nbsp; &nbsp; Computer Languages: The first programming languages, such as COBOL, were developed in the mid-1950s.<br>&nbsp; &nbsp; Graphical User Interface (GUI): Apple introduced the Lisa in 1983, bringing the GUI to personal computers.</div>', 'text', NULL, NULL, NULL, NULL, 1, 1, '2026-04-22 09:11:36'),
(9, 6, 'Computer Networking', '<div class=\"lesson-video-embed ratio ratio-16x9 mb-3\" data-video-url=\"https://www.youtube.com/watch?v=F4rYmV4nvu0\"><iframe src=\"https://www.youtube.com/embed/F4rYmV4nvu0\" title=\"Lesson video\" allow=\"accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share\" allowfullscreen></iframe></div>\n<div class=\"lesson-video-notes\">Discussion here</div>', 'video', NULL, NULL, NULL, NULL, 2, 1, '2026-04-22 09:12:32'),
(10, 7, 'External Link Lesson', '<div class=\"lesson-link-embed mb-3\" data-link-url=\"https://google.com\"><a href=\"https://google.com\" target=\"_blank\" rel=\"noopener\" class=\"btn btn-outline-success\"><i class=\"bi bi-box-arrow-up-right me-1\"></i>Open External Link</a></div>\nDescription of the link here', 'link', NULL, NULL, NULL, NULL, 1, 1, '2026-04-22 09:16:25'),
(11, 7, 'Lesson - File Uploading', 'File Desription', 'file', NULL, NULL, NULL, NULL, 2, 1, '2026-04-22 09:17:19'),
(12, 9, 'Lesson 1 - Text and HTML', 'Lesson 1 content', 'text', NULL, NULL, NULL, NULL, 1, 1, '2026-04-22 10:34:15'),
(13, 9, 'Lesson 2 - File Upload', 'File Upload Description', 'file', NULL, NULL, NULL, NULL, 2, 1, '2026-04-22 10:34:52'),
(14, 10, 'Lesson 1 for Module 2 - Video Lesson', '<div class=\"lesson-video-embed ratio ratio-16x9 mb-3\" data-video-url=\"https://www.youtube.com/watch?v=6jKRownc1io\"><iframe src=\"https://www.youtube.com/embed/6jKRownc1io\" title=\"Lesson video\" allow=\"accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share\" allowfullscreen></iframe></div>\n<div class=\"lesson-video-notes\">Video Lesson Description</div>', 'video', NULL, NULL, NULL, NULL, 1, 1, '2026-04-22 10:36:05'),
(15, 10, 'Lesson 2 for Module 2 - External Link', 'External Link Description here', 'text', NULL, NULL, NULL, NULL, 2, 1, '2026-04-22 10:36:59');

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
(1, 4, 6, '2026-04-22 06:30:25'),
(2, 5, 10, '2026-04-22 10:25:43'),
(4, 5, 14, '2026-04-22 11:53:12'),
(5, 5, 15, '2026-04-22 11:53:14'),
(6, 5, 12, '2026-04-22 11:53:37'),
(7, 5, 13, '2026-04-22 11:54:33');

-- --------------------------------------------------------

--
-- Table structure for table `lesson_progress`
--

CREATE TABLE `lesson_progress` (
  `id` int UNSIGNED NOT NULL,
  `student_id` int UNSIGNED NOT NULL,
  `lesson_id` int UNSIGNED NOT NULL,
  `status` enum('not_started','in_progress','completed') DEFAULT 'not_started',
  `progress_percent` tinyint DEFAULT '0',
  `completed_at` datetime DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `lesson_progress`
--

INSERT INTO `lesson_progress` (`id`, `student_id`, `lesson_id`, `status`, `progress_percent`, `completed_at`, `created_at`) VALUES
(6, 6, 1, 'completed', 100, '2026-04-20 23:22:40', '2026-04-21 05:20:30'),
(7, 6, 2, 'completed', 100, '2026-04-20 23:22:43', '2026-04-21 05:20:43'),
(8, 6, 3, 'completed', 100, '2026-04-20 23:22:46', '2026-04-21 05:20:54'),
(9, 6, 4, 'completed', 100, '2026-04-20 23:22:48', '2026-04-21 05:22:48'),
(10, 6, 5, 'completed', 100, '2026-04-20 23:27:13', '2026-04-21 05:27:13'),
(11, 5, 1, 'completed', 100, '2026-04-21 06:12:19', '2026-04-21 06:12:19'),
(12, 5, 2, 'completed', 100, '2026-04-21 06:12:21', '2026-04-21 06:12:21'),
(13, 5, 3, 'completed', 100, '2026-04-21 06:12:24', '2026-04-21 06:12:24'),
(14, 5, 4, 'completed', 100, '2026-04-21 06:12:25', '2026-04-21 06:12:25'),
(15, 5, 5, 'completed', 100, '2026-04-21 06:12:28', '2026-04-21 06:12:28');

-- --------------------------------------------------------

--
-- Table structure for table `melcs`
--

CREATE TABLE `melcs` (
  `id` int UNSIGNED NOT NULL,
  `subject_id` int UNSIGNED NOT NULL,
  `competency_code` varchar(50) DEFAULT NULL,
  `description` text NOT NULL,
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
  `title` varchar(255) NOT NULL,
  `description` text,
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
(10, 11, NULL, NULL, 'Module 2', '', 2, 1, 17, '2026-04-22 10:35:09');

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `id` int UNSIGNED NOT NULL,
  `user_id` int UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `message` text,
  `type` varchar(50) DEFAULT 'info',
  `link` varchar(500) DEFAULT NULL,
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
  `relationship` enum('father','mother','guardian') DEFAULT 'guardian',
  `occupation` varchar(150) DEFAULT NULL,
  `contact_number` varchar(50) DEFAULT NULL
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
-- Table structure for table `programs`
--

CREATE TABLE `programs` (
  `id` int UNSIGNED NOT NULL,
  `school_id` int UNSIGNED NOT NULL DEFAULT '1',
  `name` varchar(255) NOT NULL,
  `code` varchar(20) NOT NULL,
  `description` text,
  `degree_type` enum('bachelor','master','doctorate','diploma','certificate') NOT NULL DEFAULT 'bachelor',
  `total_units` decimal(5,1) DEFAULT NULL,
  `years_to_complete` tinyint DEFAULT '4',
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `programs`
--

INSERT INTO `programs` (`id`, `school_id`, `name`, `code`, `description`, `degree_type`, `total_units`, `years_to_complete`, `status`, `created_at`) VALUES
(1, 1, 'Bachelor of Science in Information Technology', 'BSIT', NULL, 'bachelor', 150.0, 4, 1, '2026-04-20 20:36:52'),
(2, 1, 'Bachelor of Science in Education', 'BSED', NULL, 'bachelor', 150.0, 4, 1, '2026-04-20 20:36:52'),
(3, 1, 'Bachelor of Science in Business Administration', 'BSBA', NULL, 'bachelor', 150.0, 4, 1, '2026-04-20 20:36:52'),
(4, 1, 'Bachelor of Science in Nursing', 'BSN', NULL, 'bachelor', 180.0, 4, 1, '2026-04-20 20:36:52'),
(5, 4, 'Bachelor of Industrial Technology major in Computer and Information Technology', 'BInTech-CIT', '', 'bachelor', 0.0, 4, 1, '2026-04-22 08:31:05'),
(6, 4, 'Bachelor of Industrial Technology major in Culinary and Restaurant Technology', 'BInTech-CRT', '', 'bachelor', 0.0, 4, 1, '2026-04-22 08:31:37'),
(7, 4, 'Bachelor of Science in Office Administration (BSOA)', 'BSOA', '', 'bachelor', 0.0, 4, 1, '2026-04-22 08:32:00'),
(8, 4, 'Bachelor of Science and Tourism Management (BSTM)', 'BSTM', '', 'bachelor', 0.0, 4, 1, '2026-04-22 08:32:17'),
(9, 4, 'Senior High School', 'SHS', '', 'bachelor', NULL, 4, 1, '2026-04-22 08:51:46');

-- --------------------------------------------------------

--
-- Table structure for table `program_outcomes`
--

CREATE TABLE `program_outcomes` (
  `id` int UNSIGNED NOT NULL,
  `program_id` int UNSIGNED NOT NULL,
  `code` varchar(20) NOT NULL,
  `description` text NOT NULL,
  `order_num` tinyint DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `question_bank`
--

CREATE TABLE `question_bank` (
  `id` int UNSIGNED NOT NULL,
  `subject_id` int UNSIGNED NOT NULL,
  `question_type` enum('multiple_choice','identification','essay','true_false') NOT NULL,
  `question_text` text NOT NULL,
  `answer_key` text,
  `difficulty` enum('easy','medium','hard') DEFAULT 'medium',
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
  `title` varchar(255) NOT NULL,
  `description` text,
  `quiz_type` enum('quiz','exam','assignment','activity') NOT NULL DEFAULT 'quiz',
  `component_id` int UNSIGNED DEFAULT NULL,
  `total_points` decimal(6,2) DEFAULT NULL,
  `time_limit_minutes` int DEFAULT NULL,
  `max_attempts` tinyint DEFAULT '1',
  `shuffle_questions` tinyint(1) DEFAULT '0',
  `show_results` tinyint(1) DEFAULT '1',
  `available_from` datetime DEFAULT NULL,
  `available_until` datetime DEFAULT NULL,
  `is_published` tinyint(1) NOT NULL DEFAULT '0',
  `created_by` int UNSIGNED DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
  `status` enum('in_progress','submitted','graded') NOT NULL DEFAULT 'in_progress',
  `started_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `submitted_at` datetime DEFAULT NULL,
  `graded_at` datetime DEFAULT NULL,
  `graded_by` int UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `quiz_attempt_answers`
--

CREATE TABLE `quiz_attempt_answers` (
  `id` int UNSIGNED NOT NULL,
  `attempt_id` int UNSIGNED NOT NULL,
  `question_id` int UNSIGNED NOT NULL,
  `answer_text` text,
  `choice_id` int UNSIGNED DEFAULT NULL,
  `is_correct` tinyint(1) DEFAULT NULL,
  `score` decimal(5,2) DEFAULT NULL,
  `feedback` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `quiz_choices`
--

CREATE TABLE `quiz_choices` (
  `id` int UNSIGNED NOT NULL,
  `question_id` int UNSIGNED NOT NULL,
  `choice_text` text NOT NULL,
  `is_correct` tinyint(1) NOT NULL DEFAULT '0',
  `order_num` tinyint DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `quiz_questions`
--

CREATE TABLE `quiz_questions` (
  `id` int UNSIGNED NOT NULL,
  `quiz_id` int UNSIGNED NOT NULL,
  `question_type` enum('multiple_choice','identification','essay','true_false') NOT NULL,
  `question_text` text NOT NULL,
  `question_image` varchar(500) DEFAULT NULL,
  `points` decimal(5,2) NOT NULL DEFAULT '1.00',
  `order_num` int DEFAULT '1',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `id` int UNSIGNED NOT NULL,
  `name` varchar(50) NOT NULL,
  `slug` varchar(50) NOT NULL,
  `description` varchar(255) DEFAULT NULL
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
  `name` varchar(255) NOT NULL,
  `description` text,
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
  `criterion` varchar(255) NOT NULL,
  `excellent_desc` text,
  `proficient_desc` text,
  `developing_desc` text,
  `beginning_desc` text,
  `max_score` decimal(5,2) NOT NULL DEFAULT '25.00',
  `order_num` tinyint DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `schools`
--

CREATE TABLE `schools` (
  `id` int UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `school_id_number` varchar(50) DEFAULT NULL,
  `type` enum('deped','ched','both') NOT NULL DEFAULT 'deped',
  `address` text,
  `contact_number` varchar(50) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `logo` varchar(255) DEFAULT NULL,
  `division` varchar(255) DEFAULT NULL,
  `region` varchar(255) DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `schools`
--

INSERT INTO `schools` (`id`, `name`, `school_id_number`, `type`, `address`, `contact_number`, `email`, `logo`, `division`, `region`, `status`, `created_at`) VALUES
(1, 'Default School', NULL, 'both', NULL, NULL, NULL, NULL, NULL, NULL, 1, '2026-04-20 20:36:52'),
(3, 'Chiko Bolero College', '12345', 'both', 'Address', '09', 'joselito.edong@softtechservices.net', NULL, 'n/a', 'Region XI', 1, '2026-04-21 10:05:21'),
(4, 'DOIT COLLEGE', '20260001', 'both', 'Madang, City of Mati, Davao Oriental', '', 'joselito.edong@gmail.com', NULL, '', '', 1, '2026-04-22 08:07:14');

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
(1, 1, '2025', '2026', 1, '2026-04-20 20:36:52'),
(3, 3, '2026', '2027', 1, '2026-04-21 10:05:21'),
(4, 4, '2026', '2027', 1, '2026-04-22 08:07:14');

-- --------------------------------------------------------

--
-- Table structure for table `platform_settings`
--

CREATE TABLE `platform_settings` (
  `id` int UNSIGNED NOT NULL,
  `setting_key` varchar(100) NOT NULL,
  `setting_value` text DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sections`
--

CREATE TABLE `sections` (
  `id` int UNSIGNED NOT NULL,
  `school_year_id` int UNSIGNED DEFAULT NULL,
  `school_id` int UNSIGNED NOT NULL DEFAULT '1',
  `name` varchar(100) NOT NULL,
  `system_type` enum('deped','ched') NOT NULL DEFAULT 'deped',
  `grade_level_id` int UNSIGNED DEFAULT NULL,
  `strand_id` int UNSIGNED DEFAULT NULL,
  `program_id` int UNSIGNED DEFAULT NULL,
  `year_level` tinyint DEFAULT NULL,
  `adviser_id` int UNSIGNED DEFAULT NULL,
  `capacity` int DEFAULT '40',
  `status` tinyint(1) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sections`
--

INSERT INTO `sections` (`id`, `school_year_id`, `school_id`, `name`, `system_type`, `grade_level_id`, `strand_id`, `program_id`, `year_level`, `adviser_id`, `capacity`, `status`) VALUES
(1, 1, 1, 'CIT1A', 'ched', NULL, NULL, 1, 1, NULL, 40, 1),
(3, 3, 3, 'CIT1A', '', NULL, NULL, NULL, NULL, NULL, 40, 1),
(4, 3, 3, 'dfasdf', '', NULL, NULL, NULL, NULL, NULL, 40, 1),
(5, 3, 3, 'CIT1A', '', NULL, NULL, NULL, NULL, NULL, 40, 1),
(6, 4, 4, 'CIT1A-2026-2027', '', NULL, NULL, NULL, NULL, NULL, 40, 1);

-- --------------------------------------------------------

--
-- Table structure for table `section_enrollment_keys`
--

CREATE TABLE `section_enrollment_keys` (
  `id` int UNSIGNED NOT NULL,
  `course_id` int UNSIGNED NOT NULL,
  `section_id` int UNSIGNED NOT NULL,
  `enrollment_key` varchar(50) NOT NULL,
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
  `name` varchar(50) NOT NULL,
  `type` enum('quarter','semester') NOT NULL DEFAULT 'quarter',
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
  `name` varchar(100) NOT NULL,
  `code` varchar(20) NOT NULL,
  `description` text
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
  `name` varchar(100) NOT NULL,
  `code` varchar(20) NOT NULL,
  `description` text
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
-- Table structure for table `students`
--

CREATE TABLE `students` (
  `id` int UNSIGNED NOT NULL,
  `user_id` int UNSIGNED NOT NULL,
  `school_id` int UNSIGNED DEFAULT '1',
  `lrn` varchar(20) DEFAULT NULL,
  `student_id` varchar(30) DEFAULT NULL,
  `system_type` enum('deped','ched') NOT NULL DEFAULT 'deped',
  `gender` enum('Male','Female') DEFAULT NULL,
  `birthdate` date DEFAULT NULL,
  `birth_place` varchar(255) DEFAULT NULL,
  `address` text,
  `guardian_name` varchar(255) DEFAULT NULL,
  `guardian_contact` varchar(50) DEFAULT NULL,
  `guardian_user_id` int UNSIGNED DEFAULT NULL,
  `grade_level_id` int UNSIGNED DEFAULT NULL,
  `strand_id` int UNSIGNED DEFAULT NULL,
  `program_id` int UNSIGNED DEFAULT NULL,
  `year_level` tinyint DEFAULT NULL,
  `admission_date` date DEFAULT NULL,
  `status` enum('active','inactive','graduated','dropped','transferred') NOT NULL DEFAULT 'active',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `students`
--

INSERT INTO `students` (`id`, `user_id`, `school_id`, `lrn`, `student_id`, `system_type`, `gender`, `birthdate`, `birth_place`, `address`, `guardian_name`, `guardian_contact`, `guardian_user_id`, `grade_level_id`, `strand_id`, `program_id`, `year_level`, `admission_date`, `status`, `created_at`, `updated_at`) VALUES
(1, 2, 1, NULL, '2025-0001', 'ched', 'Male', '1982-10-10', NULL, '', '', '', NULL, NULL, NULL, 1, 1, '2026-04-20', 'active', '2026-04-20 20:42:47', '2026-04-20 20:42:47'),
(2, 16, NULL, '', '', 'deped', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'active', '2026-04-22 05:40:23', '2026-04-22 08:36:26'),
(4, 6, NULL, 'LRN-6-1776810616', 'STU-6-1776810616', 'deped', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'active', '2026-04-22 06:30:16', '2026-04-22 06:30:36'),
(5, 18, 4, 'LRN-18-1776818117', 'STU-18-1776818117', 'deped', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'active', '2026-04-22 08:35:17', '2026-04-22 10:25:23');

-- --------------------------------------------------------

--
-- Table structure for table `student_grades`
--

CREATE TABLE `student_grades` (
  `id` int UNSIGNED NOT NULL,
  `enrollment_id` int UNSIGNED NOT NULL,
  `class_program_id` int UNSIGNED NOT NULL,
  `semester_id` int UNSIGNED DEFAULT NULL,
  `system_type` enum('deped','ched') NOT NULL DEFAULT 'deped',
  `ww_score` decimal(6,2) DEFAULT NULL,
  `pt_score` decimal(6,2) DEFAULT NULL,
  `qa_score` decimal(6,2) DEFAULT NULL,
  `initial_grade` decimal(6,2) DEFAULT NULL,
  `transmuted_grade` int DEFAULT NULL,
  `ched_raw_grade` decimal(6,2) DEFAULT NULL,
  `ched_gpa` decimal(3,2) DEFAULT NULL,
  `final_grade` decimal(6,2) DEFAULT NULL,
  `remarks` enum('passed','failed','incomplete','dropped','in_progress') DEFAULT 'in_progress',
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
  `code` varchar(30) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text,
  `cover_photo` varchar(255) DEFAULT NULL,
  `system_type` enum('deped','ched') NOT NULL DEFAULT 'deped',
  `grade_level_id` int UNSIGNED DEFAULT NULL,
  `learning_area_id` int UNSIGNED DEFAULT NULL,
  `strand_id` int UNSIGNED DEFAULT NULL,
  `program_id` int UNSIGNED DEFAULT NULL,
  `year_level` tinyint DEFAULT NULL,
  `semester_type` enum('quarter','1st_sem','2nd_sem','summer') DEFAULT NULL,
  `units` decimal(3,1) DEFAULT NULL,
  `lec_hours` decimal(3,1) DEFAULT NULL,
  `lab_hours` decimal(3,1) DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `subjects`
--

INSERT INTO `subjects` (`id`, `school_id`, `code`, `name`, `description`, `cover_photo`, `system_type`, `grade_level_id`, `learning_area_id`, `strand_id`, `program_id`, `year_level`, `semester_type`, `units`, `lec_hours`, `lab_hours`, `status`, `created_at`) VALUES
(3, 1, 'CS 101', '', 'Intro to Information Technology', NULL, 'deped', NULL, NULL, NULL, NULL, 1, NULL, 3.0, 2.0, 1.0, 1, '2026-04-21 11:59:56'),
(4, 1, 'madf', '', 'dfasdf', NULL, 'deped', NULL, NULL, NULL, 1, 1, '1st_sem', 3.0, 2.0, 1.0, 1, '2026-04-21 15:40:28'),
(5, 1, 'Math 10', '', 'College Algebra and Trigonometry', NULL, 'deped', NULL, NULL, NULL, 1, 1, '2nd_sem', 3.0, 3.0, 0.0, 1, '2026-04-21 15:41:21'),
(6, 1, 'ABC1', '', 'ABC1', NULL, 'deped', NULL, NULL, NULL, 1, 2, '1st_sem', 3.0, 3.0, 0.0, 1, '2026-04-21 15:41:43'),
(10, 4, 'sdf', '', 'sdfasdfsdfds', '3645daca2cc513df00eca85259073f51.jpg', 'deped', 0, NULL, NULL, 5, NULL, '1st_sem', 3.0, 0.0, 0.0, 1, '2026-04-22 08:48:21'),
(11, 4, 'Tech 1', '', 'Into to Information Technology', NULL, 'deped', 0, NULL, NULL, 5, NULL, '1st_sem', NULL, NULL, NULL, 1, '2026-04-22 10:33:22');

-- --------------------------------------------------------

--
-- Table structure for table `teachers`
--

CREATE TABLE `teachers` (
  `id` int UNSIGNED NOT NULL,
  `user_id` int UNSIGNED NOT NULL,
  `school_id` int UNSIGNED NOT NULL DEFAULT '1',
  `employee_id` varchar(30) DEFAULT NULL,
  `department` varchar(150) DEFAULT NULL,
  `specialization` varchar(255) DEFAULT NULL,
  `position` varchar(100) DEFAULT NULL,
  `employment_type` enum('regular','part_time','contractual') DEFAULT 'regular',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
  `first_name` varchar(100) NOT NULL,
  `middle_name` varchar(100) DEFAULT NULL,
  `last_name` varchar(100) NOT NULL,
  `suffix` varchar(20) DEFAULT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `avatar` varchar(255) DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `last_login` datetime DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `role_id`, `school_id`, `first_name`, `middle_name`, `last_name`, `suffix`, `email`, `password`, `phone`, `avatar`, `status`, `last_login`, `created_at`, `updated_at`) VALUES
(1, 1, NULL, 'Super', NULL, 'Admin', NULL, 'admin@lms.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NULL, NULL, 1, '2026-04-22 07:56:24', '2026-04-20 20:36:52', '2026-04-22 07:56:24'),
(2, 5, NULL, 'Joselito', 'Quilat', 'Edong', '', 'joselito.edong@deped.gov.ph', '$2y$10$B437.b7g9cgdX3Xhu0RTaOw.cbFoz.qauEMUSPzlXFGtnRwdVTI9m', '', NULL, 1, NULL, '2026-04-20 20:42:47', '2026-04-20 20:42:47'),
(3, 2, 1, 'Maria', NULL, 'Santos', NULL, 'admin@school.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NULL, NULL, 1, '2026-04-21 09:55:07', '2026-04-20 20:59:20', '2026-04-21 09:55:07'),
(4, 3, 1, 'Juan', NULL, 'Dela Cruz', NULL, 'registrar@school.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NULL, NULL, 1, NULL, '2026-04-20 20:59:20', '2026-04-20 20:59:20'),
(5, 4, 1, 'Ana', NULL, 'Reyes', NULL, 'teacher@school.com', '$2y$10$qwygJDwzjulg2LQftNp/SeaQY4H9mLP98RVN6mj209QvhddpSOFOK', NULL, NULL, 1, '2026-04-20 23:58:15', '2026-04-20 20:59:20', '2026-04-21 05:58:15'),
(6, 5, 1, 'Carlos', NULL, 'Garcia', NULL, 'student@school.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NULL, NULL, 1, '2026-04-22 06:29:42', '2026-04-20 20:59:20', '2026-04-22 06:29:42'),
(7, 6, 1, 'Rosa', NULL, 'Mendoza', NULL, 'parent@school.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NULL, NULL, 1, '2026-04-20 15:19:18', '2026-04-20 20:59:20', '2026-04-20 21:19:18'),
(8, 4, 1, 'Joselito', '', 'Edong', '', 'a@a.com', '$2y$10$7Fxa8NiVfzWvjkUpR1z1W.0YWhEGgZe11bkeYgUy0gFPcw8DVhEKa', '', NULL, 1, '2026-04-20 15:15:47', '2026-04-20 21:15:34', '2026-04-20 21:15:47'),
(9, 5, 1, 'Juan', NULL, 'Dela Cruz', NULL, 'juan.delacruz@example.com', '$2y$10$KKsWCOY1tVI9NxXIyepTeO4xwodjHcnEBXwglKgr0.TrGT4HxiOk6', NULL, NULL, 1, NULL, '2026-04-21 00:06:10', '2026-04-21 06:06:10'),
(10, 5, 1, 'Maria', NULL, 'Santos', NULL, 'maria.santos@example.com', '$2y$10$ccTdUP2P1w/zX/RXXlVInOAHzryVCtYz6RN2/K2/wTj73yNpZnhl2', NULL, NULL, 1, NULL, '2026-04-21 00:06:10', '2026-04-21 06:06:10'),
(11, 5, 1, 'Luz', NULL, 'Paron', NULL, 'luz.paron@domain.com', '$2y$10$RB9G8YD.TwjnoJEEcmBMAOJ4G7ibFiBiuNDrErBgwIYkDDVTf1COq', NULL, NULL, 1, NULL, '2026-04-21 00:06:10', '2026-04-21 06:06:10'),
(12, 5, 1, 'Nanny', NULL, 'Wang', NULL, 'nanny.wang@gmail.com', '$2y$10$pE0yC8SjUVvVW5jzCbWX6e.NgpcsWfQz3DaGmL2Y6LUEN33oaQUyy', NULL, NULL, 1, NULL, '2026-04-21 00:06:10', '2026-04-21 06:06:10'),
(13, 2, 3, 'School', NULL, 'Admin', NULL, 'joselito.edong@softtechservices.net', '$2y$10$i5VtH7aah8aPH2Fs0yp1cOYxYKH9fu4ENAPHOoG.fcSxKs2FWYsFO', NULL, NULL, 1, '2026-04-22 07:54:31', '2026-04-21 10:05:21', '2026-04-22 07:54:31'),
(14, 4, 3, 'Liam Xander', '', 'Edong', '', 'liam@teacher.com', '$2y$10$0/.o/HFTQ/eVqL6yR4iLb.4JGMK0rnmXEXq7LOSpf1uOzqDMhTVLC', '', NULL, 1, '2026-04-21 15:46:27', '2026-04-21 15:46:17', '2026-04-21 15:46:27'),
(15, 7, 3, 'course', '', 'creator', '', 'course@lms.com', '$2y$10$NxZUNSMNercqnQQ01qCIXOC0R50.bfOgREj7rs5rhhpAHM51XTMs.', '', NULL, 1, '2026-04-22 06:24:49', '2026-04-21 16:29:53', '2026-04-22 06:24:49'),
(16, 5, 3, 'Luz', '', 'Paron', '', 'luz.paron@localhost.com', '$2y$10$AF8JDAQvamq16uJDC3xPwu4D9jJEFx6o4hneQ4NW8HciC9DohWGWq', '', NULL, 1, '2026-04-22 08:36:26', '2026-04-22 05:31:59', '2026-04-22 08:36:26'),
(17, 2, 4, 'School', NULL, 'Admin', NULL, 'joselito.edong@gmail.com', '$2y$10$2XaB2UjYAcJU9EpfKsKW0uDdPmIlfa1oEq9/f4QAU.njf2dUikpdW', NULL, NULL, 1, '2026-04-22 10:32:41', '2026-04-22 08:07:15', '2026-04-22 10:32:41'),
(18, 5, 4, 'Edgardo', '', 'Amigio', '', 'edgardo.amigo@lms.com', '$2y$10$FMh7JooryKEAqLIbbMK8Ce1QPYH.oAXoFLoUQfVFVjY4pfLaE1GPO', '', NULL, 1, '2026-04-22 12:24:03', '2026-04-22 08:35:13', '2026-04-22 12:24:03');

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
-- Indexes for table `gpa_records`
--
ALTER TABLE `gpa_records`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_gpa_student` (`student_id`);

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
-- Indexes for table `lessons`
--
ALTER TABLE `lessons`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_lesson_module` (`module_id`);

--
-- Indexes for table `lesson_completions`
--
ALTER TABLE `lesson_completions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_student_lesson` (`student_id`,`lesson_id`),
  ADD KEY `idx_student_id` (`student_id`),
  ADD KEY `idx_lesson_id` (`lesson_id`);

--
-- Indexes for table `lesson_progress`
--
ALTER TABLE `lesson_progress`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_lp_student` (`student_id`),
  ADD KEY `fk_lp_lesson` (`lesson_id`);

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
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `platform_settings`
--
ALTER TABLE `platform_settings`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `setting_key` (`setting_key`);

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
  ADD KEY `fk_subj_gl` (`grade_level_id`),
  ADD KEY `fk_subj_la` (`learning_area_id`),
  ADD KEY `fk_subj_strand` (`strand_id`),
  ADD KEY `fk_subj_program` (`program_id`),
  ADD KEY `fk_subjects_school` (`school_id`);

--
-- Indexes for table `teachers`
--
ALTER TABLE `teachers`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_teacher_user` (`user_id`),
  ADD KEY `fk_teachers_school` (`school_id`);

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
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `activity_logs`
--
ALTER TABLE `activity_logs`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `announcements`
--
ALTER TABLE `announcements`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `attendance`
--
ALTER TABLE `attendance`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `audit_logs`
--
ALTER TABLE `audit_logs`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `class_programs`
--
ALTER TABLE `class_programs`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

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
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

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
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `gpa_records`
--
ALTER TABLE `gpa_records`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT;

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
-- AUTO_INCREMENT for table `lessons`
--
ALTER TABLE `lessons`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `lesson_completions`
--
ALTER TABLE `lesson_completions`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `lesson_progress`
--
ALTER TABLE `lesson_progress`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `melcs`
--
ALTER TABLE `melcs`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `modules`
--
ALTER TABLE `modules`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

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
-- AUTO_INCREMENT for table `programs`
--
ALTER TABLE `programs`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

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
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `quiz_attempts`
--
ALTER TABLE `quiz_attempts`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `quiz_attempt_answers`
--
ALTER TABLE `quiz_attempt_answers`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `quiz_choices`
--
ALTER TABLE `quiz_choices`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `quiz_questions`
--
ALTER TABLE `quiz_questions`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

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
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `platform_settings`
--
ALTER TABLE `platform_settings`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `school_years`
--
ALTER TABLE `school_years`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `sections`
--
ALTER TABLE `sections`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

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
-- AUTO_INCREMENT for table `students`
--
ALTER TABLE `students`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `student_grades`
--
ALTER TABLE `student_grades`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `subjects`
--
ALTER TABLE `subjects`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `teachers`
--
ALTER TABLE `teachers`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `transmutation_table`
--
ALTER TABLE `transmutation_table`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=42;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `class_programs`
--
ALTER TABLE `class_programs`
  ADD CONSTRAINT `fk_cp_section` FOREIGN KEY (`section_id`) REFERENCES `sections` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_cp_subject` FOREIGN KEY (`subject_id`) REFERENCES `subjects` (`id`),
  ADD CONSTRAINT `fk_cp_teacher` FOREIGN KEY (`teacher_id`) REFERENCES `teachers` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `course_collaborators`
--
ALTER TABLE `course_collaborators`
  ADD CONSTRAINT `fk_collab_course` FOREIGN KEY (`course_id`) REFERENCES `courses` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_collab_section` FOREIGN KEY (`section_id`) REFERENCES `sections` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_collab_teacher` FOREIGN KEY (`teacher_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `course_outcomes`
--
ALTER TABLE `course_outcomes`
  ADD CONSTRAINT `fk_co_subject` FOREIGN KEY (`subject_id`) REFERENCES `subjects` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `co_po_mapping`
--
ALTER TABLE `co_po_mapping`
  ADD CONSTRAINT `fk_map_co` FOREIGN KEY (`course_outcome_id`) REFERENCES `course_outcomes` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_map_po` FOREIGN KEY (`program_outcome_id`) REFERENCES `program_outcomes` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `enrollments`
--
ALTER TABLE `enrollments`
  ADD CONSTRAINT `fk_enr_section` FOREIGN KEY (`section_id`) REFERENCES `sections` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_enr_student` FOREIGN KEY (`student_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_enr_sy` FOREIGN KEY (`school_year_id`) REFERENCES `school_years` (`id`);

--
-- Constraints for table `gpa_records`
--
ALTER TABLE `gpa_records`
  ADD CONSTRAINT `fk_gpa_student` FOREIGN KEY (`student_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `grade_entries`
--
ALTER TABLE `grade_entries`
  ADD CONSTRAINT `fk_ge_component` FOREIGN KEY (`component_id`) REFERENCES `grade_components` (`id`),
  ADD CONSTRAINT `fk_ge_cp` FOREIGN KEY (`class_program_id`) REFERENCES `class_programs` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_ge_enrollment` FOREIGN KEY (`enrollment_id`) REFERENCES `enrollments` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `lessons`
--
ALTER TABLE `lessons`
  ADD CONSTRAINT `fk_lesson_module` FOREIGN KEY (`module_id`) REFERENCES `modules` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `lesson_completions`
--
ALTER TABLE `lesson_completions`
  ADD CONSTRAINT `fk_lc_lesson` FOREIGN KEY (`lesson_id`) REFERENCES `lessons` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_lc_student` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `lesson_progress`
--
ALTER TABLE `lesson_progress`
  ADD CONSTRAINT `fk_lp_lesson` FOREIGN KEY (`lesson_id`) REFERENCES `lessons` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `melcs`
--
ALTER TABLE `melcs`
  ADD CONSTRAINT `fk_melc_subject` FOREIGN KEY (`subject_id`) REFERENCES `subjects` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `modules`
--
ALTER TABLE `modules`
  ADD CONSTRAINT `fk_mod_cp` FOREIGN KEY (`class_program_id`) REFERENCES `class_programs` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `notifications`
--
ALTER TABLE `notifications`
  ADD CONSTRAINT `fk_notif_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `parents`
--
ALTER TABLE `parents`
  ADD CONSTRAINT `fk_parent_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `parent_student`
--
ALTER TABLE `parent_student`
  ADD CONSTRAINT `fk_ps_parent` FOREIGN KEY (`parent_id`) REFERENCES `parents` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_ps_student` FOREIGN KEY (`student_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `program_outcomes`
--
ALTER TABLE `program_outcomes`
  ADD CONSTRAINT `fk_po_program` FOREIGN KEY (`program_id`) REFERENCES `programs` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `question_bank`
--
ALTER TABLE `question_bank`
  ADD CONSTRAINT `fk_qb_subject` FOREIGN KEY (`subject_id`) REFERENCES `subjects` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `quizzes`
--
ALTER TABLE `quizzes`
  ADD CONSTRAINT `fk_quiz_cp` FOREIGN KEY (`class_program_id`) REFERENCES `class_programs` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `quiz_attempts`
--
ALTER TABLE `quiz_attempts`
  ADD CONSTRAINT `fk_qa_quiz` FOREIGN KEY (`quiz_id`) REFERENCES `quizzes` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_qa_student` FOREIGN KEY (`student_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `quiz_attempt_answers`
--
ALTER TABLE `quiz_attempt_answers`
  ADD CONSTRAINT `fk_qaa_attempt` FOREIGN KEY (`attempt_id`) REFERENCES `quiz_attempts` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_qaa_question` FOREIGN KEY (`question_id`) REFERENCES `quiz_questions` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `quiz_choices`
--
ALTER TABLE `quiz_choices`
  ADD CONSTRAINT `fk_qc_question` FOREIGN KEY (`question_id`) REFERENCES `quiz_questions` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `quiz_questions`
--
ALTER TABLE `quiz_questions`
  ADD CONSTRAINT `fk_qq_quiz` FOREIGN KEY (`quiz_id`) REFERENCES `quizzes` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `rubrics`
--
ALTER TABLE `rubrics`
  ADD CONSTRAINT `fk_rub_subject` FOREIGN KEY (`subject_id`) REFERENCES `subjects` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `rubric_criteria`
--
ALTER TABLE `rubric_criteria`
  ADD CONSTRAINT `fk_rc_rubric` FOREIGN KEY (`rubric_id`) REFERENCES `rubrics` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `school_years`
--
ALTER TABLE `school_years`
  ADD CONSTRAINT `fk_sy_school` FOREIGN KEY (`school_id`) REFERENCES `schools` (`id`);

--
-- Constraints for table `sections`
--
ALTER TABLE `sections`
  ADD CONSTRAINT `fk_sec_adviser` FOREIGN KEY (`adviser_id`) REFERENCES `teachers` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_sec_sy` FOREIGN KEY (`school_year_id`) REFERENCES `school_years` (`id`);

--
-- Constraints for table `section_enrollment_keys`
--
ALTER TABLE `section_enrollment_keys`
  ADD CONSTRAINT `fk_section_key_course` FOREIGN KEY (`course_id`) REFERENCES `courses` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_section_key_section` FOREIGN KEY (`section_id`) REFERENCES `sections` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `semesters`
--
ALTER TABLE `semesters`
  ADD CONSTRAINT `fk_sem_sy` FOREIGN KEY (`school_year_id`) REFERENCES `school_years` (`id`);

--
-- Constraints for table `shs_strands`
--
ALTER TABLE `shs_strands`
  ADD CONSTRAINT `fk_strand_track` FOREIGN KEY (`track_id`) REFERENCES `shs_tracks` (`id`);

--
-- Constraints for table `students`
--
ALTER TABLE `students`
  ADD CONSTRAINT `fk_student_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `student_grades`
--
ALTER TABLE `student_grades`
  ADD CONSTRAINT `fk_sg_cp` FOREIGN KEY (`class_program_id`) REFERENCES `class_programs` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_sg_enrollment` FOREIGN KEY (`enrollment_id`) REFERENCES `enrollments` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `teachers`
--
ALTER TABLE `teachers`
  ADD CONSTRAINT `fk_teacher_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `fk_users_role` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
