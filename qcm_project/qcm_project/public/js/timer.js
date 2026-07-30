// =============================================
// CHRONOMÈTRE – Décompte 10 minutes
// =============================================

document.addEventListener('DOMContentLoaded', () => {
    const timerEl = document.getElementById('timer');
    if (!timerEl) return;

    // DUREE_MAX et DEBUT sont injectés par qcm.php
    const tempsEcoule = Math.floor(Date.now() / 1000) - DEBUT;
    let restant = Math.max(0, DUREE_MAX - tempsEcoule);

    function formaterTemps(sec) {
        const m = String(Math.floor(sec / 60)).padStart(2, '0');
        const s = String(sec % 60).padStart(2, '0');
        return `⏱ ${m}:${s}`;
    }

    timerEl.textContent = formaterTemps(restant);

    const intervalle = setInterval(() => {
        restant--;
        timerEl.textContent = formaterTemps(restant);

        // Alerte à 1 minute restante
        if (restant === 60) {
            timerEl.classList.add('timer-warning');
        }

        // Temps écoulé : soumission automatique
        if (restant <= 0) {
            clearInterval(intervalle);
            timerEl.textContent = '⏱ 00:00';
            alert('Temps écoulé ! Le QCM va être soumis automatiquement.');
            document.getElementById('qcm-form').submit();
        }
    }, 1000);
});
