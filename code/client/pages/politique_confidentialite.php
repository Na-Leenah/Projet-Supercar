<?php
// ============================================================
//  client/pages/politique_confidentialite.php
//  Chemin : wamp/www/supercar/client/pages/politique_confidentialite.php
// ============================================================
require_once __DIR__ . '/../../config/session.php';

$titrePage  = 'Politique de confidentialité';
$pageActive = '';
require_once __DIR__ . '/../../includes/header.php';
?>

<div class="page-banner">
    <p class="banner-eyebrow">Légal</p>
    <h1 class="banner-title">Politique de confidentialité</h1>
    <p class="banner-sub">Dernière mise à jour : janvier 2024</p>
</div>

<div class="legal-page">

    <div class="legal-section">
        <h2 class="legal-title">1. Responsable du traitement</h2>
        <p>SuperCar Ltd, dont le siège est situé à Ebène CyberCity, Île Maurice, est responsable du traitement de vos données personnelles collectées via ce site.</p>
    </div>

    <div class="legal-section">
        <h2 class="legal-title">2. Données collectées</h2>
        <p>Nous collectons les données suivantes lorsque vous utilisez nos services :</p>
        <ul class="legal-list">
            <li><strong>Création de compte :</strong> prénom, nom, adresse email, mot de passe (hashé), numéro de téléphone.</li>
            <li><strong>Demande d'essai :</strong> prénom, nom, email, téléphone, date et créneau souhaités, véhicule sélectionné.</li>
            <li><strong>Inscription événement :</strong> prénom, nom, email, téléphone.</li>
            <li><strong>Formulaire de contact :</strong> prénom, nom, email, sujet, message.</li>
        </ul>
    </div>

    <div class="legal-section">
        <h2 class="legal-title">3. Finalités du traitement</h2>
        <p>Vos données sont utilisées exclusivement pour :</p>
        <ul class="legal-list">
            <li>Gérer votre compte utilisateur et votre authentification.</li>
            <li>Traiter vos demandes d'essai et vous contacter pour confirmation.</li>
            <li>Gérer votre inscription aux événements SuperCar.</li>
            <li>Répondre à vos messages envoyés via le formulaire de contact.</li>
            <li>Améliorer nos services (statistiques anonymes uniquement).</li>
        </ul>
    </div>

    <div class="legal-section">
        <h2 class="legal-title">4. Conservation des données</h2>
        <p>Vos données sont conservées pour une durée maximale de <strong>3 ans</strong> à compter de votre dernière interaction avec nos services. Au-delà, elles sont supprimées ou anonymisées.</p>
    </div>

    <div class="legal-section">
        <h2 class="legal-title">5. Sécurité des données</h2>
        <p>Nous mettons en œuvre les mesures techniques appropriées pour protéger vos données :</p>
        <ul class="legal-list">
            <li>Mots de passe hashés avec l'algorithme <strong>bcrypt</strong> — jamais stockés en clair.</li>
            <li>Requêtes SQL préparées pour prévenir les injections SQL.</li>
            <li>Sessions PHP sécurisées.</li>
            <li>Accès à la base de données restreint aux serveurs autorisés.</li>
        </ul>
    </div>

    <div class="legal-section">
        <h2 class="legal-title">6. Partage des données</h2>
        <p>Nous ne vendons, ne louons et ne partageons vos données personnelles avec aucun tiers, sauf obligation légale ou avec votre consentement explicite.</p>
    </div>

    <div class="legal-section">
        <h2 class="legal-title">7. Vos droits</h2>
        <p>Conformément aux lois applicables, vous disposez des droits suivants sur vos données :</p>
        <ul class="legal-list">
            <li><strong>Droit d'accès :</strong> obtenir une copie de vos données.</li>
            <li><strong>Droit de rectification :</strong> corriger des données inexactes.</li>
            <li><strong>Droit à l'effacement :</strong> demander la suppression de vos données.</li>
            <li><strong>Droit d'opposition :</strong> vous opposer au traitement de vos données.</li>
        </ul>
        <p>Pour exercer ces droits, contactez-nous à : <strong>privacy@supercar.mu</strong></p>
    </div>

    <div class="legal-section">
        <h2 class="legal-title">8. Cookies</h2>
        <p>Ce site utilise uniquement des cookies de session strictement nécessaires au fonctionnement de l'authentification. Aucun cookie publicitaire ou de tracking n'est utilisé.</p>
    </div>

</div>

<?php require_once __DIR__ . '/../../includes/footer.php'; ?>
