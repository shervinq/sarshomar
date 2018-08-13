CREATE TABLE `answerterms` (
`id`            bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
`type`          varchar(200) NULL,
`text`	 	    text CHARACTER SET utf8mb4,
`file` 		    text CHARACTER SET utf8mb4,
`datecreated`   timestamp DEFAULT CURRENT_TIMESTAMP,
`datemodified`  timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `answers` (
`id`            bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
`user_id`       int(10) UNSIGNED NOT NULL,
`survey_id`     bigint(20) UNSIGNED NOT NULL,
`startdate`     datetime NULL,
`enddate`       datetime NULL,
`lastmodified`  datetime NULL,
`status`        enum('start','early','middle','late','complete','skip','spam','filter','block') NULL DEFAULT NULL,
`step`     		bigint(20) UNSIGNED NULL,
`lastquestion`  bigint(20) UNSIGNED NULL,
`ref`	        varchar(1000) NULL,
`complete`	    bit(1) NULL,
`skip`     		int(10) UNSIGNED NULL,
`skiptry`   	int(10) UNSIGNED NULL,
`answer`   		int(10) UNSIGNED NULL,
`answertry`   	int(10) UNSIGNED NULL,
`datecreated`   timestamp DEFAULT CURRENT_TIMESTAMP,
`datemodified`  timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
PRIMARY KEY (`id`),
CONSTRAINT `answers_user_id` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON UPDATE CASCADE,
CONSTRAINT `answers_survey_id` FOREIGN KEY (`survey_id`) REFERENCES `surveys` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



CREATE TABLE `answerdetails` (
`id`            bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
`user_id`       int(10) UNSIGNED NOT NULL,
`survey_id`     bigint(20) UNSIGNED NOT NULL,
`answer_id`     bigint(20) UNSIGNED NOT NULL,
`question_id`   bigint(20) UNSIGNED NOT NULL,
`answerterm_id` bigint(20) UNSIGNED NULL,
`skip`    		bit(1) NULL,
`dateview`      datetime NULL,
`dateanswer`    datetime NULL,
`datecreated`   timestamp DEFAULT CURRENT_TIMESTAMP,
`datemodified`  timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
PRIMARY KEY (`id`),
CONSTRAINT `answerdetails_user_id` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON UPDATE CASCADE,
CONSTRAINT `answerdetails_survey_id` FOREIGN KEY (`survey_id`) REFERENCES `surveys` (`id`) ON UPDATE CASCADE,
CONSTRAINT `answerdetails_answer_id` FOREIGN KEY (`answer_id`) REFERENCES `answers` (`id`) ON UPDATE CASCADE,
CONSTRAINT `answerdetails_question_id` FOREIGN KEY (`question_id`) REFERENCES `questions` (`id`) ON UPDATE CASCADE,
CONSTRAINT `answerdetails_answerterm_id` FOREIGN KEY (`answerterm_id`) REFERENCES `answerterms` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

