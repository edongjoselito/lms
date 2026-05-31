-- Add admin_password column to schools table for storing school admin password during signup
ALTER TABLE `schools` ADD COLUMN `admin_password` VARCHAR(255) NULL AFTER `confirmation_token`;
