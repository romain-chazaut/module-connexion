<?php
session_start();

// Fonction de déconnexion
function logout() {
    // Détruire toutes les variables de session
    session_unset();
    // Détruire la session
    session_destroy();
    // Rediriger vers la page de connexion
    header("Location: connexion.php");
    exit;
}

// Vérifier si l'utilisateur a appuyé sur le bouton de déconnexion
if (isset($_GET['logout'])) {
    logout();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Mon Site</title>
    <link rel="stylesheet" type="text/css" href="CSS/style.css">
</head>
<body>
    <div class="container">
        <h1>Bienvenue sur mon site !</h1>
        <p>Ce site vous permet de créer un compte, de vous connecter et de modifier vos informations personnelles.</p>

        <?php
        // Vérifier si l'utilisateur est connecté
        if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) {
            echo '<p>Connecté en tant que ' . $_SESSION['login'] . '</p>';
            echo '<a href="?logout" class="button custom-button">Déconnexion</a>';
        } else {
            // Afficher les boutons d'inscription et de connexion dans un div de classe 'buttons'
            echo '<div class="buttons">';
            echo '<a href="inscription.php" class="button custom-button">Inscription</a>';
            echo '<a href="connexion.php" class="button custom-button">Connexion</a>';
            echo '</div>';
        }
        ?>
    </div>
</body>
</html>

