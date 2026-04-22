<?php
// ============================================================
//  includes/deconnexion.php
//  Déconnexion — détruit la session et redirige vers l'accueil
// ============================================================
require_once __DIR__ . '/../config/session.php';

// Vider toutes les variables de session
$_SESSION = [];

// Détruire le cookie de session dans le navigateur
if (ini_get('session.use_cookies')) {
    $params = session_get_cookie_params();
    setcookie(
        session_name(), '',
        time() - 42000,
        $params['path'],
        $params['domain'],
        $params['secure'],
        $params['httponly']
    );
}

// Détruire la session côté serveur
session_destroy();

// Rediriger vers l'accueil avec message
// (le flash ne peut pas être utilisé ici car session détruite)
header('Location: /index.php?deconnecte=1');
exit();
?>
