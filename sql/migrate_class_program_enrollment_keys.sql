-- Migration: Add optional enrollment keys for subject sections.
-- Empty enrollment_key means students can access the course content by default.

SET @has_enrollment_key := (
    SELECT COUNT(*)
    FROM INFORMATION_SCHEMA.COLUMNS
    WHERE TABLE_SCHEMA = DATABASE()
      AND TABLE_NAME = 'class_programs'
      AND COLUMN_NAME = 'enrollment_key'
);

SET @add_enrollment_key_sql := IF(
    @has_enrollment_key = 0,
    'ALTER TABLE `class_programs` ADD COLUMN `enrollment_key` varchar(50) DEFAULT NULL AFTER `teacher_id`',
    'SELECT ''class_programs.enrollment_key already exists'' AS message'
);

PREPARE add_enrollment_key_stmt FROM @add_enrollment_key_sql;
EXECUTE add_enrollment_key_stmt;
DEALLOCATE PREPARE add_enrollment_key_stmt;
