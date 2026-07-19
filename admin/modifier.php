<?php
// admin/modifier.php - Modifye yon liv (administratè)
require_once '../auth.php';

if (!estAdminConnecte()) {
    header('Location: login.php');
    exit;
}

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
    <title>Modifier un livre - Admin</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <h1>🔐 Administration - Bibliothèque</h1>
        <nav>
            <ul>
                <li><a href="index.php">Dashboard</a></li>
                <li><a href="liste.php">📖 Livres</a></li>
                <li><a href="ajouter.php">➕ Ajouter</a></li>
                <li><a href="../user/">🌐 Voir le site</a></li>
                <li><a href="logout.php" style="color:#e74c3c;">🚪 Déconnexion</a></li>
            </ul>
        </nav>
    </header>

    <main>
        <section>
            <h2>✏️ Modifier un livre</h2>

            <?php if (!$livre): ?>
                <div class="message error">❌ Livre non trouvé.</div>
                <a href="liste.php" class="btn">← Retour à la liste</a>
            <?php else: ?>
                <form id="form-modifier">
                    <input type="hidden" name="action" value="modifier">
                    <input type="hidden" name="id" value="<?= $livre['id'] ?>">

                    <div class="form-group">
                        <label for="titre">Titre du livre *</label>
                        <input type="text" id="titre" name="titre" value="<?= htmlspecialchars($livre['titre']) ?>" required>
                    </div>

                    <div class="form-group">
                        <label for="auteur">Auteur *</label>
                        <input type="text" id="auteur" name="auteur" value="<?= htmlspecialchars($livre['auteur']) ?>" required>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="annee">Année d'édition *</label>
                            <input type="number" id="annee" name="annee" min="1000" max="2099" value="<?= $livre['annee'] ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="maison_edition">Maison d'édition *</label>
                            <input type="text" id="maison_edition" name="maison_edition" value="<?= htmlspecialchars($livre['maison_edition']) ?>" required>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="categorie">Catégorie</label>
                            <select id="categorie" name="categorie">
                                <option value="Littérature" <?= $livre['categorie'] === 'Littérature' ? 'selected' : '' ?>>Littérature</option>
                                <option value="Science-fiction" <?= $livre['categorie'] === 'Science-fiction' ? 'selected' : '' ?>>Science-fiction</option>
                                <option value="Roman" <?= $livre['categorie'] === 'Roman' ? 'selected' : '' ?>>Roman</option>
                                <option value="Philosophie" <?= $livre['categorie'] === 'Philosophie' ? 'selected' : '' ?>>Philosophie</option>
                                <option value="Fantastique" <?= $livre['categorie'] === 'Fantastique' ? 'selected' : '' ?>>Fantastique</option>
                                <option value="Histoire" <?= $livre['categorie'] === 'Histoire' ? 'selected' : '' ?>>Histoire</option>
                                <option value="Sciences" <?= $livre['categorie'] === 'Sciences' ? 'selected' : '' ?>>Sciences</option>
                                <option value="Autre" <?= $livre['categorie'] === 'Autre' ? 'selected' : '' ?>>Autre</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="disponibilite">Disponibilité</label>
                            <select id="disponibilite" name="disponibilite">
                                <option value="Disponible" <?= $livre['disponibilite'] === 'Disponible' ? 'selected' : '' ?>>Disponible</option>
                                <option value="Emprunté" <?= $livre['disponibilite'] === 'Emprunté' ? 'selected' : '' ?>>Emprunté</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="pdf">Nom du fichier PDF</label>
                        <input type="text" id="pdf" name="pdf" value="<?= htmlspecialchars($livre['pdf'] ?? '') ?>" placeholder="ex: mon_livre.pdf">
                        <p style="font-size:0.85rem; color:var(--gray-text); margin-top:0.3rem;">
                            💡 Fichier PDF actuel : <strong><?= htmlspecialchars($livre['pdf'] ?? 'Aucun') ?></strong>
                        </p>
                    </div>

                    <div class="form-group">
                        <label for="description">Description</label>
                        <textarea id="description" name="description" rows="3" style="width:100%; padding:0.7rem 1rem; border:2px solid #ddd; border-radius:var(--border-radius); font-size:1rem;"><?= htmlspecialchars($livre['description'] ?? '') ?></textarea>
                    </div>

                    <div class="form-group">
                        <label for="nb_pages">Nombre de pages</label>
                        <input type="number" id="nb_pages" name="nb_pages" min="0" value="<?= $livre['nb_pages'] ?? 0 ?>">
                    </div>

                    <button type="submit" class="btn btn-warning">Modifier le livre</button>
                    <a href="liste.php" class="btn" style="background:#ccc;">Annuler</a>
                </form>

                <div id="message-modifier"></div>
            <?php endif; ?>
        </section>
    </main>

    <footer>
        <p>&copy; 2026 - Projet Final PROG-WEB-101 | Administration</p>
    </footer>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('form-modifier');
            const messageDiv = document.getElementById('message-modifier');

            if (!form) return;

            form.addEventListener('submit', async function(e) {
                e.preventDefault();

                const formData = new FormData(form);

                try {
                    const response = await fetch('../api/api.php?action=modifier', {
                        method: 'POST',
                        body: formData
                    });

                    const result = await response.json();

                    if (result.success) {
                        messageDiv.innerHTML = `<div class="message success">✅ ${result.message}</div>`;
                        setTimeout(() => {
                            window.location.href = 'liste.php';
                        }, 1500);
                    } else {
                        messageDiv.innerHTML = `<div class="message error">❌ ${result.message}</div>`;
                    }
                } catch (error) {
                    messageDiv.innerHTML = `<div class="message error">❌ Erreur de connexion : ${error.message}</div>`;
                }
            });
        });
    </script>
</body>
</html>
