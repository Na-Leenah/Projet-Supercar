<?php
require_once __DIR__ . '/../config/session.php';
require_once __DIR__ . '/../config/db.php';
exigerAdmin();
$pdo = getPDO();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id      = (int)($_POST['id'] ?? 0);
    $nom     = nettoyer($_POST['nom']     ?? '');
    $slogan  = nettoyer($_POST['slogan']  ?? '');
    $desc    = nettoyer($_POST['description'] ?? '');
    if ($id > 0) {
        $pdo->prepare("UPDATE marques SET nom=:n,slogan=:s,description=:d WHERE id=:id")->execute([':n'=>$nom,':s'=>$slogan,':d'=>$desc,':id'=>$id]);
        flashMessage('Marque mise à jour.','succes');
    } else {
        $pdo->prepare("INSERT INTO marques (nom,slogan,description) VALUES (:n,:s,:d)")->execute([':n'=>$nom,':s'=>$slogan,':d'=>$desc]);
        flashMessage('Marque ajoutée.','succes');
    }
    rediriger('marques.php');
}

$edit = null;
if (isset($_GET['editer']) && is_numeric($_GET['editer'])) {
    $s=$pdo->prepare("SELECT * FROM marques WHERE id=:id");$s->execute([':id'=>(int)$_GET['editer']]);$edit=$s->fetch();
}

$marques = $pdo->query("SELECT m.*, COUNT(v.id) AS nb_voitures FROM marques m LEFT JOIN voitures v ON v.marque_id=m.id GROUP BY m.id ORDER BY m.nom")->fetchAll();

$titrePage = 'Marques'; $adminPage = 'marques';
require_once __DIR__ . '/includes/admin_header.php';
?>
<div class="admin-content">
<div class="admin-page-title" style="display:flex;justify-content:space-between;align-items:center">
    <div><h1>Marques</h1><p><?= count($marques) ?> marques</p></div>
    <a href="marques.php?ajouter=1" class="btn-admin-primary">+ Ajouter</a>
</div>

<?php if (isset($_GET['ajouter']) || $edit): ?>
<div class="admin-form-card" style="margin-bottom:28px">
    <h2><?= $edit ? 'Modifier : '.htmlspecialchars($edit['nom']) : 'Ajouter une marque' ?></h2>
    <form method="POST">
        <?php if ($edit): ?><input type="hidden" name="id" value="<?= $edit['id'] ?>"><?php endif; ?>
        <div class="form-group"><label>Nom <span class="req">*</span></label><input type="text" name="nom" required value="<?= htmlspecialchars($edit['nom'] ?? '') ?>"></div>
        <div class="form-group"><label>Slogan</label><input type="text" name="slogan" value="<?= htmlspecialchars($edit['slogan'] ?? '') ?>"></div>
        <div class="form-group"><label>Description</label><textarea name="description" rows="3"><?= htmlspecialchars($edit['description'] ?? '') ?></textarea></div>
        <div style="display:flex;gap:10px"><button type="submit" class="btn-admin-primary">💾 Enregistrer</button><a href="marques.php" class="btn-admin-ghost">Annuler</a></div>
    </form>
</div>
<?php endif; ?>

<div class="admin-table-card">
    <table class="admin-table">
        <thead><tr><th>Marque</th><th>Slogan</th><th>Nb voitures</th><th>Action</th></tr></thead>
        <tbody>
        <?php foreach ($marques as $m): ?>
        <tr>
            <td><?= htmlspecialchars($m['nom']) ?></td>
            <td style="font-size:11px;color:var(--muted)"><?= htmlspecialchars($m['slogan']?:'—') ?></td>
            <td><?= $m['nb_voitures'] ?></td>
            <td><a href="marques.php?editer=<?= $m['id'] ?>" class="btn-admin-edit">Éditer</a></td>
        </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>
</div>
<?php require_once __DIR__ . '/includes/admin_footer.php'; ?>
