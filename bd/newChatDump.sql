-- MySQL dump 10.13  Distrib 8.0.36, for Win64 (x86_64)
--
-- Host: localhost    Database: housing
-- ------------------------------------------------------
-- Server version	8.0.36

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `announcements`
--

DROP TABLE IF EXISTS `announcements`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `announcements` (
  `id` int NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `importance` enum('low','medium','high') NOT NULL DEFAULT 'low',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `announcements`
--

LOCK TABLES `announcements` WRITE;
/*!40000 ALTER TABLE `announcements` DISABLE KEYS */;
/*!40000 ALTER TABLE `announcements` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `apartments`
--

DROP TABLE IF EXISTS `apartments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `apartments` (
  `id` int NOT NULL AUTO_INCREMENT,
  `buildings_id` int NOT NULL,
  `rooms` smallint unsigned NOT NULL,
  `area` float NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_apartments_buildings_idx` (`buildings_id`),
  CONSTRAINT `fk_apartments_buildings` FOREIGN KEY (`buildings_id`) REFERENCES `buildings` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `apartments`
--

LOCK TABLES `apartments` WRITE;
/*!40000 ALTER TABLE `apartments` DISABLE KEYS */;
INSERT INTO `apartments` VALUES (1,1,3,72.5),(2,1,2,54),(3,2,4,89),(4,2,2,59.5);
/*!40000 ALTER TABLE `apartments` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `apartments_has_residents`
--

DROP TABLE IF EXISTS `apartments_has_residents`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `apartments_has_residents` (
  `apartments_id` int NOT NULL,
  `residents_id` int NOT NULL,
  PRIMARY KEY (`apartments_id`,`residents_id`),
  KEY `fk_apartments_has_residents_residents1_idx` (`residents_id`),
  KEY `fk_apartments_has_residents_apartments1_idx` (`apartments_id`),
  CONSTRAINT `fk_apartments_has_residents_apartments1` FOREIGN KEY (`apartments_id`) REFERENCES `apartments` (`id`),
  CONSTRAINT `fk_apartments_has_residents_residents1` FOREIGN KEY (`residents_id`) REFERENCES `residents` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `apartments_has_residents`
--

LOCK TABLES `apartments_has_residents` WRITE;
/*!40000 ALTER TABLE `apartments_has_residents` DISABLE KEYS */;
INSERT INTO `apartments_has_residents` VALUES (1,1),(4,1),(1,2),(2,3),(3,4),(2,5),(1,8);
/*!40000 ALTER TABLE `apartments_has_residents` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `buildings`
--

DROP TABLE IF EXISTS `buildings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `buildings` (
  `id` int NOT NULL AUTO_INCREMENT,
  `addres` varchar(255) NOT NULL,
  `number` char(15) NOT NULL,
  `floors` tinyint unsigned NOT NULL,
  `aparts` smallint unsigned NOT NULL,
  `building_year` date NOT NULL,
  `building_enters` smallint unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `buildings`
--

LOCK TABLES `buildings` WRITE;
/*!40000 ALTER TABLE `buildings` DISABLE KEYS */;
INSERT INTO `buildings` VALUES (1,'ул. Ленина, д. 10','B1',5,10,'2000-05-01',1),(2,'ул. Мира, д. 20','B2',8,16,'1995-01-01',2);
/*!40000 ALTER TABLE `buildings` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `car`
--

DROP TABLE IF EXISTS `car`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `car` (
  `id` int NOT NULL AUTO_INCREMENT,
  `car_plate` varchar(9) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `carPlate_UNIQUE` (`car_plate`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `car`
--

LOCK TABLES `car` WRITE;
/*!40000 ALTER TABLE `car` DISABLE KEYS */;
INSERT INTO `car` VALUES (1,'А001АА777'),(2,'В234ВВ178');
/*!40000 ALTER TABLE `car` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `carspots`
--

DROP TABLE IF EXISTS `carspots`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `carspots` (
  `id` int NOT NULL AUTO_INCREMENT,
  `car_id` int NOT NULL,
  `residents_id` int NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_carspots_car1_idx` (`car_id`),
  KEY `fk_carspots_residents1_idx` (`residents_id`),
  CONSTRAINT `fk_carspots_car1` FOREIGN KEY (`car_id`) REFERENCES `car` (`id`),
  CONSTRAINT `fk_carspots_residents1` FOREIGN KEY (`residents_id`) REFERENCES `residents` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `carspots`
--

LOCK TABLES `carspots` WRITE;
/*!40000 ALTER TABLE `carspots` DISABLE KEYS */;
INSERT INTO `carspots` VALUES (1,1,1,'Подземная парковка'),(2,2,2,'Открытая стоянка');
/*!40000 ALTER TABLE `carspots` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `logs`
--

DROP TABLE IF EXISTS `logs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `logs` (
  `id` int NOT NULL AUTO_INCREMENT,
  `type` varchar(100) NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `description` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `logs`
--

LOCK TABLES `logs` WRITE;
/*!40000 ALTER TABLE `logs` DISABLE KEYS */;
INSERT INTO `logs` VALUES (1,'Добавление','2024-04-28 14:10:04','Житель ФИО добавлен в квартиру с ID 1');
/*!40000 ALTER TABLE `logs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `messages`
--

DROP TABLE IF EXISTS `messages`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `messages` (
  `id` int NOT NULL AUTO_INCREMENT,
  `sender_id` int DEFAULT NULL,
  `receiver_id` int DEFAULT NULL,
  `message` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `is_read` tinyint(1) DEFAULT '0',
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `sender_id` (`sender_id`),
  KEY `receiver_id` (`receiver_id`),
  CONSTRAINT `messages_ibfk_1` FOREIGN KEY (`sender_id`) REFERENCES `residents` (`id`),
  CONSTRAINT `messages_ibfk_2` FOREIGN KEY (`receiver_id`) REFERENCES `residents` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `messages`
--

LOCK TABLES `messages` WRITE;
/*!40000 ALTER TABLE `messages` DISABLE KEYS */;
/*!40000 ALTER TABLE `messages` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `payments`
--

DROP TABLE IF EXISTS `payments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `payments` (
  `id` int NOT NULL AUTO_INCREMENT,
  `apartments_id` int NOT NULL,
  `date` datetime NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `type` enum('свет','газ','кап.ремонт') NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_payments_apartments1_idx` (`apartments_id`),
  CONSTRAINT `fk_payments_apartments1` FOREIGN KEY (`apartments_id`) REFERENCES `apartments` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `payments`
--

LOCK TABLES `payments` WRITE;
/*!40000 ALTER TABLE `payments` DISABLE KEYS */;
INSERT INTO `payments` VALUES (1,1,'2024-04-01 10:00:00',5000.00,'свет'),(2,2,'2024-04-01 11:00:00',3000.00,'газ'),(3,3,'2024-04-01 12:00:00',2500.00,'кап.ремонт');
/*!40000 ALTER TABLE `payments` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `payments_has_residents`
--

DROP TABLE IF EXISTS `payments_has_residents`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `payments_has_residents` (
  `payments_id` int NOT NULL,
  `residents_id` int NOT NULL,
  PRIMARY KEY (`payments_id`,`residents_id`),
  KEY `fk_payments_has_residents_residents1_idx` (`residents_id`),
  KEY `fk_payments_has_residents_payments1_idx` (`payments_id`),
  CONSTRAINT `fk_payments_has_residents_payments1` FOREIGN KEY (`payments_id`) REFERENCES `payments` (`id`),
  CONSTRAINT `fk_payments_has_residents_residents1` FOREIGN KEY (`residents_id`) REFERENCES `residents` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `payments_has_residents`
--

LOCK TABLES `payments_has_residents` WRITE;
/*!40000 ALTER TABLE `payments_has_residents` DISABLE KEYS */;
/*!40000 ALTER TABLE `payments_has_residents` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `residents`
--

DROP TABLE IF EXISTS `residents`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `residents` (
  `id` int NOT NULL AUTO_INCREMENT,
  `full_name` varchar(255) NOT NULL,
  `birth_date` date NOT NULL,
  `registration_date` date NOT NULL,
  `phone_number` varchar(20) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `residents`
--

LOCK TABLES `residents` WRITE;
/*!40000 ALTER TABLE `residents` DISABLE KEYS */;
INSERT INTO `residents` VALUES (1,'Иванов Иван Иванович','1985-03-15','2020-01-10','+71234567890'),(2,'Петров Петр Петрович','1990-07-22','2020-02-20','+70987654321'),(3,'Сидорова Мария Ивановна','1995-11-30','2021-03-15','+70876543210'),(4,'Иванова Анна Петровна','1975-02-05','2020-05-05','+70765432109'),(5,'Александров Александр Александрович','1999-09-09','1999-09-09','+89992922'),(6,'Строев Максим Александрович','1999-09-09','1999-09-09','+89992929'),(7,'Сачейкин Илья Александрович','2024-04-28','2024-04-28','+79106482323'),(8,'Соловьева Виктория Романовка','2024-04-28','2024-04-28','+79106548723'),(9,'admin','2024-04-28','2024-04-28','0000000000'),(10,'Тестов Тест Тестович','2024-04-28','2024-04-28','+70765432110');
/*!40000 ALTER TABLE `residents` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Temporary view structure for view `residents_apartments_view`
--

DROP TABLE IF EXISTS `residents_apartments_view`;
/*!50001 DROP VIEW IF EXISTS `residents_apartments_view`*/;
SET @saved_cs_client     = @@character_set_client;
/*!50503 SET character_set_client = utf8mb4 */;
/*!50001 CREATE VIEW `residents_apartments_view` AS SELECT 
 1 AS `resident_id`,
 1 AS `full_name`,
 1 AS `иirth_date`,
 1 AS `registration_date`,
 1 AS `phone_number`,
 1 AS `apartment_id`,
 1 AS `rooms`,
 1 AS `area`,
 1 AS `building_id`,
 1 AS `addres`,
 1 AS `building_number`,
 1 AS `floors`,
 1 AS `aparts`,
 1 AS `building_year`,
 1 AS `building_enters`*/;
SET character_set_client = @saved_cs_client;

--
-- Table structure for table `residents_has_car`
--

DROP TABLE IF EXISTS `residents_has_car`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `residents_has_car` (
  `residents_id` int NOT NULL,
  `car_id` int NOT NULL,
  PRIMARY KEY (`residents_id`,`car_id`),
  KEY `fk_residents_has_car_car1_idx` (`car_id`),
  KEY `fk_residents_has_car_residents1_idx` (`residents_id`),
  CONSTRAINT `fk_residents_has_car_car1` FOREIGN KEY (`car_id`) REFERENCES `car` (`id`),
  CONSTRAINT `fk_residents_has_car_residents1` FOREIGN KEY (`residents_id`) REFERENCES `residents` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `residents_has_car`
--

LOCK TABLES `residents_has_car` WRITE;
/*!40000 ALTER TABLE `residents_has_car` DISABLE KEYS */;
/*!40000 ALTER TABLE `residents_has_car` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `storerooms`
--

DROP TABLE IF EXISTS `storerooms`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `storerooms` (
  `id` int NOT NULL AUTO_INCREMENT,
  `apartments_id` int NOT NULL,
  `area` float NOT NULL,
  `number` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_storerooms_apartments1_idx` (`apartments_id`),
  CONSTRAINT `fk_storerooms_apartments1` FOREIGN KEY (`apartments_id`) REFERENCES `apartments` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `storerooms`
--

LOCK TABLES `storerooms` WRITE;
/*!40000 ALTER TABLE `storerooms` DISABLE KEYS */;
/*!40000 ALTER TABLE `storerooms` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Final view structure for view `residents_apartments_view`
--

/*!50001 DROP VIEW IF EXISTS `residents_apartments_view`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8mb4 */;
/*!50001 SET character_set_results     = utf8mb4 */;
/*!50001 SET collation_connection      = utf8mb4_0900_ai_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `residents_apartments_view` AS select `r`.`id` AS `resident_id`,`r`.`full_name` AS `full_name`,`r`.`birth_date` AS `иirth_date`,`r`.`registration_date` AS `registration_date`,`r`.`phone_number` AS `phone_number`,`a`.`id` AS `apartment_id`,`a`.`rooms` AS `rooms`,`a`.`area` AS `area`,`b`.`id` AS `building_id`,`b`.`addres` AS `addres`,`b`.`number` AS `building_number`,`b`.`floors` AS `floors`,`b`.`aparts` AS `aparts`,`b`.`building_year` AS `building_year`,`b`.`building_enters` AS `building_enters` from (((`residents` `r` join `apartments_has_residents` `ahr` on((`r`.`id` = `ahr`.`residents_id`))) join `apartments` `a` on((`ahr`.`apartments_id` = `a`.`id`))) join `buildings` `b` on((`a`.`buildings_id` = `b`.`id`))) */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2024-05-31 12:02:17
