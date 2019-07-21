CREATE TABLE `faq_sections` ( 
	`id` INT UNSIGNED NOT NULL AUTO_INCREMENT , 
	`name` VARCHAR(255) NOT NULL , 
	PRIMARY KEY (`id`)
) ENGINE = InnoDB;

ALTER TABLE `faq` ADD `section` INT UNSIGNED NOT NULL DEFAULT '0' AFTER `id`;

ALTER TABLE `news` ADD `description` TEXT NULL AFTER `title`, ADD `img` VARCHAR(255) NULL AFTER `text`;

ALTER TABLE `subscription` ADD `hash` VARCHAR(40) NOT NULL AFTER `data`, ADD UNIQUE `hash` (`hash`);
UPDATE subscription SET hash = md5(concat(user, type, target, target_type));

ALTER TABLE `transactions` DROP `service`, DROP `service_id`;
ALTER TABLE `transactions` ADD `data` TEXT NULL AFTER `description`;
ALTER TABLE `transactions` CHANGE `type` `type` ENUM('IN','OUT') NOT NULL DEFAULT 'IN';
ALTER TABLE `transactions` ADD `status` ENUM('SUCCESS', 'PENDING', 'ERROR') NOT NULL DEFAULT 'PENDING' AFTER `data`;
ALTER TABLE `transactions` ADD `hash` VARCHAR(40) NOT NULL AFTER `ts`, ADD UNIQUE `hash` (`hash`);