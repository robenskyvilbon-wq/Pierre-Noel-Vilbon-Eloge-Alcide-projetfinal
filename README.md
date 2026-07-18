Bibliothèque en Ligne - PROG-WEB-101
Projet Final de Programmation Web - Système de gestion de bibliothèque en ligne avec PHP, JSON et JavaScript.

Description du Projet
Ce projet est une application web complète de gestion de bibliothèque permettant : - Consultation du catalogue de livres - Recherche en temps réel par titre, auteur, catégorie ou maison d’édition - Lecture de livres en PDF (réservé aux utilisateurs connectés) - Administration complète des livres (CRUD) pour les administrateurs - Authentification sécurisée pour utilisateurs et administrateurs

MEMBRES DU GROUPES
Pierre Djeson-Noel Edgard-Vilbon Robensky-Alcide Kesnel-Eloge Jampsley
 
Architecture du Projet
bibliotheque/
├── 📁 admin/                    Espace administration
│   ├── index.php                Dashboard admin (stats, actions rapides)
│   ├── liste.php                Liste complète des livres (tableau)
│   ├── ajouter.php             # Formulaire d'ajout de livre
│   ├── modifier.php            # Formulaire de modification de livre
│   ├── supprimer.php           # Confirmation de suppression
│   ├── login.php               # Connexion admin
│   ├── logout.php              # Déconnexion admin
│   ├── script.js               # JS pour le tableau admin
│   └── style.css               # Styles spécifiques admin
│
├── 📁 user/                     # Espace public/utilisateur
│   ├── index.php               # Page d'accueil (hero, stats)
│   ├── catalogue.php           # Catalogue avec cartes de livres
│   ├── recherche.php           # Recherche en temps réel
│   ├── details.php             # Détails d'un livre
│   ├── lecture.php             # Lecteur PDF intégré (iframe)
│   ├── login.php               # Connexion utilisateur
│   ├── register.php            # Inscription utilisateur
│   ├── logout.php              # Déconnexion utilisateur
│   ├── script.js               # JS pour catalogue et recherche
│   └── style.css               # Styles publics (responsive)
│
├── 📁 api/
│   └── api.php                 # API REST centrale (CRUD + recherche)
│
├── 📁 data/
│   ├── admins.json             # Données administrateurs (5 admins)
│   ├── users.json              # Données utilisateurs
│   └── livres.json             # Données livres (7 livres)
│
├── 📁 pdf/
│   └── (fichiers PDF des livres)
│
├── auth.php                     # Gestion authentification (sessions PHP)
└── README.md                    # Ce fichier
________________________________________
Technologies Utilisées
Technologie	Utilisation
PHP 8+	Backend, sessions, manipulation JSON
JavaScript (Vanilla)	Requêtes AJAX, recherche temps réel, DOM dynamique
JSON	Base de données légère (fichiers .json)
HTML5	Structure sémantique des pages
CSS3	Design responsive, variables CSS, Grid/Flexbox
Fetch API	Communication asynchrone avec l’API

Fonctionnalités
Espace Utilisateur
•	Consulter le catalogue de livres
•	Rechercher par titre, auteur, catégorie, édition (temps réel)
•	Voir les détails d’un livre
•	Lire les PDF en ligne (lecteur intégré)
•	S’inscrire / Se connecter
•	Voir les statistiques (total, disponibles, empruntés)
 Espace Administrateur
•	Dashboard avec statistiques en temps réel
•	Ajouter un nouveau livre (formulaire complet)
•	Modifier un livre existant
•	Supprimer un livre (avec confirmation)
•	Voir tous les livres en tableau
•	Gérer la disponibilité (Disponible/Emprunté)

Prérequis
•	PHP 7.4+ avec support sessions
•	Serveur web (Apache, Nginx, ou PHP built-in server)
•	Navigateur moderne (Chrome, Firefox, Edge, Safari)
•	Droits d’écriture sur les dossiers data/ et pdf/

Installation
1. Cloner le projet
git clone https://github.com/votre-org/bibliotheque.git
cd bibliotheque
2. Démarrer le serveur PHP
# Méthode 1 : Serveur PHP intégré
php -S localhost:8000

# Méthode 2 : Apache (placer dans htdocs ou www)
# Copier le dossier dans /var/www/html/ ou C:/xampp/htdocs/
3. Créer les dossiers nécessaires
mkdir -p data pdf
chmod 755 data pdf
4. Accéder à l’application
•	Site public : http://localhost:8000/user/
•	Administration : http://localhost:8000/admin/

Identifiants de Test
Administrateurs
Email	Mot de passe
alice@admin.com	admin123
bob@admin.com	admin123
charlie@admin.com	admin123
diana@admin.com	admin123
eric@admin.com	admin123
Utilisateurs (exemples)
Email	Mot de passe
jean@email.com	123456
marie@email.com	123456

📁 Structure des Données JSON
Livre (data/livres.json)
{
  "id": 1,
  "titre": "Le Petit Prince",
  "auteur": "Antoine de Saint-Exupéry",
  "annee": 1943,
  "maison_edition": "Gallimard",
  "categorie": "Littérature",
  "disponibilite": "Disponible",
  "pdf": "le_petit_prince.pdf",
  "description": "Un conte poétique...",
  "nb_pages": 96
}
Utilisateur (data/users.json)
{
  "id": 1,
  "prenom": "Jean",
  "nom": "Dupont",
  "email": "jean@email.com",
  "mot_passe": "123456",
  "date_inscription": "2026-07-10"
}

Sécurité
•	Sessions PHP pour l’authentification
•	htmlspecialchars() pour prévenir les attaques XSS
•	Validation des entrées côté serveur
•	Vérification des rôles (admin vs user) avant chaque action sensible
•	Redirection automatique si non autorisé

Points Forts du Projet
1.	Architecture MVC simplifiée : Séparation claire des responsabilités
2.	API REST interne : Communication fluide entre frontend et backend
3.	Design responsive : Adapté mobile, tablette et desktop
4.	Recherche temps réel : Expérience utilisateur fluide sans rechargement
5.	POO PHP : Classe Livre pour structurer les données
6.	Sans base de données : Fonctionne entièrement avec JSON (portable)

Auteurs
Projet Final PROG-WEB-101 - 2026
Équipe de 5 développeurs : - Développeur 1 : Architecture & Auth - Développeur 2 : API & Backend - Développeur 3 : Interface Utilisateur - Développeur 4 : Interface Admin - Développeur 5 : Tests & Documentation

Licence
Projet académique - Tous droits réservés © 2026
