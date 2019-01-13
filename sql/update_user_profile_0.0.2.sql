ALTER TABLE `users` 
	ADD `name` VARCHAR(255) NULL AFTER `role`, 
	ADD `lastname` VARCHAR(255) NULL AFTER `name`, 
	ADD `birthday` DATE NULL DEFAULT NULL AFTER `lastname`, 
	ADD `phone` VARCHAR(50) NULL DEFAULT NULL  AFTER `birthday`;