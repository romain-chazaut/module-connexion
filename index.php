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

        <?php if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) : ?>
            <p>Connecté en tant que <?php echo $_SESSION['login']; ?></p>
            <a href="?logout" class="button">Déconnexion</a><br>
            <a href="profil.php" class="button">Profil</a>
        <?php else : ?>
            <div class="buttons">
                <a href="inscription.php" class="button">Inscription</a><br>
                <a href="connexion.php" class="button">Connexion</a>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>
