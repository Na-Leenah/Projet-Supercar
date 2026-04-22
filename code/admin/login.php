<?php
// ============================================================
//  admin/login.php — Page de connexion administrateur
//  Accessible via : http://localhost/supercar/admin/
//  ou directement : http://localhost/supercar/admin/login.php
// ============================================================
require_once __DIR__ . '/../config/session.php';
require_once __DIR__ . '/../config/db.php';

// Si déjà connecté en tant qu'admin → dashboard directement
    if (estConnecte() && estAdmin()) {
    rediriger('/admin/index.php');
}

$erreur = '';

// ── Traitement du formulaire POST ──
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $email = nettoyer($_POST['email']        ?? '');
    $mdp   = $_POST['mot_de_passe']           ?? '';

    if (empty($email) || empty($mdp)) {
        $erreur = 'Veuillez remplir tous les champs.';

    } else {
        $pdo  = getPDO();
        $stmt = $pdo->prepare(
            "SELECT id, prenom, nom, email, mot_de_passe, role
             FROM utilisateurs
             WHERE email = :email AND role = 'admin'
             LIMIT 1"
        );
        $stmt->execute([':email' => $email]);
        $user = $stmt->fetch();

        if ($user && password_verify($mdp, $user['mot_de_passe'])) {

            // Connexion admin réussie
            $_SESSION['utilisateur_id'] = $user['id'];
            $_SESSION['prenom']         = $user['prenom'];
            $_SESSION['nom']            = $user['nom'];
            $_SESSION['email']          = $user['email'];
            $_SESSION['role']           = 'admin';

            rediriger('/admin/index.php');

        } else {
            // Email non admin ou mot de passe incorrect
            $erreur = 'Identifiants incorrects ou accès non autorisé.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion Admin — SuperCar</title>
    <link rel="stylesheet" href="/admin/assets/admin.css">
    <style>
        /* Page de login centrée et responsive */
        .login-page {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: var(--bg);
            width: 100%;
            padding: 28px 20px;
            box-sizing: border-box;
            background-image:
                radial-gradient(ellipse 60% 50% at 50% 0%,
                    rgba(0,200,255,0.06) 0%, transparent 60%);
        }

        .login-card {
            background: var(--bg2);
            border: 1px solid var(--border);
            border-radius: 12px;
            padding: 32px;
            width: 100%;
            max-width: 420px;
            position: relative;
            overflow: hidden;
            box-shadow: 0 6px 20px rgba(0,0,0,0.35);
        }

        /* Ligne dégradée en haut de la carte */
        .login-card::before {
            content: '';
            position: absolute;
            top: 0; left: 0; right: 0;
            height: 1px;
            background: linear-gradient(90deg,
                transparent, var(--ac1), var(--ac2), transparent);
        }

        .login-logo {
            text-align: center;
            margin-bottom: 8px;
            font-size: 20px;
            font-weight: 600;
            letter-spacing: 2px;
            text-transform: uppercase;
            color: #fff;
        }

        .login-logo em { color: var(--ac1); font-style: normal; }

        .login-subtitle {
            text-align: center;
            font-size: 11px;
            color: var(--muted);
            letter-spacing: 1.8px;
            text-transform: uppercase;
            margin-bottom: 26px;
        }

        .login-error {
            background: rgba(248,113,113,0.08);
            border-left: 3px solid #f87171;
            color: #fca5a5;
            padding: 10px 14px;
            font-size: 12px;
            border-radius: 0 4px 4px 0;
            margin-bottom: 16px;
        }

        .login-form { display: flex; flex-direction: column; gap: 14px; }

        .login-form label {
            font-size: 11px;
            color: var(--muted);
            letter-spacing: 1.5px;
            text-transform: uppercase;
            display: block;
            margin-bottom: 6px;
        }

        .login-form input {
            width: 100%;
            background: var(--bg3);
            border: 1px solid var(--border);
            border-radius: 6px;
            padding: 10px 12px;
            font-size: 14px;
            color: #fff;
            outline: none;
            font-family: inherit;
            transition: border-color 0.2s, box-shadow 0.12s;
        }

        .login-form input:focus { border-color: var(--ac1); box-shadow: 0 0 0 3px rgba(0,200,255,0.04); }
        .login-form input::placeholder { color: rgba(255,255,255,0.2); }

        .login-btn {
            width: 100%;
            background: linear-gradient(90deg, var(--ac1), var(--ac2));
            color: #fff;
            border: none;
            border-radius: 6px;
            padding: 12px;
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
            letter-spacing: 1px;
            text-transform: uppercase;
            margin-top: 6px;
            transition: opacity 0.2s, transform 0.08s;
        }

        .login-btn:active { transform: translateY(1px); }
        .login-btn:hover { opacity: 0.94; }

        .login-back {
            display: block;
            text-align: center;
            margin-top: 18px;
            font-size: 12px;
            color: var(--muted);
            transition: color 0.15s;
        }

        .login-back:hover { color: var(--ac1); }

        /* Responsive tweaks */
        @media (max-width: 480px) {
            .login-card { padding: 20px; max-width: 360px; border-radius: 10px; }
            .login-logo { font-size: 18px; }
            .login-subtitle { font-size: 10px; margin-bottom: 18px; }
            .login-form label { font-size: 10px; }
            .login-btn { padding: 11px; font-size: 13px; }
        }

        @media (max-width: 360px) {
            .login-card { padding: 16px; max-width: 340px; }
            .login-page { padding: 14px; }
        }
    </style>
</head>
<body class="admin-body">

<div class="login-page">
    <div class="login-card">

        <div class="login-logo">Super<em>Car</em></div>
        <div class="login-subtitle">Espace Administration</div>

        <?php if ($erreur): ?>
            <div class="login-error"><?= htmlspecialchars($erreur) ?></div>
        <?php endif; ?>

        <form method="POST" class="login-form">

            <div>
                <label>Adresse email</label>
                <input type="email" name="email"
                       placeholder="admin@supercar.mu"
                       value="<?= htmlspecialchars($_POST['email'] ?? '') ?>"
                       required autofocus>
            </div>

            <div>
                <label>Mot de passe</label>
                <input type="password" name="mot_de_passe"
                       placeholder="••••••••"
                       required>
            </div>

            <button type="submit" class="login-btn">
                Accéder au panneau →
            </button>

        </form>

        <a href="/index.php" class="login-back">
            ← Retour au site
        </a>

    </div>
</div>

</body>
</html>
