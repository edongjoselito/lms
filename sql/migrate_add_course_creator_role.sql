-- Migration: Add Course Creator role
-- Run this if your database was installed before the course creator feature

INSERT IGNORE INTO `roles` (`name`, `slug`, `description`) 
VALUES ('Course Creator', 'course_creator', 'Manages LMS content per subject/course');
