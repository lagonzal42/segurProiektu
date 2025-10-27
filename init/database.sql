-- MariaDB dump 10.19  Distrib 10.11.13-MariaDB, for debian-linux-gnu (x86_64)
-- Host: localhost    Database: database
-- ------------------------------------------------------
-- Server version	10.11.13-MariaDB-0ubuntu0.24.04.1

CREATE DATABASE IF NOT EXISTS `segurproiektua`;
USE `segurproiektua`;


DROP TABLE IF EXISTS `babarrunak`;

CREATE TABLE `babarrunak` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `Izena` varchar(30) NOT NULL,
  `Jatorria` varchar(30) DEFAULT NULL,
  `Kolorea` varchar(20) DEFAULT NULL,
  `Egozketa_denb_min` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
);

DROP TABLE IF EXISTS `erabiltzaileak`;

CREATE TABLE `erabiltzaileak` (
  `Izen_Abizen` varchar(50) NOT NULL,
  `NAN` char(9) NOT NULL,
  `Telefonoa` int(11) DEFAULT NULL,
  `Jaio_Data` date DEFAULT NULL,
  `Email` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`NAN`)
);


--
-- Table structure for table `usuarios`
--

DROP TABLE IF EXISTS `usuarios`;

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `nombre` text NOT NULL,
  PRIMARY KEY (`id`)
);

INSERT INTO `usuarios` VALUES
(1,'mikel'),
(2,'aitor');

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
