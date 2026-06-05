ALTER TABLE `lessons`
ADD COLUMN `taught_at` DATETIME DEFAULT NULL AFTER `is_published`;
