/*
SQLyog Ultimate v10.11 
MySQL - 5.5.27 : Database - labsmb
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
CREATE DATABASE /*!32312 IF NOT EXISTS*/`labsmb` /*!40100 DEFAULT CHARACTER SET latin1 */;

USE `labsmb`;

/*Data for the table `user_profiles` */

insert  into `user_profiles`(`id`,`user_id`,`country`,`website`) values (1,1,NULL,NULL);

/*Data for the table `users` */

insert  into `users`(`id`,`username`,`password`,`email`,`activated`,`banned`,`ban_reason`,`new_password_key`,`new_password_requested`,`new_email`,`new_email_key`,`last_ip`,`last_login`,`created`,`modified`) values (1,'admin','$P$Bk2nVCSNYSZc.3ulHMfoJRVI/TEmoW/','jameson.edwards@mcgarrybowen.com',1,0,NULL,NULL,NULL,NULL,NULL,'127.0.0.1','2013-03-20 22:13:51','2013-03-20 22:11:52','2013-03-20 17:13:51');

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
