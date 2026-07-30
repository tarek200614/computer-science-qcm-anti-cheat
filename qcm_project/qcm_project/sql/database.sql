-- =============================================
-- BASE DE DONNÉES : qcm_db
-- =============================================
CREATE DATABASE IF NOT EXISTS qcm_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE qcm_db;

-- Table utilisateurs
CREATE TABLE IF NOT EXISTS utilisateurs (
    id           INT AUTO_INCREMENT PRIMARY KEY,
    nom          VARCHAR(100)  NOT NULL,
    prenom       VARCHAR(100)  NOT NULL,
    email        VARCHAR(150)  NOT NULL UNIQUE,
    mot_de_passe VARCHAR(255)  NOT NULL,
    role         ENUM('user', 'admin') DEFAULT 'user',
    bloque       TINYINT(1) DEFAULT 0,
    created_at   DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- Table questions
CREATE TABLE IF NOT EXISTS questions (
    id            INT AUTO_INCREMENT PRIMARY KEY,
    question      TEXT         NOT NULL,
    reponse1      VARCHAR(255) NOT NULL,
    reponse2      VARCHAR(255) NOT NULL,
    reponse3      VARCHAR(255) NOT NULL,
    reponse4      VARCHAR(255) NOT NULL,
    bonne_reponse INT          NOT NULL CHECK (bonne_reponse BETWEEN 1 AND 4),
    categorie     VARCHAR(100) DEFAULT 'Général'
);

-- Table tentatives
CREATE TABLE IF NOT EXISTS tentatives (
    id              INT AUTO_INCREMENT PRIMARY KEY,
    utilisateur_id  INT   NOT NULL,
    score           FLOAT NOT NULL,
    date            DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (utilisateur_id) REFERENCES utilisateurs(id) ON DELETE CASCADE
);

-- Table réponses (détail par question)
CREATE TABLE IF NOT EXISTS reponses (
    id                   INT AUTO_INCREMENT PRIMARY KEY,
    tentative_id         INT      NOT NULL,
    question_id          INT      NOT NULL,
    reponse_utilisateur  INT      NOT NULL,
    correcte             TINYINT(1) NOT NULL,
    FOREIGN KEY (tentative_id) REFERENCES tentatives(id) ON DELETE CASCADE,
    FOREIGN KEY (question_id)  REFERENCES questions(id)  ON DELETE CASCADE
);

-- =============================================
-- DONNÉES DE TEST
-- =============================================

-- Compte admin (mot de passe : admin123 hashé)
INSERT INTO utilisateurs (nom, prenom, email, mot_de_passe, role)
VALUES ('Admin', 'Super', 'admin@qcm.fr', '$2y$10$examplehashhere', 'admin');

-- Quelques questions de test
INSERT INTO questions (question, reponse1, reponse2, reponse3, reponse4, bonne_reponse, categorie) VALUES
('Quel composant est le cerveau de l\'ordinateur ?',    'RAM', 'CPU', 'GPU', 'SSD',  2, 'Matériel'),
('Que signifie RAM ?',                                  'Read Access Memory', 'Random Access Memory', 'Rapid Action Memory', 'Run App Mode', 2, 'Matériel'),
('Que signifie HTTP ?',                                 'HyperText Transfer Protocol', 'High Tech Transfer Protocol', 'HyperText Transmission Program', 'Host Transfer Technology Protocol', 1, 'Réseaux'),
('Quel langage structure une page web ?',               'CSS', 'PHP', 'HTML', 'SQL', 3, 'Programmation'),
('Que fait Ctrl+C sous Windows ?',                      'Coller', 'Copier', 'Couper', 'Fermer', 2, 'OS'),
('Que signifie SSD ?',                                  'Super Speed Drive', 'Solid State Drive', 'System Storage Device', 'Safe Secure Disk', 2, 'Matériel'),
('Quel protocole envoie les emails ?',                  'FTP', 'HTTP', 'SMTP', 'DNS', 3, 'Réseaux'),
('Qu\'est-ce qu\'un bug ?',                             'Un insecte', 'Une erreur dans le code', 'Un virus', 'Un raccourci clavier', 2, 'Programmation'),
('Que fait Alt+F4 ?',                                   'Copier', 'Coller', 'Fermer la fenêtre', 'Redémarrer', 3, 'OS'),
('Que signifie VPN ?',                                  'Very Private Network', 'Virtual Private Network', 'Verified Public Node', 'Virtual Protocol Number', 2, 'Sécurité');
