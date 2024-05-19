-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : dim. 19 mai 2024 à 15:12
-- Version du serveur : 10.4.28-MariaDB
-- Version de PHP : 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `extrait`
--

-- --------------------------------------------------------

--
-- Structure de la table `centreec`
--

CREATE TABLE `centreec` (
  `id` int(11) NOT NULL,
  `nomCommune` varchar(255) NOT NULL,
  `codeCNI` varchar(255) NOT NULL,
  `timbre` varchar(255) DEFAULT NULL,
  `departement` varchar(255) NOT NULL,
  `region` varchar(255) NOT NULL,
  `codeRegion` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `centreec`
--

INSERT INTO `centreec` (`id`, `nomCommune`, `codeCNI`, `timbre`, `departement`, `region`, `codeRegion`) VALUES
(1, 'Guinguinéo', '476', NULL, 'Guinguinéo', 'Kaolack', '5'),
(2, 'Pikine', '1', NULL, 'Pikine', 'DAKAR', '1'),
(3, 'x', '1', NULL, 'x', 'DAKAR', '1'),
(4, 'w', '1', NULL, 'w', 'DAKAR', '1'),
(5, '1', '1', NULL, 'w', 'DAKAR', '1'),
(6, 'Baba Garage', '1', NULL, 'Bambey', 'DIOURBEL', '1'),
(7, 'Mbour', '1', NULL, 'Mbour', 'THIES', '1'),
(8, 'PIKINE', '212', NULL, 'PIKINE', 'DAKAR', '2');

-- --------------------------------------------------------

--
-- Structure de la table `demande`
--

CREATE TABLE `demande` (
  `id` int(11) NOT NULL,
  `idExtrait` int(11) NOT NULL,
  `idDelivreur` int(11) DEFAULT NULL,
  `idRetireur` int(11) DEFAULT NULL,
  `idCitoyen` int(11) NOT NULL,
  `date` date NOT NULL,
  `heure` time NOT NULL,
  `status` enum('VALIDE','REFUSE','EN COURS') NOT NULL,
  `token` varchar(255) DEFAULT NULL,
  `dateRetrait` date DEFAULT NULL,
  `heureRetrait` time DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `demande`
--

INSERT INTO `demande` (`id`, `idExtrait`, `idDelivreur`, `idRetireur`, `idCitoyen`, `date`, `heure`, `status`, `token`, `dateRetrait`, `heureRetrait`) VALUES
(1, 7, 34, 35, 32, '2024-04-09', '03:35:02', 'VALIDE', NULL, '2024-05-15', '02:36:50'),
(3, 11, 34, NULL, 33, '2024-04-28', '20:19:53', 'REFUSE', NULL, NULL, NULL),
(6, 1, 49, 51, 48, '2024-05-19', '03:41:35', 'VALIDE', NULL, '2024-05-19', '03:58:17');

-- --------------------------------------------------------

--
-- Structure de la table `extrait`
--

CREATE TABLE `extrait` (
  `id` int(11) NOT NULL,
  `numDansLeRegistre` int(11) NOT NULL,
  `dateDeLivrance` datetime NOT NULL,
  `paysNaissance` varchar(255) NOT NULL,
  `prenom` varchar(255) NOT NULL,
  `sexe` enum('MASCULIN','FEMININ') NOT NULL,
  `dateNaissance` date NOT NULL,
  `lieuNaissance` varchar(255) NOT NULL,
  `heureNaissance` time NOT NULL,
  `anneeRegistre` int(11) NOT NULL,
  `idPere` int(11) NOT NULL,
  `idMere` int(11) NOT NULL,
  `idCentreEc` int(11) NOT NULL,
  `idAgent` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `extrait`
--

INSERT INTO `extrait` (`id`, `numDansLeRegistre`, `dateDeLivrance`, `paysNaissance`, `prenom`, `sexe`, `dateNaissance`, `lieuNaissance`, `heureNaissance`, `anneeRegistre`, `idPere`, `idMere`, `idCentreEc`, `idAgent`) VALUES
(1, 123, '2021-04-09 03:29:45', 'Senegal', 'Aboubakry', 'MASCULIN', '2001-04-09', 'Guinguinéo', '01:29:46', 2001, 1, 1, 1, NULL),
(7, 321, '2024-05-06 00:00:00', 'Senegal', 'Modou', 'MASCULIN', '2024-05-06', 'Bambey', '02:44:00', 1999, 26, 26, 6, 34),
(11, 321, '2024-05-06 00:00:00', 'Senagal', 'Saly', 'FEMININ', '2024-05-06', 'Mbour', '03:13:00', 1970, 30, 30, 7, 34),
(12, 245, '2024-05-19 00:00:00', 'SENEGAL', 'BENJAMIN', 'MASCULIN', '2024-05-19', 'PIKINE', '01:48:00', 2024, 31, 31, 8, 49);

-- --------------------------------------------------------

--
-- Structure de la table `mere`
--

CREATE TABLE `mere` (
  `id` int(11) NOT NULL,
  `prenom` varchar(255) NOT NULL,
  `nom` varchar(255) NOT NULL,
  `numCNI` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `mere`
--

INSERT INTO `mere` (`id`, `prenom`, `nom`, `numCNI`) VALUES
(1, 'Sadio', 'GCBA', NULL),
(26, 'Faty', 'SALL', NULL),
(30, 'Fatou', 'Diop', NULL),
(31, 'ASTOU', 'DIOP', NULL);

-- --------------------------------------------------------

--
-- Structure de la table `pere`
--

CREATE TABLE `pere` (
  `id` int(11) NOT NULL,
  `prenom` varchar(255) NOT NULL,
  `nom` varchar(255) NOT NULL,
  `numCNI` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `pere`
--

INSERT INTO `pere` (`id`, `prenom`, `nom`, `numCNI`) VALUES
(1, 'Abdoulaye', 'BA', NULL),
(26, 'Aboubakry', 'BA', NULL),
(30, 'Ousmane', 'Tine', NULL),
(31, 'ALIOU', 'NDIAYE', NULL);

-- --------------------------------------------------------

--
-- Structure de la table `utilisateur`
--

CREATE TABLE `utilisateur` (
  `id` int(11) NOT NULL,
  `prenom` varchar(255) NOT NULL,
  `nom` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `telephone` varchar(255) NOT NULL,
  `motDePasse` varchar(255) DEFAULT NULL,
  `numCNI` varchar(255) NOT NULL,
  `type` enum('ADMIN','CITOYEN','AGENTMAIRIE','AGENTRETRAIT') NOT NULL,
  `date` date NOT NULL,
  `heure` time NOT NULL,
  `idAdmin` int(11) DEFAULT NULL,
  `actif` tinyint(1) NOT NULL,
  `token` varchar(255) NOT NULL,
  `otpCode` varchar(6) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `utilisateur`
--

INSERT INTO `utilisateur` (`id`, `prenom`, `nom`, `email`, `telephone`, `motDePasse`, `numCNI`, `type`, `date`, `heure`, `idAdmin`, `actif`, `token`, `otpCode`) VALUES
(32, 'Abdoulaye', 'BA', 'abdoulayeba@esp.sn', '777777777', '$2y$10$q0RE0t0PtURdMu0LKrnA4ey82ONLfbRqevLBCD41dHDTJMFTEItCa', '', 'CITOYEN', '2024-04-08', '06:06:58', NULL, 1, 'e4f6525e53cb6dcf71d39f582b1586c4', '339005'),
(33, 'Ndeye Coumba', 'Samb', 'ndeyecoumbasamb@esp.sn', '777777777', '$2y$10$FgO1fdWYpswb6jA7eq7wfemiEYWdWN7EU/WHFoIx6YUquYVHtrnC2', '', 'CITOYEN', '2024-04-28', '20:12:48', NULL, 1, '8ed492300ac19762fa5c0924b91bb05e', '337281'),
(34, 'Agent1', 'Mairie', 'agent1@mairie.sn', '777777777', '$2y$10$msG2OI9aLroUvrSdyCkLMOkaa5TesBL6uBwSocz2Jko5.rUY4bh1a', '', 'AGENTMAIRIE', '2024-05-05', '00:36:16', NULL, 1, '66a8419e0036ce562318010723e1fa89', '774642'),
(35, 'Agent1', 'Retrait', 'agent1@retrait.sn', '777777777', '$2y$10$AXp8ZWFHYkj67J77OIUfPOcPjjAuBW7Bkmyfh02MZjS9MtJdD1JMO', '', 'AGENTRETRAIT', '2024-05-14', '01:27:23', NULL, 1, 'dbc22f0078d8726451f77c039ca5c9b9', '910982'),
(46, 'Admin1', 'Admin', 'admin@system.sn', '777777777', '$2y$10$tXdJCo7aFZiPPQrFNX3VKePmL38mjg8dF3WgVS4uNW0s3tYCrtH.y', '', 'ADMIN', '2024-05-19', '03:15:47', NULL, 1, '$2y$10$EC.AaURlUCAxBWwOCIGNceRo.ijiTGvSQwdjTGR7LDz0FpbkcJG9S', '666060'),
(48, 'Aboubakry', 'BA', 'aboubakryba@esp.sn', '774954357', '$2y$10$Y9DZ/kUsDCL/WX4YgsmQSeuaUJdvdbSZM/L.GeRP/mQM4DoQaw1RC', '', 'CITOYEN', '2024-05-19', '03:39:28', NULL, 1, '$2y$10$3VEh2ArSTmhXwYhfagfniOt2xSqNVRn.lo.2plzIZh.09xN8lafiO', '824876'),
(49, 'Agent2', 'Mairie', 'agent2@mairie.sn', '771234567', '$2y$10$VH7z8UyRI0WeTk8et5j4U.0J4VUh.EI.V9bnPuUtr1Mjh3BfLGmsO', '1476200100000', 'AGENTMAIRIE', '2024-05-19', '03:44:13', 46, 1, 'ec088489ac800a6c9fbc4e7cfdc15608', '282049'),
(51, 'Agent2', 'Retrait', 'agent2@retrait.sn', '777654321', '$2y$10$9cOiqSe1xRRhIniyuXbmr.UFWWCwKXx2kQd6Y5X/Ley2CuosAxrEe', '1476199900000', 'AGENTRETRAIT', '2024-05-19', '03:54:32', 46, 1, 'fcf7bb1a2976a71fe803997bf7c1eaef', '699124');

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `centreec`
--
ALTER TABLE `centreec`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `demande`
--
ALTER TABLE `demande`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idExtrait` (`idExtrait`),
  ADD KEY `idDelivreur` (`idDelivreur`),
  ADD KEY `idRetireur` (`idRetireur`),
  ADD KEY `idCitoyen` (`idCitoyen`);

--
-- Index pour la table `extrait`
--
ALTER TABLE `extrait`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idPere` (`idPere`),
  ADD KEY `idMere` (`idMere`),
  ADD KEY `idCentreEc` (`idCentreEc`),
  ADD KEY `idAgent` (`idAgent`);

--
-- Index pour la table `mere`
--
ALTER TABLE `mere`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `pere`
--
ALTER TABLE `pere`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `utilisateur`
--
ALTER TABLE `utilisateur`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idAdmin` (`idAdmin`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `centreec`
--
ALTER TABLE `centreec`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT pour la table `demande`
--
ALTER TABLE `demande`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT pour la table `extrait`
--
ALTER TABLE `extrait`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT pour la table `mere`
--
ALTER TABLE `mere`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT pour la table `pere`
--
ALTER TABLE `pere`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT pour la table `utilisateur`
--
ALTER TABLE `utilisateur`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=52;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `demande`
--
ALTER TABLE `demande`
  ADD CONSTRAINT `demande_ibfk_1` FOREIGN KEY (`idExtrait`) REFERENCES `extrait` (`id`),
  ADD CONSTRAINT `demande_ibfk_2` FOREIGN KEY (`idDelivreur`) REFERENCES `utilisateur` (`id`),
  ADD CONSTRAINT `demande_ibfk_3` FOREIGN KEY (`idRetireur`) REFERENCES `utilisateur` (`id`),
  ADD CONSTRAINT `demande_ibfk_4` FOREIGN KEY (`idCitoyen`) REFERENCES `utilisateur` (`id`);

--
-- Contraintes pour la table `extrait`
--
ALTER TABLE `extrait`
  ADD CONSTRAINT `extrait_ibfk_1` FOREIGN KEY (`idPere`) REFERENCES `pere` (`id`),
  ADD CONSTRAINT `extrait_ibfk_2` FOREIGN KEY (`idMere`) REFERENCES `mere` (`id`),
  ADD CONSTRAINT `extrait_ibfk_3` FOREIGN KEY (`idCentreEc`) REFERENCES `centreec` (`id`),
  ADD CONSTRAINT `extrait_ibfk_4` FOREIGN KEY (`idAgent`) REFERENCES `utilisateur` (`id`);

--
-- Contraintes pour la table `utilisateur`
--
ALTER TABLE `utilisateur`
  ADD CONSTRAINT `utilisateur_ibfk_1` FOREIGN KEY (`idAdmin`) REFERENCES `utilisateur` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
