<?php
require_once '../../includes/auth.php';
require_once '../../includes/functions.php';
requireAdmin();

$utilisateurs = getTousUtilisateurs();
$questions    = getToutesQuestions();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Administration – QCM</title>
    <link rel="stylesheet" href="../../public/css/style.css">
    <link rel="stylesheet" href="../../public/css/admin.css">
</head>
<body>
<aside class="sidebar">
    <h2>⚙️ Admin</h2>
    <nav>
        <a href="dashboard.php">Tableau de bord</a>
        <a href="utilisateurs.php">Utilisateurs</a>
        <a href="questions.php">Questions</a>
        <a href="../../index.php">← Site</a>
    </nav>
</aside>

<main class="admin-main">
    <h2>Tableau de bord</h2>

    <div class="stats-grid">
        <div class="stat-card">
            <h3><?= count($utilisateurs) ?></h3>
            <p>Utilisateurs</p>
        </div>
        <div class="stat-card">
            <h3><?= count($questions) ?></h3>
            <p>Questions</p>
        </div>
    </div>

    <div class="quick-links">
        <a href="utilisateurs.php" class="btn-primary">Gérer les utilisateurs</a>
        <a href="questions.php"    class="btn-primary">Gérer les questions</a>
    </div>
</main>
</body>
</html>
