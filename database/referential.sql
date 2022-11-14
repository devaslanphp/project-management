-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : lun. 14 nov. 2022 à 15:13
-- Version du serveur : 10.4.22-MariaDB
-- Version de PHP : 8.1.2

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `helper`
--

-- --------------------------------------------------------

--
-- Structure de la table `ticket_priorities`
--

CREATE TABLE `ticket_priorities` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `color` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '#cecece',
  `is_default` tinyint(1) NOT NULL DEFAULT 0,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `ticket_priorities`
--

INSERT INTO `ticket_priorities` (`id`, `name`, `color`, `is_default`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1, 'Low', '#008000', 0, NULL, '2022-11-14 12:06:56', '2022-11-14 12:06:56'),
(2, 'Normal', '#CECECE', 1, NULL, '2022-11-14 12:06:56', '2022-11-14 12:06:56'),
(3, 'High', '#ff0000', 0, NULL, '2022-11-14 12:06:56', '2022-11-14 12:06:56');

-- --------------------------------------------------------

--
-- Structure de la table `ticket_statuses`
--

CREATE TABLE `ticket_statuses` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `color` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '#cecece',
  `is_default` tinyint(1) NOT NULL DEFAULT 0,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `order` int(11) NOT NULL DEFAULT 1,
  `project_id` bigint(20) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `ticket_statuses`
--

INSERT INTO `ticket_statuses` (`id`, `name`, `color`, `is_default`, `deleted_at`, `created_at`, `updated_at`, `order`, `project_id`) VALUES
(1, 'Backlog', '#cecece', 1, NULL, '2022-11-14 12:06:56', '2022-11-14 12:06:56', 1, NULL),
(2, 'In progress', '#ff7f00', 0, NULL, '2022-11-14 12:06:56', '2022-11-14 12:06:56', 2, NULL),
(3, 'Done', '#008000', 0, NULL, '2022-11-14 12:06:56', '2022-11-14 12:06:56', 3, NULL),
(4, 'Archived', '#ff0000', 0, NULL, '2022-11-14 12:06:56', '2022-11-14 12:06:56', 4, NULL);

-- --------------------------------------------------------

--
-- Structure de la table `ticket_types`
--

CREATE TABLE `ticket_types` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `icon` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `color` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '#cecece',
  `is_default` tinyint(1) NOT NULL DEFAULT 0,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `ticket_types`
--

INSERT INTO `ticket_types` (`id`, `name`, `icon`, `color`, `is_default`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1, 'Task', 'heroicon-o-check-circle', '#00FFFF', 1, NULL, '2022-11-14 12:06:56', '2022-11-14 12:06:56'),
(2, 'Evolution', 'heroicon-o-clipboard-list', '#008000', 0, NULL, '2022-11-14 12:06:56', '2022-11-14 12:06:56'),
(3, 'Bug', 'heroicon-o-x', '#ff0000', 0, NULL, '2022-11-14 12:06:56', '2022-11-14 12:06:56');

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `ticket_priorities`
--
ALTER TABLE `ticket_priorities`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `ticket_statuses`
--
ALTER TABLE `ticket_statuses`
  ADD PRIMARY KEY (`id`),
  ADD KEY `ticket_statuses_project_id_foreign` (`project_id`);

--
-- Index pour la table `ticket_types`
--
ALTER TABLE `ticket_types`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `ticket_priorities`
--
ALTER TABLE `ticket_priorities`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT pour la table `ticket_statuses`
--
ALTER TABLE `ticket_statuses`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT pour la table `ticket_types`
--
ALTER TABLE `ticket_types`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Contraintes pour les tables déchargées
--
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
