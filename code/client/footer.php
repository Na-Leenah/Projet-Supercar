<?php
include("connexion.php");

// Récupérer les infos du footer 
$sql = "SELECT tel, email, mentions, conditions, politiques FROM footer WHERE idfooter=1";
$result = mysqli_query($bdd, $sql);

if ($row = mysqli_fetch_assoc($result)) {
    $tel = $row['tel'];
    $email = $row['email'];
    $mentions = $row['mentions'];
    $conditions = $row['conditions'];
    $politiques = $row['politiques'];
}
?>

<footer class="bg-light text-primary py-3 mt-5 border-top">
  <div class="container d-flex flex-column flex-md-row justify-content-between align-items-center">
    
    <!-- Liens légaux -->
    <ul class="list-inline mb-2 mb-md-0">
      <?php if(!empty($mentions)) echo "<li class='list-inline-item'><a href='mentions.php' class='text-dark text-decoration-none'>{$mentions}</a></li>"; ?>
      <?php if(!empty($conditions)) echo "<li class='list-inline-item mx-2'>|</li><li class='list-inline-item'><a href='conditions.php' class='text-dark text-decoration-none'>{$conditions}</a></li>"; ?>
      <?php if(!empty($politiques)) echo "<li class='list-inline-item mx-2'>|</li><li class='list-inline-item'><a href='politiques.php' class='text-dark text-decoration-none'>{$politiques}</a></li>"; ?>
    </ul>

    <!-- Contact -->
    <div>
      <?php if(!empty($tel)) echo "<p class='mb-1'><i class='bi bi-telephone me-2'></i>" . $tel . "</p>"; ?>
      <?php if(!empty($email)) echo "<p class='mb-0'><i class='bi bi-envelope me-2'></i>" . $email . "</p>"; ?>
    </div>
  </div>
</footer>

<!-- Bootstrap Icons -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
