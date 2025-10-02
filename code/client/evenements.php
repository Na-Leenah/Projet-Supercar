<?php
session_start();
include("connexion.php");
?>

<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Événements</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
.card-img-top {
    max-height: 200px;
    object-fit: cover;
}
</style>
</head>
<body>

<?php require("menu.php"); ?>

<div class="container py-5">
    <h2 class="text-center mb-5">Nos Événements</h2>
    <div class="row g-4">
    <?php
    // Récupération des événements à venir uniquement
    $sql = "SELECT * FROM evenement WHERE statut='à venir' ORDER BY date_event ASC";
    $res = mysqli_query($bdd, $sql);
    if($res && mysqli_num_rows($res) > 0){
        while($event = mysqli_fetch_assoc($res)){
            ?>
            <div class="col-md-4">
                <div class="card h-100 shadow-sm">
                    <?php if(!empty($event['image'])): ?>
                        <img src="<?php echo htmlspecialchars($event['image']); ?>" class="card-img-top" alt="<?php echo htmlspecialchars($event['evenement']); ?>">
                    <?php endif; ?>
                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title"><?php echo htmlspecialchars($event['evenement']); ?></h5>
                        <p class="card-text"><?php echo nl2br(htmlspecialchars($event['description'])); ?></p>
                        <p class="text-muted mb-3">Date : <?php echo htmlspecialchars($event['date_event']); ?></p>

                        <?php
                        $user_id = isset($_SESSION['iduser']) ? $_SESSION['iduser'] : 0;

                        // Vérifie si l'utilisateur est déjà inscrit
                        $already = false;
                        if($user_id){
                            $sqlCheck = "SELECT * FROM inscriptions_evenement WHERE iduser='$user_id' AND idevent='".$event['idevent']."'";
                            $resCheck = mysqli_query($bdd, $sqlCheck);
                            $already = ($resCheck && mysqli_num_rows($resCheck) > 0);
                        }
                        ?>

                        <?php if($already): ?>
                            <button class="btn btn-success mt-auto" disabled>Déjà inscrit</button>
                        <?php elseif($user_id): ?>
                            <!-- Utilisateur connecté : inscription directe -->
                            <form method="POST" class="mt-auto">
                                <input type="hidden" name="idevent" value="<?php echo $event['idevent']; ?>">
                                <button type="submit" name="inscrire_user" class="btn btn-primary w-100">S'inscrire</button>
                            </form>
                        <?php else: ?>
                            <!-- Non connecté : popup inscription -->
                            <button class="btn btn-primary mt-auto w-100" data-bs-toggle="modal" data-bs-target="#popupInscriptionEvent<?php echo $event['idevent']; ?>">S'inscrire</button>

                            <!-- Modal pour non connecté -->
                            <div class="modal fade" id="popupInscriptionEvent<?php echo $event['idevent']; ?>" tabindex="-1" aria-hidden="true">
                              <div class="modal-dialog">
                                <div class="modal-content">
                                  <form method="POST">
                                    <div class="modal-header">
                                      <h5 class="modal-title">Inscription à l'événement</h5>
                                      <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
                                    </div>
                                    <div class="modal-body">
                                        <input type="hidden" name="idevent" value="<?php echo $event['idevent']; ?>">
                                        <div class="mb-2">
                                            <input type="text" name="nom" placeholder="Nom" class="form-control" required>
                                        </div>
                                        <div class="mb-2">
                                            <input type="text" name="prenom" placeholder="Prénom" class="form-control" required>
                                        </div>
                                        <div class="mb-2">
                                            <input type="email" name="email" placeholder="Email" class="form-control" required>
                                        </div>
                                        <div class="mb-2">
                                            <input type="tel" name="telephone" placeholder="Téléphone" class="form-control" required>
                                        </div>
                                    </div>
                                    <div class="modal-footer d-flex flex-column">
                                        <button type="submit" name="inscrire_guest" class="btn btn-primary w-100 mb-2">Envoyer</button>
                                    </div>
                                  </form>
                                </div>
                              </div>
                            </div>
                        <?php endif; ?>

                    </div>
                </div>
            </div>
            <?php
        }
    } else {
        echo "<p class='text-center'>Aucun événement à venir pour le moment.</p>";
    }
    ?>
    </div>
</div>

<?php require("footer.php"); ?>

<!-- Traitement formulaire -->
<?php
// Utilisateur connecté
if(isset($_POST['inscrire_user'])){
    $idevent = (int)$_POST['idevent'];
    $iduser = $_SESSION['iduser'];

    // Récup nom/prenom depuis utilisateurs
    $sqlUser = "SELECT nom, prenom, email, telephone FROM utilisateurs WHERE iduser='$iduser'";
    $resUser = mysqli_query($bdd, $sqlUser);
    $user = mysqli_fetch_assoc($resUser);

    $nom = mysqli_real_escape_string($bdd, $user['nom']);
    $prenom = mysqli_real_escape_string($bdd, $user['prenom']);
    $email = mysqli_real_escape_string($bdd, $user['email']);
    $telephone = mysqli_real_escape_string($bdd, $user['telephone']);
    $date_inscription = date('Y-m-d H:i:s');

    $sqlInsert = "INSERT INTO inscriptions_evenement (iduser, idevent, nom, prenom, email, telephone, date_inscription)
                  VALUES ('$iduser','$idevent','$nom','$prenom','$email','$telephone','$date_inscription')";
    if(mysqli_query($bdd, $sqlInsert)){
        echo "<script>alert('Inscription réussie !');</script>";
        echo "<script>window.location.href='evenements.php';</script>";
    } else {
        echo "<script>alert('Erreur lors de l\'inscription : ".mysqli_error($bdd)."');</script>";
    }
}

// Utilisateur non connecté (guest)
if(isset($_POST['inscrire_guest'])){
    $idevent = (int)$_POST['idevent'];
    $nom = mysqli_real_escape_string($bdd, $_POST['nom']);
    $prenom = mysqli_real_escape_string($bdd, $_POST['prenom']);
    $email = mysqli_real_escape_string($bdd, $_POST['email']);
    $telephone = mysqli_real_escape_string($bdd, $_POST['telephone']);
    $date_inscription = date('Y-m-d H:i:s');

    $sqlInsert = "INSERT INTO inscriptions_evenement (iduser, idevent, nom, prenom, email, telephone, date_inscription)
                  VALUES (NULL,'$idevent','$nom','$prenom','$email','$telephone','$date_inscription')";
    if(mysqli_query($bdd, $sqlInsert)){
        echo "<script>alert('Inscription réussie !');</script>";
        echo "<script>window.location.href='evenements.php';</script>";
    } else {
        echo "<script>alert('Erreur lors de l\'inscription : ".mysqli_error($bdd)."');</script>";
    }
}
?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
