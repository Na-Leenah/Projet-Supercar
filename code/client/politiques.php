<?php
// politique.php
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
  <h1 class="mb-4">Politique de Confidentialité</h1>
  <p>Chez SuperCar, nous accordons une grande importance à la protection de vos données personnelles.</p>
  <h3>Collecte des données</h3>
  <p>Nous collectons uniquement les informations nécessaires (nom, email, téléphone) lors de vos demandes d’essai ou d’inscription.</p>
  <h3>Utilisation des données</h3>
  <p>Les données collectées sont utilisées exclusivement pour vous fournir nos services et ne sont jamais revendues à des tiers.</p>
  <h3>Sécurité</h3>
  <p>Nous mettons en place des mesures de sécurité afin de protéger vos données contre toute perte, usage abusif ou accès non autorisé.</p>
  <h3>Vos droits</h3>
  <p>Vous disposez d’un droit d’accès, de rectification et de suppression de vos données. Pour exercer ce droit, contactez-nous à : contact@supercar.com</p>
</div>

<?php
require 'footer.php';
?>

    
</body>
</html>


