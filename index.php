<?php
// Fichier : index.php
require 'includes/header.php';
require 'includes/db.php';

// Requête SQL pour récupérer tous les switchs
$stmt = $bdd->query("SELECT * FROM switchs");
$switchs = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<h1>Catalogue des Switchs Mécaniques</h1>

<div class="catalogue" style="display: flex; gap: 20px; flex-wrap: wrap;">
    <?php if (count($switchs) > 0): ?>
        <?php foreach ($switchs as $switch): ?>
            <div class="carte-switch" style="border: 1px solid #ccc; padding: 15px; border-radius: 8px; width: 300px;">
                <h3><?= htmlspecialchars($switch['nom']) ?> (<?= htmlspecialchars($switch['marque']) ?>)</h3>
                <p><strong>Type:</strong> <?= htmlspecialchars($switch['type_switch']) ?></p>
                <p><strong>Force d'actuation:</strong> <?= htmlspecialchars($switch['force_actuation']) ?>g</p>
                <p><strong>Prix:</strong> <?= htmlspecialchars($switch['prix_unitaire']) ?> €</p>
                
                <a href="switch.php?id=<?= $switch['id_switch'] ?>" style="display:block; margin-top:10px; color:#2E75B6;">Voir les détails</a>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p>Aucun switch trouvé dans la base de données. N'oubliez pas d'insérer vos 10 enregistrements depuis phpMyAdmin !</p>
    <?php endif; ?>
</div>

<?php require 'includes/footer.php'; ?>