ALTER TABLE `users` ADD `login` VARCHAR(255) NOT NULL AFTER `id`;
UPDATE `users` SET login = email;
ALTER TABLE `users` ADD UNIQUE `login` (`login`);
ALTER TABLE `users` CHANGE `email` `email` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL;
ALTER TABLE `users` ADD `network` VARCHAR(40) NULL DEFAULT NULL AFTER `discord`, ADD INDEX `network` (`network`);
ALTER TABLE `users` ADD `parent` INT UNSIGNED NOT NULL DEFAULT '0' AFTER `network`, ADD INDEX `parent` (`parent`);