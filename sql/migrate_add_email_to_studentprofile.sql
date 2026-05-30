-- Migration: Add email column to studentprofile table
ALTER TABLE `studentprofile` ADD COLUMN `email` varchar(255) DEFAULT NULL AFTER `birth_date`;
