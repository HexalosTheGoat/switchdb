<?php
// ============================================================
// comparer.php — Comparateur de deux switchs
// L'utilisateur choisit deux switchs dans deux menus déroulants.
// On affiche ensuite un tableau comparatif.
// ============================================================

session_start();
require 'includes/db.php';

// Récupérer tous les switchs pour les menus déroulants
$tous_switchs = $pdo->query("SELECT id_switch, nom, marque FROM switchs ORDER BY marque, nom")->fetchAll();

// Récupérer les deux switchs choisis par l'utilisateur
// On utilise des valeurs par défaut (1 et 4) si rien n'est sélectionné
$id_a = intval($_GET['switch_a'] ?? 1);
$id_b = intval($_GET['switch_b'] ?? 4);

// Récupérer les données complètes des deux switchs
$stmt_a = $pdo->prepare("SELECT * FROM switchs WHERE id_switch = :id");
$stmt_a->execute([':id' => $id_a]);
$switch_a = $stmt_a->fetch();

$stmt_b = $pdo->prepare("SELECT * FROM switchs WHERE id_switch = :id");
$stmt_b->execute([':id' => $id_b]);
$switch_b = $stmt_b->fetch();

$page_active = 'comparer';
$page_title  = 'Comparer';
include 'includes/header.php';
?>

<div class="conteneur-page">
    <h1>Comparer deux switchs</h1>
    <p class="sous-titre">Sélectionnez deux switchs pour les comparer côte à côte.</p>

    <!-- Formulaire de sélection des deux switchs -->
    <form method="GET" action="comparer.php" class="comparer-form">
        <div class="comparer-selecteurs">

            <div class="select-boite">
                <label for="switch_a">Switch A</label>
                <select name="switch_a" id="switch_a" class="filtre-select">
                    <?php foreach ($tous_switchs as $s) :
                        // "selected" sur l'option actuellement choisie
                        $sel = ($s['id_switch'] == $id_a) ? 'selected' : '';
                    ?>
                        <option value="<?= $s['id_switch'] ?>" <?= $sel ?>>
                            <?= htmlspecialchars($s['marque'] . ' ' . $s['nom']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <span class="vs-label">VS</span>

            <div class="select-boite">
                <label for="switch_b">Switch B</label>
                <select name="switch_b" id="switch_b" class="filtre-select">
                    <?php foreach ($tous_switchs as $s) :
                        $sel = ($s['id_switch'] == $id_b) ? 'selected' : '';
                    ?>
                        <option value="<?= $s['id_switch'] ?>" <?= $sel ?>>
                            <?= htmlspecialchars($s['marque'] . ' ' . $s['nom']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <button type="submit" class="btn-principal">Comparer</button>
        </div>
    </form>

    <?php if ($switch_a && $switch_b) : ?>
        <!-- Tableau comparatif -->
        <div class="tableau-comparaison">
            <table>
                <thead>
                    <tr>
                        <th>Caractéristique</th>
                        <!-- Noms des deux switchs en en-tête -->
                        <th><?= htmlspecialchars($switch_a['marque'] . ' ' . $switch_a['nom']) ?></th>
                        <th><?= htmlspecialchars($switch_b['marque'] . ' ' . $switch_b['nom']) ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // On définit les lignes du tableau dans un tableau PHP
                    // pour éviter de répéter le code HTML pour chaque ligne.
                    // Chaque entrée : [label, valeur_a, valeur_b, lower_is_better]
                    // lower_is_better = true signifie que la valeur la plus basse est mise en vert
                    $lignes = [
                        ['Type',           $switch_a['type_switch'],    $switch_b['type_switch'],    false],
                        ['Niveau sonore',  $switch_a['niveau_sonore'],  $switch_b['niveau_sonore'],  false],
                        ['Force (g)',      $switch_a['force_actuation'],$switch_b['force_actuation'], true],
                        ['Course totale',  $switch_a['course_totale'] . 'mm', $switch_b['course_totale'] . 'mm', false],
                        ['Pré-actuation',  $switch_a['course_preact']  . 'mm', $switch_b['course_preact']  . 'mm', false],
                        ['Prix unitaire',  number_format($switch_a['prix_unitaire'],2,',','') . '€',
                                           number_format($switch_b['prix_unitaire'],2,',','') . '€', true],
                        ['Hot-swap',       $switch_a['hot_swap'] ? '✅ Oui' : '❌ Non',
                                           $switch_b['hot_swap'] ? '✅ Oui' : '❌ Non', false],
                    ];

                    foreach ($lignes as [$label, $val_a, $val_b, $lower]) :
                        // On détermine quelle valeur est "meilleure" pour mettre en vert
                        // (uniquement pour les valeurs numériques comparables)
                        $classe_a = '';
                        $classe_b = '';
                        $num_a = floatval($val_a);
                        $num_b = floatval($val_b);
                        if (is_numeric($num_a) && is_numeric($num_b) && $num_a !== $num_b) {
                            if ($lower) {
                                // La valeur la plus basse est meilleure (prix, force)
                                $classe_a = $num_a < $num_b ? 'gagnant' : '';
                                $classe_b = $num_b < $num_a ? 'gagnant' : '';
                            }
                        }
                    ?>
                        <tr>
                            <td class="comp-label"><?= $label ?></td>
                            <td class="<?= $classe_a ?>"><?= htmlspecialchars($val_a) ?></td>
                            <td class="<?= $classe_b ?>"><?= htmlspecialchars($val_b) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>

<?php include 'includes/footer.php'; ?>
