<?php
// admin/index.php - Dashboard Administratè
require_once '../auth.php';

// Verifye si administratè a konekte
if (!estAdminConnecte()) {
    header('Location: login.php');
    exit;
}

$admin = $_SESSION['admin'];
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Administration</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <h1>🔐 Administration - Bibliothèque</h1>
        <nav>
            <ul>
                <li><a href="index.php" class="active">Dashboard</a></li>
                <li><a href="liste.php">📖 Livres</a></li>
                <li><a href="ajouter.php">➕ Ajouter</a></li>
                <li><a href="../user/">🌐 Voir le site</a></li>
                <li><a href="logout.php" style="color:#e74c3c;">🚪 Déconnexion</a></li>
            </ul>
        </nav>
    </header>

    <main>
        <section>
            <h2>👋 Bonjour, <?= htmlspecialchars($admin['prenom'] . ' ' . $admin['nom']) ?></h2>
            <p style="color:var(--gray-text); margin-bottom:1.5rem;">
                Bienvenue dans votre espace d'administration. Vous pouvez gérer tous les livres de la bibliothèque.
            </p>

            <div style="display:grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap:1.5rem; margin:2rem 0;">
                <div style="background:var(--light-bg); padding:1.5rem; border-radius:var(--border-radius); text-align:center; border-left:4px solid var(--secondary-color);">
                    <span style="font-size:2.5rem;">📖</span>
                    <h3 id="total-livres">...</h3>
                    <p style="color:var(--gray-text);">Total livres</p>
                </div>
                <div style="background:var(--light-bg); padding:1.5rem; border-radius:var(--border-radius); text-align:center; border-left:4px solid var(--success-color);">
                    <span style="font-size:2.5rem;">✅</span>
                    <h3 id="total-disponibles">...</h3>
                    <p style="color:var(--gray-text);">Disponibles</p>
                </div>
                <div style="background:var(--light-bg); padding:1.5rem; border-radius:var(--border-radius); text-align:center; border-left:4px solid var(--danger-color);">
                    <span style="font-size:2.5rem;">📤</span>
                    <h3 id="total-empruntes">...</h3>
                    <p style="color:var(--gray-text);">Empruntés</p>
                </div>
                <div style="background:var(--light-bg); padding:1.5rem; border-radius:var(--border-radius); text-align:center; border-left:4px solid var(--warning-color);">
                    <span style="font-size:2.5rem;">👤</span>
                    <h3 id="total-users">...</h3>
                    <p style="color:var(--gray-text);">Utilisateurs</p>
                </div>
            </div>

            <div style="display:grid; grid-template-columns:1fr 1fr; gap:1.5rem;">
                <div style="background:var(--light-bg); padding:1.5rem; border-radius:var(--border-radius);">
                    <h3>⚡ Actions rapides</h3>
                    <ul style="list-style:none; padding:0;">
                        <li style="margin:0.5rem 0;"><a href="ajouter.php" class="btn btn-success btn-small">➕ Ajouter un livre</a></li>
                        <li style="margin:0.5rem 0;"><a href="liste.php" class="btn btn-small">📖 Voir tous les livres</a></li>
                        <li style="margin:0.5rem 0;"><a href="../user/" class="btn btn-warning btn-small">🌐 Voir le site public</a></li>
                    </ul>
                </div>
                <div style="background:var(--light-bg); padding:1.5rem; border-radius:var(--border-radius);">
                    <h3>📋 Derniers livres ajoutés</h3>
                    <div id="derniers-livres">
                        <p style="color:var(--gray-text);">Chargement...</p>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <footer>
        <p>&copy; 2026 - Projet Final PROG-WEB-101 | Administration</p>
    </footer>

    <script>
        // Charger les statistiques
        async function chargerStats() {
            try {
                const response = await fetch('../api/api.php?action=liste');
                const data = await response.json();
                if (data.success) {
                    const livres = data.data;
                    document.getElementById('total-livres').textContent = livres.length;
                    const disponibles = livres.filter(l => l.disponibilite === 'Disponible').length;
                    document.getElementById('total-disponibles').textContent = disponibles;
                    document.getElementById('total-empruntes').textContent = livres.length - disponibles;

                    // Derniers livres
                    const derniers = livres.slice(-5).reverse();
                    const container = document.getElementById('derniers-livres');
                    if (derniers.length > 0) {
                        container.innerHTML = derniers.map(l => `
                            <div style="display:flex; justify-content:space-between; padding:0.3rem 0; border-bottom:1px solid #eee;">
                                <span><strong>${escapeHtml(l.titre)}</strong> - ${escapeHtml(l.auteur)}</span>
                                <span class="${l.disponibilite === 'Disponible' ? 'disponible' : 'emprunte'}" style="font-size:0.85rem;">${l.disponibilite}</span>
                            </div>
                        `).join('');
                    } else {
                        container.innerHTML = '<p style="color:var(--gray-text);">Aucun livre.</p>';
                    }
                }
            } catch (error) {
                console.error('Erreur:', error);
            }
        }

        // Charger le nombre d'utilisateurs
        async function chargerUsers() {
            try {
                const response = await fetch('../data/users.json');
                const users = await response.json();
                document.getElementById('total-users').textContent = users.length;
            } catch (error) {
                document.getElementById('total-users').textContent = '0';
            }
        }

        function escapeHtml(text) {
            if (!text) return '';
            const div = document.createElement('div');
            div.textContent = text;
            return div.innerHTML;
        }

        chargerStats();
        chargerUsers();
    </script>
</body>
</html>
<?php
// admin/index.php - Dashboard Administratè
require_once '../auth.php';

// Verifye si administratè a konekte
if (!estAdminConnecte()) {
    header('Location: login.php');
    exit;
}

$admin = $_SESSION['admin'];
?>
