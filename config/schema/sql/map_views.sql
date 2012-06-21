-- MySQL dump 10.13  Distrib 5.5.24, for debian-linux-gnu (i686)
--
-- Host: localhost    Database: weatherph
-- ------------------------------------------------------
-- Server version	5.5.24-0ubuntu0.12.04.1

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `map_views`
--

DROP TABLE IF EXISTS `map_views`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `map_views` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `map_view_type_id` int(10) unsigned NOT NULL,
  `name` varchar(255) NOT NULL,
  `x1` varchar(255) NOT NULL,
  `x2` varchar(255) NOT NULL,
  `y1` varchar(255) NOT NULL,
  `y2` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=23 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `map_views`
--

LOCK TABLES `map_views` WRITE;
/*!40000 ALTER TABLE `map_views` DISABLE KEYS */;
INSERT INTO `map_views` VALUES (2,1,'All Philippines','111.32714843750325','135.67285156249676','0.8402895756535752','24.41201768480203'),(3,1,'Luzon','116.16357421875163','128.33642578124838','10.950736511266356','22.582414770293372'),(4,1,'Visayas/Mindanao','119.12957421875164','131.3024257812484','3.6491697643203027','15.627060843031257'),(5,1,'Palawan/Sulu Sea','114.53357421875162','126.70642578124838','2.5464027989479354','14.56078202759215'),(6,2,'NCR','120.84068319433601','121.22108480566402','14.355535175654415','14.723753388788541'),(7,2,'CAR','119.5587155546879','122.60192844531211','15.982453236242902','18.885497695258707'),(8,2,'Ilocos','118.93249555468789','121.97570844531211','15.469563211634433','18.380591993488604'),(9,2,'Cagayan Valley','120.1025395546879','123.1457524453121','15.660065495035672','18.568155237922994'),(10,2,'Central Luzon','119.6246335546879','122.66784644531212','14.160545034976167','17.09091652709819'),(11,2,'Central Luzon','119.6246335546879','122.66784644531212','14.160545034976167','17.09091652709819'),(12,2,'MIMAROPA','116.42211910937583','122.50854489062422','8.211490559840904','14.179186373944514'),(13,2,'Bicol','121.8933105546879','124.93652344531212','11.93722721333602','14.897013809760823'),(14,3,'Western Visayas','121.04187055468792','124.08508344531212','9.221404583621267','12.211180155349105'),(15,3,'Central Visayas','122.1130375546879','125.15625044531211','8.597315763081625','11.593050450213942'),(16,3,'Eastern Visayas','123.3435055546879','126.38671844531208','9.99590064659795','12.977794940835198'),(17,4,'Zamboanga Peninsula','121.1791995546879','124.22241244531212','6.323487807492273','9.337961599764506'),(18,4,'Northern Mindanao','123.16772455468791','126.21093744531211','6.806444144058867','9.817329282268284'),(19,4,'Davao','124.21142555468792','127.25463844531212','5.533977981076581','8.553861750856013'),(20,4,'SOCCSKSARGEN','123.56872555468789','126.61193844531209','5.490236326417025','8.510403237741306'),(21,4,'CARAGA','124.21691855468791','127.26013144531211','7.65110945925397','10.655209990481582'),(22,4,'ARMM','118.93798810937582','125.02441389062422','3.458590714075426','9.503243549563088');
/*!40000 ALTER TABLE `map_views` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `map_view_types`
--

DROP TABLE IF EXISTS `map_view_types`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `map_view_types` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `map_view_types`
--

LOCK TABLES `map_view_types` WRITE;
/*!40000 ALTER TABLE `map_view_types` DISABLE KEYS */;
INSERT INTO `map_view_types` VALUES (1,'Major Areas'),(2,'Luzon'),(3,'Visayas'),(4,'Mindanao');
/*!40000 ALTER TABLE `map_view_types` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2012-06-20 16:07:15