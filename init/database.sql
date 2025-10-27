-- MariaDB dump 10.19  Distrib 10.11.13-MariaDB, for debian-linux-gnu (x86_64)
-- Host: localhost    Database: database
-- ------------------------------------------------------
-- Server version	10.11.13-MariaDB-0ubuntu0.24.04.1

CREATE DATABASE IF NOT EXISTS `segurproiektua`
  DEFAULT CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

USE `segurproiektua`;

-- 👇 Asegura que las conexiones usen UTF-8
SET NAMES utf8mb4;
SET CHARACTER SET utf8mb4;


DROP TABLE IF EXISTS `babarrunak`;

CREATE TABLE `babarrunak` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `Izena` varchar(30) NOT NULL,
  `Jatorria` varchar(30) DEFAULT NULL,
  `Kolorea` varchar(20) DEFAULT NULL,
  `Egozketa_denb_min` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `erabiltzaileak`;

CREATE TABLE `erabiltzaileak` (
  `Izen_Abizen` varchar(50) NOT NULL,
  `NAN` char(9) NOT NULL,
  `Telefonoa` int(11) DEFAULT NULL,
  `Jaio_Data` date DEFAULT NULL,
  `Email` varchar(50) DEFAULT NULL,
  `token` varchar(256),
  PRIMARY KEY (`NAN`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



--
-- Table structure for table `usuarios`
--

DROP TABLE IF EXISTS `usuarios`;

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `nombre` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `usuarios` VALUES
(1,'mikel'),
(2,'aitor');

INSERT INTO `babarrunak` (`id`, `Izena`, `Jatorria`, `Kolorea`, `Egozketa_denb_min`) VALUES
  (1, 'Tolosa', 'Euskal Herria', 'beltza', 90),
  (2, 'Verdina', 'Asturias', 'berdea', 70),
  (3, 'Pintoa', 'Gaztela eta Leon', 'marroixka', 75),
  (4, 'Kanela', 'Errioxa', 'horia', 80),
  (5, 'Karilla', 'Andaluzia', 'zuria', 60),
  (6, 'Fabada', 'Asturias', 'zuria', 90),
  (7, 'Burgoseko Beltza', 'Gaztela eta Leon', 'beltza', 85),
  (8, 'Gernikakoa', 'Bizkaia', 'beltzarana', 80),
  (9, 'Azuki', 'Japon', 'gorria', 45),
  (10, 'Manteca', 'Nafarroa', 'horia', 65),
  (11, 'Giltxurrun Zuria', 'Kantabria', 'zuria', 70),
  (12, 'Plantxeta', 'Catalunya', 'zuria', 60),
  (13, 'Galiziar Morea', 'Galizia', 'morea', 75),
  (14, 'Cannellini', 'Italia', 'zuria', 65),
  (15, 'Frijol Beltza', 'Mexico', 'beltza', 55);
