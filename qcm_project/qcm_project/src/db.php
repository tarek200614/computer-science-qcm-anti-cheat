<?php
// =============================================
// CONFIGURATION BASE DE DONNÉES — MAMP
// =============================================

define('DB_HOST', '127.0.0.1');  // MAMP : utiliser 127.0.0.1 plutôt que localhost
define('DB_PORT', '8889');        // MAMP : port MySQL = 8889 (pas 3306)
define('DB_NAME', 'qcm_db');
define('DB_USER', 'root');
define('DB_PASS', 'root');        // MAMP : mot de passe par défaut = root

try {
    $pdo = new PDO(
        "mysql:host=" . DB_HOST . ";port=" . DB_PORT . ";dbname=" . DB_NAME . ";charset=utf8",
        DB_USER,
        DB_PASS,
        [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
        ]
    );
} catch (PDOException $e) {
    die(json_encode(['error' => 'Erreur de connexion BDD : ' . $e->getMessage()]));
}
?>
