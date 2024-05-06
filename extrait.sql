-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : lun. 06 mai 2024 à 05:30
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
(7, 'Mbour', '1', NULL, 'Mbour', 'THIES', '1');

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
  `token` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `demande`
--

INSERT INTO `demande` (`id`, `idExtrait`, `idDelivreur`, `idRetireur`, `idCitoyen`, `date`, `heure`, `status`, `token`) VALUES
(1, 1, 34, NULL, 32, '2024-04-09', '03:35:02', 'EN COURS', '567545'),
(3, 1, 34, NULL, 33, '2024-04-28', '20:19:53', 'REFUSE', NULL);

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
(7, 321, '2024-05-06 00:00:00', 'Senegal', 'Modou', 'MASCULIN', '2024-05-06', 'Bambey', '02:44:00', 2022, 26, 26, 6, 34),
(11, 321, '2024-05-06 00:00:00', 'Senagal', 'Saly', 'MASCULIN', '2024-05-06', 'Mbour', '03:13:00', 1970, 30, 30, 7, 34);

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
(30, 'Fatou', 'Diop', NULL);

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
(30, 'Ousmane', 'Tine', NULL);

-- --------------------------------------------------------

--
-- Structure de la table `retrait`
--

CREATE TABLE `retrait` (
  `id` int(11) NOT NULL,
  `idDemande` int(11) NOT NULL,
  `date` date NOT NULL,
  `heure` time NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
  `photoCNI` varchar(255) DEFAULT NULL,
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

INSERT INTO `utilisateur` (`id`, `prenom`, `nom`, `email`, `telephone`, `motDePasse`, `photoCNI`, `numCNI`, `type`, `date`, `heure`, `idAdmin`, `actif`, `token`, `otpCode`) VALUES
(29, 'Abdoulaye', 'BA', 'baa70390@gmail.com', '774954357', '$2y$10$wd0L9V0IVpmFHHtzU/hWTeP1Fq1FQxGlRAmMpxFRRiBjQcaV47x6O', NULL, '', 'CITOYEN', '2024-04-08', '03:33:40', NULL, 1, '86ba0bcacd35cb697af21220f3a56040', '652786'),
(30, 'Abdoulaye', 'BA', 'baa70391@gmail.com', '774954357', '$2y$10$Ngv95ZvQ.a6a9TXo9Mpx0uV70tRL6Z2SPEYs19Gdt.gjBjcOsLNhK', NULL, '', 'CITOYEN', '2024-04-08', '03:36:54', NULL, 1, '933056fd300d6de18dfc5d6de2212e5d', NULL),
(31, 'Abdoulaye', 'BA', 'baa70392@gmail.com', '774954357', '$2y$10$0/UDhCD9gGWKKJw588IkQePR2joDrdnNw0ZS3yXtcB/K29nljDnBu', NULL, '', 'CITOYEN', '2024-04-08', '03:38:42', NULL, 1, '3fc5fbf7793a94fb12cd616ca7d87f86', NULL),
(32, 'Aboubakry', 'BA', 'aboubakryba@esp.sn', '774954357', '$2y$10$q0RE0t0PtURdMu0LKrnA4ey82ONLfbRqevLBCD41dHDTJMFTEItCa', NULL, '', 'CITOYEN', '2024-04-08', '06:06:58', NULL, 1, 'e4f6525e53cb6dcf71d39f582b1586c4', '291006'),
(33, 'Ndeye Coumba', 'Samb', 'ndeyecoumba@esp.sn', '777777777', '$2y$10$FgO1fdWYpswb6jA7eq7wfemiEYWdWN7EU/WHFoIx6YUquYVHtrnC2', NULL, '', 'CITOYEN', '2024-04-28', '20:12:48', NULL, 1, '8ed492300ac19762fa5c0924b91bb05e', '337281'),
(34, 'Agent1', 'Mairie', 'agent1@mairie.sn', '777777777', '$2y$10$msG2OI9aLroUvrSdyCkLMOkaa5TesBL6uBwSocz2Jko5.rUY4bh1a', NULL, '', 'AGENTMAIRIE', '2024-05-05', '00:36:16', NULL, 1, '66a8419e0036ce562318010723e1fa89', '774642');

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
-- Index pour la table `retrait`
--
ALTER TABLE `retrait`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idDemande` (`idDemande`);

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT pour la table `demande`
--
ALTER TABLE `demande`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT pour la table `extrait`
--
ALTER TABLE `extrait`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT pour la table `mere`
--
ALTER TABLE `mere`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT pour la table `pere`
--
ALTER TABLE `pere`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT pour la table `retrait`
--
ALTER TABLE `retrait`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `utilisateur`
--
ALTER TABLE `utilisateur`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

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
-- Contraintes pour la table `retrait`
--
ALTER TABLE `retrait`
  ADD CONSTRAINT `retrait_ibfk_1` FOREIGN KEY (`idDemande`) REFERENCES `demande` (`id`);

--
-- Contraintes pour la table `utilisateur`
--
ALTER TABLE `utilisateur`
  ADD CONSTRAINT `utilisateur_ibfk_1` FOREIGN KEY (`idAdmin`) REFERENCES `utilisateur` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
