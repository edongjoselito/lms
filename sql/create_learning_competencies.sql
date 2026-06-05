-- Learning Competencies Table
-- Stores learning competencies for each subject

CREATE TABLE IF NOT EXISTS `learning_competencies` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `subject_id` int(11) NOT NULL,
  `school_id` int(11) DEFAULT NULL,
  `code` varchar(50) DEFAULT NULL,
  `description` text NOT NULL,
  `quarter` int(11) DEFAULT NULL,
  `sort_order` int(11) DEFAULT 0,
  `created_by` int(11) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `subject_id` (`subject_id`),
  KEY `school_id` (`school_id`),
  KEY `created_by` (`created_by`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
