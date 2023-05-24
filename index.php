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
    <!-- Lien vers votre fichier CSS externe -->
    <link rel="stylesheet" type="text/css" href="CSS/style.css">
</head>
<body>
    <h1>Bienvenue sur mon site !</h1>
    <p>Ce site vous permet de créer un compte, de vous connecter et de modifier vos informations personnelles.</p>

    <?php
    // Vérifier si l'utilisateur est connecté
    if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) {
        echo '<p>Connecté en tant que ' . $_SESSION['login'] . '</p>';
        echo '<a href="?logout" class="button">Déconnexion</a>';
    } else {
        // Afficher les boutons d'inscription et de connexion
        echo '<a href="inscription.php" class="button">Inscription</a>';
        echo '<a href="connexion.php" class="button">Connexion</a>';
    }
    ?>
</body>
</html>
