<?php
require_once '../../includes/auth.php';
require_once '../../includes/functions.php';
requireConnecte();

$userId    = $_SESSION['user_id'];
$historique = getHistoriqueUtilisateur($userId);
$moyenne   = getMoyenneUtilisateur($userId);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Mon historique – QCM</title>
    <link rel="stylesheet" href="../../public/css/style.css">
</head>
<body>
<main>
    <h2>Mon historique</h2>

    <div class="stat-box">
        <strong>Moyenne générale : <?= $moyenne ?> / 20</strong>
        (<?= count($historique) ?> tentative(s))
    </div>

    <?php if (empty($historique)): ?>
        <p>Aucune tentative pour l'instant. <a href="qcm.php">Lancer un QCM</a></p>
    <?php else: ?>
    <table>
        <thead>
            <tr>
                <th>Tentative</th>
                <th>Date</th>
                <th>Score</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($historique as $i => $t): ?>
            <tr>
                <td><?= count($historique) - $i ?></td>
                <td><?= date('d/m/Y H:i', strtotime($t['date'])) ?></td>
                <td><?= $t['score'] ?> / 20</td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
    <?php endif; ?>

    <a href="qcm.php" class="btn-primary">Nouveau QCM</a>
</main>
</body>
</html>
