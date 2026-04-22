// ============================================================
//  assets/js/main.js
//  Script global SuperCar
// ============================================================

// ── NAV : apparaît au scroll (page d'accueil uniquement) ──
// On cible le nav uniquement s'il n'a pas la classe nav-solid
// (nav-solid = pages intérieures, déjà fixes)
(function() {
    const nav = document.querySelector('.nav:not(.nav-solid)');
    if (!nav) return;   // Pas de nav transparent sur cette page

    // Seuil à partir duquel le nav devient visible
    // = la hauteur du hero (environ 650px) moins 100px
    const SEUIL = 100;

    function mettreAJourNav() {
        if (window.scrollY > SEUIL) {
            nav.classList.add('scrolled');
        } else {
            nav.classList.remove('scrolled');
        }
    }

    // Écouter le scroll
    window.addEventListener('scroll', mettreAJourNav, { passive: true });

    // Vérifier au chargement (si on arrive sur la page déjà scrollée)
    mettreAJourNav();
})();

// ── Toggle menu mobile (site) ──
(function() {
    const nav = document.querySelector('.nav');
    if (!nav) return;
    const toggle = nav.querySelector('.nav-toggle');
    const links = nav.querySelector('.nav-links');
    if (!toggle || !links) return;

    toggle.addEventListener('click', function() {
        const opened = nav.classList.toggle('nav-open');
        toggle.setAttribute('aria-expanded', opened ? 'true' : 'false');
        links.classList.toggle('open', opened);
    });
})();
