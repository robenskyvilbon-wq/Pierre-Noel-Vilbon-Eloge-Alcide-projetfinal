<?php
// user/login.php - Koneksyon itilizatè
require_once '../auth.php';

$erreur = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $mot_passe = trim($_POST['mot_passe'] ?? '');

    if (empty($email) || empty($mot_passe)) {
        $erreur = 'Veuillez remplir tous les champs.';
    } else {
        $user = verifierUser($email, $mot_passe);
        if ($user) {
            connecterUser($user);
            header('Location: index.php');
            exit;
        } else {
            $erreur = 'Email ou mot de passe incorrect.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion - Bibliothèque</title>
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
                <li><a href="login.php" class="active">Connexion</a></li>
                <li><a href="register.php">Inscription</a></li>
            </ul>
        </nav>
    </header>

    <main>
        <section>
            <h2>🔐 Connexion</h2>

            <?php if ($erreur): ?>
                <div class="message error">❌ <?= htmlspecialchars($erreur) ?></div>
            <?php endif; ?>

            <div class="login-form">
                <form method="post">
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" id="email" name="email" required>
                    </div>
                    <div class="form-group">
                        <label for="mot_passe">Mot de passe</label>
                        <input type="password" id="mot_passe" name="mot_passe" required>
                    </div>
                    <button type="submit" class="btn">Se connecter</button>
                </form>
                <p style="margin-top:1rem;">
                    Pas encore de compte ? <a href="register.php">Inscrivez-vous</a>
                </p>
            </div>
        </section>
    </main>

    <footer>
        <p>&copy; 2026 - Projet Final PROG-WEB-101</p>
    </footer>
</body>
</html>
