<?php
// ============================================================
//  admin/includes/admin_nav.php — Barre de navigation admin
// ============================================================
?>
<nav class="admin-nav">
    <a href="index.php" class="admin-nav-logo">
        Super<span>Car</span>
        <small>Admin</small>
    </a>
    <div class="admin-nav-right">
        <span class="admin-nav-user">
            👤 <?= htmlspecialchars(prenomConnecte() . ' ' . ($_SESSION['nom'] ?? '')) ?>
        </span>
        <a href="../includes/deconnexion.php" class="admin-nav-logout">
            Se déconnecter
        </a>
    </div>
</nav>
