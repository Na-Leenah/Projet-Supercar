<?php
// ============================================================
//  client/pages/conditions_utilisation.php
//  Chemin : wamp/www/supercar/client/pages/conditions_utilisation.php
// ============================================================
require_once __DIR__ . '/../../config/session.php';

$titrePage  = "Conditions d'utilisation";
$pageActive = '';
require_once __DIR__ . '/../../includes/header.php';
?>

<div class="page-banner">
    <p class="banner-eyebrow">Légal</p>
    <h1 class="banner-title">Conditions d'utilisation</h1>
    <p class="banner-sub">Dernière mise à jour : janvier 2024</p>
</div>

<div class="legal-page">

    <div class="legal-section">
        <h2 class="legal-title">1. Acceptation des conditions</h2>
        <p>En accédant et en utilisant le site <strong>supercar.mu</strong>, vous acceptez sans réserve les présentes conditions d'utilisation. Si vous n'acceptez pas ces conditions, veuillez ne pas utiliser ce site.</p>
    </div>

    <div class="legal-section">
        <h2 class="legal-title">2. Utilisation du compte</h2>
        <p>Pour accéder à certaines fonctionnalités (demandes d'essai, inscriptions aux événements), vous devez créer un compte. Vous vous engagez à :</p>
        <ul class="legal-list">
            <li>Fournir des informations exactes et à jour lors de votre inscription.</li>
            <li>Garder votre mot de passe confidentiel et ne pas le partager.</li>
            <li>Nous informer immédiatement de toute utilisation non autorisée de votre compte.</li>
            <li>Ne pas créer de faux comptes ou usurper l'identité d'un tiers.</li>
        </ul>
    </div>

    <div class="legal-section">
        <h2 class="legal-title">3. Demandes d'essai</h2>
        <p>Les demandes d'essai soumises via le site sont soumises aux conditions suivantes :</p>
        <ul class="legal-list">
            <li>Une demande d'essai est une demande de rendez-vous, non une réservation ferme.</li>
            <li>SuperCar Ltd se réserve le droit d'accepter ou de refuser toute demande.</li>
            <li>L'essai se déroule obligatoirement au siège social SuperCar à Ebène.</li>
            <li>Le demandeur doit être titulaire d'un permis de conduire valide.</li>
            <li>SuperCar Ltd peut annuler ou reporter un essai sans préavis en cas de force majeure.</li>
        </ul>
    </div>

    <div class="legal-section">
        <h2 class="legal-title">4. Inscriptions aux événements</h2>
        <p>Les inscriptions aux événements SuperCar sont soumises aux places disponibles. SuperCar Ltd se réserve le droit d'annuler ou de modifier un événement sans préavis. En cas d'annulation, les inscrits seront notifiés par email.</p>
    </div>

    <div class="legal-section">
        <h2 class="legal-title">5. Prix et informations véhicules</h2>
        <p>Les prix affichés sur le site sont donnés à titre indicatif et peuvent varier sans préavis. SuperCar Ltd ne saurait être tenu responsable d'erreurs ou d'omissions dans les informations présentées. Tout achat est soumis à un contrat de vente distinct.</p>
    </div>

    <div class="legal-section">
        <h2 class="legal-title">6. Comportement interdit</h2>
        <p>Il est strictement interdit d'utiliser ce site pour :</p>
        <ul class="legal-list">
            <li>Soumettre des informations fausses ou trompeuses.</li>
            <li>Tenter d'accéder à des zones non autorisées du site.</li>
            <li>Perturber le fonctionnement du site (attaques, spam, scripts automatisés).</li>
            <li>Collecter les données d'autres utilisateurs sans leur consentement.</li>
        </ul>
    </div>

    <div class="legal-section">
        <h2 class="legal-title">7. Modification des conditions</h2>
        <p>SuperCar Ltd se réserve le droit de modifier ces conditions à tout moment. Les modifications entrent en vigueur dès leur publication sur le site. Il vous appartient de consulter régulièrement cette page.</p>
    </div>

    <div class="legal-section">
        <h2 class="legal-title">8. Contact</h2>
        <p>Pour toute question concernant ces conditions d'utilisation, contactez-nous à : <strong>legal@supercar.mu</strong></p>
    </div>

</div>

<?php require_once __DIR__ . '/../../includes/footer.php'; ?>
