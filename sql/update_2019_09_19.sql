ALTER TABLE `transactions` 
	ADD `course_id` INT UNSIGNED NULL AFTER `pay_system_hash`, 
	ADD `group_id` INT UNSIGNED NULL AFTER `course_id`, 
	ADD INDEX `course_id` (`course_id`), 
	ADD INDEX `group_id` (`group_id`);