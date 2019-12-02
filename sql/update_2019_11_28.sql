CREATE TABLE `videos` ( 
	`code` VARCHAR(40) NOT NULL , 
	`video_code` VARCHAR(40) NOT NULL , 
	`video_url` VARCHAR(255) NOT NULL , 
	`source` VARCHAR(80) NOT NULL , 
	`params` TEXT NULL , 
	`duration` INT UNSIGNED NOT NULL DEFAULT '0' , 
	`update_date` TIMESTAMP NULL , 
	PRIMARY KEY (`code`), 
	UNIQUE `video_code` (`video_code`)
) ENGINE = InnoDB;


CREATE TABLE `workshop` ( 
	`id` INT UNSIGNED NOT NULL AUTO_INCREMENT , 
	`code` VARCHAR(40) NOT NULL , 
	`type` ENUM('collection','webinar') NOT NULL , 
	`title` VARCHAR(255) NOT NULL , 
	`description` TEXT NULL , 
	`video` VARCHAR(255) NOT NULL , 
	`video_list` TEXT NULL , 
	`video_description` TEXT NULL , 
	`img` VARCHAR(255) NULL ,
	`teacher` INT NOT NULL DEFAULT '0' , 
	`date` TIMESTAMP NOT NULL , 
	`price` DECIMAL(8,2) UNSIGNED NOT NULL DEFAULT '0',
	`status` TINYINT UNSIGNED NOT NULL DEFAULT '0',
	PRIMARY KEY (`id`), 
	INDEX `type` (`type`), 
	INDEX `teacher` (`teacher`), 
	INDEX `status` (`status`), 
	UNIQUE `code` (`code`)
) ENGINE = InnoDB;