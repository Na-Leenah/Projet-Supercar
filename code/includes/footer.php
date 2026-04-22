<?php
// ============================================================
//  includes/footer.php
//  Footer commun à toutes les pages
//  À inclure en bas de chaque page avec :
//  require_once __DIR__ . '/../includes/footer.php';
// ============================================================
?>

<!-- ══ FOOTER ══ -->
<footer class="footer">

    <div class="footer-logo">
        Super<span>Car</span>
    </div>

    <div class="footer-links">
        <a href="/client/pages/contact.php">Contact</a>
        <a href="/client/pages/mentions_legales.php">Mentions légales</a>
        <a href="/client/pages/conditions_utilisation.php">Conditions d'utilisation</a>
        <a href="/client/pages/politique_confidentialite.php">Politique de confidentialité</a>
    </div>

    <div class="footer-copy">
        © <?= date('Y') ?> SuperCar · Île Maurice
    </div>

</footer>

<!-- Script JS global -->
<script src="/assets/js/main.js"></script>

</body>
</html>
