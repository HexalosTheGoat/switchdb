<?php
// ============================================================
// includes/db.php
// Connexion à la base de données MySQL avec PDO
// Ce fichier est inclus dans toutes les pages qui ont besoin
// de la base de données.
// ============================================================

// --- Paramètres de connexion ---
// À modifier selon votre configuration WAMP/XAMPP
$hote     = 'localhost'; // adresse du serveur MySQL
$base     = 'cybros';    // nom de la base de données
$login    = 'root';      // nom d'utilisateur MySQL
$mdp      = '';          // mot de passe MySQL (vide par défaut sur WAMP)

// --- Connexion avec PDO ---
// PDO est une façon moderne et sécurisée de se connecter à MySQL en PHP.
// On utilise un bloc try/catch pour attraper les erreurs de connexion.
try {
    // On crée l'objet PDO avec les paramètres de connexion
    $pdo = new PDO(
        "mysql:host=$hote;dbname=$base;charset=utf8",
        $login,
        $mdp
    );

    // On demande à PDO de lancer une exception si une requête SQL échoue
    // (sinon les erreurs passeraient silencieusement)
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // On demande à PDO de retourner les résultats sous forme de tableaux associatifs
    // Ex : $ligne['nom'] au lieu de $ligne[0]
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    // Si la connexion échoue, on affiche un message d'erreur et on arrête tout
    die("Erreur de connexion à la base de données : " . $e->getMessage());
}
?>
