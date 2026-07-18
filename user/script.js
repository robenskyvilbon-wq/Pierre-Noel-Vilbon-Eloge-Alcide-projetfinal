// user/script.js - JavaScript pou entèfas itilizatè

document.addEventListener('DOMContentLoaded', function() {
    // ----- CHARGEMENT CATALOGUE -----
    const catalogueContainer = document.getElementById('catalogue-livres');
    if (catalogueContainer) {
        chargerCatalogue();
    }

    // ----- RECHERCHE EN TEMPS RÉEL -----
    const searchInput = document.getElementById('search-input');
    const searchBtn = document.getElementById('search-btn');
    const resultatsDiv = document.getElementById('resultats');

    if (searchInput && resultatsDiv) {
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
                console.error('Erreur chargement livres:', error);
                resultatsDiv.innerHTML = `<p class="message error">❌ Erreur de chargement: ${error.message}</p>`;
            }
        }

        function afficherResultats(livres) {
            if (!resultatsDiv) return;
            
            if (livres.length === 0) {
                resultatsDiv.innerHTML = `<p class="message info">📭 Aucun livre trouvé.</p>`;
                return;
            }

            const userConnecte = typeof userConnecte !== 'undefined' ? userConnecte : false;

            resultatsDiv.innerHTML = livres.map(livre => `
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
                livre.maison_edition.toLowerCase().includes(terme) ||
                (livre.description && livre.description.toLowerCase().includes(terme))
            );
            afficherResultats(resultats);
        }

        // Événements recherche
        searchInput.addEventListener('input', function() {
            filtrerLivres(this.value);
        });

        if (searchBtn) {
            searchBtn.addEventListener('click', function() {
                filtrerLivres(searchInput.value);
            });
        }

        searchInput.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                filtrerLivres(this.value);
            }
        });

        // Charger tous les livres au départ
        chargerTousLesLivres();
    }
});

// ----- FONCTION POUR CHARGER LE CATALOGUE -----
async function chargerCatalogue() {
    const container = document.getElementById('catalogue-livres');
    if (!container) return;

    try {
        const response = await fetch('../api/api.php?action=liste');
        const data = await response.json();

        if (data.success && data.data.length > 0) {
            const userConnecte = typeof userConnecte !== 'undefined' ? userConnecte : false;
            
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
            container.innerHTML = `<p class="message info">📭 Aucun livre disponible dans la bibliothèque.</p>`;
        }
    } catch (error) {
        container.innerHTML = `<p class="message error">❌ Erreur de chargement : ${error.message}</p>`;
        console.error('Erreur:', error);
    }
}

// ----- FONCTION POUR SUPPRIMER UN LIVRE (ADMIN SEULEMENT) -----
async function supprimerLivre(id) {
    if (!confirm('Êtes-vous sûr de vouloir supprimer ce livre ?')) return;

    try {
        const formData = new FormData();
        formData.append('action', 'supprimer');
        formData.append('id', id);

        const response = await fetch('../api/api.php?action=supprimer', {
            method: 'POST',
            body: formData
        });

        const result = await response.json();

        if (result.success) {
            alert('✅ Livre supprimé avec succès !');
            // Recharger la page ou la liste
            location.reload();
        } else {
            alert('❌ Erreur : ' + result.message);
        }
    } catch (error) {
        alert('❌ Erreur de connexion : ' + error.message);
        console.error('Erreur:', error);
    }
}

// ----- FONCTION UTILITAIRE POUR ÉCHAPPER LE HTML -----
function escapeHtml(text) {
    if (!text) return '';
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}

// ----- FONCTION POUR CHARGER LES STATISTIQUES (PAGE ACCUEIL) -----
async function chargerStats() {
    try {
        const response = await fetch('../api/api.php?action=liste');
        const data = await response.json();
        if (data.success) {
            const livres = data.data;
            
            const totalLivres = document.getElementById('total-livres');
            const totalDisponibles = document.getElementById('total-disponibles');
            const totalEmpruntes = document.getElementById('total-empruntes');
            
            if (totalLivres) totalLivres.textContent = livres.length;
            
            if (totalDisponibles || totalEmpruntes) {
                const disponibles = livres.filter(l => l.disponibilite === 'Disponible').length;
                if (totalDisponibles) totalDisponibles.textContent = disponibles;
                if (totalEmpruntes) totalEmpruntes.textContent = livres.length - disponibles;
            }
        }
    } catch (error) {
        console.error('Erreur chargement statistiques:', error);
        const elements = document.querySelectorAll('.stat-number');
        elements.forEach(el => el.textContent = 'Erreur');
    }
}

// ----- FONCTION POUR CHARGER LES DERNIERS LIVRES (PAGE ACCUEIL) -----
async function chargerDerniersLivres() {
    const container = document.getElementById('derniers-livres');
    if (!container) return;

    try {
        const response = await fetch('../api/api.php?action=liste');
        const data = await response.json();
        if (data.success && data.data.length > 0) {
            const derniers = data.data.slice(-5).reverse();
            container.innerHTML = derniers.map(l => `
                <div style="display:flex; justify-content:space-between; padding:0.5rem 0; border-bottom:1px solid #eee;">
                    <div>
                        <strong>${escapeHtml(l.titre)}</strong>
                        <span style="color:var(--gray-text); font-size:0.85rem;"> - ${escapeHtml(l.auteur)}</span>
                    </div>
                    <span class="${l.disponibilite === 'Disponible' ? 'disponible' : 'emprunte'}" style="font-size:0.85rem;">
                        ${l.disponibilite}
                    </span>
                </div>
            `).join('');
        } else {
            container.innerHTML = '<p style="color:var(--gray-text);">Aucun livre disponible.</p>';
        }
    } catch (error) {
        container.innerHTML = '<p style="color:var(--gray-text);">Erreur de chargement.</p>';
    }
}

// ----- INITIALISATION DES FONCTIONS AU CHARGEMENT -----
document.addEventListener('DOMContentLoaded', function() {
    // Statistiques sur la page d'accueil
    if (document.getElementById('total-livres')) {
        chargerStats();
    }
    
    // Derniers livres sur la page d'accueil
    if (document.getElementById('derniers-livres')) {
        chargerDerniersLivres();
    }
});
