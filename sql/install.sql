-- =====================================================
-- LMS DATABASE SCHEMA
-- DepEd (K-12) + CHED (Higher Education) Compliant
-- =====================================================
CREATE DATABASE IF NOT EXISTS `lms_db` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `lms_db`;

-- =====================================================
-- SECTION 1: CORE TABLES (Users, Roles, School Config)
-- =====================================================

CREATE TABLE IF NOT EXISTS `roles` (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `slug` varchar(50) NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `slug` (`slug`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `roles` (`name`, `slug`, `description`) VALUES
('Super Admin', 'super_admin', 'Division / University level administrator'),
('School Admin', 'school_admin', 'School-level administrator'),
('Registrar', 'registrar', 'Manages enrollment and records'),
('Teacher', 'teacher', 'Instructor / Faculty'),
('Student', 'student', 'Learner'),
('Parent', 'parent', 'Parent/Guardian (DepEd K-12)');

CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `role_id` int(11) UNSIGNED NOT NULL DEFAULT 4,
  `school_id` int(11) UNSIGNED DEFAULT NULL,
  `first_name` varchar(100) NOT NULL,
  `middle_name` varchar(100) DEFAULT NULL,
  `last_name` varchar(100) NOT NULL,
  `suffix` varchar(20) DEFAULT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `avatar` varchar(255) DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `last_login` datetime DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`),
  KEY `fk_users_role` (`role_id`),
  CONSTRAINT `fk_users_role` FOREIGN KEY (`role_id`) REFERENCES `roles`(`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Default users (password for all: password)
INSERT INTO `users` (`role_id`, `school_id`, `first_name`, `last_name`, `email`, `password`, `status`) VALUES
(1, NULL,  'Super',  'Admin',     'admin@lms.com',        '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 1),
(2, 1,     'Maria',  'Santos',    'admin@school.com',     '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 1),
(3, 1,     'Juan',   'Dela Cruz', 'registrar@school.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 1),
(4, 1,     'Ana',    'Reyes',     'teacher@school.com',   '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 1),
(5, 1,     'Carlos', 'Garcia',    'student@school.com',   '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 1),
(6, 1,     'Rosa',   'Mendoza',   'parent@school.com',    '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 1);

CREATE TABLE IF NOT EXISTS `schools` (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `school_id_number` varchar(50) DEFAULT NULL,
  `type` enum('deped','ched','both') NOT NULL DEFAULT 'deped',
  `address` text DEFAULT NULL,
  `contact_number` varchar(50) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `logo` varchar(255) DEFAULT NULL,
  `division` varchar(255) DEFAULT NULL,
  `region` varchar(255) DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `schools` (`name`, `type`) VALUES ('Default School', 'both');

CREATE TABLE IF NOT EXISTS `school_years` (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `school_id` int(11) UNSIGNED NOT NULL DEFAULT 1,
  `year_start` year NOT NULL,
  `year_end` year NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `fk_sy_school` (`school_id`),
  CONSTRAINT `fk_sy_school` FOREIGN KEY (`school_id`) REFERENCES `schools`(`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `school_years` (`school_id`, `year_start`, `year_end`, `is_active`) VALUES (1, 2025, 2026, 1);

CREATE TABLE IF NOT EXISTS `semesters` (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `school_year_id` int(11) UNSIGNED NOT NULL,
  `name` varchar(50) NOT NULL,
  `type` enum('quarter','semester') NOT NULL DEFAULT 'quarter',
  `term_number` tinyint(2) NOT NULL,
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  KEY `fk_sem_sy` (`school_year_id`),
  CONSTRAINT `fk_sem_sy` FOREIGN KEY (`school_year_id`) REFERENCES `school_years`(`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Default quarters for DepEd
INSERT INTO `semesters` (`school_year_id`, `name`, `type`, `term_number`, `is_active`) VALUES
(1, '1st Quarter', 'quarter', 1, 1),
(1, '2nd Quarter', 'quarter', 2, 0),
(1, '3rd Quarter', 'quarter', 3, 0),
(1, '4th Quarter', 'quarter', 4, 0),
(1, '1st Semester', 'semester', 1, 1),
(1, '2nd Semester', 'semester', 2, 0),
(1, 'Summer', 'semester', 3, 0);

-- =====================================================
-- SECTION 2: DEPED ACADEMIC STRUCTURE (K-12)
-- =====================================================

CREATE TABLE IF NOT EXISTS `grade_levels` (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `code` varchar(10) NOT NULL,
  `level_order` tinyint(3) NOT NULL,
  `category` enum('elementary','junior_high','senior_high') NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  PRIMARY KEY (`id`),
  UNIQUE KEY `code` (`code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `grade_levels` (`name`, `code`, `level_order`, `category`) VALUES
('Kindergarten', 'K', 0, 'elementary'),
('Grade 1', 'G1', 1, 'elementary'),
('Grade 2', 'G2', 2, 'elementary'),
('Grade 3', 'G3', 3, 'elementary'),
('Grade 4', 'G4', 4, 'elementary'),
('Grade 5', 'G5', 5, 'elementary'),
('Grade 6', 'G6', 6, 'elementary'),
('Grade 7', 'G7', 7, 'junior_high'),
('Grade 8', 'G8', 8, 'junior_high'),
('Grade 9', 'G9', 9, 'junior_high'),
('Grade 10', 'G10', 10, 'junior_high'),
('Grade 11', 'G11', 11, 'senior_high'),
('Grade 12', 'G12', 12, 'senior_high');

CREATE TABLE IF NOT EXISTS `shs_tracks` (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `code` varchar(20) NOT NULL,
  `description` text DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `shs_tracks` (`name`, `code`) VALUES
('Academic', 'ACAD'),
('Technical-Vocational-Livelihood', 'TVL'),
('Sports', 'SPORTS'),
('Arts and Design', 'ARTS');

CREATE TABLE IF NOT EXISTS `shs_strands` (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `track_id` int(11) UNSIGNED NOT NULL,
  `name` varchar(100) NOT NULL,
  `code` varchar(20) NOT NULL,
  `description` text DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_strand_track` (`track_id`),
  CONSTRAINT `fk_strand_track` FOREIGN KEY (`track_id`) REFERENCES `shs_tracks`(`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `shs_strands` (`track_id`, `name`, `code`) VALUES
(1, 'Science, Technology, Engineering and Mathematics', 'STEM'),
(1, 'Accountancy, Business and Management', 'ABM'),
(1, 'Humanities and Social Sciences', 'HUMSS'),
(1, 'General Academic Strand', 'GAS'),
(2, 'Home Economics', 'HE'),
(2, 'Information and Communications Technology', 'ICT'),
(2, 'Agri-Fishery Arts', 'AFA'),
(2, 'Industrial Arts', 'IA');

CREATE TABLE IF NOT EXISTS `learning_areas` (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(150) NOT NULL,
  `code` varchar(20) NOT NULL,
  `category` enum('core','applied','specialized','elective') NOT NULL DEFAULT 'core',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `learning_areas` (`name`, `code`, `category`) VALUES
('Filipino', 'FIL', 'core'),
('English', 'ENG', 'core'),
('Mathematics', 'MATH', 'core'),
('Science', 'SCI', 'core'),
('Araling Panlipunan', 'AP', 'core'),
('Edukasyon sa Pagpapakatao', 'ESP', 'core'),
('Technology and Livelihood Education', 'TLE', 'core'),
('MAPEH', 'MAPEH', 'core'),
('Mother Tongue', 'MTB', 'core'),
('Oral Communication', 'ORALCOMM', 'applied'),
('Reading and Writing', 'RW', 'applied'),
('Komunikasyon at Pananaliksik', 'KOMSA', 'applied'),
('General Mathematics', 'GENMATH', 'applied'),
('Statistics and Probability', 'STAT', 'applied'),
('Earth and Life Science', 'ELS', 'specialized'),
('Physical Science', 'PHYSCI', 'specialized');

-- =====================================================
-- SECTION 3: CHED ACADEMIC STRUCTURE (Higher Education)
-- =====================================================

CREATE TABLE IF NOT EXISTS `programs` (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `school_id` int(11) UNSIGNED NOT NULL DEFAULT 1,
  `name` varchar(255) NOT NULL,
  `code` varchar(20) NOT NULL,
  `description` text DEFAULT NULL,
  `degree_type` enum('bachelor','master','doctorate','diploma','certificate') NOT NULL DEFAULT 'bachelor',
  `total_units` decimal(5,1) DEFAULT NULL,
  `years_to_complete` tinyint(2) DEFAULT 4,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `programs` (`name`, `code`, `degree_type`, `total_units`) VALUES
('Bachelor of Science in Information Technology', 'BSIT', 'bachelor', 150),
('Bachelor of Science in Education', 'BSED', 'bachelor', 150),
('Bachelor of Science in Business Administration', 'BSBA', 'bachelor', 150),
('Bachelor of Science in Nursing', 'BSN', 'bachelor', 180);

CREATE TABLE IF NOT EXISTS `program_outcomes` (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `program_id` int(11) UNSIGNED NOT NULL,
  `code` varchar(20) NOT NULL,
  `description` text NOT NULL,
  `order_num` tinyint(3) DEFAULT 1,
  PRIMARY KEY (`id`),
  KEY `fk_po_program` (`program_id`),
  CONSTRAINT `fk_po_program` FOREIGN KEY (`program_id`) REFERENCES `programs`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- =====================================================
-- SECTION 4: SUBJECTS (Unified – DepEd & CHED)
-- =====================================================

CREATE TABLE IF NOT EXISTS `subjects` (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `school_id` int(11) UNSIGNED DEFAULT NULL,
  `code` varchar(30) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `system_type` enum('deped','ched') NOT NULL DEFAULT 'deped',
  `grade_level_id` int(11) UNSIGNED DEFAULT NULL,
  `learning_area_id` int(11) UNSIGNED DEFAULT NULL,
  `strand_id` int(11) UNSIGNED DEFAULT NULL,
  `program_id` int(11) UNSIGNED DEFAULT NULL,
  `year_level` tinyint(2) DEFAULT NULL,
  `semester_type` enum('quarter','1st_sem','2nd_sem','summer') DEFAULT NULL,
  `units` decimal(3,1) DEFAULT NULL,
  `lec_hours` decimal(3,1) DEFAULT NULL,
  `lab_hours` decimal(3,1) DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `fk_subj_gl` (`grade_level_id`),
  KEY `fk_subj_la` (`learning_area_id`),
  KEY `fk_subj_strand` (`strand_id`),
  KEY `fk_subj_program` (`program_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `course_outcomes` (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `subject_id` int(11) UNSIGNED NOT NULL,
  `code` varchar(20) NOT NULL,
  `description` text NOT NULL,
  `order_num` tinyint(3) DEFAULT 1,
  PRIMARY KEY (`id`),
  KEY `fk_co_subject` (`subject_id`),
  CONSTRAINT `fk_co_subject` FOREIGN KEY (`subject_id`) REFERENCES `subjects`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `co_po_mapping` (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `course_outcome_id` int(11) UNSIGNED NOT NULL,
  `program_outcome_id` int(11) UNSIGNED NOT NULL,
  `level` enum('I','D','A') NOT NULL DEFAULT 'I',
  PRIMARY KEY (`id`),
  KEY `fk_map_co` (`course_outcome_id`),
  KEY `fk_map_po` (`program_outcome_id`),
  CONSTRAINT `fk_map_co` FOREIGN KEY (`course_outcome_id`) REFERENCES `course_outcomes`(`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_map_po` FOREIGN KEY (`program_outcome_id`) REFERENCES `program_outcomes`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `melcs` (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `subject_id` int(11) UNSIGNED NOT NULL,
  `competency_code` varchar(50) DEFAULT NULL,
  `description` text NOT NULL,
  `quarter` tinyint(1) DEFAULT NULL,
  `order_num` int(11) DEFAULT 1,
  PRIMARY KEY (`id`),
  KEY `fk_melc_subject` (`subject_id`),
  CONSTRAINT `fk_melc_subject` FOREIGN KEY (`subject_id`) REFERENCES `subjects`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- =====================================================
-- SECTION 5: STUDENTS & TEACHERS PROFILES
-- =====================================================

CREATE TABLE IF NOT EXISTS `students` (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` int(11) UNSIGNED NOT NULL,
  `school_id` int(11) UNSIGNED NOT NULL DEFAULT 1,
  `lrn` varchar(20) DEFAULT NULL,
  `student_id` varchar(30) DEFAULT NULL,
  `system_type` enum('deped','ched') NOT NULL DEFAULT 'deped',
  `gender` enum('Male','Female') DEFAULT NULL,
  `birthdate` date DEFAULT NULL,
  `birth_place` varchar(255) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `guardian_name` varchar(255) DEFAULT NULL,
  `guardian_contact` varchar(50) DEFAULT NULL,
  `guardian_user_id` int(11) UNSIGNED DEFAULT NULL,
  `grade_level_id` int(11) UNSIGNED DEFAULT NULL,
  `strand_id` int(11) UNSIGNED DEFAULT NULL,
  `program_id` int(11) UNSIGNED DEFAULT NULL,
  `year_level` tinyint(2) DEFAULT NULL,
  `admission_date` date DEFAULT NULL,
  `status` enum('active','inactive','graduated','dropped','transferred') NOT NULL DEFAULT 'active',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `lrn` (`lrn`),
  UNIQUE KEY `student_id` (`student_id`),
  KEY `fk_student_user` (`user_id`),
  CONSTRAINT `fk_student_user` FOREIGN KEY (`user_id`) REFERENCES `users`(`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `teachers` (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` int(11) UNSIGNED NOT NULL,
  `school_id` int(11) UNSIGNED NOT NULL DEFAULT 1,
  `employee_id` varchar(30) DEFAULT NULL,
  `department` varchar(150) DEFAULT NULL,
  `specialization` varchar(255) DEFAULT NULL,
  `position` varchar(100) DEFAULT NULL,
  `employment_type` enum('regular','part_time','contractual') DEFAULT 'regular',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `fk_teacher_user` (`user_id`),
  CONSTRAINT `fk_teacher_user` FOREIGN KEY (`user_id`) REFERENCES `users`(`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `parents` (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` int(11) UNSIGNED NOT NULL,
  `relationship` enum('father','mother','guardian') DEFAULT 'guardian',
  `occupation` varchar(150) DEFAULT NULL,
  `contact_number` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_parent_user` (`user_id`),
  CONSTRAINT `fk_parent_user` FOREIGN KEY (`user_id`) REFERENCES `users`(`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `parent_student` (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `parent_id` int(11) UNSIGNED NOT NULL,
  `student_id` int(11) UNSIGNED NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_ps_parent` (`parent_id`),
  KEY `fk_ps_student` (`student_id`),
  CONSTRAINT `fk_ps_parent` FOREIGN KEY (`parent_id`) REFERENCES `parents`(`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_ps_student` FOREIGN KEY (`student_id`) REFERENCES `users`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- =====================================================
-- SECTION 6: SECTIONS, ENROLLMENT, SCHEDULING
-- =====================================================

CREATE TABLE IF NOT EXISTS `sections` (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `school_year_id` int(11) UNSIGNED NOT NULL,
  `school_id` int(11) UNSIGNED NOT NULL DEFAULT 1,
  `name` varchar(100) NOT NULL,
  `system_type` enum('deped','ched') NOT NULL DEFAULT 'deped',
  `grade_level_id` int(11) UNSIGNED DEFAULT NULL,
  `strand_id` int(11) UNSIGNED DEFAULT NULL,
  `program_id` int(11) UNSIGNED DEFAULT NULL,
  `year_level` tinyint(2) DEFAULT NULL,
  `adviser_id` int(11) UNSIGNED DEFAULT NULL,
  `capacity` int(11) DEFAULT 40,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  PRIMARY KEY (`id`),
  KEY `fk_sec_sy` (`school_year_id`),
  KEY `fk_sec_adviser` (`adviser_id`),
  CONSTRAINT `fk_sec_sy` FOREIGN KEY (`school_year_id`) REFERENCES `school_years`(`id`),
  CONSTRAINT `fk_sec_adviser` FOREIGN KEY (`adviser_id`) REFERENCES `teachers`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `enrollments` (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `student_id` int(11) UNSIGNED NOT NULL,
  `school_id` int(11) UNSIGNED NOT NULL DEFAULT 1,
  `school_year_id` int(11) UNSIGNED NOT NULL,
  `section_id` int(11) UNSIGNED DEFAULT NULL,
  `system_type` enum('deped','ched') NOT NULL DEFAULT 'deped',
  `grade_level_id` int(11) UNSIGNED DEFAULT NULL,
  `strand_id` int(11) UNSIGNED DEFAULT NULL,
  `program_id` int(11) UNSIGNED DEFAULT NULL,
  `year_level` tinyint(2) DEFAULT NULL,
  `semester_id` int(11) UNSIGNED DEFAULT NULL,
  `enrollment_date` date DEFAULT NULL,
  `status` enum('pending','enrolled','dropped','completed') NOT NULL DEFAULT 'pending',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `fk_enr_student` (`student_id`),
  KEY `fk_enr_sy` (`school_year_id`),
  KEY `fk_enr_section` (`section_id`),
  CONSTRAINT `fk_enr_student` FOREIGN KEY (`student_id`) REFERENCES `users`(`id`),
  CONSTRAINT `fk_enr_sy` FOREIGN KEY (`school_year_id`) REFERENCES `school_years`(`id`),
  CONSTRAINT `fk_enr_section` FOREIGN KEY (`section_id`) REFERENCES `sections`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `class_programs` (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `section_id` int(11) UNSIGNED NOT NULL,
  `subject_id` int(11) UNSIGNED NOT NULL,
  `teacher_id` int(11) UNSIGNED DEFAULT NULL,
  `semester_id` int(11) UNSIGNED DEFAULT NULL,
  `schedule_day` varchar(20) DEFAULT NULL,
  `schedule_time_start` time DEFAULT NULL,
  `schedule_time_end` time DEFAULT NULL,
  `room` varchar(50) DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  PRIMARY KEY (`id`),
  KEY `fk_cp_section` (`section_id`),
  KEY `fk_cp_subject` (`subject_id`),
  KEY `fk_cp_teacher` (`teacher_id`),
  CONSTRAINT `fk_cp_section` FOREIGN KEY (`section_id`) REFERENCES `sections`(`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_cp_subject` FOREIGN KEY (`subject_id`) REFERENCES `subjects`(`id`),
  CONSTRAINT `fk_cp_teacher` FOREIGN KEY (`teacher_id`) REFERENCES `teachers`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- =====================================================
-- SECTION 7: GRADING SYSTEM (DepEd + CHED Dual Mode)
-- =====================================================

CREATE TABLE IF NOT EXISTS `grade_components` (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `school_id` int(11) UNSIGNED DEFAULT NULL,
  `system_type` enum('deped','ched') NOT NULL DEFAULT 'deped',
  `name` varchar(100) NOT NULL,
  `code` varchar(20) NOT NULL,
  `weight_percentage` decimal(5,2) NOT NULL,
  `subject_category` varchar(50) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- DepEd default grade components
INSERT INTO `grade_components` (`system_type`, `name`, `code`, `weight_percentage`, `subject_category`) VALUES
('deped', 'Written Works', 'WW', 30.00, 'core'),
('deped', 'Performance Tasks', 'PT', 50.00, 'core'),
('deped', 'Quarterly Assessment', 'QA', 20.00, 'core'),
('deped', 'Written Works', 'WW', 25.00, 'mapeh_tle'),
('deped', 'Performance Tasks', 'PT', 55.00, 'mapeh_tle'),
('deped', 'Quarterly Assessment', 'QA', 20.00, 'mapeh_tle'),
('ched', 'Prelim Exam', 'PRELIM', 20.00, NULL),
('ched', 'Midterm Exam', 'MIDTERM', 20.00, NULL),
('ched', 'Final Exam', 'FINAL', 20.00, NULL),
('ched', 'Quizzes', 'QUIZ', 15.00, NULL),
('ched', 'Activities/Projects', 'ACT', 15.00, NULL),
('ched', 'Attendance/Participation', 'ATT', 10.00, NULL);

CREATE TABLE IF NOT EXISTS `transmutation_table` (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `initial_grade_min` decimal(5,2) NOT NULL,
  `initial_grade_max` decimal(5,2) NOT NULL,
  `transmuted_grade` int(3) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `transmutation_table` (`initial_grade_min`, `initial_grade_max`, `transmuted_grade`) VALUES
(100.00, 100.00, 100), (98.40, 99.99, 99), (96.80, 98.39, 98),
(95.20, 96.79, 97), (93.60, 95.19, 96), (92.00, 93.59, 95),
(90.40, 91.99, 94), (88.80, 90.39, 93), (87.20, 88.79, 92),
(85.60, 87.19, 91), (84.00, 85.59, 90), (82.40, 83.99, 89),
(80.80, 82.39, 88), (79.20, 80.79, 87), (77.60, 79.19, 86),
(76.00, 77.59, 85), (74.40, 75.99, 84), (72.80, 74.39, 83),
(71.20, 72.79, 82), (69.60, 71.19, 81), (68.00, 69.59, 80),
(66.40, 67.99, 79), (64.80, 66.39, 78), (63.20, 64.79, 77),
(61.60, 63.19, 76), (60.00, 61.59, 75), (56.00, 59.99, 74),
(52.00, 55.99, 73), (48.00, 51.99, 72), (44.00, 47.99, 71),
(40.00, 43.99, 70), (36.00, 39.99, 69), (32.00, 35.99, 68),
(28.00, 31.99, 67), (24.00, 27.99, 66), (20.00, 23.99, 65),
(16.00, 19.99, 64), (12.00, 15.99, 63), (8.00, 11.99, 62),
(4.00, 7.99, 61), (0.00, 3.99, 60);

CREATE TABLE IF NOT EXISTS `grade_entries` (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `enrollment_id` int(11) UNSIGNED NOT NULL,
  `class_program_id` int(11) UNSIGNED NOT NULL,
  `component_id` int(11) UNSIGNED NOT NULL,
  `semester_id` int(11) UNSIGNED DEFAULT NULL,
  `activity_name` varchar(255) DEFAULT NULL,
  `score` decimal(6,2) DEFAULT NULL,
  `total_score` decimal(6,2) DEFAULT NULL,
  `remarks` varchar(255) DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `fk_ge_enrollment` (`enrollment_id`),
  KEY `fk_ge_cp` (`class_program_id`),
  KEY `fk_ge_component` (`component_id`),
  CONSTRAINT `fk_ge_enrollment` FOREIGN KEY (`enrollment_id`) REFERENCES `enrollments`(`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_ge_cp` FOREIGN KEY (`class_program_id`) REFERENCES `class_programs`(`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_ge_component` FOREIGN KEY (`component_id`) REFERENCES `grade_components`(`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `student_grades` (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `enrollment_id` int(11) UNSIGNED NOT NULL,
  `class_program_id` int(11) UNSIGNED NOT NULL,
  `semester_id` int(11) UNSIGNED DEFAULT NULL,
  `system_type` enum('deped','ched') NOT NULL DEFAULT 'deped',
  `ww_score` decimal(6,2) DEFAULT NULL,
  `pt_score` decimal(6,2) DEFAULT NULL,
  `qa_score` decimal(6,2) DEFAULT NULL,
  `initial_grade` decimal(6,2) DEFAULT NULL,
  `transmuted_grade` int(3) DEFAULT NULL,
  `ched_raw_grade` decimal(6,2) DEFAULT NULL,
  `ched_gpa` decimal(3,2) DEFAULT NULL,
  `final_grade` decimal(6,2) DEFAULT NULL,
  `remarks` enum('passed','failed','incomplete','dropped','in_progress') DEFAULT 'in_progress',
  `is_locked` tinyint(1) NOT NULL DEFAULT 0,
  `approved_by` int(11) UNSIGNED DEFAULT NULL,
  `approved_at` datetime DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_grade` (`enrollment_id`, `class_program_id`, `semester_id`),
  KEY `fk_sg_enrollment` (`enrollment_id`),
  KEY `fk_sg_cp` (`class_program_id`),
  CONSTRAINT `fk_sg_enrollment` FOREIGN KEY (`enrollment_id`) REFERENCES `enrollments`(`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_sg_cp` FOREIGN KEY (`class_program_id`) REFERENCES `class_programs`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `gpa_records` (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `student_id` int(11) UNSIGNED NOT NULL,
  `school_year_id` int(11) UNSIGNED NOT NULL,
  `semester_id` int(11) UNSIGNED DEFAULT NULL,
  `total_units` decimal(5,1) DEFAULT NULL,
  `total_grade_points` decimal(8,2) DEFAULT NULL,
  `gpa` decimal(4,3) DEFAULT NULL,
  `status` enum('regular','dean_list','probation','dismissed') DEFAULT 'regular',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `fk_gpa_student` (`student_id`),
  CONSTRAINT `fk_gpa_student` FOREIGN KEY (`student_id`) REFERENCES `users`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- CHED Rubrics
CREATE TABLE IF NOT EXISTS `rubrics` (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `subject_id` int(11) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `total_points` decimal(6,2) DEFAULT 100.00,
  `created_by` int(11) UNSIGNED DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `fk_rub_subject` (`subject_id`),
  CONSTRAINT `fk_rub_subject` FOREIGN KEY (`subject_id`) REFERENCES `subjects`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `rubric_criteria` (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `rubric_id` int(11) UNSIGNED NOT NULL,
  `criterion` varchar(255) NOT NULL,
  `excellent_desc` text DEFAULT NULL,
  `proficient_desc` text DEFAULT NULL,
  `developing_desc` text DEFAULT NULL,
  `beginning_desc` text DEFAULT NULL,
  `max_score` decimal(5,2) NOT NULL DEFAULT 25.00,
  `order_num` tinyint(3) DEFAULT 1,
  PRIMARY KEY (`id`),
  KEY `fk_rc_rubric` (`rubric_id`),
  CONSTRAINT `fk_rc_rubric` FOREIGN KEY (`rubric_id`) REFERENCES `rubrics`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- =====================================================
-- SECTION 8: COURSES & LEARNING CONTENT MANAGEMENT
-- =====================================================

CREATE TABLE IF NOT EXISTS `courses` (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `school_id` int(11) UNSIGNED NOT NULL DEFAULT 1,
  `code` varchar(30) DEFAULT NULL,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `category` varchar(100) DEFAULT NULL,
  `enrollment_key` varchar(50) DEFAULT NULL,
  `cover_image` varchar(500) DEFAULT NULL,
  `created_by` int(11) UNSIGNED DEFAULT NULL,
  `is_published` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_courses_school` (`school_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `course_enrollments` (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `course_id` int(11) UNSIGNED NOT NULL,
  `user_id` int(11) UNSIGNED NOT NULL,
  `role` enum('teacher','student') NOT NULL DEFAULT 'student',
  `status` enum('active','completed','dropped') NOT NULL DEFAULT 'active',
  `enrolled_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_course_user` (`course_id`, `user_id`),
  KEY `idx_ce_user` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `modules` (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `course_id` int(11) UNSIGNED DEFAULT NULL,
  `class_program_id` int(11) UNSIGNED DEFAULT NULL,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `order_num` int(11) DEFAULT 1,
  `is_published` tinyint(1) NOT NULL DEFAULT 0,
  `created_by` int(11) UNSIGNED DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_modules_course` (`course_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `lessons` (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `module_id` int(11) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `content` longtext DEFAULT NULL,
  `content_type` enum('text','file','video','link') NOT NULL DEFAULT 'text',
  `file_path` varchar(500) DEFAULT NULL,
  `external_url` varchar(500) DEFAULT NULL,
  `duration_minutes` int(11) DEFAULT NULL,
  `order_num` int(11) DEFAULT 1,
  `is_published` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `fk_lesson_module` (`module_id`),
  CONSTRAINT `fk_lesson_module` FOREIGN KEY (`module_id`) REFERENCES `modules`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `lesson_progress` (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `student_id` int(11) UNSIGNED NOT NULL,
  `lesson_id` int(11) UNSIGNED NOT NULL,
  `status` enum('not_started','in_progress','completed') DEFAULT 'not_started',
  `progress_percent` tinyint(3) DEFAULT 0,
  `completed_at` datetime DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `fk_lp_student` (`student_id`),
  KEY `fk_lp_lesson` (`lesson_id`),
  CONSTRAINT `fk_lp_student` FOREIGN KEY (`student_id`) REFERENCES `students`(`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_lp_lesson` FOREIGN KEY (`lesson_id`) REFERENCES `lessons`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- =====================================================
-- SECTION 9: ASSESSMENT SYSTEM
-- =====================================================

CREATE TABLE IF NOT EXISTS `quizzes` (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `course_id` int(11) UNSIGNED DEFAULT NULL,
  `class_program_id` int(11) UNSIGNED DEFAULT NULL,
  `school_id` int(11) UNSIGNED DEFAULT NULL,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `quiz_type` enum('quiz','exam','assignment','activity') NOT NULL DEFAULT 'quiz',
  `component_id` int(11) UNSIGNED DEFAULT NULL,
  `total_points` decimal(6,2) DEFAULT NULL,
  `time_limit_minutes` int(11) DEFAULT NULL,
  `max_attempts` tinyint(3) DEFAULT 1,
  `shuffle_questions` tinyint(1) DEFAULT 0,
  `show_results` tinyint(1) DEFAULT 1,
  `available_from` datetime DEFAULT NULL,
  `available_until` datetime DEFAULT NULL,
  `is_published` tinyint(1) NOT NULL DEFAULT 0,
  `created_by` int(11) UNSIGNED DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_quizzes_course` (`course_id`),
  KEY `idx_quizzes_cp` (`class_program_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `quiz_questions` (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `quiz_id` int(11) UNSIGNED NOT NULL,
  `question_type` enum('multiple_choice','identification','essay','true_false') NOT NULL,
  `question_text` text NOT NULL,
  `question_image` varchar(500) DEFAULT NULL,
  `points` decimal(5,2) NOT NULL DEFAULT 1.00,
  `order_num` int(11) DEFAULT 1,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `fk_qq_quiz` (`quiz_id`),
  CONSTRAINT `fk_qq_quiz` FOREIGN KEY (`quiz_id`) REFERENCES `quizzes`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `quiz_choices` (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `question_id` int(11) UNSIGNED NOT NULL,
  `choice_text` text NOT NULL,
  `is_correct` tinyint(1) NOT NULL DEFAULT 0,
  `order_num` tinyint(3) DEFAULT 1,
  PRIMARY KEY (`id`),
  KEY `fk_qc_question` (`question_id`),
  CONSTRAINT `fk_qc_question` FOREIGN KEY (`question_id`) REFERENCES `quiz_questions`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `course_collaborators` (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `course_id` int(11) UNSIGNED NOT NULL,
  `teacher_id` int(11) UNSIGNED NOT NULL,
  `section_id` int(11) UNSIGNED DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_collab_course` (`course_id`),
  KEY `idx_collab_teacher` (`teacher_id`),
  KEY `idx_collab_section` (`section_id`),
  CONSTRAINT `fk_collab_course` FOREIGN KEY (`course_id`) REFERENCES `courses`(`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_collab_teacher` FOREIGN KEY (`teacher_id`) REFERENCES `users`(`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_collab_section` FOREIGN KEY (`section_id`) REFERENCES `sections`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `section_enrollment_keys` (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `course_id` int(11) UNSIGNED NOT NULL,
  `section_id` int(11) UNSIGNED NOT NULL,
  `enrollment_key` varchar(50) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_section_key` (`course_id`, `section_id`),
  KEY `idx_section_key_course` (`course_id`),
  KEY `idx_section_key_section` (`section_id`),
  CONSTRAINT `fk_section_key_course` FOREIGN KEY (`course_id`) REFERENCES `courses`(`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_section_key_section` FOREIGN KEY (`section_id`) REFERENCES `sections`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `quiz_attempts` (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `quiz_id` int(11) UNSIGNED NOT NULL,
  `student_id` int(11) UNSIGNED NOT NULL,
  `attempt_number` tinyint(3) NOT NULL DEFAULT 1,
  `score` decimal(6,2) DEFAULT NULL,
  `total_points` decimal(6,2) DEFAULT NULL,
  `percentage` decimal(5,2) DEFAULT NULL,
  `status` enum('in_progress','submitted','graded') NOT NULL DEFAULT 'in_progress',
  `started_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `submitted_at` datetime DEFAULT NULL,
  `graded_at` datetime DEFAULT NULL,
  `graded_by` int(11) UNSIGNED DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_qa_quiz` (`quiz_id`),
  KEY `fk_qa_student` (`student_id`),
  CONSTRAINT `fk_qa_quiz` FOREIGN KEY (`quiz_id`) REFERENCES `quizzes`(`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_qa_student` FOREIGN KEY (`student_id`) REFERENCES `users`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `quiz_attempt_answers` (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `attempt_id` int(11) UNSIGNED NOT NULL,
  `question_id` int(11) UNSIGNED NOT NULL,
  `answer_text` text DEFAULT NULL,
  `choice_id` int(11) UNSIGNED DEFAULT NULL,
  `is_correct` tinyint(1) DEFAULT NULL,
  `score` decimal(5,2) DEFAULT NULL,
  `feedback` text DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_qaa_attempt` (`attempt_id`),
  KEY `fk_qaa_question` (`question_id`),
  CONSTRAINT `fk_qaa_attempt` FOREIGN KEY (`attempt_id`) REFERENCES `quiz_attempts`(`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_qaa_question` FOREIGN KEY (`question_id`) REFERENCES `quiz_questions`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `question_bank` (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `subject_id` int(11) UNSIGNED NOT NULL,
  `question_type` enum('multiple_choice','identification','essay','true_false') NOT NULL,
  `question_text` text NOT NULL,
  `answer_key` text DEFAULT NULL,
  `difficulty` enum('easy','medium','hard') DEFAULT 'medium',
  `created_by` int(11) UNSIGNED DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `fk_qb_subject` (`subject_id`),
  CONSTRAINT `fk_qb_subject` FOREIGN KEY (`subject_id`) REFERENCES `subjects`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- =====================================================
-- SECTION 10: ATTENDANCE
-- =====================================================

CREATE TABLE IF NOT EXISTS `attendance` (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `course_id` int(11) UNSIGNED NOT NULL DEFAULT 0,
  `user_id` int(11) UNSIGNED NOT NULL,
  `date` date NOT NULL,
  `login_time` datetime DEFAULT NULL,
  `logout_time` datetime DEFAULT NULL,
  `duration_minutes` int(11) UNSIGNED NOT NULL DEFAULT 0,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_attendance_course` (`course_id`),
  KEY `idx_attendance_user` (`user_id`),
  KEY `idx_attendance_date` (`date`),
  CONSTRAINT `fk_attendance_user` FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- =====================================================
-- SECTION 11: COMMUNICATION
-- =====================================================

CREATE TABLE IF NOT EXISTS `announcements` (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `audience` enum('all','teachers','students','parents','section') NOT NULL DEFAULT 'all',
  `section_id` int(11) UNSIGNED DEFAULT NULL,
  `class_program_id` int(11) UNSIGNED DEFAULT NULL,
  `is_pinned` tinyint(1) NOT NULL DEFAULT 0,
  `published_at` datetime DEFAULT NULL,
  `created_by` int(11) UNSIGNED DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `notifications` (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` int(11) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `message` text DEFAULT NULL,
  `type` varchar(50) DEFAULT 'info',
  `link` varchar(500) DEFAULT NULL,
  `is_read` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `fk_notif_user` (`user_id`),
  CONSTRAINT `fk_notif_user` FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- =====================================================
-- SECTION 12: ACTIVITY LOGS
-- =====================================================

CREATE TABLE IF NOT EXISTS `activity_logs` (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` int(11) UNSIGNED DEFAULT NULL,
  `action` varchar(100) NOT NULL,
  `module` varchar(50) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `fk_log_user` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
