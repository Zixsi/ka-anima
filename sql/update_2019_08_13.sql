ALTER TABLE `users` 
	ADD `title` VARCHAR(255) NULL AFTER `blocked`, 
	ADD `soc` VARCHAR(255) NULL AFTER `title`, 
	ADD `img` VARCHAR(255) NULL AFTER `soc`;