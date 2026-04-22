<?php
require_once __DIR__ . '/../config/session.php';
require_once __DIR__ . '/../config/db.php';
exigerAdmin();
$pdo = getPDO();

// Suppression
if (isset($_GET['supprimer']) && is_numeric($_GET['supprimer'])) {
    $pdo->prepare("UPDATE voitures SET disponible = 0 WHERE id = :id")->execute([':id' => (int)$_GET['supprimer']]);
    flashMessage('Voiture retirée du catalogue.', 'succes');
    rediriger('voitures.php');
}

// Ajout / modification
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id          = (int)($_POST['id'] ?? 0);
    $marque_id   = (int)($_POST['marque_id']    ?? 0);
    $nom         = nettoyer($_POST['nom']         ?? '');
    $description = nettoyer($_POST['description'] ?? '');
    $prix        = (float)str_replace(',','.',($_POST['prix'] ?? 0));
    $performance = nettoyer($_POST['performance'] ?? '');
    $carburant   = nettoyer($_POST['carburant']   ?? '');
    $type_v      = nettoyer($_POST['type_vehicule'] ?? '');
    $annee       = (int)($_POST['annee']          ?? date('Y'));
    $image_path  = nettoyer($_POST['image_path']  ?? '');
    $en_vedette  = isset($_POST['en_vedette']) ? 1 : 0;
    $disponible  = isset($_POST['disponible'])  ? 1 : 0;

    if ($id > 0) {
        $pdo->prepare("UPDATE voitures SET marque_id=:m, nom=:n, description=:d, prix=:p, performance=:perf, carburant=:c, type_vehicule=:t, annee=:a, image_path=:img, en_vedette=:ev, disponible=:dispo WHERE id=:id")
            ->execute([':m'=>$marque_id,':n'=>$nom,':d'=>$description,':p'=>$prix,':perf'=>$performance,':c'=>$carburant,':t'=>$type_v,':a'=>$annee,':img'=>$image_path,':ev'=>$en_vedette,':dispo'=>$disponible,':id'=>$id]);
        flashMessage('Voiture mise à jour.','succes');
    } else {
        $pdo->prepare("INSERT INTO voitures (marque_id,nom,description,prix,performance,carburant,type_vehicule,annee,image_path,en_vedette,disponible) VALUES (:m,:n,:d,:p,:perf,:c,:t,:a,:img,:ev,1)")
            ->execute([':m'=>$marque_id,':n'=>$nom,':d'=>$description,':p'=>$prix,':perf'=>$performance,':c'=>$carburant,':t'=>$type_v,':a'=>$annee,':img'=>$image_path,':ev'=>$en_vedette]);
        flashMessage('Voiture ajoutée.','succes');
    }
    rediriger('voitures.php');
}

// Édition ?
$edit = null;
if (isset($_GET['editer']) && is_numeric($_GET['editer'])) {
    $edit = $pdo->prepare("SELECT * FROM voitures WHERE id=:id");
    $edit->execute([':id'=>(int)$_GET['editer']]);
    $edit = $edit->fetch();
}

$voitures = $pdo->query("SELECT v.*, m.nom AS marque FROM voitures v JOIN marques m ON v.marque_id=m.id ORDER BY m.nom, v.nom")->fetchAll();
$marques  = $pdo->query("SELECT id, nom FROM marques ORDER BY nom")->fetchAll();

$titrePage = 'Voitures'; $adminPage = 'voitures';
require_once __DIR__ . '/includes/admin_header.php';
?>
<div class="admin-content">
<div class="admin-page-title" style="display:flex;justify-content:space-between;align-items:center">
    <div><h1>Voitures</h1><p><?= count($voitures) ?> véhicules au total</p></div>
    <a href="voitures.php?ajouter=1" class="btn-admin-primary">+ Ajouter une voiture</a>
</div>

<?php if (isset($_GET['ajouter']) || $edit): ?>
<!-- FORMULAIRE ajout/édition -->
<div class="admin-form-card" style="margin-bottom:28px">
    <h2><?= $edit ? 'Modifier : '.htmlspecialchars($edit['nom']) : 'Ajouter une voiture' ?></h2>
    <form method="POST">
        <?php if ($edit): ?><input type="hidden" name="id" value="<?= $edit['id'] ?>"><?php endif; ?>
        <div class="form-row-2">
            <div class="form-group">
                <label>Marque <span class="req">*</span></label>
                <select name="marque_id" required>
                    <?php foreach ($marques as $m): ?>
                    <option value="<?= $m['id'] ?>" <?= ($edit && $edit['marque_id']==$m['id'])?'selected':'' ?>><?= htmlspecialchars($m['nom']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label>Nom du modèle <span class="req">*</span></label>
                <input type="text" name="nom" required placeholder="ex : Ghost" value="<?= htmlspecialchars($edit['nom'] ?? '') ?>">
            </div>
        </div>
        <div class="form-group">
            <label>Description</label>
            <textarea name="description" rows="3"><?= htmlspecialchars($edit['description'] ?? '') ?></textarea>
        </div>
        <div class="form-row-2">
            <div class="form-group">
                <label>Prix (Rs) <span class="req">*</span></label>
                <input type="number" name="prix" step="0.01" required value="<?= $edit['prix'] ?? '' ?>">
            </div>
            <div class="form-group">
                <label>Performance</label>
                <input type="text" name="performance" placeholder="ex : 563 ch" value="<?= htmlspecialchars($edit['performance'] ?? '') ?>">
            </div>
        </div>
        <div class="form-row-2">
            <div class="form-group">
                <label>Carburant</label>
                <select name="carburant">
                    <?php foreach (['Essence','Diesel','Hybride','Electrique'] as $c): ?>
                    <option <?= ($edit && $edit['carburant']==$c)?'selected':'' ?>><?= $c ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label>Type</label>
                <select name="type_vehicule">
                    <?php foreach (['Berline','SUV','Coupé','Cabriolet','Hypercar'] as $t): ?>
                    <option <?= ($edit && $edit['type_vehicule']==$t)?'selected':'' ?>><?= $t ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>
        <div class="form-row-2">
            <div class="form-group">
                <label>Année</label>
                <input type="number" name="annee" value="<?= $edit['annee'] ?? date('Y') ?>">
            </div>
            <div class="form-group">
                <label>Chemin image (ex: client/voitures/Bugatti/chiron-Bgt.jpg)</label>
                <input type="text" name="image_path" value="<?= htmlspecialchars($edit['image_path'] ?? '') ?>">
            </div>
        </div>
        <div style="display:flex;gap:20px;margin-bottom:16px">
            <label class="form-check"><input type="checkbox" name="en_vedette" <?= ($edit && $edit['en_vedette'])?'checked':'' ?>> En vedette sur l'accueil</label>
            <label class="form-check"><input type="checkbox" name="disponible" <?= (!$edit || $edit['disponible'])?'checked':'' ?>> Disponible</label>
        </div>
        <div style="display:flex;gap:10px">
            <button type="submit" class="btn-admin-primary">💾 Enregistrer</button>
            <a href="voitures.php" class="btn-admin-ghost">Annuler</a>
        </div>
    </form>
</div>
<?php endif; ?>

<!-- LISTE -->
<div class="admin-table-card">
    <div class="atc-head"><h2>Liste des voitures</h2></div>
    <table class="admin-table">
        <thead><tr><th>Marque</th><th>Modèle</th><th>Prix</th><th>Carburant</th><th>Vedette</th><th>Dispo</th><th>Actions</th></tr></thead>
        <tbody>
        <?php foreach ($voitures as $v): ?>
        <tr>
            <td><?= htmlspecialchars($v['marque']) ?></td>
            <td><?= htmlspecialchars($v['nom']) ?></td>
            <td><?= formaterPrix($v['prix']) ?></td>
            <td><?= htmlspecialchars($v['carburant']) ?></td>
            <td><?= $v['en_vedette'] ? '⭐' : '—' ?></td>
            <td><?= $v['disponible'] ? '<span style="color:var(--ac3)">✓</span>' : '<span style="color:#f87171">✗</span>' ?></td>
            <td style="display:flex;gap:6px">
                <a href="voitures.php?editer=<?= $v['id'] ?>" class="btn-admin-edit">Éditer</a>
                <a href="voitures.php?supprimer=<?= $v['id'] ?>" class="btn-admin-danger"
                   onclick="return confirm('Retirer cette voiture ?')">Retirer</a>
            </td>
        </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>
</div>
<?php require_once __DIR__ . '/includes/admin_footer.php'; ?>
