<?php
include("header.php");
include("connexion.php");

// Suppression
if(isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    mysqli_query($bdd,"DELETE FROM voitures WHERE idcar='$id'");
}

// Ajout / Modification
if(isset($_POST['submit'])) {
    $id = $_POST['idcar'];
    $marque = mysqli_real_escape_string($bdd,$_POST['marque']);
    $modele = mysqli_real_escape_string($bdd,$_POST['modele']);
    $prix = mysqli_real_escape_string($bdd,$_POST['prix']);
    $performance = mysqli_real_escape_string($bdd,$_POST['performance']);
    $kilometrage = mysqli_real_escape_string($bdd,$_POST['kilometrage']);

    if($id) {
        // Update
        mysqli_query($bdd,"UPDATE voitures SET marque='$marque', modele='$modele', prix='$prix', performance='$performance', kilometrage='$kilometrage' WHERE idcar='$id'");
    } else {
        // Insert
        mysqli_query($bdd,"INSERT INTO voitures (marque, modele, prix, performance, kilometrage) VALUES ('$marque','$modele','$prix','$performance','$kilometrage')");
    }
}

// Récupération
$res = mysqli_query($bdd,"SELECT * FROM voitures");
?>

<h1 class="mb-4">Gestion Voitures</h1>

<!-- Formulaire ajout/modif -->
<form method="POST" class="mb-4 row g-2">
    <input type="hidden" name="idcar" id="idcar">
    <div class="col-md-2"><input type="text" name="marque" id="marque" placeholder="Marque" class="form-control" required></div>
    <div class="col-md-2"><input type="text" name="modele" id="modele" placeholder="Modèle" class="form-control" required></div>
    <div class="col-md-2"><input type="text" name="prix" id="prix" placeholder="Prix" class="form-control" required></div>
    <div class="col-md-2"><input type="text" name="performance" id="performance" placeholder="Performance" class="form-control"></div>
    <div class="col-md-2"><input type="text" name="kilometrage" id="kilometrage" placeholder="Kilométrage" class="form-control"></div>
    <div class="col-md-2"><button type="submit" name="submit" class="btn btn-success">Ajouter / Modifier</button></div>
</form>

<!-- Liste -->
<table class="table table-striped">
<thead>
<tr>
<th>ID</th><th>Marque</th><th>Modèle</th><th>Prix</th><th>Performance</th><th>Kilométrage</th><th>Actions</th>
</tr>
</thead>
<tbody>
<?php while($v = mysqli_fetch_assoc($res)): ?>
<tr>
<td><?= $v['idcar'] ?></td>
<td><?= htmlspecialchars($v['marque']) ?></td>
<td><?= htmlspecialchars($v['modele']) ?></td>
<td><?= $v['prix'] ?></td>
<td><?= $v['performance'] ?></td>
<td><?= $v['kilometrage'] ?></td>
<td>
    <a href="#" class="btn btn-sm btn-primary edit-btn" data-id="<?= $v['idcar'] ?>" data-marque="<?= htmlspecialchars($v['marque']) ?>" data-modele="<?= htmlspecialchars($v['modele']) ?>" data-prix="<?= $v['prix'] ?>" data-performance="<?= $v['performance'] ?>" data-kilometrage="<?= $v['kilometrage'] ?>"><i class="bi bi-pencil"></i></a>
    <a href="?delete=<?= $v['idcar'] ?>" class="btn btn-sm btn-danger"><i class="bi bi-trash"></i></a>
</td>
</tr>
<?php endwhile; ?>
</tbody>
</table>

<script>
document.querySelectorAll('.edit-btn').forEach(btn=>{
    btn.addEventListener('click',e=>{
        e.preventDefault();
        document.getElementById('idcar').value = btn.dataset.id;
        document.getElementById('marque').value = btn.dataset.marque;
        document.getElementById('modele').value = btn.dataset.modele;
        document.getElementById('prix').value = btn.dataset.prix;
        document.getElementById('performance').value = btn.dataset.performance;
        document.getElementById('kilometrage').value = btn.dataset.kilometrage;
    });
});
</script>

<?php include("footer.php"); ?>
