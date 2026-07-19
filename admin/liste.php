<?php
// admin/liste.php - Lis liv pou administratè
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
    <title>Gestion des livres - Admin</title>
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
            <div style="display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; margin-bottom:1rem;">
                <h2>📖 Gestion des livres</h2>
                <a href="ajouter.php" class="btn btn-success">➕ Ajouter un livre</a>
            </div>

            <div id="liste-admin">
                <p>Chargement des livres...</p>
            </div>
        </section>
    </main>

    <footer>
        <p>&copy; 2026 - Projet Final PROG-WEB-101 | Administration</p>
    </footer>

    <script src="script.js"></script>
</body>
</html>
