-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : mar. 15 juil. 2025 à 19:48
-- Version du serveur : 10.4.32-MariaDB
-- Version de PHP : 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `gestion_documents`
--

-- --------------------------------------------------------

--
-- Structure de la table `actions_history`
--

CREATE TABLE `actions_history` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `action_type` enum('upload','delete','share','download') NOT NULL,
  `file_id` int(11) DEFAULT NULL,
  `action_time` datetime DEFAULT current_timestamp(),
  `description` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `documents`
--

CREATE TABLE `documents` (
  `id` int(11) NOT NULL,
  `type` enum('facture','devis','bon_livraison') NOT NULL,
  `client` varchar(255) NOT NULL,
  `date_creation` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `documents`
--

INSERT INTO `documents` (`id`, `type`, `client`, `date_creation`) VALUES
(1, 'devis', 'haroun', '2025-03-12 12:09:17'),
(2, 'bon_livraison', 'haroun', '2025-03-12 12:10:53'),
(3, 'facture', 'haroun', '2025-03-12 12:11:08'),
(4, 'facture', 'haroun', '2025-03-12 12:25:39');

-- --------------------------------------------------------

--
-- Structure de la table `files`
--

CREATE TABLE `files` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `filename` varchar(255) NOT NULL,
  `filepath` varchar(255) NOT NULL,
  `filetype` varchar(50) DEFAULT NULL,
  `filesize` bigint(20) DEFAULT NULL,
  `uploaded_at` datetime DEFAULT current_timestamp(),
  `parent_id` int(11) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `files`
--

INSERT INTO `files` (`id`, `user_id`, `filename`, `filepath`, `filetype`, `filesize`, `uploaded_at`, `parent_id`, `password`) VALUES
(4, 1, 'adzazd', 'uploads/1/uploads/1/mounir/adzazd', 'folder', 0, '2025-07-15 02:41:44', 3, NULL),
(7, 1, '4-_Apprentissage_Supervise_-_K-nearest_Neighbors.rar', 'uploads/1/4-_Apprentissage_Supervise_-_K-nearest_Neighbors.rar', 'application/x-rar', 614917, '2025-07-15 02:51:46', NULL, NULL),
(8, 1, 'no', 'uploads/1/uploads/omar/no', 'folder', 0, '2025-07-15 03:04:00', 1, NULL),
(9, 1, '5-_Apprentissage_Supervise_-_Arbres_de_decision_-_partie-2 (1).pdf', 'uploads/1/uploads/omar/5-_Apprentissage_Supervise_-_Arbres_de_decision_-_partie-2 (1).pdf', 'application/pdf', 804783, '2025-07-15 03:04:13', 1, NULL),
(10, 2, 'sousou14', 'uploads/2/sousou14', 'folder', 0, '2025-07-15 03:20:16', NULL, NULL),
(11, 2, '4-_Apprentissage_Supervise_-_K-nearest_Neighbors.rar', 'uploads/2/4-_Apprentissage_Supervise_-_K-nearest_Neighbors.rar', 'application/x-rar', 614917, '2025-07-15 04:00:46', NULL, NULL),
(12, 1, 'zdazdaz', 'uploads/1/uploads/omar/zdazdaz', 'folder', 0, '2025-07-15 15:40:14', 1, NULL),
(13, 1, 'si haroun', 'uploads/1/si haroun', 'folder', 0, '2025-07-15 15:55:26', NULL, NULL),
(14, 1, 'omarr', 'uploads/1/uploads/1/si haroun/omarr', 'folder', 0, '2025-07-15 15:55:42', 13, NULL),
(15, 1, '3-_Apprentissage_Supervise_-_Regression_Lineaire (1) (1).pdf', 'uploads/1/uploads/1/si haroun/3-_Apprentissage_Supervise_-_Regression_Lineaire (1) (1).pdf', 'application/pdf', 619501, '2025-07-15 15:55:53', 13, NULL),
(17, 1, '5-_Apprentissage_Supervise_-_Arbres_de_decision_-_partie-2 (1) (1).pdf', 'uploads/1/uploads/1/dasasa/5-_Apprentissage_Supervise_-_Arbres_de_decision_-_partie-2 (1) (1).pdf', 'application/pdf', 804783, '2025-07-15 15:56:59', 2, NULL),
(21, 2, 'salah', 'uploads/2/salah', 'folder', 0, '2025-07-15 17:22:40', NULL, NULL),
(28, 2, 'kaka', 'uploads/2/kaka', 'folder', 0, '2025-07-15 18:29:09', NULL, '$2y$10$RvIEuh7O5d5GhkIprU4RmuQB6NSgf4..1Ebq1cah1OOKOzTOdACdy');

-- --------------------------------------------------------

--
-- Structure de la table `login_history`
--

CREATE TABLE `login_history` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `login_time` datetime DEFAULT current_timestamp(),
  `ip_address` varchar(100) DEFAULT NULL,
  `user_agent` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `login_history`
--

INSERT INTO `login_history` (`id`, `user_id`, `login_time`, `ip_address`, `user_agent`) VALUES
(1, 1, '2025-07-15 04:47:30', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36'),
(2, 1, '2025-07-15 15:39:42', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36'),
(3, 1, '2025-07-15 15:51:42', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36'),
(4, 1, '2025-07-15 15:52:29', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36'),
(5, 2, '2025-07-15 15:58:02', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36'),
(6, 1, '2025-07-15 15:59:22', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36'),
(7, 2, '2025-07-15 16:41:42', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36');

-- --------------------------------------------------------

--
-- Structure de la table `produits`
--

CREATE TABLE `produits` (
  `id` int(11) NOT NULL,
  `document_id` int(11) DEFAULT NULL,
  `nom` varchar(255) NOT NULL,
  `quantite` int(11) NOT NULL,
  `prix_unitaire` decimal(10,2) NOT NULL,
  `tva` decimal(5,2) NOT NULL DEFAULT 20.00
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `produits`
--

INSERT INTO `produits` (`id`, `document_id`, `nom`, `quantite`, `prix_unitaire`, `tva`) VALUES
(2, 1, 'jhgsjd', 2, 15.00, 20.00),
(3, 1, 'dshgjqskj', 3, 12.00, 20.00),
(4, 2, 'jhgsjd', 2, 15.00, 20.00),
(5, 3, 'jhgsjd', 2, 15.00, 20.00),
(6, 4, 'lilas', 2, 15.00, 20.00),
(7, 4, 'langete', 1, 22.00, 20.00),
(8, 4, 'parfain', 17, 1.00, 20.00);

-- --------------------------------------------------------

--
-- Structure de la table `shared_files`
--

CREATE TABLE `shared_files` (
  `id` int(11) NOT NULL,
  `file_id` int(11) DEFAULT NULL,
  `share_token` varchar(255) NOT NULL,
  `shared_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `firstname` varchar(100) DEFAULT NULL,
  `lastname` varchar(100) DEFAULT NULL,
  `email` varchar(150) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','user') DEFAULT 'user',
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `users`
--

INSERT INTO `users` (`id`, `firstname`, `lastname`, `email`, `password`, `role`, `created_at`) VALUES
(1, 'Omar', 'Test', 'test@test.com', '$2y$10$QDJwGQvNfOWaKdTScXXuOeMPiiZQNdnzVF7h5RgavMjjhaGMLcIbG', 'admin', '2025-07-14 14:29:33'),
(2, 'syrine', 'benyounes', 'sy@gmail.c', '$2y$10$K1cC1w.822n1OjIfPYp75usSzCz.vAZOTP8KUj4xaObIArTTtwfF6', 'admin', '2025-07-15 03:19:38');

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `actions_history`
--
ALTER TABLE `actions_history`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `file_id` (`file_id`);

--
-- Index pour la table `documents`
--
ALTER TABLE `documents`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `files`
--
ALTER TABLE `files`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Index pour la table `login_history`
--
ALTER TABLE `login_history`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Index pour la table `produits`
--
ALTER TABLE `produits`
  ADD PRIMARY KEY (`id`),
  ADD KEY `document_id` (`document_id`);

--
-- Index pour la table `shared_files`
--
ALTER TABLE `shared_files`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `share_token` (`share_token`),
  ADD KEY `file_id` (`file_id`);

--
-- Index pour la table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `actions_history`
--
ALTER TABLE `actions_history`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `documents`
--
ALTER TABLE `documents`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT pour la table `files`
--
ALTER TABLE `files`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT pour la table `login_history`
--
ALTER TABLE `login_history`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT pour la table `produits`
--
ALTER TABLE `produits`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT pour la table `shared_files`
--
ALTER TABLE `shared_files`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `actions_history`
--
ALTER TABLE `actions_history`
  ADD CONSTRAINT `actions_history_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `actions_history_ibfk_2` FOREIGN KEY (`file_id`) REFERENCES `files` (`id`) ON DELETE SET NULL;

--
-- Contraintes pour la table `files`
--
ALTER TABLE `files`
  ADD CONSTRAINT `files_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `login_history`
--
ALTER TABLE `login_history`
  ADD CONSTRAINT `login_history_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `produits`
--
ALTER TABLE `produits`
  ADD CONSTRAINT `produits_ibfk_1` FOREIGN KEY (`document_id`) REFERENCES `documents` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `shared_files`
--
ALTER TABLE `shared_files`
  ADD CONSTRAINT `shared_files_ibfk_1` FOREIGN KEY (`file_id`) REFERENCES `files` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
