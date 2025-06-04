-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : dim. 01 juin 2025 à 22:29
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
-- Base de données : `gestion_immobilisations`
--

-- --------------------------------------------------------

--
-- Structure de la table `clients`
--

CREATE TABLE `clients` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `societe_id` bigint(20) UNSIGNED NOT NULL,
  `code` varchar(50) DEFAULT NULL,
  `nom` varchar(255) NOT NULL,
  `identifiant_unique` varchar(255) NOT NULL,
  `adresse` varchar(255) DEFAULT NULL,
  `code_postal` varchar(255) DEFAULT NULL,
  `ville` varchar(255) DEFAULT NULL,
  `pays` varchar(255) DEFAULT NULL,
  `telephone` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `site_web` varchar(255) DEFAULT NULL,
  `siret` varchar(255) DEFAULT NULL,
  `actif` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `clients`
--

INSERT INTO `clients` (`id`, `societe_id`, `code`, `nom`, `identifiant_unique`, `adresse`, `code_postal`, `ville`, `pays`, `telephone`, `email`, `site_web`, `siret`, `actif`, `created_at`, `updated_at`) VALUES
(1, 1, 'C0001', 'Client Principal', 'CLIENT_PRINCIPAL', '123 Avenue des Champs-Élysées', '75008', 'Paris', 'France', '01 23 45 67 89', 'contact@entreprise.fr', 'www.entreprise.fr', '12345678901234', 1, '2025-05-30 08:32:22', '2025-05-30 17:41:51'),
(3, 2, 'Velit reprehenderit', 'Ea voluptates quo be', 'Sed est id mollitia', 'Nihil est in nihil n', 'Qui temporibus ipsa', 'Eiusmod in dolorum q', 'Aliquid accusantium', '+1 (476) 181-5997', 'figobibax@mailinator.com', NULL, NULL, 1, '2025-06-01 10:22:29', '2025-06-01 10:22:29');

-- --------------------------------------------------------

--
-- Structure de la table `comptes`
--

CREATE TABLE `comptes` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `dossier_id` bigint(20) UNSIGNED NOT NULL,
  `numero` varchar(255) NOT NULL,
  `libelle` varchar(255) NOT NULL,
  `type` varchar(255) NOT NULL,
  `est_actif` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `comptes`
--

INSERT INTO `comptes` (`id`, `dossier_id`, `numero`, `libelle`, `type`, `est_actif`, `created_at`, `updated_at`) VALUES
(1, 1, '21', 'Immobilisations corporelles', 'immobilisation', 1, '2025-05-29 17:16:41', '2025-05-29 17:28:32'),
(2, 1, '28', 'Amortissements des immobilisations', 'amortissement', 1, '2025-05-29 17:16:41', '2025-05-29 17:28:32'),
(3, 1, '68', 'Dotations aux amortissements', 'dotation', 1, '2025-05-29 17:16:41', '2025-05-29 17:28:32'),
(4, 1, '44562', 'TVA déductible sur immobilisations', 'tva', 1, '2025-05-29 17:16:41', '2025-05-29 17:28:32');

-- --------------------------------------------------------

--
-- Structure de la table `comptescompta`
--

CREATE TABLE `comptescompta` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `dossier_id` bigint(20) UNSIGNED NOT NULL,
  `numero` varchar(255) NOT NULL,
  `libelle` varchar(255) NOT NULL,
  `type` varchar(255) NOT NULL,
  `est_actif` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `comptescompta`
--

INSERT INTO `comptescompta` (`id`, `dossier_id`, `numero`, `libelle`, `type`, `est_actif`, `created_at`, `updated_at`) VALUES
(1, 1, '21', 'Immobilisations corporelles', 'actif', 0, '2025-05-29 17:16:41', '2025-05-31 10:17:19'),
(2, 1, '28', 'Amortissements des immobilisations', 'amortissement', 1, '2025-05-29 17:16:41', '2025-05-29 17:28:32'),
(3, 1, '68', 'Dotations aux amortissements', 'dotation', 1, '2025-05-29 17:16:41', '2025-05-29 17:28:32'),
(4, 1, '44562', 'TVA déductible sur immobilisations', 'tva', 1, '2025-05-29 17:16:41', '2025-05-29 17:28:32'),
(5, 1, '75', 'Et unde facere corpo', 'produit', 0, '2025-06-01 11:03:16', '2025-06-01 11:04:27');

-- --------------------------------------------------------

--
-- Structure de la table `contrats`
--

CREATE TABLE `contrats` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `dossier_id` bigint(20) UNSIGNED NOT NULL,
  `reference` varchar(255) NOT NULL,
  `type` varchar(255) NOT NULL,
  `prestataire_id` bigint(20) UNSIGNED DEFAULT NULL,
  `fournisseur_id` bigint(20) UNSIGNED DEFAULT NULL,
  `date_debut` date DEFAULT NULL,
  `date_fin` date DEFAULT NULL,
  `description` text DEFAULT NULL,
  `montant_periodique` decimal(15,2) DEFAULT NULL,
  `periodicite` varchar(255) DEFAULT NULL,
  `date_prochaine_echeance` date DEFAULT NULL,
  `statut` varchar(255) NOT NULL DEFAULT 'actif',
  `document_path` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `contrats`
--

INSERT INTO `contrats` (`id`, `dossier_id`, `reference`, `type`, `prestataire_id`, `fournisseur_id`, `date_debut`, `date_fin`, `description`, `montant_periodique`, `periodicite`, `date_prochaine_echeance`, `statut`, `document_path`, `created_at`, `updated_at`) VALUES
(1, 1, 'CONT-MAINT-INFO-2023', 'maintenance', 1, NULL, '2025-03-01', '2026-03-01', 'Contrat de maintenance du parc informatique', 1000.00, 'mensuel', '2025-06-29', 'actif', NULL, '2025-05-29 17:16:42', '2025-05-29 17:28:33'),
(2, 1, 'CONT-LOC-VEH-2023', 'location', 2, 3, '2025-01-15', '2028-01-15', 'Contrat de location longue durée véhicule de direction', 450.00, 'mensuel', '2025-06-29', 'actif', NULL, '2025-05-29 17:16:42', '2025-06-01 16:00:41'),
(3, 1, 'CONT-SECU-2023', 'maintenance', 2, NULL, '2025-02-01', '2026-02-01', 'Contrat de maintenance des systèmes de sécurité', 750.00, 'trimestriel', '2025-08-29', 'actif', NULL, '2025-05-29 17:16:42', '2025-05-29 17:28:33'),
(4, 1, 'CONT-NETT-2023', 'maintenance', 3, NULL, '2025-04-01', '2026-04-01', 'Contrat de nettoyage des locaux', 1200.00, 'mensuel', '2025-06-29', 'actif', NULL, '2025-05-29 17:16:42', '2025-05-29 17:28:33'),
(5, 1, 'Dolores ullamco id t', 'maintenance', 2, 1, '1991-11-18', '1999-10-19', 'Voluptatem similique', 21.00, 'annuel', '1995-05-29', 'actif', NULL, '2025-06-01 15:59:22', '2025-06-01 16:17:06'),
(7, 1, 'Cupiditate et sunt', 'maintenance', 1, 3, '2022-08-02', '2025-05-17', 'Anim distinctio Rep', 71.00, 'annuel', '2023-04-12', 'inactif', NULL, '2025-06-01 18:55:11', '2025-06-01 18:55:11');

-- --------------------------------------------------------

--
-- Structure de la table `contrat_immobilisation`
--

CREATE TABLE `contrat_immobilisation` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `contrat_id` bigint(20) UNSIGNED NOT NULL,
  `immobilisation_id` bigint(20) UNSIGNED NOT NULL,
  `date_liaison` date NOT NULL DEFAULT '2025-05-29',
  `date_deliaison` date DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `contrat_immobilisation`
--

INSERT INTO `contrat_immobilisation` (`id`, `contrat_id`, `immobilisation_id`, `date_liaison`, `date_deliaison`, `created_at`, `updated_at`) VALUES
(1, 4, 3, '2025-06-01', '2025-06-01', '2025-06-01 16:23:55', '2025-06-01 16:28:05'),
(2, 4, 2, '2025-06-01', '2025-06-01', '2025-06-01 16:26:27', '2025-06-01 16:33:33'),
(3, 4, 3, '2025-06-01', NULL, '2025-06-01 16:33:48', '2025-06-01 16:33:48'),
(4, 4, 6, '2025-06-01', '2025-06-01', '2025-06-01 16:34:06', '2025-06-01 16:34:30'),
(5, 7, 6, '2025-06-01', NULL, '2025-06-01 18:55:30', '2025-06-01 18:55:30');

-- --------------------------------------------------------

--
-- Structure de la table `details_credit_bail`
--

CREATE TABLE `details_credit_bail` (
  `contrat_id` bigint(20) UNSIGNED NOT NULL,
  `duree_mois` int(11) DEFAULT NULL,
  `valeur_residuelle` decimal(15,2) NOT NULL COMMENT 'Valeur de rachat',
  `periodicite` enum('mensuel','trimestriel','semestriel','annuel') NOT NULL,
  `montant_redevance_periodique` decimal(15,2) NOT NULL,
  `taux_interet_periodique` decimal(8,5) DEFAULT NULL COMMENT 'Taux d"intérêt utilisé pour calculer la part intérêt',
  `montant_total_redevances` decimal(15,2) DEFAULT NULL COMMENT 'Calculé ou stocké',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `dossiers`
--

CREATE TABLE `dossiers` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `societe_id` bigint(20) UNSIGNED DEFAULT NULL,
  `client_id` bigint(20) UNSIGNED DEFAULT NULL,
  `code` varchar(255) NOT NULL,
  `nom` varchar(255) NOT NULL,
  `libelle` varchar(255) NOT NULL,
  `exercice_debut` date DEFAULT NULL,
  `exercice_fin` date DEFAULT NULL,
  `est_cloture` tinyint(1) NOT NULL DEFAULT 0,
  `date_cloture` date DEFAULT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `dossiers`
--

INSERT INTO `dossiers` (`id`, `societe_id`, `client_id`, `code`, `nom`, `libelle`, `exercice_debut`, `exercice_fin`, `est_cloture`, `date_cloture`, `description`, `created_at`, `updated_at`) VALUES
(1, 1, 1, '1', 'EMEX CONSULTING', 'EMEX', '2024-01-01', '2025-12-31', 0, NULL, 'Dossier par défaut créé automatiquement', '2025-05-29 17:16:41', '2025-05-31 14:38:34'),
(3, 2, 3, 'Excepturi voluptatem', 'Elit autem ullam te', 'Est excepteur nobis', '1987-09-02', '1988-10-11', 0, NULL, 'Omnis veniam invent', '2025-06-01 10:23:05', '2025-06-01 10:23:05');

-- --------------------------------------------------------

--
-- Structure de la table `dossier_user`
--

CREATE TABLE `dossier_user` (
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `dossier_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `dossier_user`
--

INSERT INTO `dossier_user` (`user_id`, `dossier_id`) VALUES
(1, 1),
(2, 1),
(2, 3),
(3, 1),
(4, 1),
(5, 1),
(6, 1),
(6, 3);

-- --------------------------------------------------------

--
-- Structure de la table `echeances_credit_bail`
--

CREATE TABLE `echeances_credit_bail` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `detail_credit_bail_contrat_id` bigint(20) UNSIGNED NOT NULL,
  `numero_echeance` int(11) NOT NULL,
  `date_echeance` date NOT NULL,
  `montant_redevance` decimal(15,2) NOT NULL,
  `part_interet` decimal(15,2) NOT NULL,
  `part_capital` decimal(15,2) NOT NULL COMMENT 'Redevance - Intérêt',
  `capital_restant_du` decimal(15,2) NOT NULL,
  `statut` enum('a_payer','payee','en_retard') NOT NULL DEFAULT 'a_payer',
  `date_paiement` date DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `familles`
--

CREATE TABLE `familles` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `dossier_id` bigint(20) UNSIGNED NOT NULL,
  `code` varchar(255) NOT NULL,
  `libelle` varchar(255) NOT NULL,
  `comptecompta_immobilisation_id` bigint(20) UNSIGNED DEFAULT NULL,
  `comptecompta_amortissement_id` bigint(20) UNSIGNED DEFAULT NULL,
  `comptecompta_dotation_id` bigint(20) UNSIGNED DEFAULT NULL,
  `duree_amortissement_par_defaut` int(11) DEFAULT NULL,
  `methode_amortissement_par_defaut` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `familles`
--

INSERT INTO `familles` (`id`, `dossier_id`, `code`, `libelle`, `comptecompta_immobilisation_id`, `comptecompta_amortissement_id`, `comptecompta_dotation_id`, `duree_amortissement_par_defaut`, `methode_amortissement_par_defaut`, `created_at`, `updated_at`) VALUES
(1, 1, 'MAT_INFO', 'Matériel informatique', 1, 2, 3, 5, 'lineaire', '2025-05-29 17:16:41', '2025-05-31 10:47:01'),
(2, 1, 'MOB_BUR', 'Mobilier de bureau', 1, 2, 3, 10, 'lineaire', '2025-05-29 17:16:41', '2025-05-29 17:16:41'),
(3, 1, 'MAT_TRANS', 'Matériel de transport', 1, 2, 3, 5, 'lineaire', '2025-05-29 17:16:41', '2025-05-29 17:16:41'),
(4, 1, 'MAT_IND', 'Matériel industriel', 1, 2, 3, 5, 'lineaire', '2025-05-29 17:16:41', '2025-05-29 17:16:41'),
(5, 1, 'LOGICIELS', 'Logiciels', 1, 2, 3, 1, 'lineaire', '2025-05-29 17:16:41', '2025-05-29 17:16:41'),
(6, 1, 'Omnis anim labore po', 'Minima sit eligendi', 4, 3, 2, 5, 'degressif', '2025-06-01 10:32:20', '2025-06-01 10:32:30'),
(7, 1, 'Mollitia rerum liber', 'Voluptatum non autem', 3, 5, 5, 30, 'degressif', '2025-06-01 19:22:08', '2025-06-01 19:22:08');

-- --------------------------------------------------------

--
-- Structure de la table `fournisseurs`
--

CREATE TABLE `fournisseurs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `dossier_id` bigint(20) UNSIGNED NOT NULL,
  `code` varchar(255) NOT NULL,
  `nom` varchar(255) NOT NULL,
  `adresse` varchar(255) DEFAULT NULL,
  `code_postal` varchar(255) DEFAULT NULL,
  `ville` varchar(255) DEFAULT NULL,
  `pays` varchar(255) DEFAULT NULL,
  `telephone` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `site_web` varchar(255) DEFAULT NULL,
  `siret` varchar(255) DEFAULT NULL,
  `contact_nom` varchar(255) DEFAULT NULL,
  `contact_telephone` varchar(255) DEFAULT NULL,
  `contact_email` varchar(255) DEFAULT NULL,
  `est_actif` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `fournisseurs`
--

INSERT INTO `fournisseurs` (`id`, `dossier_id`, `code`, `nom`, `adresse`, `code_postal`, `ville`, `pays`, `telephone`, `email`, `site_web`, `siret`, `contact_nom`, `contact_telephone`, `contact_email`, `est_actif`, `created_at`, `updated_at`) VALUES
(1, 1, 'DELL', 'Dell Informatique', '1 Place de la Défense', '92400', 'Courbevoie', 'France', '01 55 66 77 88', 'contact@dell.fr', 'www.dell.fr', '12345678901234', 'Service Commercial', '01 55 66 77 89', 'commercial@dell.fr', 1, '2025-05-29 17:16:42', '2025-05-29 17:28:32'),
(2, 1, 'IKEA', 'IKEA France', '425 rue Henri Barbusse', '78370', 'Plaisir', 'France', '01 30 81 27 00', 'contact@ikea.fr', 'www.ikea.fr', '23456789012345', 'Service Entreprises', '01 30 81 27 01', 'entreprises@ikea.fr', 1, '2025-05-29 17:16:42', '2025-05-29 17:28:32'),
(3, 1, 'RENAULT', 'Renault SAS', '13-15 Quai Alphonse Le Gallo', '92100', 'Boulogne-Billancourt', 'France', '01 76 84 04 04', 'contact@renault.fr', 'www.renault.fr', '34567890123456', 'Service Flotte Entreprise', '01 76 84 04 05', 'flotte@renault.fr', 1, '2025-05-29 17:16:42', '2025-05-29 17:28:32'),
(4, 1, 'ADOBE', 'Adobe France', '112 Avenue Kléber', '75016', 'Paris', 'France', '01 49 27 56 00', 'contact@adobe.fr', 'www.adobe.fr', '45678901234567', 'Service Licences', '01 49 27 56 01', 'licences@adobe.fr', 1, '2025-05-29 17:16:42', '2025-05-29 17:28:32');

-- --------------------------------------------------------

--
-- Structure de la table `immobilisations`
--

CREATE TABLE `immobilisations` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `dossier_id` bigint(20) UNSIGNED NOT NULL,
  `numero` varchar(255) DEFAULT NULL,
  `code` varchar(255) DEFAULT NULL,
  `libelle` varchar(255) DEFAULT NULL,
  `designation` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `famille_id` bigint(20) UNSIGNED DEFAULT NULL,
  `site_id` bigint(20) UNSIGNED DEFAULT NULL,
  `service_id` bigint(20) UNSIGNED DEFAULT NULL,
  `date_acquisition` date DEFAULT NULL,
  `date_mise_service` date DEFAULT NULL,
  `valeur_acquisition` decimal(15,2) DEFAULT NULL,
  `tva_deductible` decimal(15,2) DEFAULT NULL,
  `comptecompta_immobilisation_id` bigint(20) UNSIGNED DEFAULT NULL,
  `comptecompta_amortissement_id` bigint(20) UNSIGNED DEFAULT NULL,
  `comptecompta_dotation_id` bigint(20) UNSIGNED DEFAULT NULL,
  `comptecompta_tva_id` bigint(20) UNSIGNED DEFAULT NULL,
  `duree_amortissement` int(11) DEFAULT NULL,
  `methode_amortissement` varchar(255) DEFAULT NULL,
  `coefficient_degressif` decimal(8,2) DEFAULT NULL,
  `base_amortissement` decimal(15,2) DEFAULT NULL,
  `valeur_residuelle` decimal(15,2) DEFAULT NULL,
  `amortissements_cumules` decimal(15,2) DEFAULT 0.00,
  `statut` varchar(255) NOT NULL DEFAULT 'actif',
  `date_sortie` date DEFAULT NULL,
  `prix_vente` decimal(15,2) DEFAULT NULL,
  `fournisseur_id` bigint(20) UNSIGNED DEFAULT NULL,
  `numero_facture` varchar(255) DEFAULT NULL,
  `numero_serie` varchar(255) DEFAULT NULL,
  `code_barre` varchar(255) DEFAULT NULL,
  `photo_path` varchar(255) DEFAULT NULL,
  `document_path` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `immobilisations`
--

INSERT INTO `immobilisations` (`id`, `dossier_id`, `numero`, `code`, `libelle`, `designation`, `description`, `famille_id`, `site_id`, `service_id`, `date_acquisition`, `date_mise_service`, `valeur_acquisition`, `tva_deductible`, `comptecompta_immobilisation_id`, `comptecompta_amortissement_id`, `comptecompta_dotation_id`, `comptecompta_tva_id`, `duree_amortissement`, `methode_amortissement`, `coefficient_degressif`, `base_amortissement`, `valeur_residuelle`, `amortissements_cumules`, `statut`, `date_sortie`, `prix_vente`, `fournisseur_id`, `numero_facture`, `numero_serie`, `code_barre`, `photo_path`, `document_path`, `created_at`, `updated_at`) VALUES
(1, 1, 'IMM-001', 'PC-DIR-001', 'Ordinateur portable Direction', 'Ordinateur portable Direction', 'MacBook Pro 16\" 2023', 3, 1, 1, '2024-11-29', '2024-12-29', 2500.00, 500.00, 1, 2, 3, 4, 5, 'lineaire', NULL, 2500.00, 0.00, 416.67, 'actif', NULL, NULL, 1, 'FACT-2023-001', 'MBP2023-XYZ789', NULL, NULL, NULL, '2025-05-29 17:16:42', '2025-06-01 15:01:19'),
(2, 1, 'IMM-002', 'PC-COMPTA-001', 'Ordinateur fixe Comptabilité', 'Ordinateur fixe Comptabilité', 'Dell OptiPlex 7090', 1, 1, 2, '2024-05-29', '2024-06-29', 1200.00, 240.00, 1, 2, 3, 4, 3, 'lineaire', NULL, 1200.00, 0.00, 366.67, 'actif', NULL, NULL, 1, 'FACT-2022-056', 'DELL7090-ABC123', NULL, NULL, NULL, '2025-05-29 17:16:42', '2025-05-29 17:28:32'),
(3, 1, 'IMM-003', 'MOB-DIR-001', 'Bureau direction', 'Bureau direction', 'Bureau design avec retour', 2, 1, 1, '2023-05-29', '2023-05-29', 850.00, 170.00, 1, 2, 3, 4, 10, 'lineaire', NULL, 850.00, 0.00, 170.00, 'actif', NULL, NULL, 2, 'FACT-2021-023', NULL, NULL, NULL, NULL, '2025-05-29 17:16:42', '2025-05-29 17:28:32'),
(4, 1, 'IMM-004', 'VEH-LOG-001', 'Camionnette logistique', 'Camionnette logistique', 'Renault Master L2H2', 3, 3, 5, '2024-05-29', '2024-06-05', 32000.00, 6400.00, 1, 2, 3, 4, 5, 'lineaire', NULL, 32000.00, 5000.00, 5400.00, 'actif', NULL, NULL, 3, 'FACT-2022-089', 'VF1MA000567891234', NULL, NULL, NULL, '2025-05-29 17:16:42', '2025-05-29 17:28:33'),
(6, 1, NULL, NULL, NULL, 'Ea veniam tenetur m', 'Quidem nobis quibusd', 3, 2, 4, '2010-04-02', '2010-04-02', 64.00, NULL, 1, 2, 3, 4, 5, 'lineaire', NULL, 73.00, NULL, 0.00, 'actif', NULL, NULL, 2, 'Tempor animi amet', NULL, 'Adipisicing laboris', NULL, NULL, '2025-06-01 15:04:37', '2025-06-01 15:46:48');

-- --------------------------------------------------------

--
-- Structure de la table `inventaires`
--

CREATE TABLE `inventaires` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `dossier_id` bigint(20) UNSIGNED NOT NULL,
  `reference` varchar(255) NOT NULL COMMENT 'Référence unique de la session d''inventaire',
  `date_debut` date NOT NULL,
  `date_fin` date DEFAULT NULL,
  `responsable_id` bigint(20) UNSIGNED NOT NULL,
  `site_id` bigint(20) UNSIGNED DEFAULT NULL,
  `service_id` bigint(20) UNSIGNED DEFAULT NULL,
  `statut` enum('planifie','en_cours','termine','annule') NOT NULL DEFAULT 'planifie',
  `description` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `inventaires`
--

INSERT INTO `inventaires` (`id`, `dossier_id`, `reference`, `date_debut`, `date_fin`, `responsable_id`, `site_id`, `service_id`, `statut`, `description`, `created_at`, `updated_at`) VALUES
(1, 1, 'INV-20250601-123252', '2025-06-01', '2025-06-01', 2, NULL, NULL, 'en_cours', 'a', '2025-06-01 11:33:00', '2025-06-01 14:06:33'),
(2, 3, 'INV-20250601-134531', '2025-06-01', NULL, 2, NULL, NULL, 'planifie', 'test2', '2025-06-01 12:45:36', '2025-06-01 12:45:36'),
(4, 1, 'INV-20250601-145438', '2025-06-01', NULL, 2, NULL, NULL, 'en_cours', 'aaaa', '2025-06-01 13:57:17', '2025-06-01 14:01:52'),
(5, 1, 'INV-20250601-150219', '2025-06-01', NULL, 2, NULL, NULL, 'en_cours', 'xxxxx', '2025-06-01 14:02:27', '2025-06-01 14:02:27');

-- --------------------------------------------------------

--
-- Structure de la table `inventaire_details`
--

CREATE TABLE `inventaire_details` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `inventaire_id` bigint(20) UNSIGNED NOT NULL,
  `immobilisation_id` bigint(20) UNSIGNED DEFAULT NULL,
  `code_barre_scan` varchar(255) DEFAULT NULL,
  `date_scan` datetime DEFAULT NULL,
  `statut_constate` enum('trouve','non_trouve_base','non_trouve_physique','ecart_localisation','ecart_etat','endommage') DEFAULT NULL,
  `site_scan_id` bigint(20) UNSIGNED DEFAULT NULL,
  `service_scan_id` bigint(20) UNSIGNED DEFAULT NULL,
  `commentaire` text DEFAULT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `inventaire_details`
--

INSERT INTO `inventaire_details` (`id`, `inventaire_id`, `immobilisation_id`, `code_barre_scan`, `date_scan`, `statut_constate`, `site_scan_id`, `service_scan_id`, `commentaire`, `user_id`, `created_at`, `updated_at`) VALUES
(1, 4, 1, NULL, '2025-06-01 14:57:17', 'trouve', 1, 1, 'dispo', 2, '2025-06-01 13:57:17', '2025-06-01 13:57:17'),
(2, 4, 2, NULL, '2025-06-01 14:57:17', 'non_trouve_physique', 1, 2, NULL, 2, '2025-06-01 13:57:17', '2025-06-01 13:57:17'),
(3, 4, 3, NULL, '2025-06-01 14:57:17', 'non_trouve_physique', 1, 1, NULL, 2, '2025-06-01 13:57:17', '2025-06-01 13:57:17'),
(4, 4, 4, NULL, '2025-06-01 14:57:17', 'non_trouve_physique', 3, 5, NULL, 2, '2025-06-01 13:57:17', '2025-06-01 13:57:17'),
(5, 5, 1, NULL, '2025-06-01 15:02:27', 'non_trouve_physique', 1, 1, NULL, 2, '2025-06-01 14:02:27', '2025-06-01 14:02:27'),
(6, 5, 2, NULL, '2025-06-01 15:02:27', 'non_trouve_physique', 1, 2, NULL, 2, '2025-06-01 14:02:27', '2025-06-01 14:02:27'),
(7, 5, 3, NULL, '2025-06-01 15:02:27', 'non_trouve_physique', 1, 1, NULL, 2, '2025-06-01 14:02:27', '2025-06-01 14:02:27'),
(8, 5, 4, NULL, '2025-06-01 15:02:27', 'trouve', 3, 5, NULL, 2, '2025-06-01 14:02:27', '2025-06-01 14:02:27');

-- --------------------------------------------------------

--
-- Structure de la table `maintenances`
--

CREATE TABLE `maintenances` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `immobilisation_id` bigint(20) UNSIGNED NOT NULL,
  `date_intervention` date NOT NULL,
  `type` enum('preventive','curative','amelioration') NOT NULL,
  `description` text NOT NULL,
  `prestataire_id` bigint(20) UNSIGNED DEFAULT NULL,
  `cout` decimal(15,2) NOT NULL,
  `est_charge` tinyint(1) NOT NULL DEFAULT 1 COMMENT 'True = charge, False = à capitaliser/incorporer',
  `date_fin_intervention` date DEFAULT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2014_10_12_000000_create_users_table', 1),
(2, '2014_10_12_100000_create_password_reset_tokens_table', 1),
(3, '2014_10_12_100000_create_password_resets_table', 1),
(4, '2019_08_19_000000_create_failed_jobs_table', 1),
(5, '2019_12_14_000001_create_personal_access_tokens_table', 1),
(6, '2025_05_04_140140_create_dossiers_table', 1),
(7, '2025_05_04_140141_create_comptes_table', 1),
(8, '2025_05_04_140156_create_roles_table', 1),
(9, '2025_05_04_140157_create_fournisseurs_table', 1),
(10, '2025_05_04_140157_create_permissions_table', 1),
(11, '2025_05_04_140157_create_prestataires_table', 1),
(12, '2025_05_04_140157_create_role_permission_table', 1),
(13, '2025_05_04_140157_create_sites_table', 1),
(14, '2025_05_04_140158_add_role_id_to_users_table', 1),
(15, '2025_05_04_140158_create_contrats_table', 1),
(16, '2025_05_04_140158_create_services_table', 1),
(17, '2025_05_04_140159_create_familles_table', 1),
(18, '2025_05_04_140159_create_immobilisations_table', 1),
(19, '2025_05_04_140159_create_mouvements_immobilisations_table', 1),
(20, '2025_05_04_140159_create_plans_amortissement_table', 1),
(21, '2025_05_04_140159_create_provisions_depreciations_table', 1),
(22, '2025_05_04_140200_create_contrat_immobilisation_table', 1),
(23, '2025_05_04_140200_create_details_credit_bail_table', 1),
(24, '2025_05_04_140200_create_echeances_credit_bail_table', 1),
(25, '2025_05_04_140200_create_inventaires_table', 1),
(26, '2025_05_04_140200_create_maintenances_table', 1),
(27, '2025_05_04_140201_add_dossier_id_to_tables', 1),
(28, '2025_05_04_140201_create_inventaire_details_table', 1),
(29, '2025_05_04_140201_create_parametres_application_table', 1),
(30, '2025_05_29_133400_force_add_description_to_dossiers', 1),
(31, '2025_05_29_133800_fix_compte_id_in_dossiers_table', 1),
(32, '2025_05_29_134000_fix_familles_table_structure', 1);

-- --------------------------------------------------------

--
-- Structure de la table `mouvements_immobilisations`
--

CREATE TABLE `mouvements_immobilisations` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `immobilisation_id` bigint(20) UNSIGNED NOT NULL,
  `type_mouvement` enum('acquisition','cession','virement_entrant','virement_sortant','retrait','mise_en_service','augmentation_valeur','diminution_valeur','revaluation','amortissement_exceptionnel') NOT NULL,
  `date_mouvement` date NOT NULL,
  `valeur_mouvement` decimal(15,2) NOT NULL COMMENT 'Valeur brute du mouvement (e.g., prix de cession, valeur ajoutée, dotation exceptionnelle)',
  `valeur_nette_comptable` decimal(15,2) DEFAULT NULL COMMENT 'VNA au moment du mouvement (pour cessions/retraits)',
  `compte_contrepartie` varchar(20) DEFAULT NULL COMMENT 'Compte utilisé (e.g., compte PCI pour cession)',
  `description` text DEFAULT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `parametres_application`
--

CREATE TABLE `parametres_application` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `dossier_id` bigint(20) UNSIGNED DEFAULT NULL,
  `cle` varchar(255) NOT NULL,
  `valeur` text NOT NULL,
  `description` text DEFAULT NULL,
  `type_valeur` enum('string','integer','decimal','boolean','json','text') NOT NULL DEFAULT 'string',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `password_resets`
--

CREATE TABLE `password_resets` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `permissions`
--

CREATE TABLE `permissions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `permissions`
--

INSERT INTO `permissions` (`id`, `name`, `description`, `created_at`, `updated_at`) VALUES
(1, 'access-admin', 'Accéder à la section administration', '2025-05-30 08:41:14', '2025-05-30 08:41:14'),
(2, 'manage-users', 'Gérer les utilisateurs', '2025-05-30 08:41:14', '2025-05-30 08:41:14'),
(3, 'manage-roles', 'Gérer les rôles et permissions', '2025-05-30 08:41:14', '2025-05-30 08:41:14'),
(4, 'manage-dossiers', 'Gérer les dossiers', '2025-05-30 08:41:14', '2025-05-30 08:41:14'),
(5, 'manage-immobilisations', 'Gérer les immobilisations', '2025-05-30 08:41:14', '2025-05-30 08:41:14'),
(6, 'view-immobilisations', 'Voir les immobilisations', '2025-05-30 08:41:14', '2025-05-30 08:41:14'),
(7, 'manage-contrats', 'Gérer les contrats', '2025-05-30 08:41:14', '2025-05-30 08:41:14'),
(8, 'view-contrats', 'Voir les contrats', '2025-05-30 08:41:14', '2025-05-30 08:41:14'),
(9, 'manage-inventaires', 'Gérer les inventaires', '2025-05-30 08:41:14', '2025-05-30 08:41:14'),
(10, 'view-inventaires', 'Voir les inventaires', '2025-05-30 08:41:14', '2025-05-30 08:41:14'),
(11, 'manage-parametres', 'Gérer les paramètres', '2025-05-30 08:41:14', '2025-05-30 08:41:14'),
(12, 'view-parametres', 'Voir les paramètres', '2025-05-30 08:41:14', '2025-05-30 08:41:14'),
(13, 'export-data', 'Exporter les données', '2025-05-30 08:41:14', '2025-05-30 08:41:14');

-- --------------------------------------------------------

--
-- Structure de la table `personal_access_tokens`
--

CREATE TABLE `personal_access_tokens` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tokenable_type` varchar(255) NOT NULL,
  `tokenable_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `token` varchar(64) NOT NULL,
  `abilities` text DEFAULT NULL,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `plans_amortissement`
--

CREATE TABLE `plans_amortissement` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `dossier_id` bigint(20) UNSIGNED NOT NULL,
  `immobilisation_id` bigint(20) UNSIGNED NOT NULL,
  `annee_exercice` int(11) NOT NULL,
  `base_amortissable` decimal(15,2) NOT NULL,
  `taux_applique` decimal(5,2) NOT NULL COMMENT 'Taux linéaire ou dégressif selon l"année',
  `dotation_annuelle` decimal(15,2) NOT NULL,
  `amortissement_cumule_debut` decimal(15,2) DEFAULT NULL,
  `amortissement_cumule_fin` decimal(15,2) DEFAULT NULL,
  `vna_fin_exercice` decimal(15,2) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `immobilisations_libelle` varchar(255) DEFAULT NULL,
  `immobilisations_description` text DEFAULT NULL,
  `immobilisations_famille_id` int(11) DEFAULT NULL,
  `famille` varchar(255) DEFAULT NULL,
  `immobilisation_date_acquisition` date DEFAULT NULL,
  `immobilisation_date_mise_service` date DEFAULT NULL,
  `date_derniere_cloture` date DEFAULT NULL,
  `date_prochaine_cloture` date DEFAULT NULL,
  `duree_periode` int(11) DEFAULT NULL COMMENT 'Durée en jours entre les deux dates de clôture',
  `dotation_periode` decimal(15,2) DEFAULT NULL COMMENT 'Dotation calculée pour la période',
  `duree_amortissement_par_defaut` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `plans_amortissement`
--

INSERT INTO `plans_amortissement` (`id`, `dossier_id`, `immobilisation_id`, `annee_exercice`, `base_amortissable`, `taux_applique`, `dotation_annuelle`, `amortissement_cumule_debut`, `amortissement_cumule_fin`, `vna_fin_exercice`, `created_at`, `updated_at`, `immobilisations_libelle`, `immobilisations_description`, `immobilisations_famille_id`, `famille`, `immobilisation_date_acquisition`, `immobilisation_date_mise_service`, `date_derniere_cloture`, `date_prochaine_cloture`, `duree_periode`, `dotation_periode`, `duree_amortissement_par_defaut`) VALUES
(239, 1, 1, 2025, 2500.00, 0.20, 500.00, 2.74, 502.74, 1997.26, '2025-06-01 18:44:48', '2025-06-01 18:44:48', 'Ordinateur portable Direction', 'MacBook Pro 16\" 2023', 3, 'Matériel de transport', '2024-11-29', '2024-12-29', '2024-12-31', '2025-12-31', 365, 500.00, 5),
(240, 1, 2, 2025, 1200.00, 0.33, 400.00, 202.74, 602.74, 597.26, '2025-06-01 18:44:48', '2025-06-01 18:44:48', 'Ordinateur fixe Comptabilité', 'Dell OptiPlex 7090', 1, 'Matériel informatique', '2024-05-29', '2024-06-29', '2024-12-31', '2025-12-31', 365, 400.00, 5),
(241, 1, 3, 2025, 850.00, 0.10, 85.00, 135.53, 220.53, 629.47, '2025-06-01 18:44:48', '2025-06-01 18:44:48', 'Bureau direction', 'Bureau design avec retour', 2, 'Mobilier de bureau', '2023-05-29', '2023-05-29', '2024-12-31', '2025-12-31', 365, 85.00, 10),
(242, 1, 4, 2025, 32000.00, 0.20, 6400.00, 3664.66, 10064.66, 21935.34, '2025-06-01 18:44:48', '2025-06-01 18:44:48', 'Camionnette logistique', 'Renault Master L2H2', 3, 'Matériel de transport', '2024-05-29', '2024-06-05', '2024-12-31', '2025-12-31', 365, 6400.00, 5),
(243, 1, 6, 2025, 73.00, 0.20, 14.60, 73.00, 73.00, 0.00, '2025-06-01 18:44:48', '2025-06-01 18:44:48', NULL, 'Quidem nobis quibusd', 3, 'Matériel de transport', '2010-04-02', '2010-04-02', '2024-12-31', '2025-12-31', 365, 0.00, 5);

-- --------------------------------------------------------

--
-- Structure de la table `prestataires`
--

CREATE TABLE `prestataires` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `dossier_id` bigint(20) UNSIGNED NOT NULL,
  `code` varchar(255) NOT NULL,
  `nom` varchar(255) NOT NULL,
  `adresse` varchar(255) DEFAULT NULL,
  `code_postal` varchar(255) DEFAULT NULL,
  `ville` varchar(255) DEFAULT NULL,
  `pays` varchar(255) DEFAULT NULL,
  `telephone` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `site_web` varchar(255) DEFAULT NULL,
  `siret` varchar(255) DEFAULT NULL,
  `contact_nom` varchar(255) DEFAULT NULL,
  `contact_telephone` varchar(255) DEFAULT NULL,
  `contact_email` varchar(255) DEFAULT NULL,
  `est_actif` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `prestataires`
--

INSERT INTO `prestataires` (`id`, `dossier_id`, `code`, `nom`, `adresse`, `code_postal`, `ville`, `pays`, `telephone`, `email`, `site_web`, `siret`, `contact_nom`, `contact_telephone`, `contact_email`, `est_actif`, `created_at`, `updated_at`) VALUES
(1, 1, 'MAINT_INFO', 'InfoMaintenance', '45 Rue de la Maintenance', '75011', 'Paris', 'France', '01 45 67 89 10', 'contact@infomaintenance.fr', 'www.infomaintenance.fr', '56789012345678', 'Service Clients', '01 45 67 89 11', 'clients@infomaintenance.fr', 1, '2025-05-29 17:16:42', '2025-05-29 17:28:32'),
(2, 1, 'SECURITE', 'SecuriPro', '12 Avenue de la Sécurité', '69002', 'Lyon', 'France', '04 72 40 50 60', 'contact@securipro.fr', 'www.securipro.fr', '67890123456789', 'Service Commercial', '04 72 40 50 61', 'commercial@securipro.fr', 1, '2025-05-29 17:16:42', '2025-05-29 17:28:32'),
(3, 1, 'NETTOYAGE', 'CleanOffice', '8 Rue du Nettoyage', '33000', 'Bordeaux', 'France', '05 56 78 90 12', 'contact@cleanoffice.fr', 'www.cleanoffice.fr', '78901234567890', 'Service Planification', '05 56 78 90 13', 'planning@cleanoffice.fr', 1, '2025-05-29 17:16:42', '2025-05-29 17:28:32');

-- --------------------------------------------------------

--
-- Structure de la table `provisions_depreciations`
--

CREATE TABLE `provisions_depreciations` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `immobilisation_id` bigint(20) UNSIGNED NOT NULL,
  `annee_exercice` int(11) NOT NULL,
  `type` enum('dotation','reprise') NOT NULL,
  `montant` decimal(15,2) NOT NULL,
  `motif` text DEFAULT NULL,
  `date_provision` date NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `roles`
--

CREATE TABLE `roles` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `roles`
--

INSERT INTO `roles` (`id`, `name`, `description`, `created_at`, `updated_at`) VALUES
(1, 'super_admin', 'Accès complet à toutes les fonctionnalités du système', '2025-05-29 17:26:49', '2025-05-29 17:26:49'),
(2, 'admin', 'Gestion des utilisateurs et des données de l\'entreprise', '2025-05-29 17:26:49', '2025-05-29 17:26:49'),
(3, 'gestionnaire', 'Gestion des immobilisations et des contrats', '2025-05-29 17:26:49', '2025-05-29 17:26:49'),
(4, 'utilisateur', 'Consultation des immobilisations et des contrats', '2025-05-29 17:26:49', '2025-05-29 17:26:49');

-- --------------------------------------------------------

--
-- Structure de la table `role_permission`
--

CREATE TABLE `role_permission` (
  `role_id` bigint(20) UNSIGNED NOT NULL,
  `permission_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `role_permission`
--

INSERT INTO `role_permission` (`role_id`, `permission_id`) VALUES
(1, 1),
(1, 2),
(1, 3),
(1, 4),
(1, 5),
(1, 6),
(1, 7),
(1, 8),
(1, 9),
(1, 10),
(1, 11),
(1, 12),
(1, 13),
(2, 1),
(2, 2),
(2, 4),
(2, 5),
(2, 6),
(2, 7),
(2, 8),
(2, 9),
(2, 10),
(2, 11),
(2, 12),
(2, 13),
(3, 5),
(3, 6),
(3, 7),
(3, 8),
(3, 9),
(3, 10),
(3, 12),
(3, 13),
(4, 6),
(4, 8),
(4, 10),
(4, 12);

-- --------------------------------------------------------

--
-- Structure de la table `services`
--

CREATE TABLE `services` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `dossier_id` bigint(20) UNSIGNED NOT NULL,
  `site_id` bigint(20) UNSIGNED NOT NULL,
  `code` varchar(255) NOT NULL,
  `libelle` varchar(255) NOT NULL,
  `responsable` varchar(255) DEFAULT NULL,
  `email_responsable` varchar(255) DEFAULT NULL,
  `telephone_responsable` varchar(255) DEFAULT NULL,
  `est_actif` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `services`
--

INSERT INTO `services` (`id`, `dossier_id`, `site_id`, `code`, `libelle`, `responsable`, `email_responsable`, `telephone_responsable`, `est_actif`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 'DIRECTION', 'Direction Générale', 'Jean Dupont', 'j.dupont@entreprise.fr', '01 23 45 67 80', 1, '2025-05-29 17:16:41', '2025-05-29 17:28:32'),
(2, 1, 1, 'COMPTA', 'Service Comptabilité', 'Marie Martin', 'm.martin@entreprise.fr', '01 23 45 67 81', 1, '2025-05-29 17:16:41', '2025-05-29 17:28:32'),
(3, 1, 1, 'INFO', 'Service Informatique', 'Pierre Leroy', 'p.leroy@entreprise.fr', '01 23 45 67 82', 1, '2025-05-29 17:16:41', '2025-05-29 17:28:32'),
(4, 1, 2, 'PROD_NORD', 'Production Nord', 'Sophie Dubois', 's.dubois@entreprise.fr', '03 20 45 67 80', 1, '2025-05-29 17:16:41', '2025-05-29 17:28:32'),
(5, 1, 3, 'LOGISTIQUE', 'Service Logistique', 'Thomas Moreau', 't.moreau@entreprise.fr', '04 91 23 45 60', 1, '2025-05-29 17:16:41', '2025-05-29 17:28:32');

-- --------------------------------------------------------

--
-- Structure de la table `sites`
--

CREATE TABLE `sites` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `dossier_id` bigint(20) UNSIGNED NOT NULL,
  `code` varchar(255) NOT NULL,
  `libelle` varchar(255) NOT NULL,
  `adresse` varchar(255) DEFAULT NULL,
  `code_postal` varchar(255) DEFAULT NULL,
  `ville` varchar(255) DEFAULT NULL,
  `pays` varchar(255) DEFAULT NULL,
  `telephone` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `est_actif` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `sites`
--

INSERT INTO `sites` (`id`, `dossier_id`, `code`, `libelle`, `adresse`, `code_postal`, `ville`, `pays`, `telephone`, `email`, `est_actif`, `created_at`, `updated_at`) VALUES
(1, 1, 'SIEGE', 'Siège social', '123 Avenue des Champs-Élysées', '75008', 'Paris', 'France', '01 23 45 67 89', 'contact@entreprise.fr', 1, '2025-05-29 17:16:41', '2025-05-29 17:28:32'),
(2, 1, 'USINE_NORD', 'Usine Nord', '45 Rue de l\'Industrie', '59000', 'Lille', 'France', '03 20 45 67 89', 'usine.nord@entreprise.fr', 1, '2025-05-29 17:16:41', '2025-05-29 17:28:32'),
(3, 1, 'DEPOT_SUD', 'Dépôt Sud', '78 Avenue du Midi', '13000', 'Marseille', 'France', '04 91 23 45 67', 'depot.sud@entreprise.fr', 1, '2025-05-29 17:16:41', '2025-05-29 17:28:32');

-- --------------------------------------------------------

--
-- Structure de la table `societes`
--

CREATE TABLE `societes` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `code` varchar(255) NOT NULL,
  `nom` varchar(255) NOT NULL,
  `adresse` varchar(255) DEFAULT NULL,
  `code_postal` varchar(255) DEFAULT NULL,
  `ville` varchar(255) DEFAULT NULL,
  `pays` varchar(255) DEFAULT NULL,
  `telephone` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `siret` varchar(255) DEFAULT NULL,
  `est_actif` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `societes`
--

INSERT INTO `societes` (`id`, `code`, `nom`, `adresse`, `code_postal`, `ville`, `pays`, `telephone`, `email`, `siret`, `est_actif`, `created_at`, `updated_at`) VALUES
(1, 'EMEX', 'EMEX CONSULTING', '123 Avenue des Champs-Élysées', '75008', 'Paris', 'France', '01 23 45 67 89', 'contact@entreprise.fr', '12345678901234', 1, '2025-05-30 07:39:30', '2025-05-31 10:15:24'),
(2, 'Expedita fugiat omn', 'In quaerat nostrud i', 'Voluptate tempore d', 'Harum non esse tota', 'Molestiae in ab blan', 'Aliquam consectetur', '+1 (664) 996-1259', 'telak@mailinator.com', 'Consequatur lorem id', 1, '2025-06-01 09:47:26', '2025-06-01 09:47:26');

-- --------------------------------------------------------

--
-- Structure de la table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `role_id` bigint(20) UNSIGNED DEFAULT NULL,
  `client_id` bigint(20) UNSIGNED DEFAULT NULL,
  `current_dossier_id` bigint(20) UNSIGNED DEFAULT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `role_id`, `client_id`, `current_dossier_id`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'Elhabib MACHHOURI', 'hmachhouri@gmail.com', NULL, '$2y$12$.7nQ7qVvHrtJq1hNmzKpjeDwrFmCPFQMrjW8NS9Mv26NHwalEwtC2', NULL, 1, NULL, 'ECiqZ467EI0vL97b1NdRnO1eTFbHMay6FtXNxcr4LUvS50HbjR0x2SYwWWt5', '2025-05-29 17:20:19', '2025-05-29 17:20:19'),
(2, 'Super Admin', 'super.admin@example.com', NULL, '$2y$12$wIu9WTY4MQHsiSaL.3eS..DLsdnhe1uCZu8ZJvJUDkk.3C0qaQ716', 1, 1, 1, NULL, '2025-05-29 17:26:50', '2025-06-01 12:45:49'),
(3, 'Administrateur', 'admin@example.com', NULL, '$2y$12$ji2QF8lHm1H8rVnvlKeFduzn63HmG1K2jW9IkwCy5/zHZ3lAFY2QK', 2, 1, 1, '2aTbH59lFzjKN2nBLphryYopdJXKUwdBta8ujwxYtlgGaVuc7RIiAdfcZ0x1', '2025-05-29 17:28:34', '2025-05-29 17:28:34'),
(4, 'Gestionnaire', 'gestionnaire@example.com', NULL, '$2y$12$nIecRssHpr0L0zY/KlAsfuPtGDWPJ3Jnsq0u0Cmn.Dc2dsiQWRoj2', 3, 1, 1, NULL, '2025-05-29 17:28:34', '2025-05-29 17:28:34'),
(5, 'Utilisateur', 'utilisateur@example.com', NULL, '$2y$12$pNH6oaC7xWw7o19o0WoT0eZK8I43GgBF.QMB4P10X.1b5NlgA25e2', 4, 1, 1, NULL, '2025-05-29 17:28:34', '2025-05-29 17:28:34'),
(6, 'Hermione Booker', 'firefav@mailinator.com', NULL, '$2y$12$K8hhvlbo.0EGFAhjtWHsNe4qRQJNbT.aYulpqoODnchw/eYo/j7Ke', 3, 3, 3, NULL, '2025-06-01 10:19:55', '2025-06-01 10:28:59');

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `clients`
--
ALTER TABLE `clients`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `clients_identifiant_unique_unique` (`identifiant_unique`),
  ADD KEY `idx_clients_code` (`code`);

--
-- Index pour la table `comptes`
--
ALTER TABLE `comptes`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `comptes_dossier_id_numero_unique` (`dossier_id`,`numero`),
  ADD KEY `comptes_numero_index` (`numero`);

--
-- Index pour la table `comptescompta`
--
ALTER TABLE `comptescompta`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `comptescompta_dossier_id_numero_unique` (`dossier_id`,`numero`),
  ADD KEY `comptescompta_numero_index` (`numero`);

--
-- Index pour la table `contrats`
--
ALTER TABLE `contrats`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `contrats_dossier_id_reference_unique` (`dossier_id`,`reference`),
  ADD KEY `contrats_prestataire_id_foreign` (`prestataire_id`),
  ADD KEY `contrats_fournisseur_id_foreign` (`fournisseur_id`),
  ADD KEY `contrats_reference_index` (`reference`),
  ADD KEY `contrats_type_index` (`type`),
  ADD KEY `contrats_statut_index` (`statut`),
  ADD KEY `contrats_date_debut_index` (`date_debut`),
  ADD KEY `contrats_date_fin_index` (`date_fin`);

--
-- Index pour la table `contrat_immobilisation`
--
ALTER TABLE `contrat_immobilisation`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `contrat_immo_unique_index` (`contrat_id`,`immobilisation_id`,`date_deliaison`),
  ADD KEY `contrat_immobilisation_immobilisation_id_foreign` (`immobilisation_id`);

--
-- Index pour la table `details_credit_bail`
--
ALTER TABLE `details_credit_bail`
  ADD PRIMARY KEY (`contrat_id`);

--
-- Index pour la table `dossiers`
--
ALTER TABLE `dossiers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `dossiers_code_unique` (`code`),
  ADD KEY `dossiers_code_index` (`code`),
  ADD KEY `dossiers_societe_id_foreign` (`societe_id`),
  ADD KEY `dossiers_client_id_foreign` (`client_id`);

--
-- Index pour la table `dossier_user`
--
ALTER TABLE `dossier_user`
  ADD PRIMARY KEY (`user_id`,`dossier_id`),
  ADD KEY `dossier_user_dossier_id_foreign` (`dossier_id`);

--
-- Index pour la table `echeances_credit_bail`
--
ALTER TABLE `echeances_credit_bail`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `echeance_unique` (`detail_credit_bail_contrat_id`,`numero_echeance`);

--
-- Index pour la table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Index pour la table `familles`
--
ALTER TABLE `familles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `familles_dossier_id_code_unique` (`dossier_id`,`code`),
  ADD KEY `familles_code_index` (`code`),
  ADD KEY `familles_comptecompta_immobilisation_id_foreign` (`comptecompta_immobilisation_id`),
  ADD KEY `familles_comptecompta_amortissement_id_foreign` (`comptecompta_amortissement_id`),
  ADD KEY `familles_comptecompta_dotation_id_foreign` (`comptecompta_dotation_id`);

--
-- Index pour la table `fournisseurs`
--
ALTER TABLE `fournisseurs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `fournisseurs_dossier_id_code_unique` (`dossier_id`,`code`),
  ADD KEY `fournisseurs_code_index` (`code`);

--
-- Index pour la table `immobilisations`
--
ALTER TABLE `immobilisations`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `immobilisations_dossier_id_code_unique` (`dossier_id`,`code`),
  ADD KEY `immobilisations_famille_id_foreign` (`famille_id`),
  ADD KEY `immobilisations_site_id_foreign` (`site_id`),
  ADD KEY `immobilisations_service_id_foreign` (`service_id`),
  ADD KEY `immobilisations_fournisseur_id_foreign` (`fournisseur_id`),
  ADD KEY `immobilisations_code_index` (`code`),
  ADD KEY `immobilisations_numero_index` (`numero`),
  ADD KEY `immobilisations_statut_index` (`statut`),
  ADD KEY `immobilisations_date_acquisition_index` (`date_acquisition`),
  ADD KEY `immobilisations_date_mise_service_index` (`date_mise_service`),
  ADD KEY `immobilisations_comptecompta_immobilisation_id_foreign` (`comptecompta_immobilisation_id`),
  ADD KEY `immobilisations_comptecompta_amortissement_id_foreign` (`comptecompta_amortissement_id`),
  ADD KEY `immobilisations_comptecompta_dotation_id_foreign` (`comptecompta_dotation_id`),
  ADD KEY `immobilisations_comptecompta_tva_id_foreign` (`comptecompta_tva_id`),
  ADD KEY `idx_immobilisations_dossier_id` (`dossier_id`);

--
-- Index pour la table `inventaires`
--
ALTER TABLE `inventaires`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `inventaires_dossier_id_reference_unique` (`dossier_id`,`reference`),
  ADD KEY `inventaires_responsable_id_foreign` (`responsable_id`),
  ADD KEY `inventaires_site_id_foreign` (`site_id`),
  ADD KEY `inventaires_service_id_foreign` (`service_id`);

--
-- Index pour la table `inventaire_details`
--
ALTER TABLE `inventaire_details`
  ADD PRIMARY KEY (`id`),
  ADD KEY `inventaire_details_inventaire_id_foreign` (`inventaire_id`),
  ADD KEY `inventaire_details_immobilisation_id_foreign` (`immobilisation_id`),
  ADD KEY `inventaire_details_site_scan_id_foreign` (`site_scan_id`),
  ADD KEY `inventaire_details_service_scan_id_foreign` (`service_scan_id`),
  ADD KEY `inventaire_details_user_id_foreign` (`user_id`);

--
-- Index pour la table `maintenances`
--
ALTER TABLE `maintenances`
  ADD PRIMARY KEY (`id`),
  ADD KEY `maintenances_immobilisation_id_foreign` (`immobilisation_id`),
  ADD KEY `maintenances_prestataire_id_foreign` (`prestataire_id`),
  ADD KEY `maintenances_user_id_foreign` (`user_id`);

--
-- Index pour la table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `mouvements_immobilisations`
--
ALTER TABLE `mouvements_immobilisations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `mouvements_immobilisations_immobilisation_id_foreign` (`immobilisation_id`),
  ADD KEY `mouvements_immobilisations_user_id_foreign` (`user_id`);

--
-- Index pour la table `parametres_application`
--
ALTER TABLE `parametres_application`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `parametres_application_dossier_id_cle_unique` (`dossier_id`,`cle`);

--
-- Index pour la table `password_resets`
--
ALTER TABLE `password_resets`
  ADD KEY `password_resets_email_index` (`email`);

--
-- Index pour la table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Index pour la table `permissions`
--
ALTER TABLE `permissions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `permissions_name_unique` (`name`);

--
-- Index pour la table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`);

--
-- Index pour la table `plans_amortissement`
--
ALTER TABLE `plans_amortissement`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `plans_amortissement_immobilisation_id_annee_exercice_unique` (`immobilisation_id`,`annee_exercice`),
  ADD KEY `fk_plans_amortissement_dossier` (`dossier_id`);

--
-- Index pour la table `prestataires`
--
ALTER TABLE `prestataires`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `prestataires_dossier_id_code_unique` (`dossier_id`,`code`),
  ADD KEY `prestataires_code_index` (`code`);

--
-- Index pour la table `provisions_depreciations`
--
ALTER TABLE `provisions_depreciations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `provisions_depreciations_immobilisation_id_foreign` (`immobilisation_id`),
  ADD KEY `provisions_depreciations_user_id_foreign` (`user_id`);

--
-- Index pour la table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `roles_name_unique` (`name`);

--
-- Index pour la table `role_permission`
--
ALTER TABLE `role_permission`
  ADD PRIMARY KEY (`role_id`,`permission_id`),
  ADD KEY `role_permission_permission_id_foreign` (`permission_id`);

--
-- Index pour la table `services`
--
ALTER TABLE `services`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `services_dossier_id_code_unique` (`dossier_id`,`code`),
  ADD KEY `services_site_id_foreign` (`site_id`),
  ADD KEY `services_code_index` (`code`);

--
-- Index pour la table `sites`
--
ALTER TABLE `sites`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `sites_dossier_id_code_unique` (`dossier_id`,`code`),
  ADD KEY `sites_code_index` (`code`);

--
-- Index pour la table `societes`
--
ALTER TABLE `societes`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `societes_code_unique` (`code`);

--
-- Index pour la table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`),
  ADD KEY `users_role_id_foreign` (`role_id`),
  ADD KEY `users_current_dossier_id_foreign` (`current_dossier_id`),
  ADD KEY `fk_users_client` (`client_id`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `clients`
--
ALTER TABLE `clients`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT pour la table `comptes`
--
ALTER TABLE `comptes`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT pour la table `comptescompta`
--
ALTER TABLE `comptescompta`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT pour la table `contrats`
--
ALTER TABLE `contrats`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT pour la table `contrat_immobilisation`
--
ALTER TABLE `contrat_immobilisation`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT pour la table `dossiers`
--
ALTER TABLE `dossiers`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT pour la table `echeances_credit_bail`
--
ALTER TABLE `echeances_credit_bail`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `familles`
--
ALTER TABLE `familles`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT pour la table `fournisseurs`
--
ALTER TABLE `fournisseurs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT pour la table `immobilisations`
--
ALTER TABLE `immobilisations`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT pour la table `inventaires`
--
ALTER TABLE `inventaires`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT pour la table `inventaire_details`
--
ALTER TABLE `inventaire_details`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT pour la table `maintenances`
--
ALTER TABLE `maintenances`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT pour la table `mouvements_immobilisations`
--
ALTER TABLE `mouvements_immobilisations`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `parametres_application`
--
ALTER TABLE `parametres_application`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `permissions`
--
ALTER TABLE `permissions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT pour la table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `plans_amortissement`
--
ALTER TABLE `plans_amortissement`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=244;

--
-- AUTO_INCREMENT pour la table `prestataires`
--
ALTER TABLE `prestataires`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT pour la table `provisions_depreciations`
--
ALTER TABLE `provisions_depreciations`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT pour la table `services`
--
ALTER TABLE `services`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT pour la table `sites`
--
ALTER TABLE `sites`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT pour la table `societes`
--
ALTER TABLE `societes`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT pour la table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `comptes`
--
ALTER TABLE `comptes`
  ADD CONSTRAINT `comptes_dossier_id_foreign` FOREIGN KEY (`dossier_id`) REFERENCES `dossiers` (`id`);

--
-- Contraintes pour la table `comptescompta`
--
ALTER TABLE `comptescompta`
  ADD CONSTRAINT `comptescompta_dossier_id_foreign` FOREIGN KEY (`dossier_id`) REFERENCES `dossiers` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `contrats`
--
ALTER TABLE `contrats`
  ADD CONSTRAINT `contrats_dossier_id_foreign` FOREIGN KEY (`dossier_id`) REFERENCES `dossiers` (`id`),
  ADD CONSTRAINT `contrats_fournisseur_id_foreign` FOREIGN KEY (`fournisseur_id`) REFERENCES `fournisseurs` (`id`),
  ADD CONSTRAINT `contrats_prestataire_id_foreign` FOREIGN KEY (`prestataire_id`) REFERENCES `prestataires` (`id`);

--
-- Contraintes pour la table `contrat_immobilisation`
--
ALTER TABLE `contrat_immobilisation`
  ADD CONSTRAINT `contrat_immobilisation_contrat_id_foreign` FOREIGN KEY (`contrat_id`) REFERENCES `contrats` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `contrat_immobilisation_immobilisation_id_foreign` FOREIGN KEY (`immobilisation_id`) REFERENCES `immobilisations` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `details_credit_bail`
--
ALTER TABLE `details_credit_bail`
  ADD CONSTRAINT `details_credit_bail_contrat_id_foreign` FOREIGN KEY (`contrat_id`) REFERENCES `contrats` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `dossiers`
--
ALTER TABLE `dossiers`
  ADD CONSTRAINT `dossiers_client_id_foreign` FOREIGN KEY (`client_id`) REFERENCES `clients` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `dossiers_societe_id_foreign` FOREIGN KEY (`societe_id`) REFERENCES `societes` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_dossiers_client` FOREIGN KEY (`client_id`) REFERENCES `clients` (`id`) ON DELETE SET NULL;

--
-- Contraintes pour la table `dossier_user`
--
ALTER TABLE `dossier_user`
  ADD CONSTRAINT `dossier_user_dossier_id_foreign` FOREIGN KEY (`dossier_id`) REFERENCES `dossiers` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `dossier_user_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `echeances_credit_bail`
--
ALTER TABLE `echeances_credit_bail`
  ADD CONSTRAINT `echeances_credit_bail_detail_credit_bail_contrat_id_foreign` FOREIGN KEY (`detail_credit_bail_contrat_id`) REFERENCES `details_credit_bail` (`contrat_id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `familles`
--
ALTER TABLE `familles`
  ADD CONSTRAINT `familles_comptecompta_amortissement_id_foreign` FOREIGN KEY (`comptecompta_amortissement_id`) REFERENCES `comptescompta` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `familles_comptecompta_dotation_id_foreign` FOREIGN KEY (`comptecompta_dotation_id`) REFERENCES `comptescompta` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `familles_comptecompta_immobilisation_id_foreign` FOREIGN KEY (`comptecompta_immobilisation_id`) REFERENCES `comptescompta` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `familles_dossier_id_foreign` FOREIGN KEY (`dossier_id`) REFERENCES `dossiers` (`id`);

--
-- Contraintes pour la table `fournisseurs`
--
ALTER TABLE `fournisseurs`
  ADD CONSTRAINT `fournisseurs_dossier_id_foreign` FOREIGN KEY (`dossier_id`) REFERENCES `dossiers` (`id`);

--
-- Contraintes pour la table `immobilisations`
--
ALTER TABLE `immobilisations`
  ADD CONSTRAINT `immobilisations_comptecompta_amortissement_id_foreign` FOREIGN KEY (`comptecompta_amortissement_id`) REFERENCES `comptescompta` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `immobilisations_comptecompta_dotation_id_foreign` FOREIGN KEY (`comptecompta_dotation_id`) REFERENCES `comptescompta` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `immobilisations_comptecompta_immobilisation_id_foreign` FOREIGN KEY (`comptecompta_immobilisation_id`) REFERENCES `comptescompta` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `immobilisations_comptecompta_tva_id_foreign` FOREIGN KEY (`comptecompta_tva_id`) REFERENCES `comptescompta` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `immobilisations_dossier_id_foreign` FOREIGN KEY (`dossier_id`) REFERENCES `dossiers` (`id`),
  ADD CONSTRAINT `immobilisations_famille_id_foreign` FOREIGN KEY (`famille_id`) REFERENCES `familles` (`id`),
  ADD CONSTRAINT `immobilisations_fournisseur_id_foreign` FOREIGN KEY (`fournisseur_id`) REFERENCES `fournisseurs` (`id`),
  ADD CONSTRAINT `immobilisations_service_id_foreign` FOREIGN KEY (`service_id`) REFERENCES `services` (`id`),
  ADD CONSTRAINT `immobilisations_site_id_foreign` FOREIGN KEY (`site_id`) REFERENCES `sites` (`id`);

--
-- Contraintes pour la table `inventaires`
--
ALTER TABLE `inventaires`
  ADD CONSTRAINT `inventaires_dossier_id_foreign` FOREIGN KEY (`dossier_id`) REFERENCES `dossiers` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `inventaires_responsable_id_foreign` FOREIGN KEY (`responsable_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `inventaires_service_id_foreign` FOREIGN KEY (`service_id`) REFERENCES `services` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `inventaires_site_id_foreign` FOREIGN KEY (`site_id`) REFERENCES `sites` (`id`) ON DELETE SET NULL;

--
-- Contraintes pour la table `inventaire_details`
--
ALTER TABLE `inventaire_details`
  ADD CONSTRAINT `inventaire_details_immobilisation_id_foreign` FOREIGN KEY (`immobilisation_id`) REFERENCES `immobilisations` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `inventaire_details_inventaire_id_foreign` FOREIGN KEY (`inventaire_id`) REFERENCES `inventaires` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `inventaire_details_service_scan_id_foreign` FOREIGN KEY (`service_scan_id`) REFERENCES `services` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `inventaire_details_site_scan_id_foreign` FOREIGN KEY (`site_scan_id`) REFERENCES `sites` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `inventaire_details_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Contraintes pour la table `maintenances`
--
ALTER TABLE `maintenances`
  ADD CONSTRAINT `maintenances_immobilisation_id_foreign` FOREIGN KEY (`immobilisation_id`) REFERENCES `immobilisations` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `maintenances_prestataire_id_foreign` FOREIGN KEY (`prestataire_id`) REFERENCES `prestataires` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `maintenances_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Contraintes pour la table `mouvements_immobilisations`
--
ALTER TABLE `mouvements_immobilisations`
  ADD CONSTRAINT `mouvements_immobilisations_immobilisation_id_foreign` FOREIGN KEY (`immobilisation_id`) REFERENCES `immobilisations` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `mouvements_immobilisations_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Contraintes pour la table `parametres_application`
--
ALTER TABLE `parametres_application`
  ADD CONSTRAINT `parametres_application_dossier_id_foreign` FOREIGN KEY (`dossier_id`) REFERENCES `dossiers` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `plans_amortissement`
--
ALTER TABLE `plans_amortissement`
  ADD CONSTRAINT `fk_plans_amortissement_dossier` FOREIGN KEY (`dossier_id`) REFERENCES `immobilisations` (`dossier_id`),
  ADD CONSTRAINT `plans_amortissement_immobilisation_id_foreign` FOREIGN KEY (`immobilisation_id`) REFERENCES `immobilisations` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `prestataires`
--
ALTER TABLE `prestataires`
  ADD CONSTRAINT `prestataires_dossier_id_foreign` FOREIGN KEY (`dossier_id`) REFERENCES `dossiers` (`id`);

--
-- Contraintes pour la table `provisions_depreciations`
--
ALTER TABLE `provisions_depreciations`
  ADD CONSTRAINT `provisions_depreciations_immobilisation_id_foreign` FOREIGN KEY (`immobilisation_id`) REFERENCES `immobilisations` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `provisions_depreciations_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Contraintes pour la table `role_permission`
--
ALTER TABLE `role_permission`
  ADD CONSTRAINT `role_permission_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `role_permission_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `services`
--
ALTER TABLE `services`
  ADD CONSTRAINT `services_dossier_id_foreign` FOREIGN KEY (`dossier_id`) REFERENCES `dossiers` (`id`),
  ADD CONSTRAINT `services_site_id_foreign` FOREIGN KEY (`site_id`) REFERENCES `sites` (`id`);

--
-- Contraintes pour la table `sites`
--
ALTER TABLE `sites`
  ADD CONSTRAINT `sites_dossier_id_foreign` FOREIGN KEY (`dossier_id`) REFERENCES `dossiers` (`id`);

--
-- Contraintes pour la table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `fk_users_client` FOREIGN KEY (`client_id`) REFERENCES `clients` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `users_client_id_foreign` FOREIGN KEY (`client_id`) REFERENCES `clients` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `users_current_dossier_id_foreign` FOREIGN KEY (`current_dossier_id`) REFERENCES `dossiers` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `users_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
