<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
// Sécurité : Un utilisateur non inscrit ou non connecté ne peut pas accéder aux favoris
if (!isset($_SESSION['id_user'])) {
    header('Location: login.php');
    exit;
}

require 'includes/db.php';
require 'includes/header.php';

// Récupération des switchs mis en favoris par l'utilisateur connecté
$stmt = $bdd->prepare("
    SELECT s.id_switch, s.nom, s.marque, s.type_switch, s.prix_unitaire, f.date_ajout 
    FROM favoris f
    INNER JOIN switchs s ON f.id_switch = s.id_switch
    WHERE f.id_user = :id_user
");
$stmt->execute(['id_user' => $_SESSION['id_user']]);
$mes_favoris = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<div style="padding: 20px; max-width: 800px; margin: 0 auto;">
    <h1>Mes Switchs Favoris</h1>
    
    <?php if (empty($mes_favoris)): ?>
        <p>Vous n'avez pas encore ajouté de switch à vos favoris.</p>
    <?php else: ?>
        <table style="width: 100%; border-collapse: collapse; margin-top: 20px;">
            <thead>
                <tr style="background-color: #f4f4f4; text-align: left;">
                    <th style="padding: 10px; border-bottom: 2px solid #ddd;">Nom</th>
                    <th style="padding: 10px; border-bottom: 2px solid #ddd;">Marque</th>
                    <th style="padding: 10px; border-bottom: 2px solid #ddd;">Type</th>
                    <th style="padding: 10px; border-bottom: 2px solid #ddd;">Prix</th>
                    <th style="padding: 10px; border-bottom: 2px solid #ddd;">Ajouté le</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($mes_favoris as $fav): ?>
                    <tr>
                        <td style="padding: 10px; border-bottom: 1px solid #ddd;"><?php echo htmlspecialchars($fav['nom']); ?></td>
                        <td style="padding: 10px; border-bottom: 1px solid #ddd;"><?php echo htmlspecialchars($fav['marque']); ?></td>
                        <td style="padding: 10px; border-bottom: 1px solid #ddd;"><?php echo htmlspecialchars($fav['type_switch']); ?></td>
                        <td style="padding: 10px; border-bottom: 1px solid #ddd;"><?php echo htmlspecialchars($fav['prix_unitaire']); ?> €</td>
                        <td style="padding: 10px; border-bottom: 1px solid #ddd;"><?php echo htmlspecialchars($fav['date_ajout']); ?></td>
                        <td style="padding: 10px; border-bottom: 1px solid #ddd;"><a href="retirer_favori.php?id=<?php echo $fav['id_switch']; ?>" style="color: red; text-decoration: none; font-weight: bold;">❌ Retirer</a></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>

<?php require 'includes/footer.php'; ?>