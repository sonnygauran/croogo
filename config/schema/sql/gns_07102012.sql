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
  `region_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=204 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `fips_codes`
--

LOCK TABLES `fips_codes` WRITE;
/*!40000 ALTER TABLE `fips_codes` DISABLE KEYS */;
INSERT INTO `fips_codes` VALUES (1,'RP','01','Abra',1,2),(2,'RP','02','Agusan del Norte',1,17),(3,'RP','03','Agusan del Sur',1,17),(4,'RP','04','Aklan',1,10),(5,'RP','H4','Alaminos',2,4),(6,'RP','05','Albay',1,9),(7,'RP','A1','Angeles',2,6),(8,'RP','H5','Antipolo',2,7),(9,'RP','06','Antique',1,10),(10,'RP','H6','Apayao',1,2),(11,'RP','G8','Aurora',1,6),(12,'RP','A2','Bacolod',2,10),(13,'RP','A4','Baguio',2,2),(14,'RP','A5','Bais',2,11),(15,'RP','H7','Balanga',2,6),(16,'RP','22','Basilan',1,1),(17,'RP','A6','Basilan',2,1),(18,'RP','07','Bataan',1,6),(19,'RP','08','Batanes',1,5),(20,'RP','09','Batangas',1,7),(21,'RP','A7','Batangas',2,7),(22,'RP','H8','Bayawan',2,11),(23,'RP','10','Benguet',1,2),(24,'RP','H9','Biliran',1,12),(25,'RP','I1','Bislig',2,17),(26,'RP','11','Bohol',1,11),(27,'RP','12','Bukidnon',1,14),(28,'RP','13','Bulacan',1,6),(29,'RP','A8','Butuan',2,17),(30,'RP','A9','Cabanatuan',2,6),(31,'RP','B1','Cadiz',2,10),(32,'RP','14','Cagayan',1,5),(33,'RP','B2','Cagayan de Oro',2,14),(34,'RP','I2','Calamba',2,7),(35,'RP','I3','Calapan',2,8),(36,'RP','B3','Calbayog',2,12),(37,'RP','B4','Caloocan',2,3),(38,'RP','15','Camarines Norte',1,9),(39,'RP','16','Camarines Sur',1,9),(40,'RP','17','Camiguin',1,14),(41,'RP','I4','Candon',2,4),(42,'RP','B5','Canlaon',2,11),(43,'RP','18','Capiz',1,10),(44,'RP','19','Catanduanes',1,9),(45,'RP','I5','Cauayan',2,5),(46,'RP','20','Cavite',1,7),(47,'RP','B6','Cavite',2,7),(48,'RP','21','Cebu',1,11),(49,'RP','B7','Cebu',2,11),(50,'RP','I6','Compostela Valley',1,15),(51,'RP','B8','Cotabato',2,16),(52,'RP','57','Cotabato',1,16),(53,'RP','B9','Dagupan',2,4),(54,'RP','C1','Danao',2,11),(55,'RP','C2','Dapitan',2,13),(56,'RP','C3','Davao',2,15),(57,'RP','I7','Davao del Norte',1,15),(58,'RP','25','Davao del Sur',1,15),(59,'RP','26','Davao Oriental',1,15),(60,'RP','I8','Digos',2,13),(61,'RP','I9','Dinagat Islands',1,17),(62,'RP','C4','Dipolog',2,13),(63,'RP','C5','Dumaguete',2,11),(64,'RP','23','Eastern Samar',1,12),(65,'RP','J1','Escalante',2,10),(66,'RP','J2','Gapan',2,6),(67,'RP','C6','General Santos',2,16),(68,'RP','C7','Gingoog',2,14),(69,'RP','J3','Guimaras',1,10),(70,'RP','J4','Himamaylan',2,10),(71,'RP','27','Ifugao',1,2),(72,'RP','C8','Iligan',2,14),(73,'RP','28','Ilocos Norte',1,4),(74,'RP','29','Ilocos Sur',1,4),(75,'RP','30','Iloilo',1,10),(76,'RP','C9','Iloilo',2,10),(77,'RP','D1','Iriga',2,9),(78,'RP','31','Isabela',1,5),(79,'RP','J5','Isabela',2,5),(80,'RP','J6','Kabankalan',2,10),(81,'RP','J7','Kalinga',1,2),(82,'RP','J8','Kidapawan',2,16),(83,'RP','J9','Koronadal',2,16),(84,'RP','D2','La Carlota',2,10),(85,'RP','33','Laguna',1,7),(86,'RP','34','Lanao del Norte',1,14),(87,'RP','35','Lanao del Sur',1,1),(88,'RP','D3','Laoag',2,4),(89,'RP','D4','Lapu-Lapu',2,11),(90,'RP','K1','Las PiÃ±as',2,3),(91,'RP','36','La Union',1,4),(92,'RP','D5','Legaspi',2,9),(93,'RP','37','Leyte',1,12),(94,'RP','K2','Ligao',2,9),(95,'RP','D6','Lipa',2,7),(96,'RP','D7','Lucena',2,10),(97,'RP','K3','Maasin',2,12),(98,'RP','56','Maguindanao',1,1),(99,'RP','K4','Makati',2,3),(100,'RP','K5','Malabon',2,3),(101,'RP','K6','Malaybalay',2,14),(102,'RP','K7','Malolos',2,6),(103,'RP','K8','Mandaluyong',2,3),(104,'RP','D8','Mandaue',2,11),(105,'RP','D9','Manila',2,3),(106,'RP','E1','Marawi',2,1),(107,'RP','K9','Marikina',2,3),(108,'RP','38','Marinduque',1,8),(109,'RP','39','Masbate',1,9),(110,'RP','L1','Masbate',2,9),(111,'RP','L2','Meycauayan',2,6),(112,'RP','40','Mindoro Occidental',1,8),(113,'RP','41','Mindoro Oriental',1,8),(114,'RP','42','Misamis Occidental',1,14),(115,'RP','43','Misamis Oriental',1,14),(116,'RP','44','Mountain',1,2),(117,'RP','L3','Muñoz',2,6),(118,'RP','L4','Muntinlupa',2,3),(119,'RP','E2','Naga',2,9),(120,'RP','L5','Navotas',2,3),(121,'RP','H3','Negros Occidental',1,10),(122,'RP','46','Negros Oriental',1,11),(123,'RP','67','Northern Samar',1,12),(124,'RP','47','Nueva Ecija',1,6),(125,'RP','48','Nueva Vizcaya',1,5),(126,'RP','40','Occidental Mindoro',1,8),(127,'RP','E3','Olongapo',2,6),(128,'RP','41','Oriental Mindoro',1,8),(129,'RP','E4','Ormoc',2,12),(130,'RP','E5','Oroquieta',2,14),(131,'RP','E6','Ozamis',2,14),(132,'RP','E7','Pagadian',2,13),(133,'RP','49','Palawan',1,8),(134,'RP','E8','Palayan',2,6),(135,'RP','50','Pampanga',1,6),(136,'RP','L6','Panabo',2,15),(137,'RP','51','Pangasinan',1,4),(138,'RP','L7','ParaÃ±aque',2,3),(139,'RP','E9','Pasay',2,3),(140,'RP','L8','Pasig',2,3),(141,'RP','L9','Passi',2,10),(142,'RP','F1','Puerto Princesa',2,8),(143,'RP','H2','Quezon',1,7),(144,'RP','F2','Quezon',2,7),(145,'RP','68','Quirino',1,5),(146,'RP','53','Rizal',1,7),(147,'RP','54','Romblon',1,8),(148,'RP','F3','Roxas',2,10),(149,'RP','M1','Sagay',2,10),(150,'RP','M2','Samal',2,15),(151,'RP','55','Samar',1,12),(152,'RP','F4','San Carlos (Negros Occidental)',2,10),(153,'RP','F5','San Carlos (Pangasinan)',2,4),(154,'RP','M3','San Fernando (La Union)',2,4),(155,'RP','M4','San Fernando (Pampanga)',2,6),(156,'RP','F6','San Jose',2,11),(157,'RP','M5','San Jose del Monte',2,6),(158,'RP','M6','San Juan',2,3),(159,'RP','F7','San Pablo',2,7),(160,'RP','M7','Santa Rosa',2,7),(161,'RP','M8','Santiago',2,4),(162,'RP','M9','Sarangani',2,16),(163,'RP','F8','Silay',2,10),(164,'RP','N1','Sipalay',2,10),(165,'RP','69','Siquijor',1,11),(166,'RP','58','Sorsogon',1,9),(167,'RP','N2','Sorsogon',2,9),(168,'RP','70','South Cotabato',1,16),(169,'RP','59','Southern Leyte',1,12),(170,'RP','71','Sultan Kudarat',1,16),(171,'RP','60','Sulu',1,1),(172,'RP','F9','Surigao',2,17),(173,'RP','61','Surigao del Norte',1,17),(174,'RP','N3','Surigao del Norte',1,17),(175,'RP','G1','Tacloban charter RPN4- Tabaco',2,NULL),(176,'RP','62','Surigao del Sur',1,17),(177,'RP','N5','Tacurong',2,16),(178,'RP','G2','Tagaytay',2,7),(179,'RP','G3','Tagbilaran',2,11),(180,'RP','N6','Taguig',2,3),(181,'RP','N7','Tagum',2,15),(182,'RP','N8','Talisay (Cebu)',2,11),(183,'RP','N9','Talisay (Negros Occidental)',2,10),(184,'RP','O1','Tanauan',2,7),(185,'RP','O2','Tangub',2,14),(186,'RP','G4','Tanjay',2,11),(187,'RP','O3','Tarlac',1,6),(188,'RP','63','Tarlac',2,6),(189,'RP','O4','Tawi-Tawi',1,1),(190,'RP','72','Toledo',2,11),(191,'RP','G5','Trece Martires',2,7),(192,'RP','G6','Tuguegarao',2,5),(193,'RP','O5','Urdaneta',2,4),(194,'RP','O6','Valencia',2,14),(195,'RP','O7','Valenzuela',2,3),(196,'RP','O8','Victorias',2,10),(197,'RP','O9','Vigan',2,4),(198,'RP','P1','Zambales',1,6),(199,'RP','64','Zamboanaga del Sur',1,13),(200,'RP','P2','Zamboanga',2,13),(201,'RP','G7','Zamboanga del Norte',1,13),(202,'RP','65','Zamboanga del Sur',1,13),(203,'RP','66','Zamboanga Sibugay',1,13);
/*!40000 ALTER TABLE `fips_codes` ENABLE KEYS */;
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
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=latin1;
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

-- Dump completed on 2012-07-12 10:34:16
