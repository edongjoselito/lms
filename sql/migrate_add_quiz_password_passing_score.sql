-- Add passing_score and quiz_password columns to quizzes table
ALTER TABLE `quizzes` ADD COLUMN `passing_score` DECIMAL(6,2) DEFAULT NULL AFTER `is_published`;
ALTER TABLE `quizzes` ADD COLUMN `quiz_password` VARCHAR(255) DEFAULT NULL AFTER `passing_score`;
