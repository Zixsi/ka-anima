CREATE TABLE `tasks` ( 
	`id` INT UNSIGNED NOT NULL AUTO_INCREMENT , 
	`type` VARCHAR(80) NOT NULL , 
	`event` VARCHAR(80) NOT NULL , 
	`priority` TINYINT UNSIGNED NOT NULL , 
	`target` VARCHAR(255) NULL , 
	`data` TEXT NOT NULL , 
	`status` TINYINT UNSIGNED NOT NULL DEFAULT '0',
	`date` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
	PRIMARY KEY (`id`) 
) ENGINE = InnoDB;

ALTER TABLE `tasks` 
	ADD INDEX `type` (`type`), 
	ADD INDEX `event` (`event`), 
	ADD INDEX `priority` (`priority`), 
	ADD INDEX `status` (`status`);

ALTER TABLE `user_actions` 
	ADD `ip` VARCHAR(20) NULL AFTER `data`, 
	ADD `ua` TEXT NULL AFTER `ip`;

ALTER TABLE `user_actions` ADD `id` INT UNSIGNED NOT NULL AUTO_INCREMENT FIRST, ADD PRIMARY KEY (`id`);
ALTER TABLE `user_actions` DROP INDEX hash;
ALTER TABLE `user_actions` DROP `hash`;

ALTER TABLE `users` ADD `ts_last_active` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP AFTER `ts_modify`;