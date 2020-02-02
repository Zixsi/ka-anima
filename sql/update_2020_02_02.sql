CREATE TABLE `promocodes` ( 
	`id` INT UNSIGNED NOT NULL AUTO_INCREMENT , 
	`code` VARCHAR(40) NOT NULL , 
	`count` MEDIUMINT UNSIGNED NOT NULL , 
	`date_from` DATETIME NULL , 
	`date_to` DATETIME NULL , 
	`target_id` INT UNSIGNED NOT NULL , 
	`target_type` VARCHAR(40) NOT NULL , 
	`value` MEDIUMINT UNSIGNED NOT NULL , 
	PRIMARY KEY (`id`), 
	INDEX `target_id` (`target_id`), 
	INDEX `target_type` (`target_type`), 
	UNIQUE `code` (`code`)
) ENGINE = InnoDB;