<?php
// ============================================================
//  client/pages/mentions_legales.php
//  Chemin : wamp/www/supercar/client/pages/mentions_legales.php
// ============================================================
require_once __DIR__ . '/../../config/session.php';

$titrePage  = 'Mentions légales';
$pageActive = '';
require_once __DIR__ . '/../../includes/header.php';
?>

<div class="page-banner">
    <p class="banner-eyebrow">Légal</p>
    <h1 class="banner-title">Mentions légales</h1>
    <p class="banner-sub">Dernière mise à jour : janvier 2024</p>
</div>

<div class="legal-page">

    <div class="legal-section">
        <h2 class="legal-title">1. Éditeur du site</h2>
        <p>Le site <strong>supercar.mu</strong> est édité par la société <strong>SuperCar Ltd</strong>, société à responsabilité limitée au capital de Rs 500 000, immatriculée au Registre du Commerce de l'Île Maurice.</p>
        <ul class="legal-list">
            <li><strong>Siège social :</strong> Ebène CyberCity, Île Maurice</li>
            <li><strong>Téléphone :</strong> +230 5XXX XXXX</li>
            <li><strong>Email :</strong> info@supercar.mu</li>
            <li><strong>Directeur de publication :</strong> Le Gérant de SuperCar Ltd</li>
        </ul>
    </div>

    <div class="legal-section">
        <h2 class="legal-title">2. Hébergement</h2>
        <p>Le site est hébergé par :</p>
        <ul class="legal-list">
            <li><strong>Hébergeur :</strong> AlwaysData</li>
            <li><strong>Adresse :</strong> 91 rue du Faubourg Saint-Honoré, 75008 Paris, France</li>
            <li><strong>Site :</strong> www.alwaysdata.com</li>
        </ul>
    </div>

    <div class="legal-section">
        <h2 class="legal-title">3. Propriété intellectuelle</h2>
        <p>L'ensemble des contenus présents sur ce site (textes, images, logos, graphismes) sont la propriété exclusive de SuperCar Ltd ou de ses partenaires, et sont protégés par les lois en vigueur relatives à la propriété intellectuelle.</p>
        <p>Toute reproduction, représentation, modification ou exploitation non autorisée de tout ou partie du site est strictement interdite.</p>
    </div>

    <div class="legal-section">
        <h2 class="legal-title">4. Responsabilité</h2>
        <p>SuperCar Ltd s'efforce d'assurer l'exactitude et la mise à jour des informations diffusées sur ce site. Toutefois, SuperCar Ltd ne peut garantir l'exactitude, la précision ou l'exhaustivité des informations mises à disposition.</p>
        <p>SuperCar Ltd décline toute responsabilité pour tout dommage résultant d'une intrusion frauduleuse d'un tiers ayant entraîné une modification des informations sur le site.</p>
    </div>

    <div class="legal-section">
        <h2 class="legal-title">5. Liens hypertextes</h2>
        <p>Le site peut contenir des liens vers d'autres sites internet. SuperCar Ltd n'exerce aucun contrôle sur ces sites et décline toute responsabilité quant à leur contenu.</p>
    </div>

    <div class="legal-section">
        <h2 class="legal-title">6. Droit applicable</h2>
        <p>Le présent site et ses mentions légales sont soumis au droit mauricien. Tout litige relatif à l'utilisation du site sera soumis à la compétence exclusive des tribunaux de l'Île Maurice.</p>
    </div>

</div>

<?php require_once __DIR__ . '/../../includes/footer.php'; ?>
