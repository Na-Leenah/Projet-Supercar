<?php
// ============================================================
//  includes/connexion.php
//  Page de connexion — accessible à tous
// ============================================================
require_once __DIR__ . '/../config/session.php';
require_once __DIR__ . '/../config/db.php';

// Si déjà connecté, rediriger vers l'accueil
if (estConnecte()) {
    rediriger('/index.php');
}

$erreur = ''; // Stocke le message d'erreur éventuel

// ── Traitement du formulaire POST ──
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Récupération et nettoyage des champs
    $email = nettoyer($_POST['email'] ?? '');
    $mdp   = $_POST['mot_de_passe'] ?? ''; // Ne pas nettoyer le mdp (htmlspecialchars altère les hash)

    // Validation basique
    if (empty($email) || empty($mdp)) {
        $erreur = 'Veuillez remplir tous les champs.';

    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $erreur = 'Adresse email invalide.';

    } else {
        // Chercher l'utilisateur en base par son email
        $pdo  = getPDO();
        $stmt = $pdo->prepare(
            "SELECT id, prenom, nom, email, mot_de_passe, role
             FROM utilisateurs
             WHERE email = :email
             LIMIT 1"
        );
        $stmt->execute([':email' => $email]);
        $user = $stmt->fetch(); // Retourne false si non trouvé

        // Vérification : utilisateur trouvé ET mot de passe correct
        if ($user && password_verify($mdp, $user['mot_de_passe'])) {

            // ── Connexion réussie → stocker en session ──
            $_SESSION['utilisateur_id'] = $user['id'];
            $_SESSION['prenom']         = $user['prenom'];
            $_SESSION['nom']            = $user['nom'];
            $_SESSION['email']          = $user['email'];
            $_SESSION['role']           = $user['role'];

            flashMessage('Bienvenue, ' . $user['prenom'] . ' !', 'succes');

            // Rediriger vers la page demandée avant connexion, ou l'accueil
            $redirect = $_SESSION['redirect_apres_connexion'] ?? '/index.php';
            unset($_SESSION['redirect_apres_connexion']);
            rediriger($redirect);

        } else {
            // Email ou mot de passe incorrect
            $erreur = 'Email ou mot de passe incorrect.';
        }
    }
}

// ── Affichage de la page ──
$titrePage  = 'Connexion';
$pageActive = 'connexion';
require_once __DIR__ . '/header.php';
?>

<main class="auth-main">
    <div class="auth-card">

        <div class="auth-header">
            <div class="auth-eyebrow">Espace client</div>
            <h1 class="auth-title">Se connecter</h1>
            <p class="auth-sub">Accédez à votre espace pour réserver un essai</p>
        </div>

        <!-- Message d'erreur -->
        <?php if ($erreur): ?>
            <div class="form-error"><?= htmlspecialchars($erreur) ?></div>
        <?php endif; ?>

        <!-- Formulaire de connexion -->
        <form method="POST" action="" class="auth-form" novalidate>

            <div class="form-group">
                <label for="email">Adresse email</label>
                <input
                    type="email"
                    id="email"
                    name="email"
                    placeholder="jean@email.mu"
                    value="<?= htmlspecialchars($email ?? '') ?>"
                    required
                    autocomplete="email"
                >
            </div>

            <div class="form-group">
                <label for="mot_de_passe">Mot de passe</label>
                <input
                    type="password"
                    id="mot_de_passe"
                    name="mot_de_passe"
                    placeholder="Votre mot de passe"
                    required
                    autocomplete="current-password"
                >
            </div>

            <button type="submit" class="btn-auth-submit">
                Se connecter →
            </button>

        </form>

        <div class="auth-footer-link">
            Pas encore de compte ?
            <a href="/includes/inscription.php">S'inscrire gratuitement</a>
        </div>

    </div>
</main>

<?php require_once __DIR__ . '/footer.php'; ?>
