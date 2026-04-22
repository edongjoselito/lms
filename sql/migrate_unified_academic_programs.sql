-- Migration to merge grade_levels and programs into unified academic_programs table

-- Step 1: Create the unified academic_programs table
CREATE TABLE IF NOT EXISTS `academic_programs` (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `school_id` int(11) UNSIGNED NOT NULL DEFAULT 1,
  `name` varchar(255) NOT NULL,
  `code` varchar(50) NOT NULL,
  `description` text DEFAULT NULL,
  `type` enum('grade_level', 'program') NOT NULL DEFAULT 'grade_level',
  `category` enum('elementary','junior_high','senior_high') DEFAULT NULL COMMENT 'For grade levels',
  `degree_type` enum('bachelor','master','doctorate','diploma','certificate') DEFAULT NULL COMMENT 'For programs',
  `level_order` tinyint(3) DEFAULT NULL COMMENT 'For grade levels',
  `total_units` decimal(5,1) DEFAULT NULL COMMENT 'For programs',
  `years_to_complete` tinyint(2) DEFAULT NULL COMMENT 'For programs',
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `school_code` (`school_id`, `code`),
  KEY `idx_school_id` (`school_id`),
  KEY `idx_type` (`type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Step 2: Migrate grade_levels data
INSERT INTO `academic_programs` (`school_id`, `name`, `code`, `description`, `type`, `category`, `level_order`, `status`)
SELECT 
    1 as school_id,
    name,
    code,
    NULL as description,
    'grade_level' as type,
    category,
    level_order,
    status
FROM `grade_levels`;

-- Step 3: Migrate programs data
INSERT INTO `academic_programs` (`school_id`, `name`, `code`, `description`, `type`, `degree_type`, `total_units`, `years_to_complete`, `status`, `created_at`)
SELECT 
    school_id,
    name,
    code,
    description,
    'program' as type,
    degree_type,
    total_units,
    years_to_complete,
    status,
    created_at
FROM `programs`;

-- Step 4: Update subjects table to reference academic_programs instead of grade_levels and programs
-- First, add the new academic_program_id column to subjects
ALTER TABLE `subjects` ADD COLUMN `academic_program_id` int(11) UNSIGNED DEFAULT NULL AFTER `id`;

-- Migrate grade_level references
UPDATE `subjects` s
JOIN `grade_levels` gl ON s.grade_level_id = gl.id
JOIN `academic_programs` ap ON ap.code = gl.code AND ap.type = 'grade_level' AND ap.school_id = s.school_id
SET s.academic_program_id = ap.id
WHERE s.grade_level_id IS NOT NULL;

-- Migrate program references
UPDATE `subjects` s
JOIN `programs` p ON s.program_id = p.id
JOIN `academic_programs` ap ON ap.code = p.code AND ap.type = 'program' AND ap.school_id = s.school_id
SET s.academic_program_id = ap.id
WHERE s.program_id IS NOT NULL;

-- Step 5: Backup and drop old tables (commented out for safety - uncomment after verification)
-- RENAME TABLE `grade_levels` TO `grade_levels_backup`;
-- RENAME TABLE `programs` TO `programs_backup`;
