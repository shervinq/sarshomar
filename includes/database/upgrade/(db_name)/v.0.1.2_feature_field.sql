ALTER TABLE `surveys` ADD `template` BIT(1) NULL DEFAULT NULL;
ALTER TABLE `surveys` ADD `starttime` datetime NULL DEFAULT NULL;
ALTER TABLE `surveys` ADD `endtime` datetime NULL DEFAULT NULL;
ALTER TABLE `surveys` ADD `mobiles` mediumtext CHARACTER SET utf8mb4 NULL DEFAULT NULL;
ALTER TABLE `surveys` ADD `referer` varchar(2000) NULL DEFAULT NULL;

ALTER TABLE `answers` ADD `respondercode` varchar(200) NULL DEFAULT NULL AFTER `user_id`;
ALTER TABLE `answers` ADD `respondermobile` varchar(15) NULL DEFAULT NULL AFTER `user_id`;

ALTER TABLE `questions` ADD `address` varchar(200) NULL DEFAULT NULL AFTER `id`;
