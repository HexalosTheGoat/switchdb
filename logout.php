<?php
// On démarre ou récupère la session en cours
session_start();

// On vide toutes les variables de session (id_user, login, role, etc.)
session_unset();

// On détruit la session
session_destroy();

// On redirige l'utilisateur vers la page de connexion (ou l'accueil)
header('Location: login.php');
exit;
?>