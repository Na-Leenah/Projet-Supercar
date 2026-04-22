SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Procedures
--
DELIMITER $$

DROP PROCEDURE IF EXISTS `sp_creer_demande_essai`$$
CREATE PROCEDURE `sp_creer_demande_essai` (IN `p_utilisateur_id` INT, IN `p_voiture_id` INT, IN `p_prenom` VARCHAR(100), IN `p_nom` VARCHAR(100), IN `p_email` VARCHAR(150), IN `p_telephone` VARCHAR(20), IN `p_date` DATE, IN `p_horaire` VARCHAR(50), OUT `p_result` VARCHAR(100))
BEGIN
    DECLARE v_dispo INT DEFAULT 0;
    DECLARE v_exist INT DEFAULT 0;

    -- 1. Vérifier que le véhicule est disponible
    SELECT COUNT(*) INTO v_dispo
    FROM voitures
    WHERE id = p_voiture_id AND disponible = 1;

    IF v_dispo = 0 THEN
        SET p_result = 'ERREUR: Véhicule non disponible';

    ELSE
        -- 2. Vérifier qu'il n'y a pas déjà une demande en attente
        SELECT COUNT(*) INTO v_exist
        FROM demandes_essai
        WHERE utilisateur_id = p_utilisateur_id
          AND voiture_id     = p_voiture_id
          AND statut         = 'en attente';

        IF v_exist > 0 THEN
            SET p_result = 'ERREUR: Demande déjà en cours pour ce véhicule';

        ELSE
            -- 3. Insérer la demande
            INSERT INTO demandes_essai
                (utilisateur_id, voiture_id, prenom, nom, email, telephone, date_souhaitee, horaire)
            VALUES
                (p_utilisateur_id, p_voiture_id, p_prenom, p_nom, p_email, p_telephone, p_date, p_horaire);

            SET p_result = 'OK';
        END IF;
    END IF;
END$$

DELIMITER ;

-- --------------------------------------------------------
-- Table: contacts
-- --------------------------------------------------------

DROP TABLE IF EXISTS `contacts`;
CREATE TABLE IF NOT EXISTS `contacts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `prenom` varchar(100) NOT NULL,
  `nom` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL,
  `sujet` varchar(200) DEFAULT NULL,
  `message` text NOT NULL,
  `lu` tinyint(1) DEFAULT '0',
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4;

INSERT INTO `contacts` (`id`, `prenom`, `nom`, `email`, `sujet`, `message`, `lu`, `created_at`) VALUES
(1, 'Carlos', 'Dupont', 'card@gmail.com', 'Renseignement', 'Bonjour,\r\n\r\nOù se trouvent vos locaux ?', 1, '2026-04-20 15:28:45');

-- --------------------------------------------------------
-- Table: demandes_essai
-- --------------------------------------------------------

DROP TABLE IF EXISTS `demandes_essai`;
CREATE TABLE IF NOT EXISTS `demandes_essai` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `utilisateur_id` int(11) NOT NULL,
  `voiture_id` int(11) NOT NULL,
  `prenom` varchar(100) NOT NULL,
  `nom` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL,
  `telephone` varchar(20) NOT NULL,
  `date_souhaitee` date NOT NULL,
  `horaire` varchar(50) NOT NULL,
  `statut` enum('en attente','confirmé','refusé','effectué') DEFAULT 'en attente',
  `notes_admin` text,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `fk_essai_user` (`utilisateur_id`),
  KEY `fk_essai_voiture` (`voiture_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4;

INSERT INTO `demandes_essai` (`id`, `utilisateur_id`, `voiture_id`, `prenom`, `nom`, `email`, `telephone`, `date_souhaitee`, `horaire`, `statut`, `notes_admin`, `created_at`, `updated_at`) VALUES
(2, 3, 2, 'Carlos', 'Dupont', 'card@gmail.com', '+2305678770114', '2026-05-03', '10:18', 'en attente', NULL, '2026-04-20 10:18:13', '2026-04-20 10:18:13');

-- --------------------------------------------------------
-- Trigger: tg_demande_essai_update
-- --------------------------------------------------------

DROP TRIGGER IF EXISTS `tg_demande_essai_update`;
DELIMITER $$
CREATE TRIGGER `tg_demande_essai_update` BEFORE UPDATE ON `demandes_essai` FOR EACH ROW
BEGIN
    SET NEW.updated_at = NOW();
END$$
DELIMITER ;

-- --------------------------------------------------------
-- Table: evenements
-- --------------------------------------------------------

DROP TABLE IF EXISTS `evenements`;
CREATE TABLE IF NOT EXISTS `evenements` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `titre` varchar(200) NOT NULL,
  `description` text,
  `lieu` varchar(200) DEFAULT NULL,
  `date_event` date NOT NULL,
  `heure_debut` time DEFAULT NULL,
  `heure_fin` time DEFAULT NULL,
  `type_event` enum('Salon','Lancement','Portes ouvertes','Promotion','VIP') DEFAULT 'Salon',
  `inscription_requise` tinyint(1) DEFAULT '0',
  `places_max` int(11) DEFAULT NULL,
  `image_path` varchar(255) DEFAULT NULL,
  `actif` tinyint(1) DEFAULT '1',
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4;

INSERT INTO `evenements` (`id`, `titre`, `description`, `lieu`, `date_event`, `heure_debut`, `heure_fin`, `type_event`, `inscription_requise`, `places_max`, `image_path`, `actif`, `created_at`) VALUES
(1, 'Salon de l\'Auto Maurice 2024', 'Le grand salon automobile annuel de l\'Île Maurice. Venez découvrir les dernières nouveautés des plus grandes marques en exclusivité.', 'Swami Vivekananda Hall, Port-Louis', '2024-02-15', '10:00:00', '18:00:00', 'Salon', 0, NULL, 'client/evenements/evenement.jpg', 1, '2026-03-20 02:04:21'),
(2, 'Portes ouvertes SuperCar', 'Venez découvrir nos véhicules d\'exception et réserver votre essai en direct. Nos conseillers sont à votre disposition toute la journée.', 'Siège social SuperCar, Ebène', '2024-02-22', '09:00:00', '17:00:00', 'Portes ouvertes', 0, NULL, 'client/evenements/evenement.jpg', 1, '2026-03-20 02:04:21'),
(3, 'Lancement Porsche 911 GT3 RS', 'Soyez parmi les premiers à découvrir la nouvelle Porsche 911 GT3 RS à l\'Île Maurice. Événement VIP sur invitation.', 'SuperCar Showroom, Ebène', '2024-03-08', '14:00:00', '20:00:00', 'Lancement', 1, 50, 'client/evenements/evenement.jpg', 1, '2026-03-20 02:04:21'),
(4, 'Promo fin de stock — Maserati', 'Profitez d\'offres exceptionnelles sur les derniers modèles Maserati disponibles en stock. Remises jusqu\'à 15% sur les modèles 2023.', 'Tous les entrepôts SuperCar, Île Maurice', '2024-03-20', '09:00:00', '17:00:00', 'Promotion', 0, NULL, 'client/evenements/evenement.jpg', 1, '2026-03-20 02:04:21'),
(5, 'Nuit Bugatti — Expérience Exclusive', 'Une soirée de prestige dédiée aux passionnés de la marque Bugatti. Présentation exclusive du Chiron et rencontre avec les experts de la maison.', 'Intercontinental Mauritius, Balaclava', '2026-06-25', '19:00:00', '23:00:00', 'VIP', 1, 30, 'client/evenements/evenement.jpg', 1, '2026-03-20 02:04:21'),
(6, 'Lancement Bugatti', 'Découvrez le lancement des nouveaux modèles Bugatti.', 'Ebene-Tower', '2026-05-20', '14:10:00', '19:10:00', 'Lancement', 1, NULL, 'client/evenements/evenement.jpg', 1, '2026-04-20 15:19:42');

-- --------------------------------------------------------
-- Table: inscriptions_evenements
-- --------------------------------------------------------

DROP TABLE IF EXISTS `inscriptions_evenements`;
CREATE TABLE IF NOT EXISTS `inscriptions_evenements` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `evenement_id` int(11) NOT NULL,
  `utilisateur_id` int(11) DEFAULT NULL,
  `prenom` varchar(100) NOT NULL,
  `nom` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL,
  `telephone` varchar(20) DEFAULT NULL,
  `date_inscription` datetime DEFAULT CURRENT_TIMESTAMP,
  `statut` enum('confirmé','en attente','annulé') DEFAULT 'en attente',
  PRIMARY KEY (`id`),
  KEY `fk_insc_event` (`evenement_id`),
  KEY `fk_insc_user` (`utilisateur_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4;

INSERT INTO `inscriptions_evenements` (`id`, `evenement_id`, `utilisateur_id`, `prenom`, `nom`, `email`, `telephone`, `date_inscription`, `statut`) VALUES
(1, 6, 1, 'Carlos', 'Dupont', 'card@gmail.com', '+2305678770114', '2026-04-20 15:22:40', 'confirmé');

-- --------------------------------------------------------
-- Table: marques
-- --------------------------------------------------------

DROP TABLE IF EXISTS `marques`;
CREATE TABLE IF NOT EXISTS `marques` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nom` varchar(100) NOT NULL,
  `slogan` varchar(200) DEFAULT NULL,
  `description` text,
  `logo_path` varchar(255) DEFAULT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4;

INSERT INTO `marques` (`id`, `nom`, `slogan`, `description`, `logo_path`, `created_at`) VALUES
(1, 'Rolls-Royce', 'Inspiring Greatness', 'Fondée par Charles Rolls et Henry Royce au Royaume-Uni. Destinée aux clients recherchant le luxe ultime et la puissance discrète.', NULL, '2026-03-20 02:04:21'),
(2, 'Bugatti', 'Art, Forme, Technique', 'Maison fondée par Ettore Bugatti en 1909 en Alsace. Icône de l\'ingénierie automobile et du design d\'exception.', NULL, '2026-03-20 02:04:21'),
(3, 'Maserati', 'Excellence in Motion', 'Fondée à Bologne en 1914, Maserati incarne le sport, le luxe et l\'élégance italienne depuis plus d\'un siècle.', NULL, '2026-03-20 02:04:21'),
(4, 'Porsche', 'There is no substitute', 'Fondée en 1931 par Ferdinand Porsche à Stuttgart. Référence mondiale en matière de performance et de sportivité.', NULL, '2026-03-20 02:04:21');

-- --------------------------------------------------------
-- Table: utilisateurs
-- --------------------------------------------------------

DROP TABLE IF EXISTS `utilisateurs`;
CREATE TABLE IF NOT EXISTS `utilisateurs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `prenom` varchar(100) NOT NULL,
  `nom` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL,
  `mot_de_passe` varchar(255) NOT NULL,
  `telephone` varchar(20) DEFAULT NULL,
  `role` enum('client','admin') DEFAULT 'client',
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4;

INSERT INTO `utilisateurs` (`id`, `prenom`, `nom`, `email`, `mot_de_passe`, `telephone`, `role`, `created_at`, `updated_at`) VALUES
(1, 'Super', 'Admin', 'admin@supercar.mu', '$2y$10$2wfncRKlJaTwhQVGd7xER.8KVfT3kPgGd9RILxWWOWWISPLlKUfWm', '+230 5000 0000', 'admin', '2026-03-20 02:04:21', '2026-03-20 08:38:36'),
(2, 'Cesar', 'carlos', 'carlos@gmail.fr', '$2y$10$nDOK766r7tegVk3iT/goKOq3dFgXzWfUg2AgCCfDx3pwr5yIIIQJi', '+2302213456789', 'client', '2026-03-20 03:50:30', '2026-03-20 03:50:30'),
(3, 'Carlos', 'Dupont', 'card@gmail.com', '$2y$10$oOUO4aMabEevtuDiRFNgfukHH1tTqC8.1qv2.DooqorjLTXTPov8W', '+2305678770114', 'client', '2026-04-20 07:13:34', '2026-04-20 07:13:34');

-- --------------------------------------------------------
-- Table: voitures
-- --------------------------------------------------------

DROP TABLE IF EXISTS `voitures`;
CREATE TABLE IF NOT EXISTS `voitures` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `marque_id` int(11) NOT NULL,
  `nom` varchar(150) NOT NULL,
  `description` text,
  `prix` decimal(15,2) NOT NULL,
  `performance` varchar(100) DEFAULT NULL,
  `kilometrage` int(11) DEFAULT '0',
  `carburant` enum('Essence','Diesel','Hybride','Electrique') DEFAULT 'Essence',
  `type_vehicule` enum('Berline','SUV','Coupé','Cabriolet','Hypercar') DEFAULT 'Berline',
  `annee` year DEFAULT NULL,
  `image_path` varchar(255) DEFAULT NULL,
  `disponible` tinyint(1) DEFAULT '1',
  `en_vedette` tinyint(1) DEFAULT '0',
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `fk_voiture_marque` (`marque_id`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb4;

INSERT INTO `voitures` (`id`, `marque_id`, `nom`, `description`, `prix`, `performance`, `kilometrage`, `carburant`, `type_vehicule`, `annee`, `image_path`, `disponible`, `en_vedette`, `created_at`, `updated_at`) VALUES
(1, 1, 'Cullinan', 'Le Cullinan est le premier SUV de l\'histoire Rolls-Royce. Un véhicule tout-terrain sans compromis sur le luxe, équipé d\'un moteur V12 biturbo de 6,75 litres offrant une puissance de 563 chevaux.', '105000000.00', '563 ch', 20, 'Essence', 'SUV', 2023, 'client/voitures/Rolls-royce/cullinanRs.avif', 1, 1, '2026-03-20 02:04:21', '2026-03-20 02:04:21'),
(2, 1, 'Ghost', 'La Rolls-Royce Ghost incarne la philosophie du luxe post-opulent. Propulsée par un V12 biturbo de 571 chevaux, elle offre une conduite sereine et une isolation phonique incomparable.', '72000000.00', '571 ch', 15, 'Essence', 'Berline', 2023, 'client/voitures/Rolls-royce/GhostRs.jpg', 1, 1, '2026-03-20 02:04:21', '2026-03-20 02:04:21'),
(3, 1, 'Phantom', 'La Rolls-Royce Phantom VIII est le summum de l\'automobile de prestige. Architecture en aluminium, moteur V12 de 6,75 litres, intérieur entièrement personnalisable à la commande.', '120000000.00', '563 ch', 10, 'Essence', 'Berline', 2023, 'client/voitures/Rolls-royce/PhantomRs.jpg', 1, 0, '2026-03-20 02:04:21', '2026-03-20 02:04:21'),
(4, 1, 'Spectre', 'La Rolls-Royce Spectre est le premier coupé entièrement électrique de la marque. Elle annonce l\'avenir de Rolls-Royce tout en restant fidèle à l\'héritage d\'excellence de la maison.', '130000000.00', '584 ch', 5, 'Electrique', 'Coupé', 2024, 'client/voitures/Rolls-royce/SpectreRs.jpg', 1, 0, '2026-03-20 02:04:21', '2026-03-20 02:04:21'),
(5, 2, 'Chiron', 'La Bugatti Chiron est équipée d\'un moteur W16 quad-turbo de 8,0 litres développant 1500 chevaux. Elle atteint 420 km/h en vitesse de pointe, faisant d\'elle l\'une des hypercars les plus impressionnantes au monde.', '360000000.00', '1500 ch', 0, 'Essence', 'Hypercar', 2023, 'client/voitures/Bugatti/chiron-Bgt.jpg', 1, 1, '2026-03-20 02:04:21', '2026-03-20 02:04:21'),
(6, 2, 'Centodieci', 'La Bugatti Centodieci est un hommage à l\'EB110. Limitée à 10 exemplaires mondiaux, elle développe 1600 chevaux et représente l\'apogée du savoir-faire Bugatti.', '450000000.00', '1600 ch', 0, 'Essence', 'Hypercar', 2023, 'client/voitures/Bugatti/centodieci_bgt.jpg', 1, 0, '2026-03-20 02:04:21', '2026-03-20 02:04:21'),
(7, 2, 'Divo', 'La Bugatti Divo est une hypercar conçue pour la performance en virage. Avec ses 1500 chevaux et son aérodynamique optimisée, elle est la voiture de circuit ultime de la marque.', '420000000.00', '1500 ch', 0, 'Essence', 'Hypercar', 2023, 'client/voitures/Bugatti/divo-Bgt.jpg', 1, 0, '2026-03-20 02:04:21', '2026-03-20 02:04:21'),
(8, 2, 'La Voiture Noire', 'La Bugatti La Voiture Noire est un chef-d\'œuvre unique au monde. Inspirée de la Type 57 SC Atlantic, elle est considérée comme la voiture neuve la plus chère jamais vendue.', '600000000.00', '1500 ch', 0, 'Essence', 'Hypercar', 2023, 'client/voitures/Bugatti/lavoiturenoire-Bgt.jpg', 1, 0, '2026-03-20 02:04:21', '2026-03-20 02:04:21'),
(9, 3, 'Ghibli', 'La Maserati Ghibli est la berline sport la plus accessible de la marque. Alliant design expressif et motorisation puissante, elle offre l\'expérience Maserati pour un public plus large.', '25000000.00', '350 ch', 0, 'Essence', 'Berline', 2023, 'client/voitures/Maserati/ghilbi-Mst.jpg', 1, 0, '2026-03-20 02:04:21', '2026-03-20 02:04:21'),
(10, 3, 'Levante', 'Le Maserati Levante est le premier SUV de la marque. Il combine le tempérament sportif de Maserati avec la praticité d\'un SUV de luxe, propulsé par un V6 biturbo Ferrari.', '32000000.00', '430 ch', 0, 'Essence', 'SUV', 2023, 'client/voitures/Maserati/levante-Mst.jpg', 1, 1, '2026-03-20 02:04:21', '2026-03-20 02:04:21'),
(11, 3, 'MC20', 'La Maserati MC20 est le retour de la marque en supercar. Avec son moteur Nettuno V6 biturbo de 630 chevaux développé en interne, elle marque une nouvelle ère pour le Trident.', '60000000.00', '630 ch', 0, 'Essence', 'Coupé', 2023, 'client/voitures/Maserati/mc20-Mst.jpg', 1, 1, '2026-03-20 02:04:21', '2026-03-20 02:04:21'),
(12, 3, 'Quattroporte', 'La Maserati Quattroporte est la grande berline de prestige de la marque. Quatre portes, un style inoubliable et des motorisations V6 et V8 pour une expérience unique de grand tourisme.', '42000000.00', '530 ch', 0, 'Essence', 'Berline', 2023, 'client/voitures/Maserati/quattrotrofeo-Mst.jpg', 1, 0, '2026-03-20 02:04:21', '2026-03-20 02:04:21'),
(13, 4, 'Cayenne Turbo GT', 'Le Porsche Cayenne Turbo GT est le SUV sportif le plus puissant de la marque. Son V8 de 640 chevaux lui permet d\'abattre le 0-100 km/h en 3,3 secondes — un record pour un SUV 5 places.', '42000000.00', '640 ch', 0, 'Essence', 'SUV', 2023, 'client/voitures/Porsche/cayenneTGT.jpg', 1, 1, '2026-03-20 02:04:21', '2026-03-20 02:04:21'),
(14, 4, 'Panamera Turbo S', 'La Porsche Panamera Turbo S est la grande berline sportive par excellence. Son V8 biturbo de 630 chevaux offre des performances de supercar avec un confort de grand tourisme.', '38000000.00', '630 ch', 0, 'Essence', 'Berline', 2023, 'client/voitures/Porsche/panameraTS.jpg', 1, 0, '2026-03-20 02:04:21', '2026-03-20 02:04:21'),
(15, 4, 'Taycan Turbo S', 'La Porsche Taycan Turbo S est la berline électrique de performance de Porsche. Avec 761 chevaux en overboost, elle abat le 0-100 km/h en 2,8 secondes, redéfinissant la voiture électrique de sport.', '48000000.00', '761 ch', 0, 'Electrique', 'Berline', 2024, 'client/voitures/Porsche/taycanTS.PNG', 1, 1, '2026-03-20 02:04:21', '2026-03-20 02:04:21'),
(16, 4, 'Turbo S Cabriolet', 'Le Porsche 911 Turbo S Cabriolet combine les performances légendaires du Turbo S avec le plaisir du ciel ouvert. Un moteur flat-six de 650 chevaux pour une expérience incomparable.', '52000000.00', '650 ch', 0, 'Essence', 'Cabriolet', 2023, 'client/voitures/Porsche/turboS.jpg', 1, 0, '2026-03-20 02:04:21', '2026-03-20 02:04:21');

-- --------------------------------------------------------
-- Vues
-- --------------------------------------------------------

DROP VIEW IF EXISTS `v_catalogue`;
CREATE VIEW `v_catalogue` AS
  SELECT `v`.`id`, `m`.`nom` AS `marque`, `v`.`nom` AS `modele`, `v`.`description`,
         `v`.`prix`, `v`.`performance`, `v`.`kilometrage`, `v`.`carburant`,
         `v`.`type_vehicule`, `v`.`annee`, `v`.`image_path`, `v`.`disponible`, `v`.`en_vedette`
  FROM `voitures` `v`
  JOIN `marques` `m` ON `v`.`marque_id` = `m`.`id`
  WHERE `v`.`disponible` = 1
  ORDER BY `m`.`nom` ASC, `v`.`prix` ASC;

DROP VIEW IF EXISTS `v_demandes_essai`;
CREATE VIEW `v_demandes_essai` AS
  SELECT `d`.`id`,
         CONCAT(`u`.`prenom`, ' ', `u`.`nom`) AS `client`,
         `u`.`email`, `u`.`telephone`,
         CONCAT(`m`.`nom`, ' ', `v`.`nom`) AS `vehicule`,
         `v`.`image_path`,
         `d`.`date_souhaitee`, `d`.`horaire`, `d`.`statut`, `d`.`created_at`
  FROM `demandes_essai` `d`
  JOIN `utilisateurs` `u` ON `d`.`utilisateur_id` = `u`.`id`
  JOIN `voitures` `v` ON `d`.`voiture_id` = `v`.`id`
  JOIN `marques` `m` ON `v`.`marque_id` = `m`.`id`
  ORDER BY `d`.`created_at` DESC;

DROP VIEW IF EXISTS `v_evenements_actifs`;
CREATE VIEW `v_evenements_actifs` AS
  SELECT `id`, `titre`, `description`, `lieu`, `date_event`, `heure_debut`, `heure_fin`,
         `type_event`, `inscription_requise`, `places_max`, `image_path`, `actif`, `created_at`
  FROM `evenements`
  WHERE `actif` = 1 AND `date_event` >= CURDATE()
  ORDER BY `date_event` ASC;

DROP VIEW IF EXISTS `v_vedette`;
CREATE VIEW `v_vedette` AS
  SELECT `v`.`id`, `m`.`nom` AS `marque`, `v`.`nom` AS `modele`,
         `v`.`prix`, `v`.`performance`, `v`.`image_path`
  FROM `voitures` `v`
  JOIN `marques` `m` ON `v`.`marque_id` = `m`.`id`
  WHERE `v`.`en_vedette` = 1 AND `v`.`disponible` = 1
  ORDER BY `m`.`nom` ASC;

-- --------------------------------------------------------
-- Contraintes (clés étrangères)
-- --------------------------------------------------------

ALTER TABLE `demandes_essai`
  ADD CONSTRAINT `fk_essai_user` FOREIGN KEY (`utilisateur_id`) REFERENCES `utilisateurs` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_essai_voiture` FOREIGN KEY (`voiture_id`) REFERENCES `voitures` (`id`) ON UPDATE CASCADE;

ALTER TABLE `inscriptions_evenements`
  ADD CONSTRAINT `fk_insc_event` FOREIGN KEY (`evenement_id`) REFERENCES `evenements` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_insc_user` FOREIGN KEY (`utilisateur_id`) REFERENCES `utilisateurs` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

ALTER TABLE `voitures`
  ADD CONSTRAINT `fk_voiture_marque` FOREIGN KEY (`marque_id`) REFERENCES `marques` (`id`) ON UPDATE CASCADE;

COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
