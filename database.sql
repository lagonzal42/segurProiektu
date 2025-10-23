/*M!999999\- enable the sandbox mode */ 
-- MariaDB dump 10.19  Distrib 10.11.13-MariaDB, for debian-linux-gnu (x86_64)
--
-- Host: localhost    Database: SegurProiektua
-- ------------------------------------------------------
-- Server version	10.11.13-MariaDB-0ubuntu0.24.04.1

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `babarrunak`
--

DROP TABLE IF EXISTS `babarrunak`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `babarrunak` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `Izena` varchar(30) NOT NULL,
  `Jatorria` varchar(30) DEFAULT NULL,
  `Kolorea` varchar(20) DEFAULT NULL,
  `Egozketa_denb_min` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `babarrunak`
--

LOCK TABLES `babarrunak` WRITE;
/*!40000 ALTER TABLE `babarrunak` DISABLE KEYS */;
/*!40000 ALTER TABLE `babarrunak` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `erabiltzaileak`
--

DROP TABLE IF EXISTS `erabiltzaileak`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `erabiltzaileak` (
  `Izen_Abizen` varchar(50) NOT NULL,
  `NAN` char(9) NOT NULL,
  `Telefonoa` int(11) DEFAULT NULL,
  `Jaio_Data` date DEFAULT NULL,
  `Email` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`NAN`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `erabiltzaileak`
--

LOCK TABLES `erabiltzaileak` WRITE;
/*!40000 ALTER TABLE `erabiltzaileak` DISABLE KEYS */;
/*!40000 ALTER TABLE `erabiltzaileak` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `usuarios`
--

DROP TABLE IF EXISTS `usuarios`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `nombre` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `usuarios`
--

LOCK TABLES `usuarios` WRITE;
/*!40000 ALTER TABLE `usuarios` DISABLE KEYS */;
INSERT INTO `usuarios` VALUES
(1,'mikel'),
(2,'aitor');
/*!40000 ALTER TABLE `usuarios` ENABLE KEYS */;

INSERT INTO `babarrunak` (`id`, `Izena`, `Jatorria`, `Kolorea`, `Egozketa_denb_min`) VALUES
  (1, 'Tolosa', 'Euskal Herria', 'beltza', 90),
  (2, 'Verdina', 'Asturias', 'berdea', 70),
  (3, 'Pintoa', 'Castilla y León', 'marroixka', 75),
  (4, 'Canela', 'La Rioja', 'horia', 80),
  (5, 'Carilla', 'Andalucía', 'zuria', 60),
  (6, 'Fabada', 'Asturias', 'zuria', 90),
  (7, 'Negra de Burgos', 'Castilla y León', 'beltza', 85),
  (8, 'Alubia de Gernika', 'Bizkaia', 'beltzarana', 80),
  (9, 'Azuki', 'Japon', 'gorria', 45),
  (10, 'Manteca', 'Navarra', 'horia', 65),
  (11, 'Blanca riñón', 'Cantabria', 'zuria', 70),
  (12, 'Plancheta', 'Catalunya', 'zuria', 60),
  (13, 'Alubia morada', 'Galicia', 'morea', 75),
  (14, 'Cannellini', 'Italia', 'zuria', 65),
  (15, 'Frijol negro', 'México', 'beltza', 55);


/*CREATE TABLE `babarrunak` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `Izena` varchar(30) NOT NULL,
  `Jatorria` varchar(30) DEFAULT NULL,
  `Kolorea` varchar(20) DEFAULT NULL,
  `Egozketa_denb_min` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;*/
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2025-10-16 10:22:04
