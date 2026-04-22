<?php
// ============================================================
//  client/pages/voitures.php — Galerie des voitures par marque
//  Chemin : wamp/www/supercar/client/pages/voitures.php
// ============================================================
require_once __DIR__ . '/../../config/session.php';
require_once __DIR__ . '/../../config/db.php';

$pdo = getPDO();

// ── Récupérer les marques et leurs voitures ──
// On récupère d'abord toutes les marques
$marques = $pdo->query(
    "SELECT id, nom, slogan, description FROM marques ORDER BY nom"
)->fetchAll();

// Pour chaque marque, on récupère ses voitures disponibles
$voitures_par_marque = [];
foreach ($marques as $m) {
    $stmt = $pdo->prepare(
        "SELECT v.id, v.nom, v.description, v.prix, v.performance,
                v.carburant, v.type_vehicule, v.annee, v.image_path, v.en_vedette,
                m.nom AS marque
         FROM voitures v
         JOIN marques m ON v.marque_id = m.id
         WHERE v.disponible = 1 AND v.marque_id = :mid
         ORDER BY v.nom"
    );
    $stmt->execute([':mid' => $m['id']]);
    $voitures_par_marque[$m['id']] = $stmt->fetchAll();
}

$titrePage  = 'Nos modèles';
$pageActive = 'voitures';
require_once __DIR__ . '/../../includes/header.php';
?>

<!-- Bannière de la page -->
<div class="page-banner">
    <h1 class="banner-title">Nos Modèles</h1>
</div>

<!-- ════════════════════════════════════════════
     UNE SECTION PAR MARQUE
     ════════════════════════════════════════════ -->
<?php foreach ($marques as $m): ?>
    <?php
    $voitures = $voitures_par_marque[$m['id']];
    if (empty($voitures)) continue; // Ignorer les marques sans voiture
    ?>

    <section class="marque-section">

        <!-- En-tête de la marque -->
        <div class="marque-header">
            <div class="marque-header-left">
                <h2 class="marque-nom"><?= htmlspecialchars($m['nom']) ?></h2>
                <?php if ($m['slogan']): ?>
                    <p class="marque-slogan"><?= htmlspecialchars($m['slogan']) ?></p>
                <?php endif; ?>
            </div>
            <div class="marque-count">
                <?= count($voitures) ?> modèle<?= count($voitures) > 1 ? 's' : '' ?>
            </div>
        </div>

        <!-- Grille des voitures de cette marque -->
        <div class="cars-grid">
            <?php foreach ($voitures as $v): ?>
                <?php
                $barClass = match($v['marque']) {
                    'Rolls-Royce' => 'bar-rr',
                    'Bugatti'     => 'bar-bug',
                    'Maserati'    => 'bar-mas',
                    'Porsche'     => 'bar-por',
                    default       => 'bar-rr',
                };
                ?>
                <div class="car-card">

                    <!-- Image du véhicule -->
                    <div style="position:relative">
                        <img src="../../<?= htmlspecialchars($v['image_path']) ?>"
                             alt="<?= htmlspecialchars($v['marque'] . ' ' . $v['nom']) ?>"
                             class="car-card-img"
                             onerror="this.src='../../assets/img/no-image.jpg'">

                        <?php if ($v['en_vedette']): ?>
                            <span class="badge badge-new">Nouveau</span>
                        <?php endif; ?>
                    </div>

                    <!-- Ligne couleur marque -->
                    <div class="car-card-bar <?= $barClass ?>"></div>

                    <!-- Infos -->
                    <div class="car-card-body">
                        <div class="car-brand"><?= htmlspecialchars($v['marque']) ?></div>
                        <div class="car-model"><?= htmlspecialchars($v['nom']) ?></div>

                        <div class="car-tags">
                            <span class="car-tag"><?= htmlspecialchars($v['type_vehicule']) ?></span>
                            <span class="car-tag"><?= htmlspecialchars($v['carburant']) ?></span>
                            <?php if ($v['performance']): ?>
                                <span class="car-tag"><?= htmlspecialchars($v['performance']) ?></span>
                            <?php endif; ?>
                        </div>

                        <div class="car-card-footer">
                            <span class="car-price">
                                <?= formaterPrix($v['prix']) ?>
                            </span>
                            <a href="voiture_detail.php?id=<?= $v['id'] ?>"
                               class="btn-detail">
                                Voir le détail →
                            </a>
                        </div>
                    </div>

                </div>
            <?php endforeach; ?>
        </div>

    </section>

<?php endforeach; ?>

<?php require_once __DIR__ . '/../../includes/footer.php'; ?>
