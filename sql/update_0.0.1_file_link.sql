CREATE TABLE `link_files` ( 
	`file` INT(11) UNSIGNED NOT NULL , 
	`item` INT(11) UNSIGNED NOT NULL , 
	`item_type` VARCHAR(100) NOT NULL 
) ENGINE = InnoDB;

ALTER TABLE `link_files` ADD UNIQUE `file_item_type` (`file`, `item`, `item_type`);