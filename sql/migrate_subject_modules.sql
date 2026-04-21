-- Migration: Add subject_id to modules table for subject-based content management
-- Run this if your database was installed before the course content feature

ALTER TABLE `modules` 
ADD COLUMN IF NOT EXISTS `subject_id` int(11) UNSIGNED DEFAULT NULL AFTER `id`,
ADD INDEX IF NOT EXISTS `idx_modules_subject` (`subject_id`);

-- Create activities table for assignments, quizzes, forums, etc.
CREATE TABLE IF NOT EXISTS `activities` (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `module_id` int(11) UNSIGNED NOT NULL,
  `type` enum('assignment','quiz','forum','resource','page','label') NOT NULL DEFAULT 'page',
  `title` varchar(255) NOT NULL,
  `content` text DEFAULT NULL,
  `settings` json DEFAULT NULL,
  `order_num` int(11) DEFAULT 1,
  `is_published` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_activities_module` (`module_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
