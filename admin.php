<?php
session_start();
// 1. SÉCURISATION STRICTE
// Si l'utilisateur n'est pas connecté OU si son rôle n'est pas 'admin', on le renvoie à l'accueil
if (!isset($_SESSION['id_user']) || $_SESSION['role'] !== 'admin') {
    header('Location: index.php');
    exit;
}

require 'includes/header.php';
require 'includes/db.php';
?>

<div style="padding: 20px;">
    <h1>Tableau de bord Administrateur</h1>
    <p>Bienvenue, <?php echo htmlspecialchars($_SESSION['login']); ?>. Vous avez les droits complets (chmod 777) sur Cybros.</p>

    <hr>

    <!-- 2. SECTION AJOUT DE SWITCH -->
    <section style="margin-bottom: 40px;">
        <h2>Ajouter un nouveau Switch au catalogue</h2>
        <form method="POST" action="ajouter_switch.php" style="max-width: 600px;">
    
            <div style="margin-bottom: 10px;">
                <label>Nom du switch :</label>
                <input type="text" name="nom" required style="width: 100%;">
            </div>
    
            <div style="margin-bottom: 10px;">
                <label>Marque :</label>
                <input type="text" name="marque" required style="width: 100%;">
            </div>
    
            <div style="margin-bottom: 10px;">
                <label>Type :</label>
                <select name="type_switch" style="width: 100%;">
                    <option value="lineaire">Linéaire</option>
                    <option value="tactile">Tactile</option>
                    <option value="clicky">Clicky</option>
                </select>
            </div>

            <div style="margin-bottom: 10px;">
                <label>Force d'actuation (en g/cN) :</label>
                <input type="num        ber" name="force_actuation" required style="width: 100%;">
            </div>

            <div style="margin-bottom: 10px;">
                <label>Course totale (mm) :</label>
                <input type="number" step="0.1" name="course_totale" required style="width: 100%;">
            </div>

            <div style="margin-bottom: 10px;">
                <label>Course pré-actuation (mm) :</label>
                <input type="number" step="0.1" name="course_preact" required style="width: 100%;">
            </div>

            <div style="margin-bottom: 10px;">
                <label>Niveau sonore :</label>
                <select name="niveau_sonore" style="width: 100%;">
                    <option value="silencieux">Silencieux</option>
                    <option value="modere">Modéré</option>
                    <option value="fort">Fort</option>
                </select>
            </div>

            div style="margin-bottom: 10px;">
                <label>Prix unitaire (€) :</label>
                <input type="number" step="0.01" name="prix_unitaire" required style="width: 100%;">
            </div>

            <div style="margin-bottom: 10px;">
                <label>Compatible Hot-Swap :</label>
                <select name="hot_swap" style="width: 100%;">
                    <option value="1">Oui</option>
                    <option value="0">Non</option>
                </select>
            </div>

            <div style="margin-bottom: 10px;">
                <label>Description :</label>
                <textarea name="description" style="width: 100%;" rows="4"></textarea>
            </div>

            <div style="margin-bottom: 15px;">
                <label>Chemin de l'image (ex: images/mx_red.jpg) :</label>
                <input type="text" name="image_url" required style="width: 100%;">
            </div>

            <button type="submit" class="btn-accent" style="padding: 10px 15px;">Insérer dans le catalogue</button>
        </form>
    </section>

    <hr>

    <!-- 3. SECTION GESTION DES SWITCHS EXISTANTS -->
    <section>
        <h2>Gérer le catalogue actuel</h2>
        <p><em>(Ici, vous pourrez avec Quentin intégrer une boucle PHP qui affiche un tableau avec la liste des switchs et des boutons "Modifier" / "Supprimer")</em></p>
    </section>
</div>

<?php require 'includes/footer.php'; ?>