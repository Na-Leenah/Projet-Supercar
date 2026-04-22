<?php
require_once __DIR__ . '/../config/session.php';
require_once __DIR__ . '/../config/db.php';
exigerAdmin();
$pdo = getPDO();

if (isset($_GET['supprimer']) && is_numeric($_GET['supprimer'])) {
    $pdo->prepare("UPDATE evenements SET actif = 0 WHERE id=:id")->execute([':id'=>(int)$_GET['supprimer']]);
    flashMessage('Événement désactivé.','succes'); rediriger('evenements.php');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id     = (int)($_POST['id'] ?? 0);
    $data   = [
        ':titre'  => nettoyer($_POST['titre']  ?? ''),
        ':desc'   => nettoyer($_POST['description'] ?? ''),
        ':lieu'   => nettoyer($_POST['lieu']   ?? ''),
        ':date'   => $_POST['date_event']       ?? '',
        ':hdeb'   => $_POST['heure_debut']      ?? '',
        ':hfin'   => $_POST['heure_fin']        ?? '',
        ':type'   => nettoyer($_POST['type_event']   ?? 'Salon'),
        ':insc'   => isset($_POST['inscription_requise']) ? 1 : 0,
        ':places' => (isset($_POST['places_max']) && $_POST['places_max'] !== '') ? (int)$_POST['places_max'] : null,
    ];
    if ($id > 0) {
        $data[':id'] = $id;
        $pdo->prepare("UPDATE evenements SET titre=:titre,description=:desc,lieu=:lieu,date_event=:date,heure_debut=:hdeb,heure_fin=:hfin,type_event=:type,inscription_requise=:insc,places_max=:places WHERE id=:id")->execute($data);
        flashMessage('Événement mis à jour.','succes');
    } else {
        $pdo->prepare("INSERT INTO evenements (titre,description,lieu,date_event,heure_debut,heure_fin,type_event,inscription_requise,places_max,image_path,actif) VALUES (:titre,:desc,:lieu,:date,:hdeb,:hfin,:type,:insc,:places,'client/evenements/evenement.jpg',1)")->execute($data);
        flashMessage('Événement ajouté.','succes');
    }
    rediriger('evenements.php');
}

$edit = null;
if (isset($_GET['editer']) && is_numeric($_GET['editer'])) {
    $s = $pdo->prepare("SELECT * FROM evenements WHERE id=:id"); $s->execute([':id'=>(int)$_GET['editer']]); $edit = $s->fetch();
}

$evenements = $pdo->query("SELECT e.*, (SELECT COUNT(*) FROM inscriptions_evenements i WHERE i.evenement_id=e.id) AS nb_inscrits FROM evenements e ORDER BY e.date_event DESC")->fetchAll();
$mois = ['Jan','Fév','Mar','Avr','Mai','Jun','Jul','Aoû','Sep','Oct','Nov','Déc'];

$titrePage = 'Événements'; $adminPage = 'evenements';
require_once __DIR__ . '/includes/admin_header.php';
?>
<div class="admin-content">
<div class="admin-page-title" style="display:flex;justify-content:space-between;align-items:center">
    <div><h1>Événements</h1><p><?= count($evenements) ?> événements</p></div>
    <a href="evenements.php?ajouter=1" class="btn-admin-primary">+ Ajouter</a>
</div>

<?php if (isset($_GET['ajouter']) || $edit): ?>
<div class="admin-form-card" style="margin-bottom:28px">
    <h2><?= $edit ? 'Modifier : '.htmlspecialchars($edit['titre']) : 'Ajouter un événement' ?></h2>
    <form method="POST">
        <?php if ($edit): ?><input type="hidden" name="id" value="<?= $edit['id'] ?>"><?php endif; ?>
        <div class="form-group"><label>Titre <span class="req">*</span></label><input type="text" name="titre" required value="<?= htmlspecialchars($edit['titre'] ?? '') ?>"></div>
        <div class="form-group"><label>Description</label><textarea name="description" rows="3"><?= htmlspecialchars($edit['description'] ?? '') ?></textarea></div>
        <div class="form-row-2">
            <div class="form-group"><label>Lieu</label><input type="text" name="lieu" value="<?= htmlspecialchars($edit['lieu'] ?? '') ?>"></div>
            <div class="form-group"><label>Type</label>
                <select name="type_event"><?php foreach (['Salon','Lancement','Portes ouvertes','Promotion','VIP'] as $t): ?><option <?= ($edit&&$edit['type_event']==$t)?'selected':'' ?>><?= $t ?></option><?php endforeach; ?></select>
            </div>
        </div>
        <div class="form-row-2">
            <div class="form-group"><label>Date</label><input type="date" name="date_event" value="<?= $edit['date_event'] ?? '' ?>"></div>
            <div class="form-group"><label>Places max (vide = illimité)</label><input type="number" name="places_max" value="<?= $edit['places_max'] ?? '' ?>"></div>
        </div>
        <div class="form-row-2">
            <div class="form-group"><label>Heure début</label><input type="time" name="heure_debut" value="<?= $edit['heure_debut'] ?? '' ?>"></div>
            <div class="form-group"><label>Heure fin</label><input type="time" name="heure_fin" value="<?= $edit['heure_fin'] ?? '' ?>"></div>
        </div>
        <label class="form-check" style="margin-bottom:16px"><input type="checkbox" name="inscription_requise" <?= ($edit&&$edit['inscription_requise'])?'checked':'' ?>> Inscription obligatoire</label>
        <div style="display:flex;gap:10px"><button type="submit" class="btn-admin-primary">💾 Enregistrer</button><a href="evenements.php" class="btn-admin-ghost">Annuler</a></div>
    </form>
</div>
<?php endif; ?>

<div class="admin-table-card">
    <div class="atc-head"><h2>Liste des événements</h2></div>
    <table class="admin-table">
        <thead><tr><th>Titre</th><th>Date</th><th>Lieu</th><th>Type</th><th>Inscrits</th><th>Actif</th><th>Actions</th></tr></thead>
        <tbody>
        <?php foreach ($evenements as $ev): ?>
        <tr>
            <td><?= htmlspecialchars($ev['titre']) ?></td>
            <td><?= date('d/m/Y',strtotime($ev['date_event'])) ?></td>
            <td><?= htmlspecialchars($ev['lieu']) ?></td>
            <td><?= htmlspecialchars($ev['type_event']) ?></td>
            <td><?= $ev['nb_inscrits'] ?><?= $ev['places_max'] ? '/'.$ev['places_max'] : '' ?></td>
            <td><?= $ev['actif'] ? '<span style="color:var(--ac3)">✓</span>' : '<span style="color:#f87171">✗</span>' ?></td>
            <td style="display:flex;gap:6px">
                <a href="evenements.php?editer=<?= $ev['id'] ?>" class="btn-admin-edit">Éditer</a>
                <a href="evenements.php?supprimer=<?= $ev['id'] ?>" class="btn-admin-danger" onclick="return confirm('Désactiver ?')">Désactiver</a>
            </td>
        </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>
</div>
<?php require_once __DIR__ . '/includes/admin_footer.php'; ?>
