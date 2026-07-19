<?php
// admin/supprimer.php - Supprime yon liv (administratè)
require_once '../auth.php';

if (!estAdminConnecte()) {
    header('Location: login.php');
    exit;
}

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($id > 0) {
    // Li done yo
    $data = file_get_contents('../data/livres.json');
    $livres = json_decode($data, true) ?? [];
    
    // Jwenn liv la
    $livreTrouve = null;
    foreach ($livres as $l) {
        if ($l['id'] === $id) {
            $livreTrouve = $l;
            break;
        }
    }
    
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['confirmer'])) {
        // Supprime liv la
        foreach ($livres as $key => $l) {
            if ($l['id'] === $id) {
                unset($livres[$key]);
                break;
            }
        }
        $livres = array_values($livres);
        file_put_contents('../data/livres.json', json_encode($livres, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
        header('Location: liste.php?supprime=ok');
        exit;
    }
} else {
    header('Location: liste.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Supprimer un livre - Admin</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <h1>🔐 Administration - Bibliothèque</h1>
        <nav>
            <ul>
                <li><a href="index.php">Dashboard</a></li>
                <li><a href="liste.php" class="active">📖 Livres</a></li>
                <li><a href="ajouter.php">➕ Ajouter</a></li>
                <li><a href="../user/">🌐 Voir le site</a></li>
                <li><a href="logout.php" style="color:#e74c3c;">🚪 Déconnexion</a></li>
            </ul>
        </nav>
    </header>

    <main>
        <section>
            <h2>🗑️ Supprimer un livre</h2>

            <?php if (!$livreTrouve): ?>
                <div class="message error">❌ Livre non trouvé.</div>
                <a href="liste.php" class="btn">← Retour à la liste</a>
            <?php else: ?>
                <div class="message error" style="font-size:1.1rem;">
                    ⚠️ Êtes-vous sûr de vouloir supprimer ce livre ?
                </div>

                <div style="background:var(--light-bg); padding:1.5rem; border-radius:var(--border-radius); margin:1rem 0;">
                    <h3><?= htmlspecialchars($livreTrouve['titre']) ?></h3>
                    <p><strong>Auteur :</strong> <?= htmlspecialchars($livreTrouve['auteur']) ?></p>
                    <p><strong>Année :</strong> <?= $livreTrouve['annee'] ?></p>
                    <p><strong>Maison d'édition :</strong> <?= htmlspecialchars($livreTrouve['maison_edition']) ?></p>
                    <p><strong>Catégorie :</strong> <?= htmlspecialchars($livreTrouve['categorie']) ?></p>
                    <p><strong>Disponibilité :</strong> <?= $livreTrouve['disponibilite'] ?></p>
                    <?php if (!empty($livreTrouve['pdf'])): ?>
                        <p><strong>PDF :</strong> <?= htmlspecialchars($livreTrouve['pdf']) ?></p>
                    <?php endif; ?>
                </div>

                <form method="post">
                    <input type="hidden" name="confirmer" value="1">
                    <button type="submit" class="btn btn-danger">🗑️ Confirmer la suppression</button>
                    <a href="liste.php" class="btn" style="background:#ccc;">Annuler</a>
                </form>
            <?php endif; ?>
        </section>
    </main>

    <footer>
        <p>&copy; 2026 - Projet Final PROG-WEB-101 | Administration</p>
    </footer>
</body>
</html>
