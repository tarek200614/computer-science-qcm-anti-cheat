# QCM Informatique – Projet Web

Application web de génération et passage de QCM en PHP/MySQL.

---

## 🚀 Installation (XAMPP / WAMP)

1. Copier le dossier `qcm_project/` dans `htdocs/`
2. Importer `sql/database.sql` dans phpMyAdmin
3. Ajuster `includes/db.php` si besoin (user/pass)
4. Accéder à `http://localhost/qcm_project/`

---

## 📁 Structure du projet

```
qcm_project/
├── public/
│   ├── css/
│   │   ├── style.css        ← styles utilisateur
│   │   └── admin.css        ← styles admin
│   └── js/
│       ├── anti-triche.js   ← plein écran, détection onglet, blocages
│       └── timer.js         ← chronomètre décompte
├── includes/
│   ├── db.php               ← connexion PDO
│   ├── auth.php             ← sessions, droits
│   └── functions.php        ← toutes les fonctions métier
├── pages/
│   ├── user/
│   │   ├── qcm.php          ← passage du QCM
│   │   ├── resultats.php    ← résultats + correction
│   │   └── historique.php   ← historique + moyenne
│   └── admin/
│       ├── dashboard.php    ← tableau de bord
│       ├── utilisateurs.php ← gérer users
│       └── questions.php    ← gérer questions
├── sql/
│   └── database.sql         ← script BDD
├── index.php                ← page d'accueil
├── inscription.php
├── connexion.php
└── deconnexion.php
```

---

## 👥 Répartition des tâches (5 membres)

### Membre 1 – Base de données & Backend (includes/)
**Fichiers :** `sql/database.sql`, `includes/db.php`, `includes/functions.php`

- Finaliser et tester le script SQL (vérifier les FK, index)
- Importer les 100 questions du QCM fourni (`corrige_qcm_informatique_100.pdf`)
- Compléter `functions.php` : fonctions manquantes (modifier question, stats admin)
- Tester toutes les requêtes PDO en conditions réelles

---

### Membre 2 – Authentification & Sécurité PHP
**Fichiers :** `includes/auth.php`, `inscription.php`, `connexion.php`, `deconnexion.php`

- Tester inscription : validation email unique, hash bcrypt, retours d'erreur
- Tester connexion : sessions PHP, redirection selon le rôle
- Ajouter protection CSRF sur les formulaires (token caché)
- Ajouter protection contre injections SQL (déjà en PDO préparé → vérifier)
- Gérer le cas compte bloqué

---

### Membre 3 – QCM & Résultats (logique utilisateur)
**Fichiers :** `pages/user/qcm.php`, `pages/user/resultats.php`, `pages/user/historique.php`

- Vérifier le bon fonctionnement du passage question par question
- S'assurer que les réponses sont bien stockées en session
- Tester le calcul du score et l'enregistrement en BDD
- Soigner la page résultats : afficher clairement bonnes/mauvaises réponses
- Compléter la page historique avec graphique ou tableau lisible

---

### Membre 4 – Anti-triche & Timer (JavaScript)
**Fichiers :** `public/js/anti-triche.js`, `public/js/timer.js`

- Tester et affiner la détection plein écran (comportement cross-browser)
- Tester la détection de changement d'onglet (`visibilitychange` + `blur`)
- Affiner la logique d'avertissements (combien avant invalidation ?)
- Soumission automatique à la fin du timer
- Tester sur Chrome, Firefox, Edge

---

### Membre 5 – Interface & CSS (design responsive)
**Fichiers :** `public/css/style.css`, `public/css/admin.css`, toutes les pages HTML

- Finir le design de toutes les pages (accueil, QCM, résultats, admin…)
- Rendre le site **responsive** (mobile / tablette)
- Améliorer le CSS du QCM (progress bar, animation questions)
- Design de la page résultats (couleurs vert/rouge, score animé)
- Interface admin propre (sidebar, tableaux, boutons)

---

## ⚠️ Points importants

- **Pas de framework PHP** (Laravel, Symfony interdits)
- Mots de passe hashés avec `password_hash()` / `password_verify()`
- Toutes les données affichées passent par `htmlspecialchars()`
- Les requêtes SQL utilisent des **requêtes préparées PDO**
- Le QCM doit fonctionner en **plein écran obligatoire**
