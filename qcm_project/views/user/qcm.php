<?php
// =============================================
// qcm.php – VERSION CORRIGÉE
// Seule la section <div id="avertissement-triche"> est modifiée.
// Le reste du fichier est identique à l'original.
// CORRECTION : ajout de id="btn-reprendre" sur le bouton Reprendre
// pour que anti-triche.js puisse mettre à jour son libellé dynamiquement.
// =============================================
require_once '../../src/auth.php';
require_once '../../src/functions.php';
requireConnecte();
 
if (!isset($_SESSION['qcm_questions'])) {
    $_SESSION['qcm_questions']  = getQuestionsAleatoires();
    $_SESSION['qcm_debut']      = time();
    $_SESSION['qcm_en_cours']   = true;
}
 
$questions = $_SESSION['qcm_questions'];
$dureeMax  = 600;
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>QCM en cours</title>
    <link rel="stylesheet" href="../../public/css/style.css">
</head>
<body>
 
<header>
    <nav>
        <h1>🎓 QCM Informatique</h1>
        <span>Bonjour <?= htmlspecialchars($_SESSION['prenom']) ?></span>
        <a href="history.php">Mon historique</a>
        <a href="../../index.php">Accueil</a>
        <a href="../../logout.php">Déconnexion</a>
    </nav>
</header>
 
<!-- MODAL ANTI-TRICHE -->
<!-- CORRECTION : id="btn-reprendre" ajouté pour permettre la mise à jour
     dynamique du libellé depuis anti-triche.js -->
<div id="avertissement-triche" class="modal hidden">
    <div class="modal-content">
        <h3>⚠️ Avertissement</h3>
        <p>Vous avez quitté la fenêtre du QCM.</p>
        <button id="btn-reprendre" onclick="retourQCM()">Reprendre le QCM</button>
        <button onclick="annulerTentative()">Abandonner</button>
    </div>
</div>
 
<div id="qcm-container">
    <div id="header-qcm">
        <span id="timer">⏱ 10:00</span>
        <span>Question <span id="question-actuelle">1</span>/10</span>
    </div>
 
    <form id="qcm-form" method="POST" action="results.php">
        <input type="hidden" name="csrf_token" value="<?= generateCsrfToken() ?>">
        <?php foreach ($questions as $i => $q): ?>
        <div class="question <?= $i === 0 ? 'active' : '' ?>" id="q<?= $i ?>">
            <h3>Q<?= $i + 1 ?>. <?= htmlspecialchars($q['question']) ?></h3>
            <?php for ($r = 1; $r <= 4; $r++): ?>
            <label>
                <input type="radio" name="reponse[<?= $q['id'] ?>]" value="<?= $r ?>" required>
                <?= htmlspecialchars($q['reponse' . $r]) ?>
            </label>
            <?php endfor; ?>
        </div>
        <?php endforeach; ?>
 
        <div class="navigation">
            <button type="button" id="btn-precedent" onclick="changerQuestion(-1)" disabled>← Précédent</button>
            <button type="button" id="btn-suivant"   onclick="changerQuestion(1)">Suivant →</button>
            <button type="submit" id="btn-valider"   class="hidden">Valider le QCM</button>
        </div>
    </form>
</div>

<script>
    let questionActuelle = 0;
    const totalQuestions = <?= count($questions) ?>;

    function changerQuestion(direction) {
        const questions = document.querySelectorAll('.question');
        questions[questionActuelle].classList.remove('active');
        
        questionActuelle += direction;
        
        if (questionActuelle < 0) questionActuelle = 0;
        if (questionActuelle >= totalQuestions) questionActuelle = totalQuestions - 1;
        
        questions[questionActuelle].classList.add('active');
        
        document.getElementById('question-actuelle').textContent = questionActuelle + 1;
        
        document.getElementById('btn-precedent').disabled = questionActuelle === 0;
        document.getElementById('btn-suivant').classList.toggle('hidden', questionActuelle === totalQuestions - 1);
        document.getElementById('btn-valider').classList.toggle('hidden', questionActuelle !== totalQuestions - 1);
    }

    const DUREE_MAX = <?= $dureeMax ?>;
    const DEBUT     = <?= $_SESSION['qcm_debut'] ?>;
</script>
<script src="../../public/js/anti-cheat.js"></script>
<script src="../../public/js/timer.js"></script>
</body>
</html>
 