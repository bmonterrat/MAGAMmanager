-- phpMyAdmin SQL Dump
-- version 4.1.14
-- http://www.phpmyadmin.net
--
-- Client :  127.0.0.1
-- Généré le :  Mar 31 Mai 2016 à 09:02
-- Version du serveur :  5.6.17
-- Version de PHP :  5.5.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de données :  `mfa`
--

-- --------------------------------------------------------

--
-- Structure de la table `activite`
--

CREATE TABLE IF NOT EXISTS `activite` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_groupe` int(11) NOT NULL,
  `nom` text NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id_groupe` (`id_groupe`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=11 ;

--
-- Contenu de la table `activite`
--

INSERT INTO `activite` (`id`, `id_groupe`, `nom`) VALUES
(1, 1, 'dictée corrigée'),
(2, 1, 'exercices de syntaxe'),
(3, 1, 'exercices d''orthographe sur feuille'),
(5, 1, 'activité 2'),
(6, 3, 'Activité 1'),
(7, 3, 'Activité 2'),
(9, 3, 'adverbes sans temps'),
(10, 3, 'adverbes avec temps');

-- --------------------------------------------------------

--
-- Structure de la table `application`
--

CREATE TABLE IF NOT EXISTS `application` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_groupe_pr` int(11) DEFAULT NULL,
  `id_calcul_et_fusion` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `calcul_is` (`id_calcul_et_fusion`),
  KEY `groupe_is` (`id_groupe_pr`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=22 ;

--
-- Contenu de la table `application`
--

INSERT INTO `application` (`id`, `id_groupe_pr`, `id_calcul_et_fusion`) VALUES
(1, 1, 1),
(2, 5, 2),
(15, NULL, 3),
(16, NULL, 4);

-- --------------------------------------------------------

--
-- Structure de la table `calcul_et_fusion`
--

CREATE TABLE IF NOT EXISTS `calcul_et_fusion` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nom` text NOT NULL,
  `calcul_ou_fusion` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=7 ;

--
-- Contenu de la table `calcul_et_fusion`
--

INSERT INTO `calcul_et_fusion` (`id`, `nom`, `calcul_ou_fusion`) VALUES
(1, 'produit des valeurs', 0),
(2, 'différence entre valeurs', 0),
(3, 'valeur min', 1),
(4, 'valeur max', 1),
(5, 'produit des valeurs', 1),
(6, 'somme des valeurs', 1);

-- --------------------------------------------------------

--
-- Structure de la table `fusion_references`
--

CREATE TABLE IF NOT EXISTS `fusion_references` (
  `reference` int(11) NOT NULL,
  `referenced` int(11) NOT NULL,
  `poids` float NOT NULL,
  PRIMARY KEY (`reference`,`referenced`),
  KEY `referenced_is` (`referenced`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Contenu de la table `fusion_references`
--

INSERT INTO `fusion_references` (`reference`, `referenced`, `poids`) VALUES
(15, 1, 0),
(15, 2, 0),
(16, 2, 0),
(16, 15, 0);

-- --------------------------------------------------------

--
-- Structure de la table `groupe_activite`
--

CREATE TABLE IF NOT EXISTS `groupe_activite` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nom` text NOT NULL,
  `open` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

--
-- Contenu de la table `groupe_activite`
--

INSERT INTO `groupe_activite` (`id`, `nom`, `open`) VALUES
(1, 'activités en classe', 0),
(3, 'Activités sur Moodle', 1);

-- --------------------------------------------------------

--
-- Structure de la table `groupe_propriete`
--

CREATE TABLE IF NOT EXISTS `groupe_propriete` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nom` text NOT NULL,
  `for_activite` text NOT NULL,
  `for_utilisateur` text NOT NULL,
  `min_act` float NOT NULL,
  `max_act` float NOT NULL,
  `pas_act` float NOT NULL,
  `defaut_act` float NOT NULL,
  `min_uti` float NOT NULL,
  `max_uti` float NOT NULL,
  `pas_uti` float NOT NULL,
  `defaut_uti` float NOT NULL,
  `open` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=8 ;

--
-- Contenu de la table `groupe_propriete`
--

INSERT INTO `groupe_propriete` (`id`, `nom`, `for_activite`, `for_utilisateur`, `min_act`, `max_act`, `pas_act`, `defaut_act`, `min_uti`, `max_uti`, `pas_uti`, `defaut_uti`, `open`) VALUES
(1, 'Types de joueur', 'Décrit à quel point l''activité correspond au type de joueur.', 'Décrit à quel point l''utilisateur correspond au type de joueur.', 0, 1, 1, 0, -10, 20, 0.1, 0, 1),
(5, 'Prérequis !', 'test1 ', 'test 2 ', -1, 0, 1, 0, 0, 0.5, 0.5, 0.1, 0),
(7, 'Activités réalisées', 'La valeur désigne l''intérêt apporté par le fait de faire une activité plusieurs fois.\r\n0 = on ne peut faire l''activité qu''une seule fois.\r\n1 = on peut recommencer l''activité autant de fois que souhaité', 'La valeur représente le nombre de fois qu''un utilisateur a réalisé une activité.', 0, 1, 0, 0, 0, 10, 0, 0, 0);

-- --------------------------------------------------------

--
-- Structure de la table `groupe_utilisateur`
--

CREATE TABLE IF NOT EXISTS `groupe_utilisateur` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nom` text NOT NULL,
  `open` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

--
-- Contenu de la table `groupe_utilisateur`
--

INSERT INTO `groupe_utilisateur` (`id`, `nom`, `open`) VALUES
(1, 'conditions adaptées', 1),
(2, 'adaptation didactique', 0);

-- --------------------------------------------------------

--
-- Structure de la table `propriete`
--

CREATE TABLE IF NOT EXISTS `propriete` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_groupe` int(11) NOT NULL,
  `nom` text NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id_groupe` (`id_groupe`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=32 ;

--
-- Contenu de la table `propriete`
--

INSERT INTO `propriete` (`id`, `id_groupe`, `nom`) VALUES
(5, 1, 'Seeker'),
(7, 1, 'Survivor'),
(11, 1, 'Daredevil'),
(12, 1, 'Mastermind'),
(13, 1, 'Conqueror'),
(14, 1, 'Sociolizer'),
(15, 1, 'Achiever'),
(23, 7, 'répéter activité');

-- --------------------------------------------------------

--
-- Structure de la table `utilisateur`
--

CREATE TABLE IF NOT EXISTS `utilisateur` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_groupe` int(11) NOT NULL,
  `nom` text NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id_groupe` (`id_groupe`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=20 ;

--
-- Contenu de la table `utilisateur`
--

INSERT INTO `utilisateur` (`id`, `id_groupe`, `nom`) VALUES
(1, 1, 'Amandine'),
(2, 1, 'Etienne'),
(3, 1, 'Marcel'),
(4, 1, 'Julien'),
(5, 1, 'Paulette'),
(12, 2, 'Alfred2'),
(14, 1, 'Alfred'),
(15, 2, 'Amandine2'),
(16, 2, 'Etienne2'),
(17, 2, 'Julien2'),
(18, 2, 'Marcel2'),
(19, 2, 'Paulette2');

-- --------------------------------------------------------

--
-- Structure de la table `valeur_activite`
--

CREATE TABLE IF NOT EXISTS `valeur_activite` (
  `id_activite` int(11) NOT NULL,
  `id_propriete` int(11) NOT NULL,
  `valeur` float NOT NULL,
  PRIMARY KEY (`id_activite`,`id_propriete`),
  KEY `id_propriete` (`id_propriete`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Contenu de la table `valeur_activite`
--

INSERT INTO `valeur_activite` (`id_activite`, `id_propriete`, `valeur`) VALUES
(1, 5, 1),
(1, 7, 0),
(1, 11, 0),
(1, 12, 0),
(1, 13, 0),
(1, 14, 0),
(1, 15, 0),
(1, 23, 0),
(2, 5, 0),
(2, 7, 0),
(2, 11, 0),
(2, 12, 0),
(2, 13, 0),
(2, 14, 0),
(2, 15, 0),
(2, 23, 0),
(3, 5, 0),
(3, 7, 0),
(3, 11, 0),
(3, 12, 0),
(3, 13, 0),
(3, 14, 0),
(3, 15, 0),
(3, 23, 0),
(5, 5, 0),
(5, 7, 0),
(5, 11, 0),
(5, 12, 0),
(5, 13, 0),
(5, 14, 0),
(5, 15, 0),
(5, 23, 0),
(6, 5, 0),
(6, 7, 0),
(6, 11, 0),
(6, 12, 0),
(6, 13, 0),
(6, 14, 0),
(6, 15, 0),
(6, 23, 0),
(7, 5, 0),
(7, 7, 0),
(7, 11, 0),
(7, 12, 0),
(7, 13, 0),
(7, 14, 0),
(7, 15, 0),
(7, 23, 0),
(9, 5, 1),
(9, 7, 0),
(9, 11, 0),
(9, 12, 0),
(9, 13, 0),
(9, 14, 0),
(9, 15, 0),
(9, 23, 0),
(10, 5, 0),
(10, 7, 1),
(10, 11, 0),
(10, 12, 0),
(10, 13, 0),
(10, 14, 0),
(10, 15, 0),
(10, 23, 0);

-- --------------------------------------------------------

--
-- Structure de la table `valeur_utilisateur`
--

CREATE TABLE IF NOT EXISTS `valeur_utilisateur` (
  `id_utilisateur` int(11) NOT NULL,
  `id_propriete` int(11) NOT NULL,
  `valeur` float NOT NULL,
  PRIMARY KEY (`id_utilisateur`,`id_propriete`),
  KEY `id_propriete` (`id_propriete`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Contenu de la table `valeur_utilisateur`
--

INSERT INTO `valeur_utilisateur` (`id_utilisateur`, `id_propriete`, `valeur`) VALUES
(1, 5, 10),
(1, 7, 0),
(1, 11, 0),
(1, 12, 0),
(1, 13, 0),
(1, 14, 0),
(1, 15, 0),
(1, 23, 0),
(2, 5, 0),
(2, 7, 0),
(2, 11, 0),
(2, 12, 0),
(2, 13, 0),
(2, 14, 0),
(2, 15, 0),
(2, 23, 0),
(3, 5, 0),
(3, 7, 0),
(3, 11, 0),
(3, 12, 0),
(3, 13, 0),
(3, 14, 0),
(3, 15, 0),
(3, 23, 0),
(4, 5, 0),
(4, 7, 0),
(4, 11, 0),
(4, 12, 0),
(4, 13, 0),
(4, 14, 0),
(4, 15, 0),
(4, 23, 0),
(5, 5, 0),
(5, 7, 0),
(5, 11, 10),
(5, 12, 0),
(5, 13, 0),
(5, 14, 0),
(5, 15, 0),
(5, 23, 0),
(12, 5, 3),
(12, 7, 0),
(12, 11, 0),
(12, 12, 0),
(12, 13, 17),
(12, 14, -5),
(12, 15, 0),
(12, 23, 0),
(14, 5, 0),
(14, 7, 0),
(14, 11, 0),
(14, 12, 0),
(14, 13, 0),
(14, 14, 0),
(14, 15, 0),
(14, 23, 0),
(15, 5, 0),
(15, 7, 0),
(15, 11, 0),
(15, 12, 0),
(15, 13, 0),
(15, 14, 0),
(15, 15, 0),
(15, 23, 0),
(16, 5, 0),
(16, 7, 0),
(16, 11, 0),
(16, 12, 0),
(16, 13, 0),
(16, 14, 0),
(16, 15, 0),
(16, 23, 0),
(17, 5, 0),
(17, 7, 0),
(17, 11, 0),
(17, 12, 0),
(17, 13, 0),
(17, 14, 0),
(17, 15, 0),
(17, 23, 0),
(18, 5, 0),
(18, 7, 0),
(18, 11, 0),
(18, 12, 0),
(18, 13, 0),
(18, 14, 0),
(18, 15, 0),
(18, 23, 0),
(19, 5, 0),
(19, 7, 0),
(19, 11, 0),
(19, 12, 0),
(19, 13, 0),
(19, 14, 0),
(19, 15, 0),
(19, 23, 0);

-- --------------------------------------------------------

--
-- Structure de la table `visu`
--

CREATE TABLE IF NOT EXISTS `visu` (
  `id_user` int(11) NOT NULL,
  `id_groupe_act` int(11) NOT NULL,
  KEY `id_user_id` (`id_user`),
  KEY `id_groupe_act_id` (`id_groupe_act`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Contenu de la table `visu`
--

INSERT INTO `visu` (`id_user`, `id_groupe_act`) VALUES
(2, 3);

--
-- Contraintes pour les tables exportées
--

--
-- Contraintes pour la table `activite`
--
ALTER TABLE `activite`
  ADD CONSTRAINT `activite_ibfk_1` FOREIGN KEY (`id_groupe`) REFERENCES `groupe_activite` (`id`);

--
-- Contraintes pour la table `application`
--
ALTER TABLE `application`
  ADD CONSTRAINT `calcul_is` FOREIGN KEY (`id_calcul_et_fusion`) REFERENCES `calcul_et_fusion` (`id`),
  ADD CONSTRAINT `groupe_is` FOREIGN KEY (`id_groupe_pr`) REFERENCES `groupe_propriete` (`id`);

--
-- Contraintes pour la table `fusion_references`
--
ALTER TABLE `fusion_references`
  ADD CONSTRAINT `referenced_is` FOREIGN KEY (`referenced`) REFERENCES `application` (`id`),
  ADD CONSTRAINT `reference_is` FOREIGN KEY (`reference`) REFERENCES `application` (`id`);

--
-- Contraintes pour la table `propriete`
--
ALTER TABLE `propriete`
  ADD CONSTRAINT `propriete_ibfk_1` FOREIGN KEY (`id_groupe`) REFERENCES `groupe_propriete` (`id`);

--
-- Contraintes pour la table `utilisateur`
--
ALTER TABLE `utilisateur`
  ADD CONSTRAINT `utilisateur_ibfk_1` FOREIGN KEY (`id_groupe`) REFERENCES `groupe_utilisateur` (`id`);

--
-- Contraintes pour la table `valeur_activite`
--
ALTER TABLE `valeur_activite`
  ADD CONSTRAINT `valeur_activite_ibfk_1` FOREIGN KEY (`id_activite`) REFERENCES `activite` (`id`),
  ADD CONSTRAINT `valeur_activite_ibfk_2` FOREIGN KEY (`id_propriete`) REFERENCES `propriete` (`id`);

--
-- Contraintes pour la table `valeur_utilisateur`
--
ALTER TABLE `valeur_utilisateur`
  ADD CONSTRAINT `valeur_utilisateur_ibfk_1` FOREIGN KEY (`id_utilisateur`) REFERENCES `utilisateur` (`id`),
  ADD CONSTRAINT `valeur_utilisateur_ibfk_2` FOREIGN KEY (`id_propriete`) REFERENCES `propriete` (`id`);

--
-- Contraintes pour la table `visu`
--
ALTER TABLE `visu`
  ADD CONSTRAINT `id_groupe_act_id` FOREIGN KEY (`id_groupe_act`) REFERENCES `groupe_activite` (`id`),
  ADD CONSTRAINT `id_user_id` FOREIGN KEY (`id_user`) REFERENCES `utilisateur` (`id`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
