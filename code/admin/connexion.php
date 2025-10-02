<?php
$host = "localhost";
$user = "root";
$pass = "";
$dbname = "supercar";

$bdd = mysqli_connect($host, $user, $pass, $dbname);
if (!$bdd) {
    die("Erreur de connexion : " . mysqli_connect_error());
}
?>
