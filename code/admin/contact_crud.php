<?php 
include("header.php");
include("connexion.php");

// Supprimer
if(isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    mysqli_query($bdd,"DELETE FROM contact WHERE idcontact='$id'");
}

// Ajouter / Modifier
if(isset($_POST['submit'])){
    $id        = $_POST['idcontact'];
    $nom       = mysqli_real_escape_string($bdd,$_POST['nom']);
    $prenom    = mysqli_real_escape_string($bdd,$_POST['prenom']);
    $email     = mysqli_real_escape_string($bdd,$_POST['email']);
    $telephone = mysqli_real_escape_string($bdd,$_POST['telephone']);
    $message   = mysqli_real_escape_string($bdd,$_POST['message']);

    if($id){
        mysqli_query($bdd,"UPDATE contact 
            SET nom='$nom', prenom='$prenom', email='$email', telephone='$telephone', message='$message' 
            WHERE idcontact='$id'");
    } else {
        mysqli_query($bdd,"INSERT INTO contact (nom, prenom, email, telephone, message) 
            VALUES ('$nom','$prenom','$email','$telephone','$message')");
    }
}

// Récupération
$res = mysqli_query($bdd,"SELECT * FROM contact");
?>

<h1 class="mb-4">Gestion des Contacts</h1>

<form method="POST" class="mb-4 row g-2">
    <input type="hidden" name="idcontact" id="idcontact">
    <div class="col-md-2"><input type="text" name="nom" id="nom" placeholder="Nom" class="form-control" required></div>
    <div class="col-md-2"><input type="text" name="prenom" id="prenom" placeholder="Prénom" class="form-control" required></div>
    <div class="col-md-2"><input type="email" name="email" id="email" placeholder="Email" class="form-control" required></div>
    <div class="col-md-2"><input type="tel" name="telephone" id="telephone" placeholder="Téléphone" class="form-control"></div>
    <div class="col-md-4"><input type="text" name="message" id="message" placeholder="Message" class="form-control" required></div>
    <div class="col-md-12 mt-2"><button type="submit" name="submit" class="btn btn-success">Ajouter / Modifier</button></div>
</form>

<table class="table table-striped">
<thead>
<tr>
<th>ID</th><th>Nom</th><th>Prénom</th><th>Email</th><th>Téléphone</th><th>Message</th><th>Actions</th>
</tr>
</thead>
<tbody>
<?php while($c=mysqli_fetch_assoc($res)): ?>
<tr>
<td><?= $c['idcontact'] ?></td>
<td><?= htmlspecialchars($c['nom']) ?></td>
<td><?= htmlspecialchars($c['prenom']) ?></td>
<td><?= htmlspecialchars($c['email']) ?></td>
<td><?= htmlspecialchars($c['telephone']) ?></td>
<td><?= htmlspecialchars($c['message']) ?></td>
<td>
<a href="#" class="btn btn-sm btn-primary edit-btn" 
data-id="<?= $c['idcontact'] ?>" 
data-nom="<?= htmlspecialchars($c['nom']) ?>" 
data-prenom="<?= htmlspecialchars($c['prenom']) ?>" 
data-email="<?= htmlspecialchars($c['email']) ?>" 
data-telephone="<?= htmlspecialchars($c['telephone']) ?>" 
data-message="<?= htmlspecialchars($c['message']) ?>">
<i class="bi bi-pencil"></i></a>
<a href="?delete=<?= $c['idcontact'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Supprimer ce contact ?')"><i class="bi bi-trash"></i></a>
</td>
</tr>
<?php endwhile; ?>
</tbody>
</table>

<script>
document.querySelectorAll('.edit-btn').forEach(btn=>{
    btn.addEventListener('click',e=>{
        e.preventDefault();
        document.getElementById('idcontact').value = btn.dataset.id;
        document.getElementById('nom').value = btn.dataset.nom;
        document.getElementById('prenom').value = btn.dataset.prenom;
        document.getElementById('email').value = btn.dataset.email;
        document.getElementById('telephone').value = btn.dataset.telephone;
        document.getElementById('message').value = btn.dataset.message;
    });
});
</script>

<?php include("footer.php"); ?>
