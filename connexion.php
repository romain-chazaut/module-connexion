<?php
// Connexion à la base de données
$servername = "localhost";
$username = "root";
$password = "Romain-1964";
$dbname = "moduleconnexion";

session_start();

$errors = array();

try {
    // Créer une connexion
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    // Configurer PDO pour lancer des exceptions en cas d'erreur
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Si le formulaire est soumis
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $login = $_POST['login'];
        $password = $_POST['password'];

        // Vérifier si l'utilisateur existe dans la base de données
        $stmt = $conn->prepare("SELECT * FROM utilisateurs WHERE login = :login");
        $stmt->bindParam(':login', $login);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $hashed_password = $row['password'];

            // Vérifier le mot de passe
            if (password_verify($password, $hashed_password)) {
                // Authentification réussie, créer une session pour l'utilisateur
                $_SESSION['loggedin'] = true;
                $_SESSION['login'] = $row['login'];
                $_SESSION['prenom'] = $row['prenom'];
                $_SESSION['nom'] = $row['nom'];

                // Rediriger vers la page de profil ou la page d'admin en fonction du login
                if ($_SESSION['login'] === 'admin') {
                    header("Location: admin.php");
                } else {
                    header("Location: profil.php");
                }
                exit;
            } else {
                $errors[] = "Mot de passe incorrect";
            }
        } else {
            $errors[] = "Login incorrect";
        }
    }
} catch (PDOException $e) {
    $errors[] = "Erreur: " . $e->getMessage();
}

$conn = null;
?>

<!DOCTYPE html>
<html>
<head>
    <title>Connexion</title>
    <link rel="stylesheet" type="text/css" href="CSS/style.css">
</head>
<body>
    <div class="container">
        <h1>Connexion</h1>
        <?php if (!empty($errors)) : ?>
            <div class="errors">
                <?php foreach ($errors as $error) : ?>
                    <p><?php echo $error; ?></p>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
        <form method="POST" action="connexion.php">
            <label for="login">Login:</label>
            <input type="text" id="login" name="login">
            <label for="password">Mot de passe:</label>
            <input type="password" id="password" name="password"><br>
            <input type="submit" value="Se connecter" class="button"><br>
            <a href="index.php" class="button">Retour à l'accueil</a>
        </form>
    </div>
</body>
</html>
