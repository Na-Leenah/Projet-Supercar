<?php
// cars.php
include("connexion.php");
session_start();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<title>Nos Voitures - SuperCar</title>
<!-- Tailwind -->
<script src="https://cdn.tailwindcss.com"></script>
<!-- Bootstrap -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body class="bg-gray-100">

<?php require("menu.php"); ?>

<div class="container mx-auto py-12 px-4">
<?php
// Pagination
$marquesParPage = 1;
$pageActuelle = isset($_GET['page']) ? max(1,(int)$_GET['page']) : 1;
$offset = ($pageActuelle - 1) * $marquesParPage;

// total marques
$sqlTotal = "SELECT COUNT(DISTINCT marque) AS total FROM voitures";
$resTotal = mysqli_query($bdd, $sqlTotal);
$totalMarques = $resTotal ? (int)mysqli_fetch_assoc($resTotal)['total'] : 0;
$totalPages = ($totalMarques > 0) ? ceil($totalMarques / $marquesParPage) : 1;

// marques page
$sqlMarque = "SELECT DISTINCT marque FROM voitures LIMIT $offset, $marquesParPage";
$resMarque = mysqli_query($bdd, $sqlMarque);

while ($rowMarque = mysqli_fetch_assoc($resMarque)):
    $marque = $rowMarque['marque'];
?>
  <h2 class="text-4xl font-bold text-center mb-12"><?php echo htmlspecialchars($marque); ?></h2>

  <?php
  $sqlCars = "SELECT * FROM voitures WHERE marque = '".mysqli_real_escape_string($bdd,$marque)."'";
  $resCars = mysqli_query($bdd, $sqlCars);
  while ($car = mysqli_fetch_assoc($resCars)):
  ?>
    <div class="flex flex-col md:flex-row items-center mb-12 bg-white rounded-xl shadow-lg overflow-hidden hover:shadow-2xl transition-shadow duration-300">
      <div class="md:w-1/2 w-full">
        <img src="<?php echo htmlspecialchars($car['img']); ?>"
             alt="<?php echo htmlspecialchars($car['modele']); ?>"
             class="w-full h-[400px] md:h-full object-cover">
      </div>

      <div class="md:w-1/2 w-full p-6 flex flex-col justify-center">
        <h3 class="text-3xl font-bold mb-4"><?php echo htmlspecialchars($car['modele']); ?></h3>
        <p class="mb-4"><?php echo nl2br(htmlspecialchars($car['description'])); ?></p>
        <p class="mb-2"><strong>Prix :</strong> <?php echo htmlspecialchars($car['prix']); ?> €</p>
        <p class="mb-2"><strong>Performance :</strong> <?php echo htmlspecialchars($car['performance']); ?></p>
        <p class="mb-4"><strong>Kilométrage :</strong> <?php echo htmlspecialchars($car['kilometrage']); ?> km</p>

        <?php if (isset($_SESSION['iduser'])): ?>
          <!-- Utilisateur connecté -> lien direct vers essai -->
          <a href="essai.php?idcar=<?php echo (int)$car['idcar']; ?>"
             class="bg-blue-700 text-white py-3 px-6 rounded-lg w-max hover:bg-blue-800 transition">
            Réserver un essai
          </a>
        <?php else: ?>
          <!-- Non connecté -> ouvre popup connexion. data-redirect utilisé par menu.js pour remplir le champ -->
          <a href="#"
             class="bg-blue-700 text-white py-3 px-6 rounded-lg w-max hover:bg-blue-800 transition btn-essai"
             data-redirect="essai.php?idcar=<?php echo (int)$car['idcar']; ?>"
             data-bs-toggle="modal" data-bs-target="#popupConnexion">
            Réserver un essai
          </a>
        <?php endif; ?>
      </div>
    </div>
  <?php endwhile; ?>

<?php endwhile; ?>

<!-- Pagination -->
<div class="flex justify-center mt-10">
  <nav aria-label="Page navigation">
    <ul class="inline-flex -space-x-px">
      <li>
        <a href="?page=<?php echo max(1,$pageActuelle-1); ?>" class="py-2 px-4 ml-0 leading-tight text-gray-500 bg-white border border-gray-300 rounded-l-lg hover:bg-gray-100 hover:text-gray-700">&laquo;</a>
      </li>
      <?php for($i=1;$i<=$totalPages;$i++): ?>
        <li>
          <a href="?page=<?php echo $i; ?>"
             class="py-2 px-4 leading-tight <?php echo ($i == $pageActuelle) ? 'text-white bg-blue-700' : 'text-gray-500 bg-white'; ?> border border-gray-300 hover:bg-gray-100 hover:text-gray-700">
             <?php echo $i; ?>
          </a>
        </li>
      <?php endfor; ?>
      <li>
        <a href="?page=<?php echo min($totalPages,$pageActuelle+1); ?>" class="py-2 px-4 leading-tight text-gray-500 bg-white border border-gray-300 rounded-r-lg hover:bg-gray-100 hover:text-gray-700">&raquo;</a>
      </li>
    </ul>
  </nav>
</div>

</div>

<?php require("footer.php"); ?>

</body>
</html>
