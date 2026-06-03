<?php
// ============================================================
// logout.php — Déconnexion
// Ce fichier n'a pas de vue HTML, il fait juste le traitement.
// ============================================================

session_start();

// session_unset() vide toutes les variables de session
session_unset();

// session_destroy() supprime complètement la session sur le serveur
session_destroy();

// Message affiché sur la page suivante
// On doit redémarrer la session pour pouvoir écrire le flash
session_start();
$_SESSION['flash'] = ['type' => 'succes', 'msg' => 'Vous avez été déconnecté.'];

// Redirection vers la page de connexion
header('Location: login.php');
exit;
?>
