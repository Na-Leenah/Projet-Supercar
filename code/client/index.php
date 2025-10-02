<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <!-- Tailwind CDN (officiel) -->
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.min.js" integrity="sha384-G/EV+4j2dNv+tEPo3++6LCgdCROaejBqfUeNjuKAiuXbjrxilcCdDz6ZAVfHWe1Y" crossorigin="anonymous"></script>
  <title>SuperCar</title>
</head>
<body class="bg-gray-50 text-gray-900">

<?php
// démarre la session uniquement si nécessaire (évite le notice si menu.php démarre déjà la session)
session_start();

require "menu.php";
include "connexion.php";

// Récupérer les sections (header, main, voitures, evenements)
$sql = "SELECT section, texte, image FROM accueil";
$result = mysqli_query($bdd, $sql);
if (!$result) {
    die("Erreur SQL : " . mysqli_error($bdd));
}

$sections = [];
while ($row = mysqli_fetch_assoc($result)) {
    $sections[$row['section']] = $row;
}
?>

<!-- HEADER -->
<?php if (!empty($sections['header']['image']) || !empty($sections['header']['texte'])): ?>
<header
  class="h-screen bg-cover bg-center flex items-center justify-center text-white"
  style="background-image: url('<?php echo htmlspecialchars($sections['header']['image'] ?? '', ENT_QUOTES); ?>');">
  <div class="bg-black/50 px-6 py-4 rounded-lg max-w-3xl text-center">
    <h1 class="text-2xl md:text-4xl font-semibold">
      <?php echo nl2br(htmlspecialchars($sections['header']['texte'] ?? '', ENT_QUOTES)); ?>
    </h1>
  </div>
</header>
<?php endif; ?>

<!-- MAIN (texte uniquement) -->
<?php if (!empty($sections['main']['texte'])): ?>
<main class="py-12 px-6">
  <div class="max-w-4xl mx-auto text-center">
    <p class="text-lg leading-relaxed">
      <?php echo nl2br(htmlspecialchars($sections['main']['texte'], ENT_QUOTES)); ?>
    </p>
  </div>
</main>
<?php endif; ?>

<!-- SECTION VOITURES (image qui remplit la largeur du conteneur) -->
<?php if (!empty($sections['voitures'])): ?>
<section class="py-12 bg-white">
  <div class="max-w-6xl mx-auto px-6">
    <h2 class="text-3xl font-bold text-center mb-4">Nos Voitures</h2>
    <p class="text-center mb-6 max-w-3xl mx-auto">
      <?php echo nl2br(htmlspecialchars($sections['voitures']['texte'] ?? '', ENT_QUOTES)); ?>
    </p>

    <?php if (!empty($sections['voitures']['image'])): ?>
      <div class="flex justify-center">
        <a href="cars.php" class="w-full">
          <img
            src="<?php echo htmlspecialchars($sections['voitures']['image'], ENT_QUOTES); ?>"
            alt="Voitures"
            class="mx-auto w-full h-[60vh] md:h-[48vh] object-cover rounded-xl shadow-lg transition-transform duration-300 hover:scale-105"
          />
        </a>
      </div>
    <?php endif; ?>
  </div>
</section>
<?php endif; ?>

<!-- SECTION EVENEMENTS (image qui remplit la largeur du conteneur) -->
<?php if (!empty($sections['evenements'])): ?>
<section class="py-12 bg-gray-50">
  <div class="max-w-6xl mx-auto px-6">
    <h2 class="text-3xl font-bold text-center mb-4">Nos Événements</h2>
    <p class="text-center mb-6 max-w-3xl mx-auto">
      <?php echo nl2br(htmlspecialchars($sections['evenements']['texte'] ?? '', ENT_QUOTES)); ?>
    </p>

    <?php if (!empty($sections['evenements']['image'])): ?>
      <div class="flex justify-center">
        <a href="evenements.php" class="w-full">
          <img src="<?php echo htmlspecialchars($sections['evenements']['image'], ENT_QUOTES); ?>"
          alt="Événements" class="mx-auto w-full h-[90vh] md:h-[80vh] object-cover rounded-xl shadow-lg transition-transform duration-300 hover:scale-105"
          />
        </a>
      </div>
    <?php endif; ?>
  </div>
</section>
<?php endif; ?>

<?php require "footer.php"; ?>

</body>
</html>
