<?php
// Connexion à la base de données
$servername = "localhost";
$username = "root"; // Remplacez par votre nom d'utilisateur
$password = "Romain-1964"; // Remplacez par votre mot de passe
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
        $confirm_password = $_POST['confirm_password'];

        // Vérifier que les mots de passe correspondent
        if ($password != $confirm_password) {
            echo "Les mots de passe ne correspondent pas";
            exit;
        }

        // Hacher le mot de passe
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Vérifier si le login existe déjà
        $stmt = $conn->prepare("SELECT * FROM utilisateurs WHERE login = :login");
        $stmt->bindParam(':login', $login);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            echo "Ce login est déjà utilisé";
            exit;
        }

        // Insérer l'utilisateur dans la base de données
        $stmt = $conn->prepare("INSERT INTO utilisateurs (login, prenom, nom, password) VALUES (:login, :prenom, :nom, :password)");
        $stmt->bindParam(':login', $login);
        $stmt->bindParam(':prenom', $prenom);
        $stmt->bindParam(':nom', $nom);
        $stmt->bindParam(':password', $hashed_password);

        $stmt->execute();
        echo "Inscription réussie";
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
        <label for="confirm_password">Confirmer le mot de passe:</label>
        <input type="password" id="confirm_password" name="confirm_password">
        <input type="submit" value="S'inscrire">
    </form>
</body>
</html>