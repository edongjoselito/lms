ALTER TABLE `learning_competencies`
ADD COLUMN `created_by` INT(11) UNSIGNED DEFAULT NULL AFTER `sort_order`,
ADD KEY `created_by` (`created_by`);
