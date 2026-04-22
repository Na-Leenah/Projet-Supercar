<?php
// ============================================================
//  client/pages/voiture_detail.php — Fiche détail d'une voiture
//  Chemin : wamp/www/supercar/client/pages/voiture_detail.php
//  Accès  : voitures.php?id=3  (id passé en GET)
// ============================================================
require_once __DIR__ . '/../../config/session.php';
require_once __DIR__ . '/../../config/db.php';

$pdo = getPDO();

// ── Récupérer l'ID depuis l'URL et valider que c'est bien un entier ──
$id = (int)($_GET['id'] ?? 0);

// Si l'ID est invalide (0 ou négatif), on redirige vers la galerie
if ($id <= 0) {
    rediriger('voitures.php');
}

// ── Récupérer la voiture avec le nom de sa marque ──
$stmt = $pdo->prepare(
    "SELECT v.*, m.nom AS marque, m.description AS marque_description
     FROM voitures v
     JOIN marques m ON v.marque_id = m.id
     WHERE v.id = :id AND v.disponible = 1
     LIMIT 1"
);
$stmt->execute([':id' => $id]);
$voiture = $stmt->fetch();

// Si la voiture n'existe pas en base, on redirige
if (!$voiture) {
    rediriger('voitures.php');
}

// ── Variables pour header.php ──
$titrePage  = $voiture['marque'] . ' ' . $voiture['nom'];
$pageActive = 'voitures';   // Pour garder "Modèles" actif dans le nav
require_once __DIR__ . '/../../includes/header.php';
?>

<!-- Fil d'Ariane (breadcrumb) : Accueil > Modèles > Nom -->
<div class="breadcrumb">
    <a href="../../index.php">Accueil</a>
    <span>›</span>
    <a href="voitures.php">Modèles</a>
    <span>›</span>
    <span><?= htmlspecialchars($voiture['marque'] . ' ' . $voiture['nom']) ?></span>
</div>

<!-- ════════════════════════════════════════════
     SECTION DÉTAIL — Mise en page photo + infos
     Exactement comme la maquette : grande photo à gauche,
     infos + specs + bouton à droite
     ════════════════════════════════════════════ -->
<section class="detail-section">

    <!-- Photo grande à gauche -->
    <div class="detail-img">
        <img src="../../<?= htmlspecialchars($voiture['image_path']) ?>"
             alt="<?= htmlspecialchars($voiture['marque'] . ' ' . $voiture['nom']) ?>"
             onerror="this.src='../../assets/img/no-image.jpg'">
    </div>

    <!-- Infos à droite -->
    <div class="detail-info">

        <!-- Marque + Modèle -->
        <p class="detail-marque"><?= htmlspecialchars($voiture['marque']) ?></p>
        <h1 class="detail-nom"><?= htmlspecialchars($voiture['nom']) ?></h1>

        <!-- Description du modèle -->
        <p class="detail-desc">
            <?= htmlspecialchars($voiture['description']) ?>
        </p>

        <!-- Spécifications techniques -->
        <div class="detail-specs">

            <div class="spec-row spec-prix">
                <span class="spec-key">Prix</span>
                <span class="spec-val"><?= formaterPrix($voiture['prix']) ?></span>
            </div>

            <div class="spec-row">
                <span class="spec-key">Performance</span>
                <span class="spec-val">
                    <?= htmlspecialchars($voiture['performance'] ?? 'N/A') ?>
                </span>
            </div>

            <div class="spec-row">
                <span class="spec-key">Kilométrage</span>
                <span class="spec-val">
                    <?= number_format($voiture['kilometrage'], 0, '.', ' ') ?> km
                </span>
            </div>

            <div class="spec-row">
                <span class="spec-key">Carburant</span>
                <span class="spec-val"><?= htmlspecialchars($voiture['carburant']) ?></span>
            </div>

            <div class="spec-row">
                <span class="spec-key">Type</span>
                <span class="spec-val"><?= htmlspecialchars($voiture['type_vehicule']) ?></span>
            </div>

            <?php if ($voiture['annee']): ?>
            <div class="spec-row">
                <span class="spec-key">Année</span>
                <span class="spec-val"><?= $voiture['annee'] ?></span>
            </div>
            <?php endif; ?>

        </div>

        <!-- Bouton Réserver un essai -->
        <?php if (estConnecte()): ?>
            <!--
                Utilisateur connecté : lien direct vers essai.php
                avec marque et voiture_id pré-remplis en GET
            -->
            <a href="essai.php?voiture_id=<?= $voiture['id'] ?>"
               class="btn-primary btn-essai">
                Réserver un essai
            </a>
        <?php else: ?>
            <!--
                Non connecté : on redirige vers la connexion
                avec une note explicative discrète
            -->
            <a href="../../includes/connexion.php"
               class="btn-primary btn-essai">
                Réserver un essai
            </a>
            <p class="detail-login-note">
                Connexion requise pour réserver un essai
            </p>
        <?php endif; ?>

        <!-- Lien retour galerie -->
        <a href="voitures.php" class="btn-retour">
            ← Retour aux modèles
        </a>

    </div>
</section>

<?php require_once __DIR__ . '/../../includes/footer.php'; ?>
