-- Add created_by to subjects
ALTER TABLE `subjects` ADD COLUMN `created_by` INT UNSIGNED DEFAULT NULL AFTER `status`;

-- Subject collaborators table
CREATE TABLE IF NOT EXISTS `subject_collaborators` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `subject_id` INT UNSIGNED NOT NULL,
  `user_id` INT UNSIGNED NOT NULL,
  `added_by` INT UNSIGNED NOT NULL,
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_subject_user` (`subject_id`, `user_id`),
  KEY `idx_sc_subject` (`subject_id`),
  KEY `idx_sc_user` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
