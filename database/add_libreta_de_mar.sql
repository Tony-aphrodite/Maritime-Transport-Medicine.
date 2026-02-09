-- Add libreta_de_mar column to users table
-- Run this SQL if migration cannot be executed via artisan

ALTER TABLE `users` ADD COLUMN `libreta_de_mar` VARCHAR(50) NULL AFTER `rfc`;
