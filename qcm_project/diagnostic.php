<?php
// ============================================================
// FICHIER DIAGNOSTIC — à supprimer après correction
// Placez ce fichier dans C:\MAMP\htdocs\qcm_project\
// Puis ouvrez : http://localhost:8888/qcm_project/diagnostic.php
// ============================================================

ini_set('display_errors', 1);
error_reporting(E_ALL);

echo "<h2>=== DIAGNOSTIC QCM PROJECT ===</h2>";

// ---- 1. Version PHP ----
echo "<h3>1. Version PHP</h3>";
echo "<p style='color:green'>PHP " . phpversion() . "</p>";

// ---- 2. Test connexion BDD ----
echo "<h3>2. Connexion base de données</h3>";
try {
    $pdo = new PDO(
        "mysql:host=127.0.0.1;port=8889;dbname=qcm_db;charset=utf8",
        "root",
        "root",
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );
    echo "<p style='color:green'>✅ Connexion BDD OK (port 8889, user root, pass root)</p>";
} catch (PDOException $e) {
    echo "<p style='color:red'>❌ Erreur BDD : " . $e->getMessage() . "</p>";

    // Essai avec mot de passe vide
    try {
        $pdo2 = new PDO(
            "mysql:host=127.0.0.1;port=8889;dbname=qcm_db;charset=utf8",
            "root", "",
            [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
        );
        echo "<p style='color:orange'>⚠️ Connexion OK avec mot de passe VIDE (port 8889)</p>";
        $pdo = $pdo2;
    } catch (PDOException $e2) {
        // Essai port 3306
        try {
            $pdo3 = new PDO(
                "mysql:host=127.0.0.1;port=3306;dbname=qcm_db;charset=utf8",
                "root", "root",
                [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
            );
            echo "<p style='color:orange'>⚠️ Connexion OK avec port 3306 (pas 8889)</p>";
            $pdo = $pdo3;
        } catch (PDOException $e3) {
            try {
                $pdo4 = new PDO(
                    "mysql:host=127.0.0.1;port=3306;dbname=qcm_db;charset=utf8",
                    "root", "",
                    [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
                );
                echo "<p style='color:orange'>⚠️ Connexion OK port 3306 + mot de passe vide</p>";
                $pdo = $pdo4;
            } catch (PDOException $e4) {
                echo "<p style='color:red'>❌ Aucune combinaison ne fonctionne</p>";
                $pdo = null;
            }
        }
    }
}

// ---- 3. Tables BDD ----
echo "<h3>3. Tables dans qcm_db</h3>";
if (isset($pdo) && $pdo !== null) {
    $tables = $pdo->query("SHOW TABLES")->fetchAll(PDO::FETCH_COLUMN);
    if (count($tables) === 0) {
        echo "<p style='color:red'>❌ Aucune table — la base est vide, importez database.sql</p>";
    } else {
        foreach ($tables as $t) {
            $count = $pdo->query("SELECT COUNT(*) FROM `$t`")->fetchColumn();
            echo "<p style='color:green'>✅ Table <strong>$t</strong> — $count ligne(s)</p>";
        }
    }
}

// ---- 4. Fichiers critiques ----
echo "<h3>4. Fichiers du projet</h3>";
$fichiers = [
    'includes/db.php',
    'includes/auth.php',
    'includes/functions.php',
    'connexion.php',
    'inscription.php',
    'index.php',
    'pages/user/qcm.php',
    'pages/user/resultats.php',
    'public/js/timer.js',
    'public/js/anti-triche.js',
    'public/css/style.css',
];
foreach ($fichiers as $f) {
    $chemin = __DIR__ . '/' . $f;
    if (file_exists($chemin)) {
        echo "<p style='color:green'>✅ $f</p>";
    } else {
        echo "<p style='color:red'>❌ MANQUANT : $f</p>";
    }
}

// ---- 5. Chemins serveur ----
echo "<h3>5. Chemins serveur</h3>";
echo "<p>SCRIPT_NAME : " . $_SERVER['SCRIPT_NAME'] . "</p>";
echo "<p>DOCUMENT_ROOT : " . $_SERVER['DOCUMENT_ROOT'] . "</p>";
echo "<p>__DIR__ : " . __DIR__ . "</p>";

// ---- 6. Session test ----
echo "<h3>6. Test session PHP</h3>";
session_start();
$_SESSION['test'] = 'ok';
echo isset($_SESSION['test'])
    ? "<p style='color:green'>✅ Sessions PHP fonctionnelles</p>"
    : "<p style='color:red'>❌ Problème sessions PHP</p>";

echo "<hr><p style='color:gray; font-size:12px;'>Supprimez ce fichier après correction.</p>";
?>
