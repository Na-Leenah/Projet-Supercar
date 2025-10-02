<?php
// menu.php
if (session_status() === PHP_SESSION_NONE) session_start();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Menu</title>
  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    .menu-bar {
      background-color: #f4f6f8ff; /* fond clair */
      padding: 0.7rem 1rem;
    }
    .menu-bar ul {
      list-style: none;
      margin: 0;
      padding: 0;
      display: flex;
      gap: 1.5rem;
      justify-content: center; /* centrage du menu */
      align-items: center;
      flex: 1;
    }
    .menu-bar li a {
      color: #0d6efd;
      text-decoration: none;
      font-size: 1.05rem;
      transition: color 0.3s;
    }
    .menu-bar li a:hover {
      color: #0b5ed7;
    }
    .menu-right {
      margin-left: auto;
    }
    .navbar-brand {
      color: #0d8ac9ff !important;
      margin-right: 2rem;
    }
  </style>
</head>
<body>

<nav class="menu-bar d-flex align-items-center">
  <a class="navbar-brand fw-bold" href="#">SuperCar</a>

  <ul class="d-flex mb-0">
    <?php
    include "connexion.php";
    $sql = "SELECT nom, lien FROM menu ORDER BY ordre ASC";
    $res = mysqli_query($bdd, $sql);
    $i = 0;
    if ($res && mysqli_num_rows($res) > 0) {
        while ($r = mysqli_fetch_assoc($res)) {
            $i++;
            $nom = htmlspecialchars(trim($r['nom']));
            $lien = htmlspecialchars(trim($r['lien']));
            if (stripos($nom, 'demande') === false && stripos($lien, 'essai') === false) {
                echo '<li><a href="'.$lien.'">'.$nom.'</a></li>';
            }

            if ($i == 2) {
                if(isset($_SESSION['iduser'])) {
                    echo '<li><a href="essai.php">Demande d\'essai</a></li>';
                } else {
                    echo '<li><a href="javascript:void(0)" 
                               data-redirect="essai.php" 
                               data-bs-toggle="modal" 
                               data-bs-target="#popupConnexion">Demande d\'essai</a></li>';
                }
            }
        }
    }
    ?>
  </ul>

  <!-- Bouton à droite -->
  <div class="menu-right">
    <?php if(isset($_SESSION['iduser'])): ?>
      <a href="logout.php" class="btn btn-outline-danger btn-sm">Logout</a>
    <?php else: ?>
      <a class="btn btn-outline-primary btn-sm" href="#" data-bs-toggle="modal" data-bs-target="#popupInscription">Inscription</a>
    <?php endif; ?>
  </div>
</nav>

<!-- Modal Connexion -->
<div class="modal fade" id="popupConnexion" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <form id="loginForm" method="POST" novalidate>
        <div class="modal-header">
          <h5 class="modal-title">Connexion</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
        </div>
        <div class="modal-body">
          <div id="loginError" class="alert alert-danger d-none"></div>

          <div class="mb-2">
            <label class="form-label">Email</label>
            <input type="email" name="email" class="form-control" required>
          </div>

          <div class="mb-2">
            <label class="form-label">Mot de passe</label>
            <input type="password" name="motdepasse" class="form-control" required>
          </div>

          <input type="hidden" name="redirect" id="loginRedirect" value="">
        </div>
        <div class="modal-footer d-flex flex-column">
          <button type="submit" class="btn btn-primary w-100 mb-2">Se connecter</button>
          <small class="text-center">
            Pas encore inscrit ? 
            <a href="#" class="text-primary" data-bs-toggle="modal" data-bs-target="#popupInscription" data-bs-dismiss="modal">Créer un compte</a>
          </small>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Modal Inscription -->
<div class="modal fade" id="popupInscription" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <form id="registerForm" action="utilisateurs.php" method="POST">
        <div class="modal-header">
          <h5 class="modal-title">Inscription</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
        </div>

        <div class="modal-body">
          <input type="text" name="nom" placeholder="Nom" class="form-control mb-2" required>
          <input type="text" name="prenom" placeholder="Prénom" class="form-control mb-2" required>
          <input type="email" name="email" placeholder="Email" class="form-control mb-2" required>
          <input type="tel" name="telephone" placeholder="Téléphone" class="form-control mb-2" required>
          <input type="password" name="motdepasse" placeholder="Mot de passe" class="form-control mb-2" required>
        </div>

        <div class="modal-footer d-flex flex-column">
          <button type="submit" class="btn btn-primary w-100 mb-2">S’inscrire</button>
          <small class="text-center">
            Déjà inscrit ? 
            <a href="#" class="text-primary" data-bs-toggle="modal" data-bs-target="#popupConnexion" data-bs-dismiss="modal">Se connecter</a>
          </small>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.min.js"></script>

<!-- JS pour popup Demande d'essai et login AJAX -->
<script>
document.addEventListener('DOMContentLoaded', function(){
  // Remplir le champ hidden #loginRedirect pour le modal
  document.addEventListener('click', function(e){
    var btn = e.target.closest('[data-redirect]');
    if(!btn) return;
    var redirect = btn.getAttribute('data-redirect') || '';
    var inp = document.getElementById('loginRedirect');
    if(inp) inp.value = redirect;
  });

  // Submit AJAX login
  var loginForm = document.getElementById('loginForm');
  if(loginForm){
    loginForm.addEventListener('submit', function(e){
      e.preventDefault();
      var formData = new FormData(loginForm);
      var errorBox = document.getElementById('loginError');
      errorBox.classList.add('d-none');

      fetch('login.php', { method:'POST', body:formData, credentials:'same-origin' })
        .then(r => r.json())
        .then(data => {
          if(data.status === 'success'){
            var modalEl = document.getElementById('popupConnexion');
            var modal = bootstrap.Modal.getInstance(modalEl);
            if(modal) modal.hide();

            if(data.redirect && data.redirect !== 'reload'){
              window.location.href = data.redirect;
            } else {
              setTimeout(function(){ location.reload(); }, 200);
            }
          } else {
            errorBox.textContent = data.message || 'Erreur de connexion';
            errorBox.classList.remove('d-none');
          }
        })
        .catch(err=>{
          errorBox.textContent = 'Erreur réseau';
          errorBox.classList.remove('d-none');
        });
    });
  }
});
</script>

</body>
</html>
