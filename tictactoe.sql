# ************************************************************
# Sequel Pro SQL dump
# Version 4541
#
# http://www.sequelpro.com/
# https://github.com/sequelpro/sequelpro
#
# Host: 127.0.0.1 (MySQL 5.6.35)
# Database: tictactoe
# Generation Time: 2017-04-12 01:43:22 +0000
# ************************************************************


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


# Dump of table game
# ------------------------------------------------------------

DROP TABLE IF EXISTS `game`;

CREATE TABLE `game` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `challenger_id` int(11) NOT NULL,
  `opponent_id` int(11) NOT NULL,
  `challenger_tic` varchar(1) NOT NULL,
  `opponent_tic` varchar(1) NOT NULL,
  `status` varchar(10) DEFAULT NULL,
  `winner_id` int(11) DEFAULT NULL,
  `loser_id` int(11) DEFAULT NULL,
  `whosturn_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `game_challenger_id_fk` (`challenger_id`),
  KEY `game_opponent_id_fk` (`opponent_id`),
  CONSTRAINT `game_challenger_id_fk` FOREIGN KEY (`challenger_id`) REFERENCES `user` (`id`),
  CONSTRAINT `game_opponent_id_fk` FOREIGN KEY (`opponent_id`) REFERENCES `user` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Dump of table move
# ------------------------------------------------------------

DROP TABLE IF EXISTS `move`;

CREATE TABLE `move` (
  `game_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `position` int(11) NOT NULL,
  `user_tic` varchar(1) NOT NULL,
  KEY `move_game_id_fk` (`game_id`),
  KEY `move_user_id_fk` (`user_id`),
  CONSTRAINT `move_game_id_fk` FOREIGN KEY (`game_id`) REFERENCES `game` (`id`),
  CONSTRAINT `move_user_id_fk` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Dump of table user
# ------------------------------------------------------------

DROP TABLE IF EXISTS `user`;

CREATE TABLE `user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;




/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
