CREATE DATABASE projet_switchs;
GO

USE projet_switchs;
GO




-- ==========================================
-- [CRÉATION]
-- ==========================================

CREATE TABLE utilisateurs (
    id_user INT PRIMARY KEY IDENTITY(1,1),
    login VARCHAR(50) NOT NULL UNIQUE,
    mot_de_passe VARCHAR(50) NOT NULL,
    role VARCHAR(20) NOT NULL
);

CREATE TABLE marques (
    id_marque INT PRIMARY KEY IDENTITY(1,1),
    nom_marque VARCHAR(50) NOT NULL,
    pays_origine VARCHAR(50)
);

CREATE TABLE switchs_clavier (
    id INT PRIMARY KEY IDENTITY(1,1),
    id_marque INT FOREIGN KEY REFERENCES marques(id_marque),
    modele VARCHAR(50) NOT NULL,
    couleur_1 VARCHAR(30) NULL,
    couleur_2 VARCHAR(30) NULL,
    couleur_3 VARCHAR(30) NULL,
    type_switch VARCHAR(20) NOT NULL,
    niveau_bruit VARCHAR(30),
    trouvabilite VARCHAR(30),
    force_activation INT,
    force_talonnage INT,
    course_activation DECIMAL(3,1),
    course_totale DECIMAL(3,1),
    prix_unitaire DECIMAL(4,2),
    lubrifie_usine BIT
);

-- ==========================================
-- [INSERTION]
-- ==========================================

INSERT INTO utilisateurs (login, mot_de_passe, role) VALUES 
('root', 'admin123', 'admin'),
('raynor', 'rebel123', 'membre'),
('kerrigan', 'swarm456', 'membre'),
('artanis', 'entaro789', 'membre'),
('zeratul', 'void000', 'membre'),
('tychus', 'time2shine', 'membre'),
('nova', 'ghost2026', 'membre'),
('alarak', 't4ld4rim', 'membre'),
('zagara', 'br00dmother', 'membre'),
('swann', '4rmoryfix', 'membre');

INSERT INTO marques (nom_marque, pays_origine) VALUES 
('HMX', 'Chine'),
('Wingtree', 'Chine'),
('Lichicx', 'Chine'),
('Gateron', 'Chine'),
('Keygeek', 'Chine'),
('Cherry', 'Allemagne'),
('Gazzew', 'États-Unis'),
('Drop', 'États-Unis'),
('Kailh', 'Chine'),
('Outemu', 'Chine'),
('Unionwell', 'Chine');

INSERT INTO switchs_clavier 
(id_marque, modele, couleur_1, couleur_2, couleur_3, type_switch, niveau_bruit, trouvabilite, force_activation, force_talonnage, course_activation, course_totale, prix_unitaire, lubrifie_usine)
VALUES
(1, 'Firecracker', 'Blanc', 'Bleu', 'Rouge', 'Tactile', 'Modéré', 'Rare', 40, 58, 2.5, 3.5, 0.43, 1),
(1, 'Aster', 'Blanc', 'Violet', 'Jaune', 'Linéaire', 'Clacky', 'Courant', 42, 48, 2.0, 3.6, 0.32, 1),
(1, 'Lanikai', 'Beige', 'Blanc', NULL, 'Linéaire', 'Clacky', 'Courant', 43, 53, 2.0, 3.7, 0.70, 1),
(1, 'Crisp', 'Bleu', 'Rose', 'Transparent', 'Linéaire', 'Clacky', 'Courant', 48, 55, 2.0, 3.5, 0.70, 1),
(2, 'G-One', 'Bleu', 'Gris', NULL, 'Linéaire', 'Clacky', 'Courant', 42, 47, 2.0, 3.6, 0.32, 1),
(3, 'Yogurt', 'Bleu', 'Blanc', NULL, 'Linéaire', 'Silencieux', 'Courant', 45, 53, 2.0, 3.8, 0.80, 1),
(5, 'Y2', 'Rouge', 'Jaune', NULL, 'Linéaire', 'Thocky', 'Rare', 48, 53, 2.0, 3.6, 0.70, 1),
(5, 'Y3X', 'Blanc', 'Noir', NULL, 'Linéaire', 'Thocky', 'Courant', 48, 53, 2.0, 3.8, 0.50, 1),
(6, 'MX Red', 'Rouge', 'Noir', NULL, 'Linéaire', 'Modéré', 'Courant', 45, 60, 2.0, 4.0, 0.40, 0),
(11, 'Primordial Unity', 'Transparent', 'Blanc', NULL, 'Magnétique', 'Neutre', 'Courant', 37, 50, NULL, 3.5, 1.62, 1);

-- ==========================================
-- [REQUÊTES]
-- ==========================================

SELECT 
    m.nom_marque, 
    s.modele, 
    s.type_switch, 
    s.prix_unitaire
FROM switchs_clavier s
JOIN marques m ON s.id_marque = m.id_marque;

SELECT login, role FROM utilisateurs;
