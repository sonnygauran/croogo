# ************************************************************
# Sequel Pro SQL dump
# Version 3408
#
# http://www.sequelpro.com/
# http://code.google.com/p/sequel-pro/
#
# Host: localhost (MySQL 5.5.9)
# Database: weatherph
# Generation Time: 2012-07-12 09:28:43 +0000
# ************************************************************


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


# Dump of table readings
# ------------------------------------------------------------

DROP TABLE IF EXISTS `readings`;

CREATE TABLE `readings` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `datum` date DEFAULT NULL,
  `utc` varchar(255) DEFAULT NULL,
  `min` varchar(255) DEFAULT NULL,
  `ort1` varchar(255) DEFAULT NULL,
  `dir` varchar(255) DEFAULT NULL,
  `ff` varchar(255) DEFAULT NULL,
  `tl` varchar(255) DEFAULT NULL,
  `tn` varchar(255) DEFAULT NULL,
  `tx` varchar(255) DEFAULT NULL,
  `td` varchar(255) DEFAULT NULL,
  `rh` varchar(255) DEFAULT NULL,
  `rr1h` varchar(255) DEFAULT NULL,
  `rain6` varchar(255) DEFAULT NULL,
  `g1h` varchar(255) DEFAULT NULL,
  `gl1h` varchar(255) DEFAULT NULL,
  `sy` varchar(255) DEFAULT NULL,
  `sy2` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;




/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
