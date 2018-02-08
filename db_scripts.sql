CREATE DATABASE `profile_import_share` /*!40100 DEFAULT CHARACTER SET latin1 */;

use profile_import_share;

CREATE TABLE `positions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `userId` int(11) NOT NULL,
  `title` varchar(255) DEFAULT NULL,
  `summary` varchar(255) DEFAULT NULL,
  `start_date` varchar(45) DEFAULT NULL,
  `end_date` varchar(45) DEFAULT NULL,
  `company` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB;

CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `oauth_provider` varchar(255) NOT NULL,
  `oauth_uid` varchar(255) NOT NULL,
  `first_name` varchar(2048) NOT NULL,
  `last_name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `gender` varchar(10) NOT NULL,
  `locale` varchar(10) NOT NULL,
  `picture_url` varchar(255) NOT NULL,
  `profile_url` varchar(255) NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  `current_location` varchar(255) DEFAULT NULL,
  `industry` varchar(45) DEFAULT NULL,
  `summary` varchar(255) DEFAULT NULL,
  `specialties` varchar(255) DEFAULT NULL,
  `interests` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB;


