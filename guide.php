<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require 'includes/db.php';
require 'includes/header.php';

// Récupération des kits d'entretien depuis la base de données
$query = $bdd->query("SELECT * FROM kits_entretien");
$kits = $query->fetchAll(PDO::FETCH_ASSOC);
?>

<div style="padding: 20px; max-width: 800px; margin: 0 auto;">
    <h1>Guide Éducatif des Switchs</h1> 
    
    <section style="margin-bottom: 30px;">
        <h2>1. Comprendre les types de switchs</h2>
        <ul>
            <li><strong>Linéaire :</strong> Course fluide et silencieuse, très apprécié pour les jeux vidéo.</li>
            <li><strong>Tactile :</strong> Offre une légère résistance (une bosse) ressentie au moment de l'activation, idéal pour la dactylographie.</li>
            <li><strong>Clicky :</strong> Émet un clic sonore net et une sensation tactile prononcée au moment de la frappe.</li>
        </ul>
    </section>

    <section style="margin-bottom: 30px;">
        <h2>2. Conseils d'entretien et de lubrification</h2>
        <p>Pour préserver la fluidité et atténuer le bruit de vos switchs, il est recommandé de lubrifier régulièrement le boîtier (housing) et la tige (stem) à l'aide d'un lubrifiant adapté comme la Krytox .</p>
    </section>

    <section>
        <h2>3. Nos kits d'entretien référencés</h2>
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
            <?php foreach ($kits as $kit): ?>
                <div style="border: 1px solid #ccc; padding: 15px; border-radius: 5px;">
                    <h3><?php echo htmlspecialchars($kit['nom_kit']); ?></h3>
                    <p><?php echo htmlspecialchars($kit['contenu']); ?></p>
                    <p><strong>Prix :</strong> <?php echo htmlspecialchars($kit['prix']); ?> €</p>
                </div>
            <?php endforeach; ?>
        </div>
    </section>
</div>

<?php require 'includes/footer.php'; ?>