<?php
include("header.php");
include("connexion.php");

// Stats
$nb_voitures = mysqli_fetch_assoc(mysqli_query($bdd,"SELECT COUNT(*) as nb FROM voitures"))['nb'];
$nb_evenements = mysqli_fetch_assoc(mysqli_query($bdd,"SELECT COUNT(*) as nb FROM evenement"))['nb'];
$nb_inscriptions = mysqli_fetch_assoc(mysqli_query($bdd,"SELECT COUNT(*) as nb FROM inscriptions_evenement"))['nb'];
$nb_users = mysqli_fetch_assoc(mysqli_query($bdd,"SELECT COUNT(*) as nb FROM utilisateurs"))['nb'];
$nb_essais = mysqli_fetch_assoc(mysqli_query($bdd,"SELECT COUNT(*) as nb FROM essai"))['nb'];
?>

<h1 class="mb-4">Tableau de Bord</h1>
<div class="row g-3">
  <div class="col-md-3">
    <div class="card text-center bg-primary text-white p-3">
      <h4>Voitures</h4>
      <h2><?= $nb_voitures ?></h2>
    </div>
  </div>
  <div class="col-md-3">
    <div class="card text-center bg-success text-white p-3">
      <h4>Événements</h4>
      <h2><?= $nb_evenements ?></h2>
    </div>
  </div>
  <div class="col-md-3">
    <div class="card text-center bg-warning text-white p-3">
      <h4>Inscriptions</h4>
      <h2><?= $nb_inscriptions ?></h2>
    </div>
  </div>
  <div class="col-md-3">
    <div class="card text-center bg-danger text-white p-3">
      <h4>Utilisateurs</h4>
      <h2><?= $nb_users ?></h2>
    </div>
  </div>
</div>
<div class="row g-3 mt-3">
  <div class="col-md-3">
    <div class="card text-center bg-info text-white p-3">
      <h4>Essais</h4>
      <h2><?= $nb_essais ?></h2>
    </div>
  </div>
</div>

<?php include("footer.php"); ?>
