-- phpMyAdmin SQL Dump
-- version 5.0.4
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : Dim 27 mars 2022 à 20:47
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

CREATE TABLE `accounts` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `accounts`
--

INSERT INTO `accounts` (`id`, `username`, `password`) VALUES
(1, 'admin', '21232f297a57a5a743894a0e4a801fc3'),
(2, 'user', 'ee11cbb19052e40b07aac0ca060c23ee');

-- --------------------------------------------------------

--
-- Structure de la table `charges`
--

CREATE TABLE `charges` (
  `ID` int(11) NOT NULL,
  `VALUE` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `charges`
--

INSERT INTO `charges` (`ID`, `VALUE`) VALUES
(22, 0),
(23, 0),
(24, 0),
(26, 0);

-- --------------------------------------------------------

--
-- Structure de la table `sensors`
--

CREATE TABLE `sensors` (
  `ID` int(11) NOT NULL,
  `VALUE` float NOT NULL,
  `UNIXDATE` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `sensors`
--

INSERT INTO `sensors` (`ID`, `VALUE`, `UNIXDATE`) VALUES
(54, 12.3, 1648379280),
(54, 13.5, 1648379340),
(54, 14.8, 1648379400),
(54, 15.4, 1648379460),
(54, 21.1, 1648379520),
(54, 23.6, 1648379580),
(54, 24.2, 1648379640),
(54, 25, 1648379700),
(54, 24.8, 1648379760),
(54, 23.7, 1648379820),
(55, 20.3, 1648379280),
(55, 21.5, 1648379340),
(55, 30.8, 1648379400),
(55, 40.4, 1648379460),
(55, 56.1, 1648379520),
(55, 26.6, 1648379580),
(55, 24.2, 1648379640),
(55, 96, 1648379700),
(55, 58.8, 1648379760),
(55, 85.7, 1648379820),
(56, 10.3, 1648379280),
(56, 19.5, 1648379340),
(56, 15.8, 1648379400),
(56, 10.4, 1648379460),
(56, 9.1, 1648379520),
(56, 2.6, 1648379580),
(56, 16.2, 1648379640),
(56, 19, 1648379700),
(56, 22.8, 1648379760),
(56, 25, 1648379820),
(57, 18, 1648379280),
(57, 17, 1648379340),
(57, 10.4, 1648379400),
(57, 15.5, 1648379460),
(57, 19.6, 1648379520),
(57, 20.7, 1648379580),
(57, 22.9, 1648379640),
(57, 23.1, 1648379700),
(57, 24.3, 1648379760),
(57, 25, 1648379820),
(58, 40, 1648379280),
(58, 44, 1648379340),
(58, 48, 1648379400),
(58, 49, 1648379460),
(58, 52, 1648379520),
(58, 53, 1648379580),
(58, 58, 1648379640),
(58, 60, 1648379700),
(58, 61, 1648379760),
(58, 62, 1648379820),
(59, 56, 1648379280),
(59, 54, 1648379340),
(59, 50, 1648379400),
(59, 48, 1648379460),
(59, 47, 1648379520),
(59, 43, 1648379580),
(59, 39, 1648379640),
(59, 35, 1648379700),
(59, 33, 1648379760),
(59, 31, 1648379820);

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `accounts`
--
ALTER TABLE `accounts`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `accounts`
--
ALTER TABLE `accounts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
