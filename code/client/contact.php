<?php

// Connexion à la base de données
include 'connexion.php';

// Initialiser un message de confirmation ou d'erreur
$feedback = "";

// Vérifier si le formulaire est soumis
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sécuriser les données reçues
    $nom       = mysqli_real_escape_string($bdd, $_POST['nom']);
    $prenom    = mysqli_real_escape_string($bdd, $_POST['prenom']);
    $email     = mysqli_real_escape_string($bdd, $_POST['email']);
    $telephone = mysqli_real_escape_string($bdd, $_POST['telephone']);
    $message   = mysqli_real_escape_string($bdd, $_POST['message']);

    // Préparer la requête d'insertion
    $sql = "INSERT INTO contact (nom, prenom, email, telephone, message)
            VALUES ('$nom', '$prenom', '$email', '$telephone', '$message')";

    if (mysqli_query($bdd, $sql)) {
        $feedback = "✅ Votre message a bien été envoyé. Merci";
    } else {
        $feedback = "❌ Erreur : " . mysqli_error($bdd);
    }
}

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <meta charset="UTF-8">
    <title>Contactez-nous</title>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.min.js" crossorigin="anonymous"></script>
    <style>
        .contact-container {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 50px;
            padding: 55px;
            flex-wrap: wrap;
        }
        form {
            width: 650px;
            padding: 10px;
            border-radius: 4px;
            background: #f9f9f9;
        }
        input, textarea {
            width: 100%;
            padding: 8px;
            margin: 6px 0;
            border-radius: 4px;
            border: 1px solid #ccc;
        }
        button {
            background: #333;
            color: white;
            padding: 10px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        button:hover { background: #555; }
        .feedback { text-align: center; margin: 10px 0; }
        iframe {
            flex: 1;
            width: 90px;
            height: 475px;
            border: 0;
            border-radius: 4px;
            box-shadow: 0 2px 6px rgba(0,0,0,0.1);
        }
    </style>
</head>
<body>
    
    <?php require 'menu.php'; ?>
    
    <h2 class="text-center mt-4">Contactez-nous pour plus d'informations</h2>
    <p class="text-center mt-4">N'hésitez pas à soumettre votre demande et à nous rendre visite dans nos locaux.</p>
    
    <?php if ($feedback != ""): ?>
    <div class="feedback"><?php echo $feedback; ?></div>
    <?php endif; ?>

    <div class="contact-container">
        <form method="post" action="">
            <label>Nom :</label>
            <input type="text" name="nom" required>
                
            <label>Prénom :</label>
            <input type="text" name="prenom" required>
                
            <label>Email :</label>
            <input type="email" name="email" required>
                
            <label>Téléphone :</label>
            <input type="text" name="telephone">
                
            <label>Message :</label>
            <textarea name="message" rows="5" required></textarea>
                
            <button type="submit">Envoyer</button>
        </form>

        <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3742.78955389585!2d57.491997775009814!3d-20.267560781198082!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x217c5b6d0efff577%3A0x1ed7568ddf21818e!2sPorsche%20Centre%20Mauritius!5e0!3m2!1sfr!2smu!4v1757776138558!5m2!1sfr!2smu" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
    </div>
        
    <?php require 'footer.php'; ?>

</body>
</html>
