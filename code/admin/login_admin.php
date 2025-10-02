<?php
session_start();
include("connexion.php");
$message = '';

if(isset($_POST['submit'])){
    $email = mysqli_real_escape_string($bdd,$_POST['email']);
    $mdp = $_POST['motdepasse']; // on ne l’échappe pas pour password_verify

    // On récupère l'utilisateur par email
    $res = mysqli_query($bdd,"SELECT * FROM utilisateurs WHERE email='$email' LIMIT 1");

    if(mysqli_num_rows($res) > 0){
        $user = mysqli_fetch_assoc($res);

        // Vérifier le mot de passe haché
        if(password_verify($mdp, $user['motdepasse'])){
            // Vérifier si c'est un admin
            if($user['statut'] === 'admin'){
                $_SESSION['admin_logged_in'] = true;
                $_SESSION['admin_name'] = $user['nom'] . ' ' . $user['prenom'];
                $_SESSION['admin_id'] = $user['iduser'];
                header("Location: index.php");
                exit;
            } else {
                $message = "Vous n'êtes pas admin";
            }
        } else {
            $message = "Mot de passe incorrect";
        }
    } else {
        $message = "Email non trouvé";
    }
}
?>


<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Admin Login - SuperCar</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container d-flex justify-content-center align-items-center vh-100">
    <div class="card shadow p-4" style="width: 350px;">
        <h3 class="text-center mb-4">Connexion Admin</h3>
        <?php if($message): ?>
            <div class="alert alert-danger"><?= $message ?></div>
        <?php endif; ?>
        <form method="POST">
            <div class="mb-3">
                <label>Email</label>
                <input type="email" name="email" class="form-control" required>
            </div>
            <div class="mb-3">
                <label>Mot de passe</label>
                <input type="password" name="motdepasse" class="form-control" required>
            </div>
            <button type="submit" name="submit" class="btn btn-primary w-100">Se connecter</button>
        </form>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
