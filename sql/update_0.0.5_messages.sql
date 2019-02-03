CREATE TABLE `user_messages` ( 
	`id` INT UNSIGNED NOT NULL AUTO_INCREMENT , 
	`user` INT UNSIGNED NOT NULL , 
	`target` INT UNSIGNED NOT NULL , 
	`text` TEXT NOT NULL , 
	`ts` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP , 
	`is_read` TINYINT(1) UNSIGNED NOT NULL DEFAULT '0' , 
	PRIMARY KEY (`id`)
) ENGINE = InnoDB;

CREATE TABLE `user_messages_black_list` ( 
	`id` INT UNSIGNED NOT NULL , 
	`user` INT UNSIGNED NOT NULL 
) ENGINE = InnoDB;

ALTER TABLE `user_messages_black_list` ADD UNIQUE `id_user` (`id`, `user`);

CREATE TABLE `user_friends` ( 
	`id` INT UNSIGNED NOT NULL , 
	`user` INT UNSIGNED NOT NULL 
) ENGINE = InnoDB;

ALTER TABLE `user_friends` ADD UNIQUE `id_user` (`id`, `user`);