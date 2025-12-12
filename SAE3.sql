-- Adminer 4.8.1 MySQL 10.11.6-MariaDB-0+deb12u1 dump

SET NAMES utf8;
SET time_zone = '+00:00';
SET foreign_key_checks = 0;
SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO';

SET NAMES utf8mb4;

DROP DATABASE IF EXISTS `SAE3`;
CREATE DATABASE `SAE3` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci */;
USE `SAE3`;

CREATE TABLE `garage` (
  `id_garage` int(11) NOT NULL AUTO_INCREMENT,
  `nom` varchar(200) DEFAULT NULL,
  `mail` varchar(200) DEFAULT NULL,
  PRIMARY KEY (`id_garage`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `garage` (`id_garage`, `nom`, `mail`) VALUES
(1,	'Garage Central',	'contact@central.com'),
(2,	'Garage Ouest',	'ouest@garage.com');

CREATE TABLE `intervention` (
  `id_intervention` int(11) NOT NULL AUTO_INCREMENT,
  `date` date DEFAULT NULL,
  `cout` decimal(10,2) DEFAULT NULL,
  `km_voiture` bigint(20) DEFAULT NULL,
  `id_garage` int(11) DEFAULT NULL,
  `numero_vin` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id_intervention`),
  KEY `id_garage` (`id_garage`),
  KEY `numero_vin` (`numero_vin`),
  CONSTRAINT `intervention_ibfk_1` FOREIGN KEY (`id_garage`) REFERENCES `garage` (`id_garage`),
  CONSTRAINT `intervention_ibfk_2` FOREIGN KEY (`numero_vin`) REFERENCES `voiture` (`numero_vin`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `intervention` (`id_intervention`, `date`, `cout`, `km_voiture`, `id_garage`, `numero_vin`) VALUES
(1,	'2025-01-15',	89.50,	45000,	1,	'VIN0001'),
(2,	'2025-03-20',	250.00,	12000,	2,	'VIN0002'),
(3,	'2025-06-01',	50.00,	45500,	NULL,	'VIN0001');

CREATE TABLE `intervention_operation_piece` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_intervention` int(11) DEFAULT NULL,
  `id_operation` int(11) DEFAULT NULL,
  `reference_piece` varchar(100) DEFAULT NULL,
  `quantite_utilisee` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `id_intervention` (`id_intervention`),
  KEY `id_operation` (`id_operation`),
  KEY `reference_piece` (`reference_piece`),
  CONSTRAINT `intervention_operation_piece_ibfk_1` FOREIGN KEY (`id_intervention`) REFERENCES `intervention` (`id_intervention`),
  CONSTRAINT `intervention_operation_piece_ibfk_2` FOREIGN KEY (`id_operation`) REFERENCES `operations` (`id_operation`),
  CONSTRAINT `intervention_operation_piece_ibfk_3` FOREIGN KEY (`reference_piece`) REFERENCES `pieces` (`reference_piece`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `intervention_operation_piece` (`id`, `id_intervention`, `id_operation`, `reference_piece`, `quantite_utilisee`) VALUES
(1,	1,	1,	'P-001',	1),
(2,	2,	2,	'P-002',	4),
(3,	2,	3,	'P-003',	4),
(4,	3,	1,	NULL,	0);

CREATE TABLE `marque` (
  `id_marque` int(11) NOT NULL AUTO_INCREMENT,
  `nom` varchar(100) DEFAULT NULL,
  `origine` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id_marque`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `marque` (`id_marque`, `nom`, `origine`) VALUES
(1,	'Peugeot',	'France'),
(2,	'Toyota',	'Japon');

CREATE TABLE `modele` (
  `id_modele` int(11) NOT NULL AUTO_INCREMENT,
  `designation` varchar(200) DEFAULT NULL,
  `generation` varchar(100) DEFAULT NULL,
  `id_marque` int(11) DEFAULT NULL,
  PRIMARY KEY (`id_modele`),
  KEY `id_marque` (`id_marque`),
  CONSTRAINT `modele_ibfk_1` FOREIGN KEY (`id_marque`) REFERENCES `marque` (`id_marque`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `modele` (`id_modele`, `designation`, `generation`, `id_marque`) VALUES
(1,	'208',	'III',	1),
(2,	'Corolla',	'E210',	2);

CREATE TABLE `operations` (
  `id_operation` int(11) NOT NULL AUTO_INCREMENT,
  `nature` varchar(200) DEFAULT NULL,
  PRIMARY KEY (`id_operation`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `operations` (`id_operation`, `nature`) VALUES
(1,	'Vidange huile'),
(2,	'Remplacement plaquettes freins'),
(3,	'Remplacement bougies');

CREATE TABLE `pieces` (
  `reference_piece` varchar(100) NOT NULL,
  `nom_piece` varchar(200) DEFAULT NULL,
  PRIMARY KEY (`reference_piece`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `pieces` (`reference_piece`, `nom_piece`) VALUES
('P-001',	'Filtre à huile'),
('P-002',	'Plaquettes de frein'),
('P-003',	'Bougie');

CREATE TABLE `stocker` (
  `id_garage` int(11) NOT NULL,
  `reference_piece` varchar(100) NOT NULL,
  `quantite` int(11) DEFAULT NULL,
  PRIMARY KEY (`id_garage`,`reference_piece`),
  KEY `reference_piece` (`reference_piece`),
  CONSTRAINT `stocker_ibfk_1` FOREIGN KEY (`id_garage`) REFERENCES `garage` (`id_garage`),
  CONSTRAINT `stocker_ibfk_2` FOREIGN KEY (`reference_piece`) REFERENCES `pieces` (`reference_piece`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `stocker` (`id_garage`, `reference_piece`, `quantite`) VALUES
(1,	'P-001',	10),
(1,	'P-002',	5),
(2,	'P-002',	20),
(2,	'P-003',	15);

CREATE TABLE `utilisateur` (
  `id_user` int(11) NOT NULL AUTO_INCREMENT,
  `nom` varchar(100) DEFAULT NULL,
  `prenom` varchar(100) DEFAULT NULL,
  `telephone` varchar(30) DEFAULT NULL,
  `mdp` varchar(200) DEFAULT NULL,
  `mail` varchar(200) DEFAULT NULL,
  `type_user` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id_user`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `utilisateur` (`id_user`, `nom`, `prenom`, `telephone`, `mdp`, `mail`, `type_user`) VALUES
(1,	'Dupont',	'Jean',	'0123456789',	'mdp',	'jean@example.com',	'client'),
(2,	'Martin',	'Sophie',	'0222333444',	'mdp',	'sophie@example.com',	'client'),
(3,	'Bertrand',	'Alex',	'099887766',	'mdp',	'alex@example.com',	'garagiste');

CREATE TABLE `voiture` (
  `numero_vin` varchar(50) NOT NULL,
  `annee` int(11) DEFAULT NULL,
  `couleur` varchar(50) DEFAULT NULL,
  `kilometrage` bigint(20) DEFAULT NULL,
  `finition` varchar(100) DEFAULT NULL,
  `energie` varchar(50) DEFAULT NULL,
  `puissance_vin` varchar(100) DEFAULT NULL,
  `mise_en_circulation` date DEFAULT NULL,
  `immatriculation` varchar(50) DEFAULT NULL,
  `puissance_din` varchar(100) DEFAULT NULL,
  `id_modele` int(11) DEFAULT NULL,
  `id_user` int(11) DEFAULT NULL,
  PRIMARY KEY (`numero_vin`),
  KEY `id_modele` (`id_modele`),
  KEY `id_user` (`id_user`),
  CONSTRAINT `voiture_ibfk_1` FOREIGN KEY (`id_modele`) REFERENCES `modele` (`id_modele`),
  CONSTRAINT `voiture_ibfk_2` FOREIGN KEY (`id_user`) REFERENCES `utilisateur` (`id_user`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `voiture` (`numero_vin`, `annee`, `couleur`, `kilometrage`, `finition`, `energie`, `puissance_vin`, `mise_en_circulation`, `immatriculation`, `puissance_din`, `id_modele`, `id_user`) VALUES
('VIN0001',	2018,	'bleu',	45000,	'Allure',	'essence',	'115ch',	'2018-03-10',	'AA-111-AA',	'110ch',	1,	1),
('VIN0002',	2020,	'blanc',	12000,	'Luxe',	'hybride',	'136ch',	'2020-06-20',	'BB-222-BB',	'120ch',	2,	2);

-- 2025-12-11 10:01:23
