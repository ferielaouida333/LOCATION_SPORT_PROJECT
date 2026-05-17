-- SportLoc Premium Database Export
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

-- Database: `location_sport`
CREATE DATABASE IF NOT EXISTS `location_sport` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
USE `location_sport`;

-- Table structure for table `categorie`
DROP TABLE IF EXISTS `reservation`;
DROP TABLE IF EXISTS `materiel`;
DROP TABLE IF EXISTS `categorie`;
DROP TABLE IF EXISTS `users`;

CREATE TABLE `categorie` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nom` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `nom` (`nom`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `categorie` (`id`, `nom`, `description`) VALUES
(1, 'Vélos', 'Vélos de route, VTT, BMX'),
(2, 'Raquettes', 'Tennis, Badminton, Squash'),
(3, 'Ski', 'Skis, snowboards, équipements hiver');

-- Table structure for table `materiel`
CREATE TABLE `materiel` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nom` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `prix_jour` decimal(8,2) NOT NULL,
  `photo` varchar(255) DEFAULT 'default.jpg',
  `disponible` tinyint(1) DEFAULT 1,
  `categorie_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `categorie_id` (`categorie_id`),
  CONSTRAINT `materiel_ibfk_1` FOREIGN KEY (`categorie_id`) REFERENCES `categorie` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `materiel` (`nom`, `description`, `prix_jour`, `photo`, `disponible`, `categorie_id`) VALUES
('VTT Specialized Stumpjumper', 'VTT tout-suspendu haute performance pour les sentiers techniques.', 65.00, 'vtt_pro.jpg', 1, 1),
('Vélo de Route Cannondale', 'Cadre carbone ultra-léger pour les passionnés de vitesse et de longues distances.', 55.00, 'route_carbon.jpg', 1, 1),
('Raquette Wilson Pro Staff', 'La raquette légendaire pour un contrôle et une précision exceptionnels.', 25.00, 'wilson.jpg', 1, 2),
('Skis Atomic Redster', 'Skis de piste haute vitesse pour skieurs confirmés.', 85.00, 'atomic.jpg', 1, 3),
('Snowboard Jones Frontier', 'Board polyvalente freeride pour dominer toute la montagne.', 75.00, 'jones.jpg', 1, 3),
('Pack Escalade Black Diamond', 'Harnais, système d\'assurage et chaussons haute performance.', 45.00, 'climbing.jpg', 1, 2),
('VTT Électrique Haibike', 'VTTAE puissant pour explorer sans limites de distance ou de dénivelé.', 95.00, 'ebike.jpg', 1, 1),
('Kayak de Mer Perception', 'Kayak stable et rapide pour vos explorations côtières.', 50.00, 'kayak.jpg', 1, 2);

-- Table structure for table `users`
CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nom` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL,
  `mot_de_passe` varchar(255) NOT NULL,
  `role` enum('admin','client') DEFAULT 'client',
  `created_at` datetime DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `users` (`id`, `nom`, `email`, `mot_de_passe`, `role`) VALUES
(1, 'Admin SportLoc', 'admin@sport.com', '$2y$10$TKh8H1.PfbuNIAkulGl/v.5GliKMBRJpqEQCvhbZ2W1J.vB6BMMIS', 'admin'),
(2, 'Jean Dupont', 'jean@email.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uXutHOwOu', 'client');

-- Table structure for table `reservation`
CREATE TABLE `reservation` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `materiel_id` int(11) NOT NULL,
  `date_debut` date NOT NULL,
  `date_fin` date NOT NULL,
  `statut` enum('en attente','confirmée','annulée') DEFAULT 'en attente',
  `created_at` datetime DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `materiel_id` (`materiel_id`),
  CONSTRAINT `reservation_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `reservation_ibfk_2` FOREIGN KEY (`materiel_id`) REFERENCES `materiel` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

COMMIT;

