<?php
session_start();
require 'includes/db.php';

// 1. Vérification de la sécurité : l'utilisateur doit être connecté
if (!isset($_SESSION['id_user'])) {
    header('Location: login.php');
    exit;
}

// 2. Vérification de la présence de l'ID du switch dans l'URL
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header('Location: index.php');
    exit;
}

// On sécurise les variables
$id_switch = intval($_GET['id']);
$id_user = $_SESSION['id_user'];

// 3. Insertion dans la base de données
try {
    $stmt = $bdd->prepare("INSERT INTO favoris (id_user, id_switch, date_ajout) VALUES (:user, :switch, NOW())");
    $stmt->execute(['user' => $id_user, 'switch' => $id_switch]);
    
    // Redirection intelligente : on le renvoie d'où il vient !
    $page_precedente = $_SERVER['HTTP_REFERER'] ?? 'index.php';
    header("Location: " . $page_precedente);
    exit;

} catch (PDOException $e) {
    if ($e->getCode() == 23000) { 
        $page_precedente = $_SERVER['HTTP_REFERER'] ?? 'index.php';
        header("Location: " . $page_precedente);
        exit;
    }
    die("Erreur : " . $e->getMessage());
}
?>