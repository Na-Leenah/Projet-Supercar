<?php
include("header.php");
include("connexion.php");

// Forcer l'encodage UTF-8 pour bien gérer les caractères spéciaux
mysqli_set_charset($bdd, "utf8mb4");

// Supprimer
if(isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    mysqli_query($bdd,"DELETE FROM essai WHERE idessai='$id'");
}

// Ajouter / Modifier
if(isset($_POST['submit'])){
    $id = !empty($_POST['idessai']) ? (int)$_POST['idessai'] : 0;
    $iduser = (int)$_POST['iduser'];
    $idcar = (int)$_POST['idcar'];
    $date_essai = mysqli_real_escape_string($bdd,$_POST['date_essai']);
    $lieu_essai = mysqli_real_escape_string($bdd,$_POST['lieu_essai']);
    $heure_essai = mysqli_real_escape_string($bdd,$_POST['heure_essai']);
    $statut = mysqli_real_escape_string($bdd,$_POST['statut']);

    if($id > 0){
        // Mise à jour avec les champs remplis
        $updates = [];
        if(!empty($iduser)) $updates[] = "iduser='$iduser'";
        if(!empty($idcar)) $updates[] = "idcar='$idcar'";
        if(!empty($date_essai)) $updates[] = "date_essai='$date_essai'";
        if(!empty($lieu_essai)) $updates[] = "lieu_essai='$lieu_essai'";
        if(!empty($heure_essai)) $updates[] = "heure_essai='$heure_essai'";
        if(!empty($statut)) $updates[] = "statut='$statut'";

        if(!empty($updates)){
            $sql = "UPDATE essai SET ".implode(", ",$updates)." WHERE idessai='$id'";
            mysqli_query($bdd,$sql);
        }
    } else {
        mysqli_query($bdd,"INSERT INTO essai (iduser, idcar, date_essai, lieu_essai, heure_essai, statut) 
                           VALUES ('$iduser','$idcar','$date_essai','$lieu_essai','$heure_essai','$statut')");
    }
}

// Récupération
$res = mysqli_query($bdd,"SELECT e.*, v.modele, u.nom, u.prenom FROM essai e 
LEFT JOIN voitures v ON e.idcar=v.idcar 
LEFT JOIN utilisateurs u ON e.iduser=u.iduser");
?>

<h1 class="mb-4">Gestion Demandes d'Essai</h1>

<form method="POST" class="mb-4 row g-2">
    <input type="hidden" name="idessai" id="idessai">
    <div class="col-md-2">
        <select name="iduser" id="iduser" class="form-select">
            <?php
            $users = mysqli_query($bdd,"SELECT * FROM utilisateurs");
            while($u=mysqli_fetch_assoc($users)){
                echo "<option value='{$u['iduser']}'>".htmlspecialchars($u['nom']." ".$u['prenom'], ENT_QUOTES)."</option>";
            }
            ?>
        </select>
    </div>
    <div class="col-md-2">
        <select name="idcar" id="idcar" class="form-select">
            <?php
            $cars = mysqli_query($bdd,"SELECT * FROM voitures");
            while($c=mysqli_fetch_assoc($cars)){
                echo "<option value='{$c['idcar']}'>".htmlspecialchars($c['modele'], ENT_QUOTES)."</option>";
            }
            ?>
        </select>
    </div>
    <div class="col-md-2"><input type="date" name="date_essai" id="date_essai" class="form-control" required></div>
    <div class="col-md-2"><input type="text" name="lieu_essai" id="lieu_essai" placeholder="Lieu" class="form-control" required></div>
    <div class="col-md-2"><input type="time" name="heure_essai" id="heure_essai" class="form-control"></div>
    <div class="col-md-2">
        <select name="statut" id="statut" class="form-select">
            <option value="en attente">en attente</option>
            <option value="confirmé">confirmé</option>
            <option value="annulé">annulé</option>
        </select>
    </div>
    <div class="col-md-12 mt-2"><button type="submit" name="submit" class="btn btn-success">Ajouter / Modifier</button></div>
</form>

<table class="table table-striped">
<thead>
<tr>
<th>ID</th><th>Utilisateur</th><th>Voiture</th><th>Date</th><th>Lieu</th><th>Heure</th><th>Statut</th><th>Actions</th>
</tr>
</thead>
<tbody>
<?php while($e=mysqli_fetch_assoc($res)): ?>
<tr>
<td><?= $e['idessai'] ?></td>
<td><?= htmlspecialchars($e['nom'].' '.$e['prenom']) ?></td>
<td><?= htmlspecialchars($e['modele']) ?></td>
<td><?= $e['date_essai'] ?></td>
<td><?= htmlspecialchars($e['lieu_essai']) ?></td>
<td><?= $e['heure_essai'] ?></td>
<td><?= htmlspecialchars($e['statut']) ?></td>
<td>
<a href="#" class="btn btn-sm btn-primary edit-btn" 
data-id="<?= $e['idessai'] ?>" 
data-iduser="<?= $e['iduser'] ?>" 
data-idcar="<?= $e['idcar'] ?>" 
data-date="<?= $e['date_essai'] ?>" 
data-lieu="<?= htmlspecialchars($e['lieu_essai'], ENT_QUOTES) ?>" 
data-heure="<?= $e['heure_essai'] ?>" 
data-statut="<?= $e['statut'] ?>">
<i class="bi bi-pencil"></i></a>
<a href="?delete=<?= $e['idessai'] ?>" class="btn btn-sm btn-danger"><i class="bi bi-trash"></i></a>
</td>
</tr>
<?php endwhile; ?>
</tbody>
</table>

<script>
document.querySelectorAll('.edit-btn').forEach(btn=>{
    btn.addEventListener('click',e=>{
        e.preventDefault();
        document.getElementById('idessai').value = btn.dataset.id;
        document.getElementById('iduser').value = btn.dataset.iduser;
        document.getElementById('idcar').value = btn.dataset.idcar;
        document.getElementById('date_essai').value = btn.dataset.date;
        document.getElementById('lieu_essai').value = btn.dataset.lieu;
        document.getElementById('heure_essai').value = btn.dataset.heure;
        document.getElementById('statut').value = btn.dataset.statut;
    });
});
</script>

<?php include("footer.php"); ?>