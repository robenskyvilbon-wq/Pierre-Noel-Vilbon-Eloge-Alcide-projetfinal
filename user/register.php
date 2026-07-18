<?php
// user/register.php - Enskripsyon itilizatè
require_once '../auth.php';

$erreur = '';
$succes = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $prenom = trim($_POST['prenom'] ?? '');
    $nom = trim($_POST['nom'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $mot_passe = trim($_POST['mot_passe'] ?? '');
    $confirme_mot_passe = trim($_POST['confirme_mot_passe'] ?? '');

    if (empty($prenom) || empty($nom) || empty($email) || empty($mot_passe)) {
        $erreur = 'Tous les champs sont obligatoires.';
    } elseif ($mot_passe !== $confirme_mot_passe) {
        $erreur = 'Les mots de passe ne correspondent pas.';
    } elseif (userExiste($email)) {
        $erreur = 'Cet email est déjà utilisé.';
    } else {
        $user = ajouterUser($prenom, $nom, $email, $mot_passe);
        connecterUser($user);
        header('Location: index.php');
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription - Bibliothèque</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <h1>📚 Bibliothèque en ligne</h1>
        <nav>
            <ul>
                <li><a href="index.php">Accueil</a></li>
                <li><a href="catalogue.php">Catalogue</a></li>
                <li><a href="recherche.php">Rechercher</a></li>
                <li><a href="login.php">Connexion</a></li>
                <li><a href="register.php" class="active">Inscription</a></li>
            </ul>
        </nav>
    </header>

    <main>
        <section>
            <h2>📝 Créer un compte</h2>

            <?php if ($erreur): ?>
                <div class="message error">❌ <?= htmlspecialchars($erreur) ?></div>
            <?php endif; ?>

            <div class="register-form">
                <form method="post">
                    <div class="form-group">
                        <label for="prenom">Prénom *</label>
                        <input type="text" id="prenom" name="prenom" required>
                    </div>
                    <div class="form-group">
                        <label for="nom">Nom *</label>
                        <input type="text" id="nom" name="nom" required>
                    </div>
                    <div class="form-group">
                        <label for="email">Email *</label>
                        <input type="email" id="email" name="email" required>
                    </div>
                    <div class="form-group">
                        <label for="mot_passe">Mot de passe *</label>
                        <input type="password" id="mot_passe" name="mot_passe" required>
                    </div>
                    <div class="form-group">
                        <label for="confirme_mot_passe">Confirmer le mot de passe *</label>
                        <input type="password" id="confirme_mot_passe" name="confirme_mot_passe" required>
                    </div>
                    <button type="submit" class="btn btn-success">Créer mon compte</button>
                </form>
                <p style="margin-top:1rem;">
                    Vous avez déjà un compte ? <a href="login.php">Connectez-vous</a>
                </p>
            </div>
        </section>
    </main>

    <footer>
        <p>&copy; 2026 - Projet Final PROG-WEB-101</p>
    </footer>
</body>
</html>
