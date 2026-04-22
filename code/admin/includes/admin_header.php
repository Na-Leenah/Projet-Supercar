<?php
// ============================================================
//  admin/includes/admin_header.php
//  Gabarit commun : sidebar + topbar pour toutes les pages admin
// ============================================================
require_once __DIR__ . '/../../config/session.php';
exigerAdmin();  // Redirige si non admin

$adminPage = $adminPage ?? 'dashboard';
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($titrePage) ? $titrePage . ' — Admin SuperCar' : 'Admin SuperCar' ?></title>
    <link rel="stylesheet" href="/admin/assets/admin.css">
</head>
<body class="admin-body">

<!-- ══ SIDEBAR ══ -->
<aside class="admin-sidebar">

    <div class="sidebar-logo">
        <span>Super<em>Car</em></span>
        <small>Admin</small>
    </div>

    <nav class="sidebar-nav">

        <a href="/admin/index.php"
           class="nav-item <?= $adminPage === 'dashboard'    ? 'active' : '' ?>">
            <span>⊞</span> Dashboard
        </a>

        <div class="nav-sep">Catalogue</div>

        <a href="/admin/voitures.php"
           class="nav-item <?= $adminPage === 'voitures'     ? 'active' : '' ?>">
            <span>🚗</span> Voitures
        </a>

        <a href="/admin/marques.php"
           class="nav-item <?= $adminPage === 'marques'      ? 'active' : '' ?>">
            <span>★</span> Marques
        </a>

        <div class="nav-sep">Activité</div>

        <a href="/admin/evenements.php"
           class="nav-item <?= $adminPage === 'evenements'   ? 'active' : '' ?>">
            <span>📅</span> Événements
        </a>

        <a href="/admin/demandes.php"
           class="nav-item <?= $adminPage === 'demandes'     ? 'active' : '' ?>">
            <span>🔑</span> Demandes d'essai
        </a>

        <a href="/admin/inscriptions.php"
           class="nav-item <?= $adminPage === 'inscriptions' ? 'active' : '' ?>">
            <span>✅</span> Inscriptions
        </a>

        <a href="/admin/contacts.php"
           class="nav-item <?= $adminPage === 'contacts'     ? 'active' : '' ?>">
            <span>✉️</span> Messages
        </a>

        <div class="nav-sep">Gestion</div>

        <a href="/admin/utilisateurs.php"
           class="nav-item <?= $adminPage === 'utilisateurs' ? 'active' : '' ?>">
            <span>👥</span> Utilisateurs
        </a>

    </nav>

    <div class="sidebar-bottom">
        <a href="/index.php" class="btn-back-site">← Retour au site</a>
        <a href="/includes/deconnexion.php" class="btn-logout">Déconnexion</a>
    </div>

</aside>

<!-- ══ MAIN ══ -->
<div class="admin-main">

    <div class="admin-topbar">
        <button class="sidebar-toggle" aria-label="Ouvrir le menu" aria-expanded="false">☰</button>
        <div class="topbar-title"><?= htmlspecialchars($titrePage ?? 'Dashboard') ?></div>
        <div class="topbar-user">
            <?= htmlspecialchars(prenomConnecte() . ' ' . ($_SESSION['nom'] ?? '')) ?>
        </div>
    </div>

    <?php afficherFlash(); ?>
