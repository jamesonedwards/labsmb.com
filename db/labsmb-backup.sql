/*
SQLyog Ultimate v10.11 
MySQL - 5.5.27-log : Database - labsmb
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

/*Table structure for table `ci_sessions` */

DROP TABLE IF EXISTS `ci_sessions`;

CREATE TABLE `ci_sessions` (
  `session_id` varchar(40) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL DEFAULT '0',
  `ip_address` varchar(16) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL DEFAULT '0',
  `user_agent` varchar(150) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `last_activity` int(10) unsigned NOT NULL DEFAULT '0',
  `user_data` text CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  PRIMARY KEY (`session_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `ci_sessions` */

insert  into `ci_sessions`(`session_id`,`ip_address`,`user_agent`,`last_activity`,`user_data`) values ('298b13a1caf8f0e9dcb3ee77864c553e','198.179.70.98','Mozilla/5.0 (Windows NT 6.1; WOW64; rv:19.0) Gecko/20100101 Firefox/19.0',1364237079,'a:4:{s:9:\"user_data\";s:0:\"\";s:7:\"user_id\";s:1:\"1\";s:8:\"username\";s:5:\"admin\";s:6:\"status\";s:1:\"1\";}'),('3acfbf363591883f50f643caf88102ae','64.115.193.170','Mozilla/5.0 (Macintosh; Intel Mac OS X 10_7_5) AppleWebKit/537.4 (KHTML, like Gecko) Chrome/22.0.1229.94 Safari/537.4',1364237072,'a:4:{s:9:\"user_data\";s:0:\"\";s:7:\"user_id\";s:1:\"1\";s:8:\"username\";s:5:\"admin\";s:6:\"status\";s:1:\"1\";}');

/*Table structure for table `login_attempts` */

DROP TABLE IF EXISTS `login_attempts`;

CREATE TABLE `login_attempts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ip_address` varchar(40) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `login` varchar(50) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

/*Data for the table `login_attempts` */

/*Table structure for table `projects` */

DROP TABLE IF EXISTS `projects`;

CREATE TABLE `projects` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(250) NOT NULL,
  `url_key` varchar(250) NOT NULL,
  `tags` varchar(1000) NOT NULL COMMENT 'A comma-separated list of tags',
  `intro` text NOT NULL,
  `description` text NOT NULL,
  `quote` varchar(1000) DEFAULT NULL,
  `small_image_url` varchar(250) NOT NULL,
  `large_image_url` varchar(250) NOT NULL,
  `video_url` varchar(250) DEFAULT NULL,
  `video_screenshot_url` varchar(250) DEFAULT NULL,
  `flickr_photo_set_id` varchar(100) DEFAULT NULL,
  `resource_links` text,
  `enabled` tinyint(1) NOT NULL DEFAULT '1',
  `sort_order` int(11) DEFAULT NULL,
  `created` datetime NOT NULL,
  `updated` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `idx_name` (`name`),
  UNIQUE KEY `idx_url_name` (`url_key`),
  KEY `idx_enabled` (`enabled`),
  KEY `idx_created` (`created`),
  KEY `idx_updated` (`updated`),
  FULLTEXT KEY `idx_search_main` (`name`,`tags`,`intro`,`description`,`quote`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;

/*Data for the table `projects` */

insert  into `projects`(`id`,`name`,`url_key`,`tags`,`intro`,`description`,`quote`,`small_image_url`,`large_image_url`,`video_url`,`video_screenshot_url`,`flickr_photo_set_id`,`resource_links`,`enabled`,`sort_order`,`created`,`updated`) values (1,'LABSmb<br />Dynamic Brand','dynamic-branding','brand,branding,design,data,logo,visualization','Lately there been a noticeable trend  in what has been coined \'dynamic\' or \'fluid\' branding. Branding, up till now, has generally involved the defining of a brand through an identity system that establishes rules around visual elements of the brand but now the goal is to create a brand that is recognizable through repetitive elements and consistency.','This is something that will remain regardless of how branding evolves. However, there has been a trend of opening up these brands to incorporate a degree of fluidity. This allows logos and brands to incorporate outside elements into their branding while still keeping the familiarity of the brand. The question we had is why is this trend occurring?\n\nFirstly consumers view of brands has been dramatically shifting largely through technology and the influences it has on our behaviors. Choice is being put in the hands of the user for them to relate to, and consume, what they want, when they want to. As a result, brands have to shift their focus and develop trust with their potential customers. By developing smart ways of using the plethora of communication platforms available to them today, they can offer greater benefit to a customer. It\'s no longer good enough to state that you\'re the best in class, you have to openly expose how and prove it to customers upfront.\n\nThis openness is what has been affecting branding because the transparency of a brand creates a conversation with a user. They can see the changes and reactions to what is happening to that organization. Whether the changes are positive or negative the frank exposure of what is happening is generally going to be seen as a positive thing. This trend towards transparency has started to expose what we in LABS would call true dynamic branding, where organizations are allowing their brands to be affected by uncontrolled factors.[column_break]This was the approach we took when developing our own brand where we firstly developed a system to represent the group and the attributes of the team. We then took that brand and allowed it to be influenced by factors outside of our control in order to expose the dynamic nature of the group and that fact that, in a group dedicated to exploration and discovery, every day can be different. The result is a brand that we can use to represent team members and projects in completely individual and unique ways, while still keeping the recognition of the brand.\n\nAs we move forward we will be looking at how far we can push the flexibility and randomness of the inputs affecting our logo system. Exposing it to activity as random as noise levels within our working space, data transfers occurring over wireless access points or even the general mood of the group on any given day could yield intriguing results. There is no doubt that consistency and repetition will always have a place in branding. The degree to which a brand can be truly dynamic will be drastically different from one organization to the next, but it is a trend we will continue to watch and hopefully influence in new and dramatic ways.','','http://staging.labsmb.com/images/projects/1_small_image_url.jpg','http://staging.labsmb.com/images/projects/1_large_image_url.jpg','http://player.vimeo.com/video/60862399','','72157632758062459','http://cc.mcgarrybowen.com/labs/2013/03/an-experiment-in-dynamic-branding/,Code + Construct Post',1,NULL,'2013-03-22 07:29:33','2013-03-25 09:17:03'),(4,'PRISM<br />Digital Publication','prism','digital,publication,tablet,mobile,content','The mcgarrybowen Prism tablet app was our opportunity to define a way for mcgarrybowen news and current events to be shared with employees in a more immersive, dynamic and engaging way.','The insight to develop a tablet app came from the convergence of a couple of ideas. How could we create an experience that had the immediacy of a blog and the engagement of a web site combined with a personalized and focused interactive content experience?\n\nThe answer was literally on the table in front of us. For a deeper relationship with a reader we looked at magazines where the connection is more direct, the writing more crafted, and the presentation is far richer. In order to measure engagement we needed a platform where metrics are built in to determine what content was successful and what could be amplified, hence the tablet. The added benefit of tablet delivery is the ability to make content portable and shareable.[column_break]From a LABS perspective, this was an opportunity to share with the Agency and our Clients the idea that they can create immersive experiences that before was only being offered by major publishers. Their stories could not only be shared in print but now in an interactive digital reading experience.\n\nThis presents multiple opportunities for sharing content in unexpected and delightful ways. ','','http://staging.labsmb.com/images/projects/4_small_image_url.jpg','http://staging.labsmb.com/images/projects/4_large_image_url.jpg','','','72157632758062455','',1,NULL,'2013-03-25 11:44:33','2013-03-25 11:45:59');

/*Table structure for table `tags` */

DROP TABLE IF EXISTS `tags`;

CREATE TABLE `tags` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `text` varchar(250) NOT NULL,
  `created` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `idx_text` (`text`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=latin1;

/*Data for the table `tags` */

insert  into `tags`(`id`,`text`,`created`) values (1,'brand','2013-03-25 11:46:00'),(2,'branding','2013-03-25 11:46:00'),(3,'design','2013-03-25 11:46:00'),(4,'data','2013-03-25 11:46:00'),(5,'logo','2013-03-25 11:46:00'),(6,'visualization','2013-03-25 11:46:00'),(7,'digital','2013-03-25 11:46:00'),(8,'publication','2013-03-25 11:46:00'),(9,'tablet','2013-03-25 11:46:00'),(10,'mobile','2013-03-25 11:46:00'),(11,'content','2013-03-25 11:46:00');

/*Table structure for table `user_autologin` */

DROP TABLE IF EXISTS `user_autologin`;

CREATE TABLE `user_autologin` (
  `key_id` char(32) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `user_id` int(11) NOT NULL DEFAULT '0',
  `user_agent` varchar(150) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `last_ip` varchar(40) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `last_login` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`key_id`,`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `user_autologin` */

/*Table structure for table `user_profiles` */

DROP TABLE IF EXISTS `user_profiles`;

CREATE TABLE `user_profiles` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `country` varchar(20) CHARACTER SET utf8 COLLATE utf8_bin DEFAULT NULL,
  `website` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

/*Data for the table `user_profiles` */

insert  into `user_profiles`(`id`,`user_id`,`country`,`website`) values (1,1,NULL,NULL);

/*Table structure for table `users` */

DROP TABLE IF EXISTS `users`;

CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `password` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `email` varchar(100) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `activated` tinyint(1) NOT NULL DEFAULT '1',
  `banned` tinyint(1) NOT NULL DEFAULT '0',
  `ban_reason` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin DEFAULT NULL,
  `new_password_key` varchar(50) CHARACTER SET utf8 COLLATE utf8_bin DEFAULT NULL,
  `new_password_requested` datetime DEFAULT NULL,
  `new_email` varchar(100) CHARACTER SET utf8 COLLATE utf8_bin DEFAULT NULL,
  `new_email_key` varchar(50) CHARACTER SET utf8 COLLATE utf8_bin DEFAULT NULL,
  `last_ip` varchar(40) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `last_login` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `created` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modified` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

/*Data for the table `users` */

insert  into `users`(`id`,`username`,`password`,`email`,`activated`,`banned`,`ban_reason`,`new_password_key`,`new_password_requested`,`new_email`,`new_email_key`,`last_ip`,`last_login`,`created`,`modified`) values (1,'admin','$P$Bk2nVCSNYSZc.3ulHMfoJRVI/TEmoW/','jameson.edwards@mcgarrybowen.com',1,0,NULL,NULL,NULL,NULL,NULL,'198.179.70.98','2013-03-25 11:44:41','2013-03-20 22:11:52','2013-03-25 18:41:58');

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
