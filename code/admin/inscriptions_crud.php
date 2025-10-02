<?php
include("header.php");
include("connexion.php");

// Forcer l'encodage UTF-8 pour bien gérer les caractères spéciaux
mysqli_set_charset($bdd, "utf8mb4");

// Supprimer
if(isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    mysqli_query($bdd,"DELETE FROM inscriptions_evenement WHERE idinscription='$id'");
}

// Ajouter / Modifier
if(isset($_POST['submit'])){
    $id = $_POST['idinscription'];
    $iduser = !empty($_POST['iduser']) ? (int)$_POST['iduser'] : "NULL";
    $idevent = (int)$_POST['idevent'];
    $nom = mysqli_real_escape_string($bdd,$_POST['nom']);
    $prenom = mysqli_real_escape_string($bdd,$_POST['prenom']);
    $email = mysqli_real_escape_string($bdd,$_POST['email']);
    $telephone = mysqli_real_escape_string($bdd,$_POST['telephone']);
    $date_inscription = date('Y-m-d H:i:s');
    $statut = mysqli_real_escape_string($bdd,$_POST['statut']);

    if($id){
        mysqli_query($bdd,"UPDATE inscriptions_evenement 
            SET iduser=$iduser, idevent='$idevent', nom='$nom', prenom='$prenom', email='$email', telephone='$telephone', statut='$statut'
            WHERE idinscription='$id'");
    } else {
        mysqli_query($bdd,"INSERT INTO inscriptions_evenement (iduser, idevent, nom, prenom, email, telephone, date_inscription, statut) 
            VALUES ($iduser,'$idevent','$nom','$prenom','$email','$telephone','$date_inscription','$statut')");
    }
}

// Récupération
$res = mysqli_query($bdd,"SELECT ie.*, e.evenement FROM inscriptions_evenement ie 
LEFT JOIN evenement e ON ie.idevent = e.idevent");
?>

<h1 class="mb-4">Gestion Inscriptions Événements</h1>

<form method="POST" class="mb-4 row g-2">
    <input type="hidden" name="idinscription" id="idinscription">
    <div class="col-md-2"><input type="text" name="nom" id="nom" placeholder="Nom" class="form-control" required></div>
    <div class="col-md-2"><input type="text" name="prenom" id="prenom" placeholder="Prénom" class="form-control" required></div>
    <div class="col-md-2"><input type="email" name="email" id="email" placeholder="Email" class="form-control" required></div>
    <div class="col-md-2"><input type="tel" name="telephone" id="telephone" placeholder="Téléphone" class="form-control" required></div>
    <div class="col-md-2">
        <select name="idevent" id="idevent" class="form-select">
            <?php
            $events = mysqli_query($bdd,"SELECT * FROM evenement");
            while($ev=mysqli_fetch_assoc($events)){
                echo "<option value='{$ev['idevent']}'>".htmlspecialchars($ev['evenement'])."</option>";
            }
            ?>
        </select>
    </div>
    <div class="col-md-2">
        <select name="statut" class="form-select">
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
<th>ID</th><th>Nom</th><th>Prénom</th><th>Email</th><th>Téléphone</th><th>Événement</th><th>Date inscription</th><th>Statut</th><th>Actions</th>
</tr>
</thead>
<tbody>
<?php while($ie=mysqli_fetch_assoc($res)): ?>
<tr>
<td><?= $ie['idinscription'] ?></td>
<td><?= htmlspecialchars($ie['nom']) ?></td>
<td><?= htmlspecialchars($ie['prenom']) ?></td>
<td><?= htmlspecialchars($ie['email']) ?></td>
<td><?= htmlspecialchars($ie['telephone']) ?></td>
<td><?= htmlspecialchars($ie['evenement']) ?></td>
<td><?= $ie['date_inscription'] ?></td>
<td><?= $ie['statut'] ?></td>
<td>
<a href="#" class="btn btn-sm btn-primary edit-btn" 
data-id="<?= $ie['idinscription'] ?>" 
data-nom="<?= htmlspecialchars($ie['nom']) ?>" 
data-prenom="<?= htmlspecialchars($ie['prenom']) ?>" 
data-email="<?= htmlspecialchars($ie['email']) ?>" 
data-telephone="<?= htmlspecialchars($ie['telephone']) ?>" 
data-idevent="<?= $ie['idevent'] ?>" 
data-statut="<?= $ie['statut'] ?>">
<i class="bi bi-pencil"></i></a>
<a href="?delete=<?= $ie['idinscription'] ?>" class="btn btn-sm btn-danger"><i class="bi bi-trash"></i></a>
</td>
</tr>
<?php endwhile; ?>
</tbody>
</table>

<script>
document.querySelectorAll('.edit-btn').forEach(btn=>{
    btn.addEventListener('click',e=>{
        e.preventDefault();
        document.getElementById('idinscription').value = btn.dataset.id;
        document.getElementById('nom').value = btn.dataset.nom;
        document.getElementById('prenom').value = btn.dataset.prenom;
        document.getElementById('email').value = btn.dataset.email;
        document.getElementById('telephone').value = btn.dataset.telephone;
        document.getElementById('idevent').value = btn.dataset.idevent;
        document.querySelector('select[name="statut"]').value = btn.dataset.statut;
    });
});
</script>

<?php include("footer.php"); ?>