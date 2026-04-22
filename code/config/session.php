<?php
// ============================================================
//  config/session.php
//  Démarrage de la session + fonctions utilitaires globales
//  À inclure en PREMIER dans chaque page PHP du projet
// ============================================================

// Démarre la session si elle n'est pas déjà active
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// ============================================================
//  FONCTIONS UTILITAIRES
// ============================================================

/**
 * Vérifie si l'utilisateur est connecté
 * Utilisation : if (estConnecte()) { ... }
 */
function estConnecte(): bool
{
    return isset($_SESSION['utilisateur_id']);
}

/**
 * Vérifie si l'utilisateur connecté est administrateur
 * Utilisation : if (estAdmin()) { ... }
 */
function estAdmin(): bool
{
    return isset($_SESSION['role']) && $_SESSION['role'] === 'admin';
}

/**
 * Redirige vers une page et stoppe le script
 * Utilisation : rediriger('index.php')
 *              rediriger('../index.php')
 */
function rediriger(string $url): void
{
    header("Location: $url");
    exit();
}

/**
 * Redirige vers la page de connexion si l'utilisateur n'est PAS connecté
 * À appeler en haut des pages réservées (essai, espace perso...)
 * Utilisation : exigerConnexion()
 */
function exigerConnexion(): void
{
    if (!estConnecte()) {
        // On mémorise la page demandée pour y revenir après connexion
        $_SESSION['redirect_apres_connexion'] = $_SERVER['REQUEST_URI'];
        rediriger('/includes/connexion.php');
    }
}

/**
 * Redirige vers la page de connexion ADMIN si non connecté ou pas admin
 */
function exigerAdmin(): void
{
    if (!estConnecte() || !estAdmin()) {
        rediriger('/admin/login.php');
    }
}

/**
 * Nettoie et sécurise une chaîne de caractères saisie par l'utilisateur
 * Protège contre les injections HTML/XSS
 * Utilisation : $prenom = nettoyer($_POST['prenom'])
 */
function nettoyer(string $valeur): string
{
    return htmlspecialchars(trim($valeur), ENT_QUOTES, 'UTF-8');
}

/**
 * Formate un prix en roupies mauriciennes
 * Utilisation : echo formaterPrix(28000000) → "Rs 28 000 000"
 */
function formaterPrix(float $prix): string
{
    return 'Rs ' . number_format($prix, 0, '.', ' ');
}

/**
 * Retourne le prénom de l'utilisateur connecté
 * Utilisation : echo prenomConnecte()
 */
function prenomConnecte(): string
{
    return $_SESSION['prenom'] ?? '';
}

/**
 * Retourne l'ID de l'utilisateur connecté (ou 0 si non connecté)
 * Utilisation : $id = idConnecte()
 */
function idConnecte(): int
{
    return (int)($_SESSION['utilisateur_id'] ?? 0);
}

/**
 * Stocke un message flash en session (succès ou erreur)
 * Le message s'affiche une seule fois puis disparaît
 * Utilisation : flashMessage('Demande envoyée !', 'succes')
 *              flashMessage('Email invalide.', 'erreur')
 */
function flashMessage(string $message, string $type = 'succes'): void
{
    $_SESSION['flash'] = ['message' => $message, 'type' => $type];
}

/**
 * Affiche le message flash s'il existe, puis le supprime
 * À placer dans header.php juste après le <nav>
 */
function afficherFlash(): void
{
    if (isset($_SESSION['flash'])) {
        $flash = $_SESSION['flash'];
        $couleur = $flash['type'] === 'succes'
            ? 'rgba(6,255,165,0.12)'
            : 'rgba(239,68,68,0.12)';
        $bordure = $flash['type'] === 'succes'
            ? '#06ffa5'
            : '#ef4444';
        echo '<div style="
            background:' . $couleur . ';
            border-left: 3px solid ' . $bordure . ';
            color:#fff;
            padding:12px 20px;
            font-size:13px;
            margin:0;
        ">' . htmlspecialchars($flash['message']) . '</div>';

        // Suppression après affichage (flash = une seule fois)
        unset($_SESSION['flash']);
    }
}
?>
