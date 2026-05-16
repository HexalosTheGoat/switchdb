-- ==========================================
-- [BASE DE DONNÉES] >>> CRÉATION
-- INITIALISATION DE LA TABLE DES SWITCHS
-- ==========================================
CREATE TABLE switchs_clavier (
    
    -- Identifiant matriciel unique (Génération automatique MS SQL)
    id INT PRIMARY KEY IDENTITY(1,1),
    
    -- Constructeur et désignation du matériel
    marque VARCHAR(50) NOT NULL,
    modele VARCHAR(50) NOT NULL,
    couleur VARCHAR(30),
    
    -- Profil d'utilisation et caractéristiques sonores
    type_switch VARCHAR(20) NOT NULL,
    niveau_bruit VARCHAR(30),
    trouvabilite VARCHAR(30),
    
    -- Calibrage physique : Pression (g) et course (mm)
    force_activation INT,
    force_talonnage INT,
    course_activation DECIMAL(3,1),
    course_totale DECIMAL(3,1),
    
    -- Logistique : Coût unitaire et options d'usine (Format BIT : 1=Oui, 0=Non)
    prix_unitaire DECIMAL(4,2),
    lubrifie_usine BIT
);

-- ==========================================
-- [INSERTION] >>> AJOUT DES DONNÉES
-- ENREGISTREMENT DES MODÈLES DANS LA BASE
-- ==========================================
INSERT INTO switchs_clavier 
(marque, modele, couleur, type_switch, niveau_bruit, trouvabilite, force_activation, force_talonnage, course_activation, course_totale, prix_unitaire, lubrifie_usine)
VALUES

-- Modèle linéaire standard : Fiable et courant
('Cherry', 'MX Red', 'Rouge', 'Linéaire', 'Modéré', 'Courant', 45, 60, 2.0, 4.0, 0.40, 0),

-- Modèle tactile silencieux : Optimisé pour un environnement calme
('Gazzew', 'Boba U4', 'Blanc', 'Tactile', 'Silencieux', 'Moyen', 62, 68, 2.0, 4.0, 0.65, 0),

-- Modèle linéaire fluide : Efficace et économique
('Gateron', 'Milky Yellow', 'Jaune', 'Linéaire', 'Modéré', 'Courant', 50, 65, 2.0, 4.0, 0.30, 1),

-- Modèle tactile premium : Rare et signature sonore marquée
('Drop', 'Holy Panda', 'Saumon', 'Tactile', 'Bruyant', 'Rare', 67, 67, 2.0, 4.0, 1.20, 1);

-- ==========================================
-- [REQUÊTES] >>> ANALYSE ET LECTURE
-- INTERROGATION DES REGISTRES
-- ==========================================

-- Scanner et afficher l'intégralité des données enregistrées
SELECT * FROM switchs_clavier;

-- Filtrer les données : Afficher uniquement les modèles courants à moins de 0.50€
SELECT marque, modele, prix_unitaire, trouvabilite
FROM switchs_clavier 
WHERE trouvabilite = 'Courant' AND prix_unitaire <= 0.50;