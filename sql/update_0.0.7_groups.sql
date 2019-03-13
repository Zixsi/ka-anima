ALTER TABLE `courses_groups` CHANGE `ts` `ts` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP;
ALTER TABLE `courses_groups` ADD `type` ENUM('standart','advanced','vip','private') NOT NULL DEFAULT 'standart' AFTER `course_id`;

ALTER TABLE `courses` ADD `teacher` INT UNSIGNED NOT NULL AFTER `author`;
ALTER TABLE `courses` DROP `author`;
ALTER TABLE `courses_groups` ADD `teacher` INT UNSIGNED NOT NULL AFTER `type`;

TRUNCATE courses_groups;
TRUNCATE lectures_groups;
TRUNCATE lectures_homework;
TRUNCATE subscription;
TRUNCATE transactions;
TRUNCATE streams;
TRUNCATE review;
TRUNCATE link_files