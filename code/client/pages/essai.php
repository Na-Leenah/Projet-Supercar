<?php
// ============================================================
//  client/pages/essai.php — Demande d'essai
//  Chemin : wamp/www/supercar/client/pages/essai.php
// ============================================================
require_once __DIR__ . '/../../config/session.php';
require_once __DIR__ . '/../../config/db.php';

exigerConnexion();

$pdo = getPDO();

// Pré-sélection voiture depuis la fiche détail (?voiture_id=X)
$voiture_id_preselect = (int)($_GET['voiture_id'] ?? 0);

// Récupérer toutes les voitures disponibles pour le select
$voitures = $pdo->query(
    "SELECT v.id, v.nom, m.nom AS marque
     FROM voitures v
     JOIN marques m ON v.marque_id = m.id
     WHERE v.disponible = 1
     ORDER BY m.nom, v.nom"
)->fetchAll();

// Données de l'utilisateur connecté (pré-remplissage)
$stmt_user = $pdo->prepare(
    "SELECT prenom, nom, email, telephone FROM utilisateurs WHERE id = :id LIMIT 1"
);
$stmt_user->execute([':id' => idConnecte()]);
$user = $stmt_user->fetch();

$erreur = '';

// ── Traitement POST ──
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $voiture_id   = (int)($_POST['voiture_id']    ?? 0);
    $date_souhait = nettoyer($_POST['date_souhaitee'] ?? '');
    $horaire      = nettoyer($_POST['horaire']        ?? '');
    $email        = nettoyer($_POST['email']          ?? '');
    $telephone    = nettoyer($_POST['telephone']      ?? '');

    if ($voiture_id <= 0) {
        $erreur = 'Veuillez sélectionner un véhicule.';
    } elseif (empty($date_souhait)) {
        $erreur = 'Veuillez choisir une date.';
    } elseif (strtotime($date_souhait) < strtotime('tomorrow')) {
        $erreur = 'La date doit être au moins demain.';
    } elseif (empty($horaire)) {
        $erreur = 'Veuillez choisir une heure.';
    } elseif (!preg_match('/^\d{2}:\d{2}$/', $horaire)) {
        $erreur = 'Format d\'heure invalide.';
    } elseif (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $erreur = 'Adresse email invalide.';
    } elseif (empty($telephone)) {
        $erreur = 'Veuillez saisir votre numéro de téléphone.';
    } else {
        // NOTE: la procédure stockée et la colonne BDD devront être mises à jour
        // pour accepter le paramètre :horaire au lieu de :creneau.
        $stmt = $pdo->prepare(
            "CALL sp_creer_demande_essai(
                :user_id, :voiture_id,
                :prenom, :nom, :email, :tel,
                :date_s, :horaire,
                @result
            )"
        );
        $stmt->execute([
            ':user_id'    => idConnecte(),
            ':voiture_id' => $voiture_id,
            ':prenom'     => $user['prenom'],
            ':nom'        => $user['nom'],
            ':email'      => $email,
            ':tel'        => $telephone,
            ':date_s'     => $date_souhait,
            ':horaire'    => $horaire,
        ]);

        $result = $pdo->query("SELECT @result AS result")->fetch();

        if ($result['result'] === 'OK') {
            flashMessage('Votre demande d\'essai a bien été envoyée ! Nous vous contacterons sous 24h.', 'succes');
            rediriger('../../index.php');
        } else {
            $erreur = str_replace('ERREUR: ', '', $result['result']);
        }
    }
}

$titrePage  = 'Demande d\'essai';
$pageActive = 'essai';
require_once __DIR__ . '/../../includes/header.php';
?>

<!-- Bannière -->
<div class="page-banner">
    <p class="banner-eyebrow">Réservation</p>
    <h1 class="banner-title">Demande d'essai</h1>
    <p class="banner-sub">Réservez votre essai gratuit au siège social · Réponse sous 24h</p>
</div>

<!-- Indicateur d'étapes -->
<div class="essai-steps">
    <div class="step-item step-done">
        <div class="step-num">✓</div>
        <span>Connexion</span>
    </div>
    <div class="step-sep"></div>
    <div class="step-item step-on">
        <div class="step-num">2</div>
        <span>Votre demande</span>
    </div>
    <div class="step-sep"></div>
    <div class="step-item step-off">
        <div class="step-num">3</div>
        <span>Confirmation</span>
    </div>
</div>

<!-- ════════════════════════════════════════════
     FORMULAIRE + RÉCAPITULATIF
     ════════════════════════════════════════════ -->
<div class="essai-body">

    <!-- ── COLONNE FORMULAIRE ── -->
    <div class="essai-form-col">

        <?php if ($erreur): ?>
            <div class="form-error"><?= htmlspecialchars($erreur) ?></div>
        <?php endif; ?>

        <form method="POST" action="" id="form-essai">

            <!-- ── Section 1 : Coordonnées ── -->
            <div class="essai-section">
                <h2 class="essai-section-title">Vos coordonnées</h2>

                <div class="form-row-2">
                    <div class="form-group">
                        <label>Prénom</label>
                        <input type="text"
                               value="<?= htmlspecialchars($user['prenom']) ?>"
                               readonly class="input-readonly">
                    </div>
                    <div class="form-group">
                        <label>Nom</label>
                        <input type="text"
                               value="<?= htmlspecialchars($user['nom']) ?>"
                               readonly class="input-readonly">
                    </div>
                </div>

                <div class="form-row-2">
                    <div class="form-group">
                        <label>Email <span class="req">*</span></label>
                        <input type="email" name="email"
                               value="<?= htmlspecialchars($user['email']) ?>"
                               placeholder="jean@email.mu" required>
                    </div>
                    <div class="form-group">
                        <label>Téléphone <span class="req">*</span></label>
                        <input type="tel" name="telephone"
                               value="<?= htmlspecialchars($user['telephone'] ?? '') ?>"
                               placeholder="+230 5XXX XXXX" required>
                    </div>
                </div>
            </div>

            <!-- ── Section 2 : Véhicule (select simple) ── -->
            <div class="essai-section">
                <h2 class="essai-section-title">Véhicule souhaité pour l'essai</h2>

                <div class="form-row-2">
                    <!-- Select marque -->
                    <div class="form-group">
                        <label>Marque <span class="req">*</span></label>
                        <select id="select-marque" class="filter-select"
                                onchange="filtrerModeles()">
                            <option value="">— Choisir une marque —</option>
                            <?php
                            // Récupérer les marques distinctes
                            $marques_list = [];
                            foreach ($voitures as $v) {
                                $marques_list[$v['marque']] = true;
                            }
                            foreach (array_keys($marques_list) as $marque_nom):
                                // Marque pré-sélectionnée si voiture_id_preselect fourni
                                $presel_marque = '';
                                if ($voiture_id_preselect > 0) {
                                    foreach ($voitures as $v) {
                                        if ($v['id'] == $voiture_id_preselect) {
                                            $presel_marque = $v['marque'];
                                        }
                                    }
                                }
                            ?>
                                <option value="<?= htmlspecialchars($marque_nom) ?>"
                                    <?= $presel_marque === $marque_nom ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($marque_nom) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <!-- Select modèle (filtré par marque) -->
                    <div class="form-group">
                        <label>Modèle <span class="req">*</span></label>
                        <select name="voiture_id" id="select-modele" class="filter-select"
                                onchange="mettreAJourRecap()" required>
                            <option value="">— Choisir un modèle —</option>
                            <?php foreach ($voitures as $v): ?>
                                <option value="<?= $v['id'] ?>"
                                        data-marque="<?= htmlspecialchars($v['marque']) ?>"
                                        <?= $v['id'] == $voiture_id_preselect ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($v['marque'] . ' — ' . $v['nom']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
            </div>

            <!-- ── Section 3 : Date et créneau ── -->
            <div class="essai-section">
                <h2 class="essai-section-title">Date et heure souhaitées</h2>

                <div class="form-row-2">
                    <div class="form-group">
                        <label>Date souhaitée <span class="req">*</span></label>
                        <input type="date" name="date_souhaitee"
                               min="<?= date('Y-m-d', strtotime('+1 day')) ?>"
                               onchange="mettreAJourRecap()"
                               required>
                    </div>
                    <div class="form-group">
                        <label>Heure souhaitée <span class="req">*</span></label>
                        <div class="horaire-input">
                            <input type="time" name="horaire" min="09:00" max="17:00"
                                   onchange="mettreAJourRecap()" required>
                            <small class="muted">Choisissez une heure (09:00–17:00)</small>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Bouton envoi -->
            <button type="submit" class="btn-auth-submit">
                Soumettre ma demande →
            </button>

        </form>
    </div>

    <!-- ── COLONNE RÉCAPITULATIF ── -->
    <div class="essai-recap-col">

        <div class="recap-card">
            <h3 class="recap-title">Récapitulatif</h3>

            <div class="recap-row">
                <span class="recap-key">Client</span>
                <span class="recap-val">
                    <?= htmlspecialchars($user['prenom'] . ' ' . $user['nom']) ?>
                </span>
            </div>
            <div class="recap-row">
                <span class="recap-key">Véhicule</span>
                <span class="recap-val" id="recap-voiture">—</span>
            </div>
            <div class="recap-row">
                <span class="recap-key">Date</span>
                <span class="recap-val" id="recap-date">—</span>
            </div>
            <div class="recap-row">
                <span class="recap-key">Heure</span>
                <span class="recap-val" id="recap-horaire">—</span>
            </div>
            <div class="recap-row">
                <span class="recap-key">Lieu</span>
                <span class="recap-val">Siège SuperCar · Ebène</span>
            </div>
            <div class="recap-row">
                <span class="recap-key">Coût</span>
                <span class="recap-val" style="color:var(--c3)">Gratuit</span>
            </div>
        </div>

        <div class="essai-info-box">
            <p>
                Un conseiller SuperCar vous contactera dans les
                <strong style="color:var(--c1)">24h</strong>
                pour confirmer votre rendez-vous.
            </p>
        </div>

    </div>

</div>

<?php require_once __DIR__ . '/../../includes/footer.php'; ?>

<!-- ════════════════════════════════════════════
     JAVASCRIPT
     ════════════════════════════════════════════ -->
<script>
// Filtre les modèles selon la marque choisie
function filtrerModeles() {
    const marqueChoisie = document.getElementById('select-marque').value;
    const selectModele  = document.getElementById('select-modele');
    const options       = selectModele.querySelectorAll('option');

    // Réinitialiser la sélection
    selectModele.value = '';
    document.getElementById('recap-voiture').textContent = '—';

    options.forEach(opt => {
        if (!opt.value) {
            // Option vide toujours visible
            opt.style.display = '';
        } else if (!marqueChoisie || opt.dataset.marque === marqueChoisie) {
            opt.style.display = '';
        } else {
            opt.style.display = 'none';
        }
    });
}

// Met à jour la carte récap
function mettreAJourRecap() {
    // Véhicule
    const selectModele = document.getElementById('select-modele');
    const optionSelectionnee = selectModele.options[selectModele.selectedIndex];
    if (selectModele.value) {
        document.getElementById('recap-voiture').textContent =
            optionSelectionnee.textContent.trim();
    } else {
        document.getElementById('recap-voiture').textContent = '—';
    }

    // Date
    const date = document.querySelector('input[name="date_souhaitee"]').value;
    if (date) {
        const d    = new Date(date);
        const mois = ['Jan','Fév','Mar','Avr','Mai','Jun',
                      'Jul','Aoû','Sep','Oct','Nov','Déc'];
        document.getElementById('recap-date').textContent =
            d.getDate() + ' ' + mois[d.getMonth()] + ' ' + d.getFullYear();
    } else {
        document.getElementById('recap-date').textContent = '—';
    }

    // Horaire (champ time)
    const horaire = document.querySelector('input[name="horaire"]');
    document.getElementById('recap-horaire').textContent =
        horaire && horaire.value ? horaire.value : '—';
}

// Initialiser : si une voiture est pré-sélectionnée, synchro la marque
(function init() {
    const selectModele = document.getElementById('select-modele');
    if (selectModele.value) {
        const opt = selectModele.options[selectModele.selectedIndex];
        if (opt && opt.dataset.marque) {
            document.getElementById('select-marque').value = opt.dataset.marque;
            filtrerModeles();
            // Re-sélectionner le bon modèle après filtre
            selectModele.value = <?= $voiture_id_preselect ?: 0 ?>;
        }
        mettreAJourRecap();
    }
})();
</script>
