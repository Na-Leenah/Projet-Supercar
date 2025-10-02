<?php
// mentions.php
include 'connexion.php';
require 'menu.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>

    <style>
  body {
    min-height: 100vh;
    display: flex;
    flex-direction: column;
  }
  main {
    flex: 1;
  }
  footer {
    margin-top: auto;
  }
</style>

</head>
<body>

<main>
  <div class="container py-5">
    <h1 class="mb-4">Mentions Légales</h1>
    <p><strong>Éditeur du site :</strong> SuperCar, société spécialisée dans la vente de voitures de luxe.</p>
    <p><strong>Siège social :</strong> Île Maurice</p>
    <p><strong>Contact :</strong> contact@supercar.com</p>
    <p><strong>Hébergement :</strong> Alawaysdata European Cloud</p>
    <p><strong>Responsabilité :</strong> SuperCar met tout en œuvre pour assurer l'exactitude des informations diffusées. Toutefois, la société ne saurait être tenue responsable d'erreurs ou omissions.</p>
  </div>
</main>

<?php
require 'footer.php';
?>
    
</body>
</html>




