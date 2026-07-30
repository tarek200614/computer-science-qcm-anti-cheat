// =============================================
// SYSTÈME ANTI-TRICHE – VERSION CORRIGÉE
// Auteur : QCM Informatique
// Version : 2.0
// =============================================

// ─────────────────────────────────────────────
// 1. CONFIGURATION GLOBALE
// ─────────────────────────────────────────────

/** Nombre maximal d'avertissements avant soumission forcée */
const MAX_AVERTISSEMENTS = 3;

/**
 * Compteur d'avertissements courant.
 * Initialisé à 0, incrémenté à chaque infraction détectée.
 */
let avertissements = 0;

/**
 * Garde anti-rebond pour le blur.
 * Quand la modal est ouverte, le focus quitte la fenêtre ; sans ce garde,
 * l'événement blur déclencherait un second avertissement parasite.
 */
let modalOuverte = false;

/**
 * Garde anti-spam : empêche deux avertissements d'être enregistrés
 * en moins de 800 ms (ex. : fullscreenchange + blur se déclenchant ensemble).
 */
let derniereDetection = 0;
const DELAI_MIN_MS = 100;

// ─────────────────────────────────────────────
// 2. PLEIN ÉCRAN
// ─────────────────────────────────────────────

/**
 * Demande l'entrée en mode plein écran de manière cross-browser.
 * Préfixes couverts : standard, webkit (Safari), moz (Firefox ancien).
 */
function activerPleinEcran() {
    const el = document.documentElement;
    if      (el.requestFullscreen)          el.requestFullscreen();
    else if (el.webkitRequestFullscreen)    el.webkitRequestFullscreen();
    else if (el.mozRequestFullScreen)       el.mozRequestFullScreen();
}

// Activation automatique au chargement du DOM
document.addEventListener('DOMContentLoaded', () => {
    activerPleinEcran();
});

// Détection de sortie du plein écran (tous préfixes)
['fullscreenchange', 'webkitfullscreenchange', 'mozfullscreenchange'].forEach(evt => {
    document.addEventListener(evt, () => {
        const estPleinEcran = document.fullscreenElement
                           || document.webkitFullscreenElement
                           || document.mozFullScreenElement;
        if (!estPleinEcran) {
            enregistrerAvertissement('Vous avez quitté le mode plein écran.');
        }
    });
});

// ─────────────────────────────────────────────
// 3. CHANGEMENT D'ONGLET / MINIMISATION
// ─────────────────────────────────────────────

/**
 * visibilitychange : se déclenche quand l'onglet devient invisible
 * (changement d'onglet, minimisation de la fenêtre, Alt+Tab).
 * C'est l'API officielle W3C ; elle est plus fiable que blur pour ce cas.
 */
document.addEventListener('visibilitychange', () => {
    if (document.hidden) {
        enregistrerAvertissement('Vous avez quitté la fenêtre du QCM.');
    }
});

/**
 * blur sur window : se déclenche quand la fenêtre perd le focus
 * (clic dans une autre application, ouverture d'un menu OS…).
 * CORRECTION : ajout du garde "modalOuverte" pour éviter les faux positifs
 * quand c'est notre propre modal qui prend le focus.
 */
window.addEventListener('blur', () => {
    if (!modalOuverte) {
        enregistrerAvertissement('Vous avez cliqué hors de la fenêtre du QCM.');
    }
});

// ─────────────────────────────────────────────
// 4. CLIC DROIT, COPIER / COLLER, SÉLECTION
// ─────────────────────────────────────────────

/**
 * Désactivation du menu contextuel (clic droit).
 * preventDefault() empêche l'affichage du menu natif du navigateur.
 */
document.addEventListener('contextmenu', e => {
    e.preventDefault();
    enregistrerAvertissement('Utilisation du clic droit interdite.');
});

/**
 * Blocage du copier-coller et de la sélection de texte.
 */
document.addEventListener('copy', e => {
    e.preventDefault();
    enregistrerAvertissement('Copie de texte interdite.');
});

document.addEventListener('cut', e => {
    e.preventDefault();
    enregistrerAvertissement('Couper du texte est interdit.');
});

document.addEventListener('paste', e => {
    e.preventDefault();
    enregistrerAvertissement('Collage de texte interdit.');
});

// Désactivation silencieuse de la sélection de texte (pas d'avertissement,
// car un clic raté peut déclencher selectstart involontairement).
document.addEventListener('selectstart', e => e.preventDefault());

// ─────────────────────────────────────────────
// 5. RACCOURCIS CLAVIER INTERDITS
// ─────────────────────────────────────────────

/**
 * Liste des raccourcis bloqués avec leur description lisible.
 * Structure : { test: Function → boolean, label: string }
 */
const RACCOURCIS_INTERDITS = [
    { test: e => e.key === 'F12',
      label: 'F12 (Outils développeur)' },

    { test: e => e.ctrlKey && e.key.toLowerCase() === 'u',
      label: 'Ctrl+U (Voir la source)' },

    { test: e => e.ctrlKey && e.shiftKey && e.key.toLowerCase() === 'i',
      label: 'Ctrl+Shift+I (Outils développeur)' },

    { test: e => e.ctrlKey && e.shiftKey && e.key.toLowerCase() === 'j',
      label: 'Ctrl+Shift+J (Console)' },

    { test: e => e.ctrlKey && e.shiftKey && e.key.toLowerCase() === 'c',
      label: 'Ctrl+Shift+C (Inspecteur)' },

    { test: e => e.ctrlKey && e.key.toLowerCase() === 's',
      label: 'Ctrl+S (Enregistrer)' },

    { test: e => e.ctrlKey && e.key.toLowerCase() === 'a',
      label: 'Ctrl+A (Tout sélectionner)' },

    { test: e => (e.ctrlKey || e.metaKey) && e.key.toLowerCase() === 'r',
      label: 'Ctrl+R (Recharger)' },

    { test: e => e.key === 'F5',
      label: 'F5 (Recharger)' },
];

document.addEventListener('keydown', e => {
    for (const raccourci of RACCOURCIS_INTERDITS) {
        if (raccourci.test(e)) {
            e.preventDefault();
            enregistrerAvertissement(`Raccourci interdit utilisé : ${raccourci.label}`);
            return; // un seul avertissement par frappe
        }
    }
});

// ─────────────────────────────────────────────
// 6. GESTION DES AVERTISSEMENTS (CŒUR DU SYSTÈME)
// ─────────────────────────────────────────────

/**
 * Enregistre un avertissement et orchestre l'affichage / la sanction.
 *
 * Logique :
 * 1. Vérification du délai anti-spam (DELAI_MIN_MS).
 * 2. Incrémentation du compteur.
 * 3. Affichage de la modal avec le message et le compteur.
 * 4. Si MAX_AVERTISSEMENTS atteint → soumission forcée du QCM.
 *
 * @param {string} message - Description de l'infraction détectée.
 */
// ─────────────────────────────────────────────
// 7. FUNCTIONS MANQUANTES
// ─────────────────────────────────────────────

/**
 * Fonction pour revenir au QCM après avertissement
 * Appelée par le bouton "Reprendre le QCM" dans la modal
 */
function retourQCM() {
    modalOuverte = false;
    document.getElementById('avertissement-triche').classList.add('hidden');
}

/**
 * Fonction pour annuler la tentative en cours
 * Appelée par le bouton "Abandonner" dans la modal
 */
function annulerTentative() {
    if (confirm('Êtes-vous sûr de vouloir abandonner ? Vos réponses seront perdues.')) {
        window.location.href = 'history.php';
    }
}

function enregistrerAvertissement(message) {
    // ── Garde anti-spam ──────────────────────────────────────────────────
    const maintenant = Date.now();
    if (maintenant - derniereDetection < DELAI_MIN_MS) return;
    derniereDetection = maintenant;

    // ── Incrémentation ───────────────────────────────────────────────────
    avertissements++;

    // ── Mise à jour de la modal ──────────────────────────────────────────
    const modal = document.getElementById('avertissement-triche');
    if (!modal) return; // Sécurité : ne pas planter si la modal est absente

    const paragraphe = modal.querySelector('p');
    const btnReprendre = modal.querySelector('#btn-reprendre');

    // Message dynamique avec compteur visuel
    paragraphe.textContent =
        `${message} (Avertissement ${avertissements} / ${MAX_AVERTISSEMENTS})`;

    // Adapter le libellé du bouton selon la situation
    if (avertissements >= MAX_AVERTISSEMENTS) {
        if (btnReprendre) btnReprendre.textContent = 'Soumettre le QCM';
    } else {
        if (btnReprendre) btnReprendre.textContent = 'Reprendre le QCM';
    }

    // ── Affichage de la modal ────────────────────────────────────────────
    modalOuverte = true;
    modal.classList.remove('hidden');

    // ── Soumission forcée ────────────────────────────────────────────────
    // CORRECTION : en v1 la soumission n'existait pas ; le QCM continuait
    // indéfiniment même après le dernier avertissement.
    if (avertissements >= MAX_AVERTISSEMENTS) {
        // Laisser le temps à l'étudiant de lire le message (2 secondes)
        setTimeout(() => {
            const form = document.getElementById('qcm-form');
            if (form) {
                form.submit();
            } else {
                // Fallback si le formulaire n'est pas trouvé
                window.location.href = '../../index.php';
            }
        }, 2000);
    }
}

// ─────────────────────────────────────────────
// 7. ACTIONS DE LA MODAL
// ─────────────────────────────────────────────

/**
 * Ferme la modal et réactive le plein écran.
 * Appelée par le bouton "Reprendre" dans qcm.php.
 * CORRECTION : ajout de la mise à jour de modalOuverte = false
 * (absent en v1, ce qui rendait le garde inactif après la première modal).
 */
function retourQCM() {
    modalOuverte = false;
    document.getElementById('avertissement-triche').classList.add('hidden');
    activerPleinEcran();
}

/**
 * Propose à l'étudiant d'abandonner la tentative.
 * Redirige vers la page d'accueil sans enregistrer le score.
 */
function annulerTentative() {
    if (confirm('Abandonner cette tentative ? Le score ne sera pas enregistré.')) {
        window.location.href = '../../index.php';
    }
}
