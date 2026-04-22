<?php
// ============================================================
//  client/traitement/traitement_inscription_event.php
//  Traitement POST de l'inscription à un événement
//  Répond en JSON (appelé via AJAX depuis evenements.php)
// ============================================================
require_once __DIR__ . '/../../config/session.php';
require_once __DIR__ . '/../../config/db.php';

// On répond toujours en JSON
header('Content-Type: application/json');

// ── Vérifier que c'est bien une requête POST ──
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['succes' => false, 'message' => 'Méthode non autorisée.']);
    exit();
}

// ── Récupérer et nettoyer les données ──
$evenement_id = (int)($_POST['evenement_id'] ?? 0);
$prenom       = nettoyer($_POST['prenom']    ?? '');
$nom          = nettoyer($_POST['nom']       ?? '');
$email        = nettoyer($_POST['email']     ?? '');
$telephone    = nettoyer($_POST['telephone'] ?? '');

// ── Validations ──
if ($evenement_id <= 0) {
    echo json_encode(['succes' => false, 'message' => 'Événement invalide.']);
    exit();
}

if (empty($prenom) || empty($nom) || empty($email)) {
    echo json_encode(['succes' => false, 'message' => 'Veuillez remplir tous les champs obligatoires.']);
    exit();
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode(['succes' => false, 'message' => 'Adresse email invalide.']);
    exit();
}

$pdo = getPDO();

// ── Vérifier que l'événement existe et est actif ──
$ev = $pdo->prepare("SELECT id, places_max, titre FROM evenements WHERE id = :id AND actif = 1 LIMIT 1");
$ev->execute([':id' => $evenement_id]);
$evenement = $ev->fetch();

if (!$evenement) {
    echo json_encode(['succes' => false, 'message' => 'Événement introuvable.']);
    exit();
}

// ── Vérifier les places disponibles ──
if ($evenement['places_max']) {
    $nb = $pdo->prepare("SELECT COUNT(*) FROM inscriptions_evenements WHERE evenement_id = :id");
    $nb->execute([':id' => $evenement_id]);
    if ((int)$nb->fetchColumn() >= $evenement['places_max']) {
        echo json_encode(['succes' => false, 'message' => 'Désolé, cet événement est complet.']);
        exit();
    }
}

// ── Vérifier si l'email est déjà inscrit à cet événement ──
$check = $pdo->prepare(
    "SELECT id FROM inscriptions_evenements
     WHERE evenement_id = :ev_id AND email = :email LIMIT 1"
);
$check->execute([':ev_id' => $evenement_id, ':email' => $email]);

if ($check->fetch()) {
    echo json_encode(['succes' => false, 'message' => 'Cette adresse email est déjà inscrite à cet événement.']);
    exit();
}

// ── Insérer l'inscription ──
$insert = $pdo->prepare(
    "INSERT INTO inscriptions_evenements
        (evenement_id, utilisateur_id, prenom, nom, email, telephone, statut)
     VALUES
        (:ev_id, :user_id, :prenom, :nom, :email, :tel, 'confirmé')"
);

$insert->execute([
    ':ev_id'   => $evenement_id,
    ':user_id' => estConnecte() ? idConnecte() : null,  // NULL si non connecté
    ':prenom'  => $prenom,
    ':nom'     => $nom,
    ':email'   => $email,
    ':tel'     => $telephone,
]);

// ── Succès ──
echo json_encode([
    'succes'  => true,
    'message' => 'Inscription confirmée ! À bientôt à ' . $evenement['titre'] . '.',
]);
exit();
?>
