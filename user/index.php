<?php
// user/index.php - Akèy itilizatè
require_once '../auth.php';
$userConnecte = estUserConnecte() || estAdminConnecte();
$user = getUtilisateurConnecte();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Accueil - Bibliothèque</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <h1>📚 Bibliothèque en ligne</h1>
        <nav>
            <ul>
                <li><a href="index.php" class="active">Accueil</a></li>
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
            <h2>🏠 Bienvenue à la bibliothèque</h2>

            <?php if ($userConnecte): ?>
                <div class="message success">
                    👋 Bonjour <?= htmlspecialchars($user['prenom'] ?? '') ?> <?= htmlspecialchars($user['nom'] ?? '') ?> !
                </div>
            <?php endif; ?>

            <div class="hero-grid">
                <div class="hero-card">
                    <span class="icon">📖</span>
                    <h4>Consultez</h4>
                    <p>Parcourez notre catalogue de livres</p>
                    <a href="catalogue.php" class="btn btn-small" style="margin-top:0.5rem;">Voir le catalogue</a>
                </div>
                <div class="hero-card">
                    <span class="icon">🔍</span>
                    <h4>Recherchez</h4>
                    <p>Trouvez un livre en un instant</p>
                    <a href="recherche.php" class="btn btn-small" style="margin-top:0.5rem;">Rechercher</a>
                </div>
                <div class="hero-card">
                    <span class="icon">📄</span>
                    <h4>Lisez</h4>
                    <p>Accédez aux livres en PDF</p>
                    <?php if ($userConnecte): ?>
                        <a href="catalogue.php" class="btn btn-small" style="margin-top:0.5rem;">Commencer la lecture</a>
                    <?php else: ?>
                        <a href="login.php" class="btn btn-small" style="margin-top:0.5rem;">Connectez-vous</a>
                    <?php endif; ?>
                </div>
                <div class="hero-card">
                    <span class="icon">👤</span>
                    <h4>Mon compte</h4>
                    <p>Gérez votre profil</p>
                    <?php if ($userConnecte): ?>
                        <a href="logout.php" class="btn btn-small" style="margin-top:0.5rem;">Se déconnecter</a>
                    <?php else: ?>
                        <a href="register.php" class="btn btn-small" style="margin-top:0.5rem;">Créer un compte</a>
                    <?php endif; ?>
                </div>
            </div>

            <div style="background:var(--light-bg); padding:1.5rem; border-radius:var(--border-radius); border-left:4px solid var(--secondary-color); margin-top:2rem;">
                <h3>📊 Statistiques</h3>
                <div class="stats-grid">
                    <div class="stat-item">
                        <div class="stat-number" id="total-livres">...</div>
                        <div class="stat-label">Total livres</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-number" id="total-disponibles">...</div>
                        <div class="stat-label">Disponibles</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-number" id="total-empruntes">...</div>
                        <div class="stat-label">Empruntés</div>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <footer>
        <p>&copy; 2026 - Projet Final PROG-WEB-101</p>
    </footer>

    <script>
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
                }
            } catch (error) {
                console.error('Erreur:', error);
            }
        }
        chargerStats();
    </script>
</body>
</html>
