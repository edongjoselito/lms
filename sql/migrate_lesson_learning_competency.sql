ALTER TABLE `lessons`
ADD COLUMN `learning_competency_id` INT(11) UNSIGNED DEFAULT NULL AFTER `module_id`,
ADD KEY `idx_lesson_learning_competency` (`learning_competency_id`);
