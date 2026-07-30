<?php
// =============================================
// GESTION AUTHENTIFICATION & SESSIONS — CORRIGÉ POUR MAMP
// =============================================
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// CSRF Protection
function generateCsrfToken(): string {
    if (!isset($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

function verifyCsrfToken(string $token): bool {
    return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
}

// Détecte automatiquement la base du projet (fonctionne avec MAMP et XAMPP)
function baseUrl(): string {
    $script = $_SERVER['SCRIPT_NAME'];
    // Remonte jusqu'à la racine du projet (dossier contenant index.php)
    $parts = explode('/', trim($script, '/'));
    // Le dossier racine du projet est le premier segment
    $root = '/' . $parts[0];
    return $root;
}

function estConnecte(): bool {
    return isset($_SESSION['user_id']);
}

function estAdmin(): bool {
    return isset($_SESSION['role']) && $_SESSION['role'] === 'admin';
}

function estBloque(): bool {
    return isset($_SESSION['bloque']) && $_SESSION['bloque'] == 1;
}

// Redirige vers connexion si pas connecté
function requireConnecte(): void {
    $base = baseUrl();
    if (!estConnecte()) {
        header('Location: ' . $base . '/login.php');
        exit;
    }
    if (estBloque()) {
        header('Location: ' . $base . '/login.php?error=bloque');
        exit;
    }
}

// Redirige si pas admin
function requireAdmin(): void {
    $base = baseUrl();
    requireConnecte();
    if (!estAdmin()) {
        header('Location: ' . $base . '/index.php');
        exit;
    }
}

// Connecte l'utilisateur (appeler après vérification BDD)
function connecterUtilisateur(array $user): void {
    $_SESSION['user_id']  = $user['id'];
    $_SESSION['nom']      = $user['nom'];
    $_SESSION['prenom']   = $user['prenom'];
    $_SESSION['email']    = $user['email'];
    $_SESSION['role']     = $user['role'];
    $_SESSION['bloque']   = $user['bloque'] ?? 0;
}

function deconnecterUtilisateur(): void {
    $base = baseUrl();
    session_unset();
    session_destroy();
    header('Location: ' . $base . '/login.php');
    exit;
}
?>
