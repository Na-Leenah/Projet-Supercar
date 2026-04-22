<?php
// ============================================================
//  admin/includes/admin_sidebar.php — Menu latéral admin
// ============================================================

// Page active pour mettre en surbrillance le bon lien
$adminPage = $adminPage ?? '';
?>
<aside class="admin-sidebar">
    <nav class="admin-sidebar-nav">

        <a href="index.php"
           class="sidebar-link <?= $adminPage === 'dashboard' ? 'active' : '' ?>">
            <span class="sidebar-icon">📊</span> Dashboard
        </a>

        <div class="sidebar-group-label">Catalogue</div>

        <a href="voitures.php"
           class="sidebar-link <?= $adminPage === 'voitures' ? 'active' : '' ?>">
            <span class="sidebar-icon">🚗</span> Voitures
        </a>

        <div class="sidebar-group-label">Agenda</div>

        <a href="evenements.php"
           class="sidebar-link <?= $adminPage === 'evenements' ? 'active' : '' ?>">
            <span class="sidebar-icon">📅</span> Événements
        </a>

        <a href="inscriptions.php"
           class="sidebar-link <?= $adminPage === 'inscriptions' ? 'active' : '' ?>">
            <span class="sidebar-icon">✅</span> Inscriptions
        </a>

        <div class="sidebar-group-label">Clients</div>

        <a href="demandes.php"
           class="sidebar-link <?= $adminPage === 'demandes' ? 'active' : '' ?>">
            <span class="sidebar-icon">🔑</span> Demandes essai
        </a>

        <a href="contacts.php"
           class="sidebar-link <?= $adminPage === 'contacts' ? 'active' : '' ?>">
            <span class="sidebar-icon">✉️</span> Messages
        </a>

        <a href="utilisateurs.php"
           class="sidebar-link <?= $adminPage === 'utilisateurs' ? 'active' : '' ?>">
            <span class="sidebar-icon">👥</span> Utilisateurs
        </a>

        <div class="sidebar-group-label">Site</div>

        <a href="../index.php" class="sidebar-link" target="_blank">
            <span class="sidebar-icon">🌐</span> Voir le site
        </a>

    </nav>
</aside>
