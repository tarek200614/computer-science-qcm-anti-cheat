<?php
require_once '../../src/auth.php';
require_once '../../src/functions.php';
requireAdmin();

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // CSRF Protection
    if (!verifyCsrfToken($_POST['csrf_token'] ?? '')) {
        $message = 'Erreur de sécurité. Veuillez réessayer.';
    } else {
        $action = $_POST['action'] ?? '';

        if ($action === 'ajouter') {
            $data = [
                'question' => trim($_POST['question'] ?? ''),
                'r1' => trim($_POST['r1'] ?? ''),
                'r2' => trim($_POST['r2'] ?? ''),
                'r3' => trim($_POST['r3'] ?? ''),
                'r4' => trim($_POST['r4'] ?? ''),
                'bonne' => (int)($_POST['bonne'] ?? 0),
                'categorie' => trim($_POST['categorie'] ?? 'Général')
            ];
            
            if (empty($data['question']) || empty($data['r1']) || empty($data['r2']) || 
                empty($data['r3']) || empty($data['r4']) || $data['bonne'] < 1 || $data['bonne'] > 4) {
                $message = 'Tous les champs sont obligatoires et la bonne réponse doit être entre 1 et 4.';
            } else {
                ajouterQuestion($data);
                $message = 'Question ajoutée.';
            }
        } elseif ($action === 'supprimer') {
            $id = (int)($_POST['id'] ?? 0);
            if ($id > 0) {
                supprimerQuestion($id);
                $message = 'Question supprimée.';
            } else {
                $message = 'ID invalide.';
            }
        }
    }
    header('Location: questions.php?msg=' . urlencode($message));
    exit;
}

$questions = getToutesQuestions();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Questions – Admin</title>
    <link rel="stylesheet" href="../../public/css/style.css">
    <link rel="stylesheet" href="../../public/css/admin.css">
</head>
<body>
<aside class="sidebar">
    <h2>⚙️ Admin</h2>
    <nav>
        <a href="dashboard.php">Tableau de bord</a>
        <a href="users.php">Utilisateurs</a>
        <a href="questions.php" class="active">Questions</a>
        <a href="../../index.php">← Site</a>
    </nav>
</aside>

<main class="admin-main">
    <h2>Gestion des questions (<?= count($questions) ?>)</h2>

    <?php if (isset($_GET['msg'])): ?>
        <div class="alert success"><?= htmlspecialchars($_GET['msg']) ?></div>
    <?php endif; ?>

    <!-- Formulaire ajout -->
    <details class="form-ajout">
        <summary>➕ Ajouter une question</summary>
        <form method="POST">
            <input type="hidden" name="csrf_token" value="<?= generateCsrfToken() ?>">
            <input type="hidden" name="action" value="ajouter">
            <input type="text" name="question"   placeholder="Question"       required>
            <input type="text" name="r1"          placeholder="Réponse 1"      required>
            <input type="text" name="r2"          placeholder="Réponse 2"      required>
            <input type="text" name="r3"          placeholder="Réponse 3"      required>
            <input type="text" name="r4"          placeholder="Réponse 4"      required>
            <select name="bonne" required>
                <option value="">Bonne réponse...</option>
                <option value="1">Réponse 1</option>
                <option value="2">Réponse 2</option>
                <option value="3">Réponse 3</option>
                <option value="4">Réponse 4</option>
            </select>
            <input type="text" name="categorie" placeholder="Catégorie">
            <button type="submit">Ajouter</button>
        </form>
    </details>

    <!-- Liste questions -->
    <table>
        <thead>
            <tr>
                <th>ID</th><th>Question</th><th>Catégorie</th>
                <th>Bonne rép.</th><th>Action</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($questions as $q): ?>
        <tr>
            <td><?= $q['id'] ?></td>
            <td><?= htmlspecialchars(mb_substr($q['question'], 0, 60)) ?>…</td>
            <td><?= htmlspecialchars($q['categorie'] ?? '') ?></td>
            <td><?= $q['bonne_reponse'] ?></td>
            <td>
                <form method="POST" style="display:inline">
                    <input type="hidden" name="csrf_token" value="<?= generateCsrfToken() ?>">
                    <input type="hidden" name="action" value="supprimer">
                    <input type="hidden" name="id"     value="<?= $q['id'] ?>">
                    <button onclick="return confirm('Supprimer ?')">🗑 Supprimer</button>
                </form>
            </td>
        </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</main>
</body>
</html>
