<?php
// auth.php - Gestion otantifikasyon admin + itilizatè

// Kòmanse session an premye
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

define('ADMINS_FILE', 'data/admins.json');
define('USERS_FILE', 'data/users.json');

// ---- FONKSYON POU ADMIN ----
function lireAdmins() {
    if (!file_exists(ADMINS_FILE)) {
        // Kreye fichye admins.json si li pa egziste
        $admins_defo = [
            ['id' => 1, 'prenom' => 'Alice', 'nom' => 'Martin', 'email' => 'alice@admin.com', 'mot_passe' => 'admin123'],
            ['id' => 2, 'prenom' => 'Bob', 'nom' => 'Durand', 'email' => 'bob@admin.com', 'mot_passe' => 'admin123'],
            ['id' => 3, 'prenom' => 'Charlie', 'nom' => 'Lefèvre', 'email' => 'charlie@admin.com', 'mot_passe' => 'admin123'],
            ['id' => 4, 'prenom' => 'Diana', 'nom' => 'Moreau', 'email' => 'diana@admin.com', 'mot_passe' => 'admin123'],
            ['id' => 5, 'prenom' => 'Éric', 'nom' => 'Petit', 'email' => 'eric@admin.com', 'mot_passe' => 'admin123']
        ];
        file_put_contents(ADMINS_FILE, json_encode($admins_defo, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
        return $admins_defo;
    }
    $contenu = file_get_contents(ADMINS_FILE);
    $admins = json_decode($contenu, true);
    return is_array($admins) ? $admins : [];
}

function verifierAdmin($email, $mot_passe) {
    $admins = lireAdmins();
    
    // Debug: Afiche admin yo pou teste
    // error_log(print_r($admins, true));
    
    foreach ($admins as $admin) {
        // Verifye si email ak mot_passe matche
        if (isset($admin['email']) && isset($admin['mot_passe'])) {
            if ($admin['email'] === $email && $admin['mot_passe'] === $mot_passe) {
                return $admin;
            }
        }
    }
    return null;
}

function estAdminConnecte() {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    return isset($_SESSION['admin']) && isset($_SESSION['role']) && $_SESSION['role'] === 'admin';
}

function connecterAdmin($admin) {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    $_SESSION['admin'] = $admin;
    $_SESSION['role'] = 'admin';
    $_SESSION['admin_connecte'] = true;
}

function deconnecterAdmin() {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    unset($_SESSION['admin']);
    unset($_SESSION['role']);
    unset($_SESSION['admin_connecte']);
    session_destroy();
}

// ---- FONKSYON POU ITILIZATÈ ----
function lireUsers() {
    if (!file_exists(USERS_FILE)) {
        return [];
    }
    $contenu = file_get_contents(USERS_FILE);
    return json_decode($contenu, true) ?? [];
}

function ecrireUsers($users) {
    file_put_contents(USERS_FILE, json_encode($users, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
}

function verifierUser($email, $mot_passe) {
    $users = lireUsers();
    foreach ($users as $user) {
        if (isset($user['email']) && isset($user['mot_passe'])) {
            if ($user['email'] === $email && $user['mot_passe'] === $mot_passe) {
                return $user;
            }
        }
    }
    return null;
}

function userExiste($email) {
    $users = lireUsers();
    foreach ($users as $user) {
        if ($user['email'] === $email) {
            return true;
        }
    }
    return false;
}

function ajouterUser($prenom, $nom, $email, $mot_passe) {
    $users = lireUsers();
    $nouvelId = !empty($users) ? max(array_column($users, 'id')) + 1 : 1;
    $nouveauUser = [
        'id' => $nouvelId,
        'prenom' => htmlspecialchars($prenom),
        'nom' => htmlspecialchars($nom),
        'email' => htmlspecialchars($email),
        'mot_passe' => htmlspecialchars($mot_passe),
        'date_inscription' => date('Y-m-d')
    ];
    $users[] = $nouveauUser;
    ecrireUsers($users);
    return $nouveauUser;
}

function estUserConnecte() {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    return isset($_SESSION['user']) && isset($_SESSION['role']) && $_SESSION['role'] === 'user';
}

function connecterUser($user) {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    $_SESSION['user'] = $user;
    $_SESSION['role'] = 'user';
}

function deconnecterUser() {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    unset($_SESSION['user']);
    unset($_SESSION['role']);
    session_destroy();
}

function getUtilisateurConnecte() {
    if (estUserConnecte()) {
        return $_SESSION['user'];
    }
    if (estAdminConnecte()) {
        return $_SESSION['admin'];
    }
    return null;
}
?>
