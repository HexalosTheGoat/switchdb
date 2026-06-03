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
    // La requête SQL pour insérer dans la table favoris
    $stmt = $bdd->prepare("INSERT INTO favoris (id_user, id_switch, date_ajout) VALUES (:user, :switch, NOW())");
    $stmt->execute([
        'user' => $id_user,
        'switch' => $id_switch
    ]);
    
    // Succès : on redirige l'utilisateur vers la page du switch
    header("Location: switch.php?id=$id_switch&message=succes");
    exit;

} catch (PDOException $e) {
    // Gestion de l'erreur si le switch est DÉJÀ en favori 
    // (Cela arrive si la contrainte UNIQUE(id_user, id_switch) bloque l'insertion)
    if ($e->getCode() == 23000) { 
        header("Location: switch.php?id=$id_switch&message=existe_deja");
        exit;
    } else {
        // Autre erreur SQL
        die("Erreur lors de l'ajout aux favoris : " . $e->getMessage());
    }
}
?>