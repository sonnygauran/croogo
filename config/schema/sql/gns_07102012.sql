-- MySQL dump 10.13  Distrib 5.5.9, for osx10.6 (i386)
--
-- Host: localhost    Database: gns
-- ------------------------------------------------------
-- Server version	5.5.9

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
-- Table structure for table `fips_codes`
--

DROP TABLE IF EXISTS `fips_codes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `fips_codes` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `cc1` char(2) NOT NULL,
  `adm1` varchar(2) NOT NULL,
  `name` varchar(255) NOT NULL,
  `type` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=204 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `fips_codes`
--

LOCK TABLES `fips_codes` WRITE;
/*!40000 ALTER TABLE `fips_codes` DISABLE KEYS */;
INSERT INTO `fips_codes` VALUES (1,'RP','01','Abra',1),(2,'RP','02','Agusan del Norte',1),(3,'RP','03','Agusan del Sur',1),(4,'RP','04','Aklan',1),(5,'RP','H4','Alaminos',2),(6,'RP','05','Albay',1),(7,'RP','A1','Angeles',2),(8,'RP','H5','Antipolo',2),(9,'RP','06','Antique',1),(10,'RP','H6','Apayao',1),(11,'RP','G8','Aurora',1),(12,'RP','A2','Bacolod',2),(13,'RP','A4','Baguio',2),(14,'RP','A5','Bais',2),(15,'RP','H7','Balanga',2),(16,'RP','22','Basilan',1),(17,'RP','A6','Basilan',2),(18,'RP','07','Bataan',1),(19,'RP','08','Batanes',1),(20,'RP','09','Batangas',1),(21,'RP','A7','Batangas',2),(22,'RP','H8','Bayawan',2),(23,'RP','10','Benguet',1),(24,'RP','H9','Biliran',1),(25,'RP','I1','Bislig',2),(26,'RP','11','Bohol',1),(27,'RP','12','Bukidnon',1),(28,'RP','13','Bulacan',1),(29,'RP','A8','Butuan',2),(30,'RP','A9','Cabanatuan',2),(31,'RP','B1','Cadiz',2),(32,'RP','14','Cagayan',1),(33,'RP','B2','Cagayan de Oro',2),(34,'RP','I2','Calamba',2),(35,'RP','I3','Calapan',2),(36,'RP','B3','Calbayog',2),(37,'RP','B4','Caloocan',2),(38,'RP','15','Camarines Norte',1),(39,'RP','16','Camarines Sur',1),(40,'RP','17','Camiguin',1),(41,'RP','I4','Candon',2),(42,'RP','B5','Canlaon',2),(43,'RP','18','Capiz',1),(44,'RP','19','Catanduanes',1),(45,'RP','I5','Cauayan',2),(46,'RP','20','Cavite',1),(47,'RP','B6','Cavite',2),(48,'RP','21','Cebu',1),(49,'RP','B7','Cebu',2),(50,'RP','I6','Compostela Valley',1),(51,'RP','B8','Cotabato',2),(52,'RP','57','Cotabato',1),(53,'RP','B9','Dagupan',2),(54,'RP','C1','Danao',2),(55,'RP','C2','Dapitan',2),(56,'RP','C3','Davao',2),(57,'RP','I7','Davao del Norte',1),(58,'RP','25','Davao del Sur',1),(59,'RP','26','Davao Oriental',1),(60,'RP','I8','Digos',2),(61,'RP','I9','Dinagat Islands',1),(62,'RP','C4','Dipolog',2),(63,'RP','C5','Dumaguete',2),(64,'RP','23','Eastern Samar',1),(65,'RP','J1','Escalante',2),(66,'RP','J2','Gapan',2),(67,'RP','C6','General Santos',2),(68,'RP','C7','Gingoog',2),(69,'RP','J3','Guimaras',1),(70,'RP','J4','Himamaylan',2),(71,'RP','27','Ifugao',1),(72,'RP','C8','Iligan',2),(73,'RP','28','Ilocos Norte',1),(74,'RP','29','Ilocos Sur',1),(75,'RP','30','Iloilo',1),(76,'RP','C9','Iloilo',2),(77,'RP','D1','Iriga',2),(78,'RP','31','Isabela',1),(79,'RP','J5','Isabela',2),(80,'RP','J6','Kabankalan',2),(81,'RP','J7','Kalinga',1),(82,'RP','J8','Kidapawan',2),(83,'RP','J9','Koronadal',2),(84,'RP','D2','La Carlota',2),(85,'RP','33','Laguna',1),(86,'RP','34','Lanao del Norte',1),(87,'RP','35','Lanao del Sur',1),(88,'RP','D3','Laoag',2),(89,'RP','D4','Lapu-Lapu',2),(90,'RP','K1','Las PiÃ±as',2),(91,'RP','36','La Union',1),(92,'RP','D5','Legaspi',2),(93,'RP','37','Leyte',1),(94,'RP','K2','Ligao',2),(95,'RP','D6','Lipa',2),(96,'RP','D7','Lucena',2),(97,'RP','K3','Maasin',2),(98,'RP','56','Maguindanao',1),(99,'RP','K4','Makati',2),(100,'RP','K5','Malabon',2),(101,'RP','K6','Malaybalay',2),(102,'RP','K7','Malolos',2),(103,'RP','K8','Mandaluyong',2),(104,'RP','D8','Mandaue',2),(105,'RP','D9','Manila',2),(106,'RP','E1','Marawi',2),(107,'RP','K9','Marikina',2),(108,'RP','38','Marinduque',1),(109,'RP','39','Masbate',1),(110,'RP','L1','Masbate',2),(111,'RP','L2','Meycauayan',2),(112,'RP','40','Mindoro Occidental',1),(113,'RP','41','Mindoro Oriental',1),(114,'RP','42','Misamis Occidental',1),(115,'RP','43','Misamis Oriental',1),(116,'RP','44','Mountain',1),(117,'RP','L3','MuÃ±oz',2),(118,'RP','L4','Muntinlupa',2),(119,'RP','E2','Naga',2),(120,'RP','L5','Navotas',2),(121,'RP','H3','Negros Occidental',1),(122,'RP','46','Negros Oriental',1),(123,'RP','67','Northern Samar',1),(124,'RP','47','Nueva Ecija',1),(125,'RP','48','Nueva Vizcaya',1),(126,'RP','40','Occidental Mindoro',1),(127,'RP','E3','Olongapo',2),(128,'RP','41','Oriental Mindoro',1),(129,'RP','E4','Ormoc',2),(130,'RP','E5','Oroquieta',2),(131,'RP','E6','Ozamis',2),(132,'RP','E7','Pagadian',2),(133,'RP','49','Palawan',1),(134,'RP','E8','Palayan',2),(135,'RP','50','Pampanga',1),(136,'RP','L6','Panabo',2),(137,'RP','51','Pangasinan',1),(138,'RP','L7','ParaÃ±aque',2),(139,'RP','E9','Pasay',2),(140,'RP','L8','Pasig',2),(141,'RP','L9','Passi',2),(142,'RP','F1','Puerto Princesa',2),(143,'RP','H2','Quezon',1),(144,'RP','F2','Quezon',2),(145,'RP','68','Quirino',1),(146,'RP','53','Rizal',1),(147,'RP','54','Romblon',1),(148,'RP','F3','Roxas',2),(149,'RP','M1','Sagay',2),(150,'RP','M2','Samal',2),(151,'RP','55','Samar',1),(152,'RP','F4','San Carlos (Negros Occidental)',2),(153,'RP','F5','San Carlos (Pangasinan)',2),(154,'RP','M3','San Fernando (La Union)',2),(155,'RP','M4','San Fernando (Pampanga)',2),(156,'RP','F6','San Jose',2),(157,'RP','M5','San Jose del Monte',2),(158,'RP','M6','San Juan',2),(159,'RP','F7','San Pablo',2),(160,'RP','M7','Santa Rosa',2),(161,'RP','M8','Santiago',2),(162,'RP','M9','Sarangani',2),(163,'RP','F8','Silay',2),(164,'RP','N1','Sipalay',2),(165,'RP','69','Siquijor',1),(166,'RP','58','Sorsogon',1),(167,'RP','N2','Sorsogon',2),(168,'RP','70','South Cotabato',1),(169,'RP','59','Southern Leyte',1),(170,'RP','71','Sultan Kudarat',1),(171,'RP','60','Sulu',1),(172,'RP','F9','Surigao',2),(173,'RP','61','Surigao del Norte',1),(174,'RP','N3','Surigao del Norte',1),(175,'RP','G1','Tacloban charter RPN4- Tabaco',2),(176,'RP','62','Surigao del Sur',1),(177,'RP','N5','Tacurong',2),(178,'RP','G2','Tagaytay',2),(179,'RP','G3','Tagbilaran',2),(180,'RP','N6','Taguig',2),(181,'RP','N7','Tagum',2),(182,'RP','N8','Talisay (Cebu)',2),(183,'RP','N9','Talisay (Negros Occidental)',2),(184,'RP','O1','Tanauan',2),(185,'RP','O2','Tangub',2),(186,'RP','G4','Tanjay',2),(187,'RP','O3','Tarlac',1),(188,'RP','63','Tarlac',2),(189,'RP','O4','Tawi-Tawi',1),(190,'RP','72','Toledo',2),(191,'RP','G5','Trece Martires',2),(192,'RP','G6','Tuguegarao',2),(193,'RP','O5','Urdaneta',2),(194,'RP','O6','Valencia',2),(195,'RP','O7','Valenzuela',2),(196,'RP','O8','Victorias',2),(197,'RP','O9','Vigan',2),(198,'RP','P1','Zambales',1),(199,'RP','64','Zamboanaga del Sur',1),(200,'RP','P2','Zamboanga',2),(201,'RP','G7','Zamboanga del Norte',1),(202,'RP','65','Zamboanga del Sur',1),(203,'RP','66','Zamboanga Sibugay',1);
/*!40000 ALTER TABLE `fips_codes` ENABLE KEYS */;
UNLOCK TABLES;


-- Table structure for table `provinces`
--

DROP TABLE IF EXISTS `provinces`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `provinces` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `region_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=98 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `provinces`
--

LOCK TABLES `provinces` WRITE;
/*!40000 ALTER TABLE `provinces` DISABLE KEYS */;
INSERT INTO `provinces` VALUES (1,'Basilan',1),(2,'Lanao del Sur',1),(3,'Maguindanao',1),(4,'Sulu',1),(5,'Tawi-Tawi',1),(6,'Abra',2),(7,'Apayao',2),(8,'Benguet',2),(9,'Ifugao',2),(10,'Kalinga',2),(11,'Mountain Province',2),(12,'Ilocos Norte',4),(13,'Ilocos Sur',4),(14,'La Union',4),(15,'Pangasinan',4),(16,'Batanes',5),(17,'Cagayan',5),(18,'Isabela',5),(19,'Nueva Vizcaya',5),(20,'Quirino',5),(21,'Aurora',6),(22,'Bataan',6),(23,'Bulacan',6),(24,'Nueva Ecija',6),(25,'Pampanga',6),(26,'Tarlac',6),(27,'Zambales',6),(28,'Batangas',7),(29,'Cavite',7),(30,'Laguna',7),(31,'Quezon',7),(32,'Rizal',7),(33,'Marinduque',8),(34,'Occidental Mindoro',8),(35,'Oriental Mindoro',8),(36,'Palawan',8),(37,'Romblon',8),(38,'Albay',9),(39,'Camarines Norte',9),(40,'Camarines Sur',9),(41,'Catanduanes',9),(42,'Masbate',9),(43,'Sorsogon',9),(44,'Aklan',10),(45,'Antique',10),(46,'Capiz',10),(47,'Guimaras',10),(48,'Iloilo',10),(49,'Negros Occidental',10),(50,'Bohol',11),(51,'Cebu',11),(52,'Negros Oriental',11),(53,'Siquijor',11),(54,'Biliran',12),(55,'Eastern Samar',12),(56,'Leyte',12),(57,'Northern Samar',12),(58,'Samar',12),(59,'Southern Leyte',12),(60,'Zambuanga del Norte',13),(61,'Zambuanga del Sur',13),(62,'Zambuanga Sibugay',13),(63,'Bukidnon',14),(64,'Camiguin',14),(65,'Lanao del Norte',14),(66,'Misamis Occidental',14),(67,'Misamis Oriental',14),(68,'Compostela Valley',15),(69,'Davao del Norte',15),(70,'Davao del Sur',15),(71,'Davao Oriental',15),(72,'North Cotabato',16),(73,'Sarangani',16),(74,'South Cotabato',16),(75,'Sultan Kudarat',16),(76,'Agusan del Norte',17),(77,'Agusan del Sur',17),(78,'Surigao del Norte',17),(79,'Surigao del Sur',17),(80,'Caloocan',3),(81,'Las Pinas',3),(82,'Makati',3),(83,'Malabon',3),(84,'Mandaluyong',3),(85,'Manila',3),(86,'Marikina',3),(87,'Muntinlupa',3),(88,'Navotas',3),(89,'Paranaque',3),(90,'Pasig',3),(91,'Pateros',3),(92,'Quezon',3),(93,'San Juan',3),(94,'Taguig',3),(95,'Valenzuela',3),(96,'Las PiÃ±as',3),(97,'ParaÃ±aque',3);
/*!40000 ALTER TABLE `provinces` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `regions`
--

DROP TABLE IF EXISTS `regions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `regions` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `code` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `regions`
--

LOCK TABLES `regions` WRITE;
/*!40000 ALTER TABLE `regions` DISABLE KEYS */;
INSERT INTO `regions` VALUES (1,'Autonomous Region in Muslim Mindanao','ARMM'),(2,'Cordillera Administrative Region','CAR'),(3,'National Capital Region','NCR'),(4,'Ilocos Region','Region I'),(5,'Cagayan Valley','Region II'),(6,'Central Luzon','Region III'),(7,'CALABARZON','Region IV-A'),(8,'MIMAROPA','Region IV-B'),(9,'Bicol Region','Region V'),(10,'Western Visayas','Region VI'),(11,'Central Visayas','Region VII'),(12,'Eastern Visayas','Region VIII'),(13,'Zamboanga Peninsula','Region IX'),(14,'Northern Mindanao','Region X'),(15,'Davao Region','Region XI'),(16,'SOCCSKSARGEN','Region XII'),(17,'Caraga','Region XIII');
/*!40000 ALTER TABLE `regions` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2012-07-10 11:20:43
