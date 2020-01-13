ALTER TABLE `workshop` 
	ADD `meta_keyword` TEXT NULL AFTER `description`, 
	ADD `meta_description` TEXT NULL AFTER `meta_keyword`;