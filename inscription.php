<?php
// Connexion à la base de données
$servername = "localhost";
$username = "root";
$password = "Romain-1964";
$dbname = "moduleconnexion";

$errors = array();

try {
    // Créer une connexion
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    // Configurer PDO pour lancer des exceptions en cas d'erreur
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Si le formulaire est soumis
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $login = $_POST['login'];
        $prenom = $_POST['prenom'];
        $nom = $_POST['nom'];
        $password = $_POST['password'];
        $confirm_password = $_POST['confpassword'];

        // Vérifier que les mots de passe correspondent
        if ($password != $confirm_password) {
            $errors[] = "Les mots de passe ne correspondent pas";
        }

        // Vérifier les contraintes du mot de passe
        if (
            strlen($password) < 8 ||
            !preg_match('/[A-Z]/', $password) ||
            !preg_match('/[a-z]/', $password) ||
            !preg_match('/\d/', $password) ||
            !preg_match('/[^A-Za-z\d]/', $password)
        ) {
            $errors[] = "Le mot de passe doit contenir au moins 8 caractères, une majuscule, un caractère spécial, et un chiffre";
        }

        // Vérifier si le login existe déjà
        $stmt = $conn->prepare("SELECT * FROM utilisateurs WHERE login = :login");
        $stmt->bindParam(':login', $login);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $errors[] = "Ce login est déjà utilisé";
        }

        // Si aucune erreur n'est présente, procéder à l'inscription
        if (empty($errors)) {
            // Hacher le mot de passe
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            // Insérer l'utilisateur dans la base de données
            $stmt = $conn->prepare("INSERT INTO utilisateurs (login, prenom, nom, password) VALUES (:login, :prenom, :nom, :password)");
            $stmt->bindParam(':login', $login);
            $stmt->bindParam(':prenom', $prenom);
            $stmt->bindParam(':nom', $nom);
            $stmt->bindParam(':password', $hashed_password);

            if ($stmt->execute()) {
                // Redirection vers la page de connexion
                header("Location: connexion.php");
                exit;
            } else {
                $errors[] = "Erreur lors de l'inscription";
            }
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
    <title>Inscription</title>
    <link rel="stylesheet" type="text/css" href="CSS/style.css">
</head>
<body>
    <div class="container">
        <h1>Inscription</h1>
        <?php if (!empty($errors)) : ?>
            <div class="errors">
                <?php foreach ($errors as $error) : ?>
                    <p><?php echo $error; ?></p>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
        <form method="POST" action="inscription.php">
            <label for="login">Login:</label>
            <input type="text" id="login" name="login">
            <label for="prenom">Prénom:</label>
            <input type="text" id="prenom" name="prenom">
            <label for="nom">Nom:</label>
            <input type="text" id="nom" name="nom">
            <label for="password">Mot de passe:</label>
            <input type="password" id="password" name="password">
            <label for="confpassword">Confirmer le mot de passe:</label>
            <input type="password" id="confpassword" name="confpassword"><br>
            <input type="submit" value="S'inscrire" class="button"><br>
            <a href="index.php" class="button">Retour à l'accueil</a>
        </form>
    </div>
</body>
</html>
