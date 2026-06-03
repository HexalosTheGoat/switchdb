-- ============================================================
-- sql/remplissage.sql — Données de test pour CYBROS
-- À exécuter APRÈS creation.sql.
-- ============================================================

USE cybros;

-- ============================================================
-- Switchs (12 enregistrements minimum requis par le SAE)
-- ============================================================
INSERT INTO switchs (nom, marque, type_switch, force_actuation, course_totale, course_preact, niveau_sonore, prix_unitaire, hot_swap, description, lien_achat) VALUES
('MX Red',          'Cherry',    'lineaire', 45, 4.0, 2.0, 'modere',     0.45, 0, 'Le switch linéaire de référence de Cherry. Course fluide et légère, idéal pour le gaming. Durée de vie : 100 millions de frappes.', 'https://www.ldlc.com'),
('MX Speed Silver', 'Cherry',    'lineaire', 45, 3.4, 1.2, 'modere',     0.55, 0, 'Variante ultra-rapide du MX Red. Pré-actuation de 1,2mm pour les réactions les plus rapides en compétition.', 'https://www.ldlc.com'),
('MX Blue',         'Cherry',    'clicky',   60, 4.0, 2.2, 'fort',       0.50, 0, 'L''emblématique switch clicky de Cherry. Retour tactile prononcé et cliquetis sonore distinctif.', 'https://www.ldlc.com'),
('Yellow',          'Gateron',   'lineaire', 35, 4.0, 2.0, 'silencieux', 0.18, 1, 'Switch ultra-léger et très abordable. Excellent rapport qualité/prix. Légèreté de 35g idéale pour le gaming rapide.', 'https://fr.aliexpress.com'),
('G Pro Yellow',    'Gateron',   'lineaire', 35, 4.0, 2.0, 'silencieux', 0.28, 1, 'Version améliorée du Gateron Yellow avec boîtier prélubriqué en usine. Plus silencieux et plus fluide.', 'https://fr.aliexpress.com'),
('Box White',       'Kailh',     'clicky',   50, 3.6, 1.8, 'fort',       0.32, 1, 'Switch clicky à boîtier fermé. Protégé contre la poussière et les éclaboussures. Clic sec et précis.', 'https://fr.aliexpress.com'),
('Holy Panda',      'Durock',    'tactile',  67, 4.0, 2.0, 'modere',     1.20, 1, 'Le saint-graal des switches tactiles. Retour tactile très prononcé et satisfaisant, plébiscité par les passionnés.', 'https://drop.com'),
('Boba U4',         'Gazzew',    'tactile',  62, 4.0, 2.0, 'silencieux', 0.75, 1, 'Switch tactile silencieux de référence. Retour tactile important sans aucun bruit. Idéal pour les bureaux partagés.', 'https://divinikey.com'),
('CS Jelly Purple', 'Akko',      'tactile',  38, 4.0, 2.0, 'modere',     0.22, 1, 'Switch tactile abordable avec un beau retour tactile léger. Excellent point d''entrée dans le monde tactile.', 'https://en.akkogear.com'),
('NK_ Cream',       'NovelKeys', 'lineaire', 55, 4.0, 2.0, 'modere',     0.90, 0, 'Switch en POM auto-lubrifiant. Développe une meilleure sensation au fil du temps grâce au break-in.', 'https://novelkeys.com'),
('L7 67g',          'Durock',    'lineaire', 67, 4.0, 2.0, 'modere',     0.65, 1, 'Switch linéaire médium polyvalent. Force de 67g ferme et satisfaisante. Très populaire chez les enthousiastes.', 'https://dangkeebs.com'),
('Topre 45g',       'Topre',     'tactile',  45, 4.0, 2.0, 'modere',     1.50, 0, 'Hybride électro-capacitif unique. Sensation incomparable, très apprécié des professionnels. Présent dans les HHKB.', 'https://elitekeyboards.com');

-- ============================================================
-- Utilisateurs (3 comptes)
-- IMPORTANT : les mots de passe sont hashés avec password_hash()
-- Pour regénérer les hashs, utilisez :
--   echo password_hash('admin123', PASSWORD_DEFAULT);
-- Les hashs ci-dessous correspondent à :
--   admin       -> admin123
--   utilisateur -> user123
--   demo        -> demo1234
-- ============================================================
INSERT INTO utilisateurs (login, mot_de_passe, email, role, date_inscription) VALUES
('admin',
 '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
 'admin@cybros.fr', 'admin', '2024-01-10'),

('utilisateur',
 '$2y$10$TKh8H1.PfQx37YgCzwiKb.KjNyWgaHb9cbcoQgdIV.X7VfFKi3Lg2',
 'user@cybros.fr', 'user', '2024-02-14'),

('demo',
 '$2y$10$e.Rp8OcZ4pNLiO1xNn8BVuVUq5J5Ky7nHnqMfwwEDHO5B.mzjjl6',
 'demo@cybros.fr', 'user', '2024-03-01');

-- ============================================================
-- Favoris (10 enregistrements)
-- ============================================================
INSERT INTO favoris (id_user, id_switch, date_ajout) VALUES
(1, 1,  '2024-03-10'),
(1, 7,  '2024-03-11'),
(1, 4,  '2024-03-12'),
(2, 4,  '2024-04-01'),
(2, 8,  '2024-04-02'),
(2, 9,  '2024-04-03'),
(2, 5,  '2024-04-04'),
(3, 3,  '2024-05-01'),
(3, 6,  '2024-05-02'),
(3, 12, '2024-05-03');

-- ============================================================
-- Kits d'entretien (10 enregistrements)
-- ============================================================
INSERT INTO kits_entretien (nom_kit, contenu, prix, lien_achat) VALUES
('Kit Débutant Akko',        'Tire-keycap plastique, tire-switch, brosse nettoyante.',             12.99, 'https://en.akkogear.com'),
('Krytox 205g0 (3ml)',       'Lubrifiant de référence pour les switches linéaires.',               8.90,  'https://shop.dangkeebs.com'),
('Tribosys 3203 (3ml)',      'Lubrifiant léger pour switches tactiles. Préserve le retour.',       9.50,  'https://ringerkeys.com'),
('Kit Pro Switch Opener',    'Démonte-switch + plateau 110 slots + pinceau de précision.',         19.99, 'https://fr.aliexpress.com'),
('Tire-keycap Wire',         'Tire-keycap en fil métallique, compatible tous keycaps.',             4.50,  'https://fr.aliexpress.com'),
('Isopropanol 99% (250ml)',  'Nettoyant pour enlever l''ancien lubrifiant sans résidu.',           7.99,  'https://www.amazon.fr'),
('Plateau de lubrification', 'Plateau 110 emplacements pour lubrifier en série.',                  11.50, 'https://shop.typeractive.xyz'),
('Foam clavier 60%',         'Mousse sous le PCB pour réduire le bruit et améliorer le son.',      8.50,  'https://fr.aliexpress.com'),
('Bande PE stabilisateurs',  'Réduit le bruit parasite des grandes touches (espace, entrée...).',  3.99,  'https://shop.dangkeebs.com'),
('Kit Nettoyage Complet',    'Brosse antistatique, lingettes microfibre, soufflette air comprimé.',22.90, 'https://www.amazon.fr');
