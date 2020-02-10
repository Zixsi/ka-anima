CREATE TABLE `notifications` ( 
	`id` INT UNSIGNED NOT NULL AUTO_INCREMENT , 
	`user` INT UNSIGNED NOT NULL , 
	`text` VARCHAR(1000) NULL , 
	`type` VARCHAR(40) NOT NULL , 
	`param1` VARCHAR(255) NULL , 
	`param2` VARCHAR(255) NULL , 
	`param3` VARCHAR(255) NULL , 
	`status` TINYINT(1) UNSIGNED NOT NULL DEFAULT '0' , 
	`date` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP , 
	PRIMARY KEY (`id`), 
	INDEX `user_items` (`user`, `status`, `type`)
) ENGINE = InnoDB;