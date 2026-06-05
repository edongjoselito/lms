CREATE TABLE IF NOT EXISTS `lesson_notes` (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `lesson_id` int(11) UNSIGNED NOT NULL,
  `school_id` int(11) UNSIGNED DEFAULT NULL,
  `note_text` TEXT NOT NULL,
  `created_by` int(11) UNSIGNED DEFAULT NULL,
  `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `lesson_id` (`lesson_id`),
  KEY `school_id` (`school_id`),
  KEY `created_by` (`created_by`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
