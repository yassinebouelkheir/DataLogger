-- phpMyAdmin SQL Dump
-- version 5.0.4
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : Dim 01 mai 2022 à 15:27
-- Version du serveur :  10.4.16-MariaDB
-- Version de PHP : 7.4.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `pfe`
--

-- --------------------------------------------------------

--
-- Structure de la table `accounts`
--

CREATE TABLE `ACCOUNTS` (
  `ID` int(11) NOT NULL,
  `USERNAME` varchar(50) NOT NULL,
  `PASSWORD` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `accounts`
--

INSERT INTO `ACCOUNTS` (`ID`, `USERNAME`, `PASSWORD`) VALUES
(1, 'admin', '21232f297a57a5a743894a0e4a801fc3'),
(2, 'user', 'ee11cbb19052e40b07aac0ca060c23ee');

-- --------------------------------------------------------

--
-- Structure de la table `charges`
--

CREATE TABLE `CHARGES` (
  `ID` int(11) NOT NULL,
  `NAME` varchar(24) NOT NULL,
  `VALUE` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `charges`
--

INSERT INTO `CHARGES` (`ID`, `NAME`, `VALUE`) VALUES
(1, 'Charge 1', 0),
(2, 'Charge 2', 0),
(3, 'Charge 3', 0),
(4, 'Charge 4', 0),
(5, 'Charge 5', 0),
(6, 'Charge 6', 0),
(7, 'Charge 7', 0),
(8, 'Charge 8', 0);

-- --------------------------------------------------------

--
-- Structure de la table `sensors`
--

CREATE TABLE `SENSORS` (
  `ID` int(11) NOT NULL,
  `VALUE` float NOT NULL,
  `UNIXDATE` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `sensors`
--

INSERT INTO `SENSORS` (`ID`, `VALUE`, `UNIXDATE`) VALUES
(1, 0, 1648582100),
(1, 0, 1648582220),
(1, 0, 1648582340),
(1, 0, 1648582460),
(1, 0, 1648582580),
(1, 0, 1648582700),
(1, 0, 1648582820),
(1, 0, 1648582940),
(1, 0, 1648583060),
(1, 0, 1648583180),
(2, 0, 1648582100),
(2, 0, 1648582220),
(2, 0, 1648582340),
(2, 0, 1648582460),
(2, 0, 1648582580),
(2, 0, 1648582700),
(2, 0, 1648582820),
(2, 0, 1648582940),
(2, 0, 1648583060),
(2, 0, 1648583180),
(3, 0, 1648582100),
(3, 0, 1648582220),
(3, 0, 1648582340),
(3, 0, 1648582460),
(3, 0, 1648582580),
(3, 0, 1648582700),
(3, 0, 1648582820),
(3, 0, 1648582940),
(3, 0, 1648583060),
(3, 0, 1648583180),
(4, 0, 1648582100),
(4, 0, 1648582220),
(4, 0, 1648582340),
(4, 0, 1648582460),
(4, 0, 1648582580),
(4, 0, 1648582700),
(4, 0, 1648582820),
(4, 0, 1648582940),
(4, 0, 1648583060),
(4, 0, 1648583180),
(5, 0, 1648582100),
(5, 0, 1648582220),
(5, 0, 1648582340),
(5, 0, 1648582460),
(5, 0, 1648582580),
(5, 0, 1648582700),
(5, 0, 1648582820),
(5, 0, 1648582940),
(5, 0, 1648583060),
(5, 0, 1648583180),
(6, 0, 1648582100),
(6, 0, 1648582220),
(6, 0, 1648582340),
(6, 0, 1648582460),
(6, 0, 1648582580),
(6, 0, 1648582700),
(6, 0, 1648582820),
(6, 0, 1648582940),
(6, 0, 1648583060),
(6, 0, 1648583180),
(7, 0, 1648582100),
(7, 0, 1648582220),
(7, 0, 1648582340),
(7, 0, 1648582460),
(7, 0, 1648582580),
(7, 0, 1648582700),
(7, 0, 1648582820),
(7, 0, 1648582940),
(7, 0, 1648583060),
(7, 0, 1648583180),
(8, 0, 1648582100),
(8, 0, 1648582220),
(8, 0, 1648582340),
(8, 0, 1648582460),
(8, 0, 1648582580),
(8, 0, 1648582700),
(8, 0, 1648582820),
(8, 0, 1648582940),
(8, 0, 1648583060),
(8, 0, 1648583180),
(9, 0, 1648582100),
(9, 0, 1648582220),
(9, 0, 1648582340),
(9, 0, 1648582460),
(9, 0, 1648582580),
(9, 0, 1648582700),
(9, 0, 1648582820),
(9, 0, 1648582940),
(9, 0, 1648583060),
(9, 0, 1648583180),
(10, 0, 1648582100),
(10, 0, 1648582220),
(10, 0, 1648582340),
(10, 0, 1648582460),
(10, 0, 1648582580),
(10, 0, 1648582700),
(10, 0, 1648582820),
(10, 0, 1648582940),
(10, 0, 1648583060),
(10, 0, 1648583180),
(11, 0, 1648582100),
(11, 0, 1648582220),
(11, 0, 1648582340),
(11, 0, 1648582460),
(11, 0, 1648582580),
(11, 0, 1648582700),
(11, 0, 1648582820),
(11, 0, 1648582940),
(11, 0, 1648583060),
(11, 0, 1648583180),
(12, 0, 1648582100),
(12, 0, 1648582220),
(12, 0, 1648582340),
(12, 0, 1648582460),
(12, 0, 1648582580),
(12, 0, 1648582700),
(12, 0, 1648582820),
(12, 0, 1648582940),
(12, 0, 1648583060),
(12, 0, 1648583180),
(13, 0, 1648582100),
(13, 0, 1648582220),
(13, 0, 1648582340),
(13, 0, 1648582460),
(13, 0, 1648582580),
(13, 0, 1648582700),
(13, 0, 1648582820),
(13, 0, 1648582940),
(13, 0, 1648583060),
(13, 0, 1648583180);

-- --------------------------------------------------------

--
-- Structure de la table `sensors_static`
--

CREATE TABLE `SENSORS_STATIC` (
  `ID` int(11) NOT NULL,
  `VALUE` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `sensors_static`
--

INSERT INTO `SENSORS_STATIC` (`ID`, `VALUE`) VALUES
(1, 0),
(2, 0),
(3, 0),
(4, 0),
(5, 0),
(6, 0),
(7, 0),
(8, 0),
(9, 0),
(10, 0),
(11, 0),
(12, 0),
(13, 0);

-- --------------------------------------------------------

--
-- Structure de la table `updatetime`
--

CREATE TABLE `UPDATETIME` (
  `ID` int(11) NOT NULL,
  `TIME` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `updatetime`
--

INSERT INTO `UPDATETIME` (`ID`, `TIME`) VALUES
(1, 2),
(2, 2),
(3, 2),
(4, 2),
(5, 2),
(6, 2),
(7, 2);

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `accounts`
--
ALTER TABLE `ACCOUNTS`
  ADD PRIMARY KEY (`ID`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `accounts`
--
ALTER TABLE `ACCOUNTS`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
