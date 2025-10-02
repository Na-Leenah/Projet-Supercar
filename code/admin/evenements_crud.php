<?php 
include("header.php");
include("connexion.php");

// Forcer l'encodage UTF-8 pour la connexion MySQL
mysqli_set_charset($bdd, "utf8mb4");
header('Content-Type: text/html; charset=utf-8');

// Supprimer
if(isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    mysqli_query($bdd,"DELETE FROM evenement WHERE idevent='$id'");
}

// Ajouter / Modifier
if(isset($_POST['submit'])){
    $id = !empty($_POST['idevent']) ? (int)$_POST['idevent'] : 0;
    $categorie = mysqli_real_escape_string($bdd,$_POST['categorie']);
    $evenement = mysqli_real_escape_string($bdd,$_POST['evenement']);
    $description = mysqli_real_escape_string($bdd,$_POST['description']);
    $image = mysqli_real_escape_string($bdd,$_POST['image']);
    $date_event = mysqli_real_escape_string($bdd,$_POST['date_event']);
    $statut = isset($_POST['statut']) ? mysqli_real_escape_string($bdd, $_POST['statut']) : 'a venir';

    if($id > 0){
        // On met à jour uniquement les champs remplis
        $updates = [];
        if(!empty($categorie)) $updates[] = "categorie='$categorie'";
        if(!empty($evenement)) $updates[] = "evenement='$evenement'";
        if(!empty($description)) $updates[] = "description='$description'";
        if(!empty($image)) $updates[] = "image='$image'";
        if(!empty($date_event)) $updates[] = "date_event='$date_event'";
        if(!empty($statut)) $updates[] = "statut='$statut'";

        if(!empty($updates)){
            $sql = "UPDATE evenement SET ".implode(", ",$updates)." WHERE idevent='$id'";
            mysqli_query($bdd,$sql);
        }
    } else {
        mysqli_query($bdd,"INSERT INTO evenement (categorie, evenement, description, image, date_event, statut) 
                           VALUES ('$categorie','$evenement','$description','$image','$date_event','$statut')");
    }
}

// Récupération
$res = mysqli_query($bdd,"SELECT * FROM evenement");
?>

<h1 class="mb-4">Gestion Événements</h1>

<form method="POST" class="mb-4 row g-2">
    <input type="hidden" name="idevent" id="idevent">
    <div class="col-md-2"><input type="text" name="categorie" id="categorie" placeholder="Catégorie" class="form-control"></div>
    <div class="col-md-2"><input type="text" name="evenement" id="evenement" placeholder="Événement" class="form-control"></div>
    <div class="col-md-3"><input type="text" name="description" id="description" placeholder="Description" class="form-control"></div>
    <div class="col-md-2"><input type="text" name="image" id="image" placeholder="Image URL" class="form-control"></div>
    <div class="col-md-2"><input type="date" name="date_event" id="date_event" class="form-control"></div>
    <div class="col-md-1">
        <select name="statut" id="statut" class="form-select">
            <option value="a venir">À venir</option>
            <option value="passe">Passé</option>
            <option value="annule">Annulé</option>
        </select>
    </div>
    <div class="col-md-12 mt-2"><button type="submit" name="submit" class="btn btn-success">Ajouter / Modifier</button></div>
</form>

<table class="table table-striped">
<thead>
<tr>
<th>ID</th><th>Catégorie</th><th>Événement</th><th>Description</th><th>Image</th><th>Date</th><th>Statut</th><th>Actions</th>
</tr>
</thead>
<tbody>
<?php while($e=mysqli_fetch_assoc($res)): ?>
<tr>
<td><?= $e['idevent'] ?></td>
<td><?= htmlspecialchars($e['categorie'], ENT_QUOTES, 'UTF-8') ?></td>
<td><?= htmlspecialchars($e['evenement'], ENT_QUOTES, 'UTF-8') ?></td>
<td><?= htmlspecialchars($e['description'], ENT_QUOTES, 'UTF-8') ?></td>
<td><?= !empty($e['image']) ? htmlspecialchars($e['image'], ENT_QUOTES, 'UTF-8') : '-' ?></td>
<td><?= $e['date_event'] ?></td>
<td><?= htmlspecialchars($e['statut'], ENT_QUOTES, 'UTF-8') ?></td>
<td>
<a href="#" class="btn btn-sm btn-primary edit-btn" 
data-id="<?= $e['idevent'] ?>" 
data-categorie="<?= htmlspecialchars($e['categorie'], ENT_QUOTES, 'UTF-8') ?>" 
data-evenement="<?= htmlspecialchars($e['evenement'], ENT_QUOTES, 'UTF-8') ?>" 
data-description="<?= htmlspecialchars($e['description'], ENT_QUOTES, 'UTF-8') ?>" 
data-image="<?= htmlspecialchars($e['image'], ENT_QUOTES, 'UTF-8') ?>" 
data-date="<?= $e['date_event'] ?>" 
data-statut="<?= $e['statut'] ?>">
<i class="bi bi-pencil"></i></a>
<a href="?delete=<?= $e['idevent'] ?>" class="btn btn-sm btn-danger"><i class="bi bi-trash"></i></a>
</td>
</tr>
<?php endwhile; ?>
</tbody>
</table>

<script>
document.querySelectorAll('.edit-btn').forEach(btn=>{
    btn.addEventListener('click',e=>{
        e.preventDefault();
        document.getElementById('idevent').value = btn.dataset.id;
        document.getElementById('categorie').value = btn.dataset.categorie;
        document.getElementById('evenement').value = btn.dataset.evenement;
        document.getElementById('description').value = btn.dataset.description;
        document.getElementById('image').value = btn.dataset.image;
        document.getElementById('date_event').value = btn.dataset.date;
        document.getElementById('statut').value = btn.dataset.statut;
    });
});
</script>

<?php include("footer.php"); ?>