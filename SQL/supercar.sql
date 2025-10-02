-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Sep 30, 2025 at 06:42 AM
-- Server version: 5.7.40
-- PHP Version: 8.0.26

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `supercar`
--

-- --------------------------------------------------------

--
-- Table structure for table `accueil`
--

DROP TABLE IF EXISTS `accueil`;
CREATE TABLE IF NOT EXISTS `accueil` (
  `id_accueil` int(11) NOT NULL AUTO_INCREMENT,
  `texte` text,
  `image` varchar(255) DEFAULT NULL,
  `section` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id_accueil`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `accueil`
--

INSERT INTO `accueil` (`id_accueil`, `texte`, `image`, `section`) VALUES
(1, 'Bienvenue sur SuperCar, votre portail de voitures de luxe et événements exclusifs.', '/supercar/client/voitures/header.jpg', 'header'),
(2, 'SuperCar vous propose une sélection unique de voitures de prestige, des événements privés et des expériences de conduite incomparables.', '', 'main'),
(3, 'Découvrez nos modèles Rolls-Royce, Bugatti, Maserati et Porsche soigneusement sélectionnés pour votre expérience de conduite.', '/supercar/client/voitures/voitures.jpg', 'voitures'),
(4, 'Ne manquez pas nos événements exclusifs : courses, lancements de modèles, lifestyle et ventes privées.', '/supercar/client/evenements/evenement.jpg', 'evenements');

-- --------------------------------------------------------

--
-- Table structure for table `contact`
--

DROP TABLE IF EXISTS `contact`;
CREATE TABLE IF NOT EXISTS `contact` (
  `idcontact` int(11) NOT NULL AUTO_INCREMENT,
  `nom` varchar(150) NOT NULL,
  `prenom` varchar(100) NOT NULL,
  `email` varchar(255) NOT NULL,
  `telephone` varchar(20) DEFAULT NULL,
  `message` text,
  PRIMARY KEY (`idcontact`),
  UNIQUE KEY `email` (`email`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `contact`
--

INSERT INTO `contact` (`idcontact`, `nom`, `prenom`, `email`, `telephone`, `message`) VALUES
(1, 'Abdallah', 'Binti Nazra', 'nazraba@gmail.com', '58423895', 'HelloTest'),
(2, 'Patricio', 'vegas', 'patricio@gmail.com', '5678990114', 'Hello, I\'m from Russy'),
(3, 'Carl', 'Yung', 'carly@gmail.com', '33456789', 'HelloCar');

-- --------------------------------------------------------

--
-- Table structure for table `essai`
--

DROP TABLE IF EXISTS `essai`;
CREATE TABLE IF NOT EXISTS `essai` (
  `idessai` int(11) NOT NULL AUTO_INCREMENT,
  `iduser` int(11) DEFAULT NULL,
  `idcar` int(11) DEFAULT NULL,
  `date_essai` date NOT NULL,
  `lieu_essai` varchar(100) NOT NULL,
  `heure_essai` time DEFAULT NULL,
  `statut` enum('confirmé','en attente','annulé') DEFAULT 'en attente',
  PRIMARY KEY (`idessai`),
  KEY `fk_utilisateur` (`iduser`),
  KEY `fk_voiture` (`idcar`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `essai`
--

INSERT INTO `essai` (`idessai`, `iduser`, `idcar`, `date_essai`, `lieu_essai`, `heure_essai`, `statut`) VALUES
(1, 2, 4, '2025-09-13', 'Bagatelle', '12:30:00', 'confirmé'),
(2, 1, 1, '2025-10-15', 'Ebene', '21:41:00', 'en attente'),
(3, 1, 10, '2025-10-10', 'Milan', '09:10:00', 'en attente');

-- --------------------------------------------------------

--
-- Table structure for table `evenement`
--

DROP TABLE IF EXISTS `evenement`;
CREATE TABLE IF NOT EXISTS `evenement` (
  `idevent` int(11) NOT NULL AUTO_INCREMENT,
  `categorie` varchar(150) DEFAULT NULL,
  `evenement` varchar(200) DEFAULT NULL,
  `description` text,
  `image` varchar(255) DEFAULT NULL,
  `date_event` date DEFAULT NULL,
  `statut` enum('a venir','passe','annule') DEFAULT 'a venir',
  PRIMARY KEY (`idevent`)
) ENGINE=MyISAM AUTO_INCREMENT=8 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `evenement`
--

INSERT INTO `evenement` (`idevent`, `categorie`, `evenement`, `description`, `image`, `date_event`, `statut`) VALUES
(1, 'Lancement et Presentation exclusif', 'Présentation d\'un nouveau modèle', 'lancement du nouveau Rolls personnalisable Phantom', '', '2025-10-15', 'a venir'),
(2, 'Courses-performances et circuits', 'Track Day Porsche', 'Journée circuit pour passionnés, possibilité d’essayer les modèles sur un circuit de course. Marques concernées : Porsche, Maserati, Bugatti pour performance extrême.', '', '2025-09-20', 'a venir'),
(3, 'Ventes et encheres privees', 'Vente aux enchères modèles rares', 'Vente aux enchères de modèles anciens et éditions limitées. Expérience personnalisée pour réserver ou commander une édition spéciale. Marques concernées : Rolls-Royce et Bugatti.', '', '2025-11-05', 'a venir'),
(4, 'Lifestyle experiences et Networking', 'Rallye Prestige & Gala', 'Rallye de prestige de Monaco à Milan, suivi d’une soirée de gala. Exposition d’art automobile et événement caritatif haut de gamme. Marques concernées : Bugatti et Rolls-Royce.', '', '2025-12-01', 'a venir'),
(7, 'Ventes et enchères privées', 'ventes de voiture de courses', 'collection cher et de voitures anciennes et pièces ', '', '2025-09-15', 'a venir');

-- --------------------------------------------------------

--
-- Table structure for table `footer`
--

DROP TABLE IF EXISTS `footer`;
CREATE TABLE IF NOT EXISTS `footer` (
  `idfooter` int(11) NOT NULL AUTO_INCREMENT,
  `tel` varchar(20) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `adresse` text,
  `mentions` text,
  `conditions` text,
  `politiques` text,
  PRIMARY KEY (`idfooter`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `footer`
--

INSERT INTO `footer` (`idfooter`, `tel`, `email`, `adresse`, `mentions`, `conditions`, `politiques`) VALUES
(1, '+230 58423895', 'contact@supercar.com', 'Ebene Tower, Maurice', 'Mentions légales', 'Conditions d\'utilisation', 'Politique de confidentialité');

-- --------------------------------------------------------

--
-- Table structure for table `inscriptions_evenement`
--

DROP TABLE IF EXISTS `inscriptions_evenement`;
CREATE TABLE IF NOT EXISTS `inscriptions_evenement` (
  `idinscription` int(11) NOT NULL AUTO_INCREMENT,
  `iduser` int(11) DEFAULT NULL,
  `idevent` int(11) NOT NULL,
  `nom` varchar(200) DEFAULT NULL,
  `prenom` varchar(150) DEFAULT NULL,
  `email` varchar(150) DEFAULT NULL,
  `telephone` varchar(20) DEFAULT NULL,
  `date_inscription` datetime DEFAULT CURRENT_TIMESTAMP,
  `statut` enum('confirmé','annulé','en attente') DEFAULT 'en attente',
  PRIMARY KEY (`idinscription`),
  KEY `iduser` (`iduser`),
  KEY `fk_event_user` (`idevent`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `inscriptions_evenement`
--

INSERT INTO `inscriptions_evenement` (`idinscription`, `iduser`, `idevent`, `nom`, `prenom`, `email`, `telephone`, `date_inscription`, `statut`) VALUES
(1, NULL, 2, 'Patrik', 'Beltran', 'patrik@gmail.com', '5678990114', '2025-09-09 17:06:07', 'en attente'),
(2, NULL, 2, 'Patricio', 'Vegas', 'patricio@gmail.com', '5678990114', '2025-09-09 17:07:59', 'confirmé'),
(3, NULL, 3, 'Carlos ', 'Beltran', 'carlosb@gmail.com', '6677890', '2025-09-13 14:13:41', 'en attente');

-- --------------------------------------------------------

--
-- Table structure for table `menu`
--

DROP TABLE IF EXISTS `menu`;
CREATE TABLE IF NOT EXISTS `menu` (
  `idmenu` int(11) NOT NULL AUTO_INCREMENT,
  `nom` varchar(55) NOT NULL,
  `lien` varchar(100) NOT NULL,
  `ordre` int(11) NOT NULL,
  PRIMARY KEY (`idmenu`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `menu`
--

INSERT INTO `menu` (`idmenu`, `nom`, `lien`, `ordre`) VALUES
(1, 'Accueil', 'index.php', 1),
(2, 'Voitures', 'cars.php', 2),
(3, 'Demande d\'essai', 'essai.php', 3),
(4, 'Événements', 'evenements.php', 4),
(5, 'Contact', 'contact.php', 5);

-- --------------------------------------------------------

--
-- Table structure for table `utilisateurs`
--

DROP TABLE IF EXISTS `utilisateurs`;
CREATE TABLE IF NOT EXISTS `utilisateurs` (
  `iduser` int(11) NOT NULL AUTO_INCREMENT,
  `nom` varchar(150) NOT NULL,
  `prenom` varchar(100) NOT NULL,
  `email` varchar(255) NOT NULL,
  `telephone` varchar(20) NOT NULL,
  `motdepasse` varchar(255) NOT NULL,
  `statut` enum('admin','user') NOT NULL DEFAULT 'user',
  PRIMARY KEY (`iduser`),
  UNIQUE KEY `email` (`email`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `utilisateurs`
--

INSERT INTO `utilisateurs` (`iduser`, `nom`, `prenom`, `email`, `telephone`, `motdepasse`, `statut`) VALUES
(1, 'Nazra Binti ', 'Abdallah', 'nazraba@gmail.com', '58453896', '$2y$10$8x5Dz9h/nkx8Vi9trnXsRuRUH6qlrucc8Prb4UOb2Y7OmfgciMWlK', 'admin'),
(2, 'Patricio', 'Vegas', 'patricio@gmail.com', '56789908', '$2y$10$1mMWF2Fq6rEyuUbHNgdaJO7eqCTLqPYp0fMLQI1GMJ53XTnq2yl8m', 'user');

-- --------------------------------------------------------

--
-- Table structure for table `voitures`
--

DROP TABLE IF EXISTS `voitures`;
CREATE TABLE IF NOT EXISTS `voitures` (
  `idcar` int(11) NOT NULL AUTO_INCREMENT,
  `marque` varchar(50) NOT NULL,
  `modele` varchar(50) NOT NULL,
  `img` varchar(255) DEFAULT NULL,
  `description` text,
  `performance` varchar(55) DEFAULT NULL,
  `kilometrage` varchar(55) DEFAULT NULL,
  `prix` decimal(10,2) NOT NULL,
  PRIMARY KEY (`idcar`)
) ENGINE=MyISAM AUTO_INCREMENT=18 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `voitures`
--

INSERT INTO `voitures` (`idcar`, `marque`, `modele`, `img`, `description`, `performance`, `kilometrage`, `prix`) VALUES
(1, 'Rolls-Royce', 'Cullinan', '/supercar/client/voitures/Rolls-royce/cullinanRs.avif', 'Fondée par Charles Rolls et Henry Royce au Royaume-Uni. Destinée aux clients recherchant le luxe ultime et la puissance discrète. Année de création : 2018.', '563 ch', '20 km', '3300000.00'),
(2, 'Rolls-Royce', 'Phantom', '/supercar/client/voitures/Rolls-royce/PhantomRs.jpg', 'Fondée par Charles Rolls et Henry Royce au Royaume-Uni. Destinée aux amateurs de luxe traditionnel et de confort maximal. Année de création : 1925.', '571 ch', '200 km', '4500000.00'),
(3, 'Rolls-Royce', 'Ghost', '/supercar/client/voitures/Rolls-royce/GhostRs.jpg', 'Fondée par Charles Rolls et Henry Royce au Royaume-Uni. Public cible : professionnels recherchant luxe et élégance discrète. Année de création : 2009.', '571 ch', '50 km', '3500000.00'),
(4, 'Rolls-Royce', 'Spectre', '/supercar/client/voitures/Rolls-royce/SpectreRs.jpg', 'Fondée par Charles Rolls et Henry Royce au Royaume-Uni. Public cible : clients haut de gamme souhaitant un véhicule électrique luxueux. Année de création : 2023.', '580 ch', '80 km', '4000000.00'),
(5, 'Bugatti', 'Chiron', '/supercar/client/voitures/Bugatti/chiron-Bgt.jpg', 'Fondée par Ettore Bugatti en France. Destinée aux passionnés de vitesse extrême et collectionneurs. Année de création : 2016.', '1500 ch', '0 km', '2800000.00'),
(6, 'Bugatti', 'Divo', '/supercar/client/voitures/Bugatti/divo-Bgt.jpg', 'Fondée par Ettore Bugatti en France. Public cible : collectionneurs exclusifs recherchant performance et design. Année de création : 2018.', '1500 ch', '0 km', '5800000.00'),
(7, 'Bugatti', 'La Voiture Noire', '/supercar/client/voitures/Bugatti/lavoiturenoire-Bgt.jpg', 'Fondée par Ettore Bugatti en France. Destinée aux collectionneurs ultra-rares. Année de création : 2019.', '1500 ch', '0 km', '11000000.00'),
(8, 'Bugatti', 'Centodieci', '/supercar/client/voitures/Bugatti/centodieci_bgt.jpg', 'Fondée par Ettore Bugatti en France. Public cible : passionnés de performance et édition limitée. Année de création : 2021.', '1600 ch', '0 km', '9000000.00'),
(9, 'Maserati', 'Ghilbi Trofeo', '/supercar/client/voitures/Maserati/ghilbi-Mst.jpg', 'Fondée en Italie par les frères Maserati. Destinée aux amateurs de sportives de luxe et confort. Année de création : 2020.', '580 ch', '0 km', '200000.00'),
(10, 'Maserati', 'Levante Trofeo', '/supercar/client/voitures/Maserati/levante-Mst.jpg', 'Fondée en Italie par les frères Maserati. Public cible : clients recherchant SUV performant et raffiné. Année de création : 2019.', '590 ch', '0 km', '220000.00'),
(11, 'Maserati', 'MC20', '/supercar/client/voitures/Maserati/mc20-Mst.jpg', 'Fondée en Italie par les frères Maserati. Destinée aux passionnés de sportives haut de gamme. Année de création : 2020.', '630 ch', '0 km', '250000.00'),
(12, 'Maserati', 'Quattroporte Trofeo', '/supercar/client/voitures/Maserati/quattrotrofeo-Mst.jpg', 'Fondée en Italie par les frères Maserati. Public cible : luxe et berlines performantes. Année de création : 2022.', '580 ch', '0 km', '300000.00'),
(13, 'Porsche', 'Panamera', '/supercar/client/voitures/Porsche/panameraTS.jpg', 'Fondée en Allemagne par Ferdinand Porsche. Destinée aux amateurs de sportives confortables et luxueuses. Année de création : 2009.', '550 ch', '0 km', '150000.00'),
(14, 'Porsche', 'Cayenne', '/supercar/client/voitures/Porsche/cayenneTGT.jpg', 'Fondée en Allemagne par Ferdinand Porsche. Public cible : SUV performant et polyvalent. Année de création : 2002.', '550 ch', '0 km', '120000.00'),
(15, 'Porsche', 'Taycan', '/supercar/client/voitures/Porsche/taycanTS.PNG', 'Fondée en Allemagne par Ferdinand Porsche. Destinée aux passionnés de sportives électriques hautes performances. Année de création : 2019.', '600 ch', '0 km', '180000.00'),
(16, 'Porsche', 'Turbo', '/supercar/client/voitures/Porsche/turboS.jpg', 'Fondée en Allemagne par Ferdinand Porsche. Public cible : performance et luxe. Année de création : 2020.', '650 ch', '0 km', '200000.00');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
