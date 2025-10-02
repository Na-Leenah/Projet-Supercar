<?php
// conditions.php
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

<div class="container py-5">
  <h1 class="mb-4">Conditions Générales d’Utilisation</h1>
  <h3>Objet</h3>
  <p>Les présentes conditions générales d’utilisation (CGU) définissent les règles d’accès et d’utilisation du site SuperCar.</p>
  <h3>Accès au site</h3>
  <p>Le site est accessible 24h/24 et 7j/7, sauf en cas de force majeure ou de maintenance.</p>
  <h3>Propriété intellectuelle</h3>
  <p>Tous les contenus présents sur ce site (textes, images, logos) sont protégés par le droit d’auteur. Toute reproduction sans autorisation est interdite.</p>
  <h3>Responsabilités</h3>
  <p>SuperCar ne peut être tenu responsable en cas de mauvaise utilisation du site ou de dommages directs/indirects liés à l’utilisation de celui-ci.</p>
  <h3>Droit applicable</h3>
  <p>Les présentes conditions sont régies par le droit mauricien. Tout litige sera soumis à la juridiction compétente de l’Île Maurice.</p>
</div>

<?php
require 'footer.php';
?>

    
</body>
</html>

