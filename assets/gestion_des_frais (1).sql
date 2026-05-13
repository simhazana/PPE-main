-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le : mer. 13 mai 2026 à 12:25
-- Version du serveur : 9.1.0
-- Version de PHP : 8.3.14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `gestion_des_frais`
--

-- --------------------------------------------------------

--
-- Structure de la table `etat`
--

DROP TABLE IF EXISTS `etat`;
CREATE TABLE IF NOT EXISTS `etat` (
  `ID` int NOT NULL AUTO_INCREMENT,
  `libelle` varchar(255) NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb3;

--
-- Déchargement des données de la table `etat`
--

INSERT INTO `etat` (`ID`, `libelle`) VALUES
(2, 'Clôturé'),
(3, 'Validé'),
(7, 'Remboursé'),
(8, 'créee'),
(9, 'créee'),
(11, 'teste');

-- --------------------------------------------------------

--
-- Structure de la table `fichefrais`
--

DROP TABLE IF EXISTS `fichefrais`;
CREATE TABLE IF NOT EXISTS `fichefrais` (
  `IDvisiteur` int NOT NULL,
  `mois` int NOT NULL,
  `nbrJustificatifs` int NOT NULL,
  `montantValide` int NOT NULL,
  `dateModif` date NOT NULL,
  `idLigneFraisHorsForfait` int NOT NULL,
  `idEtat` int NOT NULL,
  PRIMARY KEY (`IDvisiteur`,`mois`),
  KEY `fk_fichefrais_LigneFraisHorsForfait` (`idLigneFraisHorsForfait`),
  KEY `fk_fichefrais_etat` (`idEtat`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Déchargement des données de la table `fichefrais`
--

INSERT INTO `fichefrais` (`IDvisiteur`, `mois`, `nbrJustificatifs`, `montantValide`, `dateModif`, `idLigneFraisHorsForfait`, `idEtat`) VALUES
(1, 202508, 5, 615, '2026-04-30', 2, 3),
(1, 202509, 3, 420, '2026-04-30', 3, 3),
(3, 202507, 1, 110, '2025-07-15', 2, 3),
(3, 202508, 3, 330, '2026-04-30', 3, 3);

-- --------------------------------------------------------

--
-- Structure de la table `fraisforfait`
--

DROP TABLE IF EXISTS `fraisforfait`;
CREATE TABLE IF NOT EXISTS `fraisforfait` (
  `ID` int NOT NULL AUTO_INCREMENT,
  `libelle` varchar(255) NOT NULL,
  `montant` varchar(255) NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb3;

--
-- Déchargement des données de la table `fraisforfait`
--

INSERT INTO `fraisforfait` (`ID`, `libelle`, `montant`) VALUES
(1, 'teste', '200'),
(2, 'Frais Kilométrique', '0.62'),
(3, 'Nuitée Hôtel', '80.00'),
(4, 'Repas Restaurant', '25.00');

-- --------------------------------------------------------

--
-- Structure de la table `fraishorsforfait`
--

DROP TABLE IF EXISTS `fraishorsforfait`;
CREATE TABLE IF NOT EXISTS `fraishorsforfait` (
  `ID` int NOT NULL AUTO_INCREMENT,
  `date` date NOT NULL,
  `montant` varchar(255) NOT NULL,
  `libelle` varchar(255) NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb3;

--
-- Déchargement des données de la table `fraishorsforfait`
--

INSERT INTO `fraishorsforfait` (`ID`, `date`, `montant`, `libelle`) VALUES
(1, '2025-08-03', '45.90', 'Taxi aéroport'),
(2, '2025-08-20', '18.50', 'Stationnement'),
(3, '2025-09-05', '72.00', 'Péage autoroute');

-- --------------------------------------------------------

--
-- Structure de la table `lignefraisforfait`
--

DROP TABLE IF EXISTS `lignefraisforfait`;
CREATE TABLE IF NOT EXISTS `lignefraisforfait` (
  `IDvisiteur` int NOT NULL,
  `mois` int NOT NULL,
  `IDfraisforfait` int NOT NULL,
  `quantite` varchar(255) NOT NULL,
  PRIMARY KEY (`IDvisiteur`,`mois`,`IDfraisforfait`),
  KEY `fk_lff_fraisforfait` (`IDfraisforfait`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Déchargement des données de la table `lignefraisforfait`
--

INSERT INTO `lignefraisforfait` (`IDvisiteur`, `mois`, `IDfraisforfait`, `quantite`) VALUES
(1, 202508, 1, '3'),
(1, 202508, 2, '180'),
(1, 202508, 3, '4'),
(1, 202508, 4, '5'),
(1, 202509, 1, '1'),
(1, 202509, 2, '120'),
(1, 202509, 3, '2'),
(1, 202509, 4, '3'),
(3, 202507, 1, '1'),
(3, 202507, 4, '1'),
(3, 202508, 1, '2'),
(3, 202508, 2, '75'),
(3, 202508, 3, '1'),
(3, 202508, 4, '2');

-- --------------------------------------------------------

--
-- Structure de la table `visiteur`
--

DROP TABLE IF EXISTS `visiteur`;
CREATE TABLE IF NOT EXISTS `visiteur` (
  `ID` int NOT NULL AUTO_INCREMENT,
  `NOM` varchar(255) NOT NULL,
  `PRENOM` varchar(255) NOT NULL,
  `ADRESSE` varchar(255) NOT NULL,
  `VILLE` varchar(255) NOT NULL,
  `CP` varchar(255) NOT NULL,
  `DATE_EMBAUCHE` date NOT NULL,
  `LOGIN` varchar(255) NOT NULL,
  `MDP` varchar(255) NOT NULL,
  `role` varchar(15) DEFAULT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb3;

--
-- Déchargement des données de la table `visiteur`
--

INSERT INTO `visiteur` (`ID`, `NOM`, `PRENOM`, `ADRESSE`, `VILLE`, `CP`, `DATE_EMBAUCHE`, `LOGIN`, `MDP`, `role`) VALUES
(1, 'Martin', 'Alice', '12 rue des Lilas', 'Lyon', '69003', '2023-03-15', 'alice.m', '$2y$10$BoOr0BYk4gS8pkxQVtyGOO9vlCjMV0Pz8lvgOGC9lKqS8LZllNCpa', 'Visiteur'),
(2, 'Dupont', 'Bruno', '5 av. des Cèdres', 'Paris', '75012', '2022-11-02', 'bruno.d', '$2y$10$1P4fTm/.f6cjcEoqTTMWcO7/pKGF3Ri1YzJTp5SsmL8FSxciKYFxm', 'Comptable'),
(3, 'Bernard', 'Clara', '8 bd. Victor', 'Marseille', '13008', '2024-01-09', 'clara.b', '$2y$10$wSMqLAa1Yn5XXOSETd3ZReJxr/VC3eNdMxmhPHWdRj1NOJEVTqur2', 'Comptable'),
(4, 'Petit', 'David', '27 chemin Vert', 'Toulouse', '31000', '2021-06-30', 'david.p', '$2y$10$lTvbM3sNrSpqWLNP.jCteObr1VsIp9MS7O.FCJ8Nv77RZRKoFb5j2', 'Visiteur'),
(5, 'Robert', 'Emma', '3 imp. des Écoles', 'Nantes', '44000', '2020-09-21', 'emma.r', '$2y$10$5Ev4bJti9cld7f8Av5a.SOBhLjFBM//pLD.tUcejwxAwx4Ga8VlNm', 'Comptable'),
(9, 'jja', 'ee', 'jss', 'ppq', '55555', '2026-03-19', 'alice.m', 'hash_pwd_1', 'Comptable'),
(10, 's', 's', 'lala', 'mmm', '55555', '2026-04-13', 'alice.m', 'hash_pwd_1', 'Comptable');

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `fichefrais`
--
ALTER TABLE `fichefrais`
  ADD CONSTRAINT `fk_fichefrais_etat` FOREIGN KEY (`idEtat`) REFERENCES `etat` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_fichefrais_LigneFraisHorsForfait` FOREIGN KEY (`idLigneFraisHorsForfait`) REFERENCES `fraishorsforfait` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_fichefrais_visiteur` FOREIGN KEY (`IDvisiteur`) REFERENCES `visiteur` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `lignefraisforfait`
--
ALTER TABLE `lignefraisforfait`
  ADD CONSTRAINT `fk_lff_fichefrais` FOREIGN KEY (`IDvisiteur`,`mois`) REFERENCES `fichefrais` (`IDvisiteur`, `mois`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_lff_fraisforfait` FOREIGN KEY (`IDfraisforfait`) REFERENCES `fraisforfait` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
