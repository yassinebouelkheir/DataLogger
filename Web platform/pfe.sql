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
  `NAME` varchar(24) NOT NULL,
  `VALUE` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `charges`
--

INSERT INTO `charges` (`ID`, `NAME`, `VALUE`) VALUES
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

CREATE TABLE `sensors` (
  `ID` int(11) NOT NULL,
  `VALUE` float NOT NULL,
  `UNIXDATE` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `sensors`
--

INSERT INTO `sensors` (`ID`, `VALUE`, `UNIXDATE`) VALUES
(1, 0, 1648582167),
(1, 0, 1648582167),
(1, 0, 1648582168),
(1, 0, 1648582168),
(1, 0, 1648582168),
(1, 0, 1648582168),
(1, 0, 1648582168),
(1, 0, 1648582168),
(1, 0, 1648582168),
(1, 0, 1648582168),
(2, 0, 1648582168),
(2, 0, 1648582169),
(2, 0, 1648582169),
(2, 0, 1648582169),
(2, 0, 1648582169),
(2, 0, 1648582169),
(2, 0, 1648582169),
(2, 0, 1648582169),
(2, 0, 1648582169),
(2, 0, 1648582170),
(3, 0, 1648582170),
(3, 0, 1648582170),
(3, 0, 1648582170),
(3, 0, 1648582170),
(3, 0, 1648582170),
(3, 0, 1648582170),
(3, 0, 1648582170),
(3, 0, 1648582171),
(3, 0, 1648582171),
(3, 0, 1648582171),
(4, 0, 1648582171),
(4, 0, 1648582171),
(4, 0, 1648582171),
(4, 0, 1648582171),
(4, 0, 1648582171),
(4, 0, 1648582171),
(4, 0, 1648582172),
(4, 0, 1648582172),
(4, 0, 1648582172),
(4, 0, 1648582172),
(5, 0, 1648582172),
(5, 0, 1648582172),
(5, 0, 1648582172),
(5, 0, 1648582173),
(5, 0, 1648582173),
(5, 0, 1648582173),
(5, 0, 1648582173),
(5, 0, 1648582173),
(5, 0, 1648582173),
(5, 0, 1648582173),
(6, 0, 1648582173),
(6, 0, 1648582173),
(6, 0, 1648582174),
(6, 0, 1648582174),
(6, 0, 1648582174),
(6, 0, 1648582174),
(6, 0, 1648582174),
(6, 0, 1648582174),
(6, 0, 1648582174),
(6, 0, 1648582174),
(7, 0, 1648582173),
(7, 0, 1648582173),
(7, 0, 1648582174),
(7, 0, 1648582174),
(7, 0, 1648582174),
(7, 0, 1648582174),
(7, 0, 1648582174),
(7, 0, 1648582174),
(7, 0, 1648582174),
(7, 0, 1648582174),
(8, 0, 1648582173),
(8, 0, 1648582173),
(8, 0, 1648582174),
(8, 0, 1648582174),
(8, 0, 1648582174),
(8, 0, 1648582174),
(8, 0, 1648582174),
(8, 0, 1648582174),
(8, 0, 1648582174),
(8, 0, 1648582174),
(9, 0, 1648582173),
(9, 0, 1648582173),
(9, 0, 1648582174),
(9, 0, 1648582174),
(9, 0, 1648582174),
(9, 0, 1648582174),
(9, 0, 1648582174),
(9, 0, 1648582174),
(9, 0, 1648582174),
(9, 0, 1648582174),
(10, 0, 1648582173),
(10, 0, 1648582173),
(10, 0, 1648582174),
(10, 0, 1648582174),
(10, 0, 1648582174),
(10, 0, 1648582174),
(10, 0, 1648582174),
(10, 0, 1648582174),
(10, 0, 1648582174),
(10, 0, 1648582174),
(11, 0, 1648582173),
(11, 0, 1648582173),
(11, 0, 1648582174),
(11, 0, 1648582174),
(11, 0, 1648582174),
(11, 0, 1648582174),
(11, 0, 1648582174),
(11, 0, 1648582174),
(11, 0, 1648582174),
(11, 0, 1648582174),
(12, 0, 1648582173),
(12, 0, 1648582173),
(12, 0, 1648582174),
(12, 0, 1648582174),
(12, 0, 1648582174),
(12, 0, 1648582174),
(12, 0, 1648582174),
(12, 0, 1648582174),
(12, 0, 1648582174),
(12, 0, 1648582174),
(13, 0, 1648582173),
(13, 0, 1648582173),
(13, 0, 1648582174),
(13, 0, 1648582174),
(13, 0, 1648582174),
(13, 0, 1648582174),
(13, 0, 1648582174),
(13, 0, 1648582174),
(13, 0, 1648582174),
(13, 0, 1648582174);

-- --------------------------------------------------------

--
-- Structure de la table `sensors_static`
--

CREATE TABLE `sensors_static` (
  `ID` int(11) NOT NULL,
  `VALUE` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `sensors_static`
--

INSERT INTO `sensors_static` (`ID`, `VALUE`) VALUES
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

CREATE TABLE `updatetime` (
  `ID` int(11) NOT NULL,
  `TIME` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `updatetime`
--

INSERT INTO `updatetime` (`ID`, `TIME`) VALUES
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
