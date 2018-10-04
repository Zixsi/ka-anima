ALTER TABLE `courses` 
	CHANGE `price_month` `price_month` DECIMAL(13,2) UNSIGNED NOT NULL, 
	CHANGE `price_full` `price_full` DECIMAL(13,2) UNSIGNED NOT NULL;

ALTER TABLE `subscription` CHANGE `amount` `amount` DECIMAL(13,2) UNSIGNED NOT NULL DEFAULT '0.00';