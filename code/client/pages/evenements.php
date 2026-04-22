<?php
// ============================================================
//  client/pages/evenements.php — Page des événements
//  Chemin : wamp/www/supercar/client/pages/evenements.php
// ============================================================
require_once __DIR__ . '/../../config/session.php';
require_once __DIR__ . '/../../config/db.php';

$pdo = getPDO();

// Récupérer tous les événements actifs (passés et à venir)
$evenements = $pdo->query(
    "SELECT e.*,
            (SELECT COUNT(*) FROM inscriptions_evenements i
             WHERE i.evenement_id = e.id) AS nb_inscrits
     FROM evenements e
     WHERE e.actif = 1
     ORDER BY e.date_event ASC"
)->fetchAll();

$mois = ['Jan','Fév','Mar','Avr','Mai','Jun',
         'Jul','Aoû','Sep','Oct','Nov','Déc'];

$titrePage  = 'Événements';
$pageActive = 'evenements';
require_once __DIR__ . '/../../includes/header.php';
?>

<!-- Bannière -->
<div class="page-banner">
    <h1 class="banner-title">Nos Événements</h1>
</div>

<!-- ════════════════════════════════════════════
     GRILLE DES ÉVÉNEMENTS — layout simple cards
     ════════════════════════════════════════════ -->
<section class="events-page-section">

    <?php if (empty($evenements)): ?>
        <p class="no-data">Aucun événement pour le moment.</p>

    <?php else: ?>
        <div class="ev-simple-grid">

            <?php foreach ($evenements as $ev): ?>
                <?php
                $est_passe = strtotime($ev['date_event']) < strtotime('today');
                $places_restantes = null;
                if ($ev['places_max']) {
                    $places_restantes = $ev['places_max'] - $ev['nb_inscrits'];
                }
                $complet = ($places_restantes !== null && $places_restantes <= 0);
                ?>

                <div class="ev-simple-card <?= $est_passe ? 'ev-simple-passe' : '' ?>">

                    <!-- Titre et description -->
                    <div class="ev-simple-top">
                        <h3 class="ev-simple-titre">
                            <?= htmlspecialchars($ev['titre']) ?>
                        </h3>
                        <p class="ev-simple-desc">
                            <?= htmlspecialchars($ev['description']) ?>
                        </p>
                    </div>

                    <!-- Méta informations -->
                    <div class="ev-simple-meta">

                        <!-- Date avec badge passé/à venir -->
                        <div class="ev-simple-meta-row">
                            <span class="ev-meta-label">Date :</span>
                            <span class="ev-meta-value">
                                <?php
                                $d = strtotime($ev['date_event']);
                                echo date('d', $d) . ' ' . $mois[(int)date('n', $d) - 1] . ' ' . date('Y', $d);
                                ?>
                                <?php if ($est_passe): ?>
                                    <span class="ev-badge-passe">Passé</span>
                                <?php else: ?>
                                    <span class="ev-badge-avenir">À venir</span>
                                <?php endif; ?>
                            </span>
                        </div>

                        <!-- Lieu -->
                        <div class="ev-simple-meta-row">
                            <span class="ev-meta-label">Lieu :</span>
                            <span class="ev-meta-value">
                                <?= htmlspecialchars($ev['lieu']) ?>
                            </span>
                        </div>

                        <!-- Horaires -->
                        <div class="ev-simple-meta-row">
                            <span class="ev-meta-label">Horaires :</span>
                            <span class="ev-meta-value">
                                <?= date('H\hi', strtotime($ev['heure_debut'])) ?>
                                – <?= date('H\hi', strtotime($ev['heure_fin'])) ?>
                            </span>
                        </div>

                        <!-- Places restantes si applicable -->
                        <?php if ($places_restantes !== null): ?>
                            <div class="ev-simple-meta-row">
                                <span class="ev-meta-label">Places :</span>
                                <span class="ev-meta-value <?= $places_restantes <= 5 ? 'ev-urgence' : '' ?>">
                                    <?php if ($complet): ?>
                                        Complet
                                    <?php else: ?>
                                        <?= $places_restantes ?> restante<?= $places_restantes > 1 ? 's' : '' ?>
                                    <?php endif; ?>
                                </span>
                            </div>
                        <?php endif; ?>

                    </div>

                    <!-- Bouton inscription (événements à venir uniquement) -->
                    <?php if (!$est_passe): ?>
                        <button class="ev-simple-btn <?= $complet ? 'ev-simple-btn-complet' : '' ?>"
                                <?= $complet ? 'disabled' : '' ?>
                                onclick="<?= $complet ? '' : 'ouvrirInscription(' . $ev['id'] . ', \'' . addslashes($ev['titre']) . '\', ' . (int)$ev['inscription_requise'] . ')' ?>">
                            <?= $complet ? 'Complet' : 'S\'inscrire' ?>
                        </button>
                    <?php else: ?>
                        <div class="ev-simple-passe-label">Événement passé</div>
                    <?php endif; ?>

                </div>

            <?php endforeach; ?>

        </div>
    <?php endif; ?>

</section>

<!-- ════════════════════════════════════════════
     POPUP — INSCRIPTION À UN ÉVÉNEMENT
     Identique à la version précédente (même logique)
     ════════════════════════════════════════════ -->
<div class="modal-overlay" id="modal-inscription">
    <div class="modal-box">

        <button class="modal-close" onclick="fermerModal('modal-inscription')">✕</button>

        <h2 class="modal-title">Inscription à l'événement</h2>
        <p class="modal-sub" id="modal-event-titre">—</p>

        <div id="modal-message" style="display:none"></div>

        <form id="form-inscription" method="POST"
              action="../../client/traitement/traitement_inscription_event.php">

            <input type="hidden" name="evenement_id" id="input-event-id">

            <div class="form-row-2">
                <div class="form-group">
                    <label>Prénom <span class="req">*</span></label>
                    <input type="text" name="prenom" id="input-prenom"
                           placeholder="Jean" required
                           value="<?= htmlspecialchars($_SESSION['prenom'] ?? '') ?>">
                </div>
                <div class="form-group">
                    <label>Nom <span class="req">*</span></label>
                    <input type="text" name="nom" id="input-nom"
                           placeholder="Dupont" required
                           value="<?= htmlspecialchars($_SESSION['nom'] ?? '') ?>">
                </div>
            </div>

            <div class="form-group">
                <label>Email <span class="req">*</span></label>
                <input type="email" name="email" id="input-email"
                       placeholder="jean@email.mu" required
                       value="<?= htmlspecialchars($_SESSION['email'] ?? '') ?>">
            </div>

            <div class="form-group">
                <label>Téléphone</label>
                <input type="tel" name="telephone" id="input-telephone"
                       placeholder="+230 5XXX XXXX">
            </div>

            <button type="submit" class="btn-auth-submit" style="margin-top:16px">
                Confirmer mon inscription →
            </button>

        </form>

        <?php if (!estConnecte()): ?>
            <p style="text-align:center;margin-top:14px;font-size:11px;color:rgba(255,255,255,0.4)">
                Vous pouvez vous inscrire sans compte.
                <a href="../../includes/connexion.php"
                   style="color:var(--c1)">Se connecter</a>
                pour pré-remplir automatiquement.
            </p>
        <?php endif; ?>

    </div>
</div>

<?php require_once __DIR__ . '/../../includes/footer.php'; ?>

<!-- ════════════════════════════════════════════
     JAVASCRIPT — Gestion du popup inscription
     ════════════════════════════════════════════ -->
<script>
function ouvrirInscription(eventId, eventTitre, inscriptionRequise) {
    document.getElementById('input-event-id').value = eventId;
    document.getElementById('modal-event-titre').textContent = eventTitre;
    document.getElementById('modal-message').style.display = 'none';
    document.getElementById('modal-inscription').classList.add('actif');
}

function fermerModal(id) {
    document.getElementById(id).classList.remove('actif');
}

// Fermer en cliquant sur le fond sombre
document.querySelectorAll('.modal-overlay').forEach(overlay => {
    overlay.addEventListener('click', function(e) {
        if (e.target === this) this.classList.remove('actif');
    });
});

// Soumission AJAX du formulaire d'inscription
document.getElementById('form-inscription').addEventListener('submit', function(e) {
    e.preventDefault();
    const formData = new FormData(this);

    fetch(this.action, { method: 'POST', body: formData })
    .then(res => res.json())
    .then(data => {
        const msgDiv = document.getElementById('modal-message');
        msgDiv.style.display     = 'block';
        msgDiv.style.padding     = '10px 14px';
        msgDiv.style.fontSize    = '12px';
        msgDiv.style.borderRadius = '4px';
        msgDiv.style.marginBottom = '12px';

        if (data.succes) {
            msgDiv.style.background = 'rgba(6,255,165,0.1)';
            msgDiv.style.borderLeft = '3px solid var(--c3)';
            msgDiv.style.color      = '#06ffa5';
            msgDiv.textContent      = data.message;
            setTimeout(() => fermerModal('modal-inscription'), 2000);
        } else {
            msgDiv.style.background = 'rgba(239,68,68,0.1)';
            msgDiv.style.borderLeft = '3px solid #ef4444';
            msgDiv.style.color      = '#fca5a5';
            msgDiv.textContent      = data.message;
        }
    })
    .catch(() => {
        const msgDiv = document.getElementById('modal-message');
        msgDiv.style.display = 'block';
        msgDiv.style.color   = '#fca5a5';
        msgDiv.textContent   = 'Erreur de connexion. Veuillez réessayer.';
    });
});
</script>
