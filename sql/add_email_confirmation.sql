-- Add email confirmation columns to schools table
ALTER TABLE `schools` 
ADD COLUMN `confirmation_token` VARCHAR(64) DEFAULT NULL AFTER `logo`,
ADD COLUMN `confirmed_at` DATETIME DEFAULT NULL AFTER `confirmation_token`;

-- Add index for faster lookup of confirmation tokens
ALTER TABLE `schools` 
ADD INDEX `idx_confirmation_token` (`confirmation_token`);
