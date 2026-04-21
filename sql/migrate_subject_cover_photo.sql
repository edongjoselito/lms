-- Migration: Add cover_photo column to subjects table
-- This allows each subject/course to have a cover image

ALTER TABLE `subjects` 
ADD COLUMN `cover_photo` varchar(255) DEFAULT NULL AFTER `description`;
