<?php
require_once 'src/auth.php';
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>QCM Informatique</title>
    <link rel="stylesheet" href="public/css/style.css">
</head>
<body>
<header>
    <nav>
        <h1>🎓 QCM Informatique</h1>
        <?php if (estConnecte()): ?>
            <span>Bonjour <?= htmlspecialchars($_SESSION['prenom']) ?></span>
            <a href="views/user/history.php">Mon historique</a>
            <?php if (estAdmin()): ?>
                <a href="views/admin/dashboard.php">Administration</a>
            <?php endif; ?>
            <a href="logout.php">Déconnexion</a>
        <?php else: ?>
            <a href="login.php">Connexion</a>
            <a href="register.php">Inscription</a>
        <?php endif; ?>
    </nav>
</header>

<main class="hero">
    <h2>Testez vos connaissances en informatique</h2>
    <p>10 questions aléatoires · Chronomètre · Résultats détaillés</p>

    <?php if (estConnecte()): ?>
        <a href="views/user/qcm.php" class="btn-primary">Lancer un QCM</a>
        <a href="views/user/history.php" class="btn-secondary">Mon historique</a>
    <?php else: ?>
        <a href="register.php" class="btn-primary">Commencer gratuitement</a>
        <a href="login.php" class="btn-secondary">Se connecter</a>
    <?php endif; ?>
</main>
</body>
</html>
