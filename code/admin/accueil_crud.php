<?php
include("header.php");
include("connexion.php");

mysqli_set_charset($bdd, "utf8mb4");

// Supprimer (si jamais tu veux gérer suppression d'une section)
if(isset($_GET['delete'])) {
    $section = mysqli_real_escape_string($bdd, $_GET['delete']);
    mysqli_query($bdd, "DELETE FROM accueil WHERE section='$section'");
}

// Ajouter / Modifier
if(isset($_POST['submit'])){
    $section = mysqli_real_escape_string($bdd, $_POST['section']);
    $texte = mysqli_real_escape_string($bdd, $_POST['texte']);
    $image = mysqli_real_escape_string($bdd, $_POST['image']);

    $check = mysqli_query($bdd, "SELECT section FROM accueil WHERE section='$section'");
    if(mysqli_num_rows($check) > 0){
        mysqli_query($bdd, "UPDATE accueil SET texte='$texte', image='$image' WHERE section='$section'");
    } else {
        mysqli_query($bdd, "INSERT INTO accueil (section, texte, image) VALUES ('$section', '$texte', '$image')");
    }
}

// Récupération
$res = mysqli_query($bdd, "SELECT * FROM accueil");
?>

<h1 class="mb-4">Gestion Page d'Accueil</h1>

<form method="POST" class="mb-4 row g-2">
    <div class="col-md-2">
        <select name="section" id="section" class="form-select">
            <option value="header">Header</option>
            <option value="main">Main</option>
            <option value="voitures">Voitures</option>
            <option value="evenements">Événements</option>
        </select>
    </div>
    <div class="col-md-4">
        <textarea name="texte" id="texte" placeholder="Texte" class="form-control"></textarea>
    </div>
    <div class="col-md-4">
        <input type="text" name="image" id="image" placeholder="Image URL" class="form-control">
    </div>
    <div class="col-md-2">
        <button type="submit" name="submit" class="btn btn-success w-100">Ajouter / Modifier</button>
    </div>
</form>

<table class="table table-striped">
<thead>
<tr>
<th>Section</th><th>Texte</th><th>Image</th><th>Actions</th>
</tr>
</thead>
<tbody>
<?php while($row=mysqli_fetch_assoc($res)): ?>
<tr>
<td><?= htmlspecialchars($row['section'], ENT_QUOTES, 'UTF-8') ?></td>
<td><?= nl2br(htmlspecialchars($row['texte'], ENT_QUOTES, 'UTF-8')) ?></td>
<td><?= !empty($row['image']) ? htmlspecialchars($row['image'], ENT_QUOTES, 'UTF-8') : '-' ?></td>
<td>
<a href="#" class="btn btn-sm btn-primary edit-btn"
   data-section="<?= $row['section'] ?>"
   data-texte="<?= htmlspecialchars($row['texte'], ENT_QUOTES, 'UTF-8') ?>"
   data-image="<?= htmlspecialchars($row['image'], ENT_QUOTES, 'UTF-8') ?>">
<i class="bi bi-pencil"></i></a>
<a href="?delete=<?= $row['section'] ?>" class="btn btn-sm btn-danger"><i class="bi bi-trash"></i></a>
</td>
</tr>
<?php endwhile; ?>
</tbody>
</table>

<script>
document.querySelectorAll('.edit-btn').forEach(btn=>{
    btn.addEventListener('click',e=>{
        e.preventDefault();
        document.getElementById('section').value = btn.dataset.section;
        document.getElementById('texte').value = btn.dataset.texte;
        document.getElementById('image').value = btn.dataset.image;
    });
});
</script>

<?php include("footer.php"); ?>