<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require 'includes/db.php';
require 'includes/header.php';

// 1. On vérifie si l'ID est bien présent dans l'URL
if (!isset($_GET['id']) || empty($_GET['id'])) {
    // Si pas d'ID, on redirige vers l'accueil
    header('Location: index.php');
    exit;
}

$id_switch = intval($_GET['id']); // Sécurité : on s'assure que c'est un nombre entier

// 2. On récupère les détails du switch correspondant
$stmt = $bdd->prepare("SELECT * FROM switchs WHERE id_switch = :id");
$stmt->execute(['id' => $id_switch]);
$switch = $stmt->fetch(PDO::FETCH_ASSOC);

// Si le switch n'existe pas en base de données (ex: l'utilisateur a tapé id=999)
if (!$switch) {
    echo "<div style='padding:20px;'><h3>Switch introuvable.</h3><a href='index.php'>Retour à l'accueil</a></div>";
    require 'includes/footer.php';
    exit;
}
?>

<div style="padding: 20px; max-width: 800px; margin: 0 auto;">
    <a href="index.php" style="text-decoration: none; color: #555;">← Retour au catalogue</a>

    <div style="display: flex; gap: 40px; margin-top: 20px; align-items: flex-start;">
        
        <div style="flex: 1; border: 1px solid #ddd; padding: 10px; border-radius: 5px; text-align: center;">
            <img src="<?php echo htmlspecialchars($switch['image_url']); ?>" alt="<?php echo htmlspecialchars($switch['nom']); ?>" style="max-width: 100%; height: auto;">
        </div>

        <div style="flex: 2;">
            <span style="text-transform: uppercase; color: #777; font-weight: bold; font-size: 0.9em;">
                <?php echo htmlspecialchars($switch['marque']); ?>
            </span>
            <h1 style="margin: 5px 0 15px 0;"><?php echo htmlspecialchars($switch['nom']); ?></h1>
            
            <p style="font-size: 1.2em; font-weight: bold; color: #2c3e50;">
                Prix unitaire : <?php echo htmlspecialchars($switch['prix_unitaire']); ?> €
            </p>

            <p style="font-style: italic; color: #555; background: #f9f9f9; padding: 10px; border-left: 3px solid #ccc;">
                <?php echo htmlspecialchars($switch['description']); ?>
            </p>

            <h3 style="margin-top: 20px;">Caractéristiques techniques :</h3>
            <ul style="list-style: none; padding: 0; line-height: 2em;">
                <li><strong>Type de sensation :</strong> <?php echo htmlspecialchars($switch['type_switch']); ?></li>
                <li><strong>Force d'actuation :</strong> <?php echo htmlspecialchars($switch['force_actuation']); ?> g</li>
                <li><strong>Course totale :</strong> <?php echo htmlspecialchars($switch['course_totale']); ?> mm</li>
                <li><strong>Pré-actuation :</strong> <?php echo htmlspecialchars($switch['course_preact']); ?> mm</li>
                <li><strong>Niveau sonore :</strong> <?php echo htmlspecialchars($switch['niveau_sonore']); ?></li>
                <li><strong>Compatible Hot-Swap :</strong> <?php echo $switch['hot_swap'] ? 'Oui' : 'Non'; ?></li>
            </ul>

            <?php if (isset($_SESSION['id_user'])): ?>
                <div style="margin-top: 25px;">
                    <a href="ajouter_favori.php?id=<?php echo $switch['id_switch']; ?>" class="btn-accent" style="padding: 10px 15px; text-decoration: none;">
                        ⭐ Ajouter aux favoris
                    </a>
                </div>
            <?php endif; ?>
        </div>

    </div>
</div>

<?php require 'includes/footer.php'; ?>