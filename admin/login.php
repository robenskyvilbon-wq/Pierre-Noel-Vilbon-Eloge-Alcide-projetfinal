<?php
// admin/login.php - Koneksyon administratè
require_once '../auth.php';

// Si deja konekte, redirije
if (estAdminConnecte()) {
    header('Location: index.php');
    exit;
}

$erreur = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $mot_passe = trim($_POST['mot_passe'] ?? '');

    if (empty($email) || empty($mot_passe)) {
        $erreur = 'Veuillez remplir tous les champs.';
    } else {
        // Teste si fichier admins.json egziste
        if (!file_exists('../data/admins.json')) {
            $erreur = 'Fichier admins.json pa egziste. Kontakte administratè sistem lan.';
        } else {
            $admin = verifierAdmin($email, $mot_passe);
            if ($admin) {
                connecterAdmin($admin);
                header('Location: index.php');
                exit;
            } else {
                $erreur = 'Email ou mot de passe incorrect.';
                
                // Debug: Afiche kontni admins.json pou teste
                // $admins = lireAdmins();
                // $erreur .= ' Admins: ' . print_r($admins, true);
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion Admin - Bibliothèque</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <h1>🔐 Administration</h1>
        <nav>
            <ul>
                <li><a href="login.php" class="active">Connexion</a></li>
                <li><a href="../user/">🌐 Accueil public</a></li>
            </ul>
        </nav>
    </header>

    <main>
        <section>
            <h2>🔐 Connexion administrateur</h2>

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
                <p class="hint">
                    👤 Administrateurs par défaut :<br>
                    <strong>alice@admin.com</strong> / admin123<br>
                    <strong>bob@admin.com</strong> / admin123<br>
                    <strong>charlie@admin.com</strong> / admin123<br>
                    <strong>diana@admin.com</strong> / admin123<br>
                    <strong>eric@admin.com</strong> / admin123
                </p>
                <p style="margin-top:1rem;">
                    <a href="../user/">🌐 Aller à l'accueil public</a>
                </p>
            </div>
        </section>
    </main>

    <footer>
        <p>&copy; 2026 - Projet Final PROG-WEB-101 | Administration</p>
    </footer>
</body>
</html>
