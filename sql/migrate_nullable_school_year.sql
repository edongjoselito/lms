-- Migration: Make school_year_id nullable in sections table
-- This allows creating custom sections without being tied to a specific school year

ALTER TABLE `sections` 
MODIFY COLUMN `school_year_id` int(11) UNSIGNED DEFAULT NULL;
