<?php
// ============================================================
//  client/pages/contact.php — Page de contact
//  Chemin : wamp/www/supercar/client/pages/contact.php
// ============================================================
require_once __DIR__ . '/../../config/session.php';
require_once __DIR__ . '/../../config/db.php';

$erreur  = '';
$succes  = false;

// Conserver les valeurs saisies si erreur
$champs = [
    'prenom'  => $_SESSION['prenom'] ?? '',
    'nom'     => $_SESSION['nom']    ?? '',
    'email'   => $_SESSION['email']  ?? '',
    'sujet'   => '',
    'message' => '',
];

// ── Traitement du formulaire POST ──
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $prenom  = nettoyer($_POST['prenom']  ?? '');
    $nom     = nettoyer($_POST['nom']     ?? '');
    $email   = nettoyer($_POST['email']   ?? '');
    $sujet   = nettoyer($_POST['sujet']   ?? '');
    $message = nettoyer($_POST['message'] ?? '');

    // Garder pour réaffichage
    $champs = compact('prenom', 'nom', 'email', 'sujet', 'message');

    // ── Validations ──
    if (empty($prenom) || empty($nom) || empty($email) || empty($message)) {
        $erreur = 'Veuillez remplir tous les champs obligatoires.';

    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $erreur = 'Adresse email invalide.';

    } elseif (strlen($message) < 10) {
        $erreur = 'Votre message est trop court (minimum 10 caractères).';

    } else {
        // Enregistrer en base de données
        $pdo  = getPDO();
        $stmt = $pdo->prepare(
            "INSERT INTO contacts (prenom, nom, email, sujet, message)
             VALUES (:prenom, :nom, :email, :sujet, :message)"
        );
        $stmt->execute([
            ':prenom'  => $prenom,
            ':nom'     => $nom,
            ':email'   => $email,
            ':sujet'   => $sujet,
            ':message' => $message,
        ]);

        $succes = true;

        // Réinitialiser les champs après succès
        $champs = [
            'prenom'  => $_SESSION['prenom'] ?? '',
            'nom'     => $_SESSION['nom']    ?? '',
            'email'   => $_SESSION['email']  ?? '',
            'sujet'   => '',
            'message' => '',
        ];
    }
}

$titrePage  = 'Contact';
$pageActive = 'contact';
require_once __DIR__ . '/../../includes/header.php';
?>

<!-- Bannière -->
<div class="page-banner">
    <p class="banner-eyebrow">Nous joindre</p>
    <h1 class="banner-title">Contactez-nous</h1>
    <p class="banner-sub">Notre équipe vous répond sous 24h</p>
</div>

<!-- ════════════════════════════════════════════
     CORPS DE LA PAGE
     ════════════════════════════════════════════ -->
<div class="contact-body">

    <!-- ── COLONNE GAUCHE : Formulaire ── -->
    <div class="contact-form-col">

        <!-- Message de succès -->
        <?php if ($succes): ?>
            <div class="contact-succes">
                <div class="succes-icon">✓</div>
                <div>
                    <strong>Message envoyé !</strong><br>
                    Nous vous répondrons à <?= htmlspecialchars($champs['email'] ?: $_POST['email']) ?> sous 24h.
                </div>
            </div>
        <?php endif; ?>

        <!-- Message d'erreur -->
        <?php if ($erreur): ?>
            <div class="form-error"><?= htmlspecialchars($erreur) ?></div>
        <?php endif; ?>

        <form method="POST" action="" novalidate>

            <div class="form-row-2">
                <div class="form-group">
                    <label>Prénom <span class="req">*</span></label>
                    <input type="text" name="prenom"
                           placeholder="Jean"
                           value="<?= htmlspecialchars($champs['prenom']) ?>"
                           required>
                </div>
                <div class="form-group">
                    <label>Nom <span class="req">*</span></label>
                    <input type="text" name="nom"
                           placeholder="Dupont"
                           value="<?= htmlspecialchars($champs['nom']) ?>"
                           required>
                </div>
            </div>

            <div class="form-group">
                <label>Adresse email <span class="req">*</span></label>
                <input type="email" name="email"
                       placeholder="jean@email.mu"
                       value="<?= htmlspecialchars($champs['email']) ?>"
                       required>
            </div>

            <!-- Sujet en boutons visibles -->
            <div class="form-group">
                <label>Sujet</label>
                <div class="sujet-btns">
                    <?php
                    $sujets = ['Renseignement','Demande d\'essai','Commande','Service après-vente','Autre'];
                    foreach ($sujets as $s):
                    ?>
                        <label class="sujet-opt">
                            <input type="radio" name="sujet"
                                   value="<?= htmlspecialchars($s) ?>"
                                   <?= $champs['sujet'] === $s ? 'checked' : '' ?>>
                            <span><?= htmlspecialchars($s) ?></span>
                        </label>
                    <?php endforeach; ?>
                </div>
            </div>

            <div class="form-group">
                <label>Message <span class="req">*</span></label>
                <textarea name="message"
                          rows="5"
                          placeholder="Votre message..."
                          required><?= htmlspecialchars($champs['message']) ?></textarea>
            </div>

            <button type="submit" class="btn-auth-submit">
                Envoyer le message →
            </button>

        </form>
    </div>

    <!-- ── COLONNE DROITE : Infos + Google Maps ── -->
    <div class="contact-info-col">

        <!-- Infos de contact -->
        <div class="contact-infos">

            <div class="contact-info-item">
                <div class="ci-icon">📍</div>
                <div>
                    <div class="ci-label">Adresse</div>
                    <div class="ci-val">Ebène CyberCity, Île Maurice</div>
                </div>
            </div>

            <div class="contact-info-item">
                <div class="ci-icon">📞</div>
                <div>
                    <div class="ci-label">Téléphone</div>
                    <div class="ci-val">+230 5XXX XXXX</div>
                </div>
            </div>

            <div class="contact-info-item">
                <div class="ci-icon">✉️</div>
                <div>
                    <div class="ci-label">Email</div>
                    <div class="ci-val">info@supercar.mu</div>
                </div>
            </div>

            <div class="contact-info-item">
                <div class="ci-icon">🕐</div>
                <div>
                    <div class="ci-label">Horaires</div>
                    <div class="ci-val">Lun–Sam · 9h00–17h30</div>
                </div>
            </div>

        </div>

        <!-- ── GOOGLE MAPS ── -->
        <!--
            Embed Google Maps sur Ebène CyberCity, Île Maurice
            Pas besoin de clé API pour un simple embed iframe
        -->
        <div class="contact-map">
            <iframe
                src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3745.7534040774537!2d57.48399187596682!3d-20.24182218122267!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x217c512e3c3c3c3b%3A0x8e5e5e5e5e5e5e5e!2sEb%C3%A8ne%20CyberCity%2C%20Mauritius!5e0!3m2!1sfr!2smu!4v1706000000000!5m2!1sfr!2smu"
                width="100%"
                height="300"
                style="border:0; border-radius: 8px; display:block;"
                allowfullscreen=""
                loading="lazy"
                referrerpolicy="no-referrer-when-downgrade"
                title="SuperCar — Ebène CyberCity, Île Maurice">
            </iframe>
        </div>

    </div>

</div>

<?php require_once __DIR__ . '/../../includes/footer.php'; ?>
