-- Add school_id column to grade_levels table for multi-tenancy
ALTER TABLE `grade_levels` ADD COLUMN `school_id` int(11) UNSIGNED NOT NULL DEFAULT 1 AFTER `id`;

-- Update existing grade levels to have school_id = 1 (default school)
UPDATE `grade_levels` SET `school_id` = 1 WHERE `school_id` IS NULL OR `school_id` = 0;

-- Add index for better performance
ALTER TABLE `grade_levels` ADD INDEX `idx_school_id` (`school_id`);
