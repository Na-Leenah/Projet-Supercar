<?php
// ============================================================
//  index.php — Page d'accueil SuperCar
//  Chemin : wamp/www/supercar/index.php
// ============================================================
require_once __DIR__ . '/config/session.php';
require_once __DIR__ . '/config/db.php';

$pdo = getPDO();

// Récupère les types d'événements distincts pour affichage en cards
$stmt_types = $pdo->query(
    "SELECT DISTINCT type_event
     FROM evenements
     WHERE actif = 1
     ORDER BY type_event ASC"
);
$types_evenements = $stmt_types->fetchAll(PDO::FETCH_COLUMN);

// Message de déconnexion
$msgDeconnexion = isset($_GET['deconnecte']) ? 'Vous avez été déconnecté.' : '';

$titrePage  = 'Accueil';
$pageActive = 'accueil';
require_once __DIR__ . '/includes/header.php';
?>

<!-- ════════════════════════════════════════════
     SECTION 1 — HERO
     ════════════════════════════════════════════ -->
<section class="hero-section"
         style="background-image: url('client/header.jpg');">
    <div class="hero-overlay"></div>
    <div class="hero-content">
        <p class="hero-eyebrow">Bienvenue sur SuperCar, votre portail de voitures de luxe et événements exclusifs.</p>
        <h1 class="hero-title">
            Supercar<br>
            Super<span class="grad">Class</span>
        </h1>
        <p class="hero-sub">
            Véhicules neufs des plus grandes marques mondiales.<br>
            Une expérience sur-mesure depuis 2009.
        </p>
        <div class="hero-btns">
            <a href="client/pages/voitures.php" class="btn-primary">
                Découvrir les modèles
            </a>
            <?php if (estConnecte()): ?>
                <a href="client/pages/essai.php" class="btn-ghost">
                    Demande d'essai
                </a>
            <?php else: ?>
                <a href="includes/connexion.php" class="btn-ghost">
                    Demande d'essai
                </a>
            <?php endif; ?>
        </div>
    </div>
</section>

<!-- ════════════════════════════════════════════
     SECTION 2 — À PROPOS (texte simple, sans stats)
     ════════════════════════════════════════════ -->
<section class="about-section">

    <div class="about-text-only">
        <p class="section-eyebrow">À propos</p>
        <h2 class="section-title">
            L'excellence automobile<br>au cœur de Maurice
        </h2>
        <p class="about-text">
            SuperCar est un concessionnaire fictif de véhicules de prestige créé dans le
            cadre d'un projet de formation BTS SIO SLAM. Il représente quatre grandes maisons
            automobiles mondiales : Rolls-Royce, Bugatti, Maserati et Porsche.
        </p>
        <p class="about-text" style="margin-top:14px">
            L'application web permet aux visiteurs de consulter le catalogue des véhicules
            disponibles, de s'inscrire aux événements organisés par le concessionnaire, et
            de soumettre une demande d'essai pour le véhicule de leur choix. Un espace
            d'administration complet permet à l'équipe de gérer l'ensemble du contenu.
        </p>
    </div>

</section>

<!-- ════════════════════════════════════════════
     SECTION 3 — ÉVÉNEMENTS
     Cards simples avec juste le nom de l'événement
     ════════════════════════════════════════════ -->
    <style>
    .events-section .events-grid{display:flex;flex-wrap:wrap;gap:18px;margin-top:18px}
    .events-section .ev-type-card{display:block;background:#ffffff;border-radius:12px;padding:22px 18px;flex:0 1 calc(33.333% - 18px);box-shadow:0 8px 24px rgba(0,0,0,0.08);text-decoration:none;color:inherit;transition:transform .18s ease,box-shadow .18s ease}
    .events-section .ev-type-card-inner{display:flex;align-items:center;justify-content:center;height:80px}
    .events-section .ev-type-name{font-size:1.12rem;font-weight:700;text-align:center;color:#111}
    .events-section .ev-type-card:hover{transform:translateY(-6px);box-shadow:0 18px 40px rgba(0,0,0,0.12)}
    @media (max-width:900px){.events-section .ev-type-card{flex:0 1 calc(50% - 18px)}}
    @media (max-width:520px){.events-section .ev-type-card{flex:0 1 100%}}
    </style>
<section class="events-section">

    <div class="section-head">
        <div>
            <h2 class="section-title">Nos Événements</h2>
        </div>
        <a href="client/pages/evenements.php" class="btn-outline" style="margin-top:16px">
            Voir tous les événements →
        </a>
    </div>

    <div class="events-grid">
        <?php if (empty($types_evenements)): ?>
            <p class="no-data">Aucun type d'événement disponible pour le moment.</p>

        <?php else: ?>
            <?php foreach ($types_evenements as $type): ?>
                <a href="client/pages/evenements.php?type=<?= urlencode($type) ?>" class="ev-type-card">
                    <div class="ev-type-card-inner">
                        <div class="ev-type-name">
                            <?= htmlspecialchars($type) ?>
                        </div>
                    </div>
                </a>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>

</section>

<!-- ════════════════════════════════════════════
     SECTION 4 — NOS MARQUES
     ════════════════════════════════════════════ -->
<section class="brands-section">

    <div class="section-head">
        <div>
            <p class="section-eyebrow">Nos marques</p>
            <h2 class="section-title">Quatre maisons d'exception</h2>
            <p class="section-sub">Rolls-Royce · Bugatti · Maserati · Porsche</p>
        </div>
        <a href="client/pages/voitures.php" class="btn-outline">
            Voir tous les modèles →
        </a>
    </div>

    <div class="brands-collage">
        <img src="client/voitures.jpg"
             alt="Rolls-Royce, Maserati, Porsche, Bugatti"
             class="brands-photo">
        <div class="brands-labels">
            <div class="brand-label">Rolls-Royce</div>
            <div class="brand-label">Maserati</div>
            <div class="brand-label">Porsche</div>
            <div class="brand-label">Bugatti</div>
        </div>
    </div>

    <?php if ($msgDeconnexion): ?>
        <p class="msg-deconnexion"><?= htmlspecialchars($msgDeconnexion) ?></p>
    <?php endif; ?>

</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
