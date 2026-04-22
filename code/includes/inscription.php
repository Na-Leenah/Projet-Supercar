<?php
// ============================================================
//  includes/inscription.php
//  Page d'inscription — accessible à tous
// ============================================================
require_once __DIR__ . '/../config/session.php';
require_once __DIR__ . '/../config/db.php';

// Si déjà connecté, rediriger
if (estConnecte()) {
    rediriger('/index.php');
}

$erreur  = '';
$succes  = false;
// Conserve les valeurs saisies en cas d'erreur (UX)
$champs  = ['prenom' => '', 'nom' => '', 'email' => '', 'telephone' => ''];

// ── Traitement du formulaire POST ──
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Récupération et nettoyage
    $prenom    = nettoyer($_POST['prenom']    ?? '');
    $nom       = nettoyer($_POST['nom']       ?? '');
    $email     = nettoyer($_POST['email']     ?? '');
    $telephone = nettoyer($_POST['telephone'] ?? '');
    $mdp       = $_POST['mot_de_passe']       ?? '';
    $mdp2      = $_POST['mot_de_passe2']      ?? '';

    // Conserver pour réafficher le formulaire
    $champs = compact('prenom', 'nom', 'email', 'telephone');

    // ── Validations ──
    if (empty($prenom) || empty($nom) || empty($email) || empty($mdp)) {
        $erreur = 'Veuillez remplir tous les champs obligatoires.';

    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $erreur = 'Adresse email invalide.';

    } elseif (strlen($mdp) < 8) {
        $erreur = 'Le mot de passe doit contenir au moins 8 caractères.';

    } elseif ($mdp !== $mdp2) {
        $erreur = 'Les deux mots de passe ne correspondent pas.';

    } else {
        $pdo = getPDO();

        // Vérifier que l'email n'est pas déjà utilisé
        $check = $pdo->prepare("SELECT id FROM utilisateurs WHERE email = :email LIMIT 1");
        $check->execute([':email' => $email]);

        if ($check->fetch()) {
            $erreur = 'Cette adresse email est déjà utilisée.';

        } else {
            // Hasher le mot de passe avec bcrypt (PASSWORD_DEFAULT)
            $hash = password_hash($mdp, PASSWORD_DEFAULT);

            // Insérer le nouvel utilisateur
            $insert = $pdo->prepare(
                "INSERT INTO utilisateurs (prenom, nom, email, mot_de_passe, telephone, role)
                 VALUES (:prenom, :nom, :email, :mdp, :tel, 'client')"
            );
            $insert->execute([
                ':prenom' => $prenom,
                ':nom'    => $nom,
                ':email'  => $email,
                ':mdp'    => $hash,
                ':tel'    => $telephone,
            ]);

            // Connexion automatique après inscription
            $id = $pdo->lastInsertId();
            $_SESSION['utilisateur_id'] = $id;
            $_SESSION['prenom']         = $prenom;
            $_SESSION['nom']            = $nom;
            $_SESSION['email']          = $email;
            $_SESSION['role']           = 'client';

            flashMessage('Bienvenue ' . $prenom . ' ! Votre compte a été créé.', 'succes');
            rediriger('/index.php');
        }
    }
}

$titrePage  = 'Inscription';
$pageActive = 'inscription';
require_once __DIR__ . '/header.php';
?>

<main class="auth-main">
    <div class="auth-card">

        <div class="auth-header">
            <div class="auth-eyebrow">Espace client</div>
            <h1 class="auth-title">Créer un compte</h1>
            <p class="auth-sub">Inscrivez-vous pour réserver vos essais en ligne</p>
        </div>

        <?php if ($erreur): ?>
            <div class="form-error"><?= htmlspecialchars($erreur) ?></div>
        <?php endif; ?>

        <form method="POST" action="" class="auth-form" novalidate>

            <!-- Ligne prénom + nom -->
            <div class="form-row-2">
                <div class="form-group">
                    <label for="prenom">Prénom <span class="req">*</span></label>
                    <input type="text" id="prenom" name="prenom"
                           placeholder="Jean"
                           value="<?= htmlspecialchars($champs['prenom']) ?>"
                           required>
                </div>
                <div class="form-group">
                    <label for="nom">Nom <span class="req">*</span></label>
                    <input type="text" id="nom" name="nom"
                           placeholder="Dupont"
                           value="<?= htmlspecialchars($champs['nom']) ?>"
                           required>
                </div>
            </div>

            <div class="form-group">
                <label for="email">Adresse email <span class="req">*</span></label>
                <input type="email" id="email" name="email"
                       placeholder="jean@email.mu"
                       value="<?= htmlspecialchars($champs['email']) ?>"
                       required autocomplete="email">
            </div>

            <div class="form-group">
                <label for="telephone">Téléphone</label>
                <input type="tel" id="telephone" name="telephone"
                       placeholder="+230 5XXX XXXX"
                       value="<?= htmlspecialchars($champs['telephone']) ?>">
            </div>

            <!-- Ligne mot de passe + confirmation -->
            <div class="form-row-2">
                <div class="form-group">
                    <label for="mot_de_passe">Mot de passe <span class="req">*</span></label>
                    <input type="password" id="mot_de_passe" name="mot_de_passe"
                           placeholder="Min. 8 caractères"
                           required autocomplete="new-password">
                </div>
                <div class="form-group">
                    <label for="mot_de_passe2">Confirmer <span class="req">*</span></label>
                    <input type="password" id="mot_de_passe2" name="mot_de_passe2"
                           placeholder="Répéter le mot de passe"
                           required autocomplete="new-password">
                </div>
            </div>

            <button type="submit" class="btn-auth-submit">
                Créer mon compte →
            </button>

        </form>

        <div class="auth-footer-link">
            Déjà inscrit ?
            <a href="/includes/connexion.php">Se connecter</a>
        </div>

    </div>
</main>

<?php require_once __DIR__ . '/footer.php'; ?>
