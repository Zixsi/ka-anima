ALTER TABLE `users` ADD `blog_url` VARCHAR(255) NULL AFTER `img`;

ALTER TABLE `courses` 
	ADD `text_app_main` TEXT NULL AFTER `examples_url`, 
	ADD `text_app_other` TEXT NULL AFTER `text_app_main`, 
	ADD `preview_text` TEXT NULL AFTER `text_app_other`, 
	ADD `meta_keyword` TEXT NULL AFTER `preview_text`, 
	ADD `meta_description` TEXT NULL AFTER `meta_keyword`;