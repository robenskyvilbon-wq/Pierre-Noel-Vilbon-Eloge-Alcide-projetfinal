<?php
// user/lecture.php - Lire un livre PDF
require_once '../auth.php';

// Vérifier si l'utilisateur est connecté
if (!estUserConnecte() && !estAdminConnecte()) {
    header('Location: login.php');
    exit;
}

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$livre = null;
$chemin_pdf = '';

if ($id > 0) {
    $data = file_get_contents('../data/livres.json');
    $livres = json_decode($data, true) ?? [];
    foreach ($livres as $l) {
        if ($l['id'] === $id) {
            $livre = $l;
            break;
        }
    }
}

if ($livre && !empty($livre['pdf'])) {
    $chemin_pdf = '../pdf/' . $livre['pdf'];
    if (!file_exists($chemin_pdf)) {
        $livre = null;
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lecture - <?= htmlspecialchars($livre['titre'] ?? 'Livre') ?></title>
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
                <li><a href="logout.php">🚪 Déconnexion</a></li>
            </ul>
        </nav>
    </header>

    <main>
        <section style="max-width:100%;">
            <?php if (!$livre): ?>
                <div class="message error">
                    ❌ Livre non trouvé ou PDF indisponible.
                    <a href="catalogue.php">Retour au catalogue</a>
                </div>
            <?php else: ?>
                <div style="display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; margin-bottom:1rem;">
                    <h2>📖 <?= htmlspecialchars($livre['titre']) ?></h2>
                    <a href="catalogue.php" class="btn btn-small">← Retour au catalogue</a>
                </div>
                <div style="background:var(--light-bg); padding:1rem; border-radius:var(--border-radius); margin-bottom:1rem;">
                    <p><strong>Auteur :</strong> <?= htmlspecialchars($livre['auteur']) ?></p>
                    <p><strong>Année :</strong> <?= $livre['annee'] ?> | <strong>Édition :</strong> <?= htmlspecialchars($livre['maison_edition']) ?></p>
                    <p><strong>Pages :</strong> <?= $livre['nb_pages'] ?? 'Non spécifié' ?></p>
                </div>
                <div style="width:100%; height:80vh; border:1px solid #ddd; border-radius:var(--border-radius); overflow:hidden;">
                    <iframe src="../pdf/<?= urlencode($livre['pdf']) ?>" 
                            style="width:100%; height:100%; border:none;"
                            frameborder="0">
                        Votre navigateur ne supporte pas l'affichage PDF.
                    </iframe>
                </div>
                <p style="margin-top:0.5rem; color:var(--gray-text); font-size:0.9rem;">
                    💡 Vous pouvez télécharger le PDF ou le lire directement dans le navigateur.
                </p>
            <?php endif; ?>
        </section>
    </main>

    <footer>
        <p>&copy; 2026 - Projet Final PROG-WEB-101</p>
    </footer>
</body>
</html>
