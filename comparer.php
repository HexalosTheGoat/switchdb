<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require 'includes/db.php';
require 'includes/header.php';

// Étape 1 : Récupérer tous les switchs pour remplir les listes déroulantes du formulaire
$liste_query = $bdd->query("SELECT id_switch, nom, marque FROM switchs ORDER BY marque, nom");
$tous_les_switchs = $liste_query->fetchAll(PDO::FETCH_ASSOC);

$switchA = null;
$switchB = null;

// Étape 2 : Si les deux switchs ont été sélectionnés, on va chercher leurs détails
if (isset($_GET['switch_a']) && isset($_GET['switch_b'])) {
    $stmt = $bdd->prepare("SELECT * FROM switchs WHERE id_switch = :id");
    
    // Récupération du premier switch
    $stmt->execute(['id' => $_GET['switch_a']]);
    $switchA = $stmt->fetch(PDO::FETCH_ASSOC);
    
    // Récupération du second switch
    $stmt->execute(['id' => $_GET['switch_b']]);
    $switchB = $stmt->fetch(PDO::FETCH_ASSOC);
}
?>

<div style="padding: 20px; max-width: 900px; margin: 0 auto;">
    <h1>Comparateur de Switchs</h1>
    
    <form method="GET" action="comparer.php" style="margin-bottom: 30px; background: #f9f9f9; padding: 15px; border-radius: 5px;">
        <label>Choisir le premier switch : </label>
        <select name="switch_a" required>
            <option value="">-- Sélectionner --</option>
            <?php foreach ($tous_les_switchs as $s): ?>
                <option value="<?php echo $s['id_switch']; ?>" <?php if(isset($_GET['switch_a']) && $_GET['switch_a'] == $s['id_switch']) echo 'selected'; ?>>
                    <?php echo htmlspecialchars($s['marque'] . " - " . $s['nom']); ?>
                </option>
            <?php endforeach; ?>
        </select>

        <span style="margin: 0 15px; font-weight: bold;">VS</span>

        <label>Choisir le second switch : </label>
        <select name="switch_b" required>
            <option value="">-- Sélectionner --</option>
            <?php foreach ($tous_les_switchs as $s): ?>
                <option value="<?php echo $s['id_switch']; ?>" <?php if(isset($_GET['switch_b']) && $_GET['switch_b'] == $s['id_switch']) echo 'selected'; ?>>
                    <?php echo htmlspecialchars($s['marque'] . " - " . $s['nom']); ?>
                </option>
            <?php endforeach; ?>
        </select>

        <button type="submit" style="margin-left: 15px; padding: 5px 15px;">Comparer</button>
    </form>

    <?php if ($switchA && $switchB): ?>
        <table style="width: 100%; border-collapse: collapse; text-align: center;">
            <thead>
                <tr style="background: #f4f4f4;">
                    <th style="padding: 10px; width: 33%; border: 1px solid #ddd;">Caractéristiques</th>
                    <th style="padding: 10px; width: 33%; border: 1px solid #ddd;"><?php echo htmlspecialchars($switchA['nom']); ?></th> [cite: 496]
                    <th style="padding: 10px; width: 33%; border: 1px solid #ddd;"><?php echo htmlspecialchars($switchB['nom']); ?></th> [cite: 496]
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td style="padding: 10px; border: 1px solid #ddd; font-weight: bold;">Marque</td>
                    <td style="padding: 10px; border: 1px solid #ddd;"><?php echo htmlspecialchars($switchA['marque']); ?></td> [cite: 499]
                    <td style="padding: 10px; border: 1px solid #ddd;"><?php echo htmlspecialchars($switchB['marque']); ?></td> [cite: 499]
                </tr>
                <tr>
                    <td style="padding: 10px; border: 1px solid #ddd; font-weight: bold;">Type de switch</td>
                    <td style="padding: 10px; border: 1px solid #ddd;"><?php echo htmlspecialchars($switchA['type_switch']); ?></td> [cite: 501]
                    <td style="padding: 10px; border: 1px solid #ddd;"><?php echo htmlspecialchars($switchB['type_switch']); ?></td> [cite: 501]
                </tr>
                <tr>
                    <td style="padding: 10px; border: 1px solid #ddd; font-weight: bold;">Force d'actuation</td>
                    <td style="padding: 10px; border: 1px solid #ddd;"><?php echo htmlspecialchars($switchA['force_actuation']); ?> g</td> [cite: 503]
                    <td style="padding: 10px; border: 1px solid #ddd;"><?php echo htmlspecialchars($switchB['force_actuation']); ?> g</td> [cite: 503]
                </tr>
                <tr>
                    <td style="padding: 10px; border: 1px solid #ddd; font-weight: bold;">Course totale</td>
                    <td style="padding: 10px; border: 1px solid #ddd;"><?php echo htmlspecialchars($switchA['course_totale']); ?> mm</td> [cite: 507]
                    <td style="padding: 10px; border: 1px solid #ddd;"><?php echo htmlspecialchars($switchB['course_totale']); ?> mm</td> [cite: 507]
                </tr>
                <tr>
                    <td style="padding: 10px; border: 1px solid #ddd; font-weight: bold;">Niveau Sonore</td>
                    <td style="padding: 10px; border: 1px solid #ddd;"><?php echo htmlspecialchars($switchA['niveau_sonore']); ?></td> [cite: 515]
                    <td style="padding: 10px; border: 1px solid #ddd;"><?php echo htmlspecialchars($switchB['niveau_sonore']); ?></td> [cite: 515]
                </tr>
                <tr>
                    <td style="padding: 10px; border: 1px solid #ddd; font-weight: bold;">Prix Unitaire</td>
                    <td style="padding: 10px; border: 1px solid #ddd;"><?php echo htmlspecialchars($switchA['prix_unitaire']); ?> €</td> [cite: 517]
                    <td style="padding: 10px; border: 1px solid #ddd;"><?php echo htmlspecialchars($switchB['prix_unitaire']); ?> €</td> [cite: 517]
                </tr>
            </tbody>
        </table>
    <?php elseif (isset($_GET['switch_a'])): ?>
        <p style="color: red; text-align: center;">Veuillez sélectionner deux switchs différents à comparer.</p>
    <?php endif; ?>
</div>

<?php require 'includes/footer.php'; ?>