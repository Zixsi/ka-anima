ALTER TABLE `courses` ADD `code` VARCHAR(40) NOT NULL AFTER `id`;
UPDATE courses SET code = CONCAT('course', id);
ALTER TABLE `courses` ADD UNIQUE `code` (`code`);
ALTER TABLE `courses_groups` ADD UNIQUE `code` (`code`);

CREATE TABLE `user_actions` ( 
	`user` INT UNSIGNED NOT NULL , 
	`date` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP , 
	`action` VARCHAR(80) NOT NULL , 
	`data` TEXT NULL 
) ENGINE = InnoDB;
ALTER TABLE `user_actions` ADD `hash` VARCHAR(40) NOT NULL AFTER `data`, ADD UNIQUE `hash` (`hash`);
ALTER TABLE `user_actions` ADD `date_ts` INT UNSIGNED NOT NULL AFTER `date`;