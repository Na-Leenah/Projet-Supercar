<?php
session_start();
include("connexion.php");

// Vérifier si l'utilisateur est connecté
if(!isset($_SESSION['iduser'])){
    header("Location: index.php");
    exit;
}

$iduser = $_SESSION['iduser'];

// Infos utilisateur
$sqlUser = "SELECT nom, prenom FROM utilisateurs WHERE iduser='$iduser'";
$resUser = mysqli_query($bdd, $sqlUser);
$user = mysqli_fetch_assoc($resUser);
$nom = $user['nom'] ?? '';
$prenom = $user['prenom'] ?? '';

// Récupération des voitures
$voitures = [];
$resCars = mysqli_query($bdd, "SELECT * FROM voitures ORDER BY marque, modele ASC");
while($row = mysqli_fetch_assoc($resCars)){
    $voitures[] = $row;
}

// Préparer un tableau PHP pour JS : marques -> modèles
$marques = [];
foreach($voitures as $v){
    $marques[$v['marque']][] = ['idcar'=>$v['idcar'], 'modele'=>$v['modele']];
}

// Traitement formulaire
if(isset($_POST['submit'])){
    $idcar_post = (int)$_POST['idcar'];
    $date_essai = mysqli_real_escape_string($bdd, $_POST['date_essai']);
    $lieu_essai = mysqli_real_escape_string($bdd, $_POST['lieu_essai']);
    $heure_essai = mysqli_real_escape_string($bdd, $_POST['heure_essai']);

    $sqlInsert = "INSERT INTO essai (iduser, idcar, date_essai, lieu_essai, heure_essai, statut)
                  VALUES ('$iduser', '$idcar_post', '$date_essai', '$lieu_essai', '$heure_essai', 'en attente')";
    if(mysqli_query($bdd, $sqlInsert)){
        $message = "Demande d'essai envoyée avec succès !";
    } else {
        $message = "Erreur : " . mysqli_error($bdd);
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Réserver un essai</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body class="bg-gray-100">

<?php require("menu.php"); ?>

<div class="container py-5">
    <h2 class="text-3xl font-bold mb-4 text-center">Réserver un essai</h2>

    <?php if(isset($message)) echo "<div class='alert alert-success'>{$message}</div>"; ?>

    <form method="POST" class="bg-white p-4 p-md-5 rounded-lg shadow-lg mx-auto" style="max-width:600px;">
        <div class="mb-3">
            <label class="form-label">Nom :</label>
            <input type="text" class="form-control" name="nom" value="<?php echo htmlspecialchars($nom); ?>" readonly>
        </div>
        <div class="mb-3">
            <label class="form-label">Prénom :</label>
            <input type="text" class="form-control" name="prenom" value="<?php echo htmlspecialchars($prenom); ?>" readonly>
        </div>

        <!-- Sélection de la marque -->
        <div class="mb-3">
            <label class="form-label">Marque :</label>
            <select id="selectMarque" class="form-select" required>
                <option value="">-- Choisissez une marque --</option>
                <?php foreach($marques as $marque => $modeles): ?>
                    <option value="<?php echo htmlspecialchars($marque); ?>"><?php echo htmlspecialchars($marque); ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <!-- Sélection du modèle -->
        <div class="mb-3">
            <label class="form-label">Modèle :</label>
            <select id="selectModele" name="idcar" class="form-select" required disabled>
                <option value="">-- Sélectionnez une marque d'abord --</option>
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label">Date de l'essai :</label>
            <input type="date" class="form-control" name="date_essai" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Heure de l'essai :</label>
            <input type="time" class="form-control" name="heure_essai" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Lieu de l'essai :</label>
            <input type="text" class="form-control" name="lieu_essai" required>
        </div>

        <button type="submit" name="submit" class="btn btn-primary w-100">Envoyer la demande</button>
    </form>
</div>

<?php require("footer.php"); ?>

<script>
const marques = <?php echo json_encode($marques); ?>;
const selectMarque = document.getElementById('selectMarque');
const selectModele = document.getElementById('selectModele');

selectMarque.addEventListener('change', function() {
    const marque = this.value;
    selectModele.innerHTML = '';
    if(marque && marques[marque]){
        selectModele.disabled = false;
        selectModele.innerHTML = '<option value="">-- Choisissez un modèle --</option>';
        marques[marque].forEach(m => {
            const opt = document.createElement('option');
            opt.value = m.idcar;
            opt.textContent = m.modele;
            selectModele.appendChild(opt);
        });
    } else {
        selectModele.disabled = true;
        selectModele.innerHTML = '<option value="">-- Sélectionnez une marque d\'abord --</option>';
    }
});
</script>

</body>
</html>
