<?php
session_start();
require 'includes/db.php';

if (!isset($_SESSION['id_user']) || empty($_GET['id'])) {
    header('Location: index.php');
    exit;
}

$id_switch = intval($_GET['id']);
$id_user = $_SESSION['id_user'];

// La fameuse requête de suppression demandée par le cahier des charges
$stmt = $bdd->prepare("DELETE FROM favoris WHERE id_user = :user AND id_switch = :switch");
$stmt->execute([
    'user' => $id_user,
    'switch' => $id_switch
]);

// On le renvoie exactement sur la page où il était (ex: favoris.php)
$page_precedente = $_SERVER['HTTP_REFERER'] ?? 'index.php';
header("Location: " . $page_precedente);
exit;
?>