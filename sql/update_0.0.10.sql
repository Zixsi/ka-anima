ALTER TABLE `review` ADD `file_url` VARCHAR(255) NULL AFTER `video_url`;

CREATE TABLE `wall` ( 
	`id` INT UNSIGNED NOT NULL AUTO_INCREMENT , 
	`group_id` INT UNSIGNED NOT NULL , 
	`user` INT UNSIGNED NOT NULL , 
	`text` TEXT NOT NULL , 
	`target` INT UNSIGNED NOT NULL , 
	`ts` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP , 
	`del` TINYINT UNSIGNED NOT NULL , 
	PRIMARY KEY (`id`)
) ENGINE = InnoDB;