<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CYBROS - Base de données de Switchs</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

<header>
    <div class="logo">CYBROS</div>
    <nav>
        <ul>
            <li><a href="index.php">Accueil / Recherche</a></li>
            <li><a href="comparer.php">Comparer</a></li>
            <li><a href="guide.php">Guide Éducatif</a></li>
            
            <?php if (isset($_SESSION['id_user'])): ?>
                <li><a href="favoris.php">Mes Favoris</a></li>
                
                <?php if ($_SESSION['role'] === 'admin'): ?>
                    <li><a href="admin.php">Administration</a></li>
                <?php endif; ?>
                
                <li><a href="logout.php" class="btn-accent">Déconnexion</a></li>
            <?php else: ?>
                <li><a href="login.php" class="btn-accent">Connexion</a></li>
            <?php endif; ?>
        </ul>
    </nav>
</header>

<main>