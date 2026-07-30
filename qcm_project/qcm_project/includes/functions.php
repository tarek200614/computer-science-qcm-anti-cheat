<?php
// =============================================
// FONCTIONS MÉTIER
// =============================================
require_once __DIR__ . '/db.php';

// ---------- QUESTIONS ----------

/** Tire 10 questions aléatoires depuis la BDD */
function getQuestionsAleatoires(): array {
    global $pdo;
    $stmt = $pdo->query("SELECT * FROM questions ORDER BY RAND() LIMIT 10");
    return $stmt->fetchAll();
}

/** Récupère toutes les questions (admin) */
function getToutesQuestions(): array {
    global $pdo;
    return $pdo->query("SELECT * FROM questions ORDER BY id DESC")->fetchAll();
}

/** Ajoute une question */
function ajouterQuestion(array $data): bool {
    global $pdo;
    $stmt = $pdo->prepare("INSERT INTO questions 
        (question, reponse1, reponse2, reponse3, reponse4, bonne_reponse, categorie) 
        VALUES (?, ?, ?, ?, ?, ?, ?)");
    return $stmt->execute([
        $data['question'], $data['r1'], $data['r2'],
        $data['r3'], $data['r4'], $data['bonne'], $data['categorie'] ?? 'Général'
    ]);
}

/** Supprime une question */
function supprimerQuestion(int $id): bool {
    global $pdo;
    return $pdo->prepare("DELETE FROM questions WHERE id = ?")->execute([$id]);
}

// ---------- SCORE & TENTATIVES ----------

/** Calcule la note sur 20 */
function calculerScore(int $bonnesReponses): float {
    return round(($bonnesReponses / 10) * 20, 1);
}

/** Enregistre une tentative et retourne l'id */
function enregistrerTentative(int $userId, float $score): int {
    global $pdo;
    $stmt = $pdo->prepare("INSERT INTO tentatives (utilisateur_id, score, date) VALUES (?, ?, NOW())");
    $stmt->execute([$userId, $score]);
    return (int) $pdo->lastInsertId();
}

/** Enregistre les réponses détaillées d'une tentative */
function enregistrerReponses(int $tentativeId, array $reponses): void {
    global $pdo;
    $stmt = $pdo->prepare("INSERT INTO reponses 
        (tentative_id, question_id, reponse_utilisateur, correcte) VALUES (?, ?, ?, ?)");
    foreach ($reponses as $r) {
        $stmt->execute([$tentativeId, $r['question_id'], $r['reponse'], $r['correcte']]);
    }
}

// ---------- HISTORIQUE ----------

/** Retourne les tentatives d'un utilisateur */
function getHistoriqueUtilisateur(int $userId): array {
    global $pdo;
    $stmt = $pdo->prepare("SELECT * FROM tentatives WHERE utilisateur_id = ? ORDER BY date DESC");
    $stmt->execute([$userId]);
    return $stmt->fetchAll();
}

/** Calcule la moyenne générale d'un utilisateur */
function getMoyenneUtilisateur(int $userId): float {
    global $pdo;
    $stmt = $pdo->prepare("SELECT AVG(score) as moy FROM tentatives WHERE utilisateur_id = ?");
    $stmt->execute([$userId]);
    return round($stmt->fetch()['moy'] ?? 0, 1);
}

// ---------- UTILISATEURS (admin) ----------

/** Retourne tous les utilisateurs */
function getTousUtilisateurs(): array {
    global $pdo;
    return $pdo->query("SELECT id, nom, prenom, email, role, bloque FROM utilisateurs ORDER BY id DESC")->fetchAll();
}

/** Bloque ou débloque un utilisateur */
function toggleBloquerUtilisateur(int $id, int $statut): bool {
    global $pdo;
    return $pdo->prepare("UPDATE utilisateurs SET bloque = ? WHERE id = ?")->execute([$statut, $id]);
}

/** Supprime un utilisateur */
function supprimerUtilisateur(int $id): bool {
    global $pdo;
    return $pdo->prepare("DELETE FROM utilisateurs WHERE id = ?")->execute([$id]);
}
?>
