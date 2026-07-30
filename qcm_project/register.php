<?php
require_once 'includes/auth.php';
require_once 'includes/db.php';

// Si déjà connecté → accueil
if (estConnecte()) {
    header('Location: index.php');
    exit;
}

$erreur = '';
$succes = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom     = trim($_POST['nom']      ?? '');
    $prenom  = trim($_POST['prenom']   ?? '');
    $email   = trim($_POST['email']    ?? '');
    $mdp     = $_POST['mot_de_passe']  ?? '';
    $confirm = $_POST['confirmation']  ?? '';

    if (empty($nom) || empty($prenom) || empty($email) || empty($mdp)) {
        $erreur = 'Tous les champs sont obligatoires.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $erreur = 'Adresse email invalide.';
    } elseif (strlen($mdp) < 8) {
        $erreur = 'Le mot de passe doit contenir au moins 8 caractères.';
    } elseif ($mdp !== $confirm) {
        $erreur = 'Les mots de passe ne correspondent pas.';
    } else {
        $stmt = $pdo->prepare("SELECT id FROM utilisateurs WHERE email = ?");
        $stmt->execute([$email]);
        if ($stmt->fetch()) {
            $erreur = 'Cet email est déjà utilisé.';
        } else {
            $hash = password_hash($mdp, PASSWORD_BCRYPT);
            $stmt = $pdo->prepare("INSERT INTO utilisateurs (nom, prenom, email, mot_de_passe) VALUES (?, ?, ?, ?)");
            $stmt->execute([$nom, $prenom, $email, $hash]);
            $succes = 'Compte créé avec succès !';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Inscription – QCM</title>
    <link rel="stylesheet" href="public/css/style.css">
</head>
<body>
<main class="form-container">
    <h2>Créer un compte</h2>

    <?php if ($erreur): ?>
        <div class="alert error"><?= htmlspecialchars($erreur) ?></div>
    <?php endif; ?>

    <?php if ($succes): ?>
        <div class="alert success">
            <?= htmlspecialchars($succes) ?>
            <br><br>
            <a href="connexion.php" class="btn-primary">Se connecter maintenant</a>
        </div>
    <?php else: ?>
    <form method="POST">
        <input type="text"     name="nom"          placeholder="Nom"             required>
        <input type="text"     name="prenom"        placeholder="Prénom"          required>
        <input type="email"    name="email"         placeholder="Email"           required>
        <input type="password" name="mot_de_passe"  placeholder="Mot de passe"    required minlength="8">
        <input type="password" name="confirmation"  placeholder="Confirmez le MDP" required>
        <button type="submit">S'inscrire</button>
    </form>
    <p>Déjà inscrit ? <a href="connexion.php">Se connecter</a></p>
    <?php endif; ?>
</main>
</body>
</html>
