-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : lun. 09 jan. 2023 à 15:11
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
-- Structure de la table `activities`
--

CREATE TABLE `activities` (
                              `id` bigint(20) UNSIGNED NOT NULL,
                              `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
                              `description` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
                              `created_at` timestamp NULL DEFAULT NULL,
                              `updated_at` timestamp NULL DEFAULT NULL,
                              `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `epics`
--

CREATE TABLE `epics` (
                         `id` bigint(20) UNSIGNED NOT NULL,
                         `project_id` bigint(20) UNSIGNED NOT NULL,
                         `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
                         `starts_at` date NOT NULL,
                         `ends_at` date NOT NULL,
                         `created_at` timestamp NULL DEFAULT NULL,
                         `updated_at` timestamp NULL DEFAULT NULL,
                         `deleted_at` timestamp NULL DEFAULT NULL,
                         `parent_id` bigint(20) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
                               `id` bigint(20) UNSIGNED NOT NULL,
                               `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
                               `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
                               `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
                               `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
                               `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
                               `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `jobs`
--

CREATE TABLE `jobs` (
                        `id` bigint(20) UNSIGNED NOT NULL,
                        `queue` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
                        `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
                        `attempts` tinyint(3) UNSIGNED NOT NULL,
                        `reserved_at` int(10) UNSIGNED DEFAULT NULL,
                        `available_at` int(10) UNSIGNED NOT NULL,
                        `created_at` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `media`
--

CREATE TABLE `media` (
                         `id` bigint(20) UNSIGNED NOT NULL,
                         `model_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
                         `model_id` bigint(20) UNSIGNED NOT NULL,
                         `uuid` char(36) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
                         `collection_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
                         `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
                         `file_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
                         `mime_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
                         `disk` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
                         `conversions_disk` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
                         `size` bigint(20) UNSIGNED NOT NULL,
                         `manipulations` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL,
                         `custom_properties` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL,
                         `generated_conversions` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL,
                         `responsive_images` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL,
                         `order_column` int(10) UNSIGNED DEFAULT NULL,
                         `created_at` timestamp NULL DEFAULT NULL,
                         `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `migrations`
--

CREATE TABLE `migrations` (
                              `id` int(10) UNSIGNED NOT NULL,
                              `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
                              `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `model_has_permissions`
--

CREATE TABLE `model_has_permissions` (
                                         `permission_id` bigint(20) UNSIGNED NOT NULL,
                                         `model_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
                                         `model_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `model_has_roles`
--

CREATE TABLE `model_has_roles` (
                                   `role_id` bigint(20) UNSIGNED NOT NULL,
                                   `model_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
                                   `model_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `notifications`
--

CREATE TABLE `notifications` (
                                 `id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
                                 `type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
                                 `notifiable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
                                 `notifiable_id` bigint(20) UNSIGNED NOT NULL,
                                 `data` text COLLATE utf8mb4_unicode_ci NOT NULL,
                                 `read_at` timestamp NULL DEFAULT NULL,
                                 `created_at` timestamp NULL DEFAULT NULL,
                                 `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `password_resets`
--

CREATE TABLE `password_resets` (
                                   `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
                                   `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
                                   `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `pending_user_emails`
--

CREATE TABLE `pending_user_emails` (
                                       `id` bigint(20) UNSIGNED NOT NULL,
                                       `user_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
                                       `user_id` bigint(20) UNSIGNED NOT NULL,
                                       `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
                                       `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
                                       `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `permissions`
--

CREATE TABLE `permissions` (
                               `id` bigint(20) UNSIGNED NOT NULL,
                               `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
                               `guard_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
                               `created_at` timestamp NULL DEFAULT NULL,
                               `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `personal_access_tokens`
--

CREATE TABLE `personal_access_tokens` (
                                          `id` bigint(20) UNSIGNED NOT NULL,
                                          `tokenable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
                                          `tokenable_id` bigint(20) UNSIGNED NOT NULL,
                                          `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
                                          `token` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
                                          `abilities` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
                                          `last_used_at` timestamp NULL DEFAULT NULL,
                                          `expires_at` timestamp NULL DEFAULT NULL,
                                          `created_at` timestamp NULL DEFAULT NULL,
                                          `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `projects`
--

CREATE TABLE `projects` (
                            `id` bigint(20) UNSIGNED NOT NULL,
                            `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
                            `description` longtext COLLATE utf8mb4_unicode_ci DEFAULT NULL,
                            `owner_id` bigint(20) UNSIGNED NOT NULL,
                            `status_id` bigint(20) UNSIGNED NOT NULL,
                            `deleted_at` timestamp NULL DEFAULT NULL,
                            `created_at` timestamp NULL DEFAULT NULL,
                            `updated_at` timestamp NULL DEFAULT NULL,
                            `ticket_prefix` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
                            `status_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'default'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `project_favorites`
--

CREATE TABLE `project_favorites` (
                                     `id` bigint(20) UNSIGNED NOT NULL,
                                     `user_id` bigint(20) UNSIGNED NOT NULL,
                                     `project_id` bigint(20) UNSIGNED NOT NULL,
                                     `created_at` timestamp NULL DEFAULT NULL,
                                     `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `project_statuses`
--

CREATE TABLE `project_statuses` (
                                    `id` bigint(20) UNSIGNED NOT NULL,
                                    `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
                                    `color` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '#cecece',
                                    `is_default` tinyint(1) NOT NULL DEFAULT 0,
                                    `deleted_at` timestamp NULL DEFAULT NULL,
                                    `created_at` timestamp NULL DEFAULT NULL,
                                    `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `project_users`
--

CREATE TABLE `project_users` (
                                 `id` bigint(20) UNSIGNED NOT NULL,
                                 `user_id` bigint(20) UNSIGNED NOT NULL,
                                 `project_id` bigint(20) UNSIGNED NOT NULL,
                                 `role` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
                                 `created_at` timestamp NULL DEFAULT NULL,
                                 `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `roles`
--

CREATE TABLE `roles` (
                         `id` bigint(20) UNSIGNED NOT NULL,
                         `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
                         `guard_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
                         `created_at` timestamp NULL DEFAULT NULL,
                         `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `role_has_permissions`
--

CREATE TABLE `role_has_permissions` (
                                        `permission_id` bigint(20) UNSIGNED NOT NULL,
                                        `role_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `settings`
--

CREATE TABLE `settings` (
                            `id` bigint(20) UNSIGNED NOT NULL,
                            `group` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
                            `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
                            `locked` tinyint(1) NOT NULL,
                            `payload` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL,
                            `created_at` timestamp NULL DEFAULT NULL,
                            `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `socialite_users`
--

CREATE TABLE `socialite_users` (
                                   `id` bigint(20) UNSIGNED NOT NULL,
                                   `user_id` bigint(20) UNSIGNED NOT NULL,
                                   `provider` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
                                   `provider_id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
                                   `created_at` timestamp NULL DEFAULT NULL,
                                   `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `tickets`
--

CREATE TABLE `tickets` (
                           `id` bigint(20) UNSIGNED NOT NULL,
                           `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
                           `content` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
                           `owner_id` bigint(20) UNSIGNED NOT NULL,
                           `responsible_id` bigint(20) UNSIGNED DEFAULT NULL,
                           `status_id` bigint(20) UNSIGNED NOT NULL,
                           `project_id` bigint(20) UNSIGNED NOT NULL,
                           `deleted_at` timestamp NULL DEFAULT NULL,
                           `created_at` timestamp NULL DEFAULT NULL,
                           `updated_at` timestamp NULL DEFAULT NULL,
                           `code` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
                           `type_id` bigint(20) UNSIGNED NOT NULL,
                           `order` int(11) NOT NULL DEFAULT 0,
                           `priority_id` bigint(20) UNSIGNED NOT NULL,
                           `estimation` double(8,2) DEFAULT NULL,
                           `epic_id` bigint(20) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `ticket_activities`
--

CREATE TABLE `ticket_activities` (
                                     `id` bigint(20) UNSIGNED NOT NULL,
                                     `ticket_id` bigint(20) UNSIGNED NOT NULL,
                                     `old_status_id` bigint(20) UNSIGNED NOT NULL,
                                     `new_status_id` bigint(20) UNSIGNED NOT NULL,
                                     `user_id` bigint(20) UNSIGNED NOT NULL,
                                     `created_at` timestamp NULL DEFAULT NULL,
                                     `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `ticket_comments`
--

CREATE TABLE `ticket_comments` (
                                   `id` bigint(20) UNSIGNED NOT NULL,
                                   `ticket_id` bigint(20) UNSIGNED NOT NULL,
                                   `user_id` bigint(20) UNSIGNED NOT NULL,
                                   `content` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
                                   `deleted_at` timestamp NULL DEFAULT NULL,
                                   `created_at` timestamp NULL DEFAULT NULL,
                                   `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `ticket_hours`
--

CREATE TABLE `ticket_hours` (
                                `id` bigint(20) UNSIGNED NOT NULL,
                                `ticket_id` bigint(20) UNSIGNED NOT NULL,
                                `user_id` bigint(20) UNSIGNED NOT NULL,
                                `value` double(8,2) NOT NULL,
                                `created_at` timestamp NULL DEFAULT NULL,
                                `updated_at` timestamp NULL DEFAULT NULL,
                                `comment` longtext COLLATE utf8mb4_unicode_ci DEFAULT NULL,
                                `activity_id` bigint(20) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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

-- --------------------------------------------------------

--
-- Structure de la table `ticket_relations`
--

CREATE TABLE `ticket_relations` (
                                    `id` bigint(20) UNSIGNED NOT NULL,
                                    `ticket_id` bigint(20) UNSIGNED NOT NULL,
                                    `relation_id` bigint(20) UNSIGNED NOT NULL,
                                    `type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
                                    `sort` int(11) NOT NULL DEFAULT 1,
                                    `created_at` timestamp NULL DEFAULT NULL,
                                    `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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

-- --------------------------------------------------------

--
-- Structure de la table `ticket_subscribers`
--

CREATE TABLE `ticket_subscribers` (
                                      `id` bigint(20) UNSIGNED NOT NULL,
                                      `user_id` bigint(20) UNSIGNED NOT NULL,
                                      `ticket_id` bigint(20) UNSIGNED NOT NULL,
                                      `created_at` timestamp NULL DEFAULT NULL,
                                      `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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

-- --------------------------------------------------------

--
-- Structure de la table `time_sheets`
--

CREATE TABLE `time_sheets` (
                               `id` bigint(20) UNSIGNED NOT NULL,
                               `user_id` bigint(20) UNSIGNED NOT NULL,
                               `project_id` bigint(20) UNSIGNED DEFAULT NULL,
                               `task` longtext COLLATE utf8mb4_unicode_ci DEFAULT NULL,
                               `created_at` timestamp NULL DEFAULT NULL,
                               `updated_at` timestamp NULL DEFAULT NULL,
                               `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `time_sheet_cells`
--

CREATE TABLE `time_sheet_cells` (
                                    `id` bigint(20) UNSIGNED NOT NULL,
                                    `time_sheet_id` bigint(20) UNSIGNED NOT NULL,
                                    `value` double(8,2) NOT NULL,
                                    `is_trip` tinyint(1) NOT NULL DEFAULT 0,
                                    `comment` longtext COLLATE utf8mb4_unicode_ci DEFAULT NULL,
                                    `created_at` timestamp NULL DEFAULT NULL,
                                    `updated_at` timestamp NULL DEFAULT NULL,
                                    `date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `users`
--

CREATE TABLE `users` (
                         `id` bigint(20) UNSIGNED NOT NULL,
                         `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
                         `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
                         `email_verified_at` timestamp NULL DEFAULT NULL,
                         `password` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
                         `two_factor_secret` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
                         `two_factor_recovery_codes` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
                         `two_factor_confirmed_at` timestamp NULL DEFAULT NULL,
                         `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
                         `created_at` timestamp NULL DEFAULT NULL,
                         `updated_at` timestamp NULL DEFAULT NULL,
                         `deleted_at` timestamp NULL DEFAULT NULL,
                         `creation_token` char(36) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `activities`
--
ALTER TABLE `activities`
    ADD PRIMARY KEY (`id`);

--
-- Index pour la table `epics`
--
ALTER TABLE `epics`
    ADD PRIMARY KEY (`id`),
    ADD KEY `epics_project_id_foreign` (`project_id`),
    ADD KEY `epics_parent_id_foreign` (`parent_id`);

--
-- Index pour la table `failed_jobs`
--
ALTER TABLE `failed_jobs`
    ADD PRIMARY KEY (`id`),
    ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Index pour la table `jobs`
--
ALTER TABLE `jobs`
    ADD PRIMARY KEY (`id`),
    ADD KEY `jobs_queue_index` (`queue`);

--
-- Index pour la table `media`
--
ALTER TABLE `media`
    ADD PRIMARY KEY (`id`),
    ADD UNIQUE KEY `media_uuid_unique` (`uuid`),
    ADD KEY `media_model_type_model_id_index` (`model_type`,`model_id`),
    ADD KEY `media_order_column_index` (`order_column`);

--
-- Index pour la table `migrations`
--
ALTER TABLE `migrations`
    ADD PRIMARY KEY (`id`);

--
-- Index pour la table `model_has_permissions`
--
ALTER TABLE `model_has_permissions`
    ADD PRIMARY KEY (`permission_id`,`model_id`,`model_type`),
    ADD KEY `model_has_permissions_model_id_model_type_index` (`model_id`,`model_type`);

--
-- Index pour la table `model_has_roles`
--
ALTER TABLE `model_has_roles`
    ADD PRIMARY KEY (`role_id`,`model_id`,`model_type`),
    ADD KEY `model_has_roles_model_id_model_type_index` (`model_id`,`model_type`);

--
-- Index pour la table `notifications`
--
ALTER TABLE `notifications`
    ADD PRIMARY KEY (`id`),
    ADD KEY `notifications_notifiable_type_notifiable_id_index` (`notifiable_type`,`notifiable_id`);

--
-- Index pour la table `password_resets`
--
ALTER TABLE `password_resets`
    ADD KEY `password_resets_email_index` (`email`);

--
-- Index pour la table `pending_user_emails`
--
ALTER TABLE `pending_user_emails`
    ADD PRIMARY KEY (`id`),
    ADD KEY `pending_user_emails_user_type_user_id_index` (`user_type`,`user_id`),
    ADD KEY `pending_user_emails_email_index` (`email`);

--
-- Index pour la table `permissions`
--
ALTER TABLE `permissions`
    ADD PRIMARY KEY (`id`),
    ADD UNIQUE KEY `permissions_name_guard_name_unique` (`name`,`guard_name`);

--
-- Index pour la table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
    ADD PRIMARY KEY (`id`),
    ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
    ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`);

--
-- Index pour la table `projects`
--
ALTER TABLE `projects`
    ADD PRIMARY KEY (`id`),
    ADD KEY `projects_owner_id_foreign` (`owner_id`),
    ADD KEY `projects_status_id_foreign` (`status_id`);

--
-- Index pour la table `project_favorites`
--
ALTER TABLE `project_favorites`
    ADD PRIMARY KEY (`id`),
    ADD KEY `project_favorites_user_id_foreign` (`user_id`),
    ADD KEY `project_favorites_project_id_foreign` (`project_id`);

--
-- Index pour la table `project_statuses`
--
ALTER TABLE `project_statuses`
    ADD PRIMARY KEY (`id`);

--
-- Index pour la table `project_users`
--
ALTER TABLE `project_users`
    ADD PRIMARY KEY (`id`),
    ADD KEY `project_users_user_id_foreign` (`user_id`),
    ADD KEY `project_users_project_id_foreign` (`project_id`);

--
-- Index pour la table `roles`
--
ALTER TABLE `roles`
    ADD PRIMARY KEY (`id`),
    ADD UNIQUE KEY `roles_name_guard_name_unique` (`name`,`guard_name`);

--
-- Index pour la table `role_has_permissions`
--
ALTER TABLE `role_has_permissions`
    ADD PRIMARY KEY (`permission_id`,`role_id`),
    ADD KEY `role_has_permissions_role_id_foreign` (`role_id`);

--
-- Index pour la table `settings`
--
ALTER TABLE `settings`
    ADD PRIMARY KEY (`id`),
    ADD KEY `settings_group_index` (`group`);

--
-- Index pour la table `socialite_users`
--
ALTER TABLE `socialite_users`
    ADD PRIMARY KEY (`id`),
    ADD UNIQUE KEY `socialite_users_provider_provider_id_unique` (`provider`,`provider_id`);

--
-- Index pour la table `tickets`
--
ALTER TABLE `tickets`
    ADD PRIMARY KEY (`id`),
    ADD KEY `tickets_owner_id_foreign` (`owner_id`),
    ADD KEY `tickets_responsible_id_foreign` (`responsible_id`),
    ADD KEY `tickets_status_id_foreign` (`status_id`),
    ADD KEY `tickets_project_id_foreign` (`project_id`),
    ADD KEY `tickets_type_id_foreign` (`type_id`),
    ADD KEY `tickets_priority_id_foreign` (`priority_id`),
    ADD KEY `tickets_epic_id_foreign` (`epic_id`);

--
-- Index pour la table `ticket_activities`
--
ALTER TABLE `ticket_activities`
    ADD PRIMARY KEY (`id`),
    ADD KEY `ticket_activities_ticket_id_foreign` (`ticket_id`),
    ADD KEY `ticket_activities_old_status_id_foreign` (`old_status_id`),
    ADD KEY `ticket_activities_new_status_id_foreign` (`new_status_id`),
    ADD KEY `ticket_activities_user_id_foreign` (`user_id`);

--
-- Index pour la table `ticket_comments`
--
ALTER TABLE `ticket_comments`
    ADD PRIMARY KEY (`id`),
    ADD KEY `ticket_comments_ticket_id_foreign` (`ticket_id`),
    ADD KEY `ticket_comments_user_id_foreign` (`user_id`);

--
-- Index pour la table `ticket_hours`
--
ALTER TABLE `ticket_hours`
    ADD PRIMARY KEY (`id`),
    ADD KEY `ticket_hours_ticket_id_foreign` (`ticket_id`),
    ADD KEY `ticket_hours_user_id_foreign` (`user_id`),
    ADD KEY `ticket_hours_activity_id_foreign` (`activity_id`);

--
-- Index pour la table `ticket_priorities`
--
ALTER TABLE `ticket_priorities`
    ADD PRIMARY KEY (`id`);

--
-- Index pour la table `ticket_relations`
--
ALTER TABLE `ticket_relations`
    ADD PRIMARY KEY (`id`),
    ADD KEY `ticket_relations_ticket_id_foreign` (`ticket_id`),
    ADD KEY `ticket_relations_relation_id_foreign` (`relation_id`);

--
-- Index pour la table `ticket_statuses`
--
ALTER TABLE `ticket_statuses`
    ADD PRIMARY KEY (`id`),
    ADD KEY `ticket_statuses_project_id_foreign` (`project_id`);

--
-- Index pour la table `ticket_subscribers`
--
ALTER TABLE `ticket_subscribers`
    ADD PRIMARY KEY (`id`),
    ADD KEY `ticket_subscribers_user_id_foreign` (`user_id`),
    ADD KEY `ticket_subscribers_ticket_id_foreign` (`ticket_id`);

--
-- Index pour la table `ticket_types`
--
ALTER TABLE `ticket_types`
    ADD PRIMARY KEY (`id`);

--
-- Index pour la table `time_sheets`
--
ALTER TABLE `time_sheets`
    ADD PRIMARY KEY (`id`),
    ADD KEY `time_sheets_user_id_foreign` (`user_id`),
    ADD KEY `time_sheets_project_id_foreign` (`project_id`);

--
-- Index pour la table `time_sheet_cells`
--
ALTER TABLE `time_sheet_cells`
    ADD PRIMARY KEY (`id`),
    ADD KEY `time_sheet_cells_time_sheet_id_foreign` (`time_sheet_id`);

--
-- Index pour la table `users`
--
ALTER TABLE `users`
    ADD PRIMARY KEY (`id`),
    ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `activities`
--
ALTER TABLE `activities`
    MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `epics`
--
ALTER TABLE `epics`
    MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `failed_jobs`
--
ALTER TABLE `failed_jobs`
    MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `jobs`
--
ALTER TABLE `jobs`
    MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `media`
--
ALTER TABLE `media`
    MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `migrations`
--
ALTER TABLE `migrations`
    MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `pending_user_emails`
--
ALTER TABLE `pending_user_emails`
    MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `permissions`
--
ALTER TABLE `permissions`
    MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
    MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `projects`
--
ALTER TABLE `projects`
    MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `project_favorites`
--
ALTER TABLE `project_favorites`
    MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `project_statuses`
--
ALTER TABLE `project_statuses`
    MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `project_users`
--
ALTER TABLE `project_users`
    MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `roles`
--
ALTER TABLE `roles`
    MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `settings`
--
ALTER TABLE `settings`
    MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `socialite_users`
--
ALTER TABLE `socialite_users`
    MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `tickets`
--
ALTER TABLE `tickets`
    MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `ticket_activities`
--
ALTER TABLE `ticket_activities`
    MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `ticket_comments`
--
ALTER TABLE `ticket_comments`
    MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `ticket_hours`
--
ALTER TABLE `ticket_hours`
    MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `ticket_priorities`
--
ALTER TABLE `ticket_priorities`
    MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `ticket_relations`
--
ALTER TABLE `ticket_relations`
    MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `ticket_statuses`
--
ALTER TABLE `ticket_statuses`
    MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `ticket_subscribers`
--
ALTER TABLE `ticket_subscribers`
    MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `ticket_types`
--
ALTER TABLE `ticket_types`
    MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `time_sheets`
--
ALTER TABLE `time_sheets`
    MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `time_sheet_cells`
--
ALTER TABLE `time_sheet_cells`
    MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `users`
--
ALTER TABLE `users`
    MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `epics`
--
ALTER TABLE `epics`
    ADD CONSTRAINT `epics_parent_id_foreign` FOREIGN KEY (`parent_id`) REFERENCES `epics` (`id`),
    ADD CONSTRAINT `epics_project_id_foreign` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`);

--
-- Contraintes pour la table `model_has_permissions`
--
ALTER TABLE `model_has_permissions`
    ADD CONSTRAINT `model_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `model_has_roles`
--
ALTER TABLE `model_has_roles`
    ADD CONSTRAINT `model_has_roles_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `projects`
--
ALTER TABLE `projects`
    ADD CONSTRAINT `projects_owner_id_foreign` FOREIGN KEY (`owner_id`) REFERENCES `users` (`id`),
    ADD CONSTRAINT `projects_status_id_foreign` FOREIGN KEY (`status_id`) REFERENCES `project_statuses` (`id`);

--
-- Contraintes pour la table `project_favorites`
--
ALTER TABLE `project_favorites`
    ADD CONSTRAINT `project_favorites_project_id_foreign` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`),
    ADD CONSTRAINT `project_favorites_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Contraintes pour la table `project_users`
--
ALTER TABLE `project_users`
    ADD CONSTRAINT `project_users_project_id_foreign` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`),
    ADD CONSTRAINT `project_users_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Contraintes pour la table `role_has_permissions`
--
ALTER TABLE `role_has_permissions`
    ADD CONSTRAINT `role_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE,
    ADD CONSTRAINT `role_has_permissions_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `tickets`
--
ALTER TABLE `tickets`
    ADD CONSTRAINT `tickets_epic_id_foreign` FOREIGN KEY (`epic_id`) REFERENCES `epics` (`id`),
    ADD CONSTRAINT `tickets_owner_id_foreign` FOREIGN KEY (`owner_id`) REFERENCES `users` (`id`),
    ADD CONSTRAINT `tickets_priority_id_foreign` FOREIGN KEY (`priority_id`) REFERENCES `ticket_priorities` (`id`),
    ADD CONSTRAINT `tickets_project_id_foreign` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`),
    ADD CONSTRAINT `tickets_responsible_id_foreign` FOREIGN KEY (`responsible_id`) REFERENCES `users` (`id`),
    ADD CONSTRAINT `tickets_status_id_foreign` FOREIGN KEY (`status_id`) REFERENCES `ticket_statuses` (`id`),
    ADD CONSTRAINT `tickets_type_id_foreign` FOREIGN KEY (`type_id`) REFERENCES `ticket_types` (`id`);

--
-- Contraintes pour la table `ticket_activities`
--
ALTER TABLE `ticket_activities`
    ADD CONSTRAINT `ticket_activities_new_status_id_foreign` FOREIGN KEY (`new_status_id`) REFERENCES `ticket_statuses` (`id`),
    ADD CONSTRAINT `ticket_activities_old_status_id_foreign` FOREIGN KEY (`old_status_id`) REFERENCES `ticket_statuses` (`id`),
    ADD CONSTRAINT `ticket_activities_ticket_id_foreign` FOREIGN KEY (`ticket_id`) REFERENCES `tickets` (`id`),
    ADD CONSTRAINT `ticket_activities_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Contraintes pour la table `ticket_comments`
--
ALTER TABLE `ticket_comments`
    ADD CONSTRAINT `ticket_comments_ticket_id_foreign` FOREIGN KEY (`ticket_id`) REFERENCES `tickets` (`id`),
    ADD CONSTRAINT `ticket_comments_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Contraintes pour la table `ticket_hours`
--
ALTER TABLE `ticket_hours`
    ADD CONSTRAINT `ticket_hours_activity_id_foreign` FOREIGN KEY (`activity_id`) REFERENCES `activities` (`id`),
    ADD CONSTRAINT `ticket_hours_ticket_id_foreign` FOREIGN KEY (`ticket_id`) REFERENCES `tickets` (`id`),
    ADD CONSTRAINT `ticket_hours_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Contraintes pour la table `ticket_relations`
--
ALTER TABLE `ticket_relations`
    ADD CONSTRAINT `ticket_relations_relation_id_foreign` FOREIGN KEY (`relation_id`) REFERENCES `tickets` (`id`),
    ADD CONSTRAINT `ticket_relations_ticket_id_foreign` FOREIGN KEY (`ticket_id`) REFERENCES `tickets` (`id`);

--
-- Contraintes pour la table `ticket_statuses`
--
ALTER TABLE `ticket_statuses`
    ADD CONSTRAINT `ticket_statuses_project_id_foreign` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`);

--
-- Contraintes pour la table `ticket_subscribers`
--
ALTER TABLE `ticket_subscribers`
    ADD CONSTRAINT `ticket_subscribers_ticket_id_foreign` FOREIGN KEY (`ticket_id`) REFERENCES `tickets` (`id`),
    ADD CONSTRAINT `ticket_subscribers_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Contraintes pour la table `time_sheets`
--
ALTER TABLE `time_sheets`
    ADD CONSTRAINT `time_sheets_project_id_foreign` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`),
    ADD CONSTRAINT `time_sheets_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Contraintes pour la table `time_sheet_cells`
--
ALTER TABLE `time_sheet_cells`
    ADD CONSTRAINT `time_sheet_cells_time_sheet_id_foreign` FOREIGN KEY (`time_sheet_id`) REFERENCES `time_sheets` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
