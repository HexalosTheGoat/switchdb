<?php
// ============================================================
// index.php — Page d'accueil
// Affiche la barre de recherche, les filtres et les résultats.
// ============================================================

// On définit ces variables AVANT d'inclure le header
// pour que le bon lien soit surligné dans la navbar
$page_active = 'accueil';
$page_title  = 'Accueil';

// On inclut la connexion à la base de données
require 'includes/db.php';

// ============================================================
// ÉTAPE 1 : Récupérer les valeurs envoyées par le formulaire
// ============================================================
// $_GET contient les données envoyées dans l'URL (méthode GET).
// Ex : index.php?recherche=cherry&type=lineaire
// On utilise ?? '' pour éviter les erreurs si la clé n'existe pas.

$recherche = $_GET['recherche'] ?? '';   // texte saisi dans la barre de recherche
$filtre_type   = $_GET['type']   ?? '';  // type de switch (lineaire, tactile, clicky)
$filtre_bruit  = $_GET['bruit']  ?? '';  // niveau sonore
$filtre_marque = $_GET['marque'] ?? '';  // marque
$filtre_prix   = $_GET['prix']   ?? '';  // prix maximum
$tri           = $_GET['tri']    ?? 'nom'; // critère de tri

// ============================================================
// ÉTAPE 2 : Construire la requête SQL avec les filtres
// ============================================================
// On utilise les requêtes préparées (prepare/execute) pour éviter
// les injections SQL — c'est la bonne pratique en PHP/PDO.

// On commence par la requête de base (sans filtre)
$sql    = "SELECT * FROM switchs WHERE 1=1";
// Le tableau $params contiendra les valeurs à lier à la requête
$params = [];

// Pour chaque filtre, si l'utilisateur l'a renseigné,
// on ajoute une condition WHERE à la requête.

// Filtre texte : cherche dans le nom ET la marque
if ($recherche !== '') {
    $sql     .= " AND (nom LIKE :recherche OR marque LIKE :recherche2)";
    $params[':recherche']  = '%' . $recherche . '%';  // % = n'importe quels caractères
    $params[':recherche2'] = '%' . $recherche . '%';
}

if ($filtre_type !== '') {
    $sql .= " AND type_switch = :type";
    $params[':type'] = $filtre_type;
}

if ($filtre_bruit !== '') {
    $sql .= " AND niveau_sonore = :bruit";
    $params[':bruit'] = $filtre_bruit;
}

if ($filtre_marque !== '') {
    $sql .= " AND marque = :marque";
    $params[':marque'] = $filtre_marque;
}

if ($filtre_prix !== '') {
    $sql .= " AND prix_unitaire <= :prix";
    $params[':prix'] = $filtre_prix;
}

// Tri des résultats
// On utilise un tableau pour sécuriser les valeurs autorisées
// (on ne met JAMAIS une variable directement dans ORDER BY)
$tris_autorises = ['nom', 'prix_unitaire', 'force_actuation', 'marque'];
if (!in_array($tri, $tris_autorises)) {
    $tri = 'nom'; // valeur par défaut si valeur non autorisée
}
$sql .= " ORDER BY $tri ASC";

// On prépare et on exécute la requête
$stmt = $pdo->prepare($sql);
$stmt->execute($params);

// fetchAll() récupère TOUTES les lignes du résultat dans un tableau PHP
$switchs = $stmt->fetchAll();

// On compte le nombre de résultats pour l'afficher
$nb_resultats = count($switchs);

// ============================================================
// ÉTAPE 3 : Récupérer les marques distinctes pour le filtre
// ============================================================
// Cette requête récupère toutes les marques uniques de la BDD
// pour afficher les options du filtre "Marque"
$stmt_marques = $pdo->query("SELECT DISTINCT marque FROM switchs ORDER BY marque");
$marques = $stmt_marques->fetchAll();

// On inclut maintenant le header HTML (navbar, etc.)
include 'includes/header.php';
?>

<!-- ======================================================
     PAGE D'ACCUEIL — CONTENU PRINCIPAL
     ====================================================== -->

<div class="hero">
    <h1>Trouvez le switch idéal<br>pour <span class="accent">votre clavier</span></h1>
    <p class="hero-sous-titre">
        Comparez, filtrez et découvrez parmi nos switchs mécaniques et magnétiques.
    </p>
</div>

<!-- ======================================================
     FORMULAIRE DE RECHERCHE + FILTRES
     method="GET" : les valeurs apparaissent dans l'URL,
     ce qui permet de partager/bookmarker une recherche.
     ====================================================== -->
<div class="conteneur-principal">

    <!-- ── COLONNE GAUCHE : filtres ── -->
    <aside class="sidebar">
        <form method="GET" action="index.php">

            <!-- Barre de recherche texte -->
            <div class="filtre-carte">
                <h3 class="filtre-titre">Recherche</h3>
                <input
                    type="text"
                    name="recherche"
                    class="champ-recherche"
                    placeholder="Ex : Cherry MX Red..."
                    value="<?= htmlspecialchars($recherche) ?>"
                >
                <!-- htmlspecialchars() empêche l'injection de code HTML
                     dans la valeur affichée dans le champ -->
            </div>

            <!-- Filtre : Type de switch -->
            <div class="filtre-carte">
                <h3 class="filtre-titre">Type de switch</h3>
                <div class="filtre-groupe">
                    <?php
                    // On génère les 3 options avec une boucle
                    $types = ['lineaire' => 'Linéaire', 'tactile' => 'Tactile', 'clicky' => 'Clicky'];
                    foreach ($types as $valeur => $label) :
                        // "checked" est ajouté si c'est la valeur actuellement filtrée
                        $checked = ($filtre_type === $valeur) ? 'checked' : '';
                    ?>
                        <label class="filtre-item">
                            <input type="radio" name="type" value="<?= $valeur ?>" <?= $checked ?>>
                            <?= $label ?>
                        </label>
                    <?php endforeach; ?>
                    <!-- Option "Tous" pour effacer le filtre type -->
                    <label class="filtre-item">
                        <input type="radio" name="type" value="" <?= $filtre_type === '' ? 'checked' : '' ?>>
                        Tous
                    </label>
                </div>
            </div>

            <!-- Filtre : Niveau sonore -->
            <div class="filtre-carte">
                <h3 class="filtre-titre">Niveau sonore</h3>
                <div class="filtre-groupe">
                    <?php
                    $bruits = ['silencieux' => '🔇 Silencieux', 'modere' => '🔉 Modéré', 'fort' => '🔊 Fort'];
                    foreach ($bruits as $valeur => $label) :
                        $checked = ($filtre_bruit === $valeur) ? 'checked' : '';
                    ?>
                        <label class="filtre-item">
                            <input type="radio" name="bruit" value="<?= $valeur ?>" <?= $checked ?>>
                            <?= $label ?>
                        </label>
                    <?php endforeach; ?>
                    <label class="filtre-item">
                        <input type="radio" name="bruit" value="" <?= $filtre_bruit === '' ? 'checked' : '' ?>>
                        Tous
                    </label>
                </div>
            </div>

            <!-- Filtre : Marque (données venant de la BDD) -->
            <div class="filtre-carte">
                <h3 class="filtre-titre">Marque</h3>
                <div class="filtre-groupe">
                    <?php foreach ($marques as $m) :
                        $checked = ($filtre_marque === $m['marque']) ? 'checked' : '';
                    ?>
                        <label class="filtre-item">
                            <input type="radio" name="marque" value="<?= htmlspecialchars($m['marque']) ?>" <?= $checked ?>>
                            <?= htmlspecialchars($m['marque']) ?>
                        </label>
                    <?php endforeach; ?>
                    <label class="filtre-item">
                        <input type="radio" name="marque" value="" <?= $filtre_marque === '' ? 'checked' : '' ?>>
                        Toutes
                    </label>
                </div>
            </div>

            <!-- Filtre : Prix maximum -->
            <div class="filtre-carte">
                <h3 class="filtre-titre">Prix max (€/switch)</h3>
                <select name="prix" class="filtre-select">
                    <option value="">Tous les prix</option>
                    <option value="0.30" <?= $filtre_prix === '0.30' ? 'selected' : '' ?>>Moins de 0,30€</option>
                    <option value="0.60" <?= $filtre_prix === '0.60' ? 'selected' : '' ?>>Moins de 0,60€</option>
                    <option value="1.00" <?= $filtre_prix === '1.00' ? 'selected' : '' ?>>Moins de 1,00€</option>
                    <option value="1.50" <?= $filtre_prix === '1.50' ? 'selected' : '' ?>>Tous</option>
                </select>
            </div>

            <!-- Tri -->
            <div class="filtre-carte">
                <h3 class="filtre-titre">Trier par</h3>
                <select name="tri" class="filtre-select">
                    <option value="nom"            <?= $tri === 'nom'            ? 'selected' : '' ?>>Nom</option>
                    <option value="prix_unitaire"  <?= $tri === 'prix_unitaire'  ? 'selected' : '' ?>>Prix</option>
                    <option value="force_actuation"<?= $tri === 'force_actuation'? 'selected' : '' ?>>Force</option>
                    <option value="marque"         <?= $tri === 'marque'         ? 'selected' : '' ?>>Marque</option>
                </select>
            </div>

            <!-- Boutons du formulaire -->
            <button type="submit" class="btn-principal btn-large">Appliquer les filtres</button>
            <!-- Le lien "Réinitialiser" recharge la page sans paramètres -->
            <a href="index.php" class="btn-secondaire btn-large">Réinitialiser</a>

        </form>
    </aside>

    <!-- ── COLONNE DROITE : résultats ── -->
    <main class="zone-resultats">

        <!-- Nombre de résultats -->
        <p class="nb-resultats">
            <?= $nb_resultats ?> switch<?= $nb_resultats > 1 ? 's' : '' ?> trouvé<?= $nb_resultats > 1 ? 's' : '' ?>
        </p>

        <?php if ($nb_resultats === 0) : ?>
            <!-- Aucun résultat -->
            <div class="etat-vide">
                <p>😕 Aucun switch ne correspond à vos critères.</p>
                <a href="index.php">Voir tous les switchs</a>
            </div>

        <?php else : ?>
            <!-- Grille de cartes -->
            <div class="grille-cartes">
                <?php
                // On boucle sur tous les switchs récupérés en BDD
                foreach ($switchs as $s) :
                    // On détermine l'emoji selon le type de switch
                    $emoji = match($s['type_switch']) {
                        'lineaire' => '🔴',
                        'tactile'  => '🟣',
                        'clicky'   => '🔵',
                        default    => '⚪'
                    };

                    // On vérifie si ce switch est en favori de l'utilisateur connecté
                    $est_favori = false;
                    if (isset($_SESSION['id_user'])) {
                        $stmt_fav = $pdo->prepare(
                            "SELECT COUNT(*) FROM favoris WHERE id_user = :u AND id_switch = :s"
                        );
                        $stmt_fav->execute([':u' => $_SESSION['id_user'], ':s' => $s['id_switch']]);
                        $est_favori = $stmt_fav->fetchColumn() > 0;
                    }
                ?>
                    <div class="carte-switch">
                        <!-- Icône du switch -->
                        <div class="carte-image"><?= $emoji ?></div>

                        <div class="carte-corps">
                            <!-- Marque et nom -->
                            <p class="carte-marque"><?= htmlspecialchars($s['marque']) ?></p>
                            <h2 class="carte-nom"><?= htmlspecialchars($s['nom']) ?></h2>

                            <!-- Badges type et bruit -->
                            <div class="carte-badges">
                                <span class="badge badge-<?= $s['type_switch'] ?>">
                                    <?= $s['type_switch'] ?>
                                </span>
                                <?php if ($s['niveau_sonore'] === 'silencieux') : ?>
                                    <span class="badge badge-silencieux">silencieux</span>
                                <?php endif; ?>
                                <?php if ($s['hot_swap']) : ?>
                                    <span class="badge badge-hotswap">hot-swap</span>
                                <?php endif; ?>
                            </div>

                            <!-- Specs rapides -->
                            <div class="carte-specs">
                                <span>⚡ <?= $s['force_actuation'] ?>g</span>
                                <span>📏 <?= $s['course_totale'] ?>mm</span>
                            </div>

                            <!-- Prix -->
                            <p class="carte-prix">
                                <?= number_format($s['prix_unitaire'], 2, ',', '') ?>€
                                <small>/switch</small>
                            </p>

                            <!-- Boutons -->
                            <div class="carte-actions">
                                <!-- Lien vers la page de détail (on passe l'id en GET) -->
                                <a href="switch.php?id=<?= $s['id_switch'] ?>" class="btn-detail">
                                    Voir détails
                                </a>

                                <?php if (isset($_SESSION['id_user'])) : ?>
                                    <!-- Bouton favori : pointe vers favoris.php avec l'action et l'id -->
                                    <a href="favoris.php?action=<?= $est_favori ? 'supprimer' : 'ajouter' ?>&id=<?= $s['id_switch'] ?>&retour=index.php"
                                       class="btn-favori <?= $est_favori ? 'actif' : '' ?>"
                                       title="<?= $est_favori ? 'Retirer des favoris' : 'Ajouter aux favoris' ?>">
                                        <?= $est_favori ? '★' : '☆' ?>
                                    </a>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>

                <?php endforeach; ?>
            </div>
        <?php endif; ?>

    </main>
</div>

<?php
// On inclut le pied de page HTML
include 'includes/footer.php';
?>
