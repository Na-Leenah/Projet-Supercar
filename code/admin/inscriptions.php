<?php
require_once __DIR__ . '/../config/session.php';
require_once __DIR__ . '/../config/db.php';
exigerAdmin();
$pdo = getPDO();

if (isset($_GET['supprimer']) && is_numeric($_GET['supprimer'])) {
    $pdo->prepare("DELETE FROM inscriptions_evenements WHERE id=:id")->execute([':id'=>(int)$_GET['supprimer']]);
    flashMessage('Inscription supprimée.','succes'); rediriger('inscriptions.php');
}

$filtre_ev = (int)($_GET['evenement_id'] ?? 0);
$where  = $filtre_ev ? "WHERE i.evenement_id = :ev" : "";
$params = $filtre_ev ? [':ev' => $filtre_ev] : [];

$stmt = $pdo->prepare("SELECT i.*, e.titre AS evenement FROM inscriptions_evenements i JOIN evenements e ON i.evenement_id=e.id $where ORDER BY i.date_inscription DESC");
$stmt->execute($params);
$inscriptions = $stmt->fetchAll();

$evenements_liste = $pdo->query("SELECT id, titre FROM evenements ORDER BY date_event DESC")->fetchAll();

$titrePage = 'Inscriptions'; $adminPage = 'inscriptions';
require_once __DIR__ . '/includes/admin_header.php';
?>
<div class="admin-content">
<div class="admin-page-title"><h1>Inscriptions aux événements</h1><p><?= count($inscriptions) ?> inscription(s)</p></div>
<div class="admin-toolbar">
    <select class="admin-select" onchange="window.location='inscriptions.php?evenement_id='+this.value">
        <option value="0">Tous les événements</option>
        <?php foreach ($evenements_liste as $ev): ?>
        <option value="<?= $ev['id'] ?>" <?= $filtre_ev===$ev['id']?'selected':'' ?>><?= htmlspecialchars($ev['titre']) ?></option>
        <?php endforeach; ?>
    </select>
</div>
<div class="admin-table-card">
    <table class="admin-table">
        <thead><tr><th>Événement</th><th>Inscrit</th><th>Email</th><th>Téléphone</th><th>Date inscription</th><th>Statut</th><th>Action</th></tr></thead>
        <tbody>
        <?php if (empty($inscriptions)): ?><tr><td colspan="7" class="td-empty">Aucune inscription</td></tr>
        <?php else: foreach ($inscriptions as $i): ?>
        <tr>
            <td style="font-size:11px"><?= htmlspecialchars($i['evenement']) ?></td>
            <td><?= htmlspecialchars($i['prenom'].' '.$i['nom']) ?></td>
            <td style="font-size:11px"><?= htmlspecialchars($i['email']) ?></td>
            <td style="font-size:11px"><?= htmlspecialchars($i['telephone']?:'—') ?></td>
            <td><?= date('d/m/Y',strtotime($i['date_inscription'])) ?></td>
            <td><span class="badge-statut statut-<?= urlencode($i['statut']) ?>"><?= $i['statut'] ?></span></td>
            <td><a href="inscriptions.php?supprimer=<?= $i['id'] ?>" class="btn-admin-danger" onclick="return confirm('Supprimer ?')">Supprimer</a></td>
        </tr>
        <?php endforeach; endif; ?>
        </tbody>
    </table>
</div>
</div>
<?php require_once __DIR__ . '/includes/admin_footer.php'; ?>
