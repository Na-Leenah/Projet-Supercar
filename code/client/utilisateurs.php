<?php
session_start();
include("connexion.php");

if($_SERVER['REQUEST_METHOD'] == "POST"){
    $nom = mysqli_real_escape_string($bdd, $_POST['nom']);
    $prenom = mysqli_real_escape_string($bdd, $_POST['prenom']);
    $email = mysqli_real_escape_string($bdd, $_POST['email']);
    $telephone = mysqli_real_escape_string($bdd, $_POST['telephone']);
    $motdepasse = password_hash($_POST['motdepasse'], PASSWORD_DEFAULT); // hash du mot de passe

    $sql = "INSERT INTO utilisateurs (nom, prenom, email, telephone, motdepasse) 
            VALUES ('$nom','$prenom','$email','$telephone','$motdepasse')";
    if(mysqli_query($bdd, $sql)){
        // On récupère l'iduser au lieu de user_id
        $_SESSION['iduser'] = mysqli_insert_id($bdd);
        // Redirige vers l'accueil ou la page précédente
        header("Location: index.php"); 
        exit;
    } else {
        echo "Erreur : ".mysqli_error($bdd);
    }
}
?>
