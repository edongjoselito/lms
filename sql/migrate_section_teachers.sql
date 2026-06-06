-- Migration: Add section_teachers junction table
-- This allows teachers to be assigned to specific subjects within sections
-- Teachers assigned to a section+subject can access only that subject in that section
-- Reference table is staff with primary key IDNumber

CREATE TABLE IF NOT EXISTS `section_teachers` (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `section_id` int(11) UNSIGNED NOT NULL,
  `subject_id` int(11) UNSIGNED NOT NULL,
  `staff_id` varchar(20) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_section_subject_staff` (`section_id`, `subject_id`, `staff_id`),
  KEY `fk_st_section` (`section_id`),
  KEY `fk_st_subject` (`subject_id`),
  KEY `fk_st_staff` (`staff_id`),
  CONSTRAINT `fk_st_section` FOREIGN KEY (`section_id`) REFERENCES `sections`(`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_st_subject` FOREIGN KEY (`subject_id`) REFERENCES `subjects`(`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_st_staff` FOREIGN KEY (`staff_id`) REFERENCES `staff`(`IDNumber`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
