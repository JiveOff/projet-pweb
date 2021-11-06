-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Hôte : db:3306
-- Généré le : sam. 06 nov. 2021 à 02:56
-- Version du serveur : 5.7.35
-- Version de PHP : 7.4.20

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `app_db`
--

-- --------------------------------------------------------

--
-- Structure de la table `facture`
--

CREATE TABLE `facture` (
  `id` int(11) NOT NULL,
  `id_user` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `id_vehic` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `date_d` date NOT NULL,
  `date_f` date NOT NULL,
  `montant` double NOT NULL,
  `payee` tinyint(1) NOT NULL,
  `cloture` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `facture`
--

INSERT INTO `facture` (`id`, `id_user`, `id_vehic`, `date_d`, `date_f`, `montant`, `payee`, `cloture`) VALUES
(17, '2', '22', '2021-11-22', '2021-11-27', 425, 0, 0),
(19, '2', '14', '2021-11-01', '2021-11-03', 320, 1, 0),
(20, '2', '20', '2021-11-08', '2021-11-15', 525, 0, 1),
(21, '2', '19', '2021-11-07', '2021-11-08', 235, 1, 1),
(22, '1', '20', '2021-11-07', '2021-11-09', 150, 0, 1);

-- --------------------------------------------------------

--
-- Structure de la table `user`
--

CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `nom` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `user`
--

INSERT INTO `user` (`id`, `nom`, `password`, `email`) VALUES
(1, 'admin', '$2y$13$9yDpvhv4qX2UCbren5wEheTpSVOk1Tij97FnmZcOTS6xkDzK5h4fq', 'admin@location.com'),
(2, 'test1', '$2y$13$9yDpvhv4qX2UCbren5wEheTpSVOk1Tij97FnmZcOTS6xkDzK5h4fq', 'test1@location.com'),
(3, 'test2', '$2y$13$9yDpvhv4qX2UCbren5wEheTpSVOk1Tij97FnmZcOTS6xkDzK5h4fq', 'test2@location.com'),
(4, 'test3', '$2y$13$9yDpvhv4qX2UCbren5wEheTpSVOk1Tij97FnmZcOTS6xkDzK5h4fq', 'test3@location.com');

-- --------------------------------------------------------

--
-- Structure de la table `vehicule`
--

CREATE TABLE `vehicule` (
  `id` int(11) NOT NULL,
  `modele` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `carac` longtext COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '(DC2Type:json)',
  `quantite` int(11) NOT NULL,
  `image` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `prix` double NOT NULL,
  `disponible` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `vehicule`
--

INSERT INTO `vehicule` (`id`, `modele`, `carac`, `quantite`, `image`, `prix`, `disponible`) VALUES
(14, 'T-Roc', '{\"marque\":\"BMW\",\"boite\":\"Manuelle\",\"couleur\":\"Or\",\"moteur\":\"Essence\",\"nbPlaces\":\"4\",\"nbPortes\":\"2\"}', 5, '/img/voitures/BMWT-Roc-6185c8ba9d56d.png', 160, 1),
(15, '2 Series Grand Tourer', '{\"marque\":\"BMW\",\"boite\":\"Manuelle\",\"couleur\":\"Rouge\",\"moteur\":\"Essence\",\"nbPlaces\":\"5\",\"nbPortes\":\"5\"}', 6, '/img/voitures/BMW2-Series-Grand-Tourer-6185c9150c1c6.png', 160, 0),
(16, 'Classe C', '{\"marque\":\"Mercedes-Benz\",\"boite\":\"Automatique\",\"couleur\":\"Argent\",\"moteur\":\"Essence\",\"nbPlaces\":\"5\",\"nbPortes\":\"4\"}', 3, '/img/voitures/Mercedes-BenzClasse-C-6185c94a0080c.png', 265, 1),
(17, 'Corolla', '{\"marque\":\"Toyota\",\"boite\":\"Automatique\",\"couleur\":\"Argent\",\"moteur\":\"Hybride\",\"nbPlaces\":\"5\",\"nbPortes\":\"5\"}', 1, '/img/voitures/ToyotaCorolla-6185c9ad2912c.png', 160, 0),
(18, '3008', '{\"marque\":\"Peugeot\",\"boite\":\"Automatique\",\"couleur\":\"Gris\",\"moteur\":\"Essence\",\"nbPlaces\":\"5\",\"nbPortes\":\"4\"}', 4, '/img/voitures/Peugeot3008-6185ca1c84874.png', 195, 1),
(19, 'Série 2 Cabrio', '{\"marque\":\"BMW\",\"boite\":\"Manuelle\",\"couleur\":\"Blanche\",\"moteur\":\"Essence\",\"nbPlaces\":\"4\",\"nbPortes\":\"2\"}', 1, '/img/voitures/BMWSerie-2-Cabrio-6185cab564c76.png', 235, 1),
(20, '500', '{\"marque\":\"Fiat\",\"boite\":\"Manuelle\",\"couleur\":\"Blanche\",\"moteur\":\"Essence\",\"nbPlaces\":\"4\",\"nbPortes\":\"3\"}', 12, '/img/voitures/Fiat500-6185cb176f9c5.png', 75, 1),
(21, '208', '{\"marque\":\"Peugeot\",\"boite\":\"Manuelle\",\"couleur\":\"Bleue\",\"moteur\":\"Essence\",\"nbPlaces\":\"5\",\"nbPortes\":\"5\"}', 3, '/img/voitures/Peugeot208-6185cb46b625f.png', 95, 1),
(22, 'EQ Fortwo', '{\"marque\":\"Smart\",\"boite\":\"Automatique\",\"couleur\":\"Blanche\",\"moteur\":\"Electrique\",\"nbPlaces\":\"2\",\"nbPortes\":\"2\"}', 9, '/img/voitures/SmartEQ-Fortwo-6185cb811c0f7.png', 85, 1),
(23, 'Série 1', '{\"marque\":\"BMW\",\"boite\":\"Manuelle\",\"couleur\":\"Blanche\",\"moteur\":\"Essence\",\"nbPlaces\":\"5\",\"nbPortes\":\"4\"}', 4, '/img/voitures/BMWSerie-1-6185d0a9809ef.png', 175, 1);

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `facture`
--
ALTER TABLE `facture`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `vehicule`
--
ALTER TABLE `vehicule`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `facture`
--
ALTER TABLE `facture`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT pour la table `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT pour la table `vehicule`
--
ALTER TABLE `vehicule`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
