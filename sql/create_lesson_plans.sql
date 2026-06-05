-- Lesson Plans Table
-- Stores lesson plans for each lesson based on ILAW template

CREATE TABLE IF NOT EXISTS `lesson_plans` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `lesson_id` int(11) NOT NULL,
  `school_id` int(11) DEFAULT NULL,
  `objectives` text,
  `subject_matter` text,
  `materials` text,
  `procedures` text,
  `evaluation` text,
  `assignment` text,
  `remarks` text,
  `created_by` int(11) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `lesson_id` (`lesson_id`),
  KEY `school_id` (`school_id`),
  KEY `created_by` (`created_by`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
