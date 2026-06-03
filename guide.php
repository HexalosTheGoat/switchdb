<?php
// ============================================================
// guide.php — Page guide / section éducative
// Page statique (pas de requête SQL nécessaire ici,
// sauf pour charger les kits d'entretien depuis la BDD).
// ============================================================

session_start();
require 'includes/db.php';

// Récupération des kits d'entretien depuis la BDD
$kits = $pdo->query("SELECT * FROM kits_entretien ORDER BY prix ASC")->fetchAll();

// L'onglet actif est passé dans l'URL : guide.php?onglet=installation
// Par défaut on affiche le premier onglet
$onglet = $_GET['onglet'] ?? 'types';

// Liste des onglets autorisés (sécurité : on ne met pas n'importe quoi)
$onglets_autorises = ['types', 'mecanique', 'installation', 'entretien', 'kits'];
if (!in_array($onglet, $onglets_autorises)) {
    $onglet = 'types';
}

$page_active = 'guide';
$page_title  = 'Guide';
include 'includes/header.php';
?>

<div class="conteneur-page">
    <h1>Guide des switchs</h1>
    <p class="sous-titre">Tout ce que vous devez savoir pour choisir, installer et entretenir vos switchs.</p>

    <!-- Navigation par onglets -->
    <!-- Chaque lien recharge la page avec un onglet différent -->
    <div class="guide-onglets">
        <?php
        $labels = [
            'types'        => 'Types de switchs',
            'mecanique'    => 'Méca vs Membrane',
            'installation' => 'Installation',
            'entretien'    => 'Entretien',
            'kits'         => 'Kits d\'outils',
        ];
        foreach ($labels as $cle => $label) :
            $classe = ($onglet === $cle) ? 'guide-onglet actif' : 'guide-onglet';
        ?>
            <a href="guide.php?onglet=<?= $cle ?>" class="<?= $classe ?>"><?= $label ?></a>
        <?php endforeach; ?>
    </div>

    <!-- Contenu de l'onglet actif -->
    <div class="guide-contenu">

        <?php if ($onglet === 'types') : ?>
            <h2>Les trois familles de switchs</h2>
            <p>Il existe trois grandes catégories de switchs mécaniques :</p>
            <div class="type-cartes">
                <div class="type-carte">
                    <div class="type-icone">🔴</div>
                    <h3>Linéaire</h3>
                    <p>Course fluide et régulière, sans retour tactile ni cliquetis. Idéal pour le gaming. Exemples : Cherry MX Red, Gateron Yellow.</p>
                </div>
                <div class="type-carte">
                    <div class="type-icone">🟣</div>
                    <h3>Tactile</h3>
                    <p>Retour tactile perceptible au point d'actuation, sans cliquetis sonore. Excellent pour la frappe mixte. Exemples : Holy Panda, Boba U4.</p>
                </div>
                <div class="type-carte">
                    <div class="type-icone">🔵</div>
                    <h3>Clicky</h3>
                    <p>Retour tactile ET cliquetis sonore. Sensation satisfaisante mais bruyant. Exemples : Cherry MX Blue, Kailh Box White.</p>
                </div>
            </div>

        <?php elseif ($onglet === 'mecanique') : ?>
            <h2>Clavier mécanique vs membrane</h2>
            <p>Les claviers à <strong>membrane</strong> utilisent une nappe en caoutchouc sous les touches. Ils sont moins chers mais moins précis et moins durables (5 à 10 millions de frappes).</p>
            <p>Les claviers <strong>mécaniques</strong> utilisent un switch individuel par touche. Chaque switch a une durée de vie de 50 à 100 millions de frappes. Ils offrent une frappe précise et personnalisable.</p>
            <h3>Et les switchs magnétiques ?</h3>
            <p>Une technologie récente (Hall Effect) utilise des aimants au lieu de contacts physiques. L'actuation est ajustable via logiciel (0,1mm à 4mm). Idéal pour le gaming compétitif.</p>

        <?php elseif ($onglet === 'installation') : ?>
            <h2>Installer ou remplacer un switch</h2>
            <div class="etapes">
                <?php
                // Les étapes sont dans un tableau pour être faciles à modifier
                $etapes = [
                    ['Vérifier la compatibilité', 'Assurez-vous que le switch utilise un connecteur 3 ou 5 broches compatible avec votre PCB.'],
                    ['Retirer les keycaps',        'Utilisez un tire-keycap pour retirer délicatement les touches sans les abîmer.'],
                    ['Extraire le switch',          'Si hot-swap : pincez les deux clips avec un tire-switch et tirez vers le haut.'],
                    ['Insérer le nouveau switch',  'Alignez les broches correctement puis enfoncez jusqu\'au clic de verrouillage.'],
                    ['Remettre les keycaps',        'Appuyez doucement sur chaque touche jusqu\'à entendre le clip.'],
                    ['Tester',                     'Testez chaque touche avec un outil en ligne (keyboardchecker.com).'],
                ];
                foreach ($etapes as $i => [$titre, $texte]) :
                ?>
                    <div class="etape">
                        <div class="etape-num"><?= $i + 1 ?></div>
                        <div class="etape-texte">
                            <h4><?= htmlspecialchars($titre) ?></h4>
                            <p><?= htmlspecialchars($texte) ?></p>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

        <?php elseif ($onglet === 'entretien') : ?>
            <h2>Entretien des switchs</h2>
            <h3>Lubrification</h3>
            <p>Lubrifier ses switchs réduit les frottements et améliore la sensation de frappe. Pour les <strong>linéaires</strong>, utilisez Krytox 205g0. Pour les <strong>tactiles</strong>, utilisez Tribosys 3203 (préserve le retour tactile).</p>
            <h3>Nettoyage</h3>
            <p>Démontez le switch si possible, nettoyez les résidus avec de l'isopropanol à 90% et une brosse fine. Laissez sécher complètement avant de remonter.</p>
            <h3>Fréquence recommandée</h3>
            <p>Tous les 12 à 18 mois pour un usage quotidien modéré. Tous les 6 à 12 mois pour un usage intensif.</p>

        <?php elseif ($onglet === 'kits') : ?>
            <h2>Kits d'outils recommandés</h2>
            <div class="kits-grille">
                <?php
                // On affiche les kits récupérés depuis la BDD
                foreach ($kits as $kit) :
                ?>
                    <div class="kit-carte">
                        <div class="kit-icone">🔧</div>
                        <div class="kit-info">
                            <h4><?= htmlspecialchars($kit['nom_kit']) ?></h4>
                            <p><?= htmlspecialchars($kit['contenu']) ?></p>
                            <p class="kit-prix"><?= number_format($kit['prix'], 2, ',', '') ?>€</p>
                            <?php if ($kit['lien_achat']) : ?>
                                <a href="<?= htmlspecialchars($kit['lien_achat']) ?>"
                                   target="_blank" class="btn-secondaire">Voir le produit</a>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

    </div>
</div>

<?php include 'includes/footer.php'; ?>
