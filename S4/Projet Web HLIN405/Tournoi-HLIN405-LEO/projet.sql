-- phpMyAdmin SQL Dump
-- version 4.9.5deb2
-- https://www.phpmyadmin.net/
--
-- Hôte : localhost:3306
-- Généré le : ven. 14 mai 2021 à 23:57
-- Version du serveur :  8.0.25-0ubuntu0.20.04.1
-- Version de PHP : 7.4.3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `projet`
--

-- --------------------------------------------------------

--
-- Structure de la table `Equipes`
--

CREATE TABLE `Equipes` (
  `Id_Equipe` int NOT NULL,
  `Nom_Equipe` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `Id_Tournois` int NOT NULL,
  `Niveau_Equipe` int NOT NULL,
  `Adresse_Equipe` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `Mail_Equipe` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `Tel_Equipe` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `Nom_Cap_Equipe` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `Prenom_Cap_Equipe` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `validee` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `Equipes`
--

INSERT INTO `Equipes` (`Id_Equipe`, `Nom_Equipe`, `Id_Tournois`, `Niveau_Equipe`, `Adresse_Equipe`, `Mail_Equipe`, `Tel_Equipe`, `Nom_Cap_Equipe`, `Prenom_Cap_Equipe`, `validee`) VALUES
(1, 'Bleue', 5, 10, 'a', 'b', '01', 'AB', 'CD', 0),
(2, 'Verte', 5, 20, 'e', 'f', '02', 'EF', 'GH', 0),
(5, 'Jaune', 5, 30, 'i', 'j', '03', 'IJ', 'KL', 0),
(6, 'Rouge', 5, 40, 'm', 'n', '04', 'MN', 'OP', 0),
(7, 'Violette', 5, 50, 'q', 'r', '05', 'QR', 'ST', 0);

-- --------------------------------------------------------

--
-- Structure de la table `Evenements`
--

CREATE TABLE `Evenements` (
  `Id_Evenement` int NOT NULL,
  `Nom_Evenement` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `Description_Evenement` text COLLATE utf8mb4_general_ci NOT NULL,
  `Lieu_Evenement` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `Date_Debut` date NOT NULL,
  `Date_Fin` date NOT NULL,
  `Categorie` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `Gestionnaire` varchar(20) COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `Evenements`
--

INSERT INTO `Evenements` (`Id_Evenement`, `Nom_Evenement`, `Description_Evenement`, `Lieu_Evenement`, `Date_Debut`, `Date_Fin`, `Categorie`, `Gestionnaire`) VALUES
(1, 'Tournoi des Six Nations 2021', 'Le Tournoi des Six Nations est une compétition de rugby à XV, disputée chaque année en février et mars par les équipes masculines d\'Angleterre, d\'Écosse, de France, du pays de Galles, d\'Irlande et d\'Italie.', 'Montpellier', '2021-02-06', '2021-03-20', 'Rugby', 'Rugby'),
(2, 'Top 14 2020-21', 'Le championnat de France de rugby à XV, dénommé Top 14 depuis 2005, est une compétition annuelle mettant aux prises les meilleurs clubs professionnels de rugby à XV en France. Le vainqueur du championnat de France remporte comme trophée le bouclier de Brennus.', 'France', '2020-09-04', '2021-06-25', 'Rugby', 'Rugby'),
(5, 'Pro D2 2020-21', 'Le championnat de France de rugby à XV de 2e division, également appelé Pro D2Note 1 depuis 2001, est le deuxième échelon des compétitions nationales de rugby à XV en France. C\'est une compétition qui constitue l\'antichambre de l\'élite, le Top 14. Initialement disputée entre clubs amateurs, elle est devenue professionnelle en 2000 connaissant plusieurs restructurations successives pour arriver à un format resserré à seize clubs.', 'France', '2020-09-03', '2021-06-05', 'Rugby', 'Rugby'),
(6, 'Internationaux de France de tennis 2021', 'Les Internationaux de France de tennis (ou Roland-Garros) 2021 se déroulent du 30 mai au 13 juin 2021 au Stade Roland-Garros à Paris. Il s\'agit de la 120e édition', 'Paris', '2021-05-30', '2021-06-13', 'Tennis', 'Tennis'),
(11, 'Coupe du monde de football 2022', 'La Coupe du monde de football 2022 est la 22e édition de la Coupe du monde de football, compétition organisée par la FIFA et qui réunit les meilleures sélections nationales. Elle se déroulera au Qatar du 21 novembre au 18 décembre 20221, jour de la fête nationale du Qatar et une semaine avant Noël, avec une estimation du marché télévisuel potentiel à 3,2 milliards de téléspectateurs', 'Qatar', '2022-11-21', '2022-12-18', 'Foot', 'Foot'),
(126, 'Test', 'Ceci est une description test', 'Test-Ville', '2021-05-25', '2021-08-20', 'Foot', 'Foot');

-- --------------------------------------------------------

--
-- Structure de la table `Gestionnaires`
--

CREATE TABLE `Gestionnaires` (
  `login` varchar(20) COLLATE utf8mb4_general_ci NOT NULL,
  `mdp` varchar(20) COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `Gestionnaires`
--

INSERT INTO `Gestionnaires` (`login`, `mdp`) VALUES
('Foot', 'foot34'),
('Rugby', 'rugby34'),
('Tennis', 'tennis34');

-- --------------------------------------------------------

--
-- Structure de la table `Matchs`
--

CREATE TABLE `Matchs` (
  `Id_Match` int NOT NULL,
  `Id_Equipe_A` int NOT NULL,
  `Score_A` int NOT NULL,
  `Id_Equipe_B` int NOT NULL,
  `Score_B` int NOT NULL,
  `Date_Maj` timestamp NULL DEFAULT NULL,
  `Id_Tournoi` int NOT NULL,
  `Phase` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `Matchs`
--

INSERT INTO `Matchs` (`Id_Match`, `Id_Equipe_A`, `Score_A`, `Id_Equipe_B`, `Score_B`, `Date_Maj`, `Id_Tournoi`, `Phase`) VALUES
(8, 1, 1, 5, 3, '2021-04-30 14:14:56', 9, 1),
(9, 6, 0, 2, 1, '2021-04-30 15:15:13', 9, 1),
(10, 2, 1, 7, 2, '2021-04-30 15:15:21', 9, 1);

-- --------------------------------------------------------

--
-- Structure de la table `Tournois`
--

CREATE TABLE `Tournois` (
  `Id_Tournoi` int NOT NULL,
  `Id_Evenement` int NOT NULL,
  `Nom_Tournoi` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `Nombre_Equipes_Tournoi` int NOT NULL,
  `Plus_Infos` mediumtext CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `Tournois`
--

INSERT INTO `Tournois` (`Id_Tournoi`, `Id_Evenement`, `Nom_Tournoi`, `Nombre_Equipes_Tournoi`, `Plus_Infos`) VALUES
(5, 1, 'Tournoi des Six Nations 2021', 6, 'La compétition se déroule comme chaque année sous forme de tournoi toutes rondes à un tour, chacune des six nations participantes affronte toutes les autres. Les trois équipes qui ont en 2021 l\'avantage de jouer un match de plus à domicile que les autres sont l\'Angleterre, l\'Écosse et l\'Italie.'),
(9, 2, 'Top 14 2020-21', 14, 'La saison 2020-2021 de Top 14 est la 122e édition du championnat de France de rugby à XV. Elle oppose les quatorze meilleures équipes de rugby à XV françaises. ((Champion 2020 Toulouse))'),
(10, 5, 'Pro D2 2020-21', 16, 'À la suite de l\'interruption de la saison 2019-2020, aucune équipe n\'est promue en Top 14 ni reléguée en Fédérale 1 : les seize mêmes clubs disputent le championnat.'),
(13, 6, 'Simple Messieurs', 128, ''),
(14, 6, 'Simple Dames', 128, ''),
(15, 6, 'Double Dames', 64, ''),
(16, 6, 'Double Messieurs', 64, ''),
(19, 11, 'Coupe du monde de football 2022', 32, 'La Coupe du monde de 2022 est la septième et dernière édition du mondial à compter 32 participants (depuis 1998). En 2018, la FIFA envisage cependant la possibilité d\'anticiper le passage du tournoi à 48 équipes prévu pour 2026 et de l\'appliquer en 20222,3, mais elle renonce à cette idée dès le printemps 2019 en raison de trop grandes difficultés à surmonter pour modifier et adapter une organisation initialement prévue pour accueillir 32 équipes et non 484.'),
(170, 126, 'Test', 64, 'Rubrique plus d\'informations test');

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `Equipes`
--
ALTER TABLE `Equipes`
  ADD PRIMARY KEY (`Id_Equipe`),
  ADD KEY `Id_Tournois` (`Id_Tournois`);

--
-- Index pour la table `Evenements`
--
ALTER TABLE `Evenements`
  ADD PRIMARY KEY (`Id_Evenement`),
  ADD KEY `Gestionnaire` (`Gestionnaire`);

--
-- Index pour la table `Gestionnaires`
--
ALTER TABLE `Gestionnaires`
  ADD PRIMARY KEY (`login`);

--
-- Index pour la table `Matchs`
--
ALTER TABLE `Matchs`
  ADD PRIMARY KEY (`Id_Match`),
  ADD KEY `Id_Equipe_A` (`Id_Equipe_A`),
  ADD KEY `Id_Equipe_B` (`Id_Equipe_B`),
  ADD KEY `Id_Tournoi` (`Id_Tournoi`);

--
-- Index pour la table `Tournois`
--
ALTER TABLE `Tournois`
  ADD PRIMARY KEY (`Id_Tournoi`),
  ADD KEY `Id_Evenement` (`Id_Evenement`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `Equipes`
--
ALTER TABLE `Equipes`
  MODIFY `Id_Equipe` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT pour la table `Evenements`
--
ALTER TABLE `Evenements`
  MODIFY `Id_Evenement` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=127;

--
-- AUTO_INCREMENT pour la table `Matchs`
--
ALTER TABLE `Matchs`
  MODIFY `Id_Match` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT pour la table `Tournois`
--
ALTER TABLE `Tournois`
  MODIFY `Id_Tournoi` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=172;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `Equipes`
--
ALTER TABLE `Equipes`
  ADD CONSTRAINT `Equipes_ibfk_1` FOREIGN KEY (`Id_Tournois`) REFERENCES `Tournois` (`Id_Tournoi`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Contraintes pour la table `Evenements`
--
ALTER TABLE `Evenements`
  ADD CONSTRAINT `Evenements_ibfk_1` FOREIGN KEY (`Gestionnaire`) REFERENCES `Gestionnaires` (`login`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Contraintes pour la table `Matchs`
--
ALTER TABLE `Matchs`
  ADD CONSTRAINT `Matchs_ibfk_1` FOREIGN KEY (`Id_Equipe_A`) REFERENCES `Equipes` (`Id_Equipe`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `Matchs_ibfk_2` FOREIGN KEY (`Id_Equipe_B`) REFERENCES `Equipes` (`Id_Equipe`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `Matchs_ibfk_3` FOREIGN KEY (`Id_Tournoi`) REFERENCES `Tournois` (`Id_Tournoi`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Contraintes pour la table `Tournois`
--
ALTER TABLE `Tournois`
  ADD CONSTRAINT `Tournois_ibfk_1` FOREIGN KEY (`Id_Evenement`) REFERENCES `Evenements` (`Id_Evenement`) ON DELETE RESTRICT ON UPDATE RESTRICT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
