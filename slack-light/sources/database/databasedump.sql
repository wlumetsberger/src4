/*
SQLyog Community v12.12 (64 bit)
MySQL - 5.6.24 : Database - fh_2015_scm4_s1310307025
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
CREATE DATABASE /*!32312 IF NOT EXISTS*/`fh_2015_scm4_s1310307025` /*!40100 DEFAULT CHARACTER SET latin1 */;

USE `fh_2015_scm4_s1310307025`;

/*Table structure for table `channel` */

DROP TABLE IF EXISTS `channel`;

CREATE TABLE `channel` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `description` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

/*Data for the table `channel` */

insert  into `channel`(`id`,`name`,`description`) values (1,'TechCrunch','TechCrunch'),(2,'Channel 2','Channel 2');

/*Table structure for table `channel_user` */

DROP TABLE IF EXISTS `channel_user`;

CREATE TABLE `channel_user` (
  `user_id` int(11) NOT NULL,
  `channel_id` int(11) NOT NULL,
  `default_channel` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`user_id`,`channel_id`),
  KEY `channel_id` (`channel_id`),
  CONSTRAINT `channel_user_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`),
  CONSTRAINT `channel_user_ibfk_2` FOREIGN KEY (`channel_id`) REFERENCES `channel` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `channel_user` */

insert  into `channel_user`(`user_id`,`channel_id`,`default_channel`) values (1,1,1),(1,2,0),(2,1,1),(2,2,0),(3,1,1),(3,2,0);

/*Table structure for table `logging` */

DROP TABLE IF EXISTS `logging`;

CREATE TABLE `logging` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `ip` varchar(255) NOT NULL,
  `action` varchar(255) NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=latin1;

/*Data for the table `logging` */

insert  into `logging`(`id`,`user_id`,`ip`,`action`,`timestamp`) values (1,0,'::1','registered user wolfgang/Wolfgang/Lumetsberger','2015-08-31 19:19:28'),(2,0,'::1','login failed','2015-08-31 19:20:30'),(3,1,'::1','login','2015-08-31 19:24:48'),(4,1,'::1','insert new message','2015-08-31 19:28:25'),(5,1,'::1','logout user 1','2015-08-31 19:29:17'),(6,0,'::1','registered user max/Max/Mustermann','2015-08-31 19:29:40'),(7,2,'::1','login','2015-08-31 19:29:45'),(8,2,'::1','add message to favourite1','2015-08-31 19:31:31'),(9,2,'::1','insert new message','2015-08-31 19:34:16'),(10,2,'::1','edit message 2','2015-08-31 19:35:48'),(11,2,'::1','logout user 2','2015-08-31 19:36:42'),(12,1,'::1','login','2015-08-31 19:36:48'),(13,1,'::1','insert new message','2015-08-31 19:37:50'),(14,1,'::1','delete message 3','2015-08-31 19:38:27'),(15,1,'::1','insert new message','2015-08-31 19:41:15'),(16,1,'::1','logout user 1','2015-08-31 19:42:06'),(17,0,'::1','registered user scm4/scm4/scm4','2015-08-31 19:43:35'),(18,3,'::1','login','2015-08-31 19:43:40'),(19,3,'::1','logout user 3','2015-08-31 19:43:46');

/*Table structure for table `message` */

DROP TABLE IF EXISTS `message`;

CREATE TABLE `message` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user` int(11) NOT NULL,
  `channel` int(11) NOT NULL,
  `content` text,
  `creation` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `title` varchar(255) NOT NULL,
  `deleted` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `user` (`user`),
  KEY `channel` (`channel`),
  CONSTRAINT `message_ibfk_1` FOREIGN KEY (`user`) REFERENCES `user` (`id`),
  CONSTRAINT `message_ibfk_2` FOREIGN KEY (`channel`) REFERENCES `channel` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

/*Data for the table `message` */

insert  into `message`(`id`,`user`,`channel`,`content`,`creation`,`title`,`deleted`) values (1,1,2,'Das ist eine Testnachricht von Wolfgang','2015-08-31 19:28:25','Test 1',0),(2,2,2,'Das ist Antwort Nummer 1. Die Nachricht wurde soeben Editiert.','2015-08-31 19:35:48','Antwort 1',0),(3,1,2,'Das ist Antwort Nummer 2.','2015-08-31 19:38:27','Antwort 2',1),(4,1,1,'Das ist ein Eintrag','2015-08-31 19:41:15','TechCrunch Eintrag 1',0);

/*Table structure for table `message_user` */

DROP TABLE IF EXISTS `message_user`;

CREATE TABLE `message_user` (
  `message_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `favourite` tinyint(1) NOT NULL,
  PRIMARY KEY (`message_id`,`user_id`),
  KEY `message_user_ibfk_1` (`user_id`),
  CONSTRAINT `message_user_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`),
  CONSTRAINT `message_user_ibfk_2` FOREIGN KEY (`message_id`) REFERENCES `message` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `message_user` */

insert  into `message_user`(`message_id`,`user_id`,`favourite`) values (1,1,0),(1,2,1),(1,3,0),(2,1,0),(2,2,0),(2,3,0),(3,1,0),(4,1,0),(4,3,0);

/*Table structure for table `user` */

DROP TABLE IF EXISTS `user`;

CREATE TABLE `user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) DEFAULT NULL,
  `firstname` varchar(255) DEFAULT NULL,
  `lastname` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

/*Data for the table `user` */

insert  into `user`(`id`,`username`,`password`,`firstname`,`lastname`) values (1,'wolfgang','0fc8175a00321e3b3fd4575130d2094f837a111c','Wolfgang','Lumetsberger'),(2,'max','0899b5c314dd9bf67c06d168702ab317d1000bed','Max','Mustermann'),(3,'scm4','a8af855d47d091f0376664fe588207f334cdad22','scm4','scm4');

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
