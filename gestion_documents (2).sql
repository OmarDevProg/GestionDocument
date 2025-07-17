-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : jeu. 17 juil. 2025 à 20:27
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
-- Structure de la table `action_history`
--

CREATE TABLE `action_history` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `action_type` enum('upload','delete','delete_folder','delete_file','share','download','create_user','create_folder','remove_password') NOT NULL,
  `file_id` int(11) DEFAULT NULL,
  `action_time` datetime DEFAULT current_timestamp(),
  `description` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `action_history`
--

INSERT INTO `action_history` (`id`, `user_id`, `action_type`, `file_id`, `action_time`, `description`) VALUES
(1, 2, 'create_folder', 32, '2025-07-16 01:29:53', 'Création dossier: LILI'),
(2, 2, '', NULL, '2025-07-16 01:37:27', 'Suppression de: 4-_Apprentissage_Supervise_-_K-nearest_Neighbors.rar'),
(3, 2, 'delete_folder', NULL, '2025-07-16 01:39:55', 'Suppression de: sousou14'),
(4, 2, 'remove_password', NULL, '2025-07-16 01:42:28', 'Suppression mot de passe sur dossier ID: 21'),
(5, 2, 'create_folder', 33, '2025-07-16 12:27:05', 'Création dossier: omarr'),
(6, 2, 'create_folder', 34, '2025-07-16 12:33:02', 'Création dossier: ya3tini 3asba'),
(7, 2, 'create_user', NULL, '2025-07-16 12:35:54', 'Ajout utilisateur: h@h.com'),
(8, 4, 'create_folder', 35, '2025-07-16 12:36:18', 'Création dossier: sasas'),
(9, 4, 'delete_folder', NULL, '2025-07-16 12:36:40', 'Suppression de: salah'),
(10, 2, 'create_folder', 36, '2025-07-16 13:07:50', 'Création dossier: hm'),
(11, 4, 'create_folder', NULL, '2025-07-16 13:09:55', 'Création dossier: nounou'),
(12, 2, 'create_folder', 38, '2025-07-16 13:12:17', 'Création dossier: karim'),
(13, 2, 'create_folder', 39, '2025-07-16 13:12:32', 'Création dossier: zadazd'),
(14, 4, 'create_folder', NULL, '2025-07-16 13:27:02', 'Création dossier: testing'),
(15, 4, 'create_folder', 41, '2025-07-16 13:27:16', 'Création dossier: hash'),
(16, 2, 'delete_folder', NULL, '2025-07-16 13:30:19', 'Suppression de: testing'),
(17, 4, 'delete_folder', 38, '2025-07-16 13:38:55', 'Suppression de: karim'),
(18, 4, 'delete_folder', NULL, '2025-07-16 13:39:11', 'Suppression de: nounou'),
(19, 4, 'delete_folder', NULL, '2025-07-16 13:39:41', 'Suppression de: nounou'),
(20, 4, 'delete_folder', NULL, '2025-07-16 13:40:49', 'Suppression de: nounou'),
(21, 4, 'delete_folder', NULL, '2025-07-16 13:43:43', 'Suppression de: nounou'),
(22, 4, 'delete_folder', NULL, '2025-07-16 13:44:02', 'Suppression de: nounou'),
(23, 4, 'delete_folder', NULL, '2025-07-16 13:45:05', 'Suppression de: nounou'),
(24, 4, 'create_folder', 42, '2025-07-16 13:48:11', 'Création dossier: nounou'),
(25, 4, 'create_folder', NULL, '2025-07-16 13:48:24', 'Création dossier: test2'),
(26, 4, 'create_folder', 44, '2025-07-16 13:48:33', 'Création dossier: test2'),
(27, 2, 'create_folder', NULL, '2025-07-16 13:50:50', 'Création dossier: syrinetest'),
(28, 2, 'create_folder', 46, '2025-07-16 13:51:09', 'Création dossier: n'),
(29, 2, 'create_folder', NULL, '2025-07-16 13:57:49', 'Création dossier: test45'),
(30, 2, 'delete_folder', NULL, '2025-07-16 13:58:02', 'Suppression de: test45'),
(31, 2, 'create_folder', 48, '2025-07-16 13:59:36', 'Création dossier: koukou'),
(32, 4, 'delete_folder', NULL, '2025-07-16 14:01:38', 'Suppression de: test2'),
(33, 4, 'create_folder', NULL, '2025-07-16 14:12:10', 'Création dossier: omar'),
(34, 4, '', NULL, '2025-07-16 14:12:38', 'Suppression de: gestion_documents (1).sql'),
(35, 4, '', NULL, '2025-07-16 14:12:43', 'Suppression de: 5-_Apprentissage_Supervise_-_Arbres_de_decision_-_partie-2 (1) (1) (1).pdf'),
(36, 2, 'remove_password', NULL, '2025-07-16 14:14:44', 'Suppression mot de passe sur dossier ID: 50'),
(37, 4, '', NULL, '2025-07-16 14:22:43', 'Suppression de: gestion_documents (1).sql'),
(38, 4, '', NULL, '2025-07-16 14:22:49', 'Suppression de: 5-_Apprentissage_Supervise_-_Arbres_de_decision_-_partie-2 (1) (1) (1).pdf'),
(39, 4, '', NULL, '2025-07-16 14:22:55', 'Suppression de: 5-_Apprentissage_Supervise_-_Arbres_de_decision_-_partie-2 (1) (1) (1).pdf'),
(40, 4, '', NULL, '2025-07-16 14:22:59', 'Suppression de: 5-_Apprentissage_Supervise_-_Arbres_de_decision_-_partie-2 (1) (1) (1).pdf'),
(41, 4, '', NULL, '2025-07-16 14:23:22', 'Suppression de: 5-_Apprentissage_Supervise_-_Arbres_de_decision_-_partie-2 (1) (1) (1).pdf'),
(42, 4, 'create_folder', 60, '2025-07-16 14:25:45', 'Création dossier: azza'),
(43, 4, '', 36, '2025-07-16 14:30:51', 'Renommé en: zeb'),
(44, 4, '', NULL, '2025-07-16 14:35:12', 'Renommé en: coucou.sql'),
(45, 4, '', NULL, '2025-07-16 14:35:32', 'Renommé en: coucou.txt'),
(46, 4, '', NULL, '2025-07-16 15:06:32', 'Suppression de: coucou.txt'),
(47, 4, '', NULL, '2025-07-16 15:08:55', 'Suppression de: 5-_Apprentissage_Supervise_-_Arbres_de_decision_-_partie-2 (1) (1) (1).pdf'),
(48, 4, '', NULL, '2025-07-16 15:24:02', 'Suppression de: 5-_Apprentissage_Supervise_-_Arbres_de_decision_-_partie-2 (1) (1) (1).pdf'),
(49, 4, 'delete_folder', NULL, '2025-07-16 15:24:06', 'Suppression de: omar'),
(50, 4, 'create_folder', NULL, '2025-07-16 15:53:08', 'Création dossier: nounoun'),
(51, 4, '', NULL, '2025-07-17 18:18:30', 'Suppression de: gestion_documents (1) (1).sql'),
(52, 4, 'delete_folder', NULL, '2025-07-17 18:18:36', 'Suppression de: nounoun'),
(53, 4, 'create_folder', NULL, '2025-07-17 18:18:54', 'Création dossier: testion'),
(54, 4, 'create_folder', 86, '2025-07-17 18:19:04', 'Création dossier: adad'),
(55, 4, 'create_folder', 87, '2025-07-17 18:19:09', 'Création dossier: zdazdzad'),
(56, 2, 'create_folder', 89, '2025-07-17 18:20:49', 'Création dossier: sihem'),
(57, 2, '', 66, '2025-07-17 18:23:13', 'Renommé en: klk.docs'),
(58, 2, '', NULL, '2025-07-17 19:19:01', 'Déplacé dans la corbeille: syrinetest'),
(59, 4, '', NULL, '2025-07-17 19:21:52', 'Déplacé dans la corbeille: testion');

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
(28, 2, 'kaka', 'uploads/2/kaka', 'folder', 0, '2025-07-15 18:29:09', NULL, 'co'),
(32, 2, 'LILI', 'uploads/2/LILI', 'folder', 0, '2025-07-16 01:29:53', NULL, '$2y$10$Xf/Se6.16s.LdfG5VNAXWe1gmnOinO/u/LrFiHpmwPXo5HLXb5v6i'),
(33, 2, 'omarr', 'uploads/2/uploads/2/salah/omarr', 'folder', 0, '2025-07-16 12:27:05', 21, NULL),
(34, 2, 'ya3tini 3asba', 'uploads/2/uploads/2/salah/ya3tini 3asba', 'folder', 0, '2025-07-16 12:33:02', 21, NULL),
(35, 4, 'sasas', 'uploads/4/sasas', 'folder', 0, '2025-07-16 12:36:18', NULL, '$2y$10$c4Gk06eEP8ixImX7DlJF/.Gc7PhP5kB.CRltKhu2OOLP8nQZDhpzC'),
(36, 2, 'zeb', 'uploads/2/zeb', 'folder', 0, '2025-07-16 13:07:50', NULL, '$2y$10$gCh3opmFV0VKSJyhp398BOhRjiRL0/tFotSczgYqhgF3pMKgorVA6'),
(38, 2, 'karim', 'uploads/2/karim', 'folder', 0, '2025-07-16 13:12:17', NULL, '$2y$10$AN9UjI1ZgWG5g3FjIZpsFOGiJDAkHQvbSgQ4rcwwdSIsV8IctVOou'),
(39, 2, 'zadazd', 'uploads/2/uploads/2/karim/zadazd', 'folder', 0, '2025-07-16 13:12:32', 38, NULL),
(41, 4, 'hash', 'uploads/4/uploads/4/testing/hash', 'folder', 0, '2025-07-16 13:27:16', 40, '$2y$10$ClDiPjdl/xoTnqbqrisNAObHdhIFwhIE19PY.M4pe39AgRNsvrXLe'),
(42, 4, 'nounou', 'uploads/4/uploads/4/sasas/nounou', 'folder', 0, '2025-07-16 13:48:11', 35, NULL),
(44, 4, 'test2', 'uploads/4/uploads/4/test2/test2', 'folder', 0, '2025-07-16 13:48:33', 43, '$2y$10$NyXePjEx/iK61MNJ/9jgBeIAbmc0lcnYMOpMOrvCz9m2sOykJcHyi'),
(46, 2, 'n', 'uploads/2/uploads/2/syrinetest/n', 'folder', 0, '2025-07-16 13:51:09', 45, NULL),
(48, 2, 'koukou', 'uploads/2/uploads/4/test2/koukou', 'folder', 0, '2025-07-16 13:59:36', 43, NULL),
(49, 2, '5-_Apprentissage_Supervise_-_Arbres_de_decision_-_partie-2 (1) (1) (1).pdf', 'uploads/2/uploads/2/uploads/4/test2/koukou/5-_Apprentissage_Supervise_-_Arbres_de_decision_-_partie-2 (1) (1) (1).pdf', 'application/pdf', 804783, '2025-07-16 13:59:58', 48, NULL),
(52, 4, '4-_Apprentissage_Supervise_-_K-nearest_Neighbors (2).rar', 'uploads/4/uploads/4/omar/4-_Apprentissage_Supervise_-_K-nearest_Neighbors (2).rar', 'application/x-rar', 614917, '2025-07-16 14:12:26', 50, NULL),
(60, 4, 'azza', 'uploads/4/uploads/2/karim/azza', 'folder', 0, '2025-07-16 14:25:45', 38, NULL),
(61, 4, '5-_Apprentissage_Supervise_-_Arbres_de_decision_-_partie-2 (1) (1) (1).pdf', 'uploads/4/uploads/4/uploads/2/karim/azza/5-_Apprentissage_Supervise_-_Arbres_de_decision_-_partie-2 (1) (1) (1).pdf', 'application/pdf', 804783, '2025-07-16 14:26:04', 60, NULL),
(63, 4, '5-_Apprentissage_Supervise_-_Arbres_de_decision_-_partie-2 (1) (1) (1).pdf', 'uploads/4/uploads/2/karim/5-_Apprentissage_Supervise_-_Arbres_de_decision_-_partie-2 (1) (1) (1).pdf', 'application/pdf', 804783, '2025-07-16 14:27:33', 38, NULL),
(64, 4, 'gestion_documents (1) (1).sql', 'uploads/4/uploads/4/uploads/2/karim/azza/gestion_documents (1) (1).sql', 'text/plain', 11342, '2025-07-16 14:27:44', 60, NULL),
(66, 4, 'klk.docs', 'uploads/4/klk.docs.pdf', 'application/pdf', 804783, '2025-07-16 15:24:16', NULL, NULL),
(67, 4, '5-_Apprentissage_Supervise_-_Arbres_de_decision_-_partie-2 (1) (1) (1).pdf_copie', 'uploads/4/5-_Apprentissage_Supervise_-_Arbres_de_decision_-_partie-2 (1) (1) (1).pdf', 'application/pdf', 804783, '2025-07-16 15:40:18', 66, NULL),
(68, 4, '5-_Apprentissage_Supervise_-_Arbres_de_decision_-_partie-2 (1) (1) (1).pdf_copie', 'uploads/4/5-_Apprentissage_Supervise_-_Arbres_de_decision_-_partie-2 (1) (1) (1).pdf', 'application/pdf', 804783, '2025-07-16 15:40:41', 66, NULL),
(69, 4, '5-_Apprentissage_Supervise_-_Arbres_de_decision_-_partie-2 (1) (1) (1)_copie.pdf', 'uploads/2/syrinetest/5-_Apprentissage_Supervise_-_Arbres_de_decision_-_partie-2 (1) (1) (1)_copie.pdf', 'application/pdf', 804783, '2025-07-16 15:52:34', 45, NULL),
(71, 4, '5-_Apprentissage_Supervise_-_Arbres_de_decision_-_partie-2 (1) (1) (1)_copie.pdf', 'uploads/2/syrinetest/5-_Apprentissage_Supervise_-_Arbres_de_decision_-_partie-2 (1) (1) (1)_copie.pdf', 'application/pdf', 804783, '2025-07-16 15:53:24', 45, NULL),
(72, 4, '5-_Apprentissage_Supervise_-_Arbres_de_decision_-_partie-2 (1) (1) (1)_copie.pdf', 'uploads/4/5-_Apprentissage_Supervise_-_Arbres_de_decision_-_partie-2 (1) (1) (1).pdf/5-_Apprentissage_Supervise_-_Arbres_de_decision_-_partie-2 (1) (1) (1)_copie.pdf', 'application/pdf', 804783, '2025-07-16 15:53:55', 66, NULL),
(73, 4, '5-_Apprentissage_Supervise_-_Arbres_de_decision_-_partie-2 (1) (1) (1)_copie.pdf', 'uploads/4/5-_Apprentissage_Supervise_-_Arbres_de_decision_-_partie-2 (1) (1) (1).pdf/5-_Apprentissage_Supervise_-_Arbres_de_decision_-_partie-2 (1) (1) (1)_copie.pdf', 'application/pdf', 804783, '2025-07-16 15:56:21', 66, NULL),
(74, 4, '5-_Apprentissage_Supervise_-_Arbres_de_decision_-_partie-2 (1) (1) (1)_copie.pdf', 'uploads/4/5-_Apprentissage_Supervise_-_Arbres_de_decision_-_partie-2 (1) (1) (1).pdf/5-_Apprentissage_Supervise_-_Arbres_de_decision_-_partie-2 (1) (1) (1)_copie.pdf', 'application/pdf', 804783, '2025-07-16 16:00:40', 66, NULL),
(75, 4, '5-_Apprentissage_Supervise_-_Arbres_de_decision_-_partie-2 (1) (1) (1)_copie.pdf', 'uploads/4/5-_Apprentissage_Supervise_-_Arbres_de_decision_-_partie-2 (1) (1) (1).pdf/5-_Apprentissage_Supervise_-_Arbres_de_decision_-_partie-2 (1) (1) (1)_copie.pdf', 'application/pdf', 804783, '2025-07-16 16:02:14', 66, NULL),
(76, 2, 'syrinetest_copie', 'uploads/4/5-_Apprentissage_Supervise_-_Arbres_de_decision_-_partie-2 (1) (1) (1).pdf/syrinetest_copie', 'folder', 0, '2025-07-16 16:02:35', 66, NULL),
(77, 4, '5-_Apprentissage_Supervise_-_Arbres_de_decision_-_partie-2 (1) (1) (1)_copie.pdf', 'uploads/2/syrinetest/5-_Apprentissage_Supervise_-_Arbres_de_decision_-_partie-2 (1) (1) (1)_copie.pdf', 'application/pdf', 804783, '2025-07-16 16:08:14', 45, NULL),
(78, 4, '5-_Apprentissage_Supervise_-_Arbres_de_decision_-_partie-2 (1) (1) (1)_copie.pdf', 'uploads/4/nounoun/5-_Apprentissage_Supervise_-_Arbres_de_decision_-_partie-2 (1) (1) (1)_copie.pdf', 'application/pdf', 804783, '2025-07-16 16:11:27', 70, NULL),
(79, 4, '5-_Apprentissage_Supervise_-_Arbres_de_decision_-_partie-2 (1) (1) (1)_copie.pdf', 'uploads/4/nounoun/5-_Apprentissage_Supervise_-_Arbres_de_decision_-_partie-2 (1) (1) (1)_copie.pdf', 'application/pdf', 804783, '2025-07-16 16:18:59', 70, NULL),
(80, 4, '5-_Apprentissage_Supervise_-_Arbres_de_decision_-_partie-2 (1) (1) (1)_copie_copie.pdf', 'uploads/2/syrinetest/5-_Apprentissage_Supervise_-_Arbres_de_decision_-_partie-2 (1) (1) (1)_copie_copie.pdf', 'application/pdf', 804783, '2025-07-16 16:19:50', 45, NULL),
(81, 4, '5-_Apprentissage_Supervise_-_Arbres_de_decision_-_partie-2 (1) (1) (1)_copie_copie.pdf', 'uploads/2/syrinetest/5-_Apprentissage_Supervise_-_Arbres_de_decision_-_partie-2 (1) (1) (1)_copie_copie.pdf', 'application/pdf', 804783, '2025-07-16 16:20:06', 45, NULL),
(82, 4, '5-_Apprentissage_Supervise_-_Arbres_de_decision_-_partie-2 (1) (1) (1)_copie.pdf', 'uploads/4/nounoun/5-_Apprentissage_Supervise_-_Arbres_de_decision_-_partie-2 (1) (1) (1)_copie.pdf', 'application/pdf', 804783, '2025-07-16 16:35:43', 70, NULL),
(84, 4, 'gestion_documents (1) (1)_copie.sql', 'uploads/4/nounoun/gestion_documents (1) (1)_copie.sql', 'text/plain', 11342, '2025-07-16 16:36:30', 70, NULL),
(86, 4, 'adad', 'uploads/4/uploads/4/testion/adad', 'folder', 0, '2025-07-17 18:19:04', 85, NULL),
(87, 4, 'zdazdzad', 'uploads/4/uploads/4/uploads/4/testion/adad/zdazdzad', 'folder', 0, '2025-07-17 18:19:09', 86, NULL),
(88, 4, '5-_Apprentissage_Supervise_-_Arbres_de_decision_-_partie-2 (1) (1) (1) (1).pdf', 'uploads/4/uploads/4/uploads/4/uploads/4/testion/adad/zdazdzad/5-_Apprentissage_Supervise_-_Arbres_de_decision_-_partie-2 (1) (1) (1) (1).pdf', 'application/pdf', 804783, '2025-07-17 18:19:24', 87, NULL),
(89, 2, 'sihem', 'uploads/2/uploads/4/testion/sihem', 'folder', 0, '2025-07-17 18:20:49', 85, NULL),
(90, 2, '5-_Apprentissage_Supervise_-_Arbres_de_decision_-_partie-2 (1) (1) (1) (1).pdf', 'uploads/2/uploads/2/uploads/4/testion/sihem/5-_Apprentissage_Supervise_-_Arbres_de_decision_-_partie-2 (1) (1) (1) (1).pdf', 'application/pdf', 804783, '2025-07-17 18:21:04', 89, NULL),
(91, 4, '5-_Apprentissage_Supervise_-_Arbres_de_decision_-_partie-2 (1) (1) (1)_copie.pdf', 'uploads/2/syrinetest/5-_Apprentissage_Supervise_-_Arbres_de_decision_-_partie-2 (1) (1) (1)_copie.pdf', 'application/pdf', 804783, '2025-07-17 18:22:22', 45, NULL),
(92, 4, 'klk_copie.docs', 'uploads/2/uploads/2/syrinetest/n/klk_copie.docs', 'application/pdf', 804783, '2025-07-17 18:42:09', 46, NULL),
(93, 4, 'klk_copie.docs', 'uploads/2/syrinetest/klk_copie.docs', 'application/pdf', 804783, '2025-07-17 18:44:22', 45, NULL),
(94, 4, 'klk_copie.docs', 'uploads/2/uploads/2/syrinetest/n/klk_copie.docs', 'application/pdf', 804783, '2025-07-17 18:44:29', 46, NULL),
(95, 4, 'klk_copie.docs', 'uploads/2/syrinetest/klk_copie.docs', 'application/pdf', 804783, '2025-07-17 18:45:32', 45, NULL);

-- --------------------------------------------------------

--
-- Structure de la table `files_corbeille`
--

CREATE TABLE `files_corbeille` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `filename` varchar(255) NOT NULL,
  `filepath` varchar(255) NOT NULL,
  `filetype` varchar(50) DEFAULT NULL,
  `filesize` bigint(20) DEFAULT NULL,
  `uploaded_at` datetime DEFAULT current_timestamp(),
  `parent_id` int(11) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `deleted_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `files_corbeille`
--

INSERT INTO `files_corbeille` (`id`, `user_id`, `filename`, `filepath`, `filetype`, `filesize`, `uploaded_at`, `parent_id`, `password`, `deleted_at`) VALUES
(1, 2, 'syrinetest', 'uploads/2/syrinetest', 'folder', 0, '2025-07-16 13:50:50', NULL, NULL, '2025-07-17 19:19:01'),
(2, 4, 'testion', 'uploads/4/testion', 'folder', 0, '2025-07-17 18:18:54', NULL, '$2y$10$3KJWtcPs2IQP5nwK1T7jDOlvf4.vTKjX43fAa.9TekjDuoNsvkARm', '2025-07-17 19:21:52');

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
(5, 2, '2025-07-15 15:58:02', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36'),
(7, 2, '2025-07-15 16:41:42', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36'),
(8, 2, '2025-07-16 01:29:24', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36'),
(9, 2, '2025-07-16 12:26:45', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36'),
(10, 2, '2025-07-16 12:32:49', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36'),
(11, 4, '2025-07-16 12:36:05', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36'),
(12, 2, '2025-07-16 13:03:39', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36'),
(13, 2, '2025-07-16 13:07:13', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36'),
(14, 2, '2025-07-16 13:07:30', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36'),
(15, 4, '2025-07-16 13:08:10', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36'),
(16, 2, '2025-07-16 13:10:08', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36'),
(17, 4, '2025-07-16 13:12:46', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36'),
(18, 2, '2025-07-16 13:29:22', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36'),
(19, 2, '2025-07-16 13:49:01', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36'),
(20, 4, '2025-07-16 14:01:00', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36'),
(21, 2, '2025-07-16 14:13:28', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36 Edg/138.0.0.0'),
(22, 4, '2025-07-16 14:43:12', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36'),
(23, 4, '2025-07-16 15:48:10', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36'),
(24, 4, '2025-07-17 18:18:00', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36'),
(25, 2, '2025-07-17 18:20:23', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36'),
(26, 4, '2025-07-17 19:21:44', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36');

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
(2, 'syrine', 'benyounes', 'sy@gmail.c', '$2y$10$K1cC1w.822n1OjIfPYp75usSzCz.vAZOTP8KUj4xaObIArTTtwfF6', 'admin', '2025-07-15 03:19:38'),
(4, 'haroun', 'daoud', 'h@h.com', '$2y$10$hJdJRGbpS6QLe.eXdJv9KOzHNge3crVuoAjeEmlLWp1sWNY6B.y9a', 'admin', '2025-07-16 12:35:54');

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `action_history`
--
ALTER TABLE `action_history`
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
-- Index pour la table `files_corbeille`
--
ALTER TABLE `files_corbeille`
  ADD PRIMARY KEY (`id`);

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
-- AUTO_INCREMENT pour la table `action_history`
--
ALTER TABLE `action_history`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=60;

--
-- AUTO_INCREMENT pour la table `documents`
--
ALTER TABLE `documents`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT pour la table `files`
--
ALTER TABLE `files`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=96;

--
-- AUTO_INCREMENT pour la table `files_corbeille`
--
ALTER TABLE `files_corbeille`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT pour la table `login_history`
--
ALTER TABLE `login_history`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `action_history`
--
ALTER TABLE `action_history`
  ADD CONSTRAINT `action_history_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `action_history_ibfk_2` FOREIGN KEY (`file_id`) REFERENCES `files` (`id`) ON DELETE SET NULL;

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
