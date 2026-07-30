<?php
require_once 'src/auth.php';
require_once 'src/db.php';

// Si déjà connecté → accueil
if (estConnecte()) {
    header('Location: index.php');
    exit;
}

$erreur = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // CSRF Protection
    if (!verifyCsrfToken($_POST['csrf_token'] ?? '')) {
        $erreur = 'Erreur de sécurité. Veuillez réessayer.';
    } else {
        $email = trim($_POST['email'] ?? '');
        $mdp   = $_POST['mot_de_passe'] ?? '';

        $stmt = $pdo->prepare("SELECT * FROM utilisateurs WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch();

    if ($user && password_verify($mdp, $user['mot_de_passe'])) {
        if ($user['bloque']) {
            $erreur = 'Votre compte a été bloqué. Contactez un administrateur.';
        } else {
            connecterUtilisateur($user);
            header('Location: index.php');
            exit;
        }
    } else {
        $erreur = 'Email ou mot de passe incorrect.';
    }
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion – QCM</title>
    <link rel="stylesheet" href="public/css/style.css">
</head>
<body>
<main class="form-container">
    <h2>Connexion</h2>

    <?php if ($erreur): ?>
        <div class="alert error"><?= htmlspecialchars($erreur) ?></div>
    <?php endif; ?>
    <?php if (isset($_GET['error']) && $_GET['error'] === 'bloque'): ?>
        <div class="alert error">Compte bloqué.</div>
    <?php endif; ?>

    <form method="POST">
        <input type="hidden" name="csrf_token" value="<?= generateCsrfToken() ?>">
        <input type="email"    name="email"        placeholder="Email"         required>
        <input type="password" name="mot_de_passe" placeholder="Mot de passe"  required>
        <button type="submit">Se connecter</button>
    </form>
    <p>Pas de compte ? <a href="register.php">S'inscrire</a></p>
</main>
</body>
</html>
