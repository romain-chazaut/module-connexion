<?php
// Connexion à la base de données
$servername = "localhost";
$username = "root"; 
$password = "Romain-1964"; 
$dbname = "moduleconnexion";

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
            echo "Les mots de passe ne correspondent pas";
            exit;
        }

        // Vérifier les contraintes du mot de passe
        if (
            strlen($password) < 8 ||
            !preg_match('/[A-Z]/', $password) ||
            !preg_match('/[a-z]/', $password) ||
            !preg_match('/\d/', $password) ||
            !preg_match('/[^A-Za-z\d]/', $password)
        ) {
            echo "Le mot de passe ne respecte pas les contraintes";
            exit;
        }

        // Vérifier si le login existe déjà
        $stmt = $conn->prepare("SELECT * FROM utilisateurs WHERE login = :login");
        $stmt->bindParam(':login', $login);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            echo "Ce login est déjà utilisé";
            exit;
        }

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
            echo "Erreur lors de l'inscription";
        }
    }
} catch(PDOException $e) {
    echo "Erreur: " . $e->getMessage();
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
            <input type="password" id="confpassword" name="confpassword">
            <input type="submit" value="S'inscrire">
        </form>
    </div>
</body>
</html>

