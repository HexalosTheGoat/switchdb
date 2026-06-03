<?php
// Fichier : includes/db.php
$host = 'localhost';
$dbname = 'cybros';
$username = 'root'; // Utilisateur par défaut sous XAMPP
$password = '';     // Pas de mot de passe sous XAMPP

try {
    $bdd = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    // Activation des erreurs PDO pour faciliter le débogage
    $bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die("Erreur de connexion à la base de données : " . $e->getMessage());
}
?>