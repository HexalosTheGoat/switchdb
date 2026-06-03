<?php
// ============================================================
// favoris.php — Gestion des favoris
// Ce fichier fait DEUX choses selon comment on l'appelle :
//   1. Si ?action=ajouter ou ?action=supprimer : il modifie la BDD puis redirige
//   2. Sinon : il affiche la liste des favoris de l'utilisateur
// ============================================================

session_start();
require 'includes/db.php';

// ── Protection : réservé aux utilisateurs connectés ──
// Si l'utilisateur n'est pas connecté, on le renvoie sur la page de connexion
if (!isset($_SESSION['id_user'])) {
    $_SESSION['flash'] = [
        'type' => 'erreur',
        'msg'  => 'Vous devez être connecté pour accéder aux favoris.'
    ];
    header('Location: login.php');
    exit;
}

// ============================================================
// TRAITEMENT : Ajouter ou supprimer un favori
// ============================================================
// On regarde si l'URL contient ?action=...
$action = $_GET['action'] ?? '';
$id_switch = intval($_GET['id'] ?? 0);
// Page vers laquelle on redirige après l'action
$retour = $_GET['retour'] ?? 'favoris.php';

if ($action === 'ajouter' && $id_switch > 0) {

    // On vérifie que le switch existe bien en BDD avant d'insérer
    $stmt_check = $pdo->prepare("SELECT COUNT(*) FROM switchs WHERE id_switch = :id");
    $stmt_check->execute([':id' => $id_switch]);

    if ($stmt_check->fetchColumn() > 0) {
        // INSERT IGNORE ignore l'erreur si le favori existe déjà
        // (grâce à la contrainte UNIQUE sur (id_user, id_switch) dans la BDD)
        $stmt = $pdo->prepare(
            "INSERT IGNORE INTO favoris (id_user, id_switch, date_ajout)
             VALUES (:u, :s, CURDATE())"
        );
        $stmt->execute([':u' => $_SESSION['id_user'], ':s' => $id_switch]);

        $_SESSION['flash'] = ['type' => 'succes', 'msg' => '⭐ Ajouté aux favoris !'];
    }

    header('Location: ' . $retour);
    exit;
}

if ($action === 'supprimer' && $id_switch > 0) {

    $stmt = $pdo->prepare(
        "DELETE FROM favoris WHERE id_user = :u AND id_switch = :s"
    );
    $stmt->execute([':u' => $_SESSION['id_user'], ':s' => $id_switch]);

    $_SESSION['flash'] = ['type' => 'succes', 'msg' => 'Retiré des favoris.'];

    header('Location: ' . $retour);
    exit;
}

// ============================================================
// AFFICHAGE : Liste des favoris de l'utilisateur connecté
// ============================================================
// On fait une jointure entre favoris et switchs pour récupérer
// les détails de chaque switch mis en favori.
// JOIN : on relie les deux tables sur id_switch
$stmt = $pdo->prepare(
    "SELECT s.*
     FROM favoris f
     JOIN switchs s ON f.id_switch = s.id_switch
     WHERE f.id_user = :u
     ORDER BY f.date_ajout DESC"
);
$stmt->execute([':u' => $_SESSION['id_user']]);
$favoris = $stmt->fetchAll();

$page_active = 'favoris';
$page_title  = 'Mes favoris';
include 'includes/header.php';
?>

<div class="conteneur-page">
    <h1>⭐ Mes favoris</h1>
    <p class="sous-titre">Vos switchs sauvegardés.</p>

    <?php if (empty($favoris)) : ?>
        <!-- Aucun favori -->
        <div class="etat-vide">
            <p>⭐ Vous n'avez pas encore de favoris.</p>
            <a href="index.php" class="btn-principal">Explorer les switchs</a>
        </div>

    <?php else : ?>
        <!-- On réutilise la même grille de cartes que l'accueil -->
        <div class="grille-cartes">
            <?php foreach ($favoris as $s) :
                $emoji = match($s['type_switch']) {
                    'lineaire' => '🔴', 'tactile' => '🟣', 'clicky' => '🔵', default => '⚪'
                };
            ?>
                <div class="carte-switch">
                    <div class="carte-image"><?= $emoji ?></div>
                    <div class="carte-corps">
                        <p class="carte-marque"><?= htmlspecialchars($s['marque']) ?></p>
                        <h2 class="carte-nom"><?= htmlspecialchars($s['nom']) ?></h2>
                        <div class="carte-badges">
                            <span class="badge badge-<?= $s['type_switch'] ?>"><?= $s['type_switch'] ?></span>
                        </div>
                        <p class="carte-prix"><?= number_format($s['prix_unitaire'], 2, ',', '') ?>€</p>
                        <div class="carte-actions">
                            <a href="switch.php?id=<?= $s['id_switch'] ?>" class="btn-detail">Voir détails</a>
                            <a href="favoris.php?action=supprimer&id=<?= $s['id_switch'] ?>"
                               class="btn-favori actif"
                               onclick="return confirm('Retirer des favoris ?')">★</a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<?php include 'includes/footer.php'; ?>
