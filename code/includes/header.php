<?php
// ============================================================
//  includes/header.php
//  Navigation commune à toutes les pages du site
//  À inclure en haut de chaque page avec :
//  require_once __DIR__ . '/../includes/header.php';
// ============================================================

// On inclut config au cas où ce n'est pas déjà fait
require_once __DIR__ . '/../config/session.php';

// La page active est définie dans chaque fichier PHP avant l'include
// Ex : $pageActive = 'voitures';  → mettra la classe active sur le lien
$pageActive = $pageActive ?? 'accueil';
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Le titre est défini dans chaque page avant l'include -->
    <title><?= isset($titrePage) ? $titrePage . ' — SuperCar' : 'SuperCar · Concessionnaire de prestige' ?></title>
    <!-- Feuille de styles globale -->
    <link rel="stylesheet" href="/assets/css/style.css">
</head>
<body>

<!-- ══ NAVIGATION ══ -->
<!-- Sur l'accueil : nav transparent flotte sur le hero -->
<!-- Sur les autres pages : nav-solid = fond sombre sticky -->
<nav class="nav <?= $pageActive === 'accueil' ? '' : 'nav-solid' ?>">

    <!-- Logo -->
    <a href="/index.php" class="nav-logo">
        Super<span>Car</span>
    </a>

    <!-- Toggle mobile -->
    <button class="nav-toggle" aria-label="Menu" aria-expanded="false">☰</button>

    <!-- Liens de navigation -->
    <div class="nav-links">
        <a href="/index.php"
           class="<?= $pageActive === 'accueil' ? 'active' : '' ?>">
            Accueil
        </a>
        <a href="/client/pages/voitures.php"
           class="<?= $pageActive === 'voitures' ? 'active' : '' ?>">
            Voitures
        </a>
        <a href="/client/pages/evenements.php"
           class="<?= $pageActive === 'evenements' ? 'active' : '' ?>">
            Événements
        </a>
        <a href="/client/pages/essai.php"
           class="<?= $pageActive === 'essai' ? 'active' : '' ?>">
            Essai
        </a>
        <a href="/client/pages/contact.php"
           class="<?= $pageActive === 'contact' ? 'active' : '' ?>">
            Contact
        </a>
    </div>

    <!-- Authentification -->
    <div class="nav-auth">
        <?php if (estConnecte()): ?>
            <!-- Utilisateur connecté : on affiche son prénom + bouton déconnexion -->
            <span class="nav-username">
                Bonjour, <?= htmlspecialchars(prenomConnecte()) ?>
            </span>
            <a href="/includes/deconnexion.php" class="btn-login">
                Se déconnecter
            </a>
        <?php else: ?>
            <!-- Utilisateur non connecté : login + inscription -->
            <a href="/includes/connexion.php" class="btn-login">
                Se connecter
            </a>
            <a href="/includes/inscription.php" class="btn-signup">
                S'inscrire
            </a>
        <?php endif; ?>
    </div>

</nav>

<!-- Message flash (succès ou erreur après un traitement) -->
<?php afficherFlash(); ?>

<?php if ($pageActive === 'accueil'): ?>
<!-- ── Nav transparent → solide au scroll (accueil uniquement) ── -->
<script>
    (function() {
        const nav = document.querySelector('.nav');
        // Seuil en pixels après lequel le nav devient solide
        const SEUIL = 80;

        function mettreAJourNav() {
            if (window.scrollY > SEUIL) {
                // Scrollé → fond sombre + sticky
                nav.style.background    = 'var(--bg)';
                nav.style.borderBottom  = '1px solid rgba(255,255,255,0.07)';
                nav.style.position      = 'fixed';
                nav.style.boxShadow     = '0 2px 20px rgba(0,0,0,0.4)';
            } else {
                // En haut → transparent
                nav.style.background    = 'transparent';
                nav.style.borderBottom  = 'none';
                nav.style.position      = 'absolute';
                nav.style.boxShadow     = 'none';
            }
        }

        // Écouter le scroll
        window.addEventListener('scroll', mettreAJourNav, { passive: true });

        // Appliquer au chargement (au cas où la page est rechargée scrollée)
        mettreAJourNav();
    })();
</script>
<?php endif; ?>
