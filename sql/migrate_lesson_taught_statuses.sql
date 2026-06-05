CREATE TABLE IF NOT EXISTS `lesson_taught_statuses` (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `lesson_id` int(11) UNSIGNED NOT NULL,
  `user_id` int(11) UNSIGNED NOT NULL,
  `taught_at` DATETIME NOT NULL,
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uniq_lesson_user_taught` (`lesson_id`,`user_id`),
  KEY `idx_lesson_taught_user` (`user_id`),
  CONSTRAINT `fk_lesson_taught_lesson` FOREIGN KEY (`lesson_id`) REFERENCES `lessons` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_lesson_taught_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
