ALTER TABLE `courses` ADD `price` TEXT NOT NULL AFTER `price_full`;
ALTER TABLE `courses` DROP `price_month`, DROP `price_full`, DROP `type`;

ALTER TABLE `subscription` 
	DROP `price_month`, DROP `price_full`, 
	CHANGE `type` `type` ENUM('standart', 'advanced', 'vip', 'private') NOT NULL DEFAULT 'standart', 
	CHANGE `service` `target` INT(11) UNSIGNED NOT NULL, 
	ADD `target_type` ENUM('course') NOT NULL DEFAULT 'course' AFTER `target`, 
	ADD `data` TEXT NULL AFTER `amount`;
	
ALTER TABLE `courses` ADD `only_standart` TINYINT UNSIGNED NOT NULL DEFAULT '0' AFTER `active`;

ALTER TABLE `users` 
	ADD `deleted` TINYINT(1) UNSIGNED NOT NULL DEFAULT '0' AFTER `phone`, 
	ADD `blocked` TINYINT(1) UNSIGNED NOT NULL DEFAULT '0' AFTER `deleted`;