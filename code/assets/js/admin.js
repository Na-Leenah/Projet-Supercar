// ============================================================
//  assets/js/admin.js
//  Scripts JavaScript pour le panneau d'administration
//  - toggle sidebar on small screens
// ============================================================

// Fermeture automatique des alertes (existant)
document.querySelectorAll('.admin-alert').forEach(function(alert) {
    setTimeout(function() {
        alert.style.transition = 'opacity 0.5s ease';
        alert.style.opacity    = '0';
        setTimeout(function() { alert.remove(); }, 500);
    }, 4000);
});

// Confirmation avant suppression (existant)
document.querySelectorAll('[data-confirm]').forEach(function(el) {
    el.addEventListener('click', function(e) {
        if (!confirm(this.getAttribute('data-confirm'))) {
            e.preventDefault();
        }
    });
});

// Toggle sidebar on small screens
(function() {
    const sidebar = document.querySelector('.admin-sidebar');
    const toggle = document.querySelector('.sidebar-toggle');
    if (!sidebar || !toggle) return;

    // create overlay
    let overlay = document.querySelector('.sidebar-overlay');
    if (!overlay) {
        overlay = document.createElement('div');
        overlay.className = 'sidebar-overlay';
        document.body.appendChild(overlay);
    }

    function openSidebar() {
        sidebar.classList.add('open');
        overlay.classList.add('open');
        toggle.setAttribute('aria-expanded', 'true');
    }
    function closeSidebar() {
        sidebar.classList.remove('open');
        overlay.classList.remove('open');
        toggle.setAttribute('aria-expanded', 'false');
    }

    toggle.addEventListener('click', function() {
        if (sidebar.classList.contains('open')) closeSidebar(); else openSidebar();
    });

    overlay.addEventListener('click', closeSidebar);
})();
// ============================================================
//  assets/js/admin.js
//  Scripts JavaScript pour le panneau d'administration
// ============================================================

// ── Fermeture automatique des alertes après 4 secondes ──
document.querySelectorAll('.admin-alert').forEach(function(alert) {
    setTimeout(function() {
        alert.style.transition = 'opacity 0.5s ease';
        alert.style.opacity    = '0';
        setTimeout(function() { alert.remove(); }, 500);
    }, 4000);
});

// ── Confirmation avant suppression ──
// Tous les liens avec data-confirm déclenchent une boîte de confirmation
document.querySelectorAll('[data-confirm]').forEach(function(el) {
    el.addEventListener('click', function(e) {
        if (!confirm(this.getAttribute('data-confirm'))) {
            e.preventDefault();
        }
    });
});
