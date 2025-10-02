<?php
// login.php
session_start();
header('Content-Type: application/json; charset=utf-8');
include("connexion.php");

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(["status" => "error", "message" => "Mauvaise méthode"]);
    exit;
}

// champs attendus : email, motdepasse, redirect (optionnel)
$email = isset($_POST['email']) ? trim($_POST['email']) : '';
$motdepasse = isset($_POST['motdepasse']) ? $_POST['motdepasse'] : '';
$redirect = isset($_POST['redirect']) ? trim($_POST['redirect']) : '';

if ($email === '' || $motdepasse === '') {
    echo json_encode(["status" => "error", "message" => "Veuillez remplir tous les champs"]);
    exit;
}

// protége input
$email_safe = mysqli_real_escape_string($bdd, $email);

$sql = "SELECT iduser, nom, prenom, email, motdepasse FROM utilisateurs WHERE email = '$email_safe' LIMIT 1";
$res = mysqli_query($bdd, $sql);

if (!$res || mysqli_num_rows($res) !== 1) {
    echo json_encode(["status" => "error", "message" => "Email non trouvé"]);
    exit;
}

$user = mysqli_fetch_assoc($res);

// motdepasse en base = $user['motdepasse'] (doit être hashé)
if (!password_verify($motdepasse, $user['motdepasse'])) {
    echo json_encode(["status" => "error", "message" => "Mot de passe incorrect"]);
    exit;
}

// login OK
$_SESSION['iduser'] = $user['iduser'];
$_SESSION['email'] = $user['email'];

// si redirect est fourni -> on renvoie redirect absolu/relatif
if (!empty($redirect)) {
    // protéger le redirect simple (éviter redirection vers site externe)
    // autoriser uniquement chemins relatifs commençant par / ou nom de fichier
    // ici on fait une validation basique :
    if (strpos($redirect, 'http://') === false && strpos($redirect, 'https://') === false) {
        echo json_encode(["status" => "success", "redirect" => $redirect]);
        exit;
    } else {
        // safety fallback
        echo json_encode(["status" => "success", "redirect" => "index.php"]);
        exit;
    }
}

// sinon signaler success pour reload
echo json_encode(["status" => "success", "redirect" => "reload"]);
exit;
