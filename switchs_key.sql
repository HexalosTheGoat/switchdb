-- ==========================================
-- [BASE DE DONNÉES] >>> STRUCTURE
-- INITIALISATION DES TABLES
-- ==========================================
CREATE TABLE switchs_clavier (
    
    id INT PRIMARY KEY IDENTITY(1,1),
    
    -- Usinage
    marque VARCHAR(50) NOT NULL,
    modele VARCHAR(50) NOT NULL,
    
    -- Couleurs
    couleur_1 VARCHAR(30) NULL,
    couleur_2 VARCHAR(30) NULL,
    couleur_3 VARCHAR(30) NULL,
    
    -- Utilisation client
    type_switch VARCHAR(20) NOT NULL,
    niveau_bruit VARCHAR(30),
    trouvabilite VARCHAR(30),
    
    -- Valeurs physique
    force_activation INT,
    force_talonnage INT,
    course_activation DECIMAL(3,1),
    course_totale DECIMAL(3,1),
    
    -- Logistique
    prix_unitaire DECIMAL(4,2),
    lubrifie_usine BIT
);

-- ==========================================
-- [INSERTION] >>> SWITCHS
-- ==========================================
INSERT INTO switchs_clavier 
(marque, modele, couleur_1, couleur_2, couleur_3, type_switch, niveau_bruit, trouvabilite, force_activation, force_talonnage, course_activation, course_totale, prix_unitaire, lubrifie_usine)
VALUES

('HMX', 'Firecracker', 'Blanc', 'Bleu', 'Rouge', 'Tactile', 'Modéré', 'Rare', 40, 58, 2.5, 3.5, 0.43, 1),
('HMX', 'Aster', 'Blanc', 'Violet', 'Jaune', 'Linéaire', 'Clacky', 'Courant', 42, 48, 2.0, 3.6, 0.32, 1),
('Wingtree', 'G-One', 'Bleu', 'Gris', NULL, 'Linéaire', 'Clacky', 'Courant', 42, 47, 2.0, 3.6, 0.32, 1),
('Lichicx', 'Yogurt', 'Bleu', 'Blanc', NULL, 'Linéaire', 'Silencieux', 'Courant', 45, 53, 2.0, 3.8, 0.80, 1),
('HMX', 'Lanikai', 'Beige', 'Blanc', NULL, 'Linéaire', 'Clacky', 'Courant', 43, 53, 2.0, 3.7, 0.70, 1),
('Keygeek', 'Y2', 'Rouge', 'Jaune', NULL, 'Linéaire', 'Thocky', 'Rare', 48, 53, 2.0, 3.6, 0.70, 1),
('Keygeek', 'Y3X', 'Blanc', 'Noir', NULL, 'Linéaire', 'Thocky', 'Courant', 48, 53, 2.0, 3.8, 0.50, 1),
('HMX', 'Crisp', 'Bleu', 'Rose', 'Transparent', 'Linéaire', 'Clacky', 'Courant', 48, 55, 2.0, 3.5, 0.70, 1);

-- ==========================================
-- [REQUÊTES] >>> ANALYSE ET LECTURE
-- ==========================================

-- Affichage complet
SELECT * FROM switchs_clavier;

-- Prix en dessous de 0.50€
SELECT marque, modele, prix_unitaire, trouvabilite
FROM switchs_clavier 
WHERE trouvabilite = 'Courant' AND prix_unitaire <= 0.50;
