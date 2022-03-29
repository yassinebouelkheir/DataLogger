-- phpMyAdmin SQL Dump
-- version 5.0.4deb2
-- https://www.phpmyadmin.net/
--
-- Hôte : localhost:3306
-- Généré le : mar. 29 mars 2022 à 19:29
-- Version du serveur :  10.5.15-MariaDB-0+deb11u1
-- Version de PHP : 7.4.28

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `PFE`
--

-- --------------------------------------------------------

--
-- Structure de la table `ACCOUNTS`
--

CREATE TABLE `ACCOUNTS` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `ACCOUNTS`
--

INSERT INTO `ACCOUNTS` (`id`, `username`, `password`) VALUES
(1, 'admin', '21232f297a57a5a743894a0e4a801fc3'),
(2, 'user', 'ee11cbb19052e40b07aac0ca060c23ee');

-- --------------------------------------------------------

--
-- Structure de la table `CHARGES`
--

CREATE TABLE `CHARGES` (
  `ID` int(11) NOT NULL,
  `VALUE` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `CHARGES`
--

INSERT INTO `CHARGES` (`ID`, `VALUE`) VALUES
(22, 0),
(23, 0),
(24, 0),
(25, 0);

-- --------------------------------------------------------

--
-- Structure de la table `SENSORS`
--

CREATE TABLE `SENSORS` (
  `ID` int(11) NOT NULL,
  `VALUE` float NOT NULL,
  `UNIXDATE` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `SENSORS`
--

INSERT INTO `SENSORS` (`ID`, `VALUE`, `UNIXDATE`) VALUES
(57, 24.44, 1648582167),
(59, 21.02, 1648582167),
(58, 51.12, 1648582168),
(54, 0.24, 1648582168),
(55, 0, 1648582168),
(56, 4.98, 1648582168),
(57, 23.95, 1648582168),
(59, 0, 1648582168),
(58, 51.22, 1648582168),
(54, 0.23, 1648582168),
(55, 0, 1648582168),
(56, 4.98, 1648582169),
(57, 22.48, 1648582169),
(59, 14.27, 1648582169),
(58, 51.22, 1648582169),
(54, 0.22, 1648582169),
(55, 0, 1648582169),
(56, 4.98, 1648582169),
(57, 24.93, 1648582169),
(59, 56.4, 1648582170),
(58, 51.22, 1648582170),
(54, 0.23, 1648582170),
(55, 0, 1648582170),
(56, 4.98, 1648582170),
(57, 24.93, 1648582170),
(59, 0, 1648582170),
(58, 51.22, 1648582170),
(54, 0.23, 1648582171),
(55, 0, 1648582171),
(56, 4.96, 1648582171),
(57, 23.46, 1648582171),
(59, 56.5, 1648582171),
(58, 51.22, 1648582171),
(54, 0.23, 1648582171),
(55, 0, 1648582171),
(56, 4.98, 1648582171),
(57, 24.44, 1648582172),
(59, 0, 1648582172),
(58, 51.22, 1648582172),
(54, 0.23, 1648582172),
(55, 0, 1648582172),
(56, 4.96, 1648582172),
(57, 23.46, 1648582172),
(59, 1.17, 1648582173),
(58, 51.22, 1648582173),
(54, 0.23, 1648582173),
(55, 0, 1648582173),
(56, 4.98, 1648582173),
(57, 23.95, 1648582173),
(59, 46.53, 1648582173),
(58, 51.32, 1648582173),
(54, 0.23, 1648582173),
(55, 0, 1648582174),
(56, 4.98, 1648582174),
(57, 23.95, 1648582174),
(59, 56.6, 1648582174),
(58, 51.32, 1648582174),
(54, 0.23, 1648582174),
(55, 0, 1648582174),
(56, 4.98, 1648582174);

-- --------------------------------------------------------

--
-- Structure de la table `SENSORS_STATIC`
--

CREATE TABLE `SENSORS_STATIC` (
  `ID` int(11) NOT NULL,
  `VALUE` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `SENSORS_STATIC`
--

INSERT INTO `SENSORS_STATIC` (`ID`, `VALUE`) VALUES
(54, 0),
(55, 0),
(56, 0),
(57, 0),
(58, 0),
(59, 0);

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `ACCOUNTS`
--
ALTER TABLE `ACCOUNTS`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `ACCOUNTS`
--
ALTER TABLE `ACCOUNTS`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
