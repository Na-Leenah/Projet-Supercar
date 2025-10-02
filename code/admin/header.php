<?php
session_start();
if(!isset($_SESSION['admin_logged_in'])) {
    header("Location: login_admin.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Admin SuperCar</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
<style>
    body { padding-top: 70px; }
</style>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
  <div class="container-fluid">
    <a class="navbar-brand" href="index.php">Admin SuperCar</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarAdmin">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarAdmin">
      <ul class="navbar-nav ms-auto">
        <li class="nav-item"><a class="nav-link" href="accueil_crud.php">Accueil</a></li>
        <li class="nav-item"><a class="nav-link" href="voitures_crud.php">Voitures</a></li>
        <li class="nav-item"><a class="nav-link" href="essai_crud.php">Essais</a></li>
        <li class="nav-item"><a class="nav-link" href="evenements_crud.php">Événements</a></li>
        <li class="nav-item"><a class="nav-link" href="contact_crud.php">Contact</a></li>

        <!-- Séparation -->
        <li class="nav-item ms-5"></li>

        <li class="nav-item"><a class="nav-link" href="inscriptions_crud.php">Inscriptions-évènements</a></li>
        <li class="nav-item"><a class="nav-link" href="utilisateurs_crud.php">Utilisateurs</a></li>
        <li class="nav-item"><a class="nav-link btn btn-outline-light ms-2" href="logout_admin.php">Logout</a></li>
      </ul>
    </div>
  </div>
</nav>

<div class="container">
