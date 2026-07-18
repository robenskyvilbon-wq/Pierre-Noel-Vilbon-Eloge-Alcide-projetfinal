<?php
// user/recherche.php - Rechèch liv pou itilizatè
require_once '../auth.php';
$userConnecte = estUserConnecte() || estAdminConnecte();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rechercher - Bibliothèque</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <h1>📚 Bibliothèque en ligne</h1>
        <nav>
            <ul>
                <li><a href="index.php">Accueil</a></li>
                <li><a href="catalogue.php">Catalogue</a></li>
                <li><a href="recherche.php" class="active">Rechercher</a></li>
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
            <h2>🔍 Rechercher un livre</h2>

            <div class="search-box">
                <div class="search-input-group">
                    <input type="text" style="width: 300px; font-size: 1.3rem; padding: 0.4rem; " id="search-input" placeholder="Titre, auteur, catégorie, maison d'édition...">
                    <button id="search-btn" class="btn">Rechercher</button>
                </div>
                <p class="search-hint">💡 La recherche se fait en temps réel.</p>
            </div>

            <div id="resultats-container">
                <div id="resultats" class="liste-livres">
                    <p class="message info">💡 Saisissez un terme pour commencer la recherche.</p>
                </div>
            </div>
        </section>
    </main>

    <footer>
        <p>&copy; 2026 - Projet Final PROG-WEB-101</p>
    </footer>

    <script>
        const userConnecte = <?= $userConnecte ? 'true' : 'false' ?>;

        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('search-input');
            const searchBtn = document.getElementById('search-btn');
            const resultatsDiv = document.getElementById('resultats');

            let tousLesLivres = [];

            async function chargerTousLesLivres() {
                try {
                    const response = await fetch('../api/api.php?action=liste');
                    const data = await response.json();
                    if (data.success) {
                        tousLesLivres = data.data;
                        afficherResultats(tousLesLivres);
                    }
                } catch (error) {
                    console.error('Erreur:', error);
                }
            }

            function afficherResultats(livres) {
                if (livres.length === 0) {
                    resultatsDiv.innerHTML = `<p class="message info">📭 Aucun livre trouvé.</p>`;
                    return;
                }

                resultatsDiv.innerHTML = livres.map(livre => `
                    <div class="livre-card">
                        <div class="livre-info">
                            <h4>${escapeHtml(livre.titre)}</h4>
                            <p><strong>Auteur :</strong> ${escapeHtml(livre.auteur)}</p>
                            <p><strong>Année :</strong> ${livre.annee} | <strong>Édition :</strong> ${escapeHtml(livre.maison_edition)}</p>
                            <p><strong>Catégorie :</strong> ${escapeHtml(livre.categorie)} | 
                               <span class="${livre.disponibilite === 'Disponible' ? 'disponible' : 'emprunte'}">${livre.disponibilite}</span></p>
                            ${livre.description ? `<p><strong>Description :</strong> ${escapeHtml(livre.description)}</p>` : ''}
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
            }

            function escapeHtml(text) {
                if (!text) return '';
                const div = document.createElement('div');
                div.textContent = text;
                return div.innerHTML;
            }

            function filtrerLivres(terme) {
                terme = terme.toLowerCase().trim();
                if (terme === '') {
                    afficherResultats(tousLesLivres);
                    return;
                }

                const resultats = tousLesLivres.filter(livre =>
                    livre.titre.toLowerCase().includes(terme) ||
                    livre.auteur.toLowerCase().includes(terme) ||
                    livre.categorie.toLowerCase().includes(terme) ||
                    livre.maison_edition.toLowerCase().includes(terme)
                );
                afficherResultats(resultats);
            }

            searchInput.addEventListener('input', function() {
                filtrerLivres(this.value);
            });

            searchBtn.addEventListener('click', function() {
                filtrerLivres(searchInput.value);
            });

            searchInput.addEventListener('keypress', function(e) {
                if (e.key === 'Enter') {
                    filtrerLivres(this.value);
                }
            });

            chargerTousLesLivres();
        });
    </script>
</body>
</html>
