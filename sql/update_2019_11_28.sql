ALTER TABLE video DROP PRIMARY KEY;
ALTER TABLE `video` ADD `id` INT UNSIGNED NOT NULL AUTO_INCREMENT FIRST, ADD PRIMARY KEY (`id`);
ALTER TABLE `video` ADD `duration` INT UNSIGNED NOT NULL DEFAULT '0' AFTER `type`;
ALTER TABLE `video` ADD UNIQUE `video_code` (`video_code`);
ALTER TABLE `video` ADD INDEX `source` (`source_id`, `source_type`);
ALTER TABLE `video` ADD `sort` SMALLINT UNSIGNED NOT NULL DEFAULT '500' AFTER `duration`;
ALTER TABLE `video` ADD `title` VARCHAR(255) NULL AFTER `video_code`;
ALTER TABLE `video` CHANGE `video_code` `video_code` VARCHAR(80) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL;

CREATE TABLE `workshop` ( 
	`id` INT UNSIGNED NOT NULL AUTO_INCREMENT , 
	`code` VARCHAR(40) NOT NULL , 
	`type` ENUM('collection','webinar') NOT NULL , 
	`title` VARCHAR(255) NOT NULL , 
	`description` TEXT NULL , 
	`video` VARCHAR(255) NOT NULL ,
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


ALTER TABLE `subscription` CHANGE `target_type` `target_type` ENUM('course', 'workshop') CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'course';

ALTER TABLE `transactions` ADD `source` VARCHAR(80) NOT NULL DEFAULT 'course' AFTER `group_id`, ADD INDEX `source` (`source`);