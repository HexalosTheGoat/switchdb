<?php
// ============================================================
// login.php — Page de connexion et d'inscription
// ============================================================

// On démarre la session en tout premier
session_start();

// Si l'utilisateur est déjà connecté, on le redirige vers l'accueil
if (isset($_SESSION['id_user'])) {
    header('Location: index.php');
    exit;
}

require 'includes/db.php';

// Variable pour stocker les messages d'erreur à afficher
$erreur = '';

// ============================================================
// TRAITEMENT DU FORMULAIRE DE CONNEXION
// ============================================================
// $_SERVER['REQUEST_METHOD'] vaut 'POST' quand le formulaire est soumis
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action_login'])) {

    // On récupère et nettoie les données du formulaire
    // trim() supprime les espaces en début et fin de chaîne
    $login_saisi = trim($_POST['login']    ?? '');
    $mdp_saisi   = trim($_POST['mot_de_passe'] ?? '');

    // Vérification : les champs ne doivent pas être vides
    if ($login_saisi === '' || $mdp_saisi === '') {
        $erreur = 'Veuillez remplir tous les champs.';

    } else {
        // On cherche l'utilisateur dans la BDD par son login
        $stmt = $pdo->prepare(
            "SELECT * FROM utilisateurs WHERE login = :login"
        );
        $stmt->execute([':login' => $login_saisi]);
        $utilisateur = $stmt->fetch();

        // password_verify() compare le mot de passe saisi avec le hash stocké en BDD.
        // On ne stocke JAMAIS un mot de passe en clair en base de données.
        // password_hash() lors de l'inscription génère un hash, et
        // password_verify() vérifie que le mot de passe correspond à ce hash.
        if ($utilisateur && password_verify($mdp_saisi, $utilisateur['mot_de_passe'])) {
            // Connexion réussie : on stocke les infos en session
            $_SESSION['id_user'] = $utilisateur['id_user'];
            $_SESSION['login']   = $utilisateur['login'];
            $_SESSION['role']    = $utilisateur['role'];

            // Message de bienvenue affiché sur la prochaine page
            $_SESSION['flash'] = [
                'type' => 'succes',
                'msg'  => 'Bienvenue ' . $utilisateur['login'] . ' !'
            ];

            // Redirection vers l'accueil
            header('Location: index.php');
            exit;

        } else {
            // Identifiants incorrects
            // On ne dit PAS si c'est le login ou le mot de passe qui est faux
            // (pour des raisons de sécurité)
            $erreur = 'Login ou mot de passe incorrect.';
        }
    }
}

// ============================================================
// TRAITEMENT DU FORMULAIRE D'INSCRIPTION
// ============================================================
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action_inscription'])) {

    $nouveau_login = trim($_POST['nouveau_login'] ?? '');
    $email         = trim($_POST['email']         ?? '');
    $nouveau_mdp   = trim($_POST['nouveau_mdp']   ?? '');

    // Validations
    if ($nouveau_login === '' || $email === '' || $nouveau_mdp === '') {
        $erreur = 'Veuillez remplir tous les champs.';

    } elseif (strlen($nouveau_mdp) < 6) {
        $erreur = 'Le mot de passe doit faire au moins 6 caractères.';

    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        // filter_var() avec FILTER_VALIDATE_EMAIL vérifie le format de l'email
        $erreur = 'Adresse email invalide.';

    } else {
        // On vérifie que le login n'est pas déjà pris
        $stmt_check = $pdo->prepare(
            "SELECT COUNT(*) FROM utilisateurs WHERE login = :login OR email = :email"
        );
        $stmt_check->execute([':login' => $nouveau_login, ':email' => $email]);
        $existe = $stmt_check->fetchColumn();

        if ($existe > 0) {
            $erreur = 'Ce login ou cet email est déjà utilisé.';
        } else {
            // On hash le mot de passe avant de le stocker en BDD
            // PASSWORD_DEFAULT utilise bcrypt, l'algorithme recommandé
            $hash = password_hash($nouveau_mdp, PASSWORD_DEFAULT);

            // Insertion du nouvel utilisateur
            $stmt_insert = $pdo->prepare(
                "INSERT INTO utilisateurs (login, mot_de_passe, email, role, date_inscription)
                 VALUES (:login, :mdp, :email, 'user', CURDATE())"
            );
            $stmt_insert->execute([
                ':login' => $nouveau_login,
                ':mdp'   => $hash,
                ':email' => $email
            ]);

            // Message de succès et redirection vers la page de connexion
            $_SESSION['flash'] = [
                'type' => 'succes',
                'msg'  => 'Compte créé ! Vous pouvez maintenant vous connecter.'
            ];
            header('Location: login.php');
            exit;
        }
    }
}

$page_active = '';
$page_title  = 'Connexion';
include 'includes/header.php';
?>

<!-- ======================================================
     PAGE CONNEXION — CONTENU
     ====================================================== -->
<div class="login-conteneur">
    <div class="login-boite">

        <h1 class="login-logo">CYBROS</h1>
        <p class="login-sous">Connectez-vous pour accéder à vos favoris</p>

        <?php if ($erreur !== '') : ?>
            <!-- Affichage du message d'erreur -->
            <div class="flash flash-erreur"><?= htmlspecialchars($erreur) ?></div>
        <?php endif; ?>

        <!-- Onglets Connexion / Inscription -->
        <!-- On utilise JavaScript pour basculer entre les deux formulaires -->
        <div class="login-onglets">
            <button onclick="afficherOnglet('connexion')" id="onglet-connexion" class="onglet actif">
                Connexion
            </button>
            <button onclick="afficherOnglet('inscription')" id="onglet-inscription" class="onglet">
                Inscription
            </button>
        </div>

        <!-- ── Formulaire de connexion ── -->
        <div id="form-connexion">
            <!-- action="" : le formulaire s'envoie à la même page (login.php) -->
            <!-- method="POST" : les données ne sont pas visibles dans l'URL -->
            <form method="POST" action="login.php">
                <!-- Champ caché pour identifier quel formulaire a été soumis -->
                <input type="hidden" name="action_login" value="1">

                <div class="champ-groupe">
                    <label for="login">Login</label>
                    <input type="text" id="login" name="login"
                           placeholder="votre_login" required>
                </div>

                <div class="champ-groupe">
                    <label for="mot_de_passe">Mot de passe</label>
                    <input type="password" id="mot_de_passe" name="mot_de_passe"
                           placeholder="••••••••" required>
                </div>

                <button type="submit" class="btn-principal btn-large">
                    Se connecter
                </button>
            </form>

            <!-- Indication pour la démonstration -->
            <div class="hint-boite">
                💡 <strong>Comptes de démo :</strong><br>
                Admin : <code>admin</code> / <code>admin123</code><br>
                Utilisateur : <code>utilisateur</code> / <code>user123</code>
            </div>
        </div>

        <!-- ── Formulaire d'inscription (caché par défaut) ── -->
        <div id="form-inscription" style="display:none">
            <form method="POST" action="login.php">
                <input type="hidden" name="action_inscription" value="1">

                <div class="champ-groupe">
                    <label for="nouveau_login">Login</label>
                    <input type="text" id="nouveau_login" name="nouveau_login"
                           placeholder="Choisir un login" required>
                </div>

                <div class="champ-groupe">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email"
                           placeholder="email@exemple.fr" required>
                </div>

                <div class="champ-groupe">
                    <label for="nouveau_mdp">Mot de passe</label>
                    <input type="password" id="nouveau_mdp" name="nouveau_mdp"
                           placeholder="Minimum 6 caractères" required>
                </div>

                <button type="submit" class="btn-principal btn-large">
                    Créer mon compte
                </button>
            </form>
        </div>

    </div>
</div>

<?php include 'includes/footer.php'; ?>
