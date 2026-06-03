<?php
// Fichier : login.php
require 'includes/header.php';
require 'includes/db.php';

// TRAITEMENT DES DONNEES

$erreur = "";

// Si le formulaire est soumis
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $login = $_POST['login'];
    $mdp = $_POST['mot_de_passe'];

    // Requête préparée pour éviter les injections SQL
    $stmt = $bdd->prepare("SELECT * FROM utilisateurs WHERE login = :login");
    $stmt->execute(['login' => $login]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    // Vérification de l'utilisateur (Pour vos tests, retirez password_verify si vous n'avez pas haché les mots de passe dans phpMyAdmin)
    if ($user && $user['mot_de_passe'] === $mdp) {
        // Le mot de passe est correct, on initialise la session
        $_SESSION['id_user'] = $user['id_user'];
        $_SESSION['login'] = $user['login'];
        $_SESSION['role'] = $user['role'];

        // Redirection selon le rôle
        if ($user['role'] === 'admin') {
            header('Location: admin.php');
        } else {
            header('Location: index.php'); // Ou profil.php
        }
        exit;
    } else {
        $erreur = "Identifiant ou mot de passe incorrect.";
    }
}
?>

<h1>Connexion</h1>

<?php if ($erreur): ?>
    <p style="color: red; font-weight: bold;"><?= $erreur ?></p>
<?php endif; ?>

<form method="POST" action="login.php" style="max-width: 300px;">
    <div style="margin-bottom: 15px;">
        <label for="login">Identifiant :</label><br>
        <input type="text" id="login" name="login" required style="width: 100%; padding: 8px;">
    </div>
    
    <div style="margin-bottom: 15px;">
        <label for="mot_de_passe">Mot de passe :</label><br>
        <input type="password" id="mot_de_passe" name="mot_de_passe" required style="width: 100%; padding: 8px;">
    </div>
    
    <button type="submit" class="btn-accent" style="padding: 10px 15px; border: none; color: white; cursor: pointer;">Se connecter</button>
</form>

<?php require 'includes/footer.php'; ?>