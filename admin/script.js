// admin/script.js - JavaScript pou administratè

document.addEventListener('DOMContentLoaded', function() {
    const listeContainer = document.getElementById('liste-admin');
    if (listeContainer) {
        chargerListeAdmin();
    }
});

async function chargerListeAdmin() {
    const container = document.getElementById('liste-admin');
    if (!container) return;

    try {
        const response = await fetch('../api/api.php?action=liste');
        const data = await response.json();

        if (data.success) {
            if (data.data.length === 0) {
                container.innerHTML = `<p class="message info">📭 Aucun livre dans la bibliothèque.</p>`;
                return;
            }

            container.innerHTML = `
                <div class="table-wrapper">
                    <table>
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Titre</th>
                                <th>Auteur</th>
                                <th>Année</th>
                                <th>Édition</th>
                                <th>Catégorie</th>
                                <th>Disponibilité</th>
                                <th>PDF</th>
                                <th style="text-align:center;">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            ${data.data.map(livre => `
                                <tr>
                                    <td>${livre.id}</td>
                                    <td><strong>${escapeHtml(livre.titre)}</strong></td>
                                    <td>${escapeHtml(livre.auteur)}</td>
                                    <td>${livre.annee}</td>
                                    <td>${escapeHtml(livre.maison_edition)}</td>
                                    <td>${escapeHtml(livre.categorie)}</td>
                                    <td>
                                        <span class="${livre.disponibilite === 'Disponible' ? 'disponible' : 'emprunte'}">${livre.disponibilite}</span>
                                    </td>
                                    <td>
                                        ${livre.pdf ? 
                                            `<span style="color:var(--success-color);">✅ ${escapeHtml(livre.pdf)}</span>` : 
                                            `<span style="color:var(--gray-text);">❌ Aucun</span>`
                                        }
                                    </td>
                                    <td style="text-align:center; white-space:nowrap;">
                                        <a href="modifier.php?id=${livre.id}" class="btn btn-warning btn-small" style="margin:0 0.2rem;">✏️</a>
                                        <a href="supprimer.php?id=${livre.id}" class="btn btn-danger btn-small" style="margin:0 0.2rem;">🗑️</a>
                                    </td>
                                </tr>
                            `).join('')}
                        </tbody>
                    </table>
                </div>
            `;
        }
    } catch (error) {
        container.innerHTML = `<p class="message error">❌ Erreur de chargement : ${error.message}</p>`;
        console.error('Erreur:', error);
    }
}

function escapeHtml(text) {
    if (!text) return '';
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}
