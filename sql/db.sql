

CREATE TABLE `courses` ( 
	`id` INT UNSIGNED NOT NULL AUTO_INCREMENT ,
	`active` TINYINT(1) NOT NULL DEFAULT '0' ,  
	`name` VARCHAR(255) NOT NULL , 
	`description` TEXT NOT NULL , 
	`period` TINYINT UNSIGNED NOT NULL , 
	`price_month` DECIMAL(13,2) UNSIGNED NOT NULL , 
	`price_full` DECIMAL(13,2) UNSIGNED NOT NULL , 
	`author` INT(11) UNSIGNED NOT NULL , 
	`ts` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP , 
	PRIMARY KEY (`id`)
) ENGINE = InnoDB;

CREATE TABLE `lectures` ( 
	`id` INT UNSIGNED NOT NULL AUTO_INCREMENT ,
	`active` TINYINT UNSIGNED NOT NULL DEFAULT '0' , 
	`course` INT UNSIGNED NOT NULL , 
	`name` VARCHAR(255) NOT NULL , 
	`description` TEXT NOT NULL , 
	`video` VARCHAR(255) NOT NULL , 
	`created` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP , 
	`modify` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP , 
	`sort` SMALLINT UNSIGNED NOT NULL DEFAULT '500' , 
	PRIMARY KEY (`id`)
) ENGINE = InnoDB;

CREATE TABLE `transactions` ( 
	`id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT , 
	`user` INT(11) UNSIGNED NOT NULL , 
	`type` VARCHAR(255) NOT NULL , 
	`amount` DECIMAL(13,2) UNSIGNED NOT NULL , 
	`description` VARCHAR(255) NOT NULL , 
	`service` VARCHAR(255) NOT NULL , 
	`service_id` INT(11) UNSIGNED NOT NULL , 
	`ts` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP , 
	PRIMARY KEY (`id`)
) ENGINE = InnoDB;