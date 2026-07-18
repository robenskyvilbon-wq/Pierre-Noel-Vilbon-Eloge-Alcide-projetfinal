<?php
// user/details.php - Detay yon liv
require_once '../auth.php';
$userConnecte = estUserConnecte() || estAdminConnecte();

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$livre = null;

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
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Détails du livre</title>
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
                <?php if ($userConnecte): ?>
                    <li><a href="logout.php">🚪 Déconnexion</a></li>
                <?php else: ?>
                    <li><a href="login.php">Connexion</a></li>
                    <li><a href="register.php">Inscription</a></li>
                <?php endif; ?>
            </ul>
        </nav>
    </header>

    <main>
        <section>
            <?php if (!$livre): ?>
                <div class="message error">❌ Livre non trouvé.</div>
                <a href="catalogue.php" class="btn">← Retour au catalogue</a>
            <?php else: ?>
                <div style="display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; margin-bottom:1rem;">
                    <h2>📖 <?= htmlspecialchars($livre['titre']) ?></h2>
                    <a href="catalogue.php" class="btn btn-small">← Retour au catalogue</a>
                </div>

                <div style="display:grid; grid-template-columns:1fr 1fr; gap:2rem;">
                    <div>
                        <div style="background:var(--light-bg); padding:1.5rem; border-radius:var(--border-radius);">
                            <h3>Informations</h3>
                            <p><strong>Auteur :</strong> <?= htmlspecialchars($livre['auteur']) ?></p>
                            <p><strong>Année :</strong> <?= $livre['annee'] ?></p>
                            <p><strong>Maison d'édition :</strong> <?= htmlspecialchars($livre['maison_edition']) ?></p>
                            <p><strong>Catégorie :</strong> <?= htmlspecialchars($livre['categorie']) ?></p>
                            <p><strong>Disponibilité :</strong> 
                                <span class="<?= $livre['disponibilite'] === 'Disponible' ? 'disponible' : 'emprunte' ?>">
                                    <?= $livre['disponibilite'] ?>
                                </span>
                            </p>
                            <?php if ($livre['nb_pages']): ?>
                                <p><strong>Nombre de pages :</strong> <?= $livre['nb_pages'] ?></p>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div>
                        <div style="background:var(--light-bg); padding:1.5rem; border-radius:var(--border-radius);">
                            <h3>Description</h3>
                            <p><?= htmlspecialchars($livre['description'] ?? 'Aucune description disponible.') ?></p>
                        </div>

                        <div style="margin-top:1rem; display:flex; gap:1rem; flex-wrap:wrap;">
                            <?php if (!empty($livre['pdf']) && $userConnecte): ?>
                                <a href="lecture.php?id=<?= $livre['id'] ?>" class="btn btn-success">📄 Lire le livre</a>
                            <?php elseif (!empty($livre['pdf']) && !$userConnecte): ?>
                                <a href="login.php" class="btn btn-warning">🔒 Connectez-vous pour lire</a>
                            <?php else: ?>
                                <span class="btn" style="background:#ccc;cursor:not-allowed;">📄 PDF non disponible</span>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </section>
    </main>

    <footer>
        <p>&copy; 2026 - Projet Final PROG-WEB-101</p>
    </footer>
</body>
</html>
