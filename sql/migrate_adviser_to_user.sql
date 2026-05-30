-- Migration: Change sections.adviser_id to reference users.id instead of teachers.id
-- This allows selecting advisers from users with Teacher role

-- Drop existing foreign key constraint
ALTER TABLE `sections` DROP FOREIGN KEY `fk_sec_adviser`;

-- Add new foreign key to users table
ALTER TABLE `sections` ADD CONSTRAINT `fk_sec_adviser_user` FOREIGN KEY (`adviser_id`) REFERENCES `users`(`id`) ON DELETE SET NULL;
