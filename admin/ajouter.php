<?php
// admin/ajouter.php - Ajoute yon liv (administratè)
require_once '../auth.php';

if (!estAdminConnecte()) {
    header('Location: login.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter un livre - Admin</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <h1>🔐 Administration - Bibliothèque</h1>
        <nav>
            <ul>
                <li><a href="index.php">Dashboard</a></li>
                <li><a href="liste.php">📖 Livres</a></li>
                <li><a href="ajouter.php" class="active">➕ Ajouter</a></li>
                <li><a href="../user/">🌐 Voir le site</a></li>
                <li><a href="logout.php" style="color:#e74c3c;">🚪 Déconnexion</a></li>
            </ul>
        </nav>
    </header>

    <main>
        <section>
            <h2>➕ Ajouter un nouveau livre</h2>

            <form id="form-ajouter">
                <input type="hidden" name="action" value="ajouter">

                <div class="form-group">
                    <label for="titre">Titre du livre *</label>
                    <input type="text" id="titre" name="titre" required>
                </div>

                <div class="form-group">
                    <label for="auteur">Auteur *</label>
                    <input type="text" id="auteur" name="auteur" required>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="annee">Année d'édition *</label>
                        <input type="number" id="annee" name="annee" min="1000" max="2099" required>
                    </div>
                    <div class="form-group">
                        <label for="maison_edition">Maison d'édition *</label>
                        <input type="text" id="maison_edition" name="maison_edition" required>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="categorie">Catégorie</label>
                        <select id="categorie" name="categorie">
                            <option value="Littérature">Littérature</option>
                            <option value="Science-fiction">Science-fiction</option>
                            <option value="Roman">Roman</option>
                            <option value="Philosophie">Philosophie</option>
                            <option value="Fantastique">Fantastique</option>
                            <option value="Histoire">Histoire</option>
                            <option value="Sciences">Sciences</option>
                            <option value="Autre">Autre</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="disponibilite">Disponibilité</label>
                        <select id="disponibilite" name="disponibilite">
                            <option value="Disponible">Disponible</option>
                            <option value="Emprunté">Emprunté</option>
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label for="pdf">Nom du fichier PDF</label>
                    <input type="text" id="pdf" name="pdf" placeholder="ex: mon_livre.pdf">
                    <p style="font-size:0.85rem; color:var(--gray-text); margin-top:0.3rem;">
                        💡 Mettez le fichier PDF dans le dossier <strong>pdf/</strong> avec le même nom.
                    </p>
                </div>

                <div class="form-group">
                    <label for="description">Description</label>
                    <textarea id="description" name="description" rows="3" style="width:100%; padding:0.7rem 1rem; border:2px solid #ddd; border-radius:var(--border-radius); font-size:1rem;"></textarea>
                </div>

                <div class="form-group">
                    <label for="nb_pages">Nombre de pages</label>
                    <input type="number" id="nb_pages" name="nb_pages" min="0">
                </div>

                <button type="submit" class="btn btn-success">Ajouter le livre</button>
                <a href="liste.php" class="btn" style="background:#ccc;">Annuler</a>
            </form>

            <div id="message-ajout"></div>
        </section>
    </main>

    <footer>
        <p>&copy; 2026 - Projet Final PROG-WEB-101 | Administration</p>
    </footer>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('form-ajouter');
            const messageDiv = document.getElementById('message-ajout');

            form.addEventListener('submit', async function(e) {
                e.preventDefault();

                const formData = new FormData(form);

                try {
                    const response = await fetch('../api/api.php?action=ajouter', {
                        method: 'POST',
                        body: formData
                    });

                    const result = await response.json();

                    if (result.success) {
                        messageDiv.innerHTML = `<div class="message success">✅ ${result.message}</div>`;
                        form.reset();
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
