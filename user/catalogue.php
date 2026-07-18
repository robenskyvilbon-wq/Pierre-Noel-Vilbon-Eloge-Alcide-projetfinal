<?php
// user/catalogue.php - Katalòg liv
require_once '../auth.php';
$userConnecte = estUserConnecte() || estAdminConnecte();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Catalogue - Bibliothèque</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <h1>📚 Bibliothèque en ligne</h1>
        <nav>
            <ul>
                <li><a href="index.php">Accueil</a></li>
                <li><a href="catalogue.php" class="active">Catalogue</a></li>
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
            <h2>📖 Catalogue des livres</h2>
            <p style="margin-bottom:1rem; color:var(--gray-text);">
                <?php if ($userConnecte): ?>
                    ✅ Vous êtes connecté. Vous pouvez lire les livres disponibles.
                <?php else: ?>
                    🔒 <a href="login.php">Connectez-vous</a> pour lire les livres en PDF.
                <?php endif; ?>
            </p>
            <div id="catalogue-livres" class="liste-livres">
                <p>Chargement des livres...</p>
            </div>
        </section>
    </main>

    <footer>
        <p>&copy; 2026 - Projet Final PROG-WEB-101</p>
    </footer>

    <script>
        const userConnecte = <?= $userConnecte ? 'true' : 'false' ?>;

        async function chargerCatalogue() {
            const container = document.getElementById('catalogue-livres');
            try {
                const response = await fetch('../api/api.php?action=liste');
                const data = await response.json();

                if (data.success && data.data.length > 0) {
                    container.innerHTML = data.data.map(livre => `
                        <div class="livre-card">
                            <div class="livre-info">
                                <h4>${escapeHtml(livre.titre)}</h4>
                                <p><strong>Auteur :</strong> ${escapeHtml(livre.auteur)}</p>
                                <p><strong>Année :</strong> ${livre.annee} | <strong>Édition :</strong> ${escapeHtml(livre.maison_edition)}</p>
                                <p><strong>Catégorie :</strong> ${escapeHtml(livre.categorie)} | 
                                   <span class="${livre.disponibilite === 'Disponible' ? 'disponible' : 'emprunte'}">${livre.disponibilite}</span></p>
                                ${livre.description ? `<p><strong>Description :</strong> ${escapeHtml(livre.description)}</p>` : ''}
                                ${livre.nb_pages ? `<p><strong>Pages :</strong> ${livre.nb_pages}</p>` : ''}
                            </div>
                            <div class="livre-actions">
                                <a href="details.php?id=${livre.id}" class="btn btn-small">📖 Détails</a>
                                ${livre.pdf && userConnecte ? 
                                    `<a href="lecture.php?id=${livre.id}" class="btn btn-success btn-small">📄 Lire</a>` : 
                                    (livre.pdf && !userConnecte ? 
                                        `<a href="login.php" class="btn btn-warning btn-small">🔒 Connectez-vous</a>` : 
                                        `<span class="btn btn-small" style="background:#ccc;cursor:not-allowed;">📄 Pas de PDF</span>`
                                    )
                                }
                            </div>
                        </div>
                    `).join('');
                } else {
                    container.innerHTML = `<p class="message info">📭 Aucun livre disponible.</p>`;
                }
            } catch (error) {
                container.innerHTML = `<p class="message error">❌ Erreur : ${error.message}</p>`;
            }
        }

        function escapeHtml(text) {
            if (!text) return '';
            const div = document.createElement('div');
            div.textContent = text;
            return div.innerHTML;
        }

        chargerCatalogue();
    </script>
</body>
</html>
