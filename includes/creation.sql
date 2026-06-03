-- ============================================================
-- sql/creation.sql — Création de la base de données CYBROS
-- À exécuter UNE SEULE FOIS dans phpMyAdmin ou en ligne de commande.
-- ============================================================

-- Création de la base (si elle n'existe pas déjà)
CREATE DATABASE IF NOT EXISTS cybros
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_unicode_ci;

-- On sélectionne la base
USE cybros;

-- ============================================================
-- Table : switchs
-- Contient tous les switchs référencés sur le site.
-- ============================================================
CREATE TABLE IF NOT EXISTS switchs (
    id_switch       INT AUTO_INCREMENT PRIMARY KEY,
    nom             VARCHAR(100) NOT NULL,
    marque          VARCHAR(50)  NOT NULL,
    -- ENUM : la valeur doit être l'une des options listées
    type_switch     ENUM('lineaire', 'tactile', 'clicky') NOT NULL,
    force_actuation INT          NOT NULL,   -- en grammes (centiNewtons)
    course_totale   DECIMAL(3,1) NOT NULL,   -- en millimètres
    course_preact   DECIMAL(3,1) NOT NULL,   -- pré-actuation en mm
    niveau_sonore   ENUM('silencieux', 'modere', 'fort') NOT NULL,
    prix_unitaire   DECIMAL(5,2) NOT NULL,   -- prix en euros par switch
    hot_swap        BOOLEAN      NOT NULL DEFAULT 0,  -- 1=oui, 0=non
    description     TEXT,
    lien_achat      VARCHAR(255),
    -- Contraintes CHECK : la valeur doit respecter ces conditions
    CHECK (force_actuation BETWEEN 20 AND 150),
    CHECK (prix_unitaire > 0),
    CHECK (course_totale BETWEEN 1.0 AND 6.0)
);

-- ============================================================
-- Table : utilisateurs
-- Contient les comptes utilisateurs du site.
-- ============================================================
CREATE TABLE IF NOT EXISTS utilisateurs (
    id_user         INT AUTO_INCREMENT PRIMARY KEY,
    login           VARCHAR(50)  NOT NULL UNIQUE,   -- UNIQUE : pas deux fois le même login
    mot_de_passe    VARCHAR(255) NOT NULL,           -- stocké hashé avec password_hash()
    email           VARCHAR(100) NOT NULL UNIQUE,
    role            ENUM('admin', 'user') NOT NULL DEFAULT 'user',
    date_inscription DATE NOT NULL DEFAULT (CURRENT_DATE)
);

-- ============================================================
-- Table : favoris
-- Fait le lien entre un utilisateur et ses switchs favoris.
-- C'est une table de liaison (relation N:N entre users et switchs).
-- ============================================================
CREATE TABLE IF NOT EXISTS favoris (
    id_favori  INT AUTO_INCREMENT PRIMARY KEY,
    id_user    INT NOT NULL,
    id_switch  INT NOT NULL,
    date_ajout DATE NOT NULL DEFAULT (CURRENT_DATE),
    -- FOREIGN KEY : garantit que l'id existe dans la table liée
    -- ON DELETE CASCADE : si l'utilisateur est supprimé, ses favoris le sont aussi
    FOREIGN KEY (id_user)   REFERENCES utilisateurs(id_user) ON DELETE CASCADE,
    FOREIGN KEY (id_switch) REFERENCES switchs(id_switch)    ON DELETE CASCADE,
    -- UNIQUE sur les deux colonnes : un user ne peut pas avoir deux fois le même favori
    UNIQUE KEY unique_favori (id_user, id_switch)
);

-- ============================================================
-- Table : kits_entretien
-- Contient les kits d'outils recommandés dans le guide.
-- ============================================================
CREATE TABLE IF NOT EXISTS kits_entretien (
    id_kit     INT AUTO_INCREMENT PRIMARY KEY,
    nom_kit    VARCHAR(100) NOT NULL,
    contenu    TEXT,
    prix       DECIMAL(5,2) NOT NULL,
    lien_achat VARCHAR(255),
    CHECK (prix > 0)
);
