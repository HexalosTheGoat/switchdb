<?php
// ============================================================
// includes/header.php
// En-tête HTML commun à toutes les pages du site.
// On l'inclut en haut de chaque page avec : include 'includes/header.php';
// ============================================================

// --- Démarrage de la session ---
// La session PHP permet de garder des informations d'une page à l'autre.
// Ex : savoir si l'utilisateur est connecté.
// On vérifie d'abord qu'elle n'est pas déjà démarrée pour éviter les erreurs.
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// $page_active est définie dans chaque page AVANT d'inclure ce header.
// Elle sert à mettre en surbrillance le bon lien dans le menu.
// Si elle n'est pas définie, on met une chaîne vide par défaut.
$page_active = $page_active ?? '';

// $page_title est le titre affiché dans l'onglet du navigateur.
$page_title = $page_title ?? 'CYBROS';
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Le titre de la page change selon la variable $page_title -->
    <title><?= htmlspecialchars($page_title) ?> — CYBROS</title>
    <!-- Feuille de styles principale -->
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

<!-- ======================================================
     BARRE DE NAVIGATION
     Elle est identique sur toutes les pages du site.
     ====================================================== -->
<nav class="navbar">

    <!-- Logo / nom du site — redirige vers l'accueil -->
    <a href="index.php" class="nav-logo">CYBROS</a>

    <!-- Liens de navigation -->
    <!-- La classe "actif" est ajoutée sur le lien de la page courante
         grâce à la variable $page_active -->
    <div class="nav-liens">
        <a href="index.php"    class="<?= $page_active === 'accueil'  ? 'actif' : '' ?>">Accueil</a>
        <a href="guide.php"    class="<?= $page_active === 'guide'    ? 'actif' : '' ?>">Guide</a>
        <a href="comparer.php" class="<?= $page_active === 'comparer' ? 'actif' : '' ?>">Comparer</a>

        <?php
        // On affiche le lien "Favoris" SEULEMENT si l'utilisateur est connecté.
        // $_SESSION['id_user'] est défini dans login.php quand la connexion réussit.
        if (isset($_SESSION['id_user'])) : ?>
            <a href="favoris.php" class="<?= $page_active === 'favoris' ? 'actif' : '' ?>">⭐ Favoris</a>
        <?php endif; ?>

        <?php
        // On affiche "Admin" SEULEMENT si l'utilisateur a le rôle 'admin'
        if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin') : ?>
            <a href="admin.php" class="<?= $page_active === 'admin' ? 'actif' : '' ?>" style="color:#d4a838">⚙ Admin</a>
        <?php endif; ?>
    </div>

    <!-- Partie droite : connexion ou nom de l'utilisateur -->
    <div class="nav-droite">
        <?php if (isset($_SESSION['id_user'])) : ?>
            <!-- L'utilisateur est connecté : on affiche son login et un bouton déconnexion -->
            <!-- htmlspecialchars() protège contre les attaques XSS (injection de code HTML) -->
            <span>👤 <?= htmlspecialchars($_SESSION['login']) ?></span>
            <a href="logout.php" class="btn-secondaire">Déconnexion</a>
        <?php else : ?>
            <!-- L'utilisateur n'est pas connecté : on affiche le bouton connexion -->
            <a href="login.php" class="btn-principal">Connexion</a>
        <?php endif; ?>
    </div>
</nav>

<?php
// --- Affichage des messages flash ---
// Un message flash est un message temporaire stocké en session
// (ex : "Ajouté aux favoris !" ou "Erreur de connexion").
// On l'affiche une seule fois puis on le supprime.
if (isset($_SESSION['flash'])) : ?>
    <div class="flash flash-<?= $_SESSION['flash']['type'] ?>">
        <?= htmlspecialchars($_SESSION['flash']['msg']) ?>
    </div>
<?php
    unset($_SESSION['flash']); // On supprime le message après l'avoir affiché
endif;
?>
