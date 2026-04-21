-- Migration: Add lesson completions table for sequential access
-- This tracks which lessons students have completed

CREATE TABLE IF NOT EXISTS `lesson_completions` (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `student_id` int(11) UNSIGNED NOT NULL,
  `lesson_id` int(11) UNSIGNED NOT NULL,
  `completed_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_student_lesson` (`student_id`, `lesson_id`),
  KEY `idx_student_id` (`student_id`),
  KEY `idx_lesson_id` (`lesson_id`),
  CONSTRAINT `fk_lc_student` FOREIGN KEY (`student_id`) REFERENCES `students`(`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_lc_lesson` FOREIGN KEY (`lesson_id`) REFERENCES `lessons`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
