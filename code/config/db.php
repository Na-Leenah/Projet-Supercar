<?php
// ============================================================
//  config/db.php
//  Connexion à la base de données MySQL via PDO
//  PDO = PHP Data Objects (plus sécurisé que mysqli)
// ============================================================

// --- Paramètres de connexion WampServer ---
define('DB_HOST', 'localhost');       
define('DB_NAME', 'supercar_db');     
define('DB_USER', 'root');            
define('DB_PASS', '');                
define('DB_CHARSET', 'utf8mb4');      // Encodage UTF-8 complet

// --- Fonction qui retourne la connexion PDO ---
// On utilise une fonction plutôt qu'une variable globale
// pour éviter les conflits entre fichiers
function getPDO(): PDO
{
    // DSN = Data Source Name (chaîne de connexion)
    $dsn = "mysql:host=" . DB_HOST
         . ";dbname=" . DB_NAME
         . ";charset=" . DB_CHARSET;

    // Options PDO recommandées pour un code propre et sécurisé
    $options = [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,   // Lance une exception si erreur SQL
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,         // Retourne les résultats en tableau associatif
        PDO::ATTR_EMULATE_PREPARES   => false,                    // Désactive les requêtes préparées simulées (plus sûr)
    ];

    try {
        // Tentative de connexion
        $pdo = new PDO($dsn, DB_USER, DB_PASS, $options);
        return $pdo;

    } catch (PDOException $e) {
        // En cas d'erreur, on affiche un message simple (jamais les détails en prod)
        die("Erreur de connexion à la base de données. Vérifiez que WampServer est démarré.");
    }
}
?>
