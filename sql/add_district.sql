-- Add district column to schools table
ALTER TABLE `schools` ADD COLUMN `district` VARCHAR(100) NULL AFTER `contact_number`;
