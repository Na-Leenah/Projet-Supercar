<?php
include("header.php");
include("connexion.php");

// Supprimer
if(isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    mysqli_query($bdd,"DELETE FROM utilisateurs WHERE iduser='$id'");
}

// Ajouter / Modifier
if(isset($_POST['submit'])){
    $id = $_POST['iduser'];
    $nom = mysqli_real_escape_string($bdd,$_POST['nom']);
    $prenom = mysqli_real_escape_string($bdd,$_POST['prenom']);
    $email = mysqli_real_escape_string($bdd,$_POST['email']);
    $telephone = mysqli_real_escape_string($bdd,$_POST['telephone']);
    $motdepasse = mysqli_real_escape_string($bdd,$_POST['motdepasse']);
    $statut = $_POST['statut'];

    if($id){
        mysqli_query($bdd,"UPDATE utilisateurs 
            SET nom='$nom', prenom='$prenom', email='$email', telephone='$telephone', motdepasse='$motdepasse', statut='$statut' 
            WHERE iduser='$id'");
    } else {
        mysqli_query($bdd,"INSERT INTO utilisateurs (nom, prenom, email, telephone, motdepasse, statut) 
            VALUES ('$nom','$prenom','$email','$telephone','$motdepasse','$statut')");
    }
}

// Récupération
$res = mysqli_query($bdd,"SELECT * FROM utilisateurs");
?>

<h1 class="mb-4">Gestion Utilisateurs</h1>

<form method="POST" class="mb-4 row g-2">
    <input type="hidden" name="iduser" id="iduser">
    <div class="col-md-2"><input type="text" name="nom" id="nom" placeholder="Nom" class="form-control" required></div>
    <div class="col-md-2"><input type="text" name="prenom" id="prenom" placeholder="Prénom" class="form-control" required></div>
    <div class="col-md-2"><input type="email" name="email" id="email" placeholder="Email" class="form-control" required></div>
    <div class="col-md-2"><input type="tel" name="telephone" id="telephone" placeholder="Téléphone" class="form-control" required></div>
    <div class="col-md-2"><input type="password" name="motdepasse" id="motdepasse" placeholder="Mot de passe" class="form-control" required></div>
    <div class="col-md-2">
        <select name="statut" class="form-select">
            <option value="user">user</option>
            <option value="admin">admin</option>
        </select>
    </div>
    <div class="col-md-12 mt-2"><button type="submit" name="submit" class="btn btn-success">Ajouter / Modifier</button></div>
</form>

<table class="table table-striped">
<thead>
<tr>
<th>ID</th><th>Nom</th><th>Prénom</th><th>Email</th><th>Téléphone</th><th>Statut</th><th>Actions</th>
</tr>
</thead>
<tbody>
<?php while($u=mysqli_fetch_assoc($res)): ?>
<tr>
<td><?= $u['iduser'] ?></td>
<td><?= htmlspecialchars($u['nom']) ?></td>
<td><?= htmlspecialchars($u['prenom']) ?></td>
<td><?= htmlspecialchars($u['email']) ?></td>
<td><?= htmlspecialchars($u['telephone']) ?></td>
<td><?= $u['statut'] ?></td>
<td>
<a href="#" class="btn btn-sm btn-primary edit-btn" 
data-id="<?= $u['iduser'] ?>" 
data-nom="<?= htmlspecialchars($u['nom']) ?>" 
data-prenom="<?= htmlspecialchars($u['prenom']) ?>" 
data-email="<?= htmlspecialchars($u['email']) ?>" 
data-telephone="<?= htmlspecialchars($u['telephone']) ?>" 
data-motdepasse="<?= htmlspecialchars($u['motdepasse']) ?>" 
data-statut="<?= $u['statut'] ?>">
<i class="bi bi-pencil"></i></a>
<a href="?delete=<?= $u['iduser'] ?>" class="btn btn-sm btn-danger"><i class="bi bi-trash"></i></a>
</td>
</tr>
<?php endwhile; ?>
</tbody>
</table>

<script>
document.querySelectorAll('.edit-btn').forEach(btn=>{
    btn.addEventListener('click',e=>{
        e.preventDefault();
        document.getElementById('iduser').value = btn.dataset.id;
        document.getElementById('nom').value = btn.dataset.nom;
        document.getElementById('prenom').value = btn.dataset.prenom;
        document.getElementById('email').value = btn.dataset.email;
        document.getElementById('telephone').value = btn.dataset.telephone;
        document.getElementById('motdepasse').value = btn.dataset.motdepasse;
        document.querySelector('select[name="statut"]').value = btn.dataset.statut;
    });
});
</script>

<?php include("footer.php"); ?>
