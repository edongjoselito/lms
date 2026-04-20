-- =====================================================
-- MULTI-TENANT MIGRATION
-- Adds school_id scoping to all tenant-aware tables
-- =====================================================
USE `lms_db`;

-- Users belong to a school (NULL = super_admin / platform-level)
ALTER TABLE `users` ADD COLUMN `school_id` int(11) UNSIGNED DEFAULT NULL AFTER `role_id`,
  ADD KEY `fk_users_school` (`school_id`);

-- Students scoped to school
ALTER TABLE `students` ADD COLUMN `school_id` int(11) UNSIGNED NOT NULL DEFAULT 1 AFTER `user_id`,
  ADD KEY `fk_students_school` (`school_id`);

-- Teachers scoped to school
ALTER TABLE `teachers` ADD COLUMN `school_id` int(11) UNSIGNED NOT NULL DEFAULT 1 AFTER `user_id`,
  ADD KEY `fk_teachers_school` (`school_id`);

-- Subjects can be school-specific
ALTER TABLE `subjects` ADD COLUMN `school_id` int(11) UNSIGNED DEFAULT NULL AFTER `id`,
  ADD KEY `fk_subjects_school` (`school_id`);

-- Sections scoped (already has school_year_id → school, but direct FK is cleaner)
ALTER TABLE `sections` ADD COLUMN `school_id` int(11) UNSIGNED NOT NULL DEFAULT 1 AFTER `school_year_id`,
  ADD KEY `fk_sections_school` (`school_id`);

-- Enrollments scoped
ALTER TABLE `enrollments` ADD COLUMN `school_id` int(11) UNSIGNED NOT NULL DEFAULT 1 AFTER `student_id`,
  ADD KEY `fk_enrollments_school` (`school_id`);

-- Grade components can be customized per school
ALTER TABLE `grade_components` ADD COLUMN `school_id` int(11) UNSIGNED DEFAULT NULL AFTER `id`,
  ADD KEY `fk_gc_school` (`school_id`);

-- Announcements scoped
ALTER TABLE `announcements` ADD COLUMN `school_id` int(11) UNSIGNED DEFAULT NULL AFTER `id`,
  ADD KEY `fk_ann_school` (`school_id`);

-- Quizzes inherit via class_program → section → school, but add direct for queries
ALTER TABLE `quizzes` ADD COLUMN `school_id` int(11) UNSIGNED DEFAULT NULL AFTER `class_program_id`,
  ADD KEY `fk_quiz_school` (`school_id`);

-- Update super admin to have NULL school_id (platform level)
UPDATE `users` SET `school_id` = NULL WHERE `id` = 1;
