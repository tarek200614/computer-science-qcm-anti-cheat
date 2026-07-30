<?php
require_once '../../src/auth.php';
require_once '../../src/functions.php';
requireConnecte();

// CSRF Protection
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !verifyCsrfToken($_POST['csrf_token'] ?? '')) {
    die('Erreur de sécurité.');
}

// Sécurité : QCM doit être en cours
if (!isset($_SESSION['qcm_questions']) || !isset($_SESSION['qcm_en_cours'])) {
    header('Location: qcm.php');
    exit;
}

$questions   = $_SESSION['qcm_questions'];
$reponses    = $_POST['reponse'] ?? [];
$bonnes      = 0;
$details     = [];

foreach ($questions as $q) {
    $rep_user  = (int) ($reponses[$q['id']] ?? 0);
    $correcte  = ($rep_user === (int) $q['bonne_reponse']) ? 1 : 0;
    if ($correcte) $bonnes++;
    $details[] = [
        'question'       => $q['question'],
        'question_id'    => $q['id'],
        'reponse'        => $rep_user,
        'correcte'       => $correcte,
        'bonne_reponse'  => $q['bonne_reponse'],
        'libelle_bonne'  => $q['reponse' . $q['bonne_reponse']],
        'libelle_user'   => $rep_user ? $q['reponse' . $rep_user] : 'Sans réponse',
    ];
}

$score = calculerScore($bonnes);

// Enregistrement en BDD
$tentativeId = enregistrerTentative($_SESSION['user_id'], $score);
enregistrerReponses($tentativeId, $details);

// Nettoyage session QCM
unset($_SESSION['qcm_questions'], $_SESSION['qcm_debut'], $_SESSION['qcm_en_cours']);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Résultats – QCM</title>
    <link rel="stylesheet" href="../../public/css/style.css">
</head>
<body>
<header>
    <nav>
        <h1>🎓 QCM Informatique</h1>
        <span>Bonjour <?= htmlspecialchars($_SESSION['prenom']) ?></span>
        <a href="qcm.php">Nouveau QCM</a>
        <a href="history.php">Mon historique</a>
        <a href="../../index.php">Accueil</a>
        <a href="../../logout.php">Déconnexion</a>
    </nav>
</header>

<main class="resultats-container">
    <h2>Vos résultats</h2>

    <div class="score-card">
        <div class="score-note"><?= $score ?> / 20</div>
        <p><?= $bonnes ?> bonne(s) réponse(s) sur 10</p>
    </div>

    <h3>Détail des réponses</h3>
    <?php foreach ($details as $d): ?>
    <div class="detail <?= $d['correcte'] ? 'correct' : 'incorrect' ?>">
        <p><strong><?= htmlspecialchars($d['question']) ?></strong></p>
        <?php if (!$d['correcte']): ?>
            <p>Votre réponse : <span class="mauvaise"><?= htmlspecialchars($d['libelle_user']) ?></span></p>
            <p>Bonne réponse : <span class="bonne"><?= htmlspecialchars($d['libelle_bonne']) ?></span></p>
        <?php else: ?>
            <p>✅ Bonne réponse : <?= htmlspecialchars($d['libelle_bonne']) ?></p>
        <?php endif; ?>
    </div>
    <?php endforeach; ?>

    <div class="actions">
        <a href="qcm.php" class="btn-primary">Refaire un QCM</a>
        <a href="history.php" class="btn-secondary">Mon historique</a>
    </div>
</main>
</body>
</html>
