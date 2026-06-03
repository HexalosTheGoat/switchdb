<?php
session_start();
require 'includes/db.php';

// Vérification de sécurité pour être sûr que seul l'admin insère des données
if (!isset($_SESSION['id_user']) || $_SESSION['role'] !== 'admin') {
    die("Accès refusé.");
}

// Vérification que le formulaire a bien été soumis
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    // La requête SQL avec les 11 marqueurs
    $sql = "INSERT INTO switchs (nom, marque, type_switch, force_actuation, course_totale, course_preact, niveau_sonore, prix_unitaire, hot_swap, description, image_url) 
            VALUES (:nom, :marque, :type, :force, :ct, :cp, :son, :prix, :hot, :desc, :img)";
            
    $stmt = $bdd->prepare($sql);
    
    // Exécution en liant chaque marqueur à sa donnée $_POST correspondante
    $succes = $stmt->execute([
        'nom'   => $_POST['nom'],
        'marque'=> $_POST['marque'],
        'type'  => $_POST['type_switch'],
        'force' => $_POST['force_actuation'],
        'ct'    => $_POST['course_totale'],
        'cp'    => $_POST['course_preact'],
        'son'   => $_POST['niveau_sonore'],
        'prix'  => $_POST['prix_unitaire'],
        'hot'   => $_POST['hot_swap'],
        'desc'  => $_POST['description'],
        'img'   => $_POST['image_url']
    ]);

    if ($succes) {
        header('Location: admin.php?message=ajout_reussi');
        exit;
    } else {
        echo "Erreur lors de l'insertion.";
    }
}
?>