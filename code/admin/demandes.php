<?php
require_once __DIR__ . '/../config/session.php';
require_once __DIR__ . '/../config/db.php';
exigerAdmin();
$pdo = getPDO();

// Changer le statut
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'], $_POST['statut'])) {
    $statuts_ok = ['en attente','confirmé','effectué','refusé'];
    $nouveau    = nettoyer($_POST['statut']);
    if (in_array($nouveau, $statuts_ok)) {
        $pdo->prepare("UPDATE demandes_essai SET statut=:s WHERE id=:id")->execute([':s'=>$nouveau,':id'=>(int)$_POST['id']]);
        flashMessage('Statut mis à jour.','succes');
    }
    rediriger('demandes.php');
}

$filtre = nettoyer($_GET['statut'] ?? '');
$where  = $filtre ? "WHERE d.statut = :s" : "";
$params = $filtre ? [':s' => $filtre] : [];

$stmt = $pdo->prepare(
    "SELECT d.*, CONCAT(m.nom,' ',v.nom) AS vehicule,
            CONCAT(u.prenom,' ',u.nom) AS client_compte
     FROM demandes_essai d
     JOIN voitures v ON d.voiture_id = v.id
     JOIN marques  m ON v.marque_id  = m.id
     JOIN utilisateurs u ON d.utilisateur_id = u.id
     $where
     ORDER BY d.created_at DESC"
);
$stmt->execute($params);
$demandes = $stmt->fetchAll();

$titrePage = 'Demandes d\'essai'; $adminPage = 'demandes';
require_once __DIR__ . '/includes/admin_header.php';
?>
<div class="admin-content">
<div class="admin-page-title"><h1>Demandes d'essai</h1><p><?= count($demandes) ?> demande(s)</p></div>

<div class="admin-toolbar">
    <span style="font-size:12px;color:var(--muted)">Filtrer :</span>
    <?php foreach (['' => 'Toutes','en attente'=>'En attente','confirmé'=>'Confirmé','effectué'=>'Effectué','refusé'=>'Refusé'] as $val => $lab): ?>
    <a href="demandes.php?statut=<?= urlencode($val) ?>"
       style="font-size:11px;padding:6px 14px;border-radius:4px;border:1px solid var(--border);color:<?= $filtre===$val?'#0d1117':'var(--muted)' ?>;background:<?= $filtre===$val?'var(--ac1)':'transparent' ?>">
        <?= $lab ?>
    </a>
    <?php endforeach; ?>
</div>

<div class="admin-table-card">
    <table class="admin-table">
        <thead><tr><th>Client</th><th>Email</th><th>Véhicule</th><th>Date souhaitée</th><th>Heure</th><th>Statut</th><th>Changer statut</th></tr></thead>
        <tbody>
        <?php if (empty($demandes)): ?><tr><td colspan="7" class="td-empty">Aucune demande</td></tr>
        <?php else: foreach ($demandes as $d): ?>
        <tr>
            <td><?= htmlspecialchars($d['prenom'].' '.$d['nom']) ?></td>
            <td style="font-size:11px"><?= htmlspecialchars($d['email']) ?></td>
            <td><?= htmlspecialchars($d['vehicule']) ?></td>
            <td><?= date('d/m/Y',strtotime($d['date_souhaitee'])) ?></td>
            <td style="font-size:11px"><?= htmlspecialchars($d['horaire']) ?></td>
            <td><span class="badge-statut statut-<?= urlencode($d['statut']) ?>"><?= $d['statut'] ?></span></td>
            <td>
                <form method="POST" style="display:flex;gap:6px;align-items:center">
                    <input type="hidden" name="id" value="<?= $d['id'] ?>">
                    <select name="statut" class="admin-select" style="padding:5px 8px;font-size:11px">
                        <?php foreach (['en attente','confirmé','effectué','refusé'] as $s): ?>
                        <option <?= $d['statut']===$s?'selected':'' ?>><?= $s ?></option>
                        <?php endforeach; ?>
                    </select>
                    <button type="submit" class="btn-admin-edit" style="padding:5px 10px;font-size:11px">OK</button>
                </form>
            </td>
        </tr>
        <?php endforeach; endif; ?>
        </tbody>
    </table>
</div>
</div>
<?php require_once __DIR__ . '/includes/admin_footer.php'; ?>
