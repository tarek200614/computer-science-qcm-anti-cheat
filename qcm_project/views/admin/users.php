<?php
require_once '../../src/auth.php';
require_once '../../src/functions.php';
requireAdmin();

// Actions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // CSRF Protection
    if (!verifyCsrfToken($_POST['csrf_token'] ?? '')) {
        $message = 'Erreur de sécurité. Veuillez réessayer.';
    } else {
        $id     = (int) ($_POST['id'] ?? 0);
        $action = $_POST['action'] ?? '';

        if ($action === 'supprimer') {
            supprimerUtilisateur($id);
            $message = 'Utilisateur supprimé.';
        } elseif ($action === 'bloquer') {
            toggleBloquerUtilisateur($id, 1);
            $message = 'Utilisateur bloqué.';
        } elseif ($action === 'debloquer') {
            toggleBloquerUtilisateur($id, 0);
            $message = 'Utilisateur débloqué.';
        }
    }
    header('Location: users.php?msg=' . urlencode($message ?? ''));
    exit;
}

$utilisateurs = getTousUtilisateurs();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Utilisateurs – Admin</title>
    <link rel="stylesheet" href="../../public/css/style.css">
    <link rel="stylesheet" href="../../public/css/admin.css">
</head>
<body>
<aside class="sidebar">
    <h2>⚙️ Admin</h2>
    <nav>
        <a href="dashboard.php">Tableau de bord</a>
        <a href="users.php" class="active">Utilisateurs</a>
        <a href="questions.php">Questions</a>
        <a href="../../index.php">← Site</a>
    </nav>
</aside>

<main class="admin-main">
    <h2>Gestion des utilisateurs (<?= count($utilisateurs) ?>)</h2>

    <?php if (isset($_GET['msg'])): ?>
        <div class="alert success"><?= htmlspecialchars($_GET['msg']) ?></div>
    <?php endif; ?>

    <table>
        <thead>
            <tr>
                <th>ID</th><th>Nom</th><th>Email</th>
                <th>Rôle</th><th>Statut</th><th>Actions</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($utilisateurs as $u): ?>
        <tr class="<?= $u['bloque'] ? 'bloque' : '' ?>">
            <td><?= $u['id'] ?></td>
            <td><?= htmlspecialchars($u['nom'] . ' ' . $u['prenom']) ?></td>
            <td><?= htmlspecialchars($u['email']) ?></td>
            <td><?= $u['role'] ?></td>
            <td><?= $u['bloque'] ? '🔒 Bloqué' : '✅ Actif' ?></td>
            <td>
                <?php if ($u['role'] !== 'admin'): ?>
                <form method="POST" style="display:inline">
                    <input type="hidden" name="csrf_token" value="<?= generateCsrfToken() ?>">
                    <input type="hidden" name="id" value="<?= $u['id'] ?>">
                    <button name="action" value="<?= $u['bloque'] ? 'debloquer' : 'bloquer' ?>">
                        <?= $u['bloque'] ? 'Débloquer' : 'Bloquer' ?>
                    </button>
                    <button name="action" value="supprimer"
                        onclick="return confirm('Supprimer cet utilisateur ?')">Supprimer</button>
                </form>
                <?php endif; ?>
            </td>
        </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</main>
</body>
</html>
